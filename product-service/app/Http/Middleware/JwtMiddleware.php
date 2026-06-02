<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil token dari header request
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized: Token tidak ditemukan'], 401);
        }

        try {
            // 2. Cek keaslian token menggunakan JWT_SECRET dari .env
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            
            // 3. Simpan data user (ID, Role, dll) ke dalam request agar bisa dibaca oleh Controller
            $request->attributes->add(['auth_user' => $decoded]);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized: Token tidak valid atau kadaluarsa'], 401);
        }

        return $next($request); // Lolos! Lanjutkan ke Controller
    }
}