<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if (!auth()->user()->is_admin) {
            abort(403, 'Accès non autorisé. Cette section est réservée aux administrateurs.');
        }

        return $next($request);
    }
}
