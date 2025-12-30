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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained()->restrictOnDelete();

            $table->enum('type', ['passport', 'license', 'other'])->default('passport');

            $table->string('passport_series', 4)->nullable();
            $table->string('passport_number', 6)->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->string('passport_issued_by', 250)->nullable();
            $table->string('passport_department_code', 6)->nullable();

            $table->string('license_series_number', 10)->nullable();
            $table->date('license_issue_date')->nullable();
            $table->string('license_region', 150)->nullable();
            $table->string('license_issued_by', 250)->nullable();

            $table->string('other_document_name', 150)->nullable();
            $table->string('other_series_number', 50)->nullable();
            $table->string('other_series_number_original', 50)->nullable();
            $table->date('other_issue_date')->nullable();
            $table->string('other_issued_by', 250)->nullable();

            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
