<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KolamSiklus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_siklus';

    protected $fillable = [
        'uuid',
        'kolam_budidaya_id',
        'strain',
        'start_date',
        'initial_stock',
        'initial_avg_weight',
        'stocking_density',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolam_budidaya(): HasOne
    {
        return $this->hasOne(KolamBudidaya::class, 'id', 'kolam_budidaya_id');
    }

    public function kolamBudidaya()
    {
        return $this->belongsTo(KolamBudidaya::class, 'id', 'kolam_budidaya_id');
    }

    public function estimations()
    {
        return $this->hasMany(HarvestEstimation::class);
    }

    public function realizations()
    {
        return $this->hasMany(HarvestRealization::class);
    }
}
