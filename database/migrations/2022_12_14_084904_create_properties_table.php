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
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('name');
            $table->string('address');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->longText('description');
            $table->unsignedDecimal('price', 12, 2);
            $table->unsignedDecimal('sq_ft',12, 2)->nullable();
            $table->enum('type', ['Single-family home', 'Duplex', 'Triplex', 'Fourplex', 'Condominium', 'Townhouse', 'Apartment building', 'Co-op', 'Manufactured home', 'Tiny home', 'Office building', 'Warehouse']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
