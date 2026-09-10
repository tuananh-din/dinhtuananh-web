<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class DigitalPerformanceCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Chỉ tạo mới; chạy lại không ghi đè nội dung hoặc giá admin đã chỉnh.
        Course::firstOrCreate(['slug' => 'digital-performance-management'], [
            'title' => 'Digital Performance Management',
            'short_description' => 'Khóa học giúp bạn tối ưu hiệu quả Digital Marketing bằng tư duy dựa trên dữ liệu. Củng cố kiến thức từ hoạch định, thiết lập và triển khai đến tối ưu quảng cáo, kết hợp bài giảng với thực hành và chữa bài.',
            'description' => 'Học qua video do giảng viên trực tiếp hướng dẫn, kết hợp thực hành và nhóm hỏi đáp, chữa bài.',
            'price' => 3000000,
            'sale_price' => null,
            'platform' => 'Facebook · TikTok · Google',
            'level' => 'Nền tảng đến thực hành',
            'format' => 'Video bài giảng + nhóm hỏi đáp, chữa bài',
            'duration_text' => '9 buổi học',
            'cta_text' => 'Đăng ký tư vấn',
            'is_active' => false,
            'is_featured' => true,
            'sort_order' => 0,
            'seo_title' => 'Digital Performance Management | Đinh Tuấn Anh',
            'seo_description' => 'Học Digital Performance qua video cùng Đinh Tuấn Anh: lập kế hoạch, triển khai quảng cáo, phân tích dữ liệu. Có nhóm hỏi đáp, chữa bài.',
        ]);
    }
}
