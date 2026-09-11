<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishFacebookCommunityCourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(FacebookCommunityCourseSeeder::class);
            $course = Course::where('slug', 'facebook-community-growth-system')->firstOrFail();
            $course->is_active = true;
            if (!$course->thumbnail) {
                $course->thumbnail = '/site/assets/img/courses/facebook-community-hero.webp';
            }
            $course->save();
        });
    }
}
