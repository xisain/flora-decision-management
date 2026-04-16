@extends('layout.auth')

@section('title', 'Login')
@section('image_title', 'Welcome back')
@section('image_subtitle', 'Sign in to access your dashboard and manage your plant collection.')
@section('page_title', 'Sign in to your account')
@section('page_subtitle', 'Enter your credentials to continue.')

@section('content')
    <form class="flex flex-col gap-5" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="flex flex-col gap-4">

            <div class="flex flex-col gap-2">
                <label for="email" class="text-[0.95rem] text-[#4f4f47] font-medium">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-[0.95rem] text-[#4f4f47] font-medium">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-[0.9rem] text-[#4f4f47]">Remember me</span>
                </label>
                <a href="#" class="text-[0.9rem] text-[var(--flora-teal)] hover:underline">Forgot password?</a>
            </div>

        </div>

        <button type="submit"
            class="w-full border-none rounded-2xl py-4 px-5 font-bold text-base text-white bg-[var(--flora-sage)] cursor-pointer transition-all duration-[180ms] ease-in-out hover:bg-[var(--flora-moss)] hover:-translate-y-px">
            Sign In
        </button>

        <p class="text-center text-[0.9rem] text-[#4f4f47]">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[var(--flora-teal)] hover:underline font-medium">Register here</a>
        </p>
    </form>
@endsection
