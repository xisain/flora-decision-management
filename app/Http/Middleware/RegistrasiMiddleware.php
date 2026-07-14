<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrasiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->roles->name === 'teknisi registrasi') {
            //dd($request->user()->roles);
            return $next($request);
        } else {
            abort(403, 'Tidak Memiliki Akses');
        }
    }
}
