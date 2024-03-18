<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
	use HasFactory;
	protected $table = "gurus";
	protected $primaryKey = "id_guru";

	public $timestamps = false;

	public function kelas_mapel()
	{
		return $this->hasMany(KelasMapel::class, 'guru_id', 'id_guru');
	}

	public function kelas()
	{
		return $this->hasMany(Kelas::class, 'guru_id', 'id_guru');
	}

	public function soal()
	{
		return $this->hasMany(Soal::class, 'user_id', 'users_id');
	}
}
