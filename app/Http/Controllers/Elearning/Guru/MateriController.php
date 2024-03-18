<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

# Models
use App\Models\MateriShare;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;

# Helpers
use Auth, DataTables;

class MateriController extends Controller
{
	public function main(Request $request)
	{
		$data = MateriShare::orderBy('tanggal_upload', 'DESC')
			// ->where('user_id', Auth::user()->id)
			->with('mata_pelajaran')
			->has('mata_pelajaran')
			->get();
		if ($request->ajax()) {
			return DataTables::of($data)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->tanggal_upload));
			})->addColumn('nama_mapel', function ($row) {
				$mapel = '';
				if (strlen($row->mata_pelajaran->nama_mapel) > 20) {
					$mapel = substr($row->mata_pelajaran->nama_mapel, 0, 20) . '...';
				} else {
					$mapel = $row->mata_pelajaran->nama_mapel;
				}
				return $mapel;
			})->addColumn('judul_materi', function ($row) {
				$judul = '';
				if (strlen($row->judul) > 20) {
					$judul = substr($row->judul, 0, 20) . '...';
				} else {
					$judul = $row->judul;
				}
				return $judul;
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='aktifMateri($row->id_materi)' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></button>";
				$html .= "<button onclick='tambahMateri($row->id_materi)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMateri($row->id_materi)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.guru.materi.main');
	}

	public function add(Request $request)
	{
		$user_id = Auth::user()->id;
		$data['materi'] = MateriShare::find($request->id);
		// $data['kelas'] = Kelas::where('guru_id',Auth::user()->user_id)->get();
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		$content = view('main.content.guru.materi.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
}
