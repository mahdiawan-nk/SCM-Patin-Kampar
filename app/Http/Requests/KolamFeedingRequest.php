<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamFeedingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'siklus_id'   => 'required',
            'feeding_id'  => 'required',
            'feed_at'     => 'required',
            'feed_amount' => 'required',
            'frequency'   => 'required',
            'note'        => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'siklus_id.required'   => 'Siklus wajib dipilih',
            'feeding_id.required'  => 'Pakan wajib dipilih',
            'feed_at.required'     => 'Tanggal pemberian pakan wajib diisi',
            'feed_amount.required' => 'Jumlah pakan diberikan (kg) wajib diisi',
            'frequency.required'   => 'Frekuensi per hari (kali/hari) wajib diisi',
        ];
    }
}
