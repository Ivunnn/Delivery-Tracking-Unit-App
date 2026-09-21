<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login
        if (! Auth::check()) {
            return redirect()->route('signin')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Akun dinonaktifkan
        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('signin')
                ->with('error', 'Akun kamu telah dinonaktifkan. Hubungi administrator.');
        }

        // Role tidak sesuai
        if (! in_array($user->role, $roles)) {
            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}