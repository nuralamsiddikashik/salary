<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthRepository {
    public function login( array $data ): array {
        $key = 'login:' . request()->ip();

        // 🔒 Rate limit check
        if ( RateLimiter::tooManyAttempts( $key, 5 ) ) {
            return [
                'status'  => false,
                'message' => 'Too many login attempts. Try again later.',
            ];
        }

        $user = User::where( 'email', $data['email'] )->first();

        // 🔒 Prevent user enumeration
        if ( !$user ) {

            Hash::check( $data['password'], '$2y$10$usesomesillystringforsalt$' );

            RateLimiter::hit( $key, 60 );

            Log::warning( 'Login attempt with invalid email', [
                'email' => $data['email'],
                'ip'    => request()->ip(),
            ] );

            return [
                'status'  => false,
                'message' => 'Invalid credentials',
            ];
        }

        // ✅ 🔥 NEW: Account suspend check
        if ( !$user->is_active ) {

            Log::warning( 'Suspended user login attempt', [
                'user_id' => $user->id,
                'ip'      => request()->ip(),
            ] );

            return [
                'status'  => false,
                'message' => 'Your account has been suspended. Contact admin.',
            ];
        }

        // 🔒 Password check
        if ( !Hash::check( $data['password'], $user->password ) ) {

            RateLimiter::hit( $key, 60 );

            Log::warning( 'Invalid password attempt', [
                'user_id' => $user->id,
                'ip'      => request()->ip(),
            ] );

            return [
                'status'  => false,
                'message' => 'Invalid credentials',
            ];
        }

        // 🔒 Clear attempts
        RateLimiter::clear( $key );

        Auth::login( $user );

        request()->session()->regenerate();

        Log::info( 'User logged in', [
            'user_id' => $user->id,
            'ip'      => request()->ip(),
        ] );

        return [
            'status' => true,
            'user'   => $user,
        ];
    }

    public function logout(): void {
        Log::info( 'User logged out', [
            'user_id' => Auth::id(),
            'ip'      => request()->ip(),
        ] );

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}