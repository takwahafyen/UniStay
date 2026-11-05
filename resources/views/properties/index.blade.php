@extends('layouts.app')

@section('title', 'Trouver un logement étudiant - StudentHousing')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Trouvez votre futur logement étudiant</h1>
    <p class="mt-2 text-slate-600">Recherche instantanée avec moteur de recherche Elasticsearch, proximité des universités et filtres avancés.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:col-span-1">
        <form action="{{ route('properties.index') }}" method="GET" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Recherche libre</label>
                <div class="relative">
                    <input type="text" name="query" value="{{ $filters['query'] ?? '' }}" placeholder="Ex: Studio centre-ville, Sousse..." 
                           class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Ville</label>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="Ex: Tunis, Sousse..." 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Type de logement</label>
                <select name="type" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tous les types</option>
                    <option value="studio" {{ ($filters['type'] ?? '') === 'studio' ? 'selected' : '' }}>Studio</option>
                    <option value="apartment" {{ ($filters['type'] ?? '') === 'apartment' ? 'selected' : '' }}>Appartement</option>
                    <option value="room" {{ ($filters['type'] ?? '') === 'room' ? 'selected' : '' }}>Chambre privée</option>
                    <option value="shared_flat" {{ ($filters['type'] ?? '') === 'shared_flat' ? 'selected' : '' }}>Colocation</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Prix min</label>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="0 €" 
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Prix max</label>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="800 €" 
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Filtrer
                </button>
                <a href="{{ route('properties.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition text-center">
                    Reset
                </a>
            </div>
        </form>

        <!-- Elasticsearch Facet Aggregation Box -->
        @if(!empty($facets['avg_price']['value']))
            <div class="mt-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-xs">
                <p class="font-semibold mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-simple"></i> Statistiques Elasticsearch
                </p>
                <p>Prix moyen : <span class="font-bold">{{ number_format($facets['avg_price']['value'], 0) }} €/mois</span></p>
                <p class="mt-0.5">Fourchette : {{ number_format($facets['min_price']['value'] ?? 0, 0) }} € - {{ number_format($facets['max_price']['value'] ?? 0, 0) }} €</p>
            </div>
        @endif
    </aside>

    <!-- Property Cards Grid -->
    <section class="lg:col-span-3">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-500">
                <span class="font-bold text-slate-800">{{ $total }}</span> logements trouvés
            </p>
        </div>

        @if(count($properties) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                    @php
                        $isObject = is_object($property);
                        $title = $isObject ? $property->title : $property['title'];
                        $slug = $isObject ? $property->slug : ($property['slug'] ?? $property['id']);
                        $price = $isObject ? $property->price_monthly : $property['price_monthly'];
                        $city = $isObject ? $property->city : $property['city'];
                        $surface = $isObject ? $property->surface_sqm : $property['surface_sqm'];
                        $rooms = $isObject ? $property->rooms_count : $property['rooms_count'];
                        $type = $isObject ? $property->type : $property['type'];
                        $image = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80';
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full text-xs font-semibold text-slate-800 uppercase tracking-wide">
                                {{ $type }}
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="text-xl font-bold text-slate-900">{{ number_format($price, 0) }} € <span class="text-xs font-normal text-slate-500">/mois</span></span>
                                    <span class="text-xs text-slate-500 flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $city }}</span>
                                </div>
                                <h3 class="mt-2 text-sm font-semibold text-slate-800 line-clamp-2 leading-snug">
                                    {{ $title }}
                                </h3>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span><i class="fa-solid fa-ruler-combined"></i> {{ $surface }} m²</span>
                                <span><i class="fa-solid fa-bed"></i> {{ $rooms }} pce(s)</span>
                                <a href="{{ route('properties.show', $slug) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                    Voir <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                <i class="fa-solid fa-house-circle-xmark text-4xl text-slate-300 mb-3"></i>
                <h3 class="text-base font-semibold text-slate-800">Aucun logement ne correspond à votre recherche</h3>
                <p class="text-sm text-slate-500 mt-1">Essayez d'élargir vos filtres ou de changer de ville.</p>
                <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-4 py-2 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    Réinitialiser les filtres
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
