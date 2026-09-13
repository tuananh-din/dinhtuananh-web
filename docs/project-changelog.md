# Changelog — tanh (Personal Brand, Laravel)

Ghi các thay đổi quan trọng theo phase. Mỗi mục 1 commit. Nhánh: `master`.

## Hiệu năng cuộn mobile public — 2026-09-13 (chưa commit)

- Touch, thiết bị không hover và người dùng bật Reduced Motion nay giữ native scroll: không khởi tạo GSAP, ScrollTrigger, ScrollSmoother hay SplitText app effect. Desktop fine-pointer + hover vẫn giữ hiệu ứng khi các vendor cần thiết có mặt.
- Hai callback scroll cũ được gộp thành một listener passive, cập nhật header/back-to-top bằng `requestAnimationFrame`; back-to-top và cuộn tới lỗi form dùng `auto` trên mobile/reduced-motion. WOW hiển thị nội dung ngay ở các path này.
- Hero typing chỉ chạy khi hero/tab đang hiển thị, chỉ một timer và được dọn khi pagehide. Swiper/plugin tùy chọn có guard DOM + vendor; coverflow vẫn chạy trên mobile. Loại dead GSAP preloader code không thể chạy.
- Bổ sung `PublicPerformanceMarkupTest` cho contract native motion, scroll listener và lifecycle typing. `node --check`, static contract và `git diff --check` pass. Chưa chạy PHPUnit/Artisan hay visual/device QA vì workspace thiếu PHP CLI, `vendor/` và local Laravel runtime; cần test sau deploy tại 375px/320px, reduced motion, desktop và thao tác menu/form/back-to-top.

## Accessibility accordion static legacy — 2026-09-13 (chưa commit)

- Năm URL static legacy trực tiếp `/site/faq.html`, `/site/about.html`, `/site/index-2.html`, `/site/project-details.html` và `/site/service-details.html` nay dùng native `<details>/<summary>`.
- CSS/SCSS bổ sung focus, trạng thái mở nhiều mục với icon `+`/`−`, word-wrap responsive và reduced motion; chỉ gỡ handler accordion jQuery cũ sau source guard.
- Không đổi copy, route, backend, schema hoặc migration. Browser/Sass runtime QA còn chờ; việc giữ/sửa nội dung template cũ và hướng SEO vẫn cần owner quyết định.

## Accordion trang khóa học — 2026-09-13 (chưa commit)

- Chuẩn hóa 33 disclosure đang dùng thật trên ba landing khóa học bằng native `<details>/<summary>`: curriculum, FAQ và bảng số liệu. Không thêm JavaScript, ARIA mirror, route, controller, model, schema hay migration.
- Curriculum chỉ mở buổi đầu khi tải nhưng cho phép mở nhiều buổi để đối chiếu; FAQ và bảng số liệu đóng khi tải, độc lập và vẫn cho phép so sánh nội dung.
- Đổi title curriculum từ heading sang `span.dpm-module-title` có thể wrap; bảng số liệu có nhãn chính xác “Xem bảng số liệu và cách tính CPL”, icon `+`/`−`, surface/left accent khi mở, hover/pressed và target 64px. Giữ focus native/global và table scroll cục bộ trên mobile.
- Bổ sung regression test cho HTML render: item đầu mở, FAQ/data đóng, không có heading trong summary và nhãn CPL. Static QA `git diff --check` cùng kiểm tra dấu ngoặc CSS đã đạt; chưa chạy PHPUnit/Artisan vì checkout thiếu PHP CLI và `vendor/`, chưa có visual/keyboard/screen-reader pass do local không render Laravel.
- Rủi ro cần quyết định riêng: `/site/faq.html`, `/site/about.html` và `/site/index-2.html` đều trả HTTP 200 production nhưng dùng accordion template cũ với nội dung chưa xác minh. Đây là static file trong `public/site`, không phải ba landing Laravel đang sửa tại `/courses/digital-performance-management`, `/courses/facebook-community-growth-system` và `/courses/data-analysis-visualization`. Không tự redirect/xóa/viết nội dung mới trong phase này.

## Typography public — 2026-09-13 (chưa commit)

