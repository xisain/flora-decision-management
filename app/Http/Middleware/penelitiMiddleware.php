<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class penelitiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->roles->id == 3 || $request->user()->roles->id == 1) {
            return $next($request);
        } else {
            abort(403, 'Tidak Memiliki Akses');
        }
    }
}
