<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductionWaste extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_wastes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_input_id',
        'waste_type',
        'waste_quantity',
        'disposal_method',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'waste_quantity' => 'decimal:2',
    ];

    /**
     * Common waste types
     */
    public const WASTE_TYPES = [
        'organik' => 'Limbah Organik',
        'anorganik' => 'Limbah Anorganik',
        'bahan_baku' => 'Sisa Bahan Baku',
        'produk_cacat' => 'Produk Cacat',
    ];

    /**
     * Common disposal methods
     */
    public const DISPOSAL_METHODS = [
        'didaur_ulang' => 'Didaur Ulang',
        'dibuang' => 'Dibuang',
        'diolah_kembali' => 'Diolah Kembali',
        'digunakan_kembali' => 'Digunakan Kembali',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    /**
     * Get the production input associated with this waste record.
     */
    public function productionInput()
    {
        return $this->belongsTo(ProductionInput::class);
    }

    /**
     * Get the human-readable waste type name
     */
    public function getWasteTypeNameAttribute()
    {
        return self::WASTE_TYPES[$this->waste_type] ?? $this->waste_type;
    }

    /**
     * Get the human-readable disposal method
     */
    public function getDisposalMethodNameAttribute()
    {
        return self::DISPOSAL_METHODS[$this->disposal_method] ?? $this->disposal_method;
    }

    /**
     * Scope for specific waste type
     */
    public function scopeOfWasteType($query, $type)
    {
        return $query->where('waste_type', $type);
    }

    /**
     * Scope for wastes with quantity greater than
     */
    public function scopeQuantityGreaterThan($query, $quantity)
    {
        return $query->where('waste_quantity', '>', $quantity);
    }

    /**
     * Scope for wastes with specific disposal method
     */
    public function scopeWithDisposalMethod($query, $method)
    {
        return $query->where('disposal_method', $method);
    }
}
