@extends('layouts.app')

@section('title', 'Modifier l\'Annonce - UniStay')

@section('content')
<div class="max-w-3xl mx-auto my-6">
    <div class="mb-4">
        <a href="{{ route('owner.properties.index') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Revenir à mes logements
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">Modifier l'Annonce</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Mise à jour des informations pour <strong class="text-indigo-600 dark:text-indigo-400">{{ $property->title }}</strong></p>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.properties.update', $property->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Titre & Type -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Titre de l'annonce *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $property->title) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Type de bien *</label>
                    <select id="type" name="type" required class="w-full px-3 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="studio" {{ old('type', $property->type) === 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="apartment" {{ old('type', $property->type) === 'apartment' ? 'selected' : '' }}>Appartement</option>
                        <option value="room" {{ old('type', $property->type) === 'room' ? 'selected' : '' }}>Chambre privée</option>
                        <option value="shared_flat" {{ old('type', $property->type) === 'shared_flat' ? 'selected' : '' }}>Colocation</option>
                    </select>
                </div>
            </div>

            <!-- Prix & Caution -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price_monthly" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Loyer mensuel (€) *</label>
                    <input type="number" step="0.01" id="price_monthly" name="price_monthly" value="{{ old('price_monthly', $property->price_monthly) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="deposit" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Caution (€)</label>
                    <input type="number" step="0.01" id="deposit" name="deposit" value="{{ old('deposit', $property->deposit) }}"
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                        <input type="checkbox" name="charges_included" value="1" {{ old('charges_included', $property->charges_included) ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600">
                        <span>Charges comprises</span>
                    </label>
                </div>
            </div>

            <!-- Surface, Pièces -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="surface_sqm" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Surface (m²) *</label>
                    <input type="number" id="surface_sqm" name="surface_sqm" value="{{ old('surface_sqm', $property->surface_sqm) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="rooms_count" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pièces *</label>
                    <input type="number" id="rooms_count" name="rooms_count" value="{{ old('rooms_count', $property->rooms_count) }}" min="1" max="10" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="bathrooms_count" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Salles de bain *</label>
                    <input type="number" id="bathrooms_count" name="bathrooms_count" value="{{ old('bathrooms_count', $property->bathrooms_count) }}" min="1" max="5" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
            </div>

            <!-- Ville & Adresse -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ville *</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $property->city) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="postal_code" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Code Postal *</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $property->postal_code) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Adresse *</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $property->address) }}" required
                        class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Description *</label>
                <textarea id="description" name="description" rows="4" required
                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-600">{{ old('description', $property->description) }}</textarea>
            </div>

            <!-- Amenities -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Équipements inclus :</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($amenities as $amenity)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 text-xs cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span>{{ $amenity->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('owner.properties.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Annuler</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    Mettre à jour l'annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
