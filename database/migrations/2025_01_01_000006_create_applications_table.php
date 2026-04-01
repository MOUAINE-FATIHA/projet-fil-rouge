<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->onDelete('cascade');
            $table->foreignId('offer_id')->constrained('internship_offers')->onDelete('cascade');
            $table->text('cover_letter')->nullable();          // lettre de motivation
            $table->string('cv_path')->nullable();             // CV spécifique à cette candidature
            $table->enum('status', [
                'pending',      // en attente
                'reviewing',    // en cours de traitement
                'accepted',     // acceptée
                'rejected',     // refusée
                'withdrawn',    // retirée par l'étudiant
            ])->default('pending');
            $table->text('company_feedback')->nullable();      // retour de l'entreprise
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Un étudiant ne peut postuler qu'une fois par offre
            $table->unique(['student_id', 'offer_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
