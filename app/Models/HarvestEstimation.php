<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HarvestEstimation extends Model
{
    use SoftDeletes;

    protected $table='harvest_estimations';

    protected $fillable = [
        'uuid',
        'kolam_budidaya_id',
        'kolam_siklus_id',
        'estimation_harvest_at',
        'estimation_harvest_amount',
        'estimation_harvest_weight',
        'estimation_harvest_percentage',
        'estimate_survival_rate',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolamBudidaya()
    {
        return $this->belongsTo(KolamBudidaya::class);
    }

    public function kolamSiklus()
    {
        return $this->belongsTo(KolamSiklus::class);
    }
}
