<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission {
    public function handle( $request, Closure $next, $permission ) {
        $user = auth()->user();

        if ( !$user->is_active ) {
            abort( 403, 'Account Suspended' );
        }

        if ( $user->role === 'admin' ) {
            return $next( $request );
        }

        if ( !$user->hasPermission( $permission ) ) {
            abort( 403, 'Unauthorized' );
        }

        return $next( $request );
    }
}