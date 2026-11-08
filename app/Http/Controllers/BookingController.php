<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    /**
     * Display current user's bookings (Anti-BOLA / IDOR)
     */
    public function index(): View
    {
        $user = Auth::user();

        $bookings = Booking::with(['property.images', 'property.owner'])
            ->where('student_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Store a new booking
     */
    public function store(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests_count' => 'required|integer|min:1|max:6',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                property: $property,
                student: Auth::user(),
                checkIn: $validated['check_in'],
                checkOut: $validated['check_out'],
                guests: (int) $validated['guests_count'],
                notes: $validated['notes'] ?? null
            );

            return redirect()->route('bookings.index')->with('success', "Votre réservation ({$booking->reference}) a été créée avec succès.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a booking (Safe ownership check)
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        try {
            $this->bookingService->cancelBooking($booking, Auth::user());

            return back()->with('success', "La réservation {$booking->reference} a été annulée.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
