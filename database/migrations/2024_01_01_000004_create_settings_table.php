<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('pricing_config', function (Blueprint $table) {
            $table->id();
            $table->decimal('price_per_cm', 10, 2)->default(5.00);
            $table->decimal('digital_copy_price', 10, 2)->default(50.00);
            $table->decimal('shipping_local_price', 10, 2)->default(30.00);
            $table->decimal('shipping_international_price', 10, 2)->default(150.00);
            $table->string('currency', 10)->default('MAD');
            $table->timestamps();
        });

        Schema::create('material_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->string('name_en');
            $table->decimal('price_per_unit', 10, 2);
            $table->string('unit')->default('cm²');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pricing_config');
        Schema::dropIfExists('material_prices');
    }
};
