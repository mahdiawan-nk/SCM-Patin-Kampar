<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembudidayaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap'             => 'required',
            'nik'                      => 'required',
            'jenis_kelamin'            => 'required',
            'tanggal_lahir'            => 'nullable',
            'email'                    => 'required',
            'no_hp'                    => 'required',
            'alamat_lengkap'           => 'required',
            'status'                   => 'nullable',
            'tgl_bergabung'            => 'nullable',
            'nama_usaha'               => 'required',
            'jenis_usaha'              => 'required',
            'luas_lahan'               => 'required',
            'jumlah_kolam'             => 'required',
            'sistem_budidaya'          => 'required',
            'jenis_izin_usaha'         => 'required',
            'tahun_mulai_usaha'        => 'required',
            'status_kepemilikan_usaha' => 'required',
            'nama_kelompok'            => 'required',
            'jabatan_di_kelompok'      => 'required',
            'provinsi'                 => 'required',
            'kabupaten'                => 'required',
            'kecamatan'                => 'required',
            'desa'                     => 'required',
            'alamat_usaha'             => 'required',
            'kordinat'                 => 'nullable',
            'no_izin_usaha'            => 'nullable',
            'ktp_scan'                 => 'nullable',
            'foto_lokasi'              => 'nullable',
            'surat_izin'               => 'nullable',
        ];
    }
    public function messages(): array
    {
        return [
            'nama_lengkap.required'              => 'Nama lengkap wajib diisi.',
            'nik.required'                       => 'NIK wajib diisi.',
            'jenis_kelamin.required'             => 'Jenis kelamin wajib dipilih.',
            'email.required'                     => 'Email wajib diisi.',
            'no_hp.required'                     => 'Nomor HP wajib diisi.',
            'alamat_lengkap.required'            => 'Alamat lengkap wajib diisi.',
            'nama_usaha.required'                => 'Nama usaha wajib diisi.',
            'jenis_usaha.required'               => 'Jenis usaha wajib diisi.',
            'luas_lahan.required'                => 'Luas lahan wajib diisi.',
            'jumlah_kolam.required'              => 'Jumlah kolam wajib diisi.',
            'sistem_budidaya.required'           => 'Sistem budidaya wajib dipilih.',
            'jenis_izin_usaha.required'          => 'Jenis izin usaha wajib diisi.',
            'tahun_mulai_usaha.required'         => 'Tahun mulai usaha wajib diisi.',
            'status_kepemilikan_usaha.required'  => 'Status kepemilikan usaha wajib diisi.',
            'nama_kelompok.required'             => 'Nama kelompok wajib diisi.',
            'jabatan_di_kelompok.required'       => 'Jabatan di kelompok wajib diisi.',
            'provinsi.required'                  => 'Provinsi wajib dipilih.',
            'kabupaten.required'                 => 'Kabupaten wajib dipilih.',
            'kecamatan.required'                 => 'Kecamatan wajib dipilih.',
            'desa.required'                      => 'Desa wajib dipilih.',
            'alamat_usaha.required'              => 'Alamat usaha wajib diisi.',
        ];
    }
}
