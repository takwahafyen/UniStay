@extends('layouts.app')

@section('title', __('StudentHousing - Turnkey Student Housing'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ __('Find your future student accommodation') }}</h1>
    <p class="mt-2 text-slate-600 dark:text-slate-400">{{ __('Instant search with Elasticsearch engine, university proximity, and advanced filters.') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:col-span-1">
        <form action="{{ route('properties.index') }}" method="GET" class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('Free Search') }}</label>
                <div class="relative">
                    <input type="text" name="query" value="{{ $filters['query'] ?? '' }}" placeholder="{{ __('Ex: City center studio, Sousse...') }}" 
                           class="w-full ltr:pl-9 ltr:pr-3 rtl:pr-9 rtl:pl-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-magnifying-glass absolute ltr:left-3 rtl:right-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('City') }}</label>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="{{ __('Ex: Tunis, Sousse...') }}" 
                       class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('Housing Type') }}</label>
                <select name="type" class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">{{ __('All types') }}</option>
                    <option value="studio" {{ ($filters['type'] ?? '') === 'studio' ? 'selected' : '' }}>{{ __('Studio') }}</option>
                    <option value="apartment" {{ ($filters['type'] ?? '') === 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                    <option value="room" {{ ($filters['type'] ?? '') === 'room' ? 'selected' : '' }}>{{ __('Private room') }}</option>
                    <option value="shared_flat" {{ ($filters['type'] ?? '') === 'shared_flat' ? 'selected' : '' }}>{{ __('Shared flat') }}</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('Min price') }}</label>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="0 €" 
                           class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('Max price') }}</label>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="800 €" 
                           class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('properties.index') }}" class="py-2.5 px-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition text-center">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>

        <!-- Elasticsearch Facet Aggregation Box -->
        @if(!empty($facets['avg_price']['value']))
            <div class="mt-4 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-800/60 text-indigo-900 dark:text-indigo-200 text-xs">
                <p class="font-semibold mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-simple"></i> {{ __('Elasticsearch Statistics') }}
                </p>
                <p>{{ __('Average price:') }} <span class="font-bold">{{ number_format($facets['avg_price']['value'], 0) }} €{{ __('/month') }}</span></p>
                <p class="mt-0.5">{{ __('Range:') }} {{ number_format($facets['min_price']['value'] ?? 0, 0) }} € - {{ number_format($facets['max_price']['value'] ?? 0, 0) }} €</p>
            </div>
        @endif
    </aside>

    <!-- Property Cards Grid -->
    <section class="lg:col-span-3">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $total }}</span> {{ __('properties found') }}
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
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 transition flex flex-col group">
                        <div class="relative h-48 overflow-hidden bg-slate-100 dark:bg-slate-800">
                            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute top-3 ltr:left-3 rtl:right-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm px-2.5 py-1 rounded-full text-xs font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wide border border-slate-200/50 dark:border-slate-700/50">
                                {{ __($type) }}
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($price, 0) }} € <span class="text-xs font-normal text-slate-500 dark:text-slate-400">{{ __('/month') }}</span></span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $city }}</span>
                                </div>
                                <h3 class="mt-2 text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-2 leading-snug">
                                    {{ $title }}
                                </h3>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span><i class="fa-solid fa-ruler-combined"></i> {{ $surface }} m²</span>
                                <span><i class="fa-solid fa-bed"></i> {{ $rooms }} {{ __('rm(s)') }}</span>
                                <a href="{{ route('properties.show', $slug) }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 inline-flex items-center gap-1">
                                    <span>{{ __('View') }}</span> <i class="fa-solid fa-arrow-right rtl:rotate-180 text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-12 text-center">
                <i class="fa-solid fa-house-circle-xmark text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">{{ __('No housing matches your search') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Try broadening your filters or changing the city.') }}</p>
                <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-4 py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800/60 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition">
                    {{ __('Reset filters') }}
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
