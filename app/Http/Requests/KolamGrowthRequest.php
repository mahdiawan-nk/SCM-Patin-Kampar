<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamGrowthRequest extends FormRequest
{
    public function rules()
    {
        return [
            'siklus_id'  => 'required',
            'grow_at'    => 'required',
            'avg_weight' => 'required',
            'avg_length' => 'required',
            'mortality'  => 'required',
            'note'       => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'siklus_id.required'  => 'Siklus wajib dipilih',
            'grow_at.required'    => 'Tanggal pertumbuhan wajib diisi',
            'avg_weight.required' => 'Berat rata-rata (gram) wajib diisi',
            'avg_length.required' => 'Panjang rata-rata (cm) wajib diisi',
            'mortality.required'  => 'Kematian (ekor) wajib diisi',
        ];
    }
}
