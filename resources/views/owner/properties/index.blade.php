@extends('layouts.app')

@section('title', 'Mes Logements - Espace Bailleur UniStay')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-indigo-600 text-white text-lg"><i class="fa-solid fa-house-chimney"></i></span>
            <span>Mes Logements en Location</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gérez vos annonces et réactivez vos logements en 1 clic après chaque location.</p>
    </div>
    <div>
        <a href="{{ route('owner.properties.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Publier une nouvelle annonce</span>
        </a>
    </div>
</div>

<!-- Highlight Info Banner -->
<div class="mb-6 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800/60 text-indigo-900 dark:text-indigo-200 text-xs flex items-center gap-3">
    <i class="fa-solid fa-bolt text-indigo-600 dark:text-indigo-400 text-xl"></i>
    <div>
        <p class="font-bold">Gain de temps automatique :</p>
        <p class="text-indigo-700 dark:text-indigo-300">Lorsqu'un étudiant réserve votre logement, l'annonce est automatiquement marquée <em>"Louée / Inactive"</em>. À la fin de la location, cliquez simplement sur <strong>"Remettre en location"</strong> pour relancer l'annonce sans devoir la recréer !</p>
    </div>
</div>

<!-- Properties Cards -->
<div class="space-y-4">
    @forelse($properties as $property)
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 transition">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-24 h-20 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                    <img src="{{ $property->images->first()?->path ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80' }}" 
                         alt="{{ $property->title }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            {{ $property->type }}
                        </span>
                        <span class="text-xs text-slate-400 flex items-center gap-1">
                            <i class="fa-solid fa-location-dot"></i> {{ $property->city }}
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mt-1">
                        {{ $property->title }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        <span class="font-bold text-slate-900 dark:text-white">{{ number_format($property->price_monthly, 0) }} €/mois</span> • 
                        {{ $property->surface_sqm }} m² • {{ $property->rooms_count }} pièce(s) • 
                        <strong>{{ $property->bookings_count }}</strong> réservation(s) reçue(s)
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2.5 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800">
                <!-- 1-Click Availability Toggle (Gain de temps) -->
                <form action="{{ route('owner.properties.toggle-availability', $property->id) }}" method="POST" class="inline">
                    @csrf
                    @if($property->is_available)
                        <button type="submit" title="Cliquez pour masquer ou marquer comme loué"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold border border-amber-300 dark:border-amber-800/80 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 hover:bg-amber-100 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-pause"></i>
                            <span>Marquer comme indisponible</span>
                        </button>
                    @else
                        <button type="submit" title="Remettre immédiatement en ligne sans refaire la saisie"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold border border-emerald-400 dark:border-emerald-700 bg-emerald-500 text-white hover:bg-emerald-600 transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-bolt"></i>
                            <span>⚡ Remettre en location (Rendre actif)</span>
                        </button>
                    @endif
                </form>

                <!-- Edit -->
                <a href="{{ route('owner.properties.edit', $property->id) }}" class="p-2 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-xs font-semibold" title="Modifier">
                    <i class="fa-solid fa-pen"></i>
                </a>

                <!-- View Live -->
                <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="p-2 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-xs font-semibold" title="Voir l'annonce publique">
                    <i class="fa-solid fa-eye"></i>
                </a>

                <!-- Delete -->
                <form action="{{ route('owner.properties.delete', $property->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement cette annonce ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 border border-rose-200 dark:border-rose-900 text-rose-600 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/40 transition text-xs" title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-12 text-center">
            <i class="fa-solid fa-house-chimney-crack text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Vous n'avez encore publié aucun logement</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Commencez dès maintenant en publiant votre premier studio ou appartement.</p>
            <a href="{{ route('owner.properties.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-plus-circle"></i> Publier une annonce
            </a>
        </div>
    @endforelse
</div>

@if($properties->hasPages())
    <div class="mt-6">
        {{ $properties->links() }}
    </div>
@endif
@endsection
