<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('value')->nullable()->default(null);
            $table->boolean('status')->default(false);
            $table->string('type'); // input[text,file,..], textarea, select
            $table->string('path')->nullable();
            $table->foreignIdFor(\App\Models\Indicator::class)->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Course::class)->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
