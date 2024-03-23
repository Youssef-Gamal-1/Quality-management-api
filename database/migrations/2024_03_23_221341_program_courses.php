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
        Schema::create('program_courses',function(Blueprint $table){
            $table->foreignIdFor(\App\Models\Program::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Course::class)
                ->constrained()
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_courses');
    }
};