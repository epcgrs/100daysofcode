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
        Schema::create('form_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_field_id')->constrained('form_fields')->onDelete('cascade');
            $table->string('operator'); // =, !=, contains, etc.
            $table->string('value');    // valor a comparar
            $table->foreignId('target_field_id')->constrained('form_fields')->onDelete('cascade');
            $table->string('action');   // show / hide
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_rules');
    }
};
