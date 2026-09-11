<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class FacebookCommunityCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Chỉ tạo mới; chạy lại không ghi đè dữ liệu mà admin đã cập nhật.
        Course::firstOrCreate(['slug' => 'facebook-community-growth-system'], [
            'title' => 'Facebook Community Growth System',
            'short_description' => 'Xây cộng đồng. Tạo traffic. Biến thành viên thành khách hàng.',
            'description' => 'Khóa học giúp bạn hiểu hành vi người dùng trên Facebook, xây dựng Facebook Group từ 0, phát triển hệ thống thành viên, vận hành bằng dữ liệu và biến Community thành một tài sản Marketing dài hạn.',
            'price' => 3000000,
            'platform' => 'Facebook Group',
            'level' => 'Nền tảng đến thực chiến',
            'format' => 'Video bài giảng + nhóm hỏi đáp, chữa bài',
            'duration_text' => '5 buổi học',
            'cta_text' => 'Đăng ký tư vấn',
            'is_active' => false,
            'is_featured' => true,
            'sort_order' => 1,
            'seo_title' => 'Facebook Community Growth System | Đinh Tuấn Anh',
            'seo_description' => 'Học xây và vận hành Facebook Community: phát triển thành viên, tạo traffic, tăng lead và doanh thu.',
        ]);
    }
}
