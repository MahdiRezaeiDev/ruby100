<?php

namespace App\Http\Controllers;

use App\Mail\QuoteRequestMail;
use App\Models\QuoteRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class QuoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        return $this->persist($request, $request->input('service') === 'Cash for Cars' ? 'cash_for_cars' : 'quote', 'quote_success');
    }

    public function cashForCars(Request $request): RedirectResponse
    {
        $request->merge([
            'service' => 'Cash for Cars',
        ]);

        return $this->persist($request, 'cash_for_cars', 'cash_success');
    }

    protected function persist(Request $request, string $type, string $flashKey): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return back()->with($flashKey, true);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'min:8', 'max:40', 'regex:/^\+?[0-9 ()\-]{8,40}$/'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'service' => ['required', 'string', Rule::in(['Towing Services', 'Car Removal', 'Scrap Metal Collection', 'Emergency Assistance', 'Machinery Transport', 'Cash for Cars'])],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $quote = QuoteRequest::create([
            ...$validated,
            'type' => $type,
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        $notify = SiteSetting::current()->notify_email;

        if (filled($notify)) {
            try {
                Mail::to($notify)->queue(new QuoteRequestMail($quote));
            } catch (\Throwable $exception) {
                // The request is already saved; a mail outage must not invite duplicate submissions.
                report($exception);
            }
        }

        return redirect()
            ->route('home')
            ->withFragment('quote')
            ->with($flashKey, true);
    }
}
