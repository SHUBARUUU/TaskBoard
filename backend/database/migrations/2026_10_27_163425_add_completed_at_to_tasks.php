<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// A newly added migration file that's only purpose is to update the existing table. (Tasks)
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    //  By using Schema::table instead of Schema::create -> it will update the table that it's explicitly encoded to.
    Schema::table('tasks', function (Blueprint $table) {
        $table->timestamp('completed_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('completed_at');
    });
}
};
