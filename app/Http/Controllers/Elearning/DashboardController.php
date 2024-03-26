<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{

    public function __construct()
    { }
    public function main()
    {
        if (Auth::User()->level_user == '4') {
            return redirect()->route('siswa.dashboard');
        }
        return view('main.content.dashboard.main');
    }
}
