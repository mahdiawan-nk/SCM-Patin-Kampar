<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductStockItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_type_id',
        'unit',
        'minimum_stock',
        'is_active',
        'description',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(ProductStockMutation::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(ProductStockBalance::class);
    }

    public function updateStockBalance(): void
    {
        $totalQuantity = $this->mutations()
            ->sum(DB::raw('CASE WHEN type = "in" THEN quantity ELSE -quantity END'));

        $balance = $this->balance()->firstOrNew();
        $balance->quantity = $totalQuantity;
        $balance->last_updated = now();
        $balance->save();
    }

    public function getCurrentStockAttribute(): float
    {
        return $this->balance ? (float)$this->balance->quantity : 0;
    }

    public function isBelowMinimumStock(): bool
    {
        return $this->current_stock < $this->minimum_stock;
    }
}