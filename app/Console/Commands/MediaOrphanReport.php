<?php

namespace App\Console\Commands;

use App\Models\About;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Image;
use App\Models\LeadMagnet;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MediaOrphanReport extends Command
{
    protected $signature = 'media:orphan-report';

    protected $description = 'Bao cao anh trong public/media chua duoc tham chieu';

    public function handle(): int
    {
        $mediaPath = public_path('media');

        if (!File::isDirectory($mediaPath)) {
            $this->info('Thu muc public/media khong ton tai.');

            return self::SUCCESS;
        }

        $files = File::allFiles($mediaPath);
        $references = $this->collectReferences($files);
        $orphans = [];
        $totalBytes = 0;

        foreach ($files as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());
            if (isset($references[$relativePath])) {
                continue;
            }

            $size = $file->getSize();
            $orphans[] = [$relativePath, $size];
            $totalBytes += $size;
        }

        if ($orphans === []) {
            $this->info('Khong tim thay anh orphan trong public/media.');
            $this->line('Tong dung luong: 0 B');

            return self::SUCCESS;
        }

        $this->line('File khong duoc tham chieu:');
        foreach ($orphans as [$path, $size]) {
            $this->line(sprintf('- %s (%s)', $path, $this->formatBytes($size)));
        }
        $this->line('Tong file: '.count($orphans));
        $this->line('Tong dung luong: '.$this->formatBytes($totalBytes));

        return self::SUCCESS;
    }

    private function collectReferences(array $files): array
    {
        $sources = [
            [Blog::class, ['content', 'description', 'image']],
            [Course::class, ['content', 'description', 'short_description', 'thumbnail']],
            [About::class, ['avatar', 'image', 'description', 'content', 'about_me']],
            [Setting::class, ['logo', 'favicon', 'code_header', 'code_footer', 'slogan', 'note']],
            [Image::class, ['image', 'path', 'description', 'content']],
            [Testimonial::class, ['avatar', 'content']],
            [Service::class, ['description']],
            [Skill::class, ['image', 'description']],
            [LeadMagnet::class, ['file_path', 'cover_image', 'description']],
        ];
        $references = [];

        foreach ($sources as [$model, $columns]) {
            foreach ($model::query()->get($columns) as $record) {
                foreach ($columns as $column) {
                    $value = (string) ($record->{$column} ?? '');
                    if ($value === '') {
                        continue;
                    }

                    foreach ($files as $file) {
                        $relativePath = str_replace('\\', '/', $file->getRelativePathname());
                        if (str_contains($value, $relativePath)) {
                            $references[$relativePath] = true;
                        }
                    }
                }
            }
        }

        return $references;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return number_format($bytes / (1024 * 1024), 1).' MB';
    }
}
