<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class CustomerFeedback extends Model
{
    use SoftDeletes;
    protected $table = 'customer_feedbacks';

    protected $fillable = [
        'uuid',
        'customer_id',
        'type',
        'name',
        'email',
        'message',
        'submited_at',
        'status',
        'read_at',
        'response',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
