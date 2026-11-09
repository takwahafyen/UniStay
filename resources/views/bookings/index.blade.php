@extends('layouts.app')

@section('title', 'Mes Réservations - StudentHousing')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Mes Réservations</h1>
    <p class="mt-2 text-slate-600 dark:text-slate-400">Suivez l'état de vos demandes de logements et gérez vos séjours.</p>
</div>

@if(count($bookings) > 0)
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Référence</th>
                        <th class="px-6 py-4">Logement</th>
                        <th class="px-6 py-4">Dates de séjour</th>
                        <th class="px-6 py-4">Montant total</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white">{{ $booking->reference }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-200">{{ $booking->property->title }}</td>
                            <td class="px-6 py-4">
                                {{ $booking->check_in->format('d/m/Y') }} ➔ {{ $booking->check_out->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ number_format($booking->total_amount, 0) }} €</td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">Confirmée</span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">En attente</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Annulée</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800/50">Refusée</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous l\'annulation de cette réservation ?');">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300">Annuler</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-12 text-center transition-colors">
        <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">Vous n'avez aucune réservation en cours</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Explorez les logements disponibles et effectuez votre première demande.</p>
        <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
            Trouver un logement
        </a>
    </div>
@endif
@endsection
