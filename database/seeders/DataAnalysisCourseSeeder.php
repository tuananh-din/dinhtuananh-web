<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class DataAnalysisCourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::firstOrCreate(['slug' => 'data-analysis-visualization'], [
            'title' => 'Data Analysis & Visualization',
            'short_description' => 'Đọc, phân tích và trực quan hóa dữ liệu để biến những con số thành insight, hỗ trợ ra quyết định chính xác và hiệu quả.',
            'description' => 'Khóa học giúp bạn xây tư duy phân tích dữ liệu, xử lý dữ liệu thực tế, tìm insight và trình bày kết quả rõ ràng để hỗ trợ quyết định.',
            'price' => 3000000,
            'platform' => 'Excel · Power BI · Dashboard',
            'level' => 'Nền tảng đến thực hành',
            'format' => 'Video bài giảng + nhóm hỏi đáp, chữa bài',
            'duration_text' => '5 buổi học',
            'cta_text' => 'Đăng ký tư vấn',
            'is_active' => false,
            'is_featured' => true,
            'sort_order' => 2,
            'seo_title' => 'Data Analysis & Visualization | Đinh Tuấn Anh',
            'seo_description' => 'Học phân tích và trực quan hóa dữ liệu: làm sạch dữ liệu, tìm insight, xây dashboard và trình bày kết quả.',
        ]);
    }
}
