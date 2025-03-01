<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_data', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birth_date');
            $table->string('school_year');
            $table->string('field_of_study');
            $table->string('current_school');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_data');
    }
};