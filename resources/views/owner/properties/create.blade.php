@extends('layouts.app')

@section('title', 'Publier une Annonce - UniStay')

@section('content')
<div class="max-w-3xl mx-auto my-6">
    <div class="mb-4">
        <a href="{{ route('owner.properties.index') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Revenir à mes logements
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">Déposer une Annonce de Logement</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Remplissez les détails de votre bien. Une fois publié, les étudiants pourront le réserver en direct.</p>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.properties.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Titre & Type -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Titre de l'annonce *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex: Studio Rénové Lumineux Proche Faculté"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Type de bien *</label>
                    <select id="type" name="type" required class="w-full px-3 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="studio" {{ old('type') === 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="apartment" {{ old('type') === 'apartment' ? 'selected' : '' }}>Appartement</option>
                        <option value="room" {{ old('type') === 'room' ? 'selected' : '' }}>Chambre privée</option>
                        <option value="shared_flat" {{ old('type') === 'shared_flat' ? 'selected' : '' }}>Colocation</option>
                    </select>
                </div>
            </div>

            <!-- Prix & Caution -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price_monthly" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Loyer mensuel (€) *</label>
                    <input type="number" step="0.01" id="price_monthly" name="price_monthly" value="{{ old('price_monthly') }}" required placeholder="450.00"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="deposit" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Dépôt de garantie / Caution (€)</label>
                    <input type="number" step="0.01" id="deposit" name="deposit" value="{{ old('deposit', 0) }}" placeholder="450.00"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                        <input type="checkbox" name="charges_included" value="1" checked class="w-4 h-4 rounded text-indigo-600">
                        <span>Charges comprises</span>
                    </label>
                </div>
            </div>

            <!-- Surface, Pièces, Meublé -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="surface_sqm" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Surface (m²) *</label>
                    <input type="number" id="surface_sqm" name="surface_sqm" value="{{ old('surface_sqm', 25) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="rooms_count" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nombre de pièces *</label>
                    <input type="number" id="rooms_count" name="rooms_count" value="{{ old('rooms_count', 1) }}" min="1" max="10" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="bathrooms_count" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Salles de bain *</label>
                    <input type="number" id="bathrooms_count" name="bathrooms_count" value="{{ old('bathrooms_count', 1) }}" min="1" max="5" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
            </div>

            <!-- Adresse & Localisation -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ville *</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" required placeholder="Ex: Tunis, Paris..."
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="postal_code" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Code Postal *</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required placeholder="4000"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Adresse complète *</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" required placeholder="Ex: 14 Rue de la République"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Description détaillée du logement *</label>
                <textarea id="description" name="description" rows="4" required placeholder="Décrivez l'agencement, la luminosité, la proximité des transports et des universités..."
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">{{ old('description') }}</textarea>
            </div>

            <!-- Image URL -->
            <div>
                <label for="image_url" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Lien de la photo principale (URL Unsplash / Web)</label>
                <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267"
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <!-- Amenities -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Équipements inclus :</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($amenities as $amenity)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 text-xs cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="w-4 h-4 rounded text-indigo-600">
                            <span>{{ $amenity->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('owner.properties.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Annuler</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    Publier l'annonce maintenant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
