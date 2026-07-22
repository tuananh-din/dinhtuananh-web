<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::orderBy('id', 'DESC')->paginate(20);
        return view('admin.lead.index', compact('leads'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
            'note' => 'nullable|string',
        ]);

        $lead = Lead::find($id);
        if (!$lead) {
            $errorMessage = html_entity_decode('Kh&#244;ng t&#236;m th&#7845;y lead.', ENT_QUOTES, 'UTF-8');
            return redirect()->back()->with('error', $errorMessage);
        }

        $lead->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        $successMessage = html_entity_decode('C&#7853;p nh&#7853;t lead th&#224;nh c&#244;ng.', ENT_QUOTES, 'UTF-8');
        return redirect()->back()->with('success', $successMessage);
    }
}
