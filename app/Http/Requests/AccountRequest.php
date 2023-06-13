<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AccountRequest extends FormRequest
{
    public function authorize()
    {
        return in_array(Auth::user()->role, ["admin", "superadmin"]);
    }

    public function rules()
    {
        return isset($this->id) ? [
            'name' => 'required',
            'pendidikan' => 'required',
            'alamat' => 'required',
            'jabatan_id' => 'required',
            'pangkat_id' => 'required',
            'status_id' => 'required',
            'tanggal_lahir' => 'required',
            'nip' => 'nullable|digits:18|numeric|unique:users,nip,' . $this->id,
            'nik' => 'required|digits:16|numeric|unique:users,nik,' . $this->id,
            'email' => 'required|email|unique:users,email,' . $this->id,
            'old_password' => 'nullable',
            'password' => 'required_with:old_password',
            'c_password' => 'required_with:old_password|same:password'
        ] : [
            'name' => 'required',
            'pendidikan' => 'required',
            'alamat' => 'required',
            'jabatan_id' => 'required',
            'pangkat_id' => 'required',
            'status_id' => 'required',
            'tanggal_lahir' => 'required',
            'nip' => 'nullable|digits:18|numeric|unique:users,nip,' . $this->id,
            'nik' => 'required|digits:16|numeric|unique:users,nik,' . $this->id,
            'email' => 'required|email|unique:users,email,' . $this->id,
            'password' => 'required',
            'c_password' => 'required|same:password'
        ];
    }

    public function messages(){
        return isset($this->id) ? [
            'name.required' => 'Mohon Masukkan Nama Lengkap Terlebih Dahulu!',
            'pendidikan.required' => 'Mohon Masukkan Pendidikan Terlebih Dahulu!',
            'alamat.required' => 'Mohon Masukkan Alamat Terlebih Dahulu!',
            'jabatan_id.required' => 'Mohon Pilih Jabatan Terlebih Dahulu!',
            'pangkat_id.required' => 'Mohon Pilih Pangkat Terlebih Dahulu!',
            'status_id.required' => 'Mohon Pilih Status Terlebih Dahulu!',
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
            'password.required_with' => 'Mohon Masukkan Password Terlebih Dahulu!',
            'c_password.required_with' => 'Mohon Masukkan Konfirmasi Password Terlebih Dahulu!',
            'c_password.same' => 'Konfirmasi Password Tidak Sama Dengan Password!'
        ] : [
            'name.required' => 'Mohon Masukkan Nama Lengkap Terlebih Dahulu!',
            'pendidikan.required' => 'Mohon Masukkan Pendidikan Terlebih Dahulu!',
            'alamat.required' => 'Mohon Masukkan Alamat Terlebih Dahulu!',
            'jabatan_id.required' => 'Mohon Pilih Jabatan Terlebih Dahulu!',
            'pangkat_id.required' => 'Mohon Pilih Pangkat Terlebih Dahulu!',
            'status_id.required' => 'Mohon Pilih Status Terlebih Dahulu!',
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
            'password.required' => 'Mohon Masukkan Password Terlebih Dahulu!',
            'c_password.required' => 'Mohon Masukkan Konfirmasi Password Terlebih Dahulu!',
            'c_password.same' => 'Konfirmasi Password Tidak Sama Dengan Password!'
        ];
    }
}
