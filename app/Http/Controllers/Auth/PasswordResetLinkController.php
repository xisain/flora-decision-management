<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.reset-password');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'lowercase']
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email']
        ]);
        // dd($status);
    }
}
