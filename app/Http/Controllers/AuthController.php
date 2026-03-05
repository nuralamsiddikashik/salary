<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller {
    protected AuthRepository $authRepository;

    public function __construct( AuthRepository $authRepository ) {
        $this->authRepository = $authRepository;
    }

    /**
     * Show login page
     */
    public function showLogin(): View {
        return view( 'auth.login' );
    }

    /**
     * Handle login request
     */
    public function login( LoginRequest $request ): RedirectResponse {
        $result = $this->authRepository->login(
            $request->validated()
        );

        if ( !$result['status'] ) {

            return back()
                ->withErrors( [
                    'login' => $result['message'],
                ] )
                ->withInput(
                    $request->only( 'email' )
                );
        }

        return redirect()
            ->route( 'dashboard' )
            ->with( 'success', 'Login successful' );
    }

    /**
     * Logout user
     */
    public function logout(): RedirectResponse {
        $this->authRepository->logout();

        return redirect()
            ->route( 'login' )
            ->with( 'success', 'Logged out successfully' );
    }
}