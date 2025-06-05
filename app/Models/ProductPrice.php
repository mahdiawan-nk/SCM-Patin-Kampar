<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_type_id',
        'cost_price',
        'margin',
        'selling_price',
        'effective_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cost_price' => 'decimal:2',
        'margin' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'effective_date' => 'date',
    ];

    /**
     * Get the product type that owns this price.
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Calculate selling price based on cost and margin.
     *
     * @return float
     */
    public function calculateSellingPrice()
    {
        if ($this->cost_price && $this->margin) {
            return $this->cost_price * (1 + ($this->margin / 100));
        }
        return $this->selling_price;
    }

    /**
     * Get formatted cost price with currency.
     *
     * @return string
     */
    public function getFormattedCostPriceAttribute()
    {
        return 'Rp ' . number_format($this->cost_price, 2);
    }

    /**
     * Get formatted selling price with currency.
     *
     * @return string
     */
    public function getFormattedSellingPriceAttribute()
    {
        return 'Rp ' . number_format($this->selling_price, 2);
    }

    /**
     * Get formatted margin percentage.
     *
     * @return string
     */
    public function getFormattedMarginAttribute()
    {
        return number_format($this->margin, 2) . '%';
    }

    /**
     * Scope for active prices (where effective date is null or in the past).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('effective_date')
                ->orWhere('effective_date', '<=', now());
        });
    }
}
