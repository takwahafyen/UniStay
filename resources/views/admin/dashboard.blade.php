@extends('layouts.app')

@section('title', 'Administration Générale - UniStay')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <span class="p-2 rounded-xl bg-indigo-600 text-white text-xl"><i class="fa-solid fa-gauge"></i></span>
            <span>Tableau de Bord Administrateur</span>
        </h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Supervision globale des utilisateurs, des annonces et des réservations de la plateforme.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
            <i class="fa-solid fa-user-plus"></i> Créer un utilisateur
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Utilisateurs</span>
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $stats['total_users'] }}</p>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
            <span><strong class="text-slate-700 dark:text-slate-200">{{ $stats['students_count'] }}</strong> étudiants</span> • 
            <span><strong class="text-slate-700 dark:text-slate-200">{{ $stats['owners_count'] }}</strong> bailleurs</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Annonces Publiées</span>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <i class="fa-solid fa-house-chimney text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $stats['total_properties'] }}</p>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
            <span class="text-emerald-600 dark:text-emerald-400">● {{ $stats['available_properties'] }} disponibles</span> • 
            <span class="text-amber-600 dark:text-amber-400">● {{ $stats['rented_properties'] }} louées / inactives</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Réservations</span>
            <div class="w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $stats['total_bookings'] }}</p>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
            <span><strong class="text-amber-600 dark:text-amber-400">{{ $stats['pending_bookings'] }}</strong> en attente</span> • 
            <span><strong class="text-emerald-600 dark:text-emerald-400">{{ $stats['confirmed_bookings'] }}</strong> confirmées</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Volume Financier</span>
            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <i class="fa-solid fa-euro-sign text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($stats['total_revenue'], 0) }} €</p>
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Réservations confirmées</p>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-500 text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2 transition shadow-sm">
        <i class="fa-solid fa-users-gear text-indigo-600"></i> Gérer les Utilisateurs ({{ $stats['total_users'] }})
    </a>
    <a href="{{ route('admin.properties.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-500 text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2 transition shadow-sm">
        <i class="fa-solid fa-house-chimney text-emerald-600"></i> Gérer les Annonces ({{ $stats['total_properties'] }})
    </a>
    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-500 text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2 transition shadow-sm">
        <i class="fa-solid fa-calendar-days text-violet-600"></i> Superviser les Bookings ({{ $stats['total_bookings'] }})
    </a>
</div>

<!-- Recent Tables Split -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Bookings -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i> Dernières Réservations
            </h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Tout voir</a>
        </div>
        <div class="space-y-3">
            @forelse($latestBookings as $b)
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $b->reference }} • {{ $b->property->title }}</p>
                        <p class="text-slate-500 dark:text-slate-400 mt-0.5">Par {{ $b->student->name }} ({{ $b->student->email }})</p>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-slate-900 dark:text-white">{{ number_format($b->total_amount, 0) }} €</span>
                        <div class="mt-1">
                            @if($b->status === 'confirmed')
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-semibold">Confirmée</span>
                            @elseif($b->status === 'pending')
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-semibold">En attente</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold">{{ $b->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-6">Aucune réservation pour le moment.</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Properties -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-house text-emerald-600"></i> Dernières Annonces Déposées
            </h2>
            <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Tout voir</a>
        </div>
        <div class="space-y-3">
            @forelse($latestProperties as $p)
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $p->title }}</p>
                        <p class="text-slate-500 dark:text-slate-400 mt-0.5">Bailleur : {{ $p->owner->name }} • {{ $p->city }}</p>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-slate-900 dark:text-white">{{ number_format($p->price_monthly, 0) }} €/m</span>
                        <div class="mt-1">
                            @if($p->is_available)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-semibold">Disponible</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-semibold">Loué / Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-6">Aucune annonce.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
