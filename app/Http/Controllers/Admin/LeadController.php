<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        if (!array_key_exists($status, Lead::STATUSES)) {
            $status = null;
        }

        $search = trim((string) $request->query('search', ''));
        $leads = $this->filteredLeads($status, $search)
            ->with('course')
            ->orderBy('id', 'DESC')
            ->paginate(20)
            ->withQueryString();
        $counts = Lead::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $total = $counts->sum();

        return view('admin.lead.index', compact('leads', 'status', 'search', 'counts', 'total'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(Lead::STATUSES))],
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

    public function delete($id)
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return redirect()->back()->with('error', 'Không tìm thấy lead.');
        }

        $lead->delete();

        return redirect()->back()->with('success', 'Đã xóa lead.');
    }

    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status');
        if (!array_key_exists($status, Lead::STATUSES)) {
            $status = null;
        }

        $search = trim((string) $request->query('search', ''));
        $leads = $this->filteredLeads($status, $search)->with('course')->orderByDesc('id')->get();

        return response()->streamDownload(function () use ($leads) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Tên', 'Số điện thoại', 'Email', 'Khóa học', 'Trạng thái', 'Nguồn', 'Thời gian']);

            foreach ($leads as $lead) {
                fputcsv($output, [
                    $this->csvValue($lead->name),
                    $this->csvValue($lead->phone),
                    $this->csvValue($lead->email),
                    $this->csvValue($lead->course?->title),
                    $this->csvValue(Lead::STATUSES[$lead->status] ?? $lead->status),
                    $this->csvValue($lead->source_page),
                    $lead->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($output);
        }, 'leads.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredLeads(?string $status, string $search)
    {
        return Lead::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
    }

    private function csvValue(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@]/', $value) ? "'" . $value : $value;
    }
}
