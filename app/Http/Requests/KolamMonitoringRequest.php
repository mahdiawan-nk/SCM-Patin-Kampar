<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KolamMonitoringRequest extends FormRequest
{
    public function rules()
    {
        return [
            'kolam_id' => 'required',
            'tgl_monitoring' => 'required',
            'temperature' => 'required',
            'ph' => 'required',
            'do' => 'required',
            'tds' => 'required',
            'turbidity' => 'required',
            'humidity' => 'required',
            'brightness' => 'required',
            'amonia' => 'required',
            'nitrite' => 'required',
            'nitrate' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'kolam_id.required' => 'Kolam wajib dipilih',
            'tgl_monitoring.required' => 'Tanggal monitoring wajib diisi',
            'temperature.required' => 'Suhu wajib diisi',
            'ph.required' => 'pH wajib diisi',
            'do.required' => 'DO wajib diisi',
            'tds.required' => 'TDS wajib diisi',
            'turbidity.required' => 'Turbidity wajib diisi',
            'humidity.required' => 'Kelembaban wajib diisi',
            'brightness.required' => 'Kecerahan wajib diisi',
            'amonia.required' => 'Amonia wajib diisi',
            'nitrite.required' => 'Nitrite wajib diisi',
            'nitrate.required' => 'Nitrate wajib diisi',
        ];
    }
}
