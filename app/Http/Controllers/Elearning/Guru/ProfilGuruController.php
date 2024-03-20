<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilGuruController extends Controller
{
    public function main() {
        return view('main.content.guru.profil-guru.main');
    }
}
