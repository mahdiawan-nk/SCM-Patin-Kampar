<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;
class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'segment',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
