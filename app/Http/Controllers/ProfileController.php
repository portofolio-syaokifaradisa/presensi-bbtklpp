<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Status;
use App\Models\Jabatan;
use App\Models\Pangkat;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;

class ProfileController extends Controller
{
    public function index(){
        
        $account = Auth::user();
        $jabatan = Jabatan::orderBy('nama')->get();
        $pangkat = Pangkat::orderBy('golongan')->get();
        return view('profile.edit', compact('account', 'jabatan', 'pangkat'));
    }

    public function update(ProfileRequest $request){
        try{
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->nip = $request->nip;
            $user->email = $request->email;
            $user->nik = $request->nik;
            $user->pendidikan = $request->pendidikan;
            $user->gelar = $request->gelar;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->alamat = $request->alamat;
            $user->jabatan_id = $request->jabatan_id;
            $user->pangkat_id = $request->pangkat_id;
            $user->save();

            return redirect(route('profile.index'))->with('success', 'Berhasil Mengubah Profile Akun');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!');
        }
    }

    public function password(){
        return view('profile.password');
    }

    public function updatePassword(PasswordRequest $request){
        try{
            $user = User::find(Auth::user()->id);
            if(password_verify($request->old_password, $user->password)){
                $user->password = bcrypt($request->new_password);
                $user->save();
                return redirect(route('profile.password'))->with('success', 'Password Berhasil Diubah!');
            }else{
                return redirect(route('profile.password'))->with('error', 'Password Lama Anda Salah, Silahkan Coba Lagi!')->withInput($request->input());
            }
        }catch(Exception $e){
            return redirect(route('profile.password'))->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput($request->input());
        }
    }   
}
