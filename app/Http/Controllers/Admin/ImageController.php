<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image as ModelsImage;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    public function index(){
        $images = ModelsImage::orderBy('id','DESC')->paginate(20);
        return view('admin.image.index',compact('images'));
    }

    public function create(){
        return view('admin.image.create');
    }
    public function edit($id){
        $image = ModelsImage::findOrFail($id);
        return view('admin.image.edit',compact('image'));
    }
    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|max:5120',
        ]);

        $id = $request->id;
        $imageModel = ModelsImage::find($id);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $image = Storage::url($path);
            if (!empty($imageModel?->image) && $imageModel->image !== $image) {
                $this->deleteManagedUpload($imageModel->image);
            }
        }else{
            $image = $imageModel->image ?? null;
            $path = $imageModel->path ?? '';
        }
        

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'title', 'description','type'
        ]), [
            'image' => $image,
            'path' => $path

        ]);

        ModelsImage::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        return redirect()->back();
    }
    public function delete($id){
        $n = ModelsImage::where('id',$id)->first();
        if ($n) {
            Storage::delete($n->path);
            $n->delete();
            return redirect()->back()->with('success', 'Xóa hình ảnh thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy hình ảnh để xóa.');
    }
}
