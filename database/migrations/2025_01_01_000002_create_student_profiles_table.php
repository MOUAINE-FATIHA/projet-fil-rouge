<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->unique()->nullable(); // numéro apogée
            $table->string('field_of_study')->nullable();      // filière
            $table->string('academic_level')->nullable();      // niveau: L3, M1, M2...
            $table->string('university')->nullable();
            $table->string('city')->nullable();
            $table->json('skills')->nullable();                // tableau de compétences
            $table->json('languages')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->year('graduation_year')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
