<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalGuru;
use Illuminate\Http\Request;
use DataTables, Help, CLog, Auth;

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
                $html = "<button onclick='tambahMateri($row->id_jurnal_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
                $html .= "<button onclick='hapusMateri($row->id_jurnal_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
                return $html;
            })->rawColumns(['actions'])->toJson();
        }
        return view('main.content.guru.jurnal.main');
    }

    public function add(Request $request)
    {
        $data['jurnal'] = JurnalGuru::find($request->id);
        $content = view('main.content.guru.jurnal.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
}
