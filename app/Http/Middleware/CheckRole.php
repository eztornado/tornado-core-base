<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Comprueba si el usuario está autenticado y si tiene el rol requerido
        if (!Auth::check() || Auth::user()->roles_id != $role) {
            // Redirige o maneja la autorización según sea necesario
            return redirect('login');
        }

        return $next($request);
    }
}
