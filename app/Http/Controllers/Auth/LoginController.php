<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Guardar el redirect si viene en la URL
        if ($redirect = request()->get('redirect')) {
            session(['redirect_url' => urldecode($redirect)]);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Si el login fue exitoso, verificar si hay redirección guardada
            if (session()->has('redirect_url')) {
                $redirectUrl = session()->get('redirect_url');
                session()->forget('redirect_url');
                return redirect($redirectUrl)->with('message', 'Bienvenido de nuevo');
            }

            return redirect()->intended('/admin/pedidos')->with('message', 'Bienvenido de nuevo');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
}
