<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KolamGrowth extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_growths';

    protected $fillable = [
        'uuid',
        'kolam_siklus_id',
        'grow_at',
        'avg_weight',
        'avg_length',
        'mortality',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolam_siklus()
    {
        return $this->belongsTo(KolamSiklus::class);
    }
}
