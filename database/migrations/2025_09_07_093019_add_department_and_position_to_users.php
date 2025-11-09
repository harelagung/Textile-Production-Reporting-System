<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table
                ->foreignId("department_id")
                ->nullable()
                ->after("email_verified_at")
                ->constrained("departments")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreignId("position_id")
                ->nullable()
                ->after("department_id")
                ->constrained("positions")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            //
        });
    }
};