- Thêm lớp token typography ở cuối `custom.css`: Be Vietnam Pro cho public text/display, scale semantic từ display đến meta, line-height/letter-spacing theo vai trò, và foreground token light/dark. Link text light dùng `#4d7511` (5.43:1 trên nền trắng); không thêm font, dependency hay weight mới.
- Khôi phục focus keyboard toàn site bằng `:focus-visible` 3px thắng legacy `outline:none!important`; CTA có tối thiểu 48px, cho phép text wrap và footer newsletter xếp lại trên mobile.
- Các landing DPM được override scope `.dpm-page`: helper, caption có thông tin, form, error/success, FAQ, metadata và CTA tối thiểu 14px/15px; input giữ 16px. Metadata case study và eyebrow article dùng token BVP trong `.article-page`.
- Home đổi H6 trang trí thành paragraph `.type-eyebrow`; `/courses` có eyebrow paragraph và H1 đúng nghĩa. Thêm regression test `PublicTypographySemanticsTest` cho hai contract này. Không có migration, route, controller, model, CMS data hay copy thay đổi.
- Static QA pass: `git diff --check`, kiểm tra braces CSS và cascade. Chưa chạy PHPUnit vì workspace thiếu `php` CLI và `vendor/`; chưa có visual pass vì local không render Laravel. Cần test tại 1440/768/375/320, zoom 200%, Tab/focus và light/dark sau khi có môi trường đầy đủ. Giữ import Kanit/Big Shoulders cho tới khi font network/computed-style audit đủ điều kiện xóa an toàn.

## Blog knowledge hub `/blog` — 2026-09-13 (chưa commit)

- Chuyển `/blog` từ listing theme chung thành knowledge hub: hero nêu rõ Digital Marketing, Performance & Data; panel tìm kiếm/chủ đề; state kết quả rõ ràng; CTA nhẹ, có điều kiện tới khóa học. Copy chỉ dùng định vị đã chốt, không thêm nội dung, số liệu, author, tag, featured/popular/related hoặc ảnh giả.
- Dropdown chỉ hiện chuyên mục có ít nhất một bài `is_published=1`; giữ search title/description, category, phân trang và route hiện hữu. Query dạng mảng được chuẩn hóa về rỗng để URL public không hợp lệ không gây lỗi.
- Thêm title, meta/OG description và canonical theo contract: category hợp lệ (kể cả page hợp lệ) tự canonical; search hoặc category không hợp lệ canonical về `/blog`. OG image vẫn dùng Setting đã có qua layout, không tạo asset mới.
- Card dùng `article`, `time`, category CMS, ảnh thật lazy 16:9 và excerpt thật từ description/content; ảnh thiếu không dùng thumbnail generic. CSS mới chỉ scope `.blog-page`, có lưới 3/2/1 cột, focus, light/dark và reduced motion.
- Bổ sung `BlogKnowledgeHubTest` cho metadata/OG fallback, category public, canonical/filter/pagination, empty state, query không scalar và search `0`. Không có migration, route, model, footer hoặc newsletter backend/form nào thay đổi.
- Final re-review tĩnh không còn P0/P1/P2 và `git diff --check` đã pass. Chưa chạy PHPUnit/Artisan vì workspace thiếu PHP CLI và `vendor/`; cần chạy targeted suite rồi `php artisan test` trong môi trường Laravel đầy đủ. Sau deploy cần smoke test thủ công tại 1440px/768px/375px: grid/overflow, reset ≥44px, ảnh thiếu, category/title dài, light/dark, Tab/focus/Enter, reduced motion và source meta/canonical.
- Theo dõi SEO riêng: bài live có slug legacy bị ghép dạng `httpsdinhtuananhcom...` vẫn hoạt động. Chỉ làm sạch slug cùng một 301 redirect từ URL legacy trong phạm vi route/SEO riêng; không sửa trực tiếp trong phase này để tránh mất URL đã index.

## Trang Giới thiệu — Instructor Profile — 2026-09-13

