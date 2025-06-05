<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamSiklusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'kolam_id' => 'required',
            'strain' => 'required',
            'start_date' => 'required',
            'initial_stock' => 'required',
            'initial_avg_weight' => 'required',
            'stocking_density' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'kolam_id.required' => 'Kolam wajib dipilih',
            'strain.required' => 'Strain wajib dipilih',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'initial_stock.required' => 'Stok awal wajib diisi',
            'initial_avg_weight.required' => 'Berat rata-rata awal wajib diisi',
            'stocking_density.required' => 'Densitas wajib diisi',
            'status.required' => 'Status wajib diisi',
        ];
    }
}
