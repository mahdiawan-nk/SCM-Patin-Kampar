<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeneralStockMutation extends Model
{
    use HasFactory;

    protected $table = 'general_stock_mutations';

    protected $fillable = [
        'general_stock_item_id',
        'date',
        'type',
        'quantity',
        'current_stock',
        'reference_type',
        'reference_id',
        'source',
        'usage_type',
        'adjustment_type',
        'reason',
        'note',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:3',
        'current_stock' => 'decimal:3',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($mutation) {
            // Calculate current_stock before creation
            $mutation->calculateCurrentStock();
        });

        static::created(function ($mutation) {
            $mutation->updateStockBalance();
        });

        static::updated(function ($mutation) {
            $mutation->updateStockBalance();
        });

        static::deleted(function ($mutation) {
            $mutation->updateStockBalance();
        });
    }
    public function calculateCurrentStock()
    {
        $currentStock = $this->item->current_stock ?? 0;

        if ($this->type === 'in' || ($this->type === 'adjustment' && $this->adjustment_type === 'tambah')) {
            $this->current_stock = $currentStock + $this->quantity;
        } elseif ($this->type === 'out' || ($this->type === 'adjustment' && $this->adjustment_type === 'kurang')) {
            $this->current_stock = $currentStock - $this->quantity;
        } else {
            $this->current_stock = $currentStock;
        }
    }
    /**
     * Update the stock balance after mutation changes.
     */
    public function updateStockBalance()
    {
        $item = $this->item;
        $totalQuantity = $item->mutations()
            ->sum(DB::raw("CASE WHEN type = 'in' THEN quantity ELSE -quantity END"));

        $item->balance()->updateOrCreate(
            ['general_stock_item_id' => $item->id],
            [
                'quantity' => $totalQuantity,
                'last_updated' => now(),
                // Note: You may need additional logic to calculate value
                'value' => $totalQuantity * $item->unit_price, // Assuming you have unit_price
            ]
        );
    }

    /**
     * Get the stock item that owns the mutation.
     */
    public function item()
    {
        return $this->belongsTo(GeneralStockItem::class, 'general_stock_item_id');
    }

    /**
     * Get the user who created the mutation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the reference model (polymorphic relationship).
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
