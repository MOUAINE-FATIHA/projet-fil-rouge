<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->string('convention_status')->default('pending')->after('convention_path');
            $table->timestamp('convention_validated_at')->nullable()->after('convention_status');
        });
    }

    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn(['convention_status', 'convention_validated_at']);
        });
    }
};
