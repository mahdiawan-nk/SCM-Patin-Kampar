<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackagingBatch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packaging_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'daily_production_id',
        'package_type',
        'quantity_pack',
        'label_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();

            // Generate label code if empty
            if (empty($model->label_code)) {
                $model->label_code = $model->generateLabelCode();
            }
        });
    }

    /**
     * Generate automatic label code
     */
    protected function generateLabelCode()
    {
        $date = now()->format('ymd');
        $random = Str::upper(Str::random(3));
        return "PKG-{$date}-{$random}";
    }

    /**
     * Get the daily production associated with this packaging batch.
     */
    public function dailyProduction()
    {
        return $this->belongsTo(ProductionDaily::class, 'daily_production_id');
    }

    /**
     * Scope for batches with specific package type
     */
    public function scopeOfPackageType($query, $packageType)
    {
        return $query->where('package_type', $packageType);
    }

    /**
     * Scope for batches with quantity greater than given value
     */
    public function scopeQuantityGreaterThan($query, $quantity)
    {
        return $query->where('quantity_pack', '>', $quantity);
    }
}
