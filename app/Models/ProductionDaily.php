<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductionDaily extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_dailies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_date',
        'product_type_id',
        'production_input_id',
        'production_quantity',
        'batch_code',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'production_date' => 'date',
        'production_quantity' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();

            // Generate batch code if empty
            if (empty($model->batch_code)) {
                $model->batch_code = $this->generateBatchCode();
            }
        });
    }

    /**
     * Generate automatic batch code
     */
    protected function generateBatchCode()
    {
        $date = now()->format('Ymd');
        $random = Str::upper(Str::random(4));
        return "BATCH-{$date}-{$random}";
    }

    /**
     * Get the product type associated with the production.
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the production input associated with the production.
     */
    public function productionInput()
    {
        return $this->belongsTo(ProductionInput::class);
    }

    /**
     * Scope for productions on specific date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('production_date', $date);
    }

    /**
     * Scope for productions of specific product type
     */
    public function scopeOfProductType($query, $productTypeId)
    {
        return $query->where('product_type_id', $productTypeId);
    }
}
