<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use DataTables;

class DataSiswaController extends Controller
{
	public function main(Request $request)
	{
		$data = Siswa::orderBy('id_siswa', 'DESC')
			->has('kelas_siswa')
			->with('kelas_siswa', function ($q) {
				$q->has('kelas')->with('kelas');
			})
			->get();
		if ($request->ajax()) {
			return DataTables::of($data)->addIndexColumn()->addColumn('nisn', function ($row) {
				return '-';
			})->addColumn('kelas', function ($row) {
				return $row->kelas_siswa->kelas->nama_kelas;
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
		$data['siswa'] = Siswa::find($request->id);
		$content = view('main.content.admin.master.data-siswa.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
}
