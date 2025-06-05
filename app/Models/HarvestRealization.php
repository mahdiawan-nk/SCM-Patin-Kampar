<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HarvestRealization extends Model
{
    use SoftDeletes;

    protected $table = 'harvest_realizations';

    protected $fillable = [
        'uuid',
        'kolam_budidaya_id',
        'kolam_siklus_id',
        'actual_harvest_at',
        'actual_harvest_amount',
        'actual_harvest_weight',
        'actual_harvest_percentage',
        'actual_survival_rate',
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

    public function estimation()
    {
        return $this->hasOne(HarvestEstimation::class, 'kolam_siklus_id', 'kolam_siklus_id');
    }
}
