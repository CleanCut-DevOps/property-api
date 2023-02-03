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
        Schema::create("properties", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("icon", 48);
            $table->uuid("user_id")->nullable();
            $table->uuid("type_id")->nullable();
            $table->string("label")->default("My Property");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("type_id")->references("id")->on("property_types");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists("property");
    }
};
