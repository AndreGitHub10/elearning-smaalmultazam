<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PraktekBaikGuru;
use Illuminate\Http\Request;

class PraktekBaikGuruApiController extends Controller
{
    public function main($id='') {
		if($id) {
			return PraktekBaikGuru::getPraktekBaikGuruDetail($id);
		} else {
			return PraktekBaikGuru::getPraktekBaikGuruPaginate();
		}
    }
}
