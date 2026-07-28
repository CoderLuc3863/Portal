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

            // $table->decimal('leavecount', 4, 1)
            //     ->default(1)
            //     ->after('to_date');

            // $table->enum('leavecategory', [
            //     'Full Day',
            //     'Half Day'
            // ])->default('Full Day')
            // ->after('leavecount');

            // $table->enum('leavesession', [
            //     'AM',
            //     'PM',
            //     'NA'
            // ])->default('NA')
            // ->after('leavecategory');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
        });
    }
};
