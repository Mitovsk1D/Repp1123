<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professors_data', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Linking to the user table
            $table->string('position'); // Professor's position (e.g., Assistant Professor, Lecturer)
            $table->string('company'); // Company or university where the professor works
            $table->enum('gender', ['Male', 'Female', 'Other']); // Gender field with predefined options
            $table->date('birth_date'); // Birthdate of the professor
            $table->text('work_experience')->nullable(); // Optional work experience field
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professors_data'); // Dropping the professors_data table
    }
}
