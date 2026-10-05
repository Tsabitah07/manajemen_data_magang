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
        Schema::create('campus_units', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('unit_name', [
                'Lecturer',
                'Admissions and Enrollment',
                'Academic Affairs',
                'Student Affairs',
                'Research and Development',
                'Finance and Administration',
                'Human Resources',
                'Information Technology',
                'Library Services',
                'International Relations',
                'Alumni Relations',
            ])->default('Lecturer');
            $table->enum('position', [
                'Head of Unit',
                'Deputy Head of Unit',
                'Coordinator',
                'Staff',
            ])->default('Staff');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_units');
    }
};
