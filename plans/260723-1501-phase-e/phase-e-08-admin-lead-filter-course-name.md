# E-08 — Admin lead: lọc theo trạng thái + hiện tên khoá học + đếm theo trạng thái

## Overview
- Priority: P2 | Status: Pending | Migration: KHÔNG | Route mới: KHÔNG (dùng query param trên route hiện có)

## Key Insights (đã verify)
- `app/Http/Controllers/Admin/LeadController.php:11-15`: `index()` chỉ `orderBy id DESC + paginate(20)` — không lọc.
- `resources/views/admin/lead/index.blade.php:45`: hiện `Course ID: {{ $lead->course_id }}` — admin phải tự tra ID; bảng `leads` có FK mềm `course_id` (migration `2026_03_12_000002`).
- `app/Models/Lead.php`: chưa có relation `course()`. Trạng thái đã chuẩn hoá 5 giá trị trong view (dòng 52-58: new/contacted/qualified/won/lost).
- Lead là dữ liệu giá trị nhất của site (bán khoá học) — khi lead nhiều, không lọc được sẽ khó dùng. DB hiện có 2 lead → làm mức TỐI THIỂU, không export CSV (YAGNI, làm sau khi user cần).

## Requirements
- Lọc `?status=new|contacted|qualified|won|lost` (dropdown GET, giữ phân trang qua `appends`).
- Dòng đếm: tổng + số theo từng trạng thái (1 query `groupBy`).
- Cột nguồn hiện tên khoá học thay Course ID (eager load `with('course')`, fallback hiện ID nếu course đã xoá).
- DRY: mảng `$statuses` đang hardcode trong view → chuyển thành const `Lead::STATUSES` dùng chung cho filter validate + view.

## Related Code Files
- Sửa: `app/Models/Lead.php` (thêm `const STATUSES`, relation `course(): belongsTo(Course::class)`),
  `app/Http/Controllers/Admin/LeadController.php` (index: validate status trong whitelist, `when()` filter, `with('course')`, `withQueryString()`; đếm `Lead::selectRaw('status, count(*) c')->groupBy('status')`),
  `resources/views/admin/lead/index.blade.php` (form filter GET + dòng đếm + tên course + dùng `Lead::STATUSES`).
- Tạo/Xoá: KHÔNG.

## Implementation Steps
1. Model: thêm `STATUSES` (map value→label tiếng Việt, chuyển từ view sang) + relation `course`.
2. Controller `index()`: `$status = $request->query('status'); if (!array_key_exists($status, Lead::STATUSES)) $status = null;` → `Lead::with('course')->when($status, ...)->orderBy('id','DESC')->paginate(20)->withQueryString();` + mảng đếm.
3. View: dropdown filter (auto-submit hoặc nút Lọc), badge đếm trên đầu bảng, cột nguồn: `{{ $lead->course?->title ?? ($lead->course_id ? 'Course #'.$lead->course_id : '') }}`, options status lấy từ `Lead::STATUSES`.
4. Không đổi `update()` và route.

## Todo
- [ ] Model: STATUSES + relation course
- [ ] Controller: filter + eager load + đếm
- [ ] View: dropdown + đếm + tên course
- [ ] Test tay theo Success Criteria
- [ ] Commit: `feat: admin lead loc theo trang thai va hien ten khoa hoc`

## Success Criteria (test tay)
1. http://127.0.0.1:8000/admin/lead : thấy dòng đếm (VD "Tổng 2 | Mới 2 | Đã liên hệ 0 ...").
2. Chọn lọc "Mới" → chỉ hiện lead status=new; URL có `?status=new`; đổi status 1 lead → lọc lại đúng.
3. `?status=abc` (nhập tay) → hiện tất cả, không lỗi 500.
4. Lead có `course_id` (tạo test từ form course_detail) → hiện tên khoá học; lead không có → cột trống.
5. Cập nhật status/note vẫn hoạt động như cũ (regression D/C).

## Risk Assessment
- Thấp: chỉ thêm đọc/lọc, không đổi ghi. `course_id` không có FK constraint → dùng `?->` null-safe. Rollback: revert 3 file.

## Security Considerations
- `status` validate whitelist trước khi query (chống injection qua param). Route vẫn sau `auth`. Label render qua `{{ }}` — bỏ được `{!! !!}` hiện tại ở view (dòng 61) nếu chuyển label sang UTF-8 thật trong const.

## Next Steps
- Tương lai (chờ user yêu cầu): export CSV, badge số lead mới trên sidebar admin.
