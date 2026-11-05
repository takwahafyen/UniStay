<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Property;
use App\Services\Elasticsearch\PropertySearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(
        protected PropertySearchService $searchService
    ) {}

    /**
     * Display list of properties with Elasticsearch search and filters
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'type' => 'nullable|in:studio,apartment,room,shared_flat',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'rooms' => 'nullable|integer|min:1|max:10',
            'furnished' => 'nullable|boolean',
            'lat' => 'nullable|numeric|between:-90,90',
            'lon' => 'nullable|numeric|between:-180,180',
            'radius_km' => 'nullable|numeric|min:1|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $searchData = $this->searchService->search($validated, (int) ($validated['page'] ?? 1), 12);

        // Fallback to MySQL if Elasticsearch returns empty during local dev setup
        if ($searchData['total'] === 0 && empty($validated['query'])) {
            $properties = Property::with(['images', 'owner'])
                ->where('status', 'published')
                ->where('is_available', true)
                ->latest()
                ->paginate(12);
        } else {
            $properties = $searchData['results'];
        }

        $amenities = Amenity::all();

        return view('properties.index', [
            'properties' => $properties,
            'facets' => $searchData['facets'],
            'total' => $searchData['total'],
            'filters' => $validated,
            'amenities' => $amenities,
        ]);
    }

    /**
     * Show single property details
     */
    public function show(string $slug): View
    {
        $property = Property::with(['images', 'amenities', 'owner', 'reviews.student'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('properties.show', [
            'property' => $property,
        ]);
    }
}
