@extends('layout.auth')

@section('title', 'Register')
@section('image_title', 'Join the team')
@section('image_subtitle', 'Register now to manage your research and inventory from one secure dashboard.')
@section('page_title', 'Create your account')
@section('page_subtitle', 'Fill in your details to get started.')

@section('content')
    <form class="flex flex-col gap-5" method="POST" action="#">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-x-5">

            <div class="flex flex-col gap-2">
                <label for="name" class="text-[0.95rem] text-[#4f4f47] font-medium">Full name</label>
                <input id="name" name="name" type="text" placeholder="Enter your full name"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="email" class="text-[0.95rem] text-[#4f4f47] font-medium">Email address</label>
                <input id="email" name="email" type="email" placeholder="you@example.com"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="phone_number" class="text-[0.95rem] text-[#4f4f47] font-medium">Phone number</label>
                <input id="phone_number" name="phone_number" type="tel" placeholder="0812 3456 7890"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="collector_initial" class="text-[0.95rem] text-[#4f4f47] font-medium">Collector initial</label>
                <input id="collector_initial" name="collector_initial" type="text" placeholder="AB"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-[0.95rem] text-[#4f4f47] font-medium">Password</label>
                <input id="password" name="password" type="password" placeholder="Create a password"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="text-[0.95rem] text-[#4f4f47] font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repeat your password"
                    class="w-full px-4 py-3.5 border border-[#d7d7d3] rounded-2xl bg-[#fbfbfb] text-[#2b2b25] text-[0.98rem] focus:outline-none focus:ring-2 focus:ring-[var(--flora-sage)]" />
            </div>

        </div>

        <button type="submit"
            class="w-full border-none rounded-2xl py-4 px-5 font-bold text-base text-white bg-[var(--flora-sage)] cursor-pointer transition-all duration-[180ms] ease-in-out hover:bg-[var(--flora-moss)] hover:-translate-y-px">
            Register
        </button>
    </form>
@endsection
