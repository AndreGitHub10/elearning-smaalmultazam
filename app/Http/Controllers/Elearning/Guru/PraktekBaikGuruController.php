<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Models\PraktekBaikGuru;
use Illuminate\Http\Request;
use DataTables, Auth, Help;
use Illuminate\Support\Facades\Validator;

class PraktekBaikGuruController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Paktek Baik Guru';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$praktek = PraktekBaikGuru::orderBy('id_praktek_baik_guru', 'DESC')
				->where('user_id', Auth::user()->id)
				->get();
			return DataTables::of($praktek)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->created_at));
			})->addColumn('judul', function ($row) {
				$mapel = '';
				if (strlen($row->judul) > 20) {
					$mapel = substr($row->judul, 0, 20) . '...';
				} else {
					$mapel = $row->judul;
				}
				return $mapel;
			})->editColumn('status', function ($row) {
				if ($row->status) {
					return 'aktif';
				} else {
					return 'tidak aktif';
				}
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahBerita($row->id_praktek_baik_guru)' class='btn btn-dark btn-purple p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				if ($row->status) {
					$html .= "<button onclick='aktifBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-secondary p-2'><i class='bx bx-power-off mx-1'></i></button>";
				} else {
					$html .= "<button onclick='aktifBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-power-off mx-1'></i></button>";
				}
				$html .= "<button onclick='hapusBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.guru.praktek-baik-guru.main', $data);
	}

	public function add(Request $request)
	{
		$data = $this->data;
		$data['praktek'] = PraktekBaikGuru::find($request->id);
		$content = view('main.content.guru.praktek-baik-guru.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'judul' => 'required',
			'status' => 'required',
			'isi' => 'required',
			'gambar' => 'required_without:id'
		];
		$message = [
			'judul.required' => 'Judul harus diisi',
			'status.required' => 'Status harus diisi',
			'isi.required' => 'Isi tidak boleh kosong',
			'gambar.required_without' => 'Gambar Wajib Diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

		if (empty($request->id)) {
			$praktek = new PraktekBaikGuru;
		} else {
			$praktek = PraktekBaikGuru::find($request->id);
		}
		$praktek->user_id = Auth::user()->id;
		$praktek->judul = $request->judul;
		$praktek->isi = $request->isi;
		$praktek->status = $request->status;
		$foto = date('YmdHis');
		if (!empty($request->gambar)) {
			if (!empty($request->id) && $praktek->gambar != '') {
				if (file_exists('uploads/praktek/' . $praktek->gambar)) {
					unlink('uploads/praktek/' . $praktek->gambar);
				}
			}
			$ukuranFile1 = filesize($request->gambar);
			if ($ukuranFile1 <= 500000) {
				$ext_foto1 = $request->gambar->getClientOriginalExtension();
				$filename1 = "Praktek" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/praktek/';
				$proses1 = $request->gambar->move($temp_foto1, $filename1);
				$praktek->gambar = $filename1;
			} else {
				$file1 = $_FILES['gambar']['name'];
				$ext_foto1 = $request->gambar->getClientOriginalExtension();
				if (!empty($file1)) {
					$direktori1 = 'uploads/praktek/'; //tempat upload foto
					$name1 = 'gambar'; //name pada input type file
					$namaBaru1 = "Praktek" . date('Ymd-His'); //name pada input type file
					$quality1 = 50; //konversi kualitas gambar dalam satuan %
					$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
				}
				$praktek->gambar = $namaBaru1 . "." . $ext_foto1;
			}
		}
		$praktek->save();
		if ($praktek) {
			return ['code' => 200, 'status' => 'success', 'Berhasil.'];
		} else {
			return ['code' => 201, 'status' => 'error', 'Gagal.'];
		}
	}

	public function delete(Request $request)
	{
		$data = PraktekBaikGuru::where('id_praktek_baik_guru', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}

	public function aktif(Request $request)
	{
		$rules = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'Id Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);

		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		$berita = PraktekBaikGuru::find($request->id);
		$berita->status = !$berita->status;

		if (!$berita->save()) {
			return response()->json(['message' => 'Gagal'], 201);
		}
		if ($berita->status) {
			return Help::resMsg('Berita Berhasil Diaktifkan', 200);
		}
		return Help::resMsg('Berita Berhasil Dinonaktifkan', 200);
	}
}
