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
        Schema::create('manifest_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('manifest_uploads')->onDelete('cascade');

            $table->string('truck', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('drop_address')->nullable();
            $table->string('route', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('seq')->nullable();
            $table->string('account', 50)->nullable();
            $table->string('group', 50)->nullable();
            $table->integer('draw')->nullable();
            $table->integer('returns')->nullable();

            $table->string('pub_code', 20);
            $table->date('pub_date');

            // new columns from second file
            $table->string('truck_descr')->nullable();
            $table->text('drop_instructions')->nullable();

            $table->timestamps();

            // Prevent duplicates: pub_date + account + pub_code
            $table->unique(['pub_date', 'account', 'pub_code'], 'manifest_unique_account_pub');
            $table->index(['truck', 'route']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifest_rows');
    }
};
