<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    protected $table = 'pricing_config';

    protected $fillable = [
        'price_per_cm', 'digital_copy_price',
        'shipping_local_price', 'shipping_international_price', 'currency',
    ];

    protected $casts = [
        'price_per_cm' => 'decimal:2',
        'digital_copy_price' => 'decimal:2',
        'shipping_local_price' => 'decimal:2',
        'shipping_international_price' => 'decimal:2',
    ];
}
