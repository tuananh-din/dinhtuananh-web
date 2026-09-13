---
date: 2026-09-13
scope: public disclosure and accordion audit
status: complete
---

# Audit accordion public

## Tóm tắt

- Public Laravel hiện dùng disclosure native `<details>/<summary>` chỉ ở 3 landing course DPM. Không có Bootstrap Collapse đang được gọi bởi markup public.
- DPM dùng multi-open; curriculum mở sẵn mục đầu, FAQ và bảng dữ liệu đóng sẵn. Không có JavaScript can thiệp vào các `details`.
- Còn 5 accordion jQuery cũ trong `public/site/*.html`. Chúng được deploy, có thể truy cập trực tiếp dưới `/site/...`, nhưng không có route Laravel hoặc link từ view Laravel hiện tại.
- Blog, homepage, và `course_detail` không khai báo accordion riêng. Chúng render HTML từ DB nên có thể xuất hiện disclosure do nội dung CKEditor; codebase không cho biết dữ liệu production hiện có.

## Bản đồ route, view và asset

- `routes/web.php:41-71`: `/`, `/blog`, `/courses`, `/courses/{slug}`, và blog catch-all. Không có `/faq` Laravel route.
- `app/Http/Controllers/CourseController.php:21-48`: 3 slug dùng view DPM: `digital-performance-management`, `facebook-community-growth-system`, `data-analysis-visualization`; còn lại dùng `course_detail`.
- Ba view DPM kế thừa layout và tải `digital-performance.css`: `resources/views/courses/digital-performance.blade.php:1-20`, `facebook-community.blade.php:1-18`, `data-analysis.blade.php:1-19`.
- Public layout tải `bootstrap.bundle.min.js` và `main.js` tại `resources/views/layouts/master.blade.php:138-175`. Search không tìm thấy `data-bs-toggle="collapse"`, `data-toggle="collapse"`, hay target collapse ở view/public template; Bootstrap Collapse không được dùng.
- `public/site` vẫn được copy lên production bởi `.cpanel.yml:11-12`, nên static legacy là bề mặt công khai trực tiếp, dù không thuộc navigation/route Laravel.

## Disclosure đang có trên route Laravel

| Bề mặt | File/ref | Semantics và hành vi |
| --- | --- | --- |
| DPM curriculum | `resources/views/courses/digital-performance.blade.php:67-74`; modules `config/digital-performance.php:4-14` | 9 `<details>` native, mục đầu `open`; multi-open. `summary` chứa số buổi, tiêu đề, icon `aria-hidden`. |
| DPM FAQ | `resources/views/courses/digital-performance.blade.php:117-123` | 5 `<details>` native, đều đóng ban đầu; multi-open. |
| DPM analytics data | include tại `digital-performance.blade.php:66`; markup `resources/views/partials/dpm-analytics.blade.php:1` | 1 `<details class="dpm-data-table">` đóng ban đầu; multi-open (độc lập). Bảng có `caption`, bọc ngang-scroll. |
| Facebook Community curriculum/FAQ | `resources/views/courses/facebook-community.blade.php:37,42`; modules `config/facebook-community.php:4-10` | 5 curriculum (đầu mở) + 4 FAQ (đóng); native, multi-open. |
| Data Analysis curriculum/FAQ | `resources/views/courses/data-analysis.blade.php:37,42`; modules `config/data-analysis.php:4-10` | 5 curriculum (đầu mở) + 4 FAQ (đóng); native, multi-open. |

### Khả năng dùng được và a11y DPM

- Native `summary` tự focus được và hỗ trợ Enter/Space; `open` phản ánh trạng thái cho accessibility tree. Vì vậy không cần tự thêm `role=button`, `aria-expanded` hay `aria-controls`.
- Icon curriculum là CSS `+`/`−`, còn span trang trí đã `aria-hidden`: `public/site/assets/css/digital-performance.css:67-78`; FAQ cũng có `+`/`−`: `:119-122`.
- Focus tốt: rule riêng DPM `:12-13`, và fallback global cho `summary` `public/site/assets/css/custom.css:2800-2809`.
- Mobile: curriculum giảm padding/gap và vẫn giữ text 14px tại `digital-performance.css:129,208-215`. Bảng có `.dpm-table-scroll { overflow-x:auto }` tại `:156-163`.
- Không có animation mở/đóng của DPM. `prefers-reduced-motion` chỉ vô hiệu transition nút (`digital-performance.css:132`), không gây rủi ro cho native disclosure.

### Điểm cần lưu ý DPM

