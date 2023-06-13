<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'nip',
        'email',
        'password',
        'role',
        'nik',
        'gender',
        'alamat',
        'tanggal_lahir',
        'pendidikan',
        'gelar',
        'status_id',
        'pangkat_id',
        'jabatan_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function jabatan(){
        return $this->belongsTo(Jabatan::class);
    }

    public function pangkat(){
        return $this->belongsTo(Pangkat::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
