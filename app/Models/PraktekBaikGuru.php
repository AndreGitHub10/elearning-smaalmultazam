<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktekBaikGuru extends Model
{
    use HasFactory;
    protected $table = 'praktek_baik_guru';
    protected $primaryKey = 'id_praktek_baik_guru';
}
