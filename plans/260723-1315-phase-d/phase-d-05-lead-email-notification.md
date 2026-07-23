# D-05 — Email báo lead mới cho admin

## Overview

- **Priority:** P2
- **Status:** Pending — **CẦN USER XÁC NHẬN** (gửi mail ra ngoài, cần SMTP credentials)
- **Migration:** KHÔNG
- Lead hiện chỉ lưu DB (`LeadController::store`) → admin phải tự vào `/admin/lead` mới thấy. Với site tư vấn khoá học, phản hồi chậm = mất khách. Email báo ngay khi có lead mới.

## Key Insights (đã verify từ code)

- `app/Http/Controllers/LeadController.php:36-44` — chỉ `Lead::create()`, không notify.
- Đã có 2 lớp chống spam: honeypot (dòng 15-17) + throttle 5/phút (`routes/web.php:41`) → mail chỉ gửi cho lead thật đã qua lọc, rủi ro spam mail thấp.
- Bảng `about` có cột `email` (migration `2026_01_05_084900:39`) → có thể dùng làm địa chỉ nhận, fallback về config nếu trống.
- Chưa xác nhận cấu hình MAIL trong `.env` (file bị privacy-block, không đọc — hỏi user).

## Requirements

1. Mailable `NewLeadNotification` (queue KHÔNG cần — YAGNI, site nhỏ, gửi sync; bọc try/catch).
2. Gửi tới email admin (ưu tiên: config `mail.from`/biến env riêng `LEAD_NOTIFY_EMAIL`; đơn giản, không thêm cột DB).
3. **Mail fail KHÔNG được chặn lưu lead** — try/catch quanh `Mail::send`, log lỗi, user vẫn thấy success.
4. Nội dung mail: name, phone, email, message, source_page, tên khoá học (nếu có), link tới `/admin/lead`.

## Related Code Files

- **Tạo:** `app/Mail/NewLeadNotification.php`, `resources/views/emails/new-lead.blade.php`
- **Sửa:** `app/Http/Controllers/LeadController.php` (thêm gửi mail sau `Lead::create`)
- **Sửa (user tự làm):** `.env` — MAIL_MAILER/HOST/PORT/USERNAME/PASSWORD/FROM + `LEAD_NOTIFY_EMAIL`

## Implementation Steps

1. **Hỏi user:** SMTP nào (Gmail app password? Mailgun? hay tạm `MAIL_MAILER=log` để test)? Email nhận thông báo?
2. Tạo Mailable: `php artisan make:mail NewLeadNotification` + view text/HTML đơn giản (không cần template đẹp).
3. `LeadController::store`: sau `Lead::create`, `try { Mail::to(config('mail.lead_notify'))->send(new NewLeadNotification($lead)); } catch (\Throwable $e) { Log::error(...); }`.
4. Thêm key `lead_notify` vào `config/mail.php` đọc từ env.
5. Test với `MAIL_MAILER=log` trước (mail ghi vào `storage/logs/laravel.log`) → user duyệt nội dung → mới chuyển SMTP thật.

## Todo

- [ ] User xác nhận SMTP + email nhận
- [ ] Mailable + view email
- [ ] Gắn vào LeadController (try/catch)
- [ ] Test log driver
- [ ] Chuyển SMTP thật + test end-to-end

## Success Criteria

- Với `MAIL_MAILER=log`: submit form `http://127.0.0.1:8000/contact` → lead lưu DB + nội dung mail xuất hiện trong `storage/logs/laravel.log`, đủ name/phone/message.
- Cố tình cấu hình SMTP sai: submit form → lead VẪN lưu, user VẪN thấy toast success, lỗi ghi vào log.
- Với SMTP thật: mail về hộp thư admin trong ~1 phút.
- Honeypot trigger → KHÔNG gửi mail (return sớm trước khi create).

## Risk Assessment

- **SMTP chậm làm form chờ lâu** (khả năng: trung bình / ảnh hưởng: trung bình — sync gửi) → chấp nhận ở quy mô hiện tại; nếu chậm thực tế mới cân nhắc queue (YAGNI).
- **Credentials lộ** → chỉ nằm `.env`, không commit (đã có rule pre-commit).
- **Spam mail nếu bot vượt honeypot** → throttle 5/phút giới hạn thiệt hại; theo dõi sau khi bật.
- **Rollback:** revert commit + xoá env keys — lead vẫn lưu DB như cũ.

## Security Considerations

- Không đưa dữ liệu user vào header mail (subject cố định) — tránh header injection; body render qua Blade escape.
- Không log nội dung PII của lead khi mail fail — chỉ log message lỗi.
