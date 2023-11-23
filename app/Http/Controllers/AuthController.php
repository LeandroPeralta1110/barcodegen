<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function authenticated(Request $request, $user)
    {
        if (!$user->activo) {
            auth()->logout(); // Utiliza el método logout() en lugar de logout
            return redirect()->route('login')->with('error', 'Your account is not active.');
        }

        return redirect()->route('generar-codigo-barras');
    }
}
