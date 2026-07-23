# C-01: Throttle (rate limit) cho lead form

## Overview
- **Priority:** P0
- **Status:** Pending
- **Migration:** Không
- Honeypot (B-4) chỉ chặn bot ngu. Bot thông minh hơn hoặc kẻ phá hoại vẫn có thể POST `/lead/store` hàng loạt → rác bảng `leads`. Thêm middleware `throttle` của Laravel — thay đổi 1 dòng route, an toàn tuyệt đối.

## Key Insights (từ đọc code)
- `routes/web.php:40`: `Route::post('/lead/store',...)` — KHÔNG có middleware throttle.
- `app/Http/Kernel.php:65` đã đăng ký sẵn alias `'throttle' => ThrottleRequests::class` — chỉ cần dùng, không cần cấu hình thêm.
- `LeadController@store` đã có honeypot + validate; throttle là lớp bảo vệ bổ sung, không đụng logic cũ.
- Khi bị throttle, Laravel trả HTTP 429 (trang mặc định) — chấp nhận được ở mức Phase C.

## Requirements
- Giới hạn 5 request/phút/IP cho `POST /lead/store`.
- Không thay đổi hành vi khi user submit bình thường (1-2 lần).

## Related Code Files
- **Sửa:** `routes/web.php` (1 dòng)
- **Tạo/Xóa:** Không

## Implementation Steps
1. Sửa `routes/web.php` dòng 40 thành:
   `Route::post('/lead/store',[LeadController::class,'store'])->middleware('throttle:5,1')->name('lead.store');`
2. Chạy `php artisan route:list --name=lead.store` xác nhận middleware đã gắn.

## Todo
- [ ] Thêm `->middleware('throttle:5,1')` vào route lead.store
- [ ] Verify bằng `php artisan route:list`
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. Mở `/contact`, submit form hợp lệ → vẫn thấy toastr "Đăng ký thành công", lead lưu vào DB.
2. Submit liên tiếp >5 lần trong 1 phút → nhận trang 429 Too Many Requests.
3. Đợi 1 phút → submit lại được bình thường.

## Risk Assessment
- **Thấp.** Rủi ro duy nhất: user thật submit nhiều lần do lỗi mạng bị chặn — ngưỡng 5/phút đủ rộng.
- Rollback: xóa `->middleware(...)` là về nguyên trạng.

## Security Considerations
- Throttle theo IP; sau reverse proxy cần trust proxy đúng (local Laragon không ảnh hưởng).
- Không log dữ liệu nhạy cảm mới, không đổi validate cũ.
