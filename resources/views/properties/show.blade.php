@extends('layouts.app')

@section('title', $property->title . ' - StudentHousing')

@section('content')
<div class="mb-6">
    <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition">
        <i class="fa-solid fa-arrow-left text-xs"></i> Retour aux résultats
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Property Details (Left 2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Image -->
        <div class="h-96 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-800">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80" 
                 alt="{{ $property->title }}" class="w-full h-full object-cover">
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">
                    <span>{{ $property->type }}</span> • <span>{{ $property->city }} ({{ $property->postal_code }})</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $property->title }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot"></i> {{ $property->address }}, {{ $property->postal_code }} {{ $property->city }}
                </p>
            </div>

            <!-- Key Features Badges -->
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 py-4 border-y border-slate-100 dark:border-slate-800 text-center">
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Surface</p>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $property->surface_sqm }} m²</p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pièces</p>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $property->rooms_count }}</p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Salles d'eau</p>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $property->bathrooms_count }}</p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl col-span-3 sm:col-span-1">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Meublé</p>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $property->is_furnished ? 'Oui' : 'Non' }}</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Description du logement</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-sm whitespace-pre-line">{{ $property->description }}</p>
            </div>

            <!-- Amenities -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">Équipements & Prestations</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($property->amenities as $amenity)
                        <div class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-300 bg-slate-50/50 dark:bg-slate-800/40">
                            <i class="fa-solid fa-check text-emerald-500"></i>
                            <span>{{ $amenity->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Sidebar (Right 1 column) -->
    <div class="lg:col-span-1">
        <div class="sticky top-24 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
            <div class="flex items-baseline justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($property->price_monthly, 0) }} €</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">/ mois</span>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50 font-semibold">
                    {{ $property->charges_included ? 'Charges comprises' : '+ charges' }}
                </span>
            </div>

            <form action="{{ route('bookings.store', $property->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Date d'arrivée souhaitée *</label>
                    <input type="date" name="check_in" min="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Date de départ prévisionnelle *</label>
                    <input type="date" name="check_out" min="{{ date('Y-m-d', strtotime('+1 month')) }}" required
                           class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nombre d'étudiants *</label>
                    <select name="guests_count" class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="1">1 étudiant</option>
                        <option value="2">2 étudiants</option>
                        <option value="3">3 étudiants</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Message pour le propriétaire (optionnel)</label>
                    <textarea name="notes" rows="3" placeholder="Présentez-vous brièvement (école, durée d'études)..." 
                              class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-indigo-100 dark:shadow-none transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Envoyer la demande de réservation</span>
                </button>
            </form>

            <div class="text-center pt-2">
                <p class="text-xs text-slate-400 dark:text-slate-500">Aucun débit immédiat. Le bailleur validera votre dossier.</p>
            </div>
        </div>
    </div>
</div>
@endsection
