<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No autenticado.');
        }

        foreach ($roles as $role) {
            $method = 'is' . str_replace(' ', '', ucwords(str_replace('_', ' ', $role)));
            
            if (method_exists($user, $method) && $user->$method()) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
}