<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table représentant un stage actif (candidature acceptée → stage)
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisor_profiles')->nullOnDelete();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->enum('status', [
                'not_started',    // pas encore commencé
                'in_progress',    // en cours
                'completed',      // terminé
                'interrupted',    // interrompu
            ])->default('not_started');
            $table->string('convention_path')->nullable();    // convention signée
            $table->string('report_path')->nullable();        // rapport de stage
            $table->text('student_feedback')->nullable();     // retour de l'étudiant
            $table->integer('student_rating')->nullable();    // note 1-5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
