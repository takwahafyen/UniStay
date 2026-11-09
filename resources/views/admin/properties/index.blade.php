@extends('layouts.app')

@section('title', 'Gestion des Annonces - Admin UniStay')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a> ➔ <span>Annonces</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Gestion des Annonces</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Modérez les annonces déposées par les propriétaires (House Owners).</p>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.properties.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Toutes</a>
        <a href="{{ route('admin.properties.index', ['status' => 'available']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'available' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Disponibles</a>
        <a href="{{ route('admin.properties.index', ['status' => 'rented']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'rented' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Louées / Inactives</a>
    </div>

    <form method="GET" action="{{ route('admin.properties.index') }}" class="flex items-center gap-2">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher titre, ville..."
            class="px-3 py-1.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white outline-none w-56">
        <button type="submit" class="px-3 py-1.5 bg-slate-800 dark:bg-slate-700 text-white text-xs font-semibold rounded-lg">Filtrer</button>
    </form>
</div>

<!-- Properties Table -->
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 uppercase font-semibold text-slate-400">
                <tr>
                    <th class="px-5 py-3.5">Logement</th>
                    <th class="px-5 py-3.5">Propriétaire (Bailleur)</th>
                    <th class="px-5 py-3.5">Localisation</th>
                    <th class="px-5 py-3.5">Loyer</th>
                    <th class="px-5 py-3.5">Disponibilité</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($properties as $property)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5">
                            <div>
                                <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 line-clamp-1">
                                    {{ $property->title }} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-400"></i>
                                </a>
                                <p class="text-slate-400 text-[11px] uppercase mt-0.5">{{ $property->type }} • {{ $property->surface_sqm }} m² • {{ $property->rooms_count }} pce(s)</p>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $property->owner->name }}</p>
                            <p class="text-slate-400 font-mono text-[11px]">{{ $property->owner->email }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="flex items-center gap-1 text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-location-dot text-slate-400"></i> {{ $property->city }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                            {{ number_format($property->price_monthly, 0) }} €/m
                        </td>
                        <td class="px-5 py-3.5">
                            @if($property->is_available)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-semibold" title="Suite à un booking ou action du bailleur">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Loué / Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Toggle Availability Switch -->
                                <form action="{{ route('admin.properties.toggle-availability', $property->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="{{ $property->is_available ? 'Rendre inactif / Loué' : 'Rendre disponible' }}"
                                        class="px-2 py-1 text-xs rounded-lg border {{ $property->is_available ? 'border-amber-300 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40' : 'border-emerald-300 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }} font-semibold">
                                        {{ $property->is_available ? 'Rendre inactif' : 'Rendre actif' }}
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.properties.delete', $property->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Confirmez-vous la suppression définitive de cette annonce ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer l'annonce" class="p-1.5 rounded-lg border border-rose-200 dark:border-rose-900 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Aucune annonce trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($properties->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $properties->links() }}
        </div>
    @endif
</div>
@endsection
