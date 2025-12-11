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
        Schema::create('manifest_uploads', function (Blueprint $table) {
            $table->id();
            $table->date('pub_date');
            $table->string('pub_code', 20);          // from file
            $table->string('original_filename');
            $table->string('stored_path');
            $table->unsignedBigInteger('user_id');
            $table->integer('total_rows')->default(0);
            $table->integer('imported_rows')->default(0);
            $table->integer('skipped_rows')->default(0);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['pub_date', 'pub_code']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifest_uploads');
    }
};
