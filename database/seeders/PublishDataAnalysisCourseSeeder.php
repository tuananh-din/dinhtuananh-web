<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishDataAnalysisCourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(DataAnalysisCourseSeeder::class);
            $course = Course::where('slug', 'data-analysis-visualization')->firstOrFail();
            $course->is_active = true;
            if (!$course->thumbnail) {
                $course->thumbnail = '/site/assets/img/courses/digital-performance-workspace.png';
            }
            $course->save();
        });
    }
}
