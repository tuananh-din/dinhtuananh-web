<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'sort_order' => 'nullable|integer|min:0',
            'avatar' => 'nullable|image|max:5120',
        ]);

        $id = $request->id;
        $testimonial = Testimonial::find($id);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());
            $avatar = Storage::url($path);
        } else {
            $avatar = $testimonial->avatar ?? null;
        }

        $data = array_merge($request->only([
            'name',
            'job_title',
            'company',
            'content',
            'rating',
            'sort_order',
        ]), [
            'avatar' => $avatar,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        Testimonial::query()->updateOrCreate(
            ['id' => $id],
            $data
        );

        $message = $id
            ? html_entity_decode('C&#7853;p nh&#7853;t testimonial th&#224;nh c&#244;ng.', ENT_QUOTES, 'UTF-8')
            : html_entity_decode('Th&#234;m testimonial th&#224;nh c&#244;ng.', ENT_QUOTES, 'UTF-8');
        return redirect()->back()->with('success', $message);
    }

    public function delete($id)
    {
        $testimonial = Testimonial::find($id);
        if (!$testimonial) {
            $errorMessage = html_entity_decode('Kh&#244;ng t&#236;m th&#7845;y testimonial.', ENT_QUOTES, 'UTF-8');
            return redirect()->back()->with('error', $errorMessage);
        }

        $testimonial->delete();
        $successMessage = html_entity_decode('X&#243;a testimonial th&#224;nh c&#244;ng.', ENT_QUOTES, 'UTF-8');
        return redirect()->back()->with('success', $successMessage);
    }
}
