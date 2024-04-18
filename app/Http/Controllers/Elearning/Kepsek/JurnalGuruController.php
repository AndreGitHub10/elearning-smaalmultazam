<?php

namespace App\Http\Controllers\Elearning\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\JurnalGuru;
use Illuminate\Http\Request;
use DataTables, Help, CLog, Auth;
use Illuminate\Support\Facades\Validator;

class JurnalGuruController extends Controller
{
	public function main(Request $request)
	{
		$data = JurnalGuru::orderBy('tanggal_upload', 'DESC')
			->get();
		if ($request->ajax()) {
			return DataTables::of($data)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->tanggal_upload));
			})->addColumn('jurnal', function ($row) {
				$mapel = '';
				if (strlen($row->jurnal) > 20) {
					$mapel = substr($row->jurnal, 0, 20) . '...';
				} else {
					$mapel = $row->jurnal;
				}
				return $mapel;
			})->addColumn('actions', function ($row) {
				$html = '';
				$html .= "<button onclick='tambahMateri($row->id_jurnal_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.kepsek.jurnal.main');
	}

	public function add(Request $request)
	{
		$data['jurnal'] = JurnalGuru::find($request->id);
		if ($request->id == '') {
			$data['jurnal'] = JurnalGuru::whereDate('tanggal_upload', date('Y-m-d'))->where('user_id', Auth::user()->id)->first();
		}
		$content = view('main.content.kepsek.jurnal.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
}
