<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamTreatmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'siklus_id' => 'required',
            'treat_at' => 'required',
            'disease' => 'required',
            'medication' => 'required',
            'dosage' => 'required',
            'note' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'siklus_id.required' => 'Siklus wajib dipilih',
            'treat_at.required' => 'Tanggal pemberian obat wajib diisi',
            'disease.required' => 'Penyakit wajib diisi',
            'medication.required' => 'Obat wajib diisi',
            'dosage.required' => 'Dosis wajib diisi',
        ];
    }
}