- Làm lại `/about` theo định vị học Digital Marketing thực hành: hero nêu giá trị học, nhóm nhu cầu người học, nội dung định hướng, khóa học, case study/blog đã công bố, câu chuyện CMS và CTA đến khóa học/liên hệ.
- Chỉ đọc Course `is_active=1`, CaseStudy/Blog `is_published=1`; Course rỗng có empty state trung thực, nhóm evidence rỗng được ẩn. Không dùng ảnh legacy làm minh chứng.
- Không render Service, Testimonial hoặc “Cách học tại đây” vì chưa có xác nhận nội dung, phương pháp và quyền công bố; bỏ progress/phần trăm tự đánh giá, contact link rỗng và địa chỉ cá nhân.
- `/about` có title/meta/OG/canonical riêng, fallback không rỗng, strip HTML cho mô tả. Layout escape metadata động trước khi render để tránh đưa CMS text thô vào head; Person JSON-LD vẫn giữ nguyên.
- Thêm test contract cho lọc public, empty state, metadata/OG/canonical/JSON-LD, fallback và escaping. `git diff --check`, kiểm tra cú pháp JS và kiểm tra tĩnh CSS/Blade đã pass. Chưa chạy được PHP Artisan test/full build ở workspace vì thiếu PHP CLI, `vendor/` và dependency local; cần chạy lại trong môi trường Laravel đầy đủ và visual QA sau deploy.

## Homepage conversion — 2026-09-12

- Trang chủ chuyển trọng tâm sang chọn khóa học và tư vấn lộ trình: hero có CTA khóa học/tư vấn rõ ràng, thêm luồng định hướng theo nhu cầu và đưa khóa học lên trước phần dịch vụ chung.
- Nội dung dịch vụ, case study, blog và testimonial được trình bày theo bằng chứng CMS hiện có: chỉ dùng nhãn Case Study khi có bản ghi xuất bản; ảnh legacy là “Hình ảnh hoạt động”; ẩn toàn bộ testimonial khi chưa có dữ liệu, không giữ placeholder hay tự tạo KPI/nhận xét.
- Giữ nguyên dữ liệu `HomeController`, routes, JSON-LD và hợp đồng các form; tăng khả năng dùng trên mobile/dark mode, nhãn form hiển thị rõ và tôn trọng reduced motion.
- Không có migration, route, model hoặc controller mới. Kiểm tra tĩnh diff/Blade/CSS đã thực hiện; PHP Artisan test và Vite build đầy đủ bị chặn do môi trường không có PHP/dependencies Vite khả dụng và không truy cập được Docker socket. Cần chạy lại build/test cùng kiểm tra giao diện sau deploy trên Tino cPanel.

## Phase E — 2026-07-23 (fix tồn đọng audit + bug P0 social + build-out)

- `8af7e7a` **E-01** `RouteServiceProvider::HOME` `/home` → `/admin` (hết 404 khi guest đã đăng nhập mở `/login`).
- `5a4e764` **E-02** JSON-LD 2 partial thêm `JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT` — chặn stored XSS admin→public.
- `e74b71a` **E-03** CKEditor upload bỏ `?_token=` trong URL, dùng custom adapter (`public/app/assets/js/ckeditor-csrf-upload-adapter.js`) gửi header `X-CSRF-TOKEN`.
- `775b0eb` **E-04** Dọn trang login: title theo brand, nền qua `asset()`, bỏ link chết + footer template, thêm checkbox "Ghi nhớ đăng nhập".
- `be2a7dd` **E-05 (P0)** Migration `2026_07_23_000002_add_social_columns_to_about_table` thêm 4 cột `facebook/instagram/linkedin/x` (string 191, nullable) vào bảng `about` + validate `nullable|url|max:255` — **sửa lỗi 500 khi lưu admin profile**.
- `7ed7fa9` **E-06** JSON-LD Person thêm `sameAs` từ 4 URL social (bỏ khi rỗng).
- `3b36234` **E-08** Admin lead: `Lead::STATUSES` + relation `course()`; lọc theo trạng thái, đếm theo trạng thái, hiện tên khoá học thay Course ID.
- `24cdfa1` **E-09** `og:type=article` cho blog detail + 4 thẻ `twitter:*` toàn site.
- `7a4d969` **E-07** Xoá dead code `SocialController` + model `Social` (GIỮ bảng `socials`).

## Phase D — 2026-07-23 (SEO structured data, bảo mật, email lead)

