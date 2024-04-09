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
        Schema::create('user_permission', function (Blueprint $table) {
            $table->foreignId(\App\Models\Permission::class)->constrained()->onDelete('cascade'); 
            $table->foreignId(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->primary([\App\Models\User::class, \App\Models\Permission::class]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
