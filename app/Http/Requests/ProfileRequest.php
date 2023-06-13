<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'nip' => 'nullable|numeric|digits:18|unique:users,nip,' . Auth::user()->id,
            'pendidikan' => 'required',
            'alamat' => 'required',
            'jabatan_id' => 'required',
            'pangkat_id' => 'required',
            'tanggal_lahir' => 'required',
            'nik' => 'required|digits:16|numeric|unique:users,nik,' . Auth::user()->id,
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Mohon Masukkan Nama Lengkap Terlebih Dahulu!',
            'pendidikan.required' => 'Mohon Masukkan Pendidikan Terlebih Dahulu!',
            'alamat.required' => 'Mohon Masukkan Alamat Terlebih Dahulu!',
            'jabatan_id.required' => 'Mohon Pilih Jabatan Terlebih Dahulu!',
            'pangkat_id.required' => 'Mohon Pilih Pangkat Terlebih Dahulu!',
            'tanggal_lahir.required' => 'Mohon Pilih Tanggal Lahir Terlebih Dahulu!',
            'nip.digits' => 'Mohon Masukkan NIP Sebanyak 18 Digit!',
            'nip.numeric' => 'Mohon Masukkan NIP Berupa Angka!',
            'nip.unique' => 'NIP Sudah Terdaftar Sebelumya!',
            'nik.required' => 'Mohon Masukkan NIK Terlebih Dahulu!',
            'nik.digits' => 'Mohon Masukkan NIK Sebanyak 16 Digit!',
            'nik.numeric' => 'Mohon Masukkan NIK Berupa Angka!',
            'nik.unique' => 'NIK Sudah Terdaftar Sebelumya!',
            'email.required' => 'Mohon Masukkan Email Terlebih Dahulu!',
            'email.email' => 'Mohon Masukkan Format Email yang Valid!',
            'email.unique' => 'Email Sudah Pernah Terdaftar Sebelumnya!',
        ];
    }
}
