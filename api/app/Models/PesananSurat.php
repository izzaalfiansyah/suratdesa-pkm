<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananSurat extends Model
{
    use HasFactory;

    public $table = 'pesanan_surat';

    public $fillable = [
        'surat_id',
        'values',
        'penanggung_jawab',
        'nomor_telepon',
        'status',
    ];

    public $with = [
        'surat'
    ];

    public $casts = [
        'values' => 'array'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public static function rules()
    {
        return [
            'surat_id' => 'required',
            'values' => 'required',
            'penanggung_jawab' => 'required',
            'nomor_telepon' => 'required|numeric',
            'status' => 'required|in:1,0',
        ];
    }
}
