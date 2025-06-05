<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class MarketBehaviors extends Model
{
    use SoftDeletes;

    protected $table = 'market_behaviors';

    protected $fillable = [
        'customer_id',
        'product_type_id',
        'total_orders',
        'total_spent',
        'last_order_at',
        'trend_score',
        'recommendation',
    ];
}
