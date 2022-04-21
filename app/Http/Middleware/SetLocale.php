<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        App::setLocale($request->route()->parameter('lang') ?? '');
        return $next($request);
    }
}
