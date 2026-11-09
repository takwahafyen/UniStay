@extends('layouts.app')

@section('title', 'Connexion - StudentHousing')

@section('content')
<div class="max-w-md mx-auto my-12 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 text-xl border border-indigo-100 dark:border-indigo-800/50">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Espace Connexion</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Connectez-vous pour gérer vos réservations et vos annonces</p>
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
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Adresse Email</label>
            <div class="relative">
                <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 dark:text-slate-500"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="exemple@studenthousing.com"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm outline-none transition">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Mot de passe</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 dark:text-slate-500"></i>
                <input type="password" id="password" name="password" required
                    placeholder="••••••••••••"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm outline-none transition">
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-400">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:ring-indigo-500">
                <span>Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>Se connecter</span>
        </button>
    </form>

    <!-- Quick Fill for Demo Reviewers -->
    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-center mb-3">Comptes Démo (Remplissage rapide)</p>
        <div class="grid grid-cols-1 gap-2">
            <button type="button" onclick="fillForm('student@studenthousing.com', 'StudentPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Étudiant Test</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500">student@studenthousing.com</span>
            </button>
            <button type="button" onclick="fillForm('owner@studenthousing.com', 'OwnerPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>Bailleur / Propriétaire</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500">owner@studenthousing.com</span>
            </button>
            <button type="button" onclick="fillForm('admin@studenthousing.com', 'AdminPass123!')" 
                class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center justify-between transition">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    <span>Administrateur</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500">admin@studenthousing.com</span>
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
