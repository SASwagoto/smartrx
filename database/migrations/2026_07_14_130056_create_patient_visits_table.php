<?php

use App\Enums\VisitStatus;
use App\Enums\VisitType;
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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('visit_no', 20)->unique();
            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnUpdate();
            $table->dateTime('visit_date');
            $table->string('visit_type', 20)
                ->default(VisitType::NEW->value);
            /*
            |--------------------------------------------------------------------------
            | Clinical Vitals
            |--------------------------------------------------------------------------
            */
            $table->json('vitals')->nullable();
            /*
            |--------------------------------------------------------------------------
            | Clinical Notes
            |--------------------------------------------------------------------------
            */
            $table->text('chief_complaint')->nullable();

            $table->longText('history')->nullable();

            $table->longText('clinical_findings')->nullable();

            $table->longText('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Follow Up
            |--------------------------------------------------------------------------
            */

            $table->date('follow_up_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 20)
                ->default(VisitStatus::COMPLETED->value);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

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

            $table->index('visit_no');

            $table->index('visit_date');

            $table->index('patient_id');

            $table->index('doctor_id');

            $table->index('visit_type');

            $table->index('status');

            $table->index('follow_up_date');

            $table->index('deleted_at');

            $table->index([
                'patient_id',
                'visit_date',
            ]);

            $table->index([
                'doctor_id',
                'visit_date',
            ]);

            $table->index([
                'status',
                'visit_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
};
