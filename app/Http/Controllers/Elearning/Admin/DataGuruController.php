<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use DataTables;

class DataGuruController extends Controller
{
	public function main(Request $request)
	{
		$data = Guru::orderBy('id_guru', 'DESC')
			->get();
		if ($request->ajax()) {
			return DataTables::of($data)->addIndexColumn()->addColumn('foto', function ($row) {
				return '<a>Lihat Foto</a>';
			})->addColumn('tugas_utama', function ($row) {
				return '-';
			})->addColumn('tugas_tambahan', function ($row) {
				return '-';
			})->addColumn('status', function ($row) {
				return 'aktif';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahGuru($row->id_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusGuru($row->id_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'foto'])->toJson();
		}
		return view('main.content.admin.master.data-guru.main');
	}

	public function add(Request $request)
	{
		$data['guru'] = Guru::find($request->id);
		$content = view('main.content.admin.master.data-guru.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
}
