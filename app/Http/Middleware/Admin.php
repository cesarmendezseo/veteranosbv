<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No autenticado.');
        }

        // Si no se pasaron permisos → solo admin
        if (empty($permissions)) {
            if ($user->hasRole('administrador')) {
                return $next($request);
            }
            abort(403, 'Acceso solo para administradores.');
        }

        // Normaliza: separa por coma o pipe
        $list = [];
        foreach ($permissions as $chunk) {
            foreach (preg_split('/[|,]/', $chunk) as $p) {
                $p = trim($p);
                if ($p !== '') $list[] = $p;
            }
        }

        // Si es admin o tiene algún permiso → pasa
        if ($user->hasRole('administrador') || $user->hasAnyPermission($list)) {
            return $next($request);
        }

        abort(403, 'No tenés permisos para esta acción.');
    }
}
