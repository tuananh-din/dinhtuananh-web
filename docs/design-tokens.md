# Design tokens public — v1.2.0

## Phạm vi

Tài liệu này áp dụng cho giao diện public. Token được khai báo trong `public/site/assets/css/custom.css`; không tự động áp dụng cho Admin hoặc template tĩnh legacy.

## Kiến trúc ba tầng

| Tầng | Mẫu tên | Mục đích | Ví dụ |
| --- | --- | --- | --- |
| Primitive | `--primitive-{nhóm}-{thang}` | Giá trị thô, không dùng trực tiếp trong component mới | `--primitive-color-brand-lime-400` |
| Semantic | `--semantic-{nhóm}-{vai-trò}` | Ý nghĩa dùng chung, có thể đổi theo theme | `--semantic-color-text-primary` |
| Component | `--component-{component}-{vai-trò}` | Quyết định riêng cho một họ UI | `--component-toggle-surface-active` |

Typography hiện dùng lớp semantic đã có: `--font-*`, `--type-*`, `--line-*`, `--tracking-*`. Các tên này được giữ để tránh breaking change trong CSS hiện hữu.

## Semantic tokens

| Token | Dùng cho | Không dùng cho |
| --- | --- | --- |
| `--semantic-color-surface-page` | Nền trang chính | Card hoặc dialog cần surface riêng |
| `--semantic-color-text-primary` | Heading/nội dung ưu tiên cao | Placeholder hoặc thông báo lỗi |
| `--semantic-color-text-default` | Nội dung thường | CTA primary |
| `--semantic-color-text-muted` | Metadata, nội dung phụ | Nội dung bắt buộc phải đọc |
| `--semantic-color-text-link` | Liên kết văn bản cần đạt tương phản | Nền hoặc trạng thái CTA |
| `--semantic-color-interactive-primary` | Trạng thái tương tác chính/brand | Text body thông thường |
| `--semantic-color-focus-ring` | Viền focus keyboard | Border layout mặc định |

## Color system

### Primitive palette

Primitive là màu thô, không gọi trực tiếp từ component mới. Các ramp hiện có: neutral (`0, 50, 100, 300, 500, 700, 900, 1000`), brand lime (`100, 200, 400, 600, 800`), brand green (`500, 700, 800`) và feedback blue/green/amber/red (`100`, màu chữ `600` hoặc `700`).

### Semantic color

Component mới dùng semantic token: `--semantic-color-surface-*`, `--semantic-color-text-*`, `--semantic-color-border-*`, `--semantic-color-interactive-primary-*`, `--semantic-color-feedback-{success|info|warning|error}-*`. Không dùng màu brand làm body text nếu cặp tương phản không đạt.

Interactive gồm default, hover, pressed, focus (`--semantic-color-focus-ring`), disabled và selected (dùng primary default cùng indicator text/icon). Mọi trạng thái feedback luôn gồm icon và title/text, không truyền đạt ý nghĩa chỉ bằng màu.

### Tương phản đã kiểm tra

| Cặp màu | Light | Dark | Mức dùng |
| --- | ---: | ---: | --- |
| Focus ring trên page surface | 5.62:1 | 16.03:1 | UI ≥ 3:1 |
| Link text trên page surface | 5.06:1 | 16.03:1 | Text ≥ 4.5:1 |
| Feedback text trên success/info/warning/error surface | 14.08–14.61:1 | 15.96–16.44:1 | Text ≥ 4.5:1 |

Đo theo WCAG relative luminance với surface đã composited. Khi thêm token hoặc đổi alpha, kiểm tra lại light và dark mode; màu chỉ đạt 3:1 chỉ được dùng cho UI/larger text, không cho body text.

Brand lime là màu recognition/CTA dark; ở light mode text/link dùng green-800 để giữ AA. Primary green-500 trên surface light đạt 3.61:1, do đó chỉ dùng cho component UI, không dùng làm text thường.

### Theme và color vision

Light/dark có tập semantic riêng trong `html[data-theme="light"]`, không đảo màu tự động. Success/info/warning/error luôn có icon và title trong `partials/notice-banner.blade.php`; không phân biệt trạng thái chỉ bằng green/red. Kiểm tra màu mù (protanopia, deuteranopia, tritanopia) phải giữ được phân biệt qua icon, copy và border trước khi release.

