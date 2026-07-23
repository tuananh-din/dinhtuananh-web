<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Str;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::orderBy('id','DESC')->paginate(20);
        return view('admin.blog.index',compact('blogs'));
    }

    public function create(){
        return view('admin.blog.create');
    }
    public function edit($id){
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit',compact('blog'));
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $id = $request->id;
        $blog = Blog::find($id);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());
            $image = Storage::url($path);
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

        ]);

        Blog::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        return redirect()->back();
    }
    public function delete($id){
        $n = Blog::where('id',$id)->first();
        if ($n) {
            $n->delete();
            return redirect()->back()->with('success', 'Xóa bài viết thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy bài viết để xóa.');
    }
}
