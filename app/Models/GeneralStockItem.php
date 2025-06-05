<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralStockItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'general_stock_items';

    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'minimum_stock',
        'description',
        'is_active',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public static function generateCode(): string
    {
        $prefix = 'ITM-';
        $latest = static::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;

        return $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
    /**
     * Get all stock mutations for this item.
     */
    public function mutations()
    {
        return $this->hasMany(GeneralStockMutation::class);
    }

    /**
     * Get the current stock balance for this item.
     */
    public function balance()
    {
        return $this->hasOne(GeneralStockBalance::class);
    }

    /**
     * Get the current stock quantity.
     */
    public function getCurrentStockAttribute()
    {
        return optional($this->balance)->quantity ?? 0;
    }

    /**
     * Get the total stock value.
     */
    public function getTotalValueAttribute()
    {
        return optional($this->balance)->value ?? 0;
    }
}
