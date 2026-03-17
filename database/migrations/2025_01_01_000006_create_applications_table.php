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
            $table->text('cover_letter')->nullable();          
            $table->string('cv_path')->nullable();             
            $table->enum('status', [
                'pending',      
                'reviewing',    
                'accepted',     
                'rejected',     
                'withdrawn',    
            ])->default('pending');
            $table->text('company_feedback')->nullable();      
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            
            $table->unique(['student_id', 'offer_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
