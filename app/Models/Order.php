<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'customer_address', 'customer_city', 'customer_country',
        'order_type', 'text_to_write', 'width_cm', 'height_cm', 'material',
        'calligraphy_style', 'notes', 'reference_images', 'delivery_method',
        'delivery_address', 'artwork_price', 'material_price', 'labor_price',
        'digital_copy_price', 'shipping_price', 'discount', 'total_price',
        'currency', 'payment_method', 'payment_status', 'payment_reference',
        'status', 'admin_notes', 'confirmed_at', 'completed_at',
    ];

    protected $casts = [
        'reference_images' => 'array',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'width_cm' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'artwork_price' => 'decimal:2',
        'material_price' => 'decimal:2',
        'labor_price' => 'decimal:2',
        'digital_copy_price' => 'decimal:2',
        'shipping_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'NZ-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function calculateTotal(): float
    {
        return ($this->artwork_price + $this->material_price + $this->labor_price
            + $this->digital_copy_price + $this->shipping_price) - $this->discount;
    }
}
