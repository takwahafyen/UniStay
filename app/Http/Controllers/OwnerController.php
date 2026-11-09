<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    /**
     * List all properties owned by current House Owner
     */
    public function properties(): View
    {
        $owner = Auth::user();

        $properties = Property::with(['images', 'amenities'])
            ->where('owner_id', $owner->id)
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        return view('owner.properties.index', compact('properties'));
    }

    /**
     * Show form to publish a new property
     */
    public function createProperty(): View
    {
        $amenities = Amenity::all();

        return view('owner.properties.create', compact('amenities'));
    }

    /**
     * Store a newly created property
     */
    public function storeProperty(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'type' => 'required|in:studio,apartment,room,shared_flat',
            'price_monthly' => 'required|numeric|min:50',
            'deposit' => 'nullable|numeric|min:0',
            'charges_included' => 'nullable|boolean',
            'surface_sqm' => 'required|integer|min:5|max:500',
            'rooms_count' => 'required|integer|min:1|max:10',
            'bathrooms_count' => 'required|integer|min:1|max:5',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'description' => 'required|string|min:20',
            'is_furnished' => 'nullable|boolean',
            'image_url' => 'nullable|url',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        $slug = Str::slug($validated['title']).'-'.Str::lower(Str::random(6));

        $property = Property::create([
            'owner_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_monthly' => $validated['price_monthly'],
            'deposit' => $validated['deposit'] ?? 0,
            'charges_included' => $request->has('charges_included'),
            'surface_sqm' => $validated['surface_sqm'],
            'rooms_count' => $validated['rooms_count'],
            'bathrooms_count' => $validated['bathrooms_count'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'is_furnished' => $request->has('is_furnished'),
            'is_available' => true,
            'status' => 'published',
        ]);

        if (! empty($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        }

        $imagePath = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80';
        PropertyImage::create([
            'property_id' => $property->id,
            'path' => $imagePath,
            'is_primary' => true,
            'order' => 1,
        ]);

        return redirect()->route('owner.properties.index')
            ->with('success', "Votre annonce \"{$property->title}\" a été publiée avec succès.");
    }

    /**
     * Show form to edit existing property
     */
    public function editProperty(Property $property): View
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403);
        }

        $amenities = Amenity::all();
        $selectedAmenities = $property->amenities->pluck('id')->toArray();

        return view('owner.properties.edit', compact('property', 'amenities', 'selectedAmenities'));
    }

    /**
     * Update existing property
     */
    public function updateProperty(Request $request, Property $property): RedirectResponse
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'type' => 'required|in:studio,apartment,room,shared_flat',
            'price_monthly' => 'required|numeric|min:50',
            'deposit' => 'nullable|numeric|min:0',
            'charges_included' => 'nullable|boolean',
            'surface_sqm' => 'required|integer|min:5|max:500',
            'rooms_count' => 'required|integer|min:1|max:10',
            'bathrooms_count' => 'required|integer|min:1|max:5',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'description' => 'required|string|min:20',
            'is_furnished' => 'nullable|boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        $property->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_monthly' => $validated['price_monthly'],
            'deposit' => $validated['deposit'] ?? 0,
            'charges_included' => $request->has('charges_included'),
            'surface_sqm' => $validated['surface_sqm'],
            'rooms_count' => $validated['rooms_count'],
            'bathrooms_count' => $validated['bathrooms_count'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'is_furnished' => $request->has('is_furnished'),
        ]);

        if (isset($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        }

        return redirect()->route('owner.properties.index')
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Toggle property active / inactive in 1 click
     * (E.g. after a tenant leaves, owner re-activates it without re-creating!)
     */
    public function toggleAvailability(Property $property): RedirectResponse
    {
        $user = Auth::user();

        if ($property->owner_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $property->update(['is_available' => ! $property->is_available]);
        $statusLabel = $property->is_available ? 'remise en ligne (disponible)' : 'désactivée (louée / masquée)';

        return back()->with('success', "L'annonce \"{$property->title}\" est maintenant {$statusLabel}.");
    }

    /**
     * Delete owner's property
     */
    public function deleteProperty(Property $property): RedirectResponse
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403);
        }

        $title = $property->title;
        $property->delete();

        return back()->with('success', "L'annonce \"{$title}\" a été supprimée.");
    }

    /**
     * View bookings made on owner's properties
     */
    public function bookings(): View
    {
        $owner = Auth::user();

        $bookings = Booking::with(['student', 'property'])
            ->whereHas('property', function ($query) use ($owner) {
                $query->where('owner_id', $owner->id);
            })
            ->latest()
            ->paginate(12);

        return view('owner.bookings.index', compact('bookings'));
    }

    /**
     * Owner confirms or declines a booking
     */
    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->property->owner_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,declined',
        ]);

        $booking->update([
            'status' => $validated['status'],
            'confirmed_at' => $validated['status'] === 'confirmed' ? now() : null,
        ]);

        // When confirmed, property is marked unavailable; if declined, remains available
        if ($validated['status'] === 'confirmed') {
            $booking->property->update(['is_available' => false]);
        }

        $label = $validated['status'] === 'confirmed' ? 'confirmée' : 'refusée';

        return back()->with('success', "La réservation {$booking->reference} a été {$label}.");
    }
}
