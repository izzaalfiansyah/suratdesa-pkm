<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    public $table = 'surat';

    public $fillable = [
        'nama',
        'rules'
    ];

    public $casts = [
        'rules' => 'array'
    ];

    public static function rules()
    {
        return [
            'nama' => 'required',
            'rules' => 'required',
            'rules.*.kolom' => 'required',
            'rules.*.tipe' => 'required',
            'rules.*.default' => 'nullable'
        ];
    }
}
