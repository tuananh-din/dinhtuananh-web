# E-01 — Sửa `RouteServiceProvider::HOME` `/home` → `/admin` (A10)

## Overview
- Priority: P1 | Status: Pending | Migration: KHÔNG | Đổi route cũ: KHÔNG

## Key Insights (đã verify)
- `app/Providers/RouteServiceProvider.php:20` — `public const HOME = '/home';` nhưng route `/home` KHÔNG tồn tại trong `routes/web.php`.
- `app/Http/Middleware/RedirectIfAuthenticated.php:24` — `return redirect(RouteServiceProvider::HOME);` → user ĐÃ đăng nhập mà mở `/login` sẽ bị redirect về `/home` → 404. Bug thật, tái hiện được.
- `LoginController::send` (dòng 29) đã dùng `redirect()->intended('/admin')` — không bị ảnh hưởng, chỉ middleware guest dùng HOME.

## Requirements
- User đã đăng nhập mở `/login` phải về `/admin`, không 404.

## Related Code Files
- Sửa: `app/Providers/RouteServiceProvider.php` (1 dòng).
- Tạo/Xoá: không.

## Implementation Steps
1. Đổi `public const HOME = '/home';` thành `public const HOME = '/admin';`.
2. Không cần clear cache local (config không cache ở dev).

## Todo
- [ ] Sửa hằng HOME
- [ ] Test tay theo Success Criteria
- [ ] Commit: `fix: A10 RouteServiceProvider HOME tro ve /admin`

## Success Criteria (test tay)
1. Đăng nhập tại http://127.0.0.1:8000/login → vào /admin bình thường.
2. Đang đăng nhập, mở lại http://127.0.0.1:8000/login → redirect http://127.0.0.1:8000/admin (không còn 404 `/home`).
3. Logout → mở /login vẫn hiện form bình thường.

## Risk Assessment
- Rủi ro: gần như 0 — hằng chỉ được dùng ở `RedirectIfAuthenticated`. Rollback: revert 1 dòng.

## Security Considerations
- Không thay đổi auth logic; `/admin` đã nằm sau middleware `auth`.

## Next Steps
- Độc lập, không blocker cho mục nào.
