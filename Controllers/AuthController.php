<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {

        $branches = Branch::all();
        return view('auth.login', compact('branches'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'branch_id' => ['required',  'exists:branches,id']
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
             session(['branch_id' => $credentials['branch_id']]);
            $user = Auth::user();

            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($credentials['password']);
                $user->save();
            }
             // Перенаправляем в зависимости от роли
            switch ($user->role) {
                case 'admin':
                  return redirect()->intended('/admin/dashboard');
                    break;
                case 'storekeeper':
                   return redirect()->intended('/storekeeper/dashboard');
                    break;
                case 'seller':
                   return redirect()->intended(route('seller.dashboard'));
                   break;
                default:
                   return redirect()->intended('/');
                break;
           }
        }
        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

