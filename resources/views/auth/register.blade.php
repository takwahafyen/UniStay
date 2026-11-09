@extends('layouts.app')

@section('title', 'Inscription - UniStay')

@section('content')
<div class="max-w-md mx-auto my-10 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 text-xl border border-indigo-100 dark:border-indigo-800/50">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Créer un compte</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rejoignez UniStay en tant que Bailleur ou Étudiant</p>
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

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Role Choice: Owner or Student -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Je suis un :</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="owner" {{ old('role', 'owner') === 'owner' ? 'checked' : '' }} class="peer sr-only">
                    <div class="p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 dark:peer-checked:bg-indigo-950/40 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 transition">
                        <i class="fa-solid fa-house-chimney block text-lg mb-1"></i>
                        <span class="text-xs font-bold">Bailleur / Propriétaire</span>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="student" {{ old('role') === 'student' ? 'checked' : '' }} class="peer sr-only">
                    <div class="p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 dark:peer-checked:bg-indigo-950/40 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 transition">
                        <i class="fa-solid fa-graduation-cap block text-lg mb-1"></i>
                        <span class="text-xs font-bold">Étudiant (Locataire)</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nom complet</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                placeholder="Ex: Sarah Martin"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Adresse Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                placeholder="sarah@example.com"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
        </div>

        <div>
            <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Téléphone (optionnel)</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                placeholder="+33 6 12 34 56 78"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required
                    placeholder="Min. 8 car."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirmer</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    placeholder="Min. 8 car."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
            </div>
        </div>

        <button type="submit" class="w-full mt-2 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-check"></i>
            <span>Créer mon compte</span>
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
        Vous avez déjà un compte ? 
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Connectez-vous</a>
    </div>
</div>
@endsection
