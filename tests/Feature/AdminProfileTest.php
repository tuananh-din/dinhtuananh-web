<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_profile_has_a_visible_save_action_and_can_create_a_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Lưu thay đổi')
            ->assertSee('name="avatar"', false);

        $this->from(route('admin.profile'))
            ->actingAs($user)
            ->post(route('profile.update'), [
                'name' => 'Đinh Tuấn Anh',
                'email' => 'tuananh@example.test',
            ])
            ->assertRedirect(route('admin.profile'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('about', [
            'id' => 1,
            'name' => 'Đinh Tuấn Anh',
            'email' => 'tuananh@example.test',
        ]);
    }

    public function test_admin_can_save_a_substack_profile_url(): void
    {
        $user = User::factory()->create();

        $this->from(route('admin.profile'))
            ->actingAs($user)
            ->post(route('profile.update'), [
                'name' => 'Đinh Tuấn Anh',
                'substack' => 'https://tuananh.substack.com',
            ])
            ->assertRedirect(route('admin.profile'));

        $this->assertDatabaseHas('about', ['substack' => 'https://tuananh.substack.com']);
        $this->actingAs($user)->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('name="substack"', false)
            ->assertSee('Nhập link trang Substack công khai của bạn.');
    }

    public function test_admin_header_uses_a_clear_account_menu_with_a_safe_logout_action(): void
    {
        $user = User::factory()->create(['name' => 'Quản trị viên']);

        $this->actingAs($user)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('id="admin-profile-toggle"', false)
            ->assertSee('aria-expanded="false"', false)
            ->assertSee('Cài đặt website')
            ->assertSee('Đăng xuất')
            ->assertDontSee('Notification');
    }

    public function test_admin_profile_keeps_existing_avatar_when_no_new_file_is_submitted(): void
    {
        $user = User::factory()->create();
        About::create([
            'id' => 1,
            'name' => 'Đinh Tuấn Anh',
            'avatar' => '/storage/images/avatar-cu.jpg',
        ]);

        $this->from(route('admin.profile'))
            ->actingAs($user)
            ->post(route('profile.update'), ['name' => 'Tuấn Anh mới'])
            ->assertRedirect(route('admin.profile'));

        $this->assertDatabaseHas('about', [
            'id' => 1,
            'name' => 'Tuấn Anh mới',
            'avatar' => '/storage/images/avatar-cu.jpg',
        ]);
    }

    public function test_admin_can_replace_the_profile_avatar(): void
    {
        Storage::fake();
        $user = User::factory()->create();
        About::create(['id' => 1, 'name' => 'Đinh Tuấn Anh', 'avatar' => '/storage/images/avatar-cu.jpg']);

        $this->from(route('admin.profile'))
            ->actingAs($user)
            ->post(route('profile.update'), [
                'name' => 'Đinh Tuấn Anh',
                'avatar' => UploadedFile::fake()->image('avatar-moi.jpg', 900, 1200),
            ])
            ->assertRedirect(route('admin.profile'))
            ->assertSessionHas('success');

        $avatar = About::firstOrFail()->avatar;

        $this->assertStringStartsWith('/storage/images/', $avatar);
        Storage::assertExists(str_replace('/storage/', 'public/', $avatar));
    }
}
