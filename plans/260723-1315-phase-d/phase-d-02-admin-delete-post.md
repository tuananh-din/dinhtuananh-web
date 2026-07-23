# D-02 — Đổi admin delete từ GET sang POST (6 module)

## Overview

- **Priority:** P0
- **Status:** Pending — **CẦN USER XÁC NHẬN** (đổi cấu trúc route cũ khu admin)
- **Migration:** KHÔNG
- Delete bằng GET có thể bị kích hoạt qua CSRF (link độc/prefetch) → xoá dữ liệu ngoài ý muốn. Đổi sang POST + @csrf.

## Key Insights (đã verify từ code)

- `routes/web.php` — 6 route GET delete: dòng 62 (blog), 70 (course), 83 (testimonial), 91 (service), 99 (skill), 107 (image). Tất cả nằm trong middleware `auth` nên chỉ admin login mới xoá được, nhưng GET vẫn dính CSRF vì không có token.
- Views dùng `<a href="{{ route('xxx.delete', $id) }}">` + `onclick confirm`:
  - `resources/views/admin/blog/index.blade.php:37`
  - `resources/views/admin/course/index.blade.php:45`
  - `resources/views/admin/image/index.blade.php:42`
  - `resources/views/admin/service/index.blade.php:37`
  - `resources/views/admin/testimonial/index.blade.php:40`
  - `resources/views/admin/skill/index.blade.php:39`
- **Bug kèm theo:** `Admin/BlogController.php:56-57` — `$n = Blog::where('id',$id)->first(); $n->delete();` → 500 nếu id không tồn tại (CourseController:115-124 đã xử lý đúng, dùng làm mẫu).

## Requirements

1. 6 route delete đổi `Route::get` → `Route::post` (giữ nguyên URI và tên route → view chỉ đổi markup, không đổi `route()` name).
2. 6 view index: thay `<a>` bằng `<form method="POST">` + `@csrf` + nút submit, giữ `onsubmit confirm` và style nút hiện tại.
3. Sửa `Admin\BlogController::delete` chống null (theo pattern CourseController::delete).

## Related Code Files

- **Sửa:** `routes/web.php` (6 dòng)
- **Sửa:** 6 file `resources/views/admin/{blog,course,image,service,testimonial,skill}/index.blade.php`
- **Sửa:** `app/Http/Controllers/Admin/BlogController.php` (method `delete`)
- Kiểm tra thêm các controller delete khác (Testimonial/Service/Skill/Image) có null-safe không, sửa cùng pattern nếu chưa.

## Implementation Steps

1. `routes/web.php`: đổi 6 `Route::get('/delete/{id}'...)` → `Route::post('/delete/{id}'...)`. Giữ nguyên path + name.
2. Mỗi view index: thay block `<a href delete>` bằng:
   ```blade
   <form action="{{ route('xxx.delete', $row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
       @csrf
       <button type="submit" class="btn btn-icon btn-danger btn-rounded btn-tone"><i class="fas fa-trash"></i></button>
   </form>
   ```
3. Sửa `Admin\BlogController::delete`: nếu không tìm thấy → redirect back với thông báo lỗi thay vì crash.
4. Rà 4 controller delete còn lại, thêm null-check nếu thiếu (thay đổi nhỏ, cùng commit).
5. Test tay từng module.

## Todo

- [ ] Đổi 6 route GET → POST
- [ ] Sửa 6 view index sang form POST + @csrf
- [ ] Null-safe `Admin\BlogController::delete`
- [ ] Rà null-safe 4 controller delete còn lại
- [ ] Test tay 6 module + test truy cập GET cũ trả 405

## Success Criteria

- Login admin `http://127.0.0.1:8000/admin`, vào từng module (blog/course/testimonial/service/skill/image): nút xoá hiện confirm → OK → bản ghi xoá, redirect về danh sách, không lỗi.
- Gõ tay URL GET `http://127.0.0.1:8000/admin/blog/delete/1` → 405 Method Not Allowed (mong đợi, chứng minh GET đã bị chặn).
- Xoá id không tồn tại (POST tay id 99999) → báo lỗi lịch sự, không 500.
- Style nút xoá không vỡ layout bảng.

## Risk Assessment

- **Bookmark/link cũ tới URL delete GET sẽ 405** (khả năng: thấp / ảnh hưởng: thấp — chỉ admin dùng) → chấp nhận, đây chính là mục tiêu.
- **Form lồng trong bảng làm lệch style nút** → Mitigation: `display:inline`, test mắt từng trang.
- **Rollback:** revert 1 commit (route + views + controller cùng commit, không có migration).

## Security Considerations

- POST + `@csrf` → Laravel VerifyCsrfToken chặn request giả mạo cross-site.
- Route vẫn trong middleware `auth` — không đổi quyền truy cập.
