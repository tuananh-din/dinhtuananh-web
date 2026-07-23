<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lead mới từ website</title>
</head>
<body>
    <h2>Lead mới từ website</h2>

    <p><strong>ID:</strong> {{ $lead->id }}</p>
    <p><strong>Họ tên:</strong> {{ $lead->name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $lead->phone }}</p>

    @if(!empty($lead->email))
        <p><strong>Email:</strong> {{ $lead->email }}</p>
    @endif

    @if(!empty($lead->message))
        <p><strong>Nội dung:</strong></p>
        <p>{{ $lead->message }}</p>
    @endif

    @if(!empty($lead->source_page))
        <p><strong>Trang nguồn:</strong> {{ $lead->source_page }}</p>
    @endif

    @if(!empty($lead->course_id))
        <p><strong>Course ID:</strong> {{ $lead->course_id }}</p>
    @endif

    @if(!empty($courseTitle))
        <p><strong>Tên khóa học:</strong> {{ $courseTitle }}</p>
    @endif

    @if(!empty($lead->status))
        <p><strong>Trạng thái:</strong> {{ $lead->status }}</p>
    @endif

    @if(!empty($lead->note))
        <p><strong>Ghi chú:</strong> {{ $lead->note }}</p>
    @endif

    <p><strong>Tạo lúc:</strong> {{ $lead->created_at }}</p>

    <p>
        <a href="{{ route('admin.lead') }}">Mở danh sách lead trong admin</a>
    </p>
</body>
</html>
