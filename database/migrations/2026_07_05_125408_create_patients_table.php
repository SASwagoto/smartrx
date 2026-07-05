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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('patient_unique_id', 20)->unique();

            $table->string('name');
            $table->string('phone_number', 20);

            $table->date('date_of_birth')->nullable();
            $table->string('age', 20)->nullable();

            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group', 5)->nullable();

            $table->string('email')->nullable();
            $table->string('address')->nullable();

            $table->string('occupation')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();

            $table->string('image')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            /*
    |--------------------------------------------------------------------------
    | Indexes
    |--------------------------------------------------------------------------
    */

            // Most frequent searches
            $table->index('name');
            $table->index('phone_number');

            // Patient lookup
            $table->index('patient_unique_id');

            // Filtering
            $table->index('gender');
            $table->index('blood_group');
            $table->index('is_active');

            // Audit
            $table->index('created_by');

            // Soft delete optimization
            $table->index('deleted_at');

            // Composite indexes
            $table->index(['name', 'phone_number']);
            $table->index(['is_active', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
