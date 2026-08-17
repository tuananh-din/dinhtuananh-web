<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\CaseStudyImage;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CaseStudyController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::latest()->paginate(20);

        return view('admin.case_study.index', compact('caseStudies'));
    }

    public function create()
    {
        return view('admin.case_study.create');
    }

    public function edit($id)
    {
        return view('admin.case_study.edit', ['caseStudy' => CaseStudy::with('images')->findOrFail($id)]);
    }

    public function preview($id)
    {
        $caseStudy = CaseStudy::with('images')->findOrFail($id);

        return view('case_study_detail', compact('caseStudy') + ['isPreview' => !$caseStudy->is_published]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'summary' => 'nullable|string|max:500',
            'client' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'platforms' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'kpi_1_label' => 'nullable|string|max:255',
            'kpi_1_value' => 'nullable|string|max:255',
            'kpi_2_label' => 'nullable|string|max:255',
            'kpi_2_value' => 'nullable|string|max:255',
            'kpi_3_label' => 'nullable|string|max:255',
            'kpi_3_value' => 'nullable|string|max:255',
            'kpi_4_label' => 'nullable|string|max:255',
            'kpi_4_value' => 'nullable|string|max:255',
            'content' => 'required|string',
            'title_seo' => 'nullable|string|max:255',
            'desc_seo' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*.caption' => 'nullable|string|max:255',
            'gallery.*.sort_order' => 'nullable|integer|min:0',
        ]);

        $id = $request->id;
        $caseStudy = CaseStudy::find($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->hashName('public/images');
            Storage::put($path, ImageOptimizer::encode($file));
            $image = Storage::url($path);

            if (!empty($caseStudy?->image) && $caseStudy->image !== $image) {
                $this->deleteManagedUpload($caseStudy->image);
            }
        } else {
            $image = $caseStudy?->image;
        }

        $baseSlug = Str::slug($request->slug ?: $request->title) ?: 'case-study';
        $slug = $baseSlug;
        $counter = 1;

        while (CaseStudy::where('slug', $slug)
            ->when($id, fn ($query) => $query->where('id', '!=', $id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $caseStudy = CaseStudy::query()->updateOrCreate(
            ['id' => $id],
            array_merge($request->only([
                'title', 'summary', 'client', 'industry', 'platforms', 'duration', 'role',
                'kpi_1_label', 'kpi_1_value', 'kpi_2_label', 'kpi_2_value',
                'kpi_3_label', 'kpi_3_value', 'kpi_4_label', 'kpi_4_value',
                'content', 'title_seo', 'desc_seo',
            ]), [
                'image' => $image,
                'slug' => $slug,
                'is_published' => $request->boolean('is_published'),
            ])
        );

        foreach ($request->input('gallery', []) as $imageId => $attributes) {
            $caseStudy->images()
                ->whereKey($imageId)
                ->update([
                    'caption' => $attributes['caption'] ?? null,
                    'sort_order' => $attributes['sort_order'] ?? 0,
                ]);
        }

        $nextSortOrder = (int) $caseStudy->images()->max('sort_order');

        foreach ($request->file('gallery_images', []) as $file) {
            $path = $file->hashName('public/images');
            Storage::put($path, ImageOptimizer::encode($file));

            $caseStudy->images()->create([
                'image' => Storage::url($path),
                'sort_order' => ++$nextSortOrder,
            ]);
        }

        return redirect()->back()->with('success', 'Đã lưu case study thành công.');
    }

    public function delete($id)
    {
        $caseStudy = CaseStudy::find($id);

        if (!$caseStudy) {
            return redirect()->back()->with('error', 'Không tìm thấy case study.');
        }

        $caseStudy->load('images');
        $this->deleteManagedUpload($caseStudy->image);
        foreach ($caseStudy->images as $image) {
            $this->deleteManagedUpload($image->image);
        }
        $caseStudy->delete();

        return redirect()->back()->with('success', 'Đã xóa case study thành công.');
    }

    public function deleteImage($id)
    {
        $image = CaseStudyImage::findOrFail($id);

        $this->deleteManagedUpload($image->image);
        $image->delete();

        return redirect()->back()->with('success', 'ÄÃ£ xÃ³a áº£nh minh chá»©ng thÃ nh cÃ´ng.');
    }
}
