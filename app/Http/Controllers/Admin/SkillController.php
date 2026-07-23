<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(){
        $skills = Skill::orderBy('id','DESC')->paginate(20);
        return view('admin.skill.index',compact('skills'));
    }

    public function create(){
        return view('admin.skill.create');
    }
    public function edit($id){
        $skill = Skill::findOrFail($id);
        return view('admin.skill.edit',compact('skill'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'nullable|integer',
        ]);

        $id = $request->id;
        // Chuẩn bị dữ liệu sản phẩm
        $data = array_merge($request->only([
            'name', 'description', 'number'
        ]));

        Skill::query()->updateOrCreate(
            ['id' => $id],
            $data
        );
        return redirect()->back();
    }
    public function delete($id){
        $n = Skill::where('id',$id)->first();
        if ($n) {
            $n->delete();
            return redirect()->back()->with('success', 'Xóa kỹ năng thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy kỹ năng để xóa.');
    }
}