- `ada64fe` D-04 throttle login (`throttle:5,1`).
- `118dfd5` D-01 unique slug blogs (migration `add_unique_index_to_blogs_slug`), giữ slug khi edit.
- `f19dc39` D-02 admin delete GET→POST 6 module + `@csrf` + null-safe.
- `0d7a9fb` D-03 JSON-LD `@stack('structured_data')` + partial article/person.
- `c7251c8` D-05 email lead: Mailable `NewLeadNotification` + config `mail.lead_notify` (mail fail không chặn lưu lead). **Cần cấu hình SMTP thật + `LEAD_NOTIFY_EMAIL` — xem deployment-guide.**

## Phase C — 2026-07-23 (SEO on-page + cleanup)

- `13922b9` C-01 throttle lead form. `0557b9b` C-02 canonical + sitemap.xml + robots.txt. `82f4287` C-03 trang 404 brand. `2c...` C-04 tách inline CSS. `9aa98a0` C-05 accessor `image_url`. `9bb80eb` C-06 memoize view composer.

## Audit toàn codebase + fix A1–A9 — 2026-07-23 (lỗi legacy gốc)

- A1 (CRITICAL) chặn RCE upload CKEditor. A2 (HIGH) sửa cột `code_footer`. A3 (HIGH) `findOrFail` 6 admin edit. A4/A5 validate store + rule image. A6 giữ path khi edit không upload. A7/A8 UI logout POST + honeypot lead. A9 helper xoá file rác conservative.

## Phase A + B — 2026-07-22 (nền tảng public + SEO cơ bản)

Trang public (home/about/courses/blog), meta SEO/OG cơ bản, responsive.

## Phase F — 2026-07-30

- `f4403ed` **F-5** Cập nhật README theo trạng thái project.
- `ae3d147` **F-6** Đánh dấu hoàn tất các kế hoạch Phase C, D và E.

## Phase G — 2026-07-30 (email lead thật)

- `34d5a05` **G-1** Thêm command `lead:test-notify` để kiểm tra gửi email lead không lưu dữ liệu.
- `0056dd8` **G-2** Cập nhật Mailable thông báo lead.
- `2585901` **G-3** Bổ sung test cho luồng thông báo email lead.
- `72c8385` **G-4** Cập nhật hướng dẫn Gmail SMTP và kiểm tra email lead khi deploy.

## Phase H — 2026-08-04 (Admin + đồng bộ schema)

- `4bb18f1` **H-0** Migration idempotent bổ sung `code_footer` cho bảng `setting`.
- `e6018e9` **H-1** Cập nhật hướng dẫn deploy cho cấu hình production.
- `93a7590` **H-2** Chuẩn hóa thông báo flash sau khi lưu các mục Admin.
- `c44cf30` **H-3** Thêm Dashboard Admin và route `/admin`.
- `9ef905e` **H-4** Quản lý Lead: tìm kiếm, xuất CSV và xóa mềm bằng `deleted_at`.

## Auto-deploy cPanel — 2026-08-04

- `f19fc34` Thêm `.cpanel.yml` cho cPanel Git Version Control deploy và cập nhật `.gitignore` cần thiết.

## NHÓM 1 — 2026-08-04 (thu lead + chuyển đổi)

- `a7131e7` **1.1** Thêm bảng `subscribers`, form newsletter và đồng bộ Brevo non-blocking.
- `6e30cdf` **1.2** Thêm trang cảm ơn `/cam-on` và `@stack('conversion')` cho mã chuyển đổi.
- `1da1279` **1.3** Thêm lead magnet, bảng `lead_magnets`, gửi email tải tài liệu và CRUD Admin.
- `6806aff` **1.4** Bổ sung Dashboard phân tích lead theo nguồn, khoảng thời gian và trạng thái.

## NHÓM 2 — 2026-08-04 (SEO + traffic)

- `4feabf7` **2.1** Thêm JSON-LD Course cho trang chi tiết khóa học.
- `45812f5` **2.2a** Thêm bảng `categories`, pivot `blog_category` và quan hệ Blog/Category.
- `a3fa6b2` **2.2b** Thêm CRUD Chuyên mục trong Admin và menu sidebar.
- `da7a856` **2.2c** Form Blog chọn nhiều chuyên mục, lưu bằng `sync()`.
- `f6cee15` **2.2d** Thêm tìm kiếm title/description và lọc blog public theo chuyên mục.
- `7455c82` **2.3** Thêm JSON-LD BreadcrumbList cho blog và khóa học.
- `1955d76` **2.4** Thêm `loading="lazy"` cho ảnh nội dung công khai.

