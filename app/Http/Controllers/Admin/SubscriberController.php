<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $source = trim((string) $request->query('source', ''));
        $subscribers = $this->filteredSubscribers($search, $source)->latest()->paginate(20)->withQueryString();
        $counts = Subscriber::selectRaw("COALESCE(NULLIF(source, ''), 'unknown') as source, COUNT(*) as total")
            ->groupBy('source')->orderBy('source')->pluck('total', 'source');

        return view('admin.subscriber.index', compact('subscribers', 'search', 'source', 'counts'))
            ->with('total', $counts->sum());
    }

    public function export(Request $request): StreamedResponse
    {
        $search = trim((string) $request->query('search', ''));
        $source = trim((string) $request->query('source', ''));
        $subscribers = $this->filteredSubscribers($search, $source)->latest()->get();

        return response()->streamDownload(function () use ($subscribers) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Email', 'Nguon', 'Ngay dang ky']);

            foreach ($subscribers as $subscriber) {
                fputcsv($output, [$this->csvValue($subscriber->email), $this->csvValue($subscriber->source), $subscriber->created_at?->format('Y-m-d H:i:s')]);
            }

            fclose($output);
        }, 'subscribers.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function delete(int $id)
    {
        $subscriber = Subscriber::find($id);

        if (!$subscriber) {
            return redirect()->back()->with('error', 'Khong tim thay email dang ky.');
        }

        $subscriber->delete();

        return redirect()->back()->with('success', 'Da xoa email dang ky.');
    }

    private function filteredSubscribers(string $search, string $source)
    {
        return Subscriber::query()
            ->when($search !== '', fn ($query) => $query->where('email', 'like', '%'.$search.'%'))
            ->when($source !== '', fn ($query) => $query->where('source', $source));
    }

    private function csvValue(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@]/', $value) ? "'".$value : $value;
    }
}
