<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ];
    }

    public function messages(){
        return [
            'email.required' => 'Mohon Isikan Email Terlebih Dahulu!',
            'email.email' => 'Mohon Masukkan Format Email yang Valid!',
            'email.exists' => 'Email Belum Terdaftar!',
            'password.required' => 'Mohon Isikan Password Terlebih Dahulu!'
        ];
    }
}
