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
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'favicon' => 'nullable|image|max:5120',
        ]);

        $setting = Setting::first();
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $logo = Storage::url($path);
            if (!empty($setting?->logo) && $setting->logo !== $logo) {
                $this->deleteManagedUpload($setting->logo);
            }
        }else{
            $logo = $setting->logo ?? null;
        }
        
        if($request->hasFile('favicon')){
            $file = $request->file('favicon');
            $path = $file->hashName('public/images');
            $img = Image::read($file);
            Storage::put($path, (string) $img->encode());   
            $favicon = Storage::url($path);
            if (!empty($setting?->favicon) && $setting->favicon !== $favicon) {
                $this->deleteManagedUpload($setting->favicon);
            }
        }else{
            $favicon = $setting->favicon ?? null;
        }

        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'name', 'url','code_header','code_footer','slogan','note', 'title_seo', 
            'desc_seo', 'key_seo'
        ]), [
            'logo' => $logo,
            'favicon' => $favicon

        ]);

        Setting::query()->updateOrCreate(
            ['id' => 1],
            $data
        );

        return redirect()->back()->with('success', 'Đã lưu thành công.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('upload');
        $fileName = $file->hashName();
        $file->move(public_path('media'), $fileName);

        $url = asset('media/' . $fileName);

        return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
    }

}
