<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class DistribusiSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'distribusi_scheduls'; // Note: Matches your migration spelling

    /**
     * Status constants
     */
    public const STATUS_DIJADWALKAN = 'dijadwalkan';
    public const STATUS_BERLANGSUNG = 'berlangsung';
    public const STATUS_SELESAI = 'selesai';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'delivery_id',
        'scheduled_date',
        'time_window',
        'status',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_date' => 'date',
    ];

    /**
     * Get the delivery associated with this schedule.
     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Get human-readable status.
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return [
            self::STATUS_DIJADWALKAN => 'Dijadwalkan',
            self::STATUS_BERLANGSUNG => 'Berlangsung',
            self::STATUS_SELESAI => 'Selesai',
        ][$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the start time from time window.
     *
     * @return string|null
     */
    public function getStartTimeAttribute()
    {
        if (strpos($this->time_window, '-') !== false) {
            return trim(explode('-', $this->time_window)[0]);
        }
        return null;
    }

    /**
     * Get the end time from time window.
     *
     * @return string|null
     */
    public function getEndTimeAttribute()
    {
        if (strpos($this->time_window, '-') !== false) {
            return trim(explode('-', $this->time_window)[1]);
        }
        return null;
    }

    /**
     * Check if schedule is active (current time within window).
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        $now = now();
        $today = $now->format('Y-m-d');
        $windowStart = Carbon::createFromFormat('Y-m-d H:i', "{$today} {$this->start_time}");
        $windowEnd = Carbon::createFromFormat('Y-m-d H:i', "{$today} {$this->end_time}");

        return $now->between($windowStart, $windowEnd);
    }

    /**
     * Get all available status options.
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DIJADWALKAN => 'Dijadwalkan',
            self::STATUS_BERLANGSUNG => 'Berlangsung',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    /**
     * Get status text for a given status.
     */
    public static function getStatusText(string $status): string
    {
        return self::getStatusOptions()[$status] ?? ucfirst($status);
    }
}
