<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PembudidayaUsaha extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'pembudidayas_usaha';

    protected $fillable = [
        'uuid',
        'pembudidaya_id',
        'nama_usaha',
        'jenis_usaha',
        'luas_lahan',
        'jumlah_kolam',
        'sistem_budidaya',
        'jenis_izin_usaha',
        'tahun_mulai_usaha',
        'status_kepemilikan_usaha',
        'nama_kelompok',
        'jabatan_di_kelompok',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'alamat_usaha',
        'kordinat',
        'no_izin_usaha',
        'ktp_scan',
        'foto_lokasi',
        'surat_izin',
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
}
