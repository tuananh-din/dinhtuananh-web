<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Course;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUploadLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_blog_upload_lifecycle_keeps_replaces_and_deletes_only_managed_files(): void
    {
        $this->assertUploadLifecycle(
            Blog::class,
            'blog.store',
            'blog.delete',
            'image',
            ['title' => 'Bài viết có ảnh', 'content' => 'Nội dung bài viết'],
            ['title' => 'Bài viết đã sửa', 'content' => 'Nội dung đã sửa'],
        );
    }

    public function test_course_upload_lifecycle_keeps_replaces_and_deletes_only_managed_files(): void
    {
        $this->assertUploadLifecycle(
            Course::class,
            'course.store',
            'course.delete',
            'thumbnail',
            ['title' => 'Khóa học có ảnh', 'short_description' => 'Mô tả ngắn'],
            ['title' => 'Khóa học đã sửa', 'short_description' => 'Mô tả ngắn đã sửa'],
        );
    }

    public function test_testimonial_upload_lifecycle_keeps_replaces_and_deletes_only_managed_files(): void
    {
        $this->assertUploadLifecycle(
            Testimonial::class,
            'testimonial.store',
            'testimonial.delete',
            'avatar',
            ['name' => 'Nguyễn An', 'content' => 'Nhận xét ban đầu'],
            ['name' => 'Nguyễn Bình', 'content' => 'Nhận xét đã sửa'],
        );
    }

    private function assertUploadLifecycle(
        string $modelClass,
        string $storeRoute,
        string $deleteRoute,
        string $uploadField,
        array $createPayload,
        array $updatePayload,
    ): void {
        $user = User::factory()->create();
        $createPayload[$uploadField] = UploadedFile::fake()->image('old-image.jpg');

        $this->actingAs($user)->post(route($storeRoute), $createPayload)
            ->assertSessionHasNoErrors();

        $model = $modelClass::firstOrFail();
        $oldUrl = $model->{$uploadField};
        $oldPath = $this->managedPath($oldUrl);
        $this->assertStringStartsWith('/storage/', $oldUrl);
        Storage::assertExists($oldPath);

        $updatePayload['id'] = $model->id;
        $this->actingAs($user)->post(route($storeRoute), $updatePayload)
            ->assertSessionHasNoErrors();

        $model->refresh();
        $this->assertSame($oldUrl, $model->{$uploadField});
        Storage::assertExists($oldPath);

        $updatePayload[$uploadField] = UploadedFile::fake()->image('new-image.jpg');
        $this->actingAs($user)->post(route($storeRoute), $updatePayload)
            ->assertSessionHasNoErrors();

        $model->refresh();
        $this->assertNotSame($oldUrl, $model->{$uploadField});
        Storage::assertMissing($oldPath);
        Storage::assertExists($this->managedPath($model->{$uploadField}));

        $externalPath = 'images/external-file.jpg';
        Storage::put($externalPath, 'outside managed URL');
        $model->update([$uploadField => 'https://example.test/external-file.jpg']);

        $this->actingAs($user)->post(route($deleteRoute, $model->id))
            ->assertSessionHasNoErrors();

        Storage::assertExists($externalPath);
    }

    private function managedPath(string $url): string
    {
        return str_replace('/storage/', 'public/', $url);
    }
}
