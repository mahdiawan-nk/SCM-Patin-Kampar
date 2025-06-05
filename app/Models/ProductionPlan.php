<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductionPlan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_date',
        'product_type_id',
        'planned_quantity',
        'status',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'plan_date' => 'date',
        'planned_quantity' => 'decimal:2',
    ];

    /**
     * Possible values for status enum
     */
    public const STATUSES = [
        'rencana' => 'Rencana',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai'
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
     * Get the product type associated with the production plan.
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the human-readable status name
     */
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Scope for plans with 'rencana' status
     */
    public function scopePlanned($query)
    {
        return $query->where('status', 'rencana');
    }

    /**
     * Scope for plans with 'diproses' status
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'diproses');
    }

    /**
     * Scope for plans with 'selesai' status
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }
}
