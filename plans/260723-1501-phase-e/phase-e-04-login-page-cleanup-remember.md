# E-04 — Dọn trang login: bỏ footer template, title theo brand, thêm remember (A13)

## Overview
- Priority: P1 | Status: Pending | Migration: KHÔNG | Đổi route: KHÔNG

## Key Insights (đã verify) — `resources/views/login.blade.php`
- Dòng 7: `<title>NVH - Admin Dashboard Template</title>` — title template mặc định.
- Dòng 46: link "Forget Password?" `href=""` — link chết (không có route reset password).
- Dòng 63-73: footer "© 2019 ThemeNate" + link Legal/Privacy rỗng — rác template.
- Thiếu checkbox `remember` dù `LoginController::send:23` đọc `$request->boolean('remember')` → tính năng remember hiện không bao giờ kích hoạt được.
- Dòng 21: background `url('assets/images/others/login-3.png')` — đường dẫn tương đối không bọc `asset()` (kiểm tra file có tồn tại trong `public/app/assets/...` không; nếu có thì sửa luôn bằng `asset()`, nếu không thì bỏ style này).

## Requirements
- Trang login sạch, đúng brand, có remember me hoạt động; không đổi route/controller.

## Related Code Files
- Sửa: `resources/views/login.blade.php` (duy nhất).

## Implementation Steps
1. Title: `Đăng nhập | {{ data_get($infor, 'name', 'Admin') }}` (view composer đã share `$infor` cho mọi view).
2. Xoá link "Forget Password?" (dòng 46) — không có tính năng reset, giữ link chết chỉ gây nhầm.
3. Xoá block footer template dòng 63-73 (ThemeNate/Legal/Privacy).
4. Thêm checkbox trước nút Sign In: `<input type="checkbox" name="remember" id="remember" value="1"> <label for="remember">Ghi nhớ đăng nhập</label>`.
5. Sửa background dòng 21: bọc `asset('app/assets/images/others/login-3.png')` nếu file tồn tại, ngược lại bỏ inline style.

## Todo
- [ ] Sửa login.blade.php (5 điểm trên)
- [ ] Test tay theo Success Criteria
- [ ] Commit: `fix: A13 don trang login + checkbox remember`

## Success Criteria (test tay)
1. http://127.0.0.1:8000/login: title tab = "Đăng nhập | {tên site}", không còn ThemeNate/© 2019/Forget Password.
2. Đăng nhập KHÔNG tick remember → đóng browser (hết session) → phải login lại.
3. Đăng nhập CÓ tick remember → cookie `remember_web_*` xuất hiện (DevTools > Application > Cookies).
4. Layout không vỡ trên mobile width (~375px).

## Risk Assessment
- Thấp: chỉ 1 view, không đụng logic. Rollback: revert file.

## Security Considerations
- Remember token do Laravel quản lý (cột `remember_token` bảng `users` đã có từ migration mặc định — verified `2014_10_12_000000`). Logout hiện tại (`Auth::logout`) tự vô hiệu remember cookie.

## Next Steps
- Độc lập.
