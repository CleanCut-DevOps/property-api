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
        Schema::create("property", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("user_id")->nullable();
            $table->uuid("type_id")->nullable();
            $table->string("icon", 48);
            $table->string("label")->default("My Property");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("type_id")->references("id")->on("type");
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
