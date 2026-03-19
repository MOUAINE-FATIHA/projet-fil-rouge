<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('domain')->nullable();              // domaine: web, data, marketing...
            $table->enum('type', ['pfe', 'pfa', 'summer', 'other'])->default('pfe');
            $table->integer('duration_months');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->decimal('stipend', 8, 2)->nullable();      // gratification en MAD
            $table->json('required_skills')->nullable();
            $table->string('required_level')->nullable();      // niveau requis
            $table->integer('slots')->default(1);              // nombre de places
            $table->enum('status', ['draft', 'published', 'closed', 'archived'])->default('published');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'application_deadline']);
            $table->index('domain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_offers');
    }
};
