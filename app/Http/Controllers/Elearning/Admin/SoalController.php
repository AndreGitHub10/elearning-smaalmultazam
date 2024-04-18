<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use Illuminate\Http\Request;
use DataTables;

class SoalController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Soal';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$soal = Soal::orderBy('id_soal', 'DESC')
				->with('mata_pelajaran')
				->with('guru')
				->get();
			return DataTables::of($soal)->addIndexColumn()->addColumn('tanggal', function ($row) {
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
				$html = "<button onclick='lihat($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'tanggal'])->toJson();
		}
		return view('main.content.admin.soal.main',$data);
	}
}
