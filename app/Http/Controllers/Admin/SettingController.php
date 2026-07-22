<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SettingController extends Controller
{
    public function index(){
        $setting = Setting::first();

        return view('admin.setting',compact('setting'));
    }

    public function updateSetting(Request $request){

        $setting = Setting::first();
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $logo = Storage::url($path);
        }else{
            $logo = $setting->logo ?? null;
        }
        
        if($request->hasFile('favicon')){
            $file = $request->file('favicon');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $favicon = Storage::url($path);
        }else{
            $favicon = $setting->favicon ?? null;
        }

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'name', 'url','code_header','code_body','slogan','note', 'title_seo', 
            'desc_seo', 'key_seo'
        ]), [
            'logo' => $logo,
            'favicon' => $favicon

        ]);

        Setting::query()->updateOrCreate(
            ['id' => 1],
            $data
        );

        return redirect()->back();
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            $request->file('upload')->move(public_path('media'), $fileName);
    
            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

}
