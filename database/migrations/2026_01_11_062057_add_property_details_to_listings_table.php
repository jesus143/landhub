<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('is_titled')->default(false)->after('status')->comment('Whether the property is titled');
            $table->text('trees_plants')->nullable()->after('is_titled')->comment('List of trees and plants on the property');
            $table->string('terrain_type')->nullable()->after('trees_plants')->comment('Terrain description (e.g., FLAT to Rolling)');
            $table->boolean('vehicle_accessible')->default(false)->after('terrain_type')->comment('Whether vehicles can access the property');
            $table->text('additional_features')->nullable()->after('vehicle_accessible')->comment('Additional features and amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['is_titled', 'trees_plants', 'terrain_type', 'vehicle_accessible', 'additional_features']);
        });
    }
};
