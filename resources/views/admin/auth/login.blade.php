{{--
  admin/auth/login.blade.php
  Deliberately minimal: one centered card, the real site logo, and nothing
  else competing for attention. Controller: Admin\AuthController@store via
  route('admin.authenticate').
--}}
@extends('admin.layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex flex-col">

    <div class="p-6 sm:p-8">
        <a href="{{ url('/') }}" class="text-sm muted hover:text-blue-600 flex items-center gap-2 w-fit">
            <i class="fa-solid fa-arrow-left text-xs"></i>Back to store
        </a>
    </div>

    <div class="flex-1 flex items-center justify-center px-6 pb-16">
        <form action="{{ route('admin.authenticate') }}" method="POST"
              class="w-full max-w-[380px]">
            @csrf

            <div class="flex flex-col items-center text-center mb-8">
                <img src="{{ asset('assets/images/logo.png') }}" alt="AgriExpress" class="h-10 w-auto">
                <h1 class="text-xl font-bold mt-6">Admin login</h1>
                <p class="muted text-sm mt-1">Sign in to your admin account</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-4 text-sm">
                <div>
                    <label for="email" class="block font-medium mb-1.5">Email address</label>
                    <div class="flex items-center gap-2.5 border bd rounded-xl px-3.5 h-11">
                        <i class="fa-regular fa-envelope text-slate-400"></i>
                        <input id="email" name="email" type="email" required autofocus
                               class="flex-1 border-0 text-[14px]" placeholder="admin@agriexpress.com"
                               value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block font-medium mb-1.5">Password</label>
                    <div class="flex items-center gap-2.5 border bd rounded-xl px-3.5 h-11">
                        <i class="fa-solid fa-lock text-slate-400"></i>
                        <input id="password" name="password" type="password" required
                               class="flex-1 border-0 text-[14px]" placeholder="Enter your password">
                        <button type="button" onclick="togglePw()" aria-label="Show password" class="text-slate-400">
                            <i id="pwIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between text-[13px]">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" checked class="w-4 h-4 rounded accent-blue-600">
                        <span class="muted">Remember me</span>
                    </label>
                    <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}" class="text-blue-600 font-medium">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl h-11 flex items-center justify-center gap-2.5 transition-colors">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePw(){
    const f = document.getElementById('password');
    const icon = document.getElementById('pwIcon');
    const show = f.type === 'password';
    f.type = show ? 'text' : 'password';
    icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
}
</script>
@endpush
