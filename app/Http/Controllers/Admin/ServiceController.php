<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
        $services = Service::orderBy('id','DESC')->paginate(20);
        return view('admin.service.index',compact('services'));
    }

    public function create(){
        return view('admin.service.create');
    }
    public function edit($id){
        $service = Service::findOrFail($id);
        return view('admin.service.edit',compact('service'));
    }
    public function store(Request $request){
        $id = $request->id;
        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'title', 'description'
        ]));

        Service::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        return redirect()->back();
    }
    public function delete($id){
        $n = Service::where('id',$id)->first();
        if ($n) {
            $n->delete();
            return redirect()->back()->with('success', 'Xóa ngành nghề thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy ngành nghề để xóa.');
    }
}
