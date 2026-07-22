<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AboutController extends Controller
{
    public function index(){
        $about = About::first();
        return view('admin.profile',compact('about'));
    }

    public function updateProfile(Request $request){

        $about = About::first();
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $avatar = Storage::url($path);
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

        return redirect()->back();
    }
}
