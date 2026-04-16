<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Http\Request;
use Auth;

class RegisteredUserController extends Controller
{
    public function __construct(private RegisterService $service)
    {

    }
    public function create()
    {
        return view('auth.register');
    }
    public function store(RegisterRequest $request)
    {
        $user = $this->service->store($request->toServiceData());
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registration successful!');
    }
}
