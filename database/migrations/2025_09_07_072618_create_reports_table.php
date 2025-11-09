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
        Schema::create("reports", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("shift_id")->constrained("shifts")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("machine_id")->constrained("machines")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("construction_id")->constrained("constructions")->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger("stock")->default(0);
            $table->decimal("counter", 10, 2)->default(0.0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reports");
    }
};
