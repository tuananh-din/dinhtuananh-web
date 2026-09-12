---
title: "Homepage content evidence audit"
date: 2026-09-12
scope: "Public homepage copy; no application-code changes"
---

# Homepage content evidence audit

## Tóm tắt

Trang hiện là thương hiệu cá nhân, không có dữ liệu pháp lý hay định vị chứng minh đây là một “trung tâm”. Dùng ngôn ngữ **đào tạo Digital Marketing**; không tự nhận “trung tâm”, “học viện”, “chuyên gia”, “được chứng nhận” hay “đơn vị hàng đầu” nếu chưa có bằng chứng bổ sung.

Không có database SQLite/SQL hay dump dữ liệu trong repo để xác nhận bản ghi production hiện tại. Vì vậy: chỉ dùng nội dung/định lượng từ record đang được public query tại runtime; seed là ví dụ khởi tạo, không phải social proof production.

## Nguồn dữ liệu thực tế trên homepage

| Biến view | Query/nguồn chính xác | Có thể nói thật | Không suy ra được |
|---|---|---|---|
| `$about` | `About::first() ?? new About()` — [HomeController.php](../../app/Http/Controllers/HomeController.php:19) | Tên, mô tả, giới thiệu, điện thoại/email/social nếu trường có giá trị | Chức danh, số năm kinh nghiệm, chứng chỉ, quy mô đội ngũ |
| `$jobs`, `$words` | Toàn bộ `services`, sắp xếp ID giảm dần; `$words` chỉ là title — [HomeController.php](../../app/Http/Controllers/HomeController.php:20) | Tên/mô tả dịch vụ đã admin nhập | Kết quả dịch vụ, số khách hàng, lĩnh vực chuyên sâu ngoài phần mô tả |
| `$skills` | 4 `skills` có `number` cao nhất — [HomeController.php](../../app/Http/Controllers/HomeController.php:22) | Tên/mô tả năng lực có dữ liệu | Trình độ/chứng nhận hay số lượng năng lực toàn hệ thống (chỉ lấy tối đa 4) |
| `$featuredCourse`, `$highlightCourses` | Chỉ `courses.is_active=1`; featured trước, sau đó fallback; 2 khóa còn lại — [HomeController.php](../../app/Http/Controllers/HomeController.php:29) | Title, mô tả, nền tảng, cấp độ, hình thức, thời lượng, giá và CTA của **record đang active** | “Đang mở đăng ký”, số học viên, tỷ lệ thành công, lịch khai giảng (schema không có các trường này) |
| `$cases`, `$usesCaseStudies` | Tối đa 3 `case_studies.is_published=1`; nếu không có thì fallback `images.type=0` — [HomeController.php](../../app/Http/Controllers/HomeController.php:24) | Case đã publish: title, summary, client/industry/platform/role và 4 cặp KPI có giá trị | Fallback ảnh là case study, kết quả dự án hoặc KPI; không có case nào thì không có bằng chứng kết quả |
| `$featuredTestimonials` | Testimonial active + featured, fallback active; tối đa 6 — [HomeController.php](../../app/Http/Controllers/HomeController.php:51) | Nguyên văn feedback, tên, vai trò/công ty, avatar, rating của đúng bản ghi | Trung bình sao, số học viên, outcome được bảo đảm, testimonial gắn với khóa nào (không có `course_id`) |
| `$blogs` | Tối đa 3 blog `is_published=1` — [HomeController.php](../../app/Http/Controllers/HomeController.php:23) | Tiêu đề, mô tả, ngày tạo của bài đã publish | Tổng số bài trên website (biến này bị giới hạn 3), chuyên môn đã được kiểm chứng |
| `$leadMagnet` | Lead magnet active mới nhất — [HomeController.php](../../app/Http/Controllers/HomeController.php:66) | Tên/mô tả tài liệu và CTA nhận tài liệu | “Miễn phí”, giá trị/số trang, số lượt tải nếu record không nêu |
| `$contact`, `$infor` | Global view composer: `About::first()` và `Setting::first()` — [AppServiceProvider.php](../../app/Providers/AppServiceProvider.php:29) | Site name/slogan/contact/SEO nếu đã nhập | Những thành tích không có trường hoặc nội dung nguồn |

### Các field làm căn cứ

- Course có trường mô tả, giá, nền tảng, cấp độ, thời lượng, hình thức, CTA và cờ `is_active`/`is_featured`; xem [migration courses](../../database/migrations/2026_03_12_000001_create_courses_table.php:11).
- Testimonial có danh tính, nội dung, rating, trạng thái active/featured; xem [migration testimonials](../../database/migrations/2026_03_12_000003_create_testimonials_table.php:11).
- Case study có client/industry/platform/role và bốn cặp KPI dạng text; chỉ `is_published=1` mới vào homepage; xem [migration case studies](../../database/migrations/2026_08_17_000001_create_case_studies_table.php:15).
- Admin validation không đòi bằng chứng/đồng ý công khai cho testimonial hoặc case study. Hãy xác nhận quyền dùng tên, avatar, logo và KPI trước khi public.

