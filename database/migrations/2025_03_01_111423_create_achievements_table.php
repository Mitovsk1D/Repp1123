<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Links achievement to a user
            $table->string('title'); // Achievement title (e.g., "Python Master", "Certified Developer")
            $table->text('description')->nullable(); // Optional description of the achievement
            $table->string('type')->default('badge'); // Type of achievement (badge, certificate, etc.)
            $table->string('image')->nullable(); // Optional image URL for the achievement
            $table->timestamp('earned_at')->useCurrent(); // Timestamp when the achievement was earned
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements'); // Drops the table on rollback
    }
};
