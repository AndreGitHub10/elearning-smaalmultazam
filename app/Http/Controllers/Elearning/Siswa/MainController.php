<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

# Models
use App\Models\JawabanSiswa;
use App\Models\Pertanyaan;
use App\Models\Soal;

class MainController extends Controller{
	public function kerjakan(Request $request){
		$pertanyaans = Pertanyaan::selectRaw("
				id_pertanyaan,
				(case when (pertanyaan.pertanyaan_text='' or pertanyaan.pertanyaan_text is null) then false else true end) as pertanyaan_text
			")->
			with(['pilihan_jawaban', 'pertanyaan_file'])->
			where('soal_id', $request->id_soal)->
			get();
		return view('main.content.siswa.soal.lembar-kerja',compact('pertanyaans'));
	}

	public function store(Request $request){
		if(!($soal = Soal::where('id_soal',$request->id)->first())){
			return response()->json([
				'metadata' => [
					'code' => 204,
					'message' => 'Soal tidak ditemukan'
				],
			]);
		}
		if($jawaban = JawabanSiswa::where('soal_id',$request->id)->first()){ # Jawaban sudah dibuat
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Data berhasil ditemukan'
				],
				'response' => $jawaban
			]);
		}
		
		$jawaban = new JawabanSiswa;
		// $jawaban->siswa_id = ;
		// $jawaban->soal_id = ;
		// $jawaban->jawaban_siswa = ;
		// $jawaban->save();
		return response()->json([
			'metadata' => [
				'code' => 200,
				'message' => 'Data berhasil ditemukan'
			],
			'response' => $jawaban
		]);
	}
}
