<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' =>
            $request->password])
        ) {
            return ['success' => true];
        } else {
            abort(422, __('auth.failed'));
        }
    }

    public function logout()
    {
        Auth::logout();

        return ['success' => true];
    }
}
