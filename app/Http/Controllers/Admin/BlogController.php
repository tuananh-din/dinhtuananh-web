<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Str;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::orderBy('id','DESC')->paginate(20);
        $publishedCount = Blog::where('is_published', 1)->count();
        $draftCount = Blog::where('is_published', 0)->count();
        return view('admin.blog.index', compact('blogs', 'publishedCount', 'draftCount'));
    }

    public function create(){
        return view('admin.blog.create', ['categories' => Category::orderBy('name')->get()]);
    }
    public function edit($id){
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', ['blog' => $blog, 'categories' => Category::orderBy('name')->get()]);
    }
    public function preview($id)
    {
        $blog = Blog::with('categories')->findOrFail($id);
        $otherBlogs = Blog::where('is_published', 1)->where('id', '!=', $blog->id)->latest()->limit(3)->get();
        $featuredCourse = Course::where('is_active', 1)->where('is_featured', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        if (!$featuredCourse) {
            $featuredCourse = Course::where('is_active', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        }
        return view('blog_detail', compact('blog', 'otherBlogs', 'featuredCourse') + ['isPreview' => !$blog->is_published]);
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $id = $request->id;
        $blog = Blog::find($id);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());
            $image = Storage::url($path);
            if (!empty($blog?->image) && $blog->image !== $image) {
                $this->deleteManagedUpload($blog->image);
            }
        }else{
            $image = $blog->image ?? null;
        }

        if ($blog) {
            $slug = $blog->slug;
        } else {
            $baseSlug = Str::slug($request->title);
            if (!$baseSlug) {
                $baseSlug = 'blog';
            }

            $slug = $baseSlug;
            $counter = 1;
            while (
                Blog::where('slug', $slug)
                    ->when($id, function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    })
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'title', 'description','content', 'title_seo',
            'desc_seo', 'key_seo'
        ]), [
            'image' => $image,
            'slug' => $slug,
            'is_published' => $request->boolean('is_published'),

        ]);

        $savedBlog = Blog::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        $savedBlog->categories()->sync($request->input('categories', []));
        return redirect()->back()->with('success', 'Đã lưu thành công.');
    }
    public function delete($id){
        $n = Blog::where('id',$id)->first();
        if ($n) {
            $this->deleteManagedUpload($n->image);
            $n->delete();
            return redirect()->back()->with('success', 'Xóa bài viết thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy bài viết để xóa.');
    }
}
