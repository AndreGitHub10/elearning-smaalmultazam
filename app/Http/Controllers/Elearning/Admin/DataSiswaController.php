<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Users;
use Illuminate\Http\Request;
use DataTables, CLog, Help, DB;
use Illuminate\Support\Facades\Validator;

class DataSiswaController extends Controller
{
	public function main(Request $request)
	{
		$data = Siswa::orderBy('id_siswa', 'DESC')
			// ->has('kelas_siswa')
			->with('kelas_siswa', function ($q) {
				$q->has('kelas')->with('kelas');
			})
			->get();
		if ($request->ajax()) {
			return DataTables::of($data)->addIndexColumn()->addColumn('nisn', function ($row) {
				return '-';
			})->addColumn('kelas', function ($row) {
				return $row->kelas_siswa ? $row->kelas_siswa->kelas->nama_kelas : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahSiswa($row->id_siswa)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusSiswa($row->id_siswa)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'foto'])->toJson();
		}
		return view('main.content.admin.master.data-siswa.main');
	}

	public function add(Request $request)
	{
		$data['siswa'] = Siswa::join('users', 'users.id', '=', 'siswas.users_id')->find($request->id);
		$content = view('main.content.admin.master.data-siswa.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'nama' => 'required',
			'no_induk' => 'required',
			'nisn' => 'required',
			'tahun_masuk' => 'required',
			'tempat_lahir' => 'required',
			'tanggal_lahir' => 'required',
			'jenis_kelamin' => 'required',
			'nama_ayah' => 'required',
			'nama_ibu' => 'required',
			'no_telp' => 'required',
			'status' => 'required',
			'alamat' => 'required',
		];
		$message = [
			'nama.required' => 'Nama harus diisi',
			'no_induk.required' => 'No Induk harus diisi',
			'nisn.required' => 'NISN harus diisi',
			'tahun_masuk.required' => 'Tahun Masuk Pertanyaan harus diisi',
			'tempat_lahir.required' => 'Tempat Lahir harus diisi',
			'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
			'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
			'nama_ayah.required' => 'Nama Ayah harus diisi',
			'nama_ibu.required' => 'Nama Ibu harus diisi',
			'no_telp.required' => 'No Telepon harus diisi',
			'status.required' => 'Status harus diisi',
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
		try {
			DB::beginTransaction();
			if (!empty($request->id)) {
				if (!$siswa = Siswa::where('id_siswa', $request->id)->first()) {
					return ['status' => 'fail', 'message' => 'Gagal mengupdate, data siswa tidak ditemukan'];
				}
			} else {
				$siswa = new Siswa;
			}
			$siswa->nama = $request->nama;
			$siswa->nisn = $request->nisn;
			$siswa->thn_masuk = $request->tahun_masuk;
			$siswa->tmp_lahir = $request->tempat_lahir;
			$siswa->tgl_lahir = $request->tanggal_lahir;
			$siswa->gender = $request->jenis_kelamin;
			$siswa->nama_ayah = $request->nama_ayah;
			$siswa->nama_ibu = $request->nama_ibu;
			$siswa->no_tlp = $request->no_telp;
			$siswa->status = $request->status ? 'Siswa Aktif' : 'Bukan Siswa Aktif';
			$siswa->alamat = $request->alamat;
			$siswa->foto = ' ';

			if (isset($siswa->users_id)) {
				if (!$user = Users::where('id', $siswa->users_id)->first()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan data, data user tidak ditemukan'];
				}
				if (Users::where('no_induk', $request->no_induk)->where('id', '!=', $request->users_id)->first()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan data, data user tidak ditemukan'];
				}
			} else {
				if (Users::where('no_induk', $request->no_induk)->first()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan data, Nomor Induk telah digunakan, silahkan menggunakan nomor lain'];
				}
				$user = new Users;
			}
			$user->email = $request->no_induk;
			$user->no_induk = $request->no_induk;
			$user->password = bcrypt($request->no_induk);
			$user->level_user = 4;
			$user->active = $request->status;
			if (!$user->save()) {
				DB::rollback();
				return ['status' => 'fail', 'message' => 'Gagal menyimpan data'];
			}
			if (!isset($siswa->users_id)) {
				$siswa->users_id = $user->id;
			}
			if (!$siswa->save()) {
				DB::rollback();
				return ['status' => 'fail', 'message' => 'Gagal menyimpan data'];
			}
			DB::commit();
			return ['status' => 'success', 'message' => 'Berhasil menyimpan data'];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge([
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]);
			CLog::catchError($request);
			return Help::resMsg(null, 500);
		}
	}
}
