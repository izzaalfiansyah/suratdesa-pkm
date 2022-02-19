<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $fotoPath;

    public function __construct()
    {
        $this->fotoPath = public_path(Model::fotoPath);

        if (!is_dir($this->fotoPath)) {
            mkdir($this->fotoPath);
        }
    }

    public function index()
    {
        $data = Model::all();

        return [
            'error' => false,
            'data' => $data,
        ];
    }

    public function show($id)
    {
        $data = Model::find($id);

        return [
            'error' => false,
            'data' => $data,
        ];
    }

    public function store(Request $req)
    {
        $schema = Validator::make($req->all(), array_merge(Model::rules, Model::authRules));

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        $data['password'] = Hash::make($req->password);

        if (strpos($req->foto, ';base64,') >= 0) {
            $foto = explode(';base64,', $req->foto)[1];
            $fotoName = date('Ymdhis') . '.png';

            file_put_contents($this->fotoPath . $fotoName, base64_decode($foto));
            $data['foto'] = $fotoName;
        }

        Model::create($data);

        return [
            'error' => false,
            'message' => 'data berhasil ditambah',
        ];
    }

    public function update(Request $req, $id)
    {
        $schema = Validator::make($req->all(), Model::rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        $item = Model::find($id);

        if (strpos($req->foto, ';base64,') >= 0) {
            $foto = explode(';base64,', $req->foto)[1];
            $fotoName = date('Ymdhis') . '.png';

            file_put_contents($this->fotoPath . $fotoName, base64_decode($foto));
            $data['foto'] = $fotoName;
            @unlink($this->fotoPath . $item->foto);
        }

        $item->update($data);

        return [
            'error' => false,
            'message' => 'data berhasil diedit',
        ];
    }

    public function updateAuth(Request $req, $id)
    {
        $schema = Validator::make($req->all(), Model::authRules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        $data['password'] = Hash::make($req->password);

        Model::find($id)->update($data);

        return [
            'error' => false,
            'message' => 'autentikasi berhasil diedit',
        ];
    }

    public function destroy($id)
    {
        $item = Model::find($id);
        @unlink($this->fotoPath . $item->foto);
        $item->delete();

        return [
            'error' => false,
            'message' => 'data berhasil dihapus',
        ];
    }

    public function login(Request $req)
    {
        $schema = Validator::make($req->all(), Model::authRules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $item = Model::where('username', $req->username)->first();

        if ($item) {
            if (Hash::check($req->password, $item->password)) {
                return [
                    'error' => false,
                    'message' => 'berhasil login',
                    'data' => $item,
                ];
            } else {
                return [
                    'error' => true,
                    'message' => ['password salah'],
                ];
            }
        } else {
            return [
                'error' => true,
                'message' => ['username tidak ditemukan'],
            ];
        }
    }
}
