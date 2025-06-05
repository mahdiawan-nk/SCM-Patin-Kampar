<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryTracking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'delivery_trackings';

    /**
     * Status constants
     */
    public const STATUS_MULAI = 'mulai';
    public const STATUS_DALAM_PERJALANAN = 'dalam_perjalanan';
    public const STATUS_TIBA = 'tiba';
    public const STATUS_TERLAMBAT = 'terlambat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'delivery_id',
        'status',
        'latitude',
        'longitude',
        'timestamp',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the delivery that owns this tracking record.
     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Get formatted coordinates.
     *
     * @return string|null
     */
    public function getFormattedCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return number_format($this->latitude, 6) . ', ' . number_format($this->longitude, 6);
        }
        return null;
    }

    /**
     * Get human-readable status.
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            self::STATUS_MULAI => 'Mulai Pengiriman',
            self::STATUS_DALAM_PERJALANAN => 'Dalam Perjalanan',
            self::STATUS_TIBA => 'Tiba di Tujuan',
            self::STATUS_TERLAMBAT => 'Terlambat',
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Check if tracking has location data.
     *
     * @return bool
     */
    public function getHasLocationAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get all available status options.
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_MULAI => 'Mulai Pengiriman',
            self::STATUS_DALAM_PERJALANAN => 'Dalam Perjalanan',
            self::STATUS_TIBA => 'Tiba di Tujuan',
            self::STATUS_TERLAMBAT => 'Terlambat',
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
