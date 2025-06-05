<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductionInput extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_inputs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_date',
        'source_type',
        'source_name',
        'total_weight_kg',
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
        'total_weight_kg' => 'decimal:2',
    ];

    /**
     * Possible values for source_type enum
     */
    public const SOURCE_TYPES = [
        'panen' => 'Panen',
        'stok' => 'Stok',
        'lainnya' => 'Lainnya'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    /**
     * Get the human-readable source type name
     */
    public function getSourceTypeNameAttribute()
    {
        return self::SOURCE_TYPES[$this->source_type] ?? $this->source_type;
    }
}
