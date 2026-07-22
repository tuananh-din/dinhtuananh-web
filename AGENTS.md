# AGENTS.md

## Ngôn ngữ
- Luôn trả lời bằng tiếng Việt.
- Giải thích dễ hiểu cho người mới.

## Cách làm việc
- Không tự quyết những gì chưa chắc.
- Nếu có điểm chưa rõ, phải hỏi lại trước khi code.
- Không mở rộng scope nếu tôi chưa đồng ý.
- Không refactor lớn nếu chưa giải thích rõ lý do.
- Ưu tiên tận dụng code cũ tối đa.
- Ưu tiên thay đổi nhỏ, an toàn, dễ review.

## Quy tắc cho project này
- Đây là project Laravel cũ đang được nâng cấp từng phase.
- Không tự ý đụng Phase khác.
- Không tự ý thêm module ngoài phạm vi tôi yêu cầu.
- Không tự ý đổi cấu trúc route cũ nếu không thật sự cần.
- Không tự ý đổi schema database nếu chưa được xác nhận.
- Nếu có migration rủi ro, phải báo trước.

## Môi trường chạy
- Project root: C:\App\laragon\www\tanh
- Chạy local bằng Laragon / PHP local
- Trước khi chạy lệnh quan trọng, hãy nói rõ sẽ chạy lệnh gì.

## Khi làm Phase
- Trước khi code, phải báo:
  1. sẽ sửa gì
  2. file nào bị ảnh hưởng
  3. có migration không
  4. cách test
- Sau khi code xong, phải báo:
  1. file đã sửa
  2. file đã tạo
  3. route đã thêm/sửa
  4. migration đã thêm
  5. cách test tay
  6. rủi ro còn lại

## UI/UX
- Ưu tiên giao diện sạch, rõ hierarchy, CTA rõ.
- Giữ style cũ, không redesign toàn bộ nếu tôi chưa yêu cầu.
- Nếu thiếu dữ liệu, dùng fallback lịch sự, không làm vỡ layout.

## Không được làm
- Không deploy production nếu tôi chưa yêu cầu.
- Không xóa code cũ hàng loạt.
- Không xóa module đã tồn tại chỉ để “đúng scope” hơn; ưu tiên tách phụ thuộc thay vì đập bỏ.
