<?php

namespace App\Services;

use App\Repositories\AppSettingsRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ExceptionReporter
{
    public static function send(\Throwable $e): void
    {
        try {
            $fingerprint = hash('sha1', implode('|', [
                get_class($e), $e->getFile(), $e->getLine(),
                mb_substr($e->getMessage(), 0, 500),
            ]));

            $payload = [
                'app'         => 'bukvar-android',
                'app_id'      => (new AppSettingsRepository())->getAppId(),
                'env'         => config('app.env'),
                'app_version' => config('app.version'),
                'php'         => PHP_VERSION,
                'message'     => $e->getMessage(),
                'exception'   => get_class($e),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'trace'       => Str::limit($e->getTraceAsString(), 8000),
                'url'         => request()->fullUrl() ?? null,
                'method'      => request()->method() ?? null,
                'context'     => self::safeContext(),
                'fingerprint' => $fingerprint,
                'timestamp'   => now()->toIso8601String(),
            ];

            // 1) Serialize once
            $body   = json_encode($payload, JSON_UNESCAPED_SLASHES);

            // 3) Send exactly those bytes
            $url = config('services.exception_sink.url'); // e.g. https://yourdomain/api/exception-sink
            Http::timeout(5)
                ->adminHeaders()
                ->withBody($body, 'application/json')
                ->post($url);
        } catch (\Throwable $ignore) {
            // swallow
        }
    }

    protected static function safeContext(): array
    {
        // Scrub sensitive data
        $input = request()->except(['password', 'password_confirmation', 'token', 'authorization']);
        return [
            'ip'      => request()->ip(),
            'agent'   => request()->userAgent(),
            'input'   => $input,
            'headers' => collect(request()->headers->all())
                ->except(['authorization', 'cookie'])
                ->toArray(),
        ];
    }
}
