---
title: Audit typography public site
date: 2026-09-13
scope: Read-only audit — Laravel Blade public Home, About, Blog, Courses
status: complete
---

# Audit typography public site

## Tóm tắt

Hệ public đã có phần tốt: body mặc định là Be Vietnam Pro, Blog article có token riêng và selector được scope chặt, viewport không chặn zoom. Tuy nhiên hệ typography chưa thống nhất: layout đang tải ba font chữ, các heading/menu/CTA template vẫn dùng Big Shoulders Display hoặc Kanit; token chỉ tồn tại ở `article.css`. Rủi ro ưu tiên là chữ 10–13px trên landing khóa học và focus keyboard của link/input bị rule template cũ triệt tiêu.

Không sửa code trong audit này. Không đụng route, data, backend hoặc dependency.

## Phạm vi đã kiểm

- Layout: `resources/views/layouts/master.blade.php`, `header.blade.php`, `footer.blade.php`.
- CSS: `main.css`, `custom.css`, `article.css`; thêm `digital-performance.css` vì ba view course nạp trực tiếp.
- Public views: Home, About, Blog listing/detail, Courses listing/detail, ba landing course.
- Asset font thực tế trong `public/site/assets/webfonts`.

## Font load, asset và weight thực tế

| Nguồn | Bằng chứng | Family / weight có thể dùng | Nhận định |
| --- | --- | --- | --- |
| Google Fonts | `resources/views/layouts/master.blade.php:106-108` | Be Vietnam Pro normal **400, 500, 600, 700**, `display=swap` | Không có font text self-hosted; request không có italic hoặc 800. |
| CSS import template | `public/site/assets/css/main.css:22-23` | Big Shoulders Display (provider không chốt weight trong URL); Kanit normal/italic 100–900 | Tạo thêm hai request chain trong CSS. Cả hai vẫn được selector public dùng. |
| Local assets | `public/site/assets/webfonts/fa-*.woff2/.ttf` | Chỉ Font Awesome: thin 100, light 300, regular 400, solid/duotone 900, brands 400 | Đây là icon font, không phải fallback text cho Be Vietnam Pro/Kanit/Big Shoulders. `all.min.css:6` khai báo chúng. |

### Xung đột family/weight

- `main.css:44-55` đặt `body` là Kanit và uppercase; `custom.css:1066` ghi đè body sang Be Vietnam Pro, bỏ uppercase. Đây là override phụ thuộc thứ tự nạp, không phải token.
- Heading vẫn còn Big Shoulders Display + uppercase toàn cục ở `main.css:83-95`. Chỉ Blog article tự sửa lại Be Vietnam Pro ở `article.css:138-150` và `436-476`; landing course tự reset tại `digital-performance.css:1` (selector `.dpm-page ...`).
- Header desktop vẫn là Kanit 500, uppercase, tracking `.5px`: `main.css:1129-1140`. CTA `.theme-btn` là Kanit: `main.css:208-232`. Link social footer cũng là Kanit: `main.css:2065-2069`. Vì thế cùng một page hiện có Be Vietnam Pro, Big Shoulders Display và Kanit.
- Be Vietnam Pro chỉ request tới 700, nhưng các element kế thừa body yêu cầu 800 ở Home `custom.css:1401-1404`, Blog `1802-1808`, About `2273-2278`. Browser sẽ synthetic-bold 800 (hoặc fallback). Có thể hạ các use này về 700, không cần request weight mới.
- `article.css:485-488` cho phép italic nhưng request Be Vietnam Pro hiện không có italic; đây là synthetic italic/fallback, thấp ưu tiên.

## Scale, line-height, tracking đang có

