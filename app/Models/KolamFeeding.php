<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KolamFeeding extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_feedings';

    protected $fillable = [
        'uuid',
        'kolam_siklus_id',
        'general_stock_item_id',
        'feeding_name',
        'feed_at',
        'feed_amount',
        'frequency',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolam_siklus(): BelongsTo
    {
        return $this->belongsTo(KolamSiklus::class);
    }

    public function generalStockItem(): BelongsTo
    {
        return $this->belongsTo(GeneralStockItem::class, 'general_stock_item_id', 'id');
    }
}
