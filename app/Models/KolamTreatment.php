<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KolamTreatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kolam_treatments';

    protected $fillable = [
        'uuid',
        'kolam_siklus_id',
        'treat_at',
        'disease',
        'medication',
        'dosage',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function kolamSiklus()
    {
        return $this->belongsTo(KolamSiklus::class);
    }
}
