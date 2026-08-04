# Hướng dẫn sử dụng Admin

Tài liệu này dành cho người mới quản trị website. Đăng nhập tại `/login`, sau đó vào `/admin`.

> Quy tắc chung: nhập xong hãy kiểm tra lại nội dung, bấm lưu, rồi mở trang public tương ứng bằng cửa sổ ẩn danh. Không sửa file `.env`, không xóa file trong `storage` bằng tay.

## 1. Bản đồ menu

| Menu | Dùng để làm gì | Hiển thị ở đâu |
| --- | --- | --- |
| Thông tin cá nhân | Hồ sơ, liên hệ, giới thiệu | Trang chủ, Giới thiệu, Liên hệ, footer |
| Hình ảnh | Quản lý ảnh dùng cho nội dung | Tùy loại ảnh đã chọn |
| Kỹ năng | Danh sách kỹ năng/chỉ số | Trang chủ/Giới thiệu khi có dữ liệu |
| Ngành nghề | Dịch vụ | Khu vực dịch vụ public |
| Bài viết | Blog | `/blog` và trang bài viết |
| Khóa học | Danh sách, landing/course detail, lead | `/courses`, `/courses/{slug}` |
| Leads | Người gửi form tư vấn | Admin → Leads |
| Testimonial | Nhận xét khách hàng | Trang chủ khi có dữ liệu |
| Cài đặt | Tên site, logo, favicon, SEO chung, code header/footer | Toàn site |

## 2. Quy tắc ảnh

### Điều hệ thống thực sự kiểm tra

- Upload ảnh thông thường trong Admin: phải là file ảnh, tối đa **5 MB**.
- Ảnh chèn CKEditor: `jpg`, `jpeg`, `png`, `gif`, `webp`, tối đa **5 MB**.
- Ảnh được lưu tại `/storage/images/...`; không tự xóa file trực tiếp trên ổ đĩa.
- **Cần xác minh:** code hiện không bắt buộc chiều rộng/cao pixel. Các con số bên dưới là khuyến nghị để ảnh không bị mờ, méo hoặc nặng.

### Bảng kích thước khuyến nghị

| Vị trí | Tỷ lệ/kích thước khuyến nghị | Gợi ý |
| --- | --- | --- |
| Logo | Ngang, khoảng 300×100 px | PNG nền trong suốt hoặc SVG nếu hệ thống upload chấp nhận; để khoảng trống quanh logo |
| Favicon | Vuông 512×512 px | PNG rõ nét, icon đơn giản |
| Thumbnail khóa học | Ngang 16:9, tối thiểu 1200×675 px | Không nhét chữ nhỏ; ảnh bị cắt để vừa card |
| Ảnh đại diện bài viết | Ngang 16:9, tối thiểu 1200×675 px | Dùng cùng tỷ lệ giữa các bài để danh sách đều |
| Avatar testimonial | Vuông 600×600 px | Chân dung rõ, mặt ở giữa |
| Ảnh CKEditor | Rộng tối đa 1600 px | Nén ảnh trước khi upload để trang tải nhanh |

## 3. Khóa học: hướng dẫn từng trường

Vào **Khóa học** → **Thêm mới** hoặc biểu tượng sửa.

| Trường | Cách nhập |
| --- | --- |
| Tiêu đề (*) | Tên khóa học dễ hiểu, ví dụ `Google Ads thực chiến`. |
| Slug | Có thể để trống để hệ thống tự tạo từ tiêu đề. Không cần tự bỏ dấu hay tự thêm dấu gạch; hệ thống chuẩn hóa và xử lý trùng slug. |
| Mô tả ngắn (*) | Đoạn giới thiệu hiển thị trong card/list; nên 1–3 câu ngắn. |
| Mô tả/Nội dung | Nội dung chi tiết. Không dán script hoặc mã không đáng tin cậy. |
| Thumbnail | Dùng ảnh 16:9 theo bảng trên. Nếu không upload khi sửa, ảnh cũ được giữ lại. |
| Giá gốc/Giá sale | Nhập số, không âm. Giá sale không được lớn hơn giá gốc. |
| Nền tảng/Cấp độ/Thời lượng/Hình thức | Thông tin phụ giúp người học chọn khóa học. Có thể để trống nếu chưa rõ. |
| CTA text | Chữ trên nút, tối đa **60 ký tự**. Ví dụ `Đăng ký học ngay`. |
| CTA link | Được phép: `https://...`, `http://...`, `/contact` hoặc `tel:0900000000`. Không dùng `javascript:`, `data:` hoặc `//example.com`. |
| Thứ tự hiển thị | Số nhỏ hiển thị trước. |
| SEO title | Tối đa **60 ký tự**, nên có tên khóa học và lợi ích chính. |
| SEO description | Tối đa **155 ký tự**, viết 1 câu tự nhiên tóm tắt khóa học. |
| Khóa học nổi bật | Bật khi muốn đưa khóa học lên khu vực nổi bật trang chủ. |
| Đang hiển thị | Tắt để ẩn khỏi public nhưng vẫn giữ dữ liệu trong Admin. |

### Lưu ý khóa học

- Nếu khóa học đã có lead, nút xóa sẽ **ẩn** khóa học thay vì xóa để giữ lịch sử lead.
- Sau khi lưu, mở `/courses` và trang chi tiết để kiểm tra ảnh, giá, CTA và tiếng Việt.
- Nếu lỗi hiện ngay dưới ô CTA/SEO/giá sale/link, sửa đúng ô đó rồi lưu lại.

## 4. Bài viết Blog

1. Vào **Bài viết** → **Thêm mới**.
2. Nhập tiêu đề, slug (nếu form có), mô tả ngắn và nội dung.
3. Upload ảnh đại diện 16:9.
4. Dùng CKEditor cho nội dung dài; chỉ chèn ảnh thuộc quyền sử dụng.
5. Điền title/keyword SEO nếu cần, sau đó mở `/blog` và bài viết để kiểm tra.

Không dán mã script lạ vào nội dung. Ảnh trong bài nên được nén trước để không làm trang chậm.

## 5. Cài đặt, Profile và ảnh

- **Cài đặt:** thay tên website, URL, logo, favicon và dữ liệu SEO chung. Kiểm tra logo trên header/footer sau khi lưu.
- **Thông tin cá nhân:** cập nhật tên, điện thoại, email, mô tả và mạng xã hội. Link mạng xã hội phải mở đúng trang thật.
- **Hình ảnh:** chọn đúng loại ảnh trước khi lưu. Không dùng ảnh quá nặng; hãy giữ tỷ lệ theo vị trí hiển thị.
- **Kỹ năng/Dịch vụ:** dùng tên ngắn, rõ nghĩa; kiểm tra thứ tự hiển thị nếu có.
- **Testimonial:** tên, chức danh, nhận xét, rating 1–5 và avatar vuông. Dùng nhận xét có sự đồng ý của khách hàng.

## 6. Leads

Lead là người đã gửi form tư vấn. Cập nhật trạng thái và ghi chú sau khi liên hệ.

- Không xóa hoặc sửa thông tin liên hệ nếu chưa kiểm tra lịch sử trao đổi.
- Ghi chú ngắn, có ngày/việc tiếp theo, ví dụ: `24/07: đã gọi, hẹn tư vấn lại thứ Hai`.
- Kiểm tra khóa học quan tâm để tư vấn đúng ngữ cảnh.

## 7. Checklist trước và sau khi lưu

### Trước khi lưu

- [ ] Nội dung có dấu tiếng Việt đúng, không có chuỗi lỗi kiểu `HÃ£y`.
- [ ] Ảnh đúng tỷ lệ, dưới 5 MB và đã nén.
- [ ] CTA link là `https/http`, `/...` hoặc `tel:` hợp lệ.
- [ ] CTA/SEO không vượt giới hạn hiển thị trên form.
- [ ] Không bật “nổi bật” cho nội dung chưa hoàn thiện.

### Sau khi lưu

- [ ] Mở đúng trang public và làm mới trang.
- [ ] Ảnh không bị vỡ, méo hoặc cắt mất chủ thể.
- [ ] Nút CTA dẫn đúng nơi; số điện thoại mở đúng số.
- [ ] Kiểm tra mobile nếu vừa thay ảnh/card/CTA.
- [ ] Với khóa học: kiểm tra `/courses`, trang chi tiết và form tư vấn.

## Đo chuyển đổi trang cảm ơn

Sau khi khách gửi form tư vấn hoặc đăng ký newsletter, website chuyển tới `/cam-on`. Kỹ thuật viên có thể dán sự kiện Facebook Pixel, Google Ads hoặc GA4 riêng cho trang này trong `resources/views/thank_you.blade.php` tại vùng `@push('conversion')`.

## 8. Khi có lỗi

- Lỗi đỏ dưới ô nhập: đọc đúng message và sửa field đó.
- Không thấy thay đổi: kiểm tra đã bấm lưu, đang xem đúng trang và hard refresh (`Ctrl+F5`).
- Ảnh không hiển thị: kiểm tra file là ảnh, dưới 5 MB, và đường dẫn `/storage` của môi trường local hoạt động.
- Không tự sửa database, `.env`, file trong `storage` hoặc source code để xử lý lỗi nội dung. Ghi lại URL, thao tác và ảnh chụp lỗi để kỹ thuật viên kiểm tra.
