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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('dosage_data')->nullable();
            $table->string('dosage_unit')->nullable();
            $table->string('dosage_time')->nullable();
            $table->string('duration')->nullable();
            $table->string('duration_type')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
