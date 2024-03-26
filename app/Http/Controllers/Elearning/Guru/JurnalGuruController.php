<?php

namespace App\Http\Controllers\Elearning\Guru;

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

    public function save(Request $request)
    {
        $params = [
            'jurnal' => 'required',
            'tanggal_upload' => 'required',
        ];
        $message = [
            'jurnal.required' => 'Jurnal harus diisi',
            'tanggal_upload.required' => 'Tanggal Upload harus diisi',
        ];
        $validator = Validator::make($request->all(), $params, $message);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $val) {
                $msg = $val[0]; # Get validation messages, only one
                break;
            }
            return ['status' => 'fail', 'message' => $msg];
        }

        if (!empty($request->id)) {
            if(!$jurnal=JurnalGuru::where('id_jurnal',$request->id)->first()) {
                return ['status' => 'fail', 'message' => 'Gagal menyimpan, data tidak ditemukan'];
            }
        } else {
            $jurnal = new JurnalGuru;
        }
        $jurnal->jurnal = $request->jurnal;
        $jurnal->tanggal_upload = date('Y-m-d H:i:s',strtotime($request->tanggal_upload));
        if (!$jurnal->save()) {
            return ['status' => 'fail', 'message' => 'Gagal menyimpan, Coba Lagi!'];
        }
        return ['status' => 'success', 'message' => 'Berhasil menyimpan Jurnal'];
    }
}
