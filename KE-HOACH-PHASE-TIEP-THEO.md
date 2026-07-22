# Kế hoạch Phase tiếp theo — cho Codex/Claude Code thực thi

> Tài liệu này do Claude (Cowork) soạn sau khi đọc trực tiếp source tại
> `C:\App\laragon\www\tanh` (bản tháng 3/2026, đã có Courses/Leads/Testimonials).
> Tuân thủ AGENTS.md của repo: tiếng Việt, thay đổi nhỏ, không mở rộng scope,
> báo trước file ảnh hưởng, không đổi schema khi chưa xác nhận.

## Bối cảnh đã xác minh (KHÔNG cần làm lại)

Những mục này đã xong ở phase trước, Codex không đụng vào:
- Bug nội dung blog: `blog_detail.blade.php` đã render `{!! $blog->content !!}` đúng.
- Route `contact` đã tồn tại, có trang contact + form lead (`lead.store`, có `@csrf`).
- Nội dung theme "agency" đã thay bằng tiếng Việt định vị personal brand bán khóa học.
- Header/footer đã có fallback `data_get(...)` lịch sự, alt ảnh phần lớn có nghĩa.

## PHASE A — Sửa lỗi còn tồn (ưu tiên cao)

### A-1 · [P1] Asset đường dẫn tương đối → vỡ CSS/JS ở route 2 cấp
**Vấn đề:** mọi asset đang là `site/assets/...` (tương đối). Với route mới
`/courses/{slug}` (2 cấp), trình duyệt sẽ tìm `/courses/site/assets/...` → 404,
trang chi tiết khóa học gần như chắc chắn vỡ giao diện. (Trang `/ten-bai.html`
1 cấp nên "vô tình" chạy được.)
**Việc:** đổi toàn bộ `src=`/`href=` asset tĩnh trong các file layout + view
frontend sang `{{ asset('site/assets/...') }}`. Bao gồm cả favicon/logo nếu là
đường dẫn từ storage (kiểm tra giá trị DB có sẵn `/storage/...` hay không trước
khi bọc asset()).
**File ảnh hưởng:** `layouts/master.blade.php`, `layouts/header.blade.php`,
`layouts/footer.blade.php`, `home.blade.php`, `about.blade.php`,
`portfolio.blade.php`, `life.blade.php`, `blogs.blade.php`,
`blog_detail.blade.php`, `contact.blade.php`, `courses.blade.php`,
`course_detail.blade.php`, `login.blade.php`.
**Cách test:** mở `/courses/<slug-bat-ky>` → CSS/JS load đủ, tab Network không 404.

### A-2 · [P1] Menu chính ẩn trên desktop
**Vấn đề:** `header.blade.php` — cụm `.mean__menu-wrapper` vẫn gắn `d-none`;
desktop không có thanh menu ngang (Trang chủ / Giới thiệu / Case Study / Blog /
Khóa học / Liên hệ), người dùng phải bấm hamburger.
**Việc:** hiện menu ngang từ breakpoint `lg` trở lên (vd đổi `d-none` →
`d-none d-lg-block`, kiểm tra CSS theme để menu hiển thị đúng); giữ hamburger +
offcanvas cho mobile. Cẩn thận meanmenu clone nav gây menu đôi — kiểm tra init
trong `site/assets/js/main.js`, chỉ áp dụng mobile.
**Cách test:** desktop ≥1200px thấy menu ngang bấm được; mobile 375px thấy
hamburger; không nhân đôi menu; console sạch.

### A-3 · [P2] jQuery nạp 2 lần
**Vấn đề:** `master.blade.php` nạp jQuery 3.0.0 (CDN, head) + 3.7.1 (local, cuối body).
**Việc:** bỏ bản 3.0.0 ở head. Kiểm tra không có script inline nào chạy trước
khi jQuery local nạp (script typing ở `home.blade.php` nằm cuối content, chạy
sau — nhưng PHẢI xác nhận thứ tự thực thi sau khi bỏ). Nếu có inline script cần
jQuery sớm, chuyển xuống sau dòng nạp jQuery local.
**Cách test:** console gõ `jQuery.fn.jquery` → "3.7.1"; typing effect ở hero vẫn
chạy; toastr vẫn hiện khi submit form lead.