## Seed và template: giới hạn sử dụng

- `DatabaseSeeder` chỉ gọi `CourseSeeder` — [DatabaseSeeder.php](../../database/seeders/DatabaseSeeder.php:13). Một database trống có ba khóa mẫu Facebook/TikTok/Google active, nhưng CTA dùng số mẫu `tel:0900000000` và text không dấu; đừng dùng nó như nội dung production hoặc số hotline thật.
- Ba landing course Digital Performance, Facebook Community, Data Analysis có seed riêng và mặc định `is_active=false`. Chúng chỉ hiện khi đã publish/manual active.
- Các số `500K+`, `24K+`, `3M+` ở template Facebook Community không lấy từ record `case_studies` hay seeder. Không tái sử dụng trên homepage trừ khi có case study đã publish, KPI tương ứng và quyền công bố.

## Copy đề xuất theo section

Copy dưới đây không hứa hẹn kết quả và không thêm con số. Phần trong ngoặc vuông chỉ hiển thị khi record tương ứng tồn tại.

### Hero

- Eyebrow: `ĐÀO TẠO DIGITAL MARKETING`
- H1: `Học Digital Marketing theo lộ trình rõ ràng — hiểu cách thiết lập, đo lường và tối ưu.`
- Mô tả: `Khám phá các khóa học đang hiển thị, xem thông tin từng lộ trình và gửi mục tiêu của bạn để nhận tư vấn phù hợp.`
- Ba điểm ngắn:
  - `Nội dung theo từng chủ đề Digital Marketing.`
  - `Thông tin nền tảng, cấp độ, thời lượng và học phí hiển thị theo từng khóa khi có dữ liệu.`
  - `Xem dự án và phản hồi đã được công bố trước khi quyết định.`
- CTA chính: `Xem khóa học`
- CTA phụ: `Xem case study` **chỉ khi** `$usesCaseStudies`; nếu không, đổi thành `Tìm hiểu về giảng viên` và trỏ trang About.

Không dùng “giúp ra đơn”, “kết quả thực tế” hoặc “đa nền tảng” như lời hứa bao quát khi chưa có course/case active chứng minh.

### Nhóm phù hợp và lợi ích học

- Tiêu đề: `Bạn đang muốn bắt đầu hoặc hệ thống hóa Digital Marketing?`
- Thẻ 1: `Người mới` — `Bắt đầu bằng khóa học có cấp độ và lộ trình phù hợp.`
- Thẻ 2: `Marketer` — `Củng cố cách thiết lập, đọc chỉ số và tối ưu theo mục tiêu công việc.`
- Thẻ 3: `Người kinh doanh` — `Tìm hiểu các nội dung phù hợp với kênh và nhu cầu đang triển khai.`
- Tiêu đề section dịch vụ: `Nội dung đào tạo và giải pháp đang có`
- Intro: `Khám phá từng nội dung bên dưới; phần mô tả được cập nhật từ thông tin quản trị.`

Chỉ render các thẻ nội dung từ `$jobs` khi collection không rỗng. Không gọi đây là “đội ngũ”, “phương pháp độc quyền” hay “dịch vụ đã triển khai thành công”.

### Khóa học

- Eyebrow: `KHÓA HỌC`
- Tiêu đề: `Khám phá các khóa học đang hiển thị`
- Card featured: dùng nguyên `[course.title]`, `[course.short_description]`, và chỉ render từng meta/giá khi field không null.
- CTA: `Xem nội dung khóa học`; CTA thứ hai theo `cta_text`/`cta_link`, hoặc `Nhận tư vấn về khóa học`.
- Empty state: `Hiện chưa có khóa học để hiển thị. Hãy để lại mục tiêu học để nhận tư vấn.`

Không ghi “đang mở đăng ký”: `$is_active` chỉ là trạng thái public. Không ghi “ưu đãi” chỉ vì có `sale_price`; đổi thành `Học phí hiện tại` nếu chưa có điều kiện/hiệu lực ưu đãi.

### Bằng chứng

**Case study, khi `$usesCaseStudies=true`:**

- Eyebrow: `DỰ ÁN ĐÃ CÔNG BỐ`
- Tiêu đề: `Xem cách các dự án đã được triển khai`
- Card: `[title]` + `[summary]`; chỉ hiện `[industry]`, client, nền tảng, vai trò và KPI có field tương ứng.
- CTA: `Xem chi tiết case study`

**Chỉ có Image fallback hoặc không có dữ liệu:**

- Đổi section thành `Hình ảnh hoạt động`.
- Không dùng “case study”, “kết quả”, KPI, logo khách hàng hay CTA “Xem case study”.
- Nếu không có ảnh: ẩn toàn bộ section, không dùng placeholder như social proof.

**Testimonials, khi collection không rỗng:**

- Eyebrow: `CHIA SẺ TỪ HỌC VIÊN / KHÁCH HÀNG`
- Tiêu đề: `Những phản hồi đã được công bố`
- Hiển thị nguyên văn `[content]`, `[name]`, và job/company/avatar/rating chỉ khi từng field có dữ liệu/quyền dùng.

Không tính “X/5 trung bình”, không thêm “hàng trăm học viên”, và không gán feedback cho một khóa học cụ thể. Nếu không có testimonial active thì **ẩn section**; không thay bằng lời cam kết uy tín.

### Blog, giới thiệu, lead magnet

- Blog title: `Bài viết mới` / `Góc chia sẻ về Digital Marketing`; CTA `Đọc bài viết`.
- Nếu `$blogs` rỗng: `Nội dung đang được cập nhật.` + CTA `Xem khóa học`; không nói “hệ kiến thức chuyên sâu”.
- About title: `Tìm hiểu về [about.name]`; body dùng `about_me`, fallback `content`; CTA `Xem giới thiệu`.
- Chỉ hiện nút gọi, email và social khi giá trị tương ứng không rỗng.
- Lead magnet: dùng đúng `[leadMagnet.name]` + `[leadMagnet.description]`; CTA `Nhận tài liệu`. Không gọi là ebook miễn phí hoặc nêu số trang khi không có field.

### FAQ và CTA cuối

**FAQ không cần thêm dữ liệu:**

1. `Tôi mới bắt đầu có thể chọn khóa nào?` — `Xem cấp độ ở từng khóa học hoặc gửi mục tiêu để được tư vấn nội dung phù hợp.`
2. `Khóa học học theo hình thức nào?` — `Hình thức được hiển thị trong thông tin của từng khóa khi đã cập nhật.`
3. `Học phí được xem ở đâu?` — `Học phí hiện ở trang chi tiết khi khóa học có thông tin giá; nếu chưa có, hãy gửi yêu cầu tư vấn.`
4. `Tôi có thể hỏi trước khi đăng ký không?` — `Có. Hãy gửi mục tiêu hoặc câu hỏi qua form liên hệ.`

Không trả lời về lịch khai giảng, thời hạn truy cập, hoàn tiền, chứng chỉ, số buổi, giảng viên trực tiếp hay SLA phản hồi ở FAQ chung: schema homepage không bảo đảm các dữ kiện này.

- H2 CTA: `Bạn đang tìm một lộ trình học Digital Marketing phù hợp?`
- Mô tả: `Để lại mục tiêu học hoặc câu hỏi của bạn. Thông tin được dùng để liên hệ tư vấn.`
- Nút: `Gửi yêu cầu tư vấn`
- Nút phụ: `Xem tất cả khóa học`; `Gọi tư vấn` chỉ khi `about.tel` có giá trị.

Xóa/đổi câu “liên hệ trong giờ làm việc” và “tư vấn nhanh” nếu không có cam kết vận hành đã được duyệt.

## Nội dung phải ẩn khi thiếu data

| Thiếu data | Bắt buộc ẩn/đổi | Lý do |
|---|---|---|
| Không active course | Cards/course CTA giá; dùng empty state | Không có offer public |
| Price/sale price null | Số tiền và “ưu đãi” | Không có giá/hiệu lực ưu đãi |
| Không published case study | Label Case study, kết quả, KPI, client logo và CTA portfolio | Fallback chỉ là Image, không phải bằng chứng |
| Không active testimonial | Cả testimonial section và mọi số liệu review | Không có feedback public |
| Không blog published | Grid blog và lời hứa về thư viện kiến thức | Không có nội dung public |
| About name/bio trống | Tên giảng viên, profile claim, nút About contextual | `new About()` có thể rỗng |
| Tel/email/social trống | Nút gọi, email, icon social | Tránh link rỗng `tel:`/`mailto:` |
| Không lead magnet active | Form “nhận tài liệu” | Không có file active để gửi |
| Không KPI field/nguồn đồng ý | Con số performance, logo, tên khách hàng | Tránh claim không kiểm chứng/quyền công bố không rõ |

## Notes triển khai content

- Sửa thông điệp hiện tại `Khóa học đang mở đăng ký` thành wording dựa trên `is_active` (chỉ nghĩa là hiển thị public); source hiện ở [home.blade.php](../../resources/views/home.blade.php:132).
- Bỏ dấu `+` ở metric. `$blogs` chỉ tối đa 3, `$skills` chỉ tối đa 4; các metric hiện tại không biểu thị tổng thực tế; source [home.blade.php](../../resources/views/home.blade.php:46).
- `about->content` và `about_me` đang render raw HTML. Content editor phải chỉ xuất HTML đã được duyệt, không dùng làm nơi thêm claim chưa kiểm chứng; source [home.blade.php](../../resources/views/home.blade.php:118).

## Unresolved questions

1. Production hiện có những Course/CaseStudy/Testimonial/Blog/LeadMagnet active nào? Cần xem Admin hoặc database đã được cấp quyền để chốt copy theo record.
2. Có quyền công khai tên, ảnh và KPI của từng khách hàng/học viên không?
3. Thương hiệu muốn định vị pháp lý là cá nhân, “đơn vị đào tạo” hay “trung tâm”? Repo hiện chỉ chứng minh personal brand.
