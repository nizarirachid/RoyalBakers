<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('type', ['individual', 'group', 'online', 'offline', 'hybrid'])->default('online');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('calligraphy_style', ['moroccan', 'andalusian', 'naskh', 'thuluth', 'kufic', 'all'])->default('all');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('duration_hours')->nullable();
            $table->integer('max_students')->nullable();
            $table->integer('enrolled_count')->default(0);
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();
            $table->json('curriculum')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_paid', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('progress', ['enrolled', 'in_progress', 'completed'])->default('enrolled');
            $table->integer('completion_percentage')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->text('content_ar')->nullable();
            $table->text('content_fr')->nullable();
            $table->text('content_en')->nullable();
            $table->string('video_url')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('courses');
    }
};
