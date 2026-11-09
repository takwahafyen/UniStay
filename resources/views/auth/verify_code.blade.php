@extends('layouts.app')

@section('title', 'Validation Code Email - UniStay')

@section('content')
<div class="max-w-md mx-auto my-12 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 text-xl border border-indigo-100 dark:border-indigo-800/50">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Validation par Code</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Entrez le code à 6 chiffres envoyé à <strong class="text-indigo-600 dark:text-indigo-400">{{ $email }}</strong>
        </p>
    </div>

    @if(session('info'))
        <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-300 text-xs font-mono">
            <i class="fa-solid fa-circle-info mr-1"></i> {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
            {{ $errors->first('code') }}
        </div>
    @endif

    <form action="{{ route('login.otp.verify') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label for="code" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 text-center">Code de sécurité à 6 chiffres</label>
            <input type="text" id="code" name="code" maxlength="6" autofocus required
                placeholder="123456"
                class="w-full text-center text-3xl font-mono tracking-widest py-3 rounded-xl bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-600 outline-none">
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-shield-check"></i>
            <span>Vérifier et me connecter</span>
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
            <i class="fa-solid fa-arrow-left"></i> Revenir à la connexion classique
        </a>
    </div>
</div>
@endsection
