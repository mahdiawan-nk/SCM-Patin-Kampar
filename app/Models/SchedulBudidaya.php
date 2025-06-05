<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SchedulBudidaya extends Model
{
    use SoftDeletes;

    protected $table = 'schedule_budidayas';

    protected $fillable = [
        'uuid',
        'kolam_budidaya_id',
        'activity_type',
        'title',
        'schedule_at',
        'reminder_at',
        'is_done',
        'note',
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
        return $this->belongsTo(KolamBudidaya::class);
    }
}
