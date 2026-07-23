# D-04 — Throttle route POST login (chống brute-force)

## Overview

- **Priority:** P1 (nhỏ nhất Phase D, làm đầu tiên)
- **Status:** Pending (không cần xác nhận — thêm middleware, không đổi URI/schema)
- **Migration:** KHÔNG
- Trang `/login` là cửa vào admin. Hiện POST login KHÔNG có rate limit → bot có thể brute-force mật khẩu không giới hạn.

## Key Insights (đã verify từ code)

- `routes/web.php:46` — `Route::post('login',[LoginController::class,'send'])->name('login.send');` KHÔNG có `throttle`.
- So sánh: `routes/web.php:41` — lead form ĐÃ có `->middleware('throttle:5,1')` (C-01) → dùng đúng pattern đó (DRY, đã chứng minh chạy tốt).
- `LoginController::send` (dòng 15-38) validate + `Auth::attempt` chuẩn, có session regenerate — chỉ thiếu rate limit.

## Requirements

1. Thêm `->middleware('throttle:5,1')` vào route `login.send` (5 lần/phút/IP — đủ cho người thật gõ nhầm, chặn bot).
2. Không đổi URI, tên route, controller.

## Related Code Files

- **Sửa:** `routes/web.php` (1 dòng, route `login.send`)

## Implementation Steps

1. `routes/web.php:46` → `Route::post('login',[LoginController::class,'send'])->middleware('throttle:5,1')->name('login.send');`
2. `php artisan route:list --name=login` xác nhận middleware gắn đúng.
3. Test tay.

## Todo

- [ ] Thêm throttle middleware
- [ ] `route:list` verify
- [ ] Test tay 6 lần submit sai → 429

## Success Criteria

- `http://127.0.0.1:8000/login` submit sai mật khẩu 5 lần → lần 6 trong cùng 1 phút trả **429 Too Many Requests**.
- Đợi hết 1 phút → login đúng bình thường, vào được `/admin`.
- Login đúng ngay lần đầu → không bị ảnh hưởng.

## Risk Assessment

- **Admin thật gõ sai 5 lần bị khoá 1 phút** (khả năng: thấp / ảnh hưởng: rất thấp — chờ 60s) → chấp nhận.
- **Nhiều người sau NAT chung IP** → site cá nhân, chỉ 1 admin → không đáng kể.
- **Rollback:** revert 1 dòng.

## Security Considerations

- Throttle theo IP là lớp chặn brute-force cơ bản; kết hợp session regenerate sẵn có là đủ cho site 1 admin (YAGNI: không cần lockout account/captcha lúc này).
