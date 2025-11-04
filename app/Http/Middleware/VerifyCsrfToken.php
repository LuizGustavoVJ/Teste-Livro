<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            Log::warning(
                'CSRF error',
                [
                'session_token' => $request->session()->token(),
                'input_token' => $request->input('_token'),
                'cookie_token' => $request->cookie('XSRF-TOKEN'),
                ]
            );
            throw $e;
        }
    }
}
