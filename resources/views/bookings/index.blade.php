@extends('layouts.app')

@section('title', 'Mes Réservations - StudentHousing')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mes Réservations</h1>
    <p class="mt-2 text-slate-600">Suivez l'état de vos demandes de logements et gérez vos séjours.</p>
</div>

@if(count($bookings) > 0)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Référence</th>
                        <th class="px-6 py-4">Logement</th>
                        <th class="px-6 py-4">Dates de séjour</th>
                        <th class="px-6 py-4">Montant total</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $booking->reference }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $booking->property->title }}</td>
                            <td class="px-6 py-4">
                                {{ $booking->check_in->format('d/m/Y') }} ➔ {{ $booking->check_out->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ number_format($booking->total_amount, 0) }} €</td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Confirmée</span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">En attente</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Annulée</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Refusée</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous l\'annulation de cette réservation ?');">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">Annuler</button>
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
    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
        <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 mb-3"></i>
        <h3 class="text-base font-semibold text-slate-800">Vous n'avez aucune réservation en cours</h3>
        <p class="text-sm text-slate-500 mt-1">Explorez les logements disponibles et effectuez votre première demande.</p>
        <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
            Trouver un logement
        </a>
    </div>
@endif
@endsection
