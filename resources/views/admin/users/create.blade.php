@extends('layouts.app')

@section('title', 'Créer un Utilisateur - Admin UniStay')

@section('content')
<div class="max-w-xl mx-auto my-6">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Retour à la liste des utilisateurs
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">Créer un Nouvel Utilisateur</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Créez un compte manuellement pour un Étudiant ou un Bailleur (House Owner).</p>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Type de rôle *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="owner" {{ old('role') === 'owner' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 dark:peer-checked:bg-indigo-950/40 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 transition text-xs font-bold">
                            <i class="fa-solid fa-house-chimney mr-1"></i> House Owner (Bailleur)
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="student" {{ old('role', 'student') === 'student' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 dark:peer-checked:bg-indigo-950/40 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 transition text-xs font-bold">
                            <i class="fa-solid fa-graduation-cap mr-1"></i> Étudiant (Locataire)
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nom complet *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Adresse Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mot de passe temporaire *</label>
                <input type="password" id="password" name="password" required placeholder="Min. 8 caractères"
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-indigo-600">
                    <span>Activer le compte immédiatement</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Annuler</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm">Créer l'utilisateur</button>
            </div>
        </form>
    </div>
</div>
@endsection