## NHÓM 3 — 2026-08-04 (accessibility + branding Admin)

- `e78a6a1` **3.2** Thêm skip-to-content, focus-visible, aria/alt cơ bản và cải thiện accessibility trang public.
- `8412766` **3.3** Đồng bộ branding Admin theo nhận diện site bằng CSS và asset có sẵn.

## Review fix — 2026-08-04

- `0ded470` Đồng bộ subscriber lead magnet lên Brevo theo cách non-blocking; bỏ branding trùng và bổ sung `.visually-hidden`.

## NHÓM 4 — 2026-08-04 (vận hành)

- `47571a0` **health** Thêm route công khai `/health`: kiểm tra DB, trả JSON `ok` (200) hoặc `error` (503).
- `8175ec2` **backup-db** Thêm command `backup:db`: `mysqldump` qua `MYSQL_PWD`, nén vào `storage/app/backups`, dọn bản quá 14 ngày; sẵn dùng với cron hằng đêm.

---

**Ghi chú:** DB local có 2 blog seed test (`bai-mau-1-facebook-ads`, `bai-mau-2-personal-brand`) — xoá trước khi go-live nếu không cần.

## NHÓM 5 — 2026-08-05 (liên hệ nổi + UX blog/form)

- `9ff7a54` **5.4** Hoàn thiện UX liên hệ nổi, blog và form; bổ sung checkbox chọn chuyên mục khi tạo/sửa blog.

## NHÓM 6 — 2026-08-05 (vận hành Admin + SEO/media/CLS)

- `fc26ec1` **6.1** Admin quản lý subscriber và xuất CSV; Dashboard/sidebar có lối vào module Subscriber.
- `95364b4` **6.2** Sitemap chỉ đưa nội dung hợp lệ; thêm `noindex` cho trang cảm ơn.
- `6010ee0` **6.3** Thêm command `media:orphan-report` để báo cáo ảnh media không còn được tham chiếu.
- `8eaa4eb` **6.4** Điều chỉnh trang chi tiết khóa học và CSS để giảm CLS.

## NHÓM 7 — 2026-08-05 (nội dung khóa học, breadcrumbs, lỗi và bảo mật)

- `31d74c8` **7.1** Hoàn thiện hiển thị testimonial cho trang chi tiết khóa học.
- `0055d49` **7.2** Thêm breadcrumbs cho Blog và Khóa học.
- `ec2c061` **7.3** Thêm trang lỗi 500/503 và kênh log daily.
- `e7be28f` **7.4** Thêm middleware SecurityHeaders và test header bảo mật.

## NHÓM 8 — 2026-08-05 (xuất bản blog + RSS)

- `34eb4b6` **8.1a** Migration bổ sung `blogs.is_published`.
- `f711e40` **8.1b** Áp dụng trạng thái xuất bản vào luồng admin/public/sitemap; blog chưa xuất bản không lộ công khai.
- `c024d7d` **8.2** Bổ sung khối khóa học liên quan trong trang blog.
- `bbc1f6e` **8.3** Thêm RSS tại `/feed`.
- `138c19c` **8.4** Bổ sung coverage test cho NHÓM 8.
- `2bfb667` **review-fix-2** Rà soát và sửa các điểm UI/SEO liên quan sau NHÓM 8.

## NHÓM 9 — 2026-08-05 (preview blog, upload ảnh và 404)

- `45493d4` **9.1** Thêm preview blog từ Admin.
- `38fda8e` **9.2** Bổ sung lọc/danh sách Admin Blog.
- `1f8362b` **9.3** Thêm `App\Support\ImageOptimizer`: ảnh upload được giới hạn 1600px cạnh dài và chất lượng JPG 82.
- `44fb54e` **9.4** Cải thiện trang 404 với gợi ý điều hướng.

## NHÓM 10 — 2026-08-05 (preview khóa học + thùng rác Blog)

