# PROJECT GAP AUDIT — tanh

> Rà soát tĩnh theo source, cấu hình mẫu, tài liệu và kết quả kiểm tra gần nhất ngày 24/07/2026. Mục tiêu là lập backlog hoàn thiện; **tài liệu này không thay đổi logic, schema hay cấu hình chạy thực tế**. Mọi nhận định không thể kiểm tra bằng source được đánh dấu **cần xác minh**.

## 1. Tình trạng hiện tại

Dự án là website personal brand/bán khóa học Laravel 10, có public site và CMS admin qua session login. Các luồng cốt lõi đã tồn tại: nội dung giới thiệu, blog, khóa học, lead, SEO cơ bản, upload ảnh và quản trị dữ liệu.

- Working tree sạch trước khi tạo tài liệu này; commit gần nhất là `ba5a0b1 docs: add project instructions`.
- `routes/web.php` cùng API hiện có 52 routes, trong đó admin được bọc `auth`, login và lead có rate-limit `5/phút`.
- `php artisan route:list --except-vendor` đã chạy thành công ở lần kiểm tra trước.
- Test hiện có 2 test example. Unit pass; feature `GET /` fail 500 khi chạy vì MySQL local từ chối kết nối. `phpunit.xml` chưa dùng database test riêng, nên chưa thể dùng kết quả này để kết luận trang chủ lỗi logic.
- Phase C–E đã được triển khai theo source/changelog. Một số file trong `plans/` vẫn ghi `Pending`, nên không phản ánh trạng thái thực tế.

### Không có Critical nào khác được xác minh từ source

Ngoài mục secret ở dưới, chưa thấy bằng chứng source về RCE, SQL injection trực tiếp, route admin public hoặc CSRF bị tắt. Tuy nhiên audit này không thay thế pentest, kiểm tra dependency CVE hoặc kiểm tra production server.

## 2. Những phần đã tương đối ổn

- [x] Phân tách public/admin rõ trong route, toàn bộ `/admin/**` yêu cầu `auth`.
- [x] POST form có CSRF; các route xóa admin đã chuyển sang POST + confirm UI.
- [x] Login và lead có throttle; login regenerate session, logout invalidate session/regenerate CSRF token.
- [x] Blog/course slug được tạo tránh trùng; `blogs.slug` và `courses.slug` có unique index.
- [x] Public course chỉ đọc bản `is_active=1`; `firstOrFail` được dùng ở các detail quan trọng.
- [x] Lead có honeypot, validation cơ bản, lưu được ngay cả khi email notification lỗi.
- [x] Upload thông thường giới hạn là ảnh 5 MB; upload `/storage` có helper xóa file cũ bảo thủ.
- [x] Public layout đã chuẩn hóa asset URL, canonical, OG/Twitter, JSON-LD được encode và có 404/sitemap/robots.
- [x] View composer tránh query lặp trong cùng request và các view có khá nhiều fallback khi About/Setting rỗng.
- [x] Có changelog/deployment guide, migration và CourseSeeder; Git history được chia theo phase khá dễ truy vết.

## 3. Backlog thiếu/yếu theo mức độ ưu tiên

### Critical

#### C-01 — Thông tin database không rỗng trong `.env.example`

- [ ] **Vấn đề:** `.env.example` chứa URL production-like cùng username/password database không rỗng. Không thể xác minh credential còn hiệu lực hay chỉ là dữ liệu cũ, nhưng file này có thể bị commit/chia sẻ cùng source.
- **Ảnh hưởng:** nếu credential còn dùng được, có nguy cơ truy cập trái phép database; kể cả khi đã hết hiệu lực, nó tạo thói quen lưu secret trong source.
- **Khu vực liên quan:** `.env.example`, lịch sử Git/remote và hệ thống database/email thực tế (cần xác minh).
- **Đề xuất:** chủ hệ thống kiểm tra credential có còn hoạt động; nếu có hoặc không chắc chắn thì rotate ngay tại nhà cung cấp. Thay `.env.example` bằng placeholder an toàn, rà soát Git history/remote và secret manager. Không ghi secret mới vào tài liệu hoặc commit.
- **Test sau sửa:** clone mới, copy `.env.example` sang `.env`, điền secret local riêng và chạy app; xác nhận `git grep` không còn credential thật. Việc xác minh rotate phải thực hiện bằng credential mới ngoài source.

