# D-01 — Unique slug cho bảng `blogs` + sửa logic slug admin

## Overview

- **Priority:** P0
- **Status:** Pending — **CẦN USER XÁC NHẬN** (đổi schema DB)
- **Migration:** CÓ (thêm unique index `blogs.slug`)
- Trùng slug → `firstOrFail` lấy bài sai/bài cũ. Đồng thời admin store hiện không validate và regenerate slug mỗi lần edit → đổi URL bài đã publish (mất SEO).

## Key Insights (đã verify từ code)

- `database/migrations/2026_01_05_084900_create_database_table.php:67` — `$table->string('slug');` KHÔNG unique.
- `app/Http/Controllers/Admin/BlogController.php:45` — `'slug' => Str::slug($request->title)`: không check trùng, regenerate cả khi edit.
- `app/Http/Controllers/Admin/BlogController.php:25-54` — store KHÔNG có `$request->validate()` (title trống → slug rỗng → URL `/.html`).
- `app/Http/Controllers/Admin/CourseController.php:66-81` — courses ĐÃ có logic slug unique với suffix `-1, -2...` → tái dùng pattern này (DRY).
- `app/Http/Controllers/BlogController.php:16` — public detail dùng `where('slug')->firstOrFail()`.
- DB local (kiểm tra 2026-07-23 qua tinker): 2 blogs, **0 slug trùng** → thêm index an toàn ở local. Production PHẢI kiểm tra lại.

## Requirements

1. Migration mới thêm `unique('slug')` cho bảng `blogs` (KHÔNG sửa migration cũ).
2. Trước khi migrate: kiểm tra slug trùng; nếu có, đổi bản ghi trùng thành `slug-2, slug-3...` (giữ bản ghi id nhỏ nhất nguyên slug gốc).
3. Admin store: validate `title` required; sinh slug unique kiểu suffix (như CourseController); **khi edit giữ nguyên slug cũ** (không đổi URL bài đã publish).

## Related Code Files

- **Tạo:** `database/migrations/2026_07_23_XXXXXX_add_unique_index_to_blogs_slug.php`
- **Sửa:** `app/Http/Controllers/Admin/BlogController.php` (method `store`)
- **Không đụng:** migration cũ, `app/Http/Controllers/BlogController.php` (public), views

## Implementation Steps

1. **Kiểm tra dữ liệu trùng** (chạy trước, báo kết quả):
   `php artisan tinker --execute="print_r(App\Models\Blog::select('slug')->groupBy('slug')->havingRaw('COUNT(*)>1')->pluck('slug')->all());"`
2. Nếu có trùng: script tinker đổi slug các bản ghi trùng (trừ id nhỏ nhất) thành `{slug}-{n}` — báo user danh sách URL bị đổi trước khi chạy.
3. Tạo migration mới: `up()` → `$table->unique('slug');` trong `Schema::table('blogs')`; `down()` → `$table->dropUnique(['slug']);`.
4. Sửa `Admin\BlogController::store`:
   - Thêm `$request->validate(['title' => 'required|string|max:255']);`
   - Nếu `$id` có và blog tồn tại → giữ `$blog->slug`; nếu tạo mới → `Str::slug($title)` + fallback `'blog'` nếu rỗng + vòng while suffix `-1, -2...` loại trừ chính `$id` (copy pattern CourseController:66-81).
5. Chạy `php artisan migrate`.
6. Test tay (Success Criteria).

## Todo

- [ ] Kiểm tra slug trùng trong DB (local + hỏi user về production)
- [ ] (Nếu có trùng) dedupe + báo user
- [ ] Migration unique index
- [ ] Sửa store: validation + slug unique + giữ slug khi edit
- [ ] `php artisan migrate` + test tay

## Success Criteria

- `php artisan migrate` chạy sạch, không lỗi duplicate key.
- Admin tạo 2 bài cùng title → slug bài 2 tự thành `{slug}-1`, cả 2 mở được ở `http://127.0.0.1:8000/{slug}.html`.
- Edit bài cũ đổi title → slug/URL KHÔNG đổi, bài vẫn mở được URL cũ.
- Tạo bài không title → báo lỗi validation, không tạo bản ghi slug rỗng.
- Insert tay slug trùng qua tinker → DB báo lỗi duplicate (index hoạt động).

## Risk Assessment

- **Migration fail nếu production có slug trùng** (khả năng: trung bình / ảnh hưởng: cao) → Mitigation: bước 1-2 kiểm tra + dedupe TRƯỚC khi migrate; không migrate production khi chưa verify.
- **Dedupe đổi URL bài đang có traffic** → Mitigation: báo user danh sách slug bị đổi trước khi chạy; giữ id nhỏ nhất nguyên slug.
- **Rollback:** `php artisan migrate:rollback --step=1` (chỉ drop index, không mất dữ liệu).

## Security Considerations

- Unique index chặn tình huống 2 bài trùng slug → user xem nhầm nội dung.
- Validation server-side chặn slug rỗng/HTML injection vào title (Str::slug đã sanitize slug).