Các semantic token đổi theo `html[data-theme="light"]`; component không tự chứa giá trị màu thô khi có semantic token phù hợp.

## Spacing, grid và breakpoint

### Spacing scale

Scale public dùng base-4/base-8: `4, 8, 12, 16, 24, 32, 48, 64, 96px`. Primitive tương ứng là `--primitive-space-1` đến `--primitive-space-9`. Không tạo số spacing mới nếu một bước trong scale đáp ứng được mục đích.

| Semantic token | Dùng cho |
| --- | --- |
| `--semantic-space-component-gap-sm` | Khoảng cách nhỏ giữa icon/label hoặc item sát nhau |
| `--semantic-space-component-gap` | Khoảng cách mặc định giữa phần tử trong component |
| `--semantic-space-component-padding-sm` | Padding control/card nhỏ |
| `--semantic-space-component-padding` | Padding control/card thông thường |
| `--semantic-space-layout-gutter` | Gutter/side margin cấp layout |
| `--semantic-space-layout-section` | Khoảng trắng dọc giữa section lớn |

Không dùng token layout cho padding trong button/input. Không dùng token component để tách section. Các giá trị legacy không theo scale chỉ đổi khi sửa chính component đó và cần smoke test desktop/mobile.

### Grid và breakpoint public

| Tên | Viewport | Cột | Gutter / margin |
| --- | --- | ---: | --- |
| `narrow` | `< 768px` | 4 | 16px / 16px |
| `mid` | `768–1199px` | 8 | 24px / 24px |
| `wide` | `≥ 1200px` | 12 | 32px / 32px |

Trong CSS, `--grid-columns`, `--grid-gutter`, `--grid-margin` phản ánh grid đang hoạt động; `.layout-grid` dành cho layout public mới. Vì CSS custom property không thể làm điều kiện `@media`, giá trị breakpoint vẫn viết cố định trong media query và phải đồng bộ bảng này.

### Density và baseline

Public site dùng một density “comfortable”; không thêm compact mode vì không có màn hình dữ liệu dày đặc. Admin chỉ thêm density variant khi có nhu cầu vận hành được xác nhận. Chiều cao control mới và spacing dọc nên bám bội số 4px để các cụm nội dung xếp đều; typography legacy chỉ chuẩn hóa khi chạm component liên quan, không refactor hàng loạt.

## Quy tắc thêm token

1. Không thêm token cho một giá trị chỉ xuất hiện một lần hoặc chỉ là ngoại lệ legacy đã được xác nhận.
2. Dùng primitive khi cần giá trị gốc; tạo semantic khi giá trị có một mục đích dùng chung, đặc biệt khi cần đổi light/dark.
3. Chỉ tạo component token khi quyết định đó thuộc riêng một component hoặc họ component.
4. Không đặt tên theo màu ở semantic/component. Dùng vai trò, ví dụ `interactive-primary`, không dùng `green-button`.
5. Mỗi token mới phải được review cùng: mục đích, ít nhất hai bối cảnh sử dụng hoặc lý do theo theme, ảnh hưởng light/dark và responsive.
6. Không đổi giá trị semantic đang dùng mà không thêm mục vào changelog và kiểm tra các surface sử dụng nó.

## Đồng bộ design tool

Chưa có design tool được kết nối với repository nên không có đồng bộ tự động. Khi Figma (hoặc công cụ tương đương) được chọn, dùng tên token 1:1 với CSS trong tài liệu này; bắt đầu với semantic color và typography trước. Không tự tạo hoặc sửa biến trên design tool khi chưa có quyền truy cập.

## Phiên bản và thay đổi

- Phiên bản hiện tại: `v1.2.0`.
- Mọi thay đổi token phải ghi vào `docs/project-changelog.md`, gồm token bị ảnh hưởng, lý do và các bề mặt cần smoke test.
- Thay đổi primitive chưa được tiêu thụ là patch; thêm semantic/component là minor; đổi giá trị semantic/component đang được tiêu thụ là breaking change nội bộ và phải kiểm tra light/dark, keyboard focus và mobile.
