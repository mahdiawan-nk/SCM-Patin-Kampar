<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KolamMonitoring extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_monitorings';

    protected $fillable = [
        'kolam_budidaya_id',
        'tgl_monitoring',
        'temperature',
        'ph',
        'do',
        'tds',
        'turbidity',
        'humidity',
        'brightness',
        'amonia',
        'nitrite',
        'nitrate',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolam_budidaya()
    {
        return $this->belongsTo(KolamBudidaya::class, 'kolam_budidaya_id');
    }

    public function kolamBudidaya()
    {
        return $this->belongsTo(KolamBudidaya::class, 'kolam_budidaya_id');
    }
}
