<?php

namespace App\Http\Controllers;

use App\Models\PesananSurat as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananSuratController extends Controller
{
    public function index(Request $req)
    {
        $builder = new Model;
        
        $data = $builder->get();

        return [
            'error' => false,
            'data' => $data
        ];
    }

    public function show($id)
    {
        $data = Model::find($id);

        return [
            'error' => false,
            'data' => $data
        ];
    }

    public function store(Request $req)
    {
        $rules = Model::rules();
        $surat = \App\Models\Surat::find($req->surat_id);
        if ($surat) {
            foreach ($surat->rules as $key => $value) {
                $rules['values.' . $value['kolom']] = 'required';
            }
        } else {
            return [
                'error' => true,
                'message' => 'surat tidak ditemukan'
            ];
        }
        
        $schema = Validator::make($req->all(), $rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all()
            ];
        }

        $data = $schema->validated();
        Model::create($data);

        return [
            'error' => false,
            'message' => 'surat berhasil ditambah'
        ];
    }

    public function update(Request $req, $id)
    {
        $rules = Model::rules();
        $surat = \App\Models\Surat::find($req->surat_id);
        if ($surat) {
            foreach ($surat->rules as $key => $value) {
                $rules['values.' . $value['kolom']] = 'required';
            }
        } else {
            return [
                'error' => true,
                'message' => 'surat tidak ditemukan'
            ];
        }
        
        $schema = Validator::make($req->all(), $rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all()
            ];
        }

        $data = $schema->validated();
        Model::find($id)->update($data);

        return [
            'error' => false,
            'message' => 'surat berhasil diedit'
        ];
    }

    public function destroy($id)
    {
        Model::destroy($id);

        return [
            'error' => false,
            'message' => 'surat berhasil dihapus'
        ];
    }
}