| Khu vực | Current scale / line-height / tracking | Đánh giá |
| --- | --- | --- |
| Base template | body 16px/28px, h1 100px→38px, h2 55px→34px, h3 24px, h4–h5 18px, h6 16px tại `main.css:44-188` | Không phải scale token; breakpoint h2 không đơn điệu: 36px ở `:max-width 991` (`:148-151`) rồi 42px ở `:max-width 767` (`:153-156`). |
| Global public override | body line-height 1.7; `p/li/label/input/textarea/select` 16px, tracking `.01em`: `custom.css:1066-1069` | Đủ body reading, nhưng áp lên mọi component hiện tại/tương lai. |
| Home / About / Blog hub | Hero/section title dùng `clamp(40–82px)` với tracking âm `-.035em` đến `-.04em`; lead 18px/1.65–1.68; card heading 22–34px. Xem `custom.css:1355-1592`, `1811-1853`, `2181-2238`. | Visual scale tốt ở các page mới, nhưng là giá trị rời rạc theo component. |
| Blog article | Token cục bộ `--article-body-size:19px`, `--article-body-lh:1.7` ở `article.css:29-49`; h1 30–50/1.16, h2 25–34/1.3, h3 21–26/1.4, h4 19–21/1.45 tại `:138-159`, `:414-476`. | Mẫu tốt nhất hiện có: token + scope; chưa tái dùng được ngoài article. |
| Course landing DPM | Base 16px/1.75; h1 38–68/1.1, h2 29–44/1.25, h3 21/1.4. Toàn bộ CSS minify nằm ở `digital-performance.css:1`. | Có hệ riêng, nhưng hard-code nhiều size 10–15px; không dùng token chung. |

## Findings

### P1 — Link và input có thể mất visible focus

- `main.css:191-193` đặt `a { outline: none !important; }`. Rule focus global sau đó tại `custom.css:783-789` không có `!important`, nên không thắng outline của link. Header (`header.blade.php:95-108`) và footer (`footer.blade.php:13-24`, `:74-77`) không có focus style riêng.
- `main.css:74-77` đặt `input:focus { outline:none; }`; selector `:where(input...):focus-visible` ở `custom.css:783-795` có specificity thấp hơn. Input generic có thể chỉ còn border/box-shadow tùy cascade, không có outline nhất quán.
- Các scope Blog và DPM có khai báo focus riêng (`custom.css:1898-1904`, `digital-performance.css:1`), nhưng anchor rule `!important` toàn cục vẫn là rủi ro. Đây là regression accessibility, không chỉ là styling.

### P1 — Landing Courses hiển thị nhiều text 10–13px, kể cả form và thông tin cần đọc

`resources/views/courses/data-analysis.blade.php:17-18`, `digital-performance.blade.php:18-19`, `facebook-community.blade.php:16-17` đều nạp `digital-performance.css`. Trong file minify đó (tất cả tại dòng 1):

- 10–12px: eyebrow, hero-note (10px mobile), path labels/tags, subnav, figure caption, price label, label form, `small`, footnote/preview/chart labels.
- 13px: text link, outcome body, module number, form intro/input/error/success/FAQ answer/closing; breakpoint mobile vẫn cho body curriculum 13px.
- View tạo các phần này trực tiếp: DPM hero/form/FAQ ở `resources/views/courses/digital-performance.blade.php:31-35, 98-113, 117-124`; hai landing còn lại dùng cùng markup pattern.

Page zoom không bị vô hiệu (viewport `master.blade.php:32-34` không có `maximum-scale` hay `user-scalable=no`), nên browser zoom vẫn hoạt động. Nhưng px 10–13 không tôn trọng base size của người dùng và quá nhỏ cho mobile/low vision; form label 12px và helper 10–11px là cần nâng đầu tiên. Mục tiêu tối thiểu: text đọc được/label/error/FAQ >=14px; chỉ giữ 12px nếu là decorative, không critical và có tương phản đã kiểm.

### P2 — Typography source of truth bị chia bốn nơi, global selector dễ làm vỡ page mới

- `main.css:44-55`, `83-95`, `191-202`, `custom.css:1066-1069`, `article.css:29-49`, `digital-performance.css:1` đều đóng vai trò “base typography”. Không có token semantic dùng chung.
- `custom.css:1067` selector `body p, body li, body label, body input, body textarea, body select` force 16px/tracking `.01em` lên mọi view, CMS content và component mới. Nó phải được override lặp lại ở article/DPM.
- `main.css:62-65` xóa marker cho toàn bộ `ul`; `article.css:519-566` phải khôi phục riêng list đọc. Đây là ví dụ direct global reset làm phần content phải “vá” lại.
- `article.css:1-23` đang scope đúng (`.article-page`/`body.is-article`) và không dùng `!important`; đây nên là ranh giới giữ nguyên, không đưa selector toàn cục vào file này.
- `master.blade.php:100-105` nạp `article.css` trên mọi public route dù rule đã scope. Không gây leak, nhưng tăng CSS không cần thiết ngoài Blog/article; không phải blocker cho đợt token nhỏ.

### P2 — Semantic heading hierarchy chưa nhất quán

- Trang Courses listing không có H1: mở bằng `<h6>` rồi `<h2>` tại `resources/views/courses.blade.php:5-10`. Đây là lỗi hierarchy rõ ràng.
- Home dùng nhiều `<h6>` như visual eyebrow trước `<h2>`: `home.blade.php:55-60`, `90-95`, `271-274`, `294-300`. Chúng làm outline nhảy H1 → H6 → H2; About/Blog page mới đã dùng `<p>` eyebrow (`about.blade.php:57-68`, `blogs.blade.php:44-46`) là pattern nên theo.
- H1 trên Home, About, Blog listing, Blog detail và course detail nhìn chung đúng một lần: `home.blade.php:19`, `about.blade.php:37`, `blogs.blade.php:45`, `blog_detail.blade.php:75`, `course_detail.blade.php:45`; các DPM landing cũng có một H1 (`courses/digital-performance.blade.php:32`).
- Footer dùng `<h3>` chỉ để in brand name (`footer.blade.php:21-24`). Mức độ thấp hơn; cân nhắc `<p>`/brand text nếu không đại diện một section heading.

### P2 — Một số text public dưới 14px ngoài DPM

| Page/selector | Vị trí | Size | Nội dung thực tế |
| --- | --- | --- | --- |
| Home `.brand-eyebrow`, `.featured-pill`, `.homepage-hero-guide__eyebrow`, optional form note | `custom.css:11-22`, `154-162`, `1375-1382`, `1646-1650`; markup `home.blade.php:18, 42, 101, 317-319` | 12px | Eyebrow/badge acceptable nếu decorative; optional note 12px là text form. |
| Blog `.blog-category-chip`, `.blog-page__eyebrow` | `custom.css:412-420`, `1802-1808`; markup `blogs.blade.php:44, 81, 114-117` | 13px | Category/eyebrow; 800 không có font asset Be. |
| Course listing `.course-badge` / detail `.lead-note` | `custom.css:544-554`, `682-686`; markup `courses.blade.php:17-19` | 12px / 13px | Badge can be compact; lead note should be >=14px. |
| About `.about-profile-eyebrow`, `.about-profile-card-label` | `custom.css:2172-2179`, `2450-2456`; markup `about.blade.php:57, 68, 78, 101, 105, 112` | 12px | Visual meta; check contrast and retain only as non-critical meta. |
| Article case meta | `article.css:206-214` | 13px | Case-study labels; not normal article body, but same baseline concern. |

### P3 — Mobile scale and synthetic-font quality

- Root template h1/h2 are px breakpoint rules, not token/clamp (`main.css:97-161`). The H2 size grows at a narrower breakpoint as noted above; home sections inherit this because they do not set their own h2 scale.
- 12px labels with `.08em–.15em` tracking (`custom.css:1375-1382`, `2172-2179`; DPM CSS line 1) are visually smaller still in Vietnamese. Prefer high tracking only for all-caps decorative labels.
- Header has 16px Kanit + 0.5px tracking uppercase (`main.css:1129-1140`) while page content says Be Vietnam Pro. At 200% zoom layout must be tested for header nav, theme toggle and CTA wrapping; current CSS uses fixed `58px` button line-height (`main.css:208-232`).

