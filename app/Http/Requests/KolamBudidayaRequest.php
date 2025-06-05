<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamBudidayaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'pembudidaya_id'  => 'required',
            'nama_kolam'   => 'required',
            'lokasi_kolam' => 'required',
            'panjang'      => 'required',
            'lebar'        => 'required',
            'kedalaman'    => 'required',
            'volume_air'   => 'required',
            'kapasitas'    => 'required',
            'jenis_kolam'  => 'required',
            'status'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'pembudidaya_id.required'  => 'Budidaya wajib dipilih.',
            'nama_kolam.required'   => 'Nama kolam wajib diisi.',
            'lokasi_kolam.required' => 'Lokasi kolam wajib diisi.',
            'panjang.required'      => 'Panjang wajib diisi.',
            'lebar.required'        => 'Lebar wajib diisi.',
            'kedalaman.required'    => 'Kedalaman wajib diisi.',
            'volume_air.required'   => 'Volume air wajib diisi.',
            'kapasitas.required'    => 'Kapasitas wajib diisi.',
            'jenis_kolam.required'  => 'Jenis kolam wajib diisi.',
            'status.required'       => 'Status wajib diisi.',
        ];
    }
}
