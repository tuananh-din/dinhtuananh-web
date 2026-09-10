<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishDigitalPerformanceCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Run explicitly when publishing; do not include in routine deployments.
        DB::transaction(function () {
            $this->call(DigitalPerformanceCourseSeeder::class);
            $course = Course::where('slug', 'digital-performance-management')->firstOrFail();
            $course->is_active = true;
            if (!$course->thumbnail) {
                $course->thumbnail = '/site/assets/img/courses/digital-performance-workspace.png';
            }
            $course->save();
        });
    }
}
