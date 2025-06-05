<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStockBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_stock_item_id',
        'quantity',
        'value',
        'last_updated',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'value' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    public function productStockItem(): BelongsTo
    {
        return $this->belongsTo(ProductStockItem::class);
    }
}