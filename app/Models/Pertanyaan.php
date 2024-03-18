<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Pertanyaan extends Model
{
	use HasFactory;


	protected $table = "pertanyaan";
	protected $primaryKey = "id_pertanyaan";

	public function pilihan_jawaban()
	{
		return $this->hasMany(PilihanJawaban::class, 'pertanyaan_id', 'id_pertanyaan');
	}

	public function pertanyaan_file()
	{
		return $this->hasMany(PertanyaanFile::class, 'pertanyaan_id', 'id_pertanyaan');
	}

	public static function generatePertanyaan($request)
	{
		if (count(Pertanyaan::where('soal_id', $request->id_soal)->get())) {
			return false;
		}
		for ($i = 0; $i < (int) ($request->jumlah_soal); $i++) {
			$pertanyaan = new Pertanyaan;
			$pertanyaan->soal_id = $request->id_soal;
			$pertanyaan->nomor = $i + 1;
			$pertanyaan->pertanyaan_text = $request->pertanyaan_text;
			if (!$pertanyaan->save()) {
				return false;
			}
		}
		return true;
	}
}