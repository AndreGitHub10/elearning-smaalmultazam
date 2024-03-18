<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UjiKompetensiController extends Controller
{
    public function main()
    {
        return view('main.content.siswa.uji-kompetensi.main');
    }
}
