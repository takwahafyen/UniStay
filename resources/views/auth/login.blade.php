@extends('layouts.app')

@section('title', __('Login Area') . ' - StudentHousing')

@section('content')
<div class="max-w-md mx-auto my-12 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 text-xl border border-indigo-100 dark:border-indigo-800/50">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Login Area') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Sign in to manage your bookings and listings') }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Email Address') }}</label>
            <div class="relative">
                <i class="fa-regular fa-envelope absolute ltr:left-3.5 rtl:right-3.5 top-3.5 text-slate-400 dark:text-slate-500"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="exemple@studenthousing.com"
                    class="w-full ltr:pl-10 ltr:pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm outline-none transition">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Password') }}</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute ltr:left-3.5 rtl:right-3.5 top-3.5 text-slate-400 dark:text-slate-500"></i>
                <input type="password" id="password" name="password" required
                    placeholder="••••••••••••"
                    class="w-full ltr:pl-10 ltr:pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm outline-none transition">
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-400">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:ring-indigo-500">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket rtl:rotate-180"></i>
            <span>{{ __('Sign In') }}</span>
        </button>
    </form>

    <!-- Alternative: Instant Email Code (OTP) / Google Login -->
    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 text-center mb-3">
            <i class="fa-brands fa-google text-rose-500 mr-1"></i> Connexion avec Code de validation par Email / Google
        </p>
        <form action="{{ route('login.otp.send') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="email" name="email" required placeholder="votre-email@student.com" 
                class="flex-1 px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none">
            <button type="submit" class="px-3 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-paper-plane text-[10px]"></i>
                <span>Recevoir Code</span>
            </button>
        </form>
    </div>

    <!-- Registration Link for House Owners & Students -->
    <div class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
        Vous êtes nouveau ? 
        <a href="{{ route('register') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Créer un compte Bailleur ou Étudiant</a>
    </div>

    <!-- Quick Fill for Demo Reviewers -->
    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-center mb-3">{{ __('Demo Accounts (Quick Autofill)') }}</p>
        <div class="grid grid-cols-1 gap-2">
            <button type="button" onclick="fillForm('student@studenthousing.com', 'StudentPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ __('Student Demo') }}</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500 font-mono text-[11px]">student@studenthousing.com</span>
            </button>
            <button type="button" onclick="fillForm('owner@studenthousing.com', 'OwnerPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>{{ __('Landlord / Owner') }}</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500 font-mono text-[11px]">owner@studenthousing.com</span>
            </button>
            <button type="button" onclick="fillForm('admin@studenthousing.com', 'AdminPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    <span>{{ __('Administrator') }}</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500 font-mono text-[11px]">admin@studenthousing.com</span>
            </button>
        </div>
    </div>
</div>

<script>
    function fillForm(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
