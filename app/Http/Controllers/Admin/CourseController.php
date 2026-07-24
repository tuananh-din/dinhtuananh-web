<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('admin.course.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.course.create');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.edit', compact('course'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'thumbnail' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'cta_link' => 'nullable|string|max:255',
        ]);

        if (!is_null($request->price) && !is_null($request->sale_price) && (float) $request->sale_price > (float) $request->price) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sale_price' => 'Giá sale không được lớn hơn giá gốc.']);
        }

        $id = $request->id;
        $course = Course::find($id);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());
            $thumbnail = Storage::url($path);
            if (!empty($course?->thumbnail) && $course->thumbnail !== $thumbnail) {
                $this->deleteManagedUpload($course->thumbnail);
            }
        } else {
            $thumbnail = $course->thumbnail ?? null;
        }

        $baseSlug = Str::slug($request->slug ?: $request->title);
        if (!$baseSlug) {
            $baseSlug = 'course';
        }
        $slug = $baseSlug;
        $counter = 1;
        while (
            Course::where('slug', $slug)
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = array_merge($request->only([
            'title',
            'short_description',
            'description',
            'content',
            'price',
            'sale_price',
            'platform',
            'level',
            'duration_text',
            'format',
            'cta_text',
            'cta_link',
            'sort_order',
            'seo_title',
            'seo_description',
        ]), [
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        Course::query()->updateOrCreate(
            ['id' => $id],
            $data
        );

        $message = $id ? 'Cập nhật khóa học thành công.' : 'Tạo khóa học mới thành công.';
        return redirect()->back()->with('success', $message);
    }

    public function delete($id)
    {
        $course = Course::where('id', $id)->first();
        if ($course) {
            if (Lead::where('course_id', $course->id)->exists()) {
                $course->update(['is_active' => 0]);

                return redirect()->back()->with('success', 'Khóa học đã có lead nên được ẩn thay vì xóa.');
            }

            $this->deleteManagedUpload($course->thumbnail);
            $course->delete();
            return redirect()->back()->with('success', 'Xóa khóa học thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy khóa học để xóa.');
    }
}
