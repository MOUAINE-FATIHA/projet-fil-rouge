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
        Schema::table('internships', function (Blueprint $table) {
            $table->string('convention_place')->nullable()->after('convention_validated_at');
            $table->text('convention_tasks')->nullable()->after('convention_place');
            $table->text('convention_notes')->nullable()->after('convention_tasks');
            $table->timestamp('convention_prepared_at')->nullable()->after('convention_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn([
                'convention_place',
                'convention_tasks',
                'convention_notes',
                'convention_prepared_at',
            ]);
        });
    }
};
