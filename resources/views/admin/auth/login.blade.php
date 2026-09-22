@extends('admin.layouts.guest')

@section('title', 'Admin Login')

@section('content')
    <div class="min-h-screen lg:flex">

        {{-- ============ LEFT: BRAND PANEL ============ --}}
        <div
            class="brand-side relative overflow-hidden text-white lg:w-1/2 min-h-[420px] lg:min-h-screen px-8 sm:px-14 py-12 flex flex-col">
            <div class="blob w-72 h-72 bg-blue-600/30 -top-10 -left-16"></div>
            <div class="blob w-96 h-96 bg-indigo-500/10 bottom-0 right-0"></div>

            <div class="relative flex items-center gap-3">
                <div
                    class="w-11 h-11 rounded-xl bg-linear-to-br from-blue-500 to-indigo-600 grid place-items-center shadow-lg shadow-blue-900/40">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">AgriExpress</span>
            </div>

            <div class="relative mt-10 sm:mt-14 max-w-md">
                <p class="text-blue-400 text-[12.5px] font-semibold tracking-wide">Admin panel</p>
                <h1 class="text-3xl sm:text-[2.6rem] leading-[1.15] font-bold mt-3">Manage your store<br>with <span
                        class="text-blue-400">ease</span></h1>
                <p class="text-slate-400 text-[15px] mt-4 leading-relaxed">Powerful tools to manage products, orders,
                    customers and grow your business.</p>

                <div class="grid grid-cols-2 gap-x-6 gap-y-5 mt-8 text-[13.5px]">
                    <div class="flex items-start gap-3">
                        <span class="feature-icon w-10 h-10 rounded-xl grid place-items-center shrink-0"><i
                                class="fa-solid fa-cube text-blue-300"></i></span>
                        <div>
                            <p class="font-semibold">Product management</p>
                            <p class="text-slate-400 text-[12.5px] mt-0.5">Add, edit &amp; organize products</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="feature-icon w-10 h-10 rounded-xl grid place-items-center shrink-0"><i
                                class="fa-solid fa-cart-shopping text-blue-300"></i></span>
                        <div>
                            <p class="font-semibold">Order tracking</p>
                            <p class="text-slate-400 text-[12.5px] mt-0.5">Monitor orders in real-time</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="feature-icon w-10 h-10 rounded-xl grid place-items-center shrink-0"><i
                                class="fa-solid fa-users text-blue-300"></i></span>
                        <div>
                            <p class="font-semibold">Customer management</p>
                            <p class="text-slate-400 text-[12.5px] mt-0.5">View &amp; manage your customers</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="feature-icon w-10 h-10 rounded-xl grid place-items-center shrink-0"><i
                                class="fa-solid fa-chart-simple text-blue-300"></i></span>
                        <div>
                            <p class="font-semibold">Sales analytics</p>
                            <p class="text-slate-400 text-[12.5px] mt-0.5">Get insights &amp; grow faster</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative mt-auto pt-12 hidden sm:block">
                <div class="floaty max-w-105">
                    <div class="laptop-shadow rounded-t-xl border-[6px] border-slate-700 bg-slate-800 p-2">
                        <div class="rounded-md overflow-hidden bg-[#f5f7fb]">
                            <div class="flex items-center gap-2 px-3 py-2 bg-[#0b1533]">
                                <i class="fa-solid fa-bag-shopping text-blue-400 text-[10px]"></i>
                                <span class="text-white text-[10px] font-semibold">AgriExpress</span>
                                <span class="ml-auto flex gap-1"><i
                                        class="fa-solid fa-circle text-[4px] text-slate-500"></i><i
                                        class="fa-solid fa-circle text-[4px] text-slate-500"></i></span>
                            </div>
                            <div class="p-3 grid grid-cols-3 gap-2">
                                <div class="bg-white rounded-lg p-2 col-span-1">
                                    <p class="text-[8px] text-slate-400">Total sales</p>
                                    <p class="text-[11px] font-bold text-slate-800">$12,493</p>
                                    <p class="text-[7px] text-emerald-500 font-semibold">&uarr; 12.5%</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 col-span-1">
                                    <p class="text-[8px] text-slate-400">Total orders</p>
                                    <p class="text-[11px] font-bold text-slate-800">48</p>
                                    <p class="text-[7px] text-emerald-500 font-semibold">&uarr; 8.3%</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 col-span-1">
                                    <p class="text-[8px] text-slate-400">Customers</p>
                                    <p class="text-[11px] font-bold text-slate-800">36</p>
                                    <p class="text-[7px] text-emerald-500 font-semibold">&uarr; 20.0%</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 col-span-3 h-14 flex items-end gap-1">
                                    <svg viewBox="0 0 200 40" class="w-full h-full" preserveAspectRatio="none">
                                        <polyline points="0,30 30,22 60,25 90,12 120,18 150,8 200,4" fill="none"
                                            stroke="#2563eb" stroke-width="2" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-3 bg-slate-600 rounded-b-xl mx-[-6px]"></div>
                    <div class="h-1.5 w-24 bg-slate-500 rounded-b-lg mx-auto"></div>
                </div>
            </div>

            <div class="relative flex items-center gap-2 text-[12px] text-slate-400 mt-10">
                <i class="fa-solid fa-bag-shopping text-blue-400"></i> AgriExpress &nbsp;&middot;&nbsp; &copy;
                {{ date('Y') }} AgriExpress. All rights reserved.
            </div>
        </div>

        {{-- ============ RIGHT: LOGIN FORM ============ --}}
        <div class="relative flex-1 flex flex-col min-h-[70vh] lg:min-h-screen">
            <div class="flex justify-end p-6 sm:p-8">
                <a href="{{ url('/') }}" class="text-sm muted hover:text-blue-600 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>Back to store
                </a>
            </div>

            <div class="flex-1 flex items-center justify-center px-6 pb-12">
                <form action="{{ route('admin.authenticate') }}" method="POST"
                    class="w-full max-w-[420px] surface border rounded-3xl p-8 sm:p-9 shadow-[0_20px_50px_-20px_rgba(15,23,42,.15)]">
                    @csrf

                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-blue-500 to-indigo-600 grid place-items-center text-white">
                                <i class="fa-solid fa-bag-shopping"></i></div>
                            <span class="text-lg font-bold">AgriExpress</span>
                        </div>
                        <h1 class="text-2xl font-bold mt-5">Admin login</h1>
                        <p class="muted text-sm mt-1.5">Sign in to your admin account</p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mt-7 space-y-5 text-sm">
                        <div>
                            <label for="email" class="block font-medium mb-1.5">Email address</label>
                            <div class="flex items-center gap-2.5 border bd rounded-xl px-3.5 h-11">
                                <i class="fa-regular fa-envelope text-slate-400"></i>
                                <input id="email" name="email" type="email" required autofocus
                                    class="flex-1 border-0 text-[14px]" placeholder="admin@shopease.com"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-medium mb-1.5">Password</label>
                            <div class="flex items-center gap-2.5 border bd rounded-xl px-3.5 h-11">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                                <input id="password" name="password" type="password" required
                                    class="flex-1 border-0 text-[14px]" placeholder="Enter your password">
                                <button type="button" onclick="togglePw()" aria-label="Show password"
                                    class="text-slate-400">
                                    <i id="pwIcon" class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between text-[13px]">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" checked class="w-4 h-4 rounded accent-blue-600">
                                <span class="muted">Remember me</span>
                            </label>
                            <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}"
                                class="text-blue-600 font-medium">Forgot password?</a>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl h-11 flex items-center justify-center gap-2.5 transition-colors">
                            <i class="fa-solid fa-right-to-bracket"></i> Sign in
                        </button>
                    </div>
                    {{-- 
                <div class="flex items-center gap-3 my-6">
                    <span class="flex-1 h-px" style="background:var(--border)"></span>
                    <span class="text-[11.5px] muted">OR</span>
                    <span class="flex-1 h-px" style="background:var(--border)"></span>
                </div>

                <div class="flex items-center gap-3 justify-center text-center">
                    <span class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 grid place-items-center shrink-0"><i class="fa-solid fa-shield-halved text-sm"></i></span>
                    <div class="text-left">
                        <p class="text-[13px] font-medium">Secure login</p>
                        <p class="text-[11.5px] muted">Your data is protected and encrypted</p>
                    </div>
                </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePw() {
            const f = document.getElementById('password');
            const icon = document.getElementById('pwIcon');
            const show = f.type === 'password';
            f.type = show ? 'text' : 'password';
            icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        }
    </script>
@endpush
