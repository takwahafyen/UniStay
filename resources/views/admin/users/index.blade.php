@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Admin UniStay')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a> ➔ <span>Utilisateurs</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Gestion des Utilisateurs</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Gérez les comptes des Bailleurs (House Owners) et des Étudiants.</p>
    </div>
    <div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Créer un utilisateur</span>
        </a>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ !request('role') ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Tous</a>
        <a href="{{ route('admin.users.index', ['role' => 'owner']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('role') === 'owner' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Bailleurs (Owners)</a>
        <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('role') === 'student' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Étudiants</a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher nom, email..."
            class="px-3 py-1.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white outline-none w-56">
        <button type="submit" class="px-3 py-1.5 bg-slate-800 dark:bg-slate-700 text-white text-xs font-semibold rounded-lg">Filtrer</button>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 uppercase font-semibold text-slate-400">
                <tr>
                    <th class="px-5 py-3.5">Utilisateur</th>
                    <th class="px-5 py-3.5">Rôle</th>
                    <th class="px-5 py-3.5">Statut Compte</th>
                    <th class="px-5 py-3.5">Activité</th>
                    <th class="px-5 py-3.5">Inscrit le</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-200">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-slate-400 font-mono text-[11px]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($user->role === 'owner')
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200 dark:border-blue-800/60 flex items-center gap-1 w-fit">
                                    <i class="fa-solid fa-house-chimney text-[10px]"></i> House Owner
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 font-semibold border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1 w-fit">
                                    <i class="fa-solid fa-graduation-cap text-[10px]"></i> Étudiant
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Désactivé
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($user->role === 'owner')
                                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->properties()->count() }}</span> annonces
                            @else
                                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->bookings()->count() }}</span> réservations
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-400">
                            {{ $user->created_at?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- 1-Click Toggle Active / Inactive -->
                                <form action="{{ route('admin.users.toggle-active', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="{{ $user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}"
                                        class="p-1.5 rounded-lg border {{ $user->is_active ? 'border-amber-300 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40' : 'border-emerald-300 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}">
                                        <i class="fa-solid {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" title="Modifier"
                                    class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Confirmez-vous la suppression définitive du compte de {{ $user->name }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer" class="p-1.5 rounded-lg border border-rose-200 dark:border-rose-900 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