- `f71ba54` **10.1** Thêm preview khóa học từ Admin.
- `94d8831` **10.2** Bổ sung lọc danh sách khóa học trong Admin.
- `6723255` **10.3a** Migration bổ sung `blogs.deleted_at`.
- `60338cc` **10.3b** Admin xóa blog theo cơ chế xóa mềm và có trang thùng rác.
- `f24d17c` **10.4** Bổ sung empty state cho danh sách blog công khai.

## NHÓM 11 — 2026-08-05 (preloader, hero và liên hệ)

- `01eb388` **11.1** Thêm preloader tùy biến.
- `78d32a4` **11.2** Preloader hiển thị tối thiểu 800ms, lưu trạng thái bằng `sessionStorage`, có fallback WOW; đồng bộ header inline script.
- `7513e48` **11.3** Tinh chỉnh hero trang chủ.
- `8f5f932` **11.4** Tinh chỉnh trang liên hệ.

## NHÓM 12 — 2026-08-05 (dark mode, font và cache asset)

- `53e56b8`, `2b146e6`, `a119411`, `e0d299d`, `1b24ea2` **12.1–12.3b** Hoàn thiện dark mode và font Be Vietnam Pro trên public/admin.
- `93ba613` **12.4** Thêm cache-busting asset theo `?v=filemtime`.

## NHÓM 13 — 2026-08-05 (responsive mobile)

- `3fd7dbb`–`6d67f53` **13.1–13.9, 13.5b** Hoàn thiện mobile menu, logo light mode, CTA, marquee và hero shape; gồm các review-fix giao diện sau triển khai.

## NHÓM 14 — 2026-08-05 (branding Admin)

- `ae433a3` **admin-title** Đổi tiêu đề Admin thành `Quản trị — {tên site}`; header Admin dùng logo từ Setting, fallback tên site khi chưa có logo.

## NHÓM 15 — 2026-08-05 (polish UI sau QC)

- `2bc1fe3` **15.1** Menu public đánh dấu trang hiện tại (`active`, `aria-current`) cho desktop và menu mobile.
- `b492598` **15.2** Đồng bộ giao diện dark mode cho các select public.
- `25065d4` **15.3** Sửa icon chia sẻ X/Twitter và độ tương phản nút chia sẻ blog.
- `9c59972` **15.4** Căn giữa, bọc card và responsive khối gợi ý bài viết trên trang 404.
- `e6d53cd` **15.5** Trang Case Study vào thẳng lưới dự án 3:2, không dùng lại banner trang Giới thiệu.
- `beaabe8` **15.3b (review-fix)** Khắc phục icon chia sẻ trắng-trên-trắng: màu/viền riêng cho light và dark mode, dùng `fab fa-twitter` tương thích theme.

## Blog editorial, NHÓM 17, Case Study và Admin — 2026-08-17

- `e4f2f92` Blog detail nhận Article Design System, TOC desktop và khung nội dung mới.
- `5ed5977` Sửa TOC Blog sticky bằng cách cho aside stretch để có khoảng cuộn.
- `fd0eabe` Admin Blog/Khóa học cho phép nhập slug, chuẩn hóa và chống trùng.
- `009cd82` Cân bằng card hai cột và thêm fallback cho thanh skill/progress.
- `e1d7080` Thêm fallback 1.5 giây cho các animation text-reveal bị kẹt.
- `0211ac5` Ẩn shape gây rối và căn giữa các section có ít nội dung.
- `f27426d` Cân lại banner trang Giới thiệu cho desktop/mobile và hai theme.
- `f39c3e7` Tắt smooth scroll trên bài viết để TOC sticky hoạt động, ẩn caption trùng trên banner About.
- `4b6d0de` Thay icon tạm của floating contact bằng SVG inline Zalo/Messenger chuẩn brand.
- `568aa7d` Thêm migration, model, CRUD Admin và preview cho Case Study.
- `62c1e01` Thêm detail `/portfolio/{slug}`, SEO/KPI và fallback Case Study public/home.
- `eb3ca9a` Căn giữa Blog khi không có TOC và bật Image Caption cho CKEditor Blog.
- `9ad07f5` Chỉ giữ một logo Admin ở sidebar và nâng tương phản menu.
