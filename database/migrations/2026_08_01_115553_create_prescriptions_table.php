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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_visit_id')->nullable()->constrained('patient_visits')->onDelete('set null');
            $table->string('patient_name')->nullable();
            $table->string('patient_phone')->nullable();
            $table->string('patient_age')->nullable();
            $table->enum('patient_gender', ['male', 'female', 'other'])->nullable();
            $table->string('prescription_no', 20)->unique();
            $table->dateTime('prescription_date');
            $table->text('clinical_notes')->nullable();
            $table->text('advice')->nullable();
            $table->json('diagnosis')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('follow_up_text')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Comment: Proper indexing for prescriptions table
            $table->index('patient_name');          // Fast search by patient name
            $table->index('patient_phone');         // Fast search by phone number
            $table->index('prescription_date');     // Useful for date range filtering & sorting
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
