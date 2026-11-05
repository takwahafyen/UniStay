<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users (Admin, Owner, Student)
        $admin = User::firstOrCreate(
            ['email' => 'admin@studenthousing.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('AdminPass123!'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $owner = User::firstOrCreate(
            ['email' => 'owner@studenthousing.com'],
            [
                'name' => 'Bailleur Résidence',
                'password' => Hash::make('OwnerPass123!'),
                'role' => 'owner',
                'phone' => '+33 6 12 34 56 78',
                'is_active' => true,
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@studenthousing.com'],
            [
                'name' => 'Étudiant Test',
                'password' => Hash::make('StudentPass123!'),
                'role' => 'student',
                'phone' => '+33 6 98 76 54 32',
                'is_active' => true,
            ]
        );

        // 2. Create Amenities
        $amenitiesList = [
            ['name' => 'Wi-Fi Fibre Haut Débit', 'slug' => 'wifi', 'icon' => 'wifi'],
            ['name' => 'Machine à laver', 'slug' => 'washing-machine', 'icon' => 'washer'],
            ['name' => 'Cuisine Équipée', 'slug' => 'equipped-kitchen', 'icon' => 'kitchen'],
            ['name' => 'Proche Transports / Métro', 'slug' => 'transport', 'icon' => 'train'],
            ['name' => 'Ascenseur', 'slug' => 'elevator', 'icon' => 'elevator'],
            ['name' => 'Climatisation / Chauffage', 'slug' => 'ac', 'icon' => 'snowflake'],
        ];

        $createdAmenities = [];
        foreach ($amenitiesList as $item) {
            $createdAmenities[] = Amenity::firstOrCreate(['slug' => $item['slug']], $item);
        }

        // 3. Create Sample Student Properties
        $sampleProperties = [
            [
                'title' => 'Studio Moderne Tout Équipé - Proche Campus Universitaire',
                'description' => 'Superbe studio rénové de 24m², idéal pour étudiant. Cuisine équipée avec micro-ondes et réfrigérateur, coin bureau spacieux, salle de bain privée avec douche à l’italienne.',
                'type' => 'studio',
                'price_monthly' => 450.00,
                'deposit' => 450.00,
                'charges_included' => true,
                'surface_sqm' => 24,
                'rooms_count' => 1,
                'bathrooms_count' => 1,
                'address' => '12 Rue des Universités',
                'city' => 'Tunis',
                'postal_code' => '1002',
                'latitude' => 36.8065,
                'longitude' => 10.1815,
                'is_furnished' => true,
                'is_available' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Colocation T3 Conviviale avec Balcon pour Étudiants',
                'description' => 'Chambre privée dans un grand appartement de 70m². Espace commun lumineux avec balcon, cuisine moderne et salle de bain commune. Connexion fibre très haut débit.',
                'type' => 'shared_flat',
                'price_monthly' => 320.00,
                'deposit' => 320.00,
                'charges_included' => true,
                'surface_sqm' => 16,
                'rooms_count' => 3,
                'bathrooms_count' => 1,
                'address' => '45 Boulevard des Sciences',
                'city' => 'Sousse',
                'postal_code' => '4000',
                'latitude' => 35.8256,
                'longitude' => 10.6084,
                'is_furnished' => true,
                'is_available' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Chambre Calme chez l’Habitant avec Bureau et SDB',
                'description' => 'Chambre individuelle tout confort dans une maison calme, à 5 minutes à pied de la faculté. Idéal pour étudiant sérieux cherchant la tranquillité pour réviser.',
                'type' => 'room',
                'price_monthly' => 280.00,
                'deposit' => 280.00,
                'charges_included' => true,
                'surface_sqm' => 14,
                'rooms_count' => 1,
                'bathrooms_count' => 1,
                'address' => '8 Avenue de la République',
                'city' => 'Sfax',
                'postal_code' => '3000',
                'latitude' => 34.7406,
                'longitude' => 10.7603,
                'is_furnished' => true,
                'is_available' => true,
                'status' => 'published',
            ],
        ];

        foreach ($sampleProperties as $propData) {
            $slug = Str::slug($propData['title']);
            $property = Property::firstOrCreate(
                ['slug' => $slug],
                array_merge($propData, ['owner_id' => $owner->id])
            );

            // Attach amenities
            $property->amenities()->sync(collect($createdAmenities)->pluck('id'));

            // Attach demo image
            PropertyImage::firstOrCreate(
                ['property_id' => $property->id, 'path' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'],
                ['is_primary' => true, 'order' => 1, 'caption' => 'Vue principale de la pièce']
            );
        }
    }
}
