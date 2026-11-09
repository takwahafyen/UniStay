<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Admin Overview Dashboard
     */
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'students_count' => User::where('role', 'student')->count(),
            'owners_count' => User::where('role', 'owner')->count(),
            'active_users' => User::where('is_active', true)->where('role', '!=', 'admin')->count(),
            'total_properties' => Property::count(),
            'available_properties' => Property::where('is_available', true)->count(),
            'rented_properties' => Property::where('is_available', false)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed'])->sum('total_amount'),
        ];

        $latestBookings = Booking::with(['property.owner', 'student'])
            ->latest()
            ->take(5)
            ->get();

        $latestProperties = Property::with('owner')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestBookings', 'latestProperties'));
    }

    /**
     * User Management (Students & House Owners)
     */
    public function users(Request $request): View
    {
        $query = User::where('role', '!=', 'admin')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create new user (Student or House Owner)
     */
    public function createUser(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store new user created by Admin
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'role' => 'required|in:student,owner',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:30',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->has('is_active'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Le compte {$validated['role']} pour {$validated['name']} a été créé.");
    }

    /**
     * Edit existing user
     */
    public function editUser(User $user): View
    {
        if ($user->isAdmin()) {
            abort(403, 'Impossible de modifier l\'administrateur principal.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update existing user
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:student,owner',
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} mis à jour avec succès.");
    }

    /**
     * Toggle User active / inactive status
     */
    public function toggleUserActive(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver ce compte administrateur.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Le compte de {$user->name} a été {$status}.");
    }

    /**
     * Delete User
     */
    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->id === Auth::id()) {
            return back()->with('error', 'Impossible de supprimer ce compte administrateur.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Le compte de {$name} et ses données associées ont été supprimés.");
    }

    /**
     * Property Listings Management
     */
    public function properties(Request $request): View
    {
        $query = Property::with('owner')->latest();

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true);
            } elseif ($request->status === 'rented') {
                $query->where('is_available', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $properties = $query->paginate(15)->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Toggle Property availability (Admin override)
     */
    public function togglePropertyAvailability(Property $property): RedirectResponse
    {
        $property->update(['is_available' => ! $property->is_available]);
        $state = $property->is_available ? 'active (disponible)' : 'inactive (louée / masquée)';

        return back()->with('success', "L'annonce \"{$property->title}\" est maintenant {$state}.");
    }

    /**
     * Delete Property (Admin moderation)
     */
    public function deleteProperty(Property $property): RedirectResponse
    {
        $title = $property->title;
        $property->delete();

        return back()->with('success', "L'annonce \"{$title}\" a été définitivement supprimée.");
    }

    /**
     * Bookings Supervision
     */
    public function bookings(Request $request): View
    {
        $query = Booking::with(['property.owner', 'student'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Update Booking status (Confirm, Cancel, Decline)
     */
    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,declined',
        ]);

        $booking->update([
            'status' => $validated['status'],
            'confirmed_at' => $validated['status'] === 'confirmed' ? now() : $booking->confirmed_at,
            'cancelled_at' => $validated['status'] === 'cancelled' ? now() : $booking->cancelled_at,
        ]);

        // When booking is confirmed, property is marked rented/inactive
        if ($validated['status'] === 'confirmed') {
            $booking->property->update(['is_available' => false]);
        } elseif (in_array($validated['status'], ['cancelled', 'declined'])) {
            $booking->property->update(['is_available' => true]);
        }

        return back()->with('success', "Réservation {$booking->reference} mise à jour (statut : {$validated['status']}).");
    }
}
