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
        Schema::create('truck_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('source_code', 20)->unique();   // e.g. 'DEV1'
            $table->string('target_code', 20);             // e.g. 'DEV-01'
            $table->string('description')->nullable();     // optional, for your own reference
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_mappings');
    }
};
