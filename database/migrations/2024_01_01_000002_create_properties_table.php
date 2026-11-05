<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['studio', 'apartment', 'room', 'shared_flat']);
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('deposit', 10, 2)->default(0.00);
            $table->boolean('charges_included')->default(true);
            $table->unsignedSmallInteger('surface_sqm');
            $table->unsignedTinyInteger('rooms_count')->default(1);
            $table->unsignedTinyInteger('bathrooms_count')->default(1);
            $table->string('address');
            $table->string('city', 100);
            $table->string('postal_code', 20);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_furnished')->default(true);
            $table->boolean('is_available')->default(true);
            $table->enum('status', ['draft', 'published', 'rented', 'archived'])->default('published');
            $table->timestamps();

            $table->index(['city', 'status', 'is_available']);
            $table->index(['price_monthly', 'rooms_count']);
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
        });

        Schema::create('amenity_property', function (Blueprint $table) {
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->primary(['property_id', 'amenity_id']);
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('amenity_property');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('properties');
    }
};
