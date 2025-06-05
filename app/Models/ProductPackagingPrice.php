<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPackagingPrice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_packaging_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_type_id',
        'package_type',
        'net_weight',
        'selling_price',
        'cost_price',
        'effective_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'net_weight' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'effective_date' => 'date',
    ];

    /**
     * Get the product type that owns this packaging price.
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get formatted net weight with unit.
     *
     * @return string
     */
    public function getFormattedNetWeightAttribute()
    {
        return number_format($this->net_weight, 2) . ' kg';
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
     * Get formatted cost price with currency.
     *
     * @return string
     */
    public function getFormattedCostPriceAttribute()
    {
        return $this->cost_price ? 'Rp ' . number_format($this->cost_price, 2) : '-';
    }

    /**
     * Calculate the price per kilogram.
     *
     * @return float|null
     */
    public function getPricePerKgAttribute()
    {
        if ($this->net_weight > 0) {
            return $this->selling_price / $this->net_weight;
        }
        return null;
    }

    /**
     * Get formatted price per kilogram.
     *
     * @return string
     */
    public function getFormattedPricePerKgAttribute()
    {
        return $this->price_per_kg ? 'Rp ' . number_format($this->price_per_kg, 2) . '/kg' : '-';
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

    /**
     * Scope for prices by package type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPackageType($query, $type)
    {
        return $query->where('package_type', $type);
    }
}