### A-4 · [P2] Title/meta dùng chung mọi trang
**Việc:**
- `master.blade.php`: `<title>@yield('page_title', $siteName)</title>`;
  meta description: `@yield('meta_description', $seoDescription)`.
- Từng view set section tương ứng: Blog detail → title bài + description ngắn
  (`Str::limit(strip_tags($blog->content), 155)`); Course detail → title khóa học;
  các trang tĩnh → tên trang + brand.
- Thêm OG cơ bản: `og:title`, `og:description`, `og:image` (ảnh bài/khóa học,
  fallback logo), `og:url` (`url()->current()`).
- Bỏ/đổi `meta name="author" content="Gramentheme"`.
**Cách test:** view-source 4 trang (home, 1 bài blog, 1 khóa học, contact) →
title/description/OG khác nhau, đúng nội dung.

## PHASE B — Hoàn thiện trải nghiệm (sau khi Phase A pass QC)

### B-1 · [P2] Card blog ở trang /blog quá nghèo
Trang chủ card blog đã có mô tả; trang `/blog` chỉ có ảnh + tiêu đề.
**Việc:** thêm mô tả ngắn (`$row->description`) + ngày đăng
(`$row->created_at->format('d/m/Y')`) vào card `blogs.blade.php`, đồng bộ style
với card ở trang chủ. Không đổi controller trừ khi thiếu dữ liệu.

### B-2 · [P2] Trang blog detail thiếu meta + điều hướng
**Việc:** thêm ngày đăng dưới tiêu đề; khối "Bài viết khác" (3 bài mới nhất khác
bài hiện tại — cần sửa `BlogController@detail` truyền thêm biến); nút chia sẻ
Facebook/X/copy link (link share tĩnh, không cần SDK).
**Lưu ý:** thay đổi controller nhỏ, không đổi schema.

### B-3 · [P3] Chuyển inline <style> lớn ra file CSS riêng
`home.blade.php` và `contact.blade.php` đang chứa ~270 dòng CSS inline.
**Việc:** gom vào `public/site/assets/css/custom.css`, nạp sau `main.css` trong
master. KHÔNG đổi giá trị CSS trong lúc di chuyển (diff phải chứng minh chỉ move).

### B-4 · [P3] Lead form chống spam nhẹ
**Việc:** thêm honeypot field ẩn (input tên vô nghĩa, CSS ẩn; server bỏ qua
request nếu có giá trị) vào 2 form (home final CTA + contact). Kiểm tra
`LeadController@store` đã validate `name`, `phone` (bắt buộc, độ dài hợp lý),
`email` (email hợp lệ nếu có) — nếu thiếu thì bổ sung validate, KHÔNG đổi schema.

## Quy trình bắt buộc

1. **Trước khi code:** `git init` + commit baseline nếu repo chưa có git
   (hiện KHÔNG thấy `.git` trong project).
2. Mỗi mục = 1 commit riêng: `phase-a: A-1 asset()`, v.v.
3. Sau mỗi phase: dừng lại cho review (`/codex:review` hoặc dán diff cho Claude
   Cowork). Tối đa 3 vòng sửa/review mỗi phase.
4. Báo cáo theo format AGENTS.md (file sửa/tạo, route thay đổi, migration, cách
   test tay, rủi ro còn lại).

## Tiêu chí QC (Claude sẽ soát theo đây)

- **Chức năng:** các route chạy; course detail có CSS; menu desktop/mobile đúng;
  typing effect + toastr sống; pagination blog chạy.
- **Bảo mật:** `{!! !!}` chỉ với nội dung admin; form giữ `@csrf`; validate lead;
  không lộ `.env`; admin vẫn sau middleware auth.
- **Accessibility:** menu ngang dùng được bằng bàn phím; alt có nghĩa; tương phản
  chữ trên nền tối đạt; label/placeholder form rõ.
- **Responsive:** 360 / 768 / 1280 không tràn ngang, form 2 cột co về 1 cột.
- **Diff:** nhỏ, đúng scope, không refactor lạc đề, không đụng vendor/.
