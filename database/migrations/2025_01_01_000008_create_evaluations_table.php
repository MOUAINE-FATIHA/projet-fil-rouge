<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('evaluator_type', ['company', 'supervisor']); // qui évalue
            $table->integer('technical_score')->nullable();     // compétences techniques /20
            $table->integer('behavior_score')->nullable();      // comportement /20
            $table->integer('communication_score')->nullable(); // communication /20
            $table->integer('autonomy_score')->nullable();      // autonomie /20
            $table->integer('overall_score')->nullable();       // note globale /20
            $table->text('comments')->nullable();
            $table->boolean('is_final')->default(false);        // évaluation finale
            $table->timestamps();
            $table->unique(['internship_id', 'evaluator_id', 'evaluator_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
