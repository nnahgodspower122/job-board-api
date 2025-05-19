<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->string('cover_letter')->nullable(); 
            $table->string('resume')->nullable();
            $table->timestamps();

            $table->unique(['job_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
}