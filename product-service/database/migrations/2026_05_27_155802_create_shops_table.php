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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('money', 15, 2)->default(0);
            $table->string('province');
            $table->unsignedBigInteger('province_id');
            $table->string('city');
            $table->unsignedBigInteger('city_id');
            $table->string('district');
            $table->unsignedBigInteger('district_id');
            $table->string('created_by')->nullable();
            $table->unsignedBigInteger('user_id'); // FK to users service
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
