<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'deliveries';

    // Delivery type constants
    public const TYPE_INTERNAL = 'internal';
    public const TYPE_EKSTERNAL = 'eksternal';

    // Status constants
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DIKIRIM = 'dikirim';
    public const STATUS_TIBA = 'tiba';
    public const STATUS_GAGAL = 'gagal';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'delivery_type',
        'delivery_date',
        'estimated_arrival',
        'actual_arrival',
        'status',
        'driver_id',
        'vehicle_id',
        'courier_service',
        'tracking_number',
        'tracking_url',
        'note',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'delivery_date' => 'date',
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
    ];

    /**
     * Get all available delivery types.
     */
    public static function getDeliveryTypes(): array
    {
        return [
            self::TYPE_INTERNAL => 'Internal',
            self::TYPE_EKSTERNAL => 'Eksternal',
        ];
    }

    /**
     * Get all available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_DIKIRIM => 'Dikirim',
            self::STATUS_TIBA => 'Tiba',
            self::STATUS_GAGAL => 'Gagal',
        ];
    }

    /**
     * Relationship with Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship with Driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(ArmadaDriver::class);
    }

    /**
     * Relationship with Vehicle.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(ArmadaVehicle::class);
    }

    /**
     * Get human-readable delivery type.
     */
    public function getDeliveryTypeNameAttribute(): string
    {
        return self::getDeliveryTypes()[$this->delivery_type] ?? ucfirst($this->delivery_type);
    }

    /**
     * Get human-readable status.
     */
    public function getStatusNameAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Check if delivery is late.
     */
    public function getIsLateAttribute(): bool
    {
        if ($this->actual_arrival) {
            return $this->actual_arrival > $this->estimated_arrival;
        }

        return now() > $this->estimated_arrival;
    }

    /**
     * Scope for internal deliveries.
     */
    public function scopeInternal($query)
    {
        return $query->where('delivery_type', self::TYPE_INTERNAL);
    }

    /**
     * Scope for eksternal deliveries.
     */
    public function scopeEksternal($query)
    {
        return $query->where('delivery_type', self::TYPE_EKSTERNAL);
    }

    /**
     * Scope for active deliveries (not completed or failed).
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_TIBA, self::STATUS_GAGAL]);
    }

    /**
     * Scope for completed deliveries.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_TIBA);
    }
}
