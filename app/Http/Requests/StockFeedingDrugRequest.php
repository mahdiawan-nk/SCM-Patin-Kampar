<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockFeedingDrugRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'name' => 'required',
            'jumlah' => 'required',
            'satuan' => 'required',
            'kadaluarsa_at' => 'required',
            'note' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Jenis pakan/obat wajib dipilih',
            'name.required' => 'Jenis pakan/obat wajib diisi',
            'jumlah.required' => 'Jumlah pakan/obat (kg) wajib diisi',
            'satuan.required' => 'Satuan pakan/obat wajib diisi',
            'kadaluarsa_at.required' => 'Tanggal kadaluarsa pakan/obat wajib diisi',
        ];
    }
}
