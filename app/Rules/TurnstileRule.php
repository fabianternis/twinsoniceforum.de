<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.turnstile.secret_key');

        // Allow dummy test mode if secret is dummy key (always passes test)
        if (empty($value) && in_array($secretKey, ['1x0000000000000000000000000000000AA', '2x0000000000000000000000000000000AA'])) {
            return;
        }

        if (empty($value)) {
            $fail('Bitte bestaetige die Cloudflare Turnstile Sicherheitspruefung.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $body = $response->json();

            if (!$response->successful() || !($body['success'] ?? false)) {
                $fail('Die Cloudflare Turnstile Sicherheitspruefung ist fehlgeschlagen.');
            }
        } catch (\Exception $e) {
            // Fail gracefully if HTTP request fails in local dev environment
            if (!app()->isLocal()) {
                $fail('Sicherheitspruefung derzeit nicht verfuegbar.');
            }
        }
    }
}
