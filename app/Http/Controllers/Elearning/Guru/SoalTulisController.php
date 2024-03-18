<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoalRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pertanyaan;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth, Help, CLog, DB, DataTables, GRes;

class SoalTulisController extends Controller
{
	public function main(Request $request)
	{
		if ($request->ajax()) {
			$data = Soal::orderBy('id_soal', 'DESC')
				->where('user_id', Auth::user()->id)
				->with('mata_pelajaran')
				->with('guru')
				->get();
			return DataTables::of($data)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('H:i:s d F Y', strtotime($row->mulai_pengerjaan)) . '<br>S/D<br>' . date('H:i:s d F Y', strtotime($row->selesai_pengerjaan));
			})->addColumn('nama_mapel', function ($row) {
				$mapel = '-';
				if ($row->mata_pelajaran) {
					if (strlen($row->mata_pelajaran->nama_mapel) > 20) {
						$mapel = substr($row->mata_pelajaran->nama_mapel, 0, 20) . '...';
					} else {
						$mapel = $row->mata_pelajaran->nama_mapel;
					}
				}
				return $mapel;
			})->addColumn('judul_soal', function ($row) {
				$judul = '';
				if (strlen($row->judul_soal) > 20) {
					$judul .= substr($row->judul_soal, 0, 20) . '...';
				} else {
					$judul .= $row->judul_soal ? $row->judul_soal : '-';
				}
				return $judul;
			})->addColumn('nama_guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='aktifSoal($row->id_soal)' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></button>";
				$html .= "<button onclick='tambahSoal($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusSoal($row->id_soal)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'tanggal'])->toJson();
		}
		return view('main.content.guru.soal-materi.main');
	}

	public function add(Request $request)
	{
		$user_id = Auth::user()->id;
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		$data['soal'] = Soal::where('id_soal', $request->id)->first();
		$content = view('main.content.guru.soal-materi.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function createSoal(SoalRequest $request)
	{
		DB::connection('elearning')->beginTransaction();
		try {
			if (!$soal = Soal::store($request)) {
				DB::connection('elearning')->rollback();
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			if (!$pertanyaan = Pertanyaan::generatePertanyaan($soal)) {
				DB::connection('elearning')->rollback();
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			DB::connection('elearning')->commit();
			return Help::resMsg("Berhasil menyimpan soal", 200);
		} catch (\Throwable $e) {
			DB::connection('elearning')->rollback();
			$logPayload['file'] = $e->getFile();
			$logPayload['message'] = $e->getMessage();
			$logPayload['line'] = $e->getLine();
			return $logPayload;
			CLog::catchError($request->merge(['log_payload' => $logPayload])); # Logging
			return Help::resMsg(null, 500);
		}
	}

	public function pertanyaanForm(Request $request)
	{
		$params = [
			'id_soal' => 'required',
		];
		$message = [
			'id_soal.required' => 'ID harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$data['soal'] = Soal::where('id_soal', $request->id_soal)->first()) {
			return ['status' => 'fail', 'message' => 'Soal tidak ditemukan'];
		}
		$data['pertanyaans'] = Pertanyaan::select('id_pertanyaan')->selectRaw("(case when (pertanyaan.pertanyaan_text='' or pertanyaan.pertanyaan_text is null) then false else true end) as pertanyaan_text")->with(['pilihan_jawaban', 'pertanyaan_file'])->where('soal_id', $request->id_soal)->get();
		if (isset($request->id_pertanyaan)) {
			if (!$data['pertanyaan'] = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->first()) {
				return ['status' => 'fail', 'message' => 'Pertanyaan tidak ditemukan'];
			}
			$data['pilihan_jawaban'] = PilihanJawaban::where('pertanyaan_id', $data['pertanyaan']->id_pertanyaan)->get();
			return ['status' => 'success', 'message' => 'Pertanyaan berhasil ditemukan', 'data' => $data];
		}
		$content = view('main.content.guru.soal-materi.lembar-soal', $data)->render();
		return ['status' => 'success', 'message' => 'Soal berhasil ditemukan', 'content' => $content];
	}
}
