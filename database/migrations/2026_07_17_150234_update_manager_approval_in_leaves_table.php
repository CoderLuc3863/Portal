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
        Schema::table('leaves', function (Blueprint $table) {

            // $table->dropColumn('manager_status');

            //$table->string('manager_approval', 256)
            //      ->nullable()
            //      ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {

            $table->dropColumn('manager_approval');

            $table->string('manager_status', 256)
                  ->nullable()
                  ->after('status');
        });
    }
};