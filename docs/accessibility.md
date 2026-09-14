# Accessibility baseline — WCAG 2.2 AA

## Cam kết

Giao diện public và admin mới/sửa phải đạt **WCAG 2.2 AA** làm mức cơ sở. Không coi thay đổi là hoàn tất nếu thao tác chính không dùng được bằng bàn phím hoặc trạng thái không được đọc bởi công cụ hỗ trợ.

## Quy ước áp dụng trong code

- Không bỏ `:focus-visible`. Focus ring dùng token `--focus-ring`; tương phản của viền với vùng kề cận phải từ **3:1**.
- Văn bản thường phải đạt tối thiểu **4.5:1**; chữ lớn và component UI tối thiểu **3:1**. Khi thay màu light/dark, kiểm tra cả hover, disabled, error và focus.
- Dùng HTML native trước (button, label, details/summary, nav). Không dùng `div`/`a` không có `href` để tạo button.
- Icon không có chữ bắt buộc có `aria-label` trên control; icon trang trí dùng `aria-hidden="true"`.
- Menu/dialog: Enter/Space mở control native, Escape đóng khi phù hợp, focus nhìn thấy rõ, `aria-expanded`/`aria-current` phản ánh trạng thái.
- Phân trang: trang hiện tại có `aria-current="page"`; disabled và ellipsis không phải link có thể focus.
- Thông báo kết quả dùng `role="status"` hoặc `role="alert"`; form có lỗi trỏ focus đến thông báo đầu tiên.

## Kiểm tra trước khi merge

1. Chỉ dùng bàn phím: Tab/Shift+Tab, Enter/Space, Escape; không kẹt focus và thứ tự focus hợp lý.
2. Kiểm tra light và dark mode ở desktop và mobile; zoom trình duyệt 200% không làm mất CTA hay control.
3. Chạy automated test và kiểm tra markup/ARIA của component bị sửa.
4. Kiểm tra bằng **NVDA + Chrome** trên Windows và **VoiceOver + Safari** trên macOS cho flow thay đổi. Ghi lại route, phiên bản trình duyệt và lỗi phát hiện vào PR/commit note.

## Quy tắc review

Mọi component mới phải nêu: tên control, keyboard behavior, ARIA/state, focus order, thông báo lỗi/tải và các cặp màu cần kiểm tra. Chỉ thêm token mới khi dùng tối thiểu hai bề mặt hoặc có lý do theme rõ ràng; ghi thay đổi token vào `docs/project-changelog.md`.

## Giới hạn hiện tại

Repository không thể tự chạy NVDA hay VoiceOver. Hai bước screen-reader trong mục kiểm tra trước khi merge phải được xác nhận bằng test tay trên thiết bị thật trước khi phát hành.
