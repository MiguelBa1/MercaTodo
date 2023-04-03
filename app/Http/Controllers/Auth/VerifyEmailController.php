<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Make a log
        Log::info('[EMAIL: VERIFIED]', [
            'user_id' => $request->user()->getAttribute('id'),
            'user_name' => $request->user()->getAttribute('name'),
            'user_email' => $request->user()->getAttribute('email'),
        ]);

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
