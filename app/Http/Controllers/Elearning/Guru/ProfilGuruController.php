<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class ProfilGuruController extends Controller
{
	public function main()
	{
		$data['guru'] = Guru::where('users_id', Auth::user()->id)->first();
		return view('main.content.guru.profil-guru.main', $data);
	}

	public function save(Request $request)
	{
		$params = [
			'id_guru' => 'required',
			'nip' => 'required',
			'no_tlp' => 'required',
			'gender' => 'required',
			'tmp_lahir' => 'required',
			'tgl_lahir' => 'required',
			'alamat' => 'required',
		];
		$message = [
			'id_guru.required' => 'ID Guru harus diisi',
			'nip.required' => 'NIP harus diisi',
			'no_tlp.required' => 'No Telepon harus diisi',
			'gender.required' => 'Gender harus diisi',
			'tmp_lahir.required' => 'Tempat Lahir harus diisi',
			'tgl_lahir.required' => 'Tanggal Lahir harus diisi',
			'alamat.required' => 'Alamat harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$guru = Guru::where('id_guru', $request->id_guru)->first()) {
			return ['status' => 'fail', 'message' => 'Data Guru tidak ditemukan'];
		}
		$guru->nip = $request->nip;
		$guru->no_tlp = $request->no_tlp;
		$guru->gender = $request->gender;
		$guru->tmp_lahir = $request->tmp_lahir;
		$guru->tgl_lahir = $request->tgl_lahir;
		$guru->alamat = $request->alamat;
		if (isset($request->foto)) {
			if ($guru->foto != '') {
				if (file_exists('uploads/guru/' . $guru->foto)) {
					unlink('uploads/guru/' . $guru->foto);
				}
			}
			$ukuranFile1 = filesize($request->foto);
			if ($ukuranFile1 <= 500000) {
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				$filename1 = "Foto_Guru" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/guru/';
				$proses1 = $request->foto->move($temp_foto1, $filename1);
				$guru->foto = $filename1;
			} else {
				$file1 = $_FILES['foto']['name'];
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				if (!empty($file1)) {
					$direktori1 = 'uploads/guru/'; //tempat upload foto
					$name1 = 'foto'; //name pada input type file
					$namaBaru1 = "Foto_Guru" . date('Ymd-His'); //name pada input type file
					$quality1 = 50; //konversi kualitas gambar dalam satuan %
					$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
				}
				$guru->foto = $namaBaru1 . "." . $ext_foto1;
			}
		}
		if (!$guru->save()) {
			return ['status' => 'fail', 'message' => 'Gagal Menyimpan, coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan!'];
	}
}
