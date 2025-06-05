<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductStockMutation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_stock_item_id',
        'date',
        'type',
        'quantity',
        'current_stock',
        'reference_type',
        'reference_id',
        'source',
        'note',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'current_stock' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($mutation) {
            $mutation->updateCurrentStock();
        });

        static::created(function ($mutation) {
            $mutation->productStockItem->updateStockBalance();
        });

        static::updated(function ($mutation) {
            $mutation->productStockItem->updateStockBalance();
        });

        static::deleted(function ($mutation) {
            $mutation->productStockItem->updateStockBalance();
        });
    }

    public function productStockItem(): BelongsTo
    {
        return $this->belongsTo(ProductStockItem::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function updateCurrentStock(): void
    {
        $currentStock = $this->productStockItem->current_stock;

        if ($this->type === 'in') {
            $this->current_stock = $currentStock + $this->quantity;
        } else {
            $this->current_stock = $currentStock - $this->quantity;
        }
    }
}
