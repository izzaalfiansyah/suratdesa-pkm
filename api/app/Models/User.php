<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $table = 'user';

    public $fillable = [
        'username',
        'password',
        'nama',
        'nomor_telepon',
        'alamat',
        'role',
        'foto'
    ];

    public $appends = [
        'role_nama',
        'foto_url',
    ];

    public function getRoleNamaAttribute()
    {
        return ($this->role == '1') ? 'Admin' : 'Regular';
    }

    public function getFotoUrlAttribute()
    {
        return url($this::fotoPath . ($this->foto ? $this->foto : 'default.png'));
    }


    
    const rules = [
        'nama' => 'required',
        'nomor_telepon' => 'required|numeric',
        'alamat' => 'required',
        'role' => 'required|in:1,2'
    ];

    const authRules = [
        'username' => 'required',
        'password' => 'required'
    ];

    const fotoPath = 'user/';
}
