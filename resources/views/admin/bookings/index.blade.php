@extends('layouts.app')

@section('title', 'Supervision des Réservations - Admin UniStay')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a> ➔ <span>Réservations</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Supervision des Réservations</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Consultez et régulez l'ensemble des réservations passées entre étudiants et bailleurs.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 flex items-center gap-2">
    <a href="{{ route('admin.bookings.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Toutes</a>
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'pending' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">En attente</a>
    <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'confirmed' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Confirmées</a>
    <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'cancelled' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">Annulées</a>
</div>

<!-- Bookings Table -->
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 uppercase font-semibold text-slate-400">
                <tr>
                    <th class="px-5 py-3.5">Réf & Logement</th>
                    <th class="px-5 py-3.5">Bailleur (Owner)</th>
                    <th class="px-5 py-3.5">Étudiant (Locataire)</th>
                    <th class="px-5 py-3.5">Période</th>
                    <th class="px-5 py-3.5">Montant</th>
                    <th class="px-5 py-3.5">Statut</th>
                    <th class="px-5 py-3.5 text-right">Modération</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $booking->reference }}</span>
                            <p class="font-medium text-slate-700 dark:text-slate-300 line-clamp-1 mt-0.5">{{ $booking->property->title }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $booking->property->owner->name }}</p>
                            <p class="text-slate-400 font-mono text-[11px]">{{ $booking->property->owner->email }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $booking->student->name }}</p>
                            <p class="text-slate-400 font-mono text-[11px]">{{ $booking->student->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300">
                            {{ $booking->check_in->format('d/m/Y') }} ➔ {{ $booking->check_out->format('d/m/Y') }}
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
                            <div class="flex items-center justify-end gap-1.5">
                                @if($booking->status === 'pending')
                                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" title="Confirmer la réservation (rend l'annonce inactive)" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold">Confirmer</button>
                                    </form>
                                @endif

                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" title="Annuler la réservation" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold">Annuler</button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Aucune réservation enregistrée.</td>
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
