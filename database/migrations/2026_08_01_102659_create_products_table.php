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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->unique();
            $table->string('code')->nullable();

            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->decimal('price', 10, 2)->nullable()->default(0);
            $table->decimal('quantity', 10, 2)->nullable()->default(0);

            $table->string('brand')->nullable();
            $table->string('category_id')->nullable();
            $table->string('category_code')->nullable();
            $table->string('category_name')->nullable();

            $table->timestamps();

            $table->index('name');
            $table->index('generic_name');
            $table->index('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
