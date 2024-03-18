<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function main() {
        return view('main.content.dashboard.main');
    }
}
