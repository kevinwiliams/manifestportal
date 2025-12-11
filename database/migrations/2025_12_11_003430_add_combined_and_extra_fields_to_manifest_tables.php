<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add combined manifest fields
        Schema::table('manifest_uploads', function (Blueprint $table) {
            if (!Schema::hasColumn('manifest_uploads', 'combined_file_path')) {
                $table->string('combined_file_path')->nullable()->after('stored_path');
            }

            if (!Schema::hasColumn('manifest_uploads', 'combined_at')) {
                $table->timestamp('combined_at')->nullable()->after('combined_file_path');
            }
        });

        // Add extra detail fields from second file
        Schema::table('manifest_rows', function (Blueprint $table) {
            if (!Schema::hasColumn('manifest_rows', 'truck_descr')) {
                $table->string('truck_descr')->nullable()->after('pub_date');
            }

            if (!Schema::hasColumn('manifest_rows', 'drop_instructions')) {
                $table->text('drop_instructions')->nullable()->after('truck_descr');
            }
        });
    }

    public function down(): void
    {
        Schema::table('manifest_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('manifest_uploads', 'combined_at')) {
                $table->dropColumn('combined_at');
            }
            if (Schema::hasColumn('manifest_uploads', 'combined_file_path')) {
                $table->dropColumn('combined_file_path');
            }
        });

        Schema::table('manifest_rows', function (Blueprint $table) {
            if (Schema::hasColumn('manifest_rows', 'truck_descr')) {
                $table->dropColumn('truck_descr');
            }
            if (Schema::hasColumn('manifest_rows', 'drop_instructions')) {
                $table->dropColumn('drop_instructions');
            }
        });
    }
};
