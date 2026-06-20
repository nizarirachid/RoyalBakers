<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_country')->nullable();
            $table->enum('order_type', ['artwork', 'name_writing', 'verse_hadith', 'institution', 'home_decor', 'digital_copy', 'custom'])->default('custom');
            $table->text('text_to_write')->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->enum('material', ['paper', 'wood', 'glass', 'plaster', 'canvas', 'leather', 'fabric', 'digital', 'other'])->nullable();
            $table->enum('calligraphy_style', ['moroccan', 'andalusian', 'naskh', 'thuluth', 'kufic', 'ruqah', 'diwani', 'other'])->nullable();
            $table->text('notes')->nullable();
            $table->json('reference_images')->nullable();
            $table->enum('delivery_method', ['digital', 'postal', 'pickup'])->default('digital');
            $table->string('delivery_address')->nullable();
            $table->decimal('artwork_price', 10, 2)->default(0);
            $table->decimal('material_price', 10, 2)->default(0);
            $table->decimal('labor_price', 10, 2)->default(0);
            $table->decimal('digital_copy_price', 10, 2)->default(0);
            $table->decimal('shipping_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('currency', 10)->default('MAD');
            $table->enum('payment_method', ['paypal', 'stripe', 'bank_transfer', 'manual', 'cash'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'refunded', 'cancelled'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['new', 'confirmed', 'in_progress', 'ready', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('orders');
    }
};
