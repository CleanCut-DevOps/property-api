<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create("room_types", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("type_id");
            $table->string('label');
            $table->unsignedDouble('price');
            $table->boolean('available');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('property_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists("room_type");
    }
};
