<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
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
        $blog = Blog::find($id);
        return view('admin.blog.edit',compact('blog'));
    }
    public function store(Request $request){
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
        

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'title', 'description','content', 'title_seo', 
            'desc_seo', 'key_seo'
        ]), [
            'image' => $image,
            'slug' => Str::slug($request->title),

        ]);

        Blog::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        return redirect()->back();
    }
    public function delete($id){
        $n = Blog::where('id',$id)->first();
        $n->delete();
        return redirect()->back();
    }
}
