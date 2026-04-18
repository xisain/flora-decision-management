@extends('layout.auth')
@section('image_title', 'Reset Password')
@section('image_subtitle', 'Fill Email For Reseting Your Password')
@section('page_title', 'Forget Password?')
@section('page_subtitle', 'Enter your credentials to continue.')
@section('content')
    <form class="flex flex-col gap-5" method="POST" action="{{ route('password.email') }}">
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

            <button type="submit"
                class="w-full border-none rounded-2xl py-4 px-5 font-bold text-base text-white bg-[var(--flora-sage)] cursor-pointer transition-all duration-[180ms] ease-in-out hover:bg-[var(--flora-moss)] hover:-translate-y-px">
                Reset Password
            </button>
        </div>

    </form>
@endsection
