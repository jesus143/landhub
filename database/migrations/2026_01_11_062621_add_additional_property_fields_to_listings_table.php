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
            $table->string('property_type')->nullable()->after('category')->comment('LOT, HOUSE, etc.');
            $table->decimal('frontage', 10, 2)->nullable()->after('property_type')->comment('Frontage in meters');
            $table->string('road_type')->nullable()->after('frontage')->comment('Cemented, Dirt, Gravel, etc.');
            $table->integer('num_rooms')->nullable()->after('road_type')->comment('Number of rooms (for houses)');
            $table->boolean('is_fenced')->default(false)->after('num_rooms')->comment('Whether property is fenced');
            $table->boolean('is_beachfront')->default(false)->after('is_fenced')->comment('Whether property is beachfront');
            $table->decimal('beach_frontage', 10, 2)->nullable()->after('is_beachfront')->comment('Beach frontage in meters');
            $table->string('title_status')->nullable()->after('is_titled')->comment('Clean Title, Solo title, etc.');
            $table->text('payment_terms')->nullable()->after('additional_features')->comment('Payment terms (Cash basis, pwede 2 gives, etc.)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'property_type',
                'frontage',
                'road_type',
                'num_rooms',
                'is_fenced',
                'is_beachfront',
                'beach_frontage',
                'title_status',
                'payment_terms',
            ]);
        });
    }
};
