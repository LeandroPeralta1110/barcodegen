<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;

class CustomAuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            Fortify::username() => 'required|string',
            'password' => 'required|string',
        ]);

        $user = $this->attemptLogin($request);

        if ($user && !$user->activo) {
            $this->guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account is not active.');
        }

        return $this->sendLoginResponse($request);
    }
}
