<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->back()->with('success', 'Cảm ơn bạn đã đăng ký.');
        }

        $request->validate([
            'email' => 'required|email|max:255',
            'source' => 'nullable|string|max:255',
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['source' => $request->source ?: 'unknown']
        );

        if (!$subscriber->wasRecentlyCreated) {
            return redirect()->back()->with('success', 'Email này đã được đăng ký. Cảm ơn bạn!');
        }

        $apiKey = config('services.brevo.api_key');
        $listId = config('services.brevo.list_id');

        if ($apiKey && $listId) {
            try {
                Http::acceptJson()
                    ->withHeaders(['api-key' => $apiKey])
                    ->post('https://api.brevo.com/v3/contacts', [
                        'email' => $subscriber->email,
                        'listIds' => [(int) $listId],
                        'updateEnabled' => true,
                    ])
                    ->throw();
            } catch (\Throwable $e) {
                Log::error('Brevo subscriber sync failed.', [
                    'subscriber_id' => $subscriber->id,
                    'exception_class' => $e::class,
                ]);
            }
        } else {
            Log::warning('Brevo subscriber sync skipped because configuration is missing.', [
                'subscriber_id' => $subscriber->id,
            ]);
        }

        return redirect()->route('thank.you')->with('success', 'Đăng ký newsletter thành công.');
    }
}
