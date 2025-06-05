<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralStockBalance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'general_stock_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'general_stock_item_id',
        'quantity',
        'value',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'decimal:3',
        'value' => 'decimal:2',
        'last_updated' => 'date',
    ];

    /**
     * Get the stock item that owns the balance record.
     */
    public function item()
    {
        return $this->belongsTo(GeneralStockItem::class, 'general_stock_item_id');
    }
}
