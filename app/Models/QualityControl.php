<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class QualityControl extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quality_controls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_daily_id',
        'inspector_name',
        'inspection_date',
        'result',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'inspection_date' => 'date',
    ];

    /**
     * Possible values for result enum
     */
    public const RESULTS = [
        'lulus' => 'Lulus',
        'tidak_lulus' => 'Tidak Lulus',
        'perlu_review' => 'Perlu Review'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();

            // Set default inspection date if empty
            if (empty($model->inspection_date)) {
                $model->inspection_date = now();
            }
        });
    }

    /**
     * Get the daily production associated with this quality control.
     */
    public function productionDaily()
    {
        return $this->belongsTo(ProductionDaily::class);
    }

    /**
     * Get the human-readable result name
     */
    public function getResultNameAttribute()
    {
        return self::RESULTS[$this->result] ?? $this->result;
    }

    /**
     * Scope for passed inspections
     */
    public function scopePassed($query)
    {
        return $query->where('result', 'lulus');
    }

    /**
     * Scope for failed inspections
     */
    public function scopeFailed($query)
    {
        return $query->where('result', 'tidak_lulus');
    }

    /**
     * Scope for inspections needing review
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('result', 'perlu_review');
    }

    /**
     * Check if the inspection is passed
     */
    public function isPassed()
    {
        return $this->result === 'lulus';
    }

    /**
     * Check if the inspection needs review
     */
    public function needsReview()
    {
        return $this->result === 'perlu_review';
    }
}
