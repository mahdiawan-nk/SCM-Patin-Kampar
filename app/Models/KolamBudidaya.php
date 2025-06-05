<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KolamBudidaya extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_budidayas';

    protected $fillable = [
        'uuid',
        'pembudidaya_id',
        'nama_kolam',
        'lokasi_kolam',
        'panjang',
        'lebar',
        'kedalaman',
        'volume_air',
        'kapasitas',
        'jenis_kolam',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function pembudidaya(): BelongsTo
    {
        return $this->belongsTo(Pembudidaya::class);
    }

    public function siklus(): HasMany
    {
        return $this->hasMany(KolamSiklus::class, 'kolam_budidaya_id', 'id');
    }

    public function kolamSiklus(): HasMany
    {
        return $this->hasMany(KolamSiklus::class, 'kolam_budidaya_id', 'id');
    }

    public function monitoring(): HasMany
    {
        return $this->hasMany(KolamMonitoring::class, 'kolam_budidaya_id', 'id');
    }

    public function kolamMonitoring(): HasMany
    {
        return $this->hasMany(KolamMonitoring::class, 'kolam_budidaya_id', 'id');
    }

    public function estimations()
    {
        return $this->hasMany(HarvestEstimation::class,'kolam_budidaya_id', 'id');
    }

    public function realizations()
    {
        return $this->hasMany(HarvestRealization::class, 'kolam_budidaya_id', 'id');
    }
}
