<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Support\ImageOptimizer;

class AboutController extends Controller
{
    public function index(){
        $about = About::first();
        return view('admin.profile',compact('about'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:5120',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'x' => 'nullable|url|max:255',
        ]);

        $about = About::first();
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $path = $file->hashName('public/images');
            Storage::put($path, ImageOptimizer::encode($file));
            $avatar = Storage::url($path);
            if (!empty($about?->avatar) && $about->avatar !== $avatar) {
                $this->deleteManagedUpload($about->avatar);
            }
        }else{
            $avatar = $about->avatar ?? null;
        }
        

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'name', 'description','content','about_me','tel','email', 'address',
            'x','instagram','facebook','linkedin'
        ]), [
            'avatar' => $avatar,

        ]);

        About::query()->updateOrCreate(
            ['id' => 1],
            $data
        );

        return redirect()->back()->with('success', 'Đã lưu thành công.');
    }
}
