<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArmadaVehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'armada_vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'capacity_kg',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'capacity_kg' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Vehicle type constants for consistent usage
     */
    public const TYPE_TRUCK = 'truck';
    public const TYPE_PICKUP = 'pickup';
    public const TYPE_VAN = 'van';
    public const TYPE_MOTORCYCLE = 'motorcycle';

    /**
     * Get available vehicle types
     *
     * @return array
     */
    public static function getVehicleTypes(): array
    {
        return [
            self::TYPE_TRUCK => 'Truck',
            self::TYPE_PICKUP => 'Pickup',
            self::TYPE_VAN => 'Van',
            self::TYPE_MOTORCYCLE => 'Motorcycle',
        ];
    }

    /**
     * Get the active status as human-readable text
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get formatted capacity with unit
     *
     * @return string
     */
    public function getFormattedCapacityAttribute(): string
    {
        return number_format($this->capacity_kg, 2) . ' kg';
    }

    /**
     * Get the human-readable vehicle type
     *
     * @return string
     */
    public function getVehicleTypeNameAttribute(): string
    {
        return self::getVehicleTypes()[$this->vehicle_type] ?? ucfirst($this->vehicle_type);
    }

    /**
     * Scope a query to only include active vehicles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include vehicles of specific type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('vehicle_type', $type);
    }
}
