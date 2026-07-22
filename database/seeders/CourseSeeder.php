<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        if (Course::count() > 0) {
            return;
        }

        $now = now();

        Course::insert([
            [
                'title' => 'Facebook Ads Thuc Chien',
                'slug' => 'facebook-ads-thuc-chien',
                'short_description' => 'Danh cho nguoi moi can hoc nhanh de tu chay quang cao ra don.',
                'description' => 'Khoa hoc tap trung vao thuc hanh, quy trinh setup va toi uu co ban.',
                'content' => '<p>Hoc tu duy setup camp, test mau quang cao va doc chi so chinh.</p>',
                'thumbnail' => null,
                'price' => 3990000,
                'sale_price' => 2490000,
                'platform' => 'Online Zoom + Video',
                'level' => 'Beginner',
                'duration_text' => '6 buoi',
                'format' => 'Hoc online',
                'cta_text' => 'Dang ky ngay',
                'cta_link' => 'tel:0900000000',
                'is_featured' => 1,
                'is_active' => 1,
                'sort_order' => 0,
                'seo_title' => 'Facebook Ads Thuc Chien',
                'seo_description' => 'Khoa hoc Facebook Ads cho nguoi moi.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'TikTok Ads Cho Ban Hang',
                'slug' => 'tiktok-ads-cho-ban-hang',
                'short_description' => 'Huong dan lam noi dung va quang cao TikTok de co lead/chot don.',
                'description' => 'Phu hop chu shop, doanh nghiep nho va marketer can traffic.',
                'content' => '<p>Hoc cach set camp TikTok Ads, scale ngan sach va toi uu don hang.</p>',
                'thumbnail' => null,
                'price' => 3590000,
                'sale_price' => 2190000,
                'platform' => 'Online',
                'level' => 'Beginner - Intermediate',
                'duration_text' => '5 buoi',
                'format' => 'Hoc online',
                'cta_text' => 'Nhan tu van',
                'cta_link' => 'tel:0900000000',
                'is_featured' => 0,
                'is_active' => 1,
                'sort_order' => 1,
                'seo_title' => 'TikTok Ads Cho Ban Hang',
                'seo_description' => 'Khoa hoc TikTok Ads de ban hang.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Google Ads Nen Tang',
                'slug' => 'google-ads-nen-tang',
                'short_description' => 'Xay nen tang Google Ads tim kiem cho doanh nghiep.',
                'description' => 'Danh cho nguoi can khach hang co nhu cau ro rang qua tim kiem.',
                'content' => '<p>Hoc keyword, cau truc camp va toi uu theo muc tieu chuyen doi.</p>',
                'thumbnail' => null,
                'price' => 3290000,
                'sale_price' => null,
                'platform' => 'Online',
                'level' => 'Beginner',
                'duration_text' => '4 buoi',
                'format' => 'Hoc online',
                'cta_text' => 'Dang ky hoc',
                'cta_link' => 'tel:0900000000',
                'is_featured' => 0,
                'is_active' => 1,
                'sort_order' => 2,
                'seo_title' => 'Google Ads Nen Tang',
                'seo_description' => 'Khoa hoc Google Ads co ban.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
