# Deployment Guide — tanh (Laravel)

Domain production: **https://dinhtuananh.com**

## 0. Preflight bắt buộc và bằng chứng kiểm tra

> Không deploy chỉ vì checklist này đã được viết. Người có quyền hạ tầng phải thực hiện và lưu bằng chứng ở nơi an toàn (ticket/runbook nội bộ), **không** ghi secret, token, mật khẩu, địa chỉ mailbox riêng hoặc dữ liệu lead vào Git.

- [ ] Xác nhận credential từng xuất hiện trong file tracked đã được owner kiểm tra/rotate nếu còn hiệu lực.
- [ ] Xác nhận `.env` production không nằm trong Git; `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` đúng domain.
- [ ] Xác nhận backup DB mới nhất có thể khôi phục ở môi trường an toàn trước migration có schema.
- [ ] Xác nhận migration đang chờ, dung lượng storage và quyền ghi `storage/`.
- [ ] Xác nhận SMTP và `LEAD_NOTIFY_EMAIL` bằng mailbox được phép test; không paste credential vào source/log/ticket công khai.
- [ ] Xác nhận kế hoạch rollback code/schema và người chịu trách nhiệm thực hiện.

## 1. Checklist deploy prod (theo thứ tự)

1. **Backup DB prod** trước mọi thay đổi schema:
   ```
   mysqldump -u <user> -p <db_name> > backup-$(date +%Y%m%d-%H%M).sql
   ```
2. **Pull code** nhánh `master` (hoặc merge lên nhánh deploy).
3. **`.env` prod** — set tối thiểu:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://dinhtuananh.com
   ```
   > `APP_URL` bắt buộc đúng host để `route()`/`url()` trong sitemap.xml + canonical + OG sinh đúng link tuyệt đối.
4. **Chạy migration** (Phase D + E có schema mới):
   ```
   php artisan migrate --force
   ```
   Gồm: `add_unique_index_to_blogs_slug` (D-01), `add_social_columns_to_about_table` (E-05). Quên bước này → lưu admin profile vẫn lỗi 500 trên prod.
5. **Cache config/route/view** (chỉ prod):
   ```
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
   > Sau khi `config:cache`, mọi lần đổi `.env` phải chạy lại `config:cache` (hoặc `config:clear`).
6. **Storage link** (ảnh upload qua `/storage/`):
   ```
   php artisan storage:link
   ```
7. **Xoá blog seed test** nếu không cần:
   ```sql
   DELETE FROM blogs WHERE slug LIKE 'bai-mau-%';
   ```
8. **Smoke test:** `/`, `/about`, `/courses`, `/{slug}.html`, `/sitemap.xml` (text/xml), URL sai → 404 brand; đăng nhập admin → lưu profile không 500.

## 2. Cấu hình email thông báo lead (D-05)

Code đã xong: khi có lead mới, gửi `NewLeadNotification` tới `config('mail.lead_notify')`. Nếu gửi lỗi → chỉ log, KHÔNG chặn lưu lead.

`config/mail.php` (dòng ~115):
```php
'lead_notify' => env('LEAD_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
```

### Cần set trong `.env` (thay giá trị thật):
```
MAIL_MAILER=smtp
MAIL_HOST=<smtp-host>           # vd smtp.gmail.com / smtp.mailgun.org
MAIL_PORT=587                   # 587 (TLS) hoặc 465 (SSL)
MAIL_USERNAME=<smtp-user>
MAIL_PASSWORD=<smtp-pass>       # Gmail: dùng App Password, KHÔNG dùng mật khẩu chính
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@dinhtuananh.com"
MAIL_FROM_NAME="Đinh Tuấn Anh"
LEAD_NOTIFY_EMAIL="<email-nhan-thong-bao>"
```

> **Hiện trạng local:** `MAIL_HOST=mailpit`, `MAIL_PORT=1025` (mail catcher, không gửi ra ngoài). Chưa có `LEAD_NOTIFY_EMAIL`.

### Test sau khi cấu hình:
```
php artisan config:clear
php artisan tinker --execute="Mail::raw('test lead', fn(\$m)=>\$m->to(config('mail.lead_notify'))->subject('Test')); echo 'sent';"
```
Hoặc submit thử 1 form lead trên site rồi kiểm hộp thư. **DO NOT** commit `.env` (đã trong `.gitignore`).

Ghi nhận cả nhánh lỗi: tạm thời dùng SMTP/mailbox test không hợp lệ hoặc ngắt kết nối ở staging, submit lead và xác nhận lead vẫn được lưu còn lỗi email chỉ có trong log. Không làm thử nghiệm này trên production nếu chưa có kế hoạch và quyền thực hiện.

## 3. Lệnh chạy local (dev/QC)

- Start MySQL (Laragon): `Start-Process 'C:\App\laragon\bin\mysql\...\mysqld.exe' -ArgumentList '--defaults-file=...my.ini' -WindowStyle Hidden`
- Serve: `php artisan serve --host=127.0.0.1 --port=8000` → http://127.0.0.1:8000
- PHP: `C:/App/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe`

## 4. Rollback nhanh

- Code: `git revert <hash>` (mỗi mục 1 commit riêng).
- Schema E-05: `php artisan migrate:rollback --step=1` (drop 4 cột social, data cột cũ nguyên vẹn).
