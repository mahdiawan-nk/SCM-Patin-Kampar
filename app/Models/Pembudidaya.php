<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Pembudidaya extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'pembudidayas';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'email',
        'no_hp',
        'alamat_lengkap',
        'status',
        'tgl_bergabung',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    protected function jenisKelamin(): Attribute
    {
        return Attribute::make(
            get: fn($value) => match (strtoupper($value)) {
                'P' => 'Perempuan',
                'L' => 'Laki-laki',
                default => $value,
            },
            set: fn($value) => match (strtolower($value)) {
                'perempuan' => 'P',
                'p' => 'P',
                'laki-laki' => 'L',
                'l' => 'L',
                default => $value,
            }
        );
    }

    public function usaha(): HasOne
    {
        return $this->hasOne(PembudidayaUsaha::class, 'pembudidaya_id');
    }

    public function kolam(): HasMany
    {
        return $this->hasMany(KolamBudidaya::class, 'pembudidaya_id', 'id');
    }
}
