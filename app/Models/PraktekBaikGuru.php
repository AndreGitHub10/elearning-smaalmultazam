<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktekBaikGuru extends Model
{
    use HasFactory;
    protected $table = 'praktek_baik_guru';
    protected $primaryKey = 'id_praktek_baik_guru';
    protected $connection = 'mysql';

    public static function getPraktekBaikGuruPaginate(){
		return PraktekBaikGuru::selectRaw('id_praktek_baik_guru as id_berita, isi, judul, gambar')
			->where('status', true)
			->orderBy('id_praktek_baik_guru', 'DESC')
			->paginate(8);
	}

	public static function getPraktekBaikGuruDetail($id) {
		return PraktekBaikGuru::selectRaw('id_praktek_baik_guru as id_berita, isi, judul, gambar')
            ->where('id_praktek_baik_guru',$id)
			->where('status', true)
			->first();
	}
}
