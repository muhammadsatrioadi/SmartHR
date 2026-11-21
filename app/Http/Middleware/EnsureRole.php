<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses route berdasarkan peran akun (users.role).
 *
 * Contoh pemakaian: Route::middleware('role:admin_hr')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user && in_array($user->role, $roles, true), 403, 'Anda tidak memiliki hak akses ke halaman ini.');

        return $next($request);
    }
}
