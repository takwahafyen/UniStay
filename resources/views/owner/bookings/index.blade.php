@extends('layouts.app')

@section('title', 'Réservations Reçues - Espace Bailleur UniStay')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-indigo-600 text-white text-lg"><i class="fa-solid fa-calendar-check"></i></span>
            <span>Réservations Reçues</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Consultez les demandes des étudiants sur vos logements et gérez vos confirmations.</p>
    </div>
</div>

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 uppercase font-semibold text-slate-400">
                <tr>
                    <th class="px-5 py-3.5">Réf & Logement</th>
                    <th class="px-5 py-3.5">Étudiant (Locataire)</th>
                    <th class="px-5 py-3.5">Dates du séjour</th>
                    <th class="px-5 py-3.5">Occupants</th>
                    <th class="px-5 py-3.5">Montant Total</th>
                    <th class="px-5 py-3.5">Statut</th>
                    <th class="px-5 py-3.5 text-right">Décision</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $booking->reference }}</span>
                            <p class="font-semibold text-slate-700 dark:text-slate-300 line-clamp-1 mt-0.5">{{ $booking->property->title }}</p>
                            @if($booking->notes)
                                <p class="text-[11px] text-slate-400 italic mt-0.5">Note : "{{ Str::limit($booking->notes, 40) }}"</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-slate-900 dark:text-white">{{ $booking->student->name }}</p>
                            <p class="text-slate-400 font-mono text-[11px]">{{ $booking->student->email }}</p>
                            @if($booking->student->phone)
                                <p class="text-slate-400 text-[11px]"><i class="fa-solid fa-phone text-[9px]"></i> {{ $booking->student->phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">
                            {{ $booking->check_in->format('d/m/Y') }} ➔ {{ $booking->check_out->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            {{ $booking->guests_count }} étudiant(s)
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                            {{ number_format($booking->total_amount, 0) }} €
                        </td>
                        <td class="px-5 py-3.5">
                            @if($booking->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-semibold">Confirmée</span>
                            @elseif($booking->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-semibold">En attente</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold">Annulée</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 font-semibold">Refusée</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($booking->status === 'pending')
                                <div class="flex items-center justify-end gap-1.5">
                                    <form action="{{ route('owner.bookings.status', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">Accepter</button>
                                    </form>
                                    <form action="{{ route('owner.bookings.status', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition">Refuser</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Aucune demande de réservation reçue pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
