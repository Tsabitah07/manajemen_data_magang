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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();

            $table->string('internship_id');
            $table->foreign('internship_id')->references('id')->on('internships')->onDelete('cascade');
            $table->string('student_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('cv')->nullable();
            $table->boolean('cv_required')->default(false);

            $table->enum('status', [
                'waiting',
                'accepted',
                'rejected',
                'cancelled',
                'completed'
            ])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