1. `summary` của curriculum chứa `<h3>` (`digital-performance.blade.php:71`, `facebook-community.blade.php:37`, `data-analysis.blade.php:37`). `summary` chỉ nhận phrasing content; đây là HTML không hợp lệ. Không gây hỏng toggle hiện tại, nhưng dễ tạo khác biệt parser/a11y. FAQ chỉ có text nên không bị lỗi này.
2. Rule global ẩn marker native (`digital-performance.css:68-69`). `dpm-data-table` không có icon thay thế hay style theo `[open]` (`:156-162`), nên người dùng chỉ thấy text gạch chân, không thấy rõ trạng thái đóng/mở. Curriculum và FAQ đã có icon thay thế.

## Accordion legacy trong static `public/site`

| Static page | Markup | Logic và rủi ro |
| --- | --- | --- |
| `public/site/faq.html:306-375` | 5 `.accordion.block`, item đầu active/current | Chỉ click chuột vào `<div class="acc-btn">`; không phải button, không focus/keyboard, không `aria-expanded`/`aria-controls`. |
| `public/site/about.html:1125-1188` | 5 cùng pattern | Như trên. |
| `public/site/index-2.html:1077-1140` | 5 cùng pattern | Như trên. |
| `public/site/project-details.html:387-450` | 5 cùng pattern | Như trên. |
| `public/site/service-details.html:375-438` | 5 cùng pattern | Như trên. |

- `public/site/assets/js/main.js:632-656` triển khai custom accordion: click đóng item đang mở, hoặc đóng mọi item khác rồi mở item click. Đây là **single-open**, animation `slideUp/slideDown(300)`.
- CSS display/visual state tại `public/site/assets/css/main.css:7398-7486` (FAQ static) và `:6667-6754` (các vùng `.faq-items`); icon đổi `+` sang `−` bằng pseudo-element khi class `.active`.
- Hai CSS block có responsive text/padding (`main.css:6690-6695,6717-6720,6732-6740` và `:7422-7427,7449-7452,7464-7472`) nhưng không có `prefers-reduced-motion`. Vì vậy animation jQuery 300ms vẫn chạy với người yêu cầu giảm chuyển động.
- `main.css:8544-8723` chứa selector `.accordion-single`, nhưng không có markup hay JavaScript tương ứng trong codebase; coi là CSS legacy không dùng, không nằm trong scope sửa tối thiểu.

## Homepage, blog và generic course

- Homepage không có accordion/disclosure tĩnh (`resources/views/home.blade.php`); chỉ có HTML About raw tại `:300`.
- Blog detail không có accordion tĩnh (`resources/views/blog_detail.blade.php:108-121`); `$blog->content` render raw tại `:111`. Controller chỉ chọn bài publish tại `app/Http/Controllers/BlogController.php:37-45`.
- Generic course detail không có accordion tĩnh; `$course->content` render raw tại `resources/views/course_detail.blade.php:66-71`. Ba DPM pages cũng render field này ở `digital-performance.blade.php:73`, `facebook-community.blade.php:37`, `data-analysis.blade.php:37`.
- Không có CSS/JS contract cho `<details>` do nội dung CMS tạo ra ngoài scope `.dpm-page`; cần kiểm tra dữ liệu production nếu muốn khẳng định “không có disclosure” trong các body động.

## Phạm vi sửa nhỏ, an toàn nếu được duyệt

1. Chỉ sửa 3 view curriculum DPM: thay `<h3>` bên trong `summary` bằng phần tử phrasing có class title, rồi đổi selector CSS tương ứng. Giữ native multi-open và không thêm JavaScript/ARIA dư thừa.
2. Chỉ bổ sung indicator `+`/`−` cho `.dpm-data-table summary` trong `digital-performance.css`; không thay đổi markup hay hành vi.
3. Không đụng `main.js`, `main.css`, và 5 static HTML ở phase này trừ khi chủ sở hữu muốn sửa trực tiếp các URL legacy. Sửa chúng đúng cách là một scope riêng: đổi trigger thành `<button>`, liên kết panel/id, đồng bộ ARIA + keyboard, và tôn trọng reduced motion.

## Câu hỏi chưa giải quyết

- Production có bài Blog, About hoặc content Course nào chứa `<details>`/`.accordion-box` từ CKEditor không? Repository không có DB dump nên chưa thể xác nhận.
- Các static `/site/*.html` có còn được quảng bá hoặc nhận traffic ngoài route Laravel không? Nếu không, nên quyết định redirect/chặn riêng thay vì mở rộng scope DPM.
