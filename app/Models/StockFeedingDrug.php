<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StockFeedingDrug extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stocks_feedings_drugs';

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'jumlah',
        'satuan',
        'kadaluarsa_at',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