### High

#### H-01 — Test chưa cô lập database và coverage hầu như bằng 0

- [ ] **Vấn đề:** `phpunit.xml` comment SQLite, feature test dùng DB môi trường; chỉ có 1 test `true === true` và 1 smoke test `/`.
- **Ảnh hưởng:** test không ổn định theo Laragon/MySQL, không phát hiện regression ở login, lead, CRUD, upload, slug hay route.
- **Khu vực liên quan:** `phpunit.xml`, `.env.testing` (chưa có), `tests/**`, migrations/seeders.
- **Đề xuất:** tạo môi trường test database riêng (SQLite file/in-memory hoặc MySQL test), tuyệt đối không dùng DB local có dữ liệu thật; thêm `RefreshDatabase` cho test DB; thay example tests bằng test theo luồng ưu tiên.
- **Test sau sửa:** tắt MySQL local vẫn chạy được toàn bộ suite nếu chọn SQLite; chạy `artisan test` 2 lần liên tiếp cho cùng kết quả; kiểm tra test không sửa DB dev.

#### H-02 — Quan hệ lead–course không được database bảo vệ

- [ ] **Vấn đề:** `leads.course_id` có Eloquent `belongsTo` nhưng migration không có foreign key; xóa course có thể để lead mồ côi. Public lead hiện cũng âm thầm bỏ `course_id` không tồn tại.
- **Ảnh hưởng:** dữ liệu CRM mất ngữ cảnh, báo cáo/admin hiển thị fallback `Course #id`; không rõ đây là quyết định nghiệp vụ hay thiếu sót.
- **Khu vực liên quan:** `database/migrations/2026_03_12_000002_create_leads_table.php`, `app/Models/Lead.php`, `Admin/CourseController`, `LeadController`, `admin/lead/index.blade.php`.
- **Đề xuất:** trước hết xác nhận nghiệp vụ muốn giữ lịch sử lead khi khóa học bị xóa. Phương án an toàn thường là chặn xóa course đã có lead hoặc foreign key `nullOnDelete`; cần migration được duyệt và kế hoạch dữ liệu cũ.
- **Test sau sửa:** tạo lead gắn course, thử xóa course và xác nhận hành vi đúng quyết định (bị chặn hoặc `course_id` thành null); lead list không lỗi.

#### H-03 — Trạng thái lead chưa được ép theo tập giá trị chuẩn ở server

- [ ] **Vấn đề:** `Lead::STATUSES` định nghĩa 5 trạng thái nhưng `Admin\LeadController@update` chỉ validate `required|string|max:50`.
- **Ảnh hưởng:** request thủ công hoặc thay đổi view có thể tạo status không thống nhất; filter/count/dashboard tương lai sai.
- **Khu vực liên quan:** `app/Models/Lead.php`, `app/Http/Controllers/Admin/LeadController.php`, `resources/views/admin/lead/index.blade.php`.
- **Đề xuất:** validate bằng `Rule::in(array_keys(Lead::STATUSES))`; cân nhắc test feature. Không cần migration nếu chỉ khóa input.
- **Test sau sửa:** admin gửi mỗi status hợp lệ thành công; status lạ trả validation error và bản ghi không đổi.

#### H-04 — Cấu hình production và email chưa được chứng minh end-to-end

