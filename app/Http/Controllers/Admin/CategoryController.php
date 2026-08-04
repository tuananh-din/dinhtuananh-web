<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index() { return view('admin.category.index', ['categories' => Category::latest()->paginate(20)]); }
    public function create() { return view('admin.category.form'); }
    public function edit($id) { return view('admin.category.form', ['category' => Category::findOrFail($id)]); }
    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate(['name' => 'required|string|max:255', 'slug' => ['nullable','string','max:255', Rule::unique('categories','slug')->ignore($id)]]);
        $slug = Str::slug($request->slug ?: $request->name) ?: 'category';
        $base = $slug; $i = 1;
        while (Category::where('slug', $slug)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) { $slug = $base.'-'.$i++; }
        Category::updateOrCreate(['id' => $id], ['name' => $request->name, 'slug' => $slug]);
        return redirect()->route('admin.category')->with('success', 'Đã lưu chuyên mục.');
    }
    public function delete($id) { Category::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Đã xóa chuyên mục.'); }
}
