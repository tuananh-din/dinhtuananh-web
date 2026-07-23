# E-02 — JSON-LD thêm `JSON_HEX_TAG` chống thoát `</script>`

## Overview
- Priority: P1 (security, stored XSS admin-only) | Status: Pending | Migration: KHÔNG

## Key Insights (đã verify)
- `resources/views/partials/jsonld-article.blade.php:33` — `@json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)`.
- `resources/views/partials/jsonld-person.blade.php:23` — cùng pattern.
- Thiếu `JSON_HEX_TAG`: nếu admin nhập `</script><script>...` vào title/description blog hoặc about, chuỗi được in nguyên trong thẻ `<script type="application/ld+json">` → thoát thẻ → stored XSS (chỉ admin tạo được, nhưng chạy trên trang PUBLIC cho mọi visitor).
- Lưu ý: `JSON_HEX_TAG` encode `<` `>` thành `<>` — JSON-LD vẫn hợp lệ với Google (unicode escape chuẩn JSON).

## Requirements
- `<` và `>` trong mọi giá trị JSON-LD phải được escape; schema vẫn parse được (test bằng Rich Results Test / JSON.parse).

## Related Code Files
- Sửa: `resources/views/partials/jsonld-article.blade.php` (dòng 33), `resources/views/partials/jsonld-person.blade.php` (dòng 23).

## Implementation Steps
1. Cả 2 file: đổi flags thành `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG`.
2. (Khuyến nghị thêm) `| JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT` — cùng chi phí, phòng ngừa context khác. Tối thiểu bắt buộc: `JSON_HEX_TAG`.

## Todo
- [ ] Sửa 2 partials
- [ ] Test tay theo Success Criteria
- [ ] Commit: `fix: json-ld them JSON_HEX_TAG chong thoat script tag`

## Success Criteria (test tay)
1. Vào /admin/blog/edit/{id}, tạm đặt title = `Test</script><script>alert(1)</script>` → mở trang blog detail public (`/{slug}.html`) → KHÔNG có alert, view-source thấy `</script...`; trả lại title cũ sau khi test.
2. http://127.0.0.1:8000/ và /about: view-source, copy JSON trong `application/ld+json` → `JSON.parse` OK (console browser).
3. Tiếng Việt trong JSON-LD vẫn hiển thị nguyên (JSON_UNESCAPED_UNICODE giữ nguyên).

## Risk Assessment
- Rủi ro thấp: chỉ đổi flag encode. Nếu Google Rich Results báo lỗi (không có khả năng) → revert flag.

## Security Considerations
- Đóng vector stored XSS admin→public. Không thay đổi dữ liệu DB.

## Next Steps
- Độc lập. E-06 (sameAs) sẽ sửa tiếp `jsonld-person` — làm E-02 trước để E-06 kế thừa flag đúng.