- [ ] **Vấn đề:** code gửi email lead đã có nhưng cấu hình SMTP/`LEAD_NOTIFY_EMAIL` thực tế chưa được xác minh; environment production, cache, storage link, backup/migration cũng chỉ được mô tả trong tài liệu.
- **Ảnh hưởng:** lead vẫn lưu nhưng thông báo có thể thất lạc; deploy có thể lỗi ảnh `/storage`, URL canonical/sitemap hoặc lộ debug.
- **Khu vực liên quan:** `config/mail.php`, `.env`/hạ tầng (cần xác minh), `docs/deployment-guide.md`, `config/filesystems.php`, `config/app.php`.
- **Đề xuất:** dùng checklist staging/production không chứa secret trong Git; cấu hình SMTP thật, mailbox nhận lead, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` chuẩn; chạy backup/migrate/storage link/cache theo deployment guide.
- **Test sau sửa:** submit lead trên staging nhận đúng email và row DB; kiểm tra log khi SMTP down; mở ảnh upload, sitemap/canonical bằng domain thật; restore thử backup ở môi trường an toàn.

### Medium

#### M-01 — Validation CRUD chưa đồng đều và chưa có Form Request

- [ ] **Vấn đề:** validation đặt trực tiếp trong controller; một số field cho phép dữ liệu rộng hơn nhu cầu. Ví dụ Image admin chỉ bắt buộc/validate file ảnh nhưng không xác định enum `type`; service/skill không validate toàn bộ text optional theo giới hạn rõ ràng.
- **Ảnh hưởng:** dữ liệu không nhất quán, khó tái dùng rule và khó test khi form mở rộng.
- **Khu vực liên quan:** `app/Http/Controllers/Admin/{Image,Service,Skill,Blog,Course,Testimonial,About,Setting}Controller.php`.
- **Đề xuất:** không refactor hàng loạt ngay. Mỗi phase chỉ chuẩn hóa validation của module đang sửa; sau khi có test có thể tách Form Request theo từng module.
- **Test sau sửa:** gửi dữ liệu thiếu/sai type/quá dài qua HTTP; xác nhận lỗi validation, old input, và dữ liệu hợp lệ vẫn lưu.

#### M-02 — Mass assignment rộng trên gần như toàn bộ model

- [ ] **Vấn đề:** các model nội dung phần lớn dùng `$guarded = []`.
- **Ảnh hưởng:** controller hiện dùng `only()` khá an toàn, nhưng một thay đổi sau này dùng `$request->all()` có thể ghi field không chủ đích (flag active, SEO, status).
- **Khu vực liên quan:** `app/Models/{About,Blog,Course,Image,Lead,Service,Setting,Skill,Testimonial}.php`.
- **Đề xuất:** không đổi đồng loạt vì có rủi ro legacy. Khi chạm từng model, lập `$fillable` rõ cho field được phép và có test create/update; hoặc duy trì `only()` như quy ước bắt buộc được document hóa.
- **Test sau sửa:** thử gửi field ngoài whitelist và xác nhận không bị ghi; CRUD hợp lệ vẫn hoạt động.

#### M-03 — Chưa có audit trail và quan sát lỗi nghiệp vụ

- [ ] **Vấn đề:** chỉ có `Log::error` khi gửi email lead và `Log::warning` khi xóa upload thất bại; không có audit admin, health check, error monitoring hay quy ước correlation/request id.
- **Ảnh hưởng:** khó truy vết ai sửa/xóa nội dung và khó phát hiện lỗi production trước khi người dùng báo.
- **Khu vực liên quan:** controllers admin, `app/Http/Controllers/LeadController.php`, `app/Http/Controllers/Controller.php`, `config/logging.php`, hạ tầng monitoring (cần xác minh).
- **Đề xuất:** trước hết chuẩn hóa log có context không nhạy cảm cho sự kiện lead/email/upload; cân nhắc audit trail chỉ khi có yêu cầu vận hành. Dịch vụ Sentry/health check là scope hạ tầng cần duyệt riêng.
- **Test sau sửa:** mô phỏng lỗi mail/xóa file, kiểm tra log có id/sự kiện nhưng không chứa password, token hay PII quá mức; kiểm tra rotation log trên server.

#### M-04 — Content HTML/snippet admin có bề mặt XSS có chủ đích

- [ ] **Vấn đề:** `code_header`, `code_footer` và rich content render raw. JSON-LD đã được encode, nhưng raw snippets vẫn thực thi như mục đích quản trị.
- **Ảnh hưởng:** tài khoản admin bị chiếm hoặc nhập code không an toàn có thể ảnh hưởng toàn bộ public site.
- **Khu vực liên quan:** `resources/views/layouts/master.blade.php`, blog/course detail, admin setting/profile và CKEditor.
- **Đề xuất:** không sanitize mù quáng vì có thể phá analytics/HTML hợp lệ. Xác nhận ai được cấp admin; giới hạn user admin, dùng password mạnh, backup nội dung. Nếu nghiệp vụ chỉ cần rich text, cân nhắc allowlist HTML cho content và tách snippet tracking khỏi editor.
- **Test sau sửa:** kiểm tra script analytics hợp lệ vẫn chạy (nếu được chấp nhận); payload không nằm trong allowlist bị loại hoặc encode theo policy; regression blog/course content.

#### M-05 — Upload CKEditor chưa có lifecycle file rõ ràng

- [ ] **Vấn đề:** ảnh CKEditor lưu trực tiếp ở `public/media`; không đi qua `Storage`, không có metadata và không có cơ chế biết ảnh nào còn được rich content tham chiếu.
- **Ảnh hưởng:** file orphan tăng theo thời gian; dọn nhầm có thể làm vỡ ảnh trong bài viết.
- **Khu vực liên quan:** `Admin\SettingController@upload`, `public/media/`, adapter `public/app/assets/js/ckeditor-csrf-upload-adapter.js`.
- **Đề xuất:** giữ nguyên ở phase ngắn hạn. Nếu dung lượng trở thành vấn đề, thiết kế inventory/reconciliation ở chế độ báo cáo trước, backup rồi mới xóa; không tự động dọn file theo tên đoán.
- **Test sau sửa:** upload/chèn/sửa/xóa nội dung mẫu; báo cáo nhận đúng file tham chiếu; chỉ sau review mới kiểm tra delete orphan trên bản sao DB/storage.

#### M-06 — Hiệu năng asset và query chưa có đo lường

- [ ] **Vấn đề:** public master nạp nhiều JS/CSS theme trên mọi trang; AppServiceProvider vẫn query Setting/About một lần mỗi request. Sitemap đọc toàn bộ blog/course active.
- **Ảnh hưởng:** tải trang có thể chậm, đặc biệt mobile; nhưng chưa có số liệu Lighthouse/APM nên không nên tối ưu theo cảm tính.
- **Khu vực liên quan:** `resources/views/layouts/master.blade.php`, `app/Providers/AppServiceProvider.php`, `SitemapController.php`, `public/site/assets/**`.
- **Đề xuất:** đo trước bằng Lighthouse/DevTools trên staging với cache lạnh/ấm. Ưu tiên defer/loại asset thực sự không dùng theo từng trang và cache có invalidation rõ ràng; không đổi hàng loạt theme asset trong một phase.
- **Test sau sửa:** so sánh số request, transfer size, LCP/CLS/console trước-sau trên `/`, `/courses/{slug}`, blog; xác nhận slider/menu/toastr/theme toggle vẫn hoạt động.

#### M-07 — UX/a11y chưa có quy trình QC tự động hoặc baseline

- [ ] **Vấn đề:** UI đã có responsive/theme toggle/fallback, nhưng không thấy test accessibility, visual regression hay checklist có bằng chứng thực thi cho keyboard, contrast, focus và form error.
- **Ảnh hưởng:** rủi ro menu offcanvas, toggle, CKEditor/form và dark/light mode khó dùng ở một số viewport/thiết bị.
- **Khu vực liên quan:** `resources/views/layouts/{master,header,footer}.blade.php`, `public/site/assets/css/custom.css`, các view form public/admin.
- **Đề xuất:** làm audit thủ công 360/768/1280 và keyboard trước; chỉ thêm tooling (Lighthouse/axe/Playwright) khi đã chốt phạm vi CI. Giữ style hiện tại, sửa theo phát hiện cụ thể.
- **Test sau sửa:** tab qua header/offcanvas/toggle/form, xem focus rõ; test form invalid; chụp/kiểm tra 360, 768, 1280 sáng/tối không tràn ngang.

#### M-08 — Seed data chưa đủ để dựng môi trường phát triển nhất quán

- [ ] **Vấn đề:** `DatabaseSeeder` chỉ seed courses; không có admin user, setting/about, blog, testimonial, service, skill, image. Một số view dựa fallback nên không vỡ, nhưng khó demo/test luồng thật.
- **Ảnh hưởng:** onboard/developer mới phải tạo thủ công, test feature không có fixture nhất quán.
- **Khu vực liên quan:** `database/seeders/{DatabaseSeeder,CourseSeeder}.php`, factories (chỉ `UserFactory`).
- **Đề xuất:** cần xác nhận dữ liệu demo có được phép không. Nếu duyệt, tạo fixture giả rõ ràng, idempotent, không dùng thông tin production; tách seed dev/demo với production.
- **Test sau sửa:** migrate trên DB trống + seed tạo đủ public/admin demo; chạy seed lần hai không duplicate; không chạy seed demo ở production.

#### M-09 — Phân quyền và quản lý tài khoản chưa mở rộng

- [ ] **Vấn đề:** mọi `users` đăng nhập đều có toàn quyền admin; không thấy UI quản lý user, reset password hoặc role/policy.
- **Ảnh hưởng:** chấp nhận được nếu website chỉ có một chủ sở hữu, nhưng không đủ khi giao cho editor/agency.
- **Khu vực liên quan:** `config/auth.php`, `LoginController`, `User` model, routes admin.
- **Đề xuất:** **cần xác minh** mô hình vận hành. Nếu chỉ một admin, ghi rõ quy trình đổi password/backup và không thêm role. Nếu nhiều người, lập phase riêng cho role/policy/audit, vì đây là mở rộng lớn.
- **Test sau sửa:** với multi-user, test mỗi role bị chặn/được phép đúng module; với single-user, test login/đổi credential/recovery theo quy trình đã chốt.

### Low

#### L-01 — README mặc định Laravel không hướng dẫn dự án

- [ ] **Vấn đề:** `README.md` vẫn là README Laravel mặc định, không mô tả setup Laragon, migration/seed, route chính, test, upload/storage link hay quy ước project.
- **Ảnh hưởng:** onboarding phải dựa vào tài liệu khác; dễ chạy sai lệnh/môi trường.
- **Khu vực liên quan:** `README.md`, `PROJECT-INSTRUCTIONS.md`, `docs/deployment-guide.md`.
- **Đề xuất:** thay README bằng entry point ngắn, liên kết tới hai tài liệu dự án, yêu cầu runtime và lệnh local an toàn. Không đưa secret/domain credential vào README.
- **Test sau sửa:** một developer mới làm theo README ở máy sạch có thể cài dependency, cấu hình `.env`, migrate/seed dev, storage link và mở app.

#### L-02 — Plans lịch sử chưa đồng bộ nhãn trạng thái

- [ ] **Vấn đề:** `plans/260723-1056-phase-c`, `phase-d`, `phase-e` vẫn có `Pending` trong khi changelog/source đã hoàn thành.
- **Ảnh hưởng:** người đọc có thể làm lại phase hoặc hiểu sai priority.
- **Khu vực liên quan:** `plans/**/plan.md`, `docs/project-changelog.md`.
- **Đề xuất:** hoặc cập nhật status Done kèm liên kết commit, hoặc thêm banner “historical/đã triển khai”; không cần sửa logic.
- **Test sau sửa:** đối chiếu mọi hạng mục plan với changelog/source; không còn một status mâu thuẫn không có chú thích.

#### L-03 — API/Sanctum và Vite là phần cài đặt nhưng chưa có nhu cầu nghiệp vụ rõ

- [ ] **Vấn đề:** Sanctum/API chỉ có `/api/user` mặc định; Vite entry tồn tại nhưng UI chủ yếu dùng asset tĩnh trực tiếp.
- **Ảnh hưởng:** không phải lỗi, nhưng có thể gây kỳ vọng sai khi phát triển hoặc tăng bề mặt dependency cần bảo trì.
- **Khu vực liên quan:** `routes/api.php`, `config/sanctum.php`, `resources/js/**`, `vite.config.js`, `package.json`.
- **Đề xuất:** không xóa/tái kiến trúc ngay. Ghi rõ đây là capability dự phòng; chỉ đầu tư API/Vite khi có use case được duyệt.
- **Test sau sửa:** nếu có quyết định dùng, thêm smoke test/build; nếu không dùng, kiểm tra không có luồng public/admin phụ thuộc ngầm trước mọi thay đổi.

## 4. Tóm tắt theo nhóm

| Nhóm | Mức cao nhất | Vấn đề chính |
| --- | --- | --- |
| Chức năng | High | Cần quyết định lifecycle course–lead; chưa có bằng chứng thiếu checkout/dashboard là bug vì scope hiện tại dùng CTA/lead. |
| Bảo mật | Critical | Credential không rỗng trong `.env.example`; raw admin snippets cần quản trị quyền chặt. |
| Validation | High | Lead status chưa bị allowlist server-side; validation CRUD chưa đồng đều. |
| Database/migration/seeder | High | Không có FK lead–course, fixture/seed dev thiếu. |
| UI/UX | Medium | Chưa có audit a11y/viewport có bằng chứng. |
| Test | High | Không có DB test độc lập và coverage nghiệp vụ. |
| Logging/error handling | Medium | Log tối thiểu, chưa có audit/monitoring/health-check. |
| Performance | Medium | Nhiều asset/query nhưng chưa đo để kết luận bottleneck. |
| Deployment/config/env | Critical | Secret template; SMTP/prod config/storage/backup cần xác minh end-to-end. |
| Documentation | Low | README mặc định và plan historical chưa đồng bộ. |

## 5. Roadmap hoàn thiện theo phase nhỏ

Mỗi phase dưới đây nên có **một commit riêng**, dừng review trước phase kế tiếp. Không gộp migration với refactor/UI nếu không cần.

### Phase 0 — Khẩn cấp: vệ sinh secret/config mẫu

- [ ] Chủ hệ thống xác minh và rotate credential có khả năng đã lộ.
- [ ] Làm sạch `.env.example` thành placeholder, rà soát secret trong tracked files/history theo quyền được cấp.
- [ ] Cập nhật checklist deploy để nhắc không commit `.env`.
- Migration: không.
- Rủi ro: cần phối hợp người sở hữu hạ tầng; không tự rotate/deploy.

### Phase 1 — Nền test đáng tin cậy

- [ ] Chốt SQLite hoặc MySQL database test riêng.
- [ ] Thêm `.env.testing` an toàn/config PHPUnit; test migration không đụng DB dev.
- [ ] Thay 2 example tests bằng smoke public + test login/lead cơ bản.
- Migration: không (trừ migration chạy trong DB test).
- Rủi ro: phải kiểm chứng driver SQLite/PHP extension hoặc quyền DB test.

### Phase 2 — Tính toàn vẹn lead, thay đổi nhỏ/rủi ro thấp

- [ ] Allowlist `Lead::STATUSES` ở update.
- [ ] Thêm feature tests cho lead status/validation.
- [ ] Chỉ sau khi xác nhận nghiệp vụ: quyết định xử lý course bị xóa có lead; tách migration FK/nullOnDelete thành commit riêng nếu được duyệt.
- Migration: không cho allowlist; có thể có cho integrity, cần duyệt.

### Phase 3 — Củng cố môi trường vận hành

- [ ] Staging/prod checklist có bằng chứng: SMTP, email lead, storage link, debug off, APP_URL, backup/restore thử.
- [ ] Bổ sung log context tối thiểu cho lead/mail/upload, không ghi secret/PII dư thừa.
- [ ] Cân nhắc monitoring/health check sau khi thống nhất hạ tầng.
- Migration: không.

### Phase 4 — Chất lượng nội dung và trải nghiệm

- [ ] QC a11y/responsive có checklist/bằng chứng.
- [ ] Đo performance, chỉ tối ưu asset/query được chứng minh ảnh hưởng.
- [ ] Rà soát policy raw HTML/snippet và lifecycle ảnh CKEditor theo nhu cầu vận hành.
- Migration: không mặc định.

### Phase 5 — Dữ liệu phát triển và tài liệu

- [ ] Xác nhận được phép có data demo rồi bổ sung factories/seeders idempotent.
- [ ] Viết README dự án, đồng bộ nhãn plans historical, liên kết `PROJECT-INSTRUCTIONS.md` và tài liệu audit này.
- [ ] Chỉ lập phase roles/permissions nếu mô hình có nhiều admin được xác nhận.
- Migration: không mặc định.

## 6. Phase đầu tiên nên làm

**Nên làm Phase 0: vệ sinh và xác minh secret/config mẫu.** Đây là thay đổi source nhỏ, không đổi logic, route hay schema nhưng giảm rủi ro cao nhất. Tuy nhiên phần rotate credential và rà soát remote/history cần quyền/chủ sở hữu hạ tầng xác nhận trước; không tự thực hiện chỉ dựa trên audit.

Sau Phase 0, Phase 1 (database test riêng + test smoke thật) là bước tăng chất lượng bền vững nhất và giúp các phase sau an toàn hơn.

