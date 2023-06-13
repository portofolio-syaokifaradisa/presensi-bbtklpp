<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
            'c_password' => 'required|same:new_password'
        ];
    }

    public function messages(){
        return [
            'old_password.required' => 'Mohon Masukkan Password Lama Terlebih Dahulu!',
            'new_password.required' => 'Mohon Masukkan Password Baru Terlebih Dahulu!',
            'c_password.required' => 'Mohon Masukkan Konfirmasi Password Terlebih Dahulu!',
            'c_password.same' => 'Konfirmasi Password Tidak Sama Dengan Password Baru!'
        ];
    }
}
