# C-06: Giảm query trùng của view composer

## Overview
- **Priority:** P2
- **Status:** Pending
- **Migration:** Không
- `AppServiceProvider::boot()` đăng ký `view()->composer('*')` gọi `About::first()` + `Setting::first()`. Composer `'*'` chạy cho MỖI view được render — mỗi request render master + header + footer + content view (+ mỗi partial) → 2 query x số view = 8-10 query trùng lặp mỗi page load. Fix: query 1 lần/request bằng memoization đơn giản.

## Key Insights (từ đọc code)
- `app/Providers/AppServiceProvider.php:29-32` — composer `'*'` với 2 query mỗi lần chạy.
- `$infor` (Setting) dùng ở: master, blogs, contact, courses... ; `$contact` (About) dùng ở: header, footer. Cả hai gần như bất biến giữa các request (chỉ đổi khi admin update setting/profile).
- Giải pháp KISS nhất, không thêm dependency, không cache file/redis (YAGNI): dùng biến static trong closure — query lần đầu, các lần sau trong cùng request dùng lại.
- KHÔNG dùng `Cache::remember` xuyên request ở phase này — tránh rủi ro admin update setting mà site không thấy đổi (phải bàn invalidation, ngoài scope thay đổi nhỏ).

## Requirements
- Mỗi HTTP request chỉ query bảng `setting` và `about` đúng 1 lần.
- Hành vi hiển thị không đổi (kể cả khi bảng rỗng → giữ nguyên giá trị null như hiện tại).

## Related Code Files
- **Sửa:** `app/Providers/AppServiceProvider.php` (~6 dòng)
- **Tạo/Xóa:** Không

## Implementation Steps
1. Sửa closure composer thành dạng memoize:
   ```php
   view()->composer('*', function ($view) {
       // C-6: query 1 lần/request, các view sau dùng lại (null-safe khi bảng rỗng).
       static $contact = false, $infor = false;
       if ($contact === false) { $contact = About::first(); }
       if ($infor === false)   { $infor = Setting::first(); }
       $view->with('contact', $contact);
       $view->with('infor', $infor);
   });
   ```
   Lưu ý dùng `false` làm sentinel (không dùng `null`) vì `About::first()` có thể trả null hợp lệ.
2. Xác nhận không nơi nào mutate `$infor`/`$contact` trong view (đã grep — chỉ đọc qua `data_get`).

## Todo
- [ ] Sửa AppServiceProvider theo pattern memoize
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. Bật query log tạm (hoặc Laravel Debugbar nếu có; không thì `DB::enableQueryLog()` + `dd(DB::getQueryLog())` tạm trong route test rồi gỡ): trang chủ chỉ còn 1 query `setting` + 1 query `about`.
2. Mọi trang public hiển thị y hệt trước đó (tên brand, logo, phone, email ở header/footer).
3. Vào admin sửa Setting name → F5 trang chủ thấy tên mới (vì memoize chỉ sống trong 1 request).

## Risk Assessment
- **Thấp.** Static trong closure sống theo process; với PHP-FPM/Apache mod_php mỗi request là vòng đời riêng — an toàn. LƯU Ý: nếu sau này chạy Octane/long-running process thì static sẽ dính giữa request → khi đó phải đổi sang per-request container binding (ghi chú lại, hiện tại Laragon không ảnh hưởng).
- Rollback: revert 1 commit.

## Security Considerations
- Không có input người dùng, không đổi quyền truy cập.
