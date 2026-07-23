# E-06 — JSON-LD Person thêm `sameAs` từ cột social

## Overview
- Priority: P2 | Status: Pending | Migration: KHÔNG | **Phụ thuộc: E-05 đã chạy migration**

## Key Insights (đã verify)
- `resources/views/partials/jsonld-person.blade.php` build schema Person (name, url, image, jobTitle, description) — thiếu `sameAs` (Google dùng để nối profile social → knowledge panel).
- Sau E-05, `$about` (bảng `about`) có `facebook/instagram/linkedin/x`. Partial nhận `$about` từ home.blade.php:3 và about.blade.php:5.

## Requirements
- `sameAs` = mảng các URL social không rỗng; bỏ hẳn key nếu cả 4 rỗng (array_filter hiện có đã xử lý mảng rỗng).

## Related Code Files
- Sửa: `resources/views/partials/jsonld-person.blade.php` (duy nhất).

## Implementation Steps
1. Trong `@php` block, thêm:
   `'sameAs' => array_values(array_filter([data_get($person,'facebook'), data_get($person,'instagram'), data_get($person,'linkedin'), data_get($person,'x')]))`.
2. Kiểm tra `array_filter` cuối partial giữ logic bỏ mảng rỗng (đã có nhánh `is_array` trong jsonld-article; partial person hiện chỉ filter scalar — cập nhật closure cho case mảng rỗng).

## Todo
- [ ] Sửa partial + xử lý mảng rỗng
- [ ] Test tay theo Success Criteria
- [ ] Commit: `feat: json-ld person them sameAs tu social links`

## Success Criteria (test tay)
1. Điền social trong /admin/profile → view-source http://127.0.0.1:8000/ và /about → JSON-LD có `"sameAs":["https://facebook.com/...", ...]`, `JSON.parse` OK.
2. Xoá hết social → JSON-LD KHÔNG có key `sameAs` (không phải `[]`).
3. Paste JSON vào Google Rich Results Test (nếu tiện) → không lỗi cú pháp.

## Risk Assessment
- Rất thấp: chỉ thêm key vào schema. Rollback: revert 1 file.

## Security Considerations
- Kế thừa flags `JSON_HEX_TAG` từ E-02 (làm E-02 trước). URL admin nhập được encode trong JSON.

## Next Steps
- Không có mục nào phụ thuộc.
