# E-03 — CKEditor upload: bỏ `?_token=` trong URL, gửi CSRF qua header (A12)

## Overview
- Priority: P1 | Status: Pending | Migration: KHÔNG | Đổi route: KHÔNG

## Key Insights (đã verify)
- `resources/views/admin/blog/create.blade.php:88` và `admin/blog/edit.blade.php:89`: `uploadUrl: '{{route('image.upload').'?_token='.csrf_token()}}'` — CSRF token lộ trong URL (access log server/proxy).
- CKEditor 5 v34.2.0 **classic build CDN** (create.blade.php:81) dùng adapter `ckfinder` — adapter này KHÔNG hỗ trợ custom header; build classic KHÔNG kèm `SimpleUploadAdapter`. → Fix đúng: viết **custom upload adapter** nhỏ (~30 dòng JS) dùng XHR/fetch kèm header `X-CSRF-TOKEN` (Laravel `VerifyCsrfToken` đọc header này chuẩn).
- Server endpoint: `SettingController::upload` (dòng 73-86) nhận field `upload`, trả JSON `{fileName, uploaded:1, url}` — giữ nguyên, adapter đọc `res.url`.

## Requirements
- Upload ảnh trong editor blog (create + edit) hoạt động như cũ; token KHÔNG còn trong URL.
- DRY: 1 file JS dùng chung cho cả 2 view.

## Related Code Files
- Tạo: `public/app/assets/js/ckeditor-csrf-upload-adapter.js` (custom adapter: FileRepository → XHR POST `image.upload`, header `X-CSRF-TOKEN` từ meta tag hoặc biến truyền vào, FormData field `upload`, resolve `{default: res.url}`).
- Sửa: `resources/views/admin/blog/create.blade.php` (dòng 83-98), `resources/views/admin/blog/edit.blade.php` (block tương ứng): nạp file JS, thay `ckfinder: {uploadUrl...}` bằng `extraPlugins: [CsrfUploadAdapterPlugin]` (truyền uploadUrl = `{{ route('image.upload') }}` + csrf token qua config/biến global).

## Implementation Steps
1. Viết `ckeditor-csrf-upload-adapter.js`: hàm factory nhận `{uploadUrl, csrfToken}`, đăng ký `editor.plugins.get('FileRepository').createUploadAdapter`; XHR: `setRequestHeader('X-CSRF-TOKEN', csrfToken)`; reject khi `!res.uploaded`.
2. create.blade.php: thay config `ckfinder` bằng `extraPlugins`, set `window.ckUploadConfig = { url: '{{ route('image.upload') }}', token: '{{ csrf_token() }}' }` (token trong JS inline — KHÔNG trong URL, không vào access log).
3. Lặp lại cho edit.blade.php (giữ phần init khác nguyên trạng).
4. Không đụng route/controller.

## Todo
- [ ] Tạo adapter JS dùng chung
- [ ] Sửa create.blade.php
- [ ] Sửa edit.blade.php
- [ ] Test tay theo Success Criteria
- [ ] Commit: `fix: A12 ckeditor upload dung header X-CSRF-TOKEN thay query token`

## Success Criteria (test tay)
1. /admin/blog/create: kéo/chọn ảnh vào editor → ảnh upload thành công, hiển thị trong nội dung; tab Network: request POST `/admin/ckeditor/image_upload` KHÔNG có `?_token=`, có header `X-CSRF-TOKEN`, response 200.
2. /admin/blog/edit/{id}: tương tự.
3. Upload file không phải ảnh (vd .txt đổi tên .jpg giả) → bị từ chối 422 (validate A1 vẫn hoạt động), editor báo lỗi không crash.

## Risk Assessment
- Trung bình-thấp: đổi cơ chế upload editor; nếu adapter lỗi → ảnh không chèn được (dễ phát hiện khi test). Mitigation: test cả create + edit trước commit. Rollback: revert 3 file.

## Security Considerations
- Token khỏi URL/access log. Route vẫn sau middleware `auth` + CSRF verify. Không tắt CSRF cho route upload (không dùng `$except`).

## Next Steps
- Độc lập với các mục khác (không trùng file).
