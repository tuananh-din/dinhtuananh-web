<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ImageController as AdminImageController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController ;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[HomeController::class,'index'])->name('index');
Route::get('/about',[HomeController::class,'about'])->name('about');
Route::get('/life',[HomeController::class,'life'])->name('life');
Route::get('/portfolio',[HomeController::class,'portfolio'])->name('portfolio');
Route::get('/contact',[HomeController::class,'contact'])->name('contact');

Route::get('/blog',[BlogController::class,'blogs'])->name('blogs');
Route::get('/courses',[CourseController::class,'index'])->name('courses');
Route::get('/courses/{slug}',[CourseController::class,'detail'])->name('course.detail');
Route::post('/lead/store',[LeadController::class,'store'])->middleware('throttle:5,1')->name('lead.store');
Route::get('/sitemap.xml',[SitemapController::class,'index'])->name('sitemap');
Route::get('/{slug}.html',[BlogController::class,'detail'])->name('blog');

Route::get('/login',[LoginController::class,'login'])->name('login');
Route::post('login',[LoginController::class,'send'])->name('login.send');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () { 
        Route::get('/', [SettingController::class, 'index'])->name('setting');
        Route::post('setting/update', [SettingController::class, 'updateSetting'])->name('setting.update');
        Route::get('/profile', [AboutController::class, 'index'])->name('admin.profile');
        Route::post('profile/update', [AboutController::class, 'updateProfile'])->name('profile.update');
        Route::post('ckeditor/image_upload', [SettingController::class, 'upload'])->name('image.upload');

        Route::prefix('blog')->group(function () { 
            Route::get('/', [AdminBlogController::class, 'index'])->name('admin.blog');
            Route::get('/create', [AdminBlogController::class, 'create'])->name('blog.create');
            Route::get('/edit/{id}', [AdminBlogController::class, 'edit'])->name('blog.edit');
            Route::post('store', [AdminBlogController::class, 'store'])->name('blog.store');
            Route::get('/delete/{id}', [AdminBlogController::class, 'delete'])->name('blog.delete');
        });

        Route::prefix('course')->group(function () {
            Route::get('/', [AdminCourseController::class, 'index'])->name('admin.course');
            Route::get('/create', [AdminCourseController::class, 'create'])->name('course.create');
            Route::get('/edit/{id}', [AdminCourseController::class, 'edit'])->name('course.edit');
            Route::post('store', [AdminCourseController::class, 'store'])->name('course.store');
            Route::get('/delete/{id}', [AdminCourseController::class, 'delete'])->name('course.delete');
        });

        Route::prefix('lead')->group(function () {
            Route::get('/', [AdminLeadController::class, 'index'])->name('admin.lead');
            Route::post('/update/{id}', [AdminLeadController::class, 'update'])->name('lead.update');
        });

        Route::prefix('testimonial')->group(function () {
            Route::get('/', [AdminTestimonialController::class, 'index'])->name('admin.testimonial');
            Route::get('/create', [AdminTestimonialController::class, 'create'])->name('testimonial.create');
            Route::get('/edit/{id}', [AdminTestimonialController::class, 'edit'])->name('testimonial.edit');
            Route::post('store', [AdminTestimonialController::class, 'store'])->name('testimonial.store');
            Route::get('/delete/{id}', [AdminTestimonialController::class, 'delete'])->name('testimonial.delete');
        });

        Route::prefix('service')->group(function () { 
            Route::get('/', [AdminServiceController::class, 'index'])->name('admin.service');
            Route::get('/create', [AdminServiceController::class, 'create'])->name('service.create');
            Route::get('/edit/{id}', [AdminServiceController::class, 'edit'])->name('service.edit');
            Route::post('store', [AdminServiceController::class, 'store'])->name('service.store');
            Route::get('/delete/{id}', [AdminServiceController::class, 'delete'])->name('service.delete');
        });

        Route::prefix('skill')->group(function () { 
            Route::get('/', [AdminSkillController::class, 'index'])->name('admin.skill');
            Route::get('/create', [AdminSkillController::class, 'create'])->name('skill.create');
            Route::get('/edit/{id}', [AdminSkillController::class, 'edit'])->name('skill.edit');
            Route::post('store', [AdminSkillController::class, 'store'])->name('skill.store');
            Route::get('/delete/{id}', [AdminSkillController::class, 'delete'])->name('skill.delete');
        });

        Route::prefix('image')->group(function () { 
            Route::get('/', [AdminImageController::class, 'index'])->name('admin.image');
            Route::get('/create', [AdminImageController::class, 'create'])->name('image.create');
            Route::get('/edit/{id}', [AdminImageController::class, 'edit'])->name('image.edit');
            Route::post('store', [AdminImageController::class, 'store'])->name('image.store');
            Route::get('/delete/{id}', [AdminImageController::class, 'delete'])->name('image.delete');
        });

    });
});
