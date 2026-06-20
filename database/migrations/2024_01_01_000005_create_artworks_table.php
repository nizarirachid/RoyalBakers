<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->default('artwork');
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_ar');
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('main_image');
            $table->json('gallery_images')->nullable();
            $table->enum('calligraphy_style', ['moroccan', 'andalusian', 'naskh', 'thuluth', 'kufic', 'ruqah', 'diwani', 'other'])->default('moroccan');
            $table->enum('material', ['paper', 'wood', 'glass', 'plaster', 'canvas', 'leather', 'fabric', 'digital', 'other'])->default('paper');
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_for_sale')->default(false);
            $table->boolean('is_sold')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->string('text_content')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('artwork_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->string('tag');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_tags');
        Schema::dropIfExists('artworks');
        Schema::dropIfExists('categories');
    }
};
