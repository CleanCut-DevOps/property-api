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
        Schema::create("type", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('label');
            $table->string('description');
            $table->text('detailed_description')->nullable();
            $table->boolean('available')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists("type");
    }
};