## Phạm vi thay đổi nhỏ nhất đề xuất

Mục tiêu: token semantic cho typography public, không đổi route/data/backend/dependency và không redesign.

1. Chỉ bổ sung/điều chỉnh một block token ở cuối `public/site/assets/css/custom.css`, không sửa `main.css` vendor/template lớn:
   - `--font-sans`, `--font-display`, `--text-body`, `--text-ui`, `--text-meta`, `--lh-body`, `--lh-heading`, `--tracking-label`, `--focus-ring`.
   - Giá trị body dùng Be Vietnam Pro normal 400/500/600/700 đã request; map 800 hiện hữu về 700.
   - `--text-meta` đặt tối thiểu 14px; 12px chỉ để decorative token riêng nếu thật sự cần.
2. Dùng token để override có scope rõ: body/form control, heading public, `.theme-btn`, header menu và footer link. Quyết định một lần `--font-display`: giữ Big Shoulders chỉ cho display đã được duyệt, hoặc Be Vietnam Pro cho heading Vietnamese; không để Kanit còn rải trong menu/CTA/footer. Xóa import Kanit/Big Shoulders chỉ sau khi search không còn selector thực sự dùng chúng.
3. Sửa rule focus tại `custom.css:783-795` sao cho thắng legacy: override explicit `a:focus-visible` và `input:focus-visible` với specificity/`!important` tối thiểu cần thiết, cùng token focus; test header/footer, CTA, form, light/dark.
4. Trong `public/site/assets/css/digital-performance.css`, nâng text đọc/label/status/form/FAQ từ 10–13px lên token 14px; giữ scope `.dpm-page`. Vì file minify toàn bộ ở dòng 1, reformat trước khi chỉnh nếu team cần review line-by-line, nhưng không thay behavior/layout.
5. Chỉ chỉnh semantic markup tối thiểu: `courses.blade.php:8-9` thành eyebrow không-heading + một H1; các `home.blade.php` H6 eyebrow thành `<p>`/`span` có class token. Không cần đổi data hoặc heading text.
6. Giữ nguyên `article.css` scope/token hiện có; nếu cần liên kết token sau này thì map article token vào public token qua custom property, không đưa global selector vào article file.

### Files dự kiến nếu thực hiện

- Sửa: `public/site/assets/css/custom.css`, `public/site/assets/css/digital-performance.css`, `resources/views/courses.blade.php`, `resources/views/home.blade.php`.
- Có thể sửa một dòng load tại `resources/views/layouts/master.blade.php` **chỉ sau** khi xác nhận không còn Kanit/Big Shoulders dùng thực tế.
- Không cần migration, route, controller, model, package hay font dependency mới.

### Kiểm thử thủ công cần có

- Chrome/Firefox/Safari: 100%, 200% page zoom; 320px, 375px, 768px, desktop.
- Keyboard-only: skip link → header menu → theme toggle → CTA → footer; check visible focus trong dark/light.
- Screen-reader heading list: Home và Courses listing phải không còn H6 eyebrow và phải có đúng một H1.
- DPM form: label, hint, validation success/error, FAQ answer, caption vẫn đọc được ở mobile/light/dark.
- Network: chỉ còn font request được chọn; kiểm tra 400/500/600/700 không synthetic và không phát sinh 800.

## Câu hỏi chưa giải quyết

- Big Shoulders Display có phải display face được duyệt cho heading Vietnamese, hay mục tiêu là toàn bộ text/heading dùng Be Vietnam Pro? Quyết định này xác định có thể bỏ import Big Shoulders hay không.
- Có cần giữ 10–12px cho các nhãn purely decorative trên DPM? Nếu có, cần xác nhận contrast và không dùng cho label/form/status/FAQ/caption có thông tin.
