<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Elearning\Admin\DataGuruController;
use App\Http\Controllers\Elearning\Admin\DataKelasController;
use App\Http\Controllers\Elearning\Admin\DataSiswaController;
use App\Http\Controllers\Elearning\Admin\MataPelajaranController;
use App\Http\Controllers\Elearning\Admin\MateriElearningController;
use App\Http\Controllers\Elearning\Admin\TahunAjaranController;
use App\Http\Controllers\Elearning\DashboardController;
use App\Http\Controllers\Elearning\Guru\SoalTulisController;
use App\Http\Controllers\Error\ErrorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect()->route('dashboard');
});

# START ERROR
Route::controller(ErrorController::class)
	->prefix('error')
	->as('error.')->group(function () {
		Route::get('401', 'error401')->name('401');
	});
# END ERROR

# START AUTH
Route::controller(AuthController::class)
	->as('auth.')->group(function () {
		Route::get('login', 'login')->name('login');
		Route::post('do-login', 'doLogin')->name('doLogin');
		Route::get('logout', 'logout')->name('logout');
	});
# END AUTH

# START MIDDLEWARE AUTH
Route::middleware(['auth'])->group(function () {
	Route::get('dashboard', [DashboardController::class, 'main'])->name('dashboard');

	# START MIDDLEWARE ADMIN
	Route::middleware(['adminElearning'])
		->prefix('admin')
		->as('admin.')
		->group(function () {

			# START MASTER > TAHUN AJARAN
			Route::controller(TahunAjaranController::class)
				->prefix('tahun-ajaran')
				->as('tahunAjaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > TAHUN AJARAN

			# START MASTER > DATA GURU
			Route::controller(DataGuruController::class)
				->prefix('data-guru')
				->as('dataGuru.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > DATA GURU

			# START MASTER > DATA KELAS
			Route::controller(DataKelasController::class)
				->prefix('data-kelas')
				->as('dataKelas.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > DATA KELAS

			# START MASTER > DATA SISWA
			Route::controller(DataSiswaController::class)
				->prefix('data-siswa')
				->as('dataSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > DATA SISWA

			# START MASTER > MATA PELAJARAN
			Route::controller(MataPelajaranController::class)
				->prefix('mata-pelajaran')
				->as('mataPelajaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > MATA PELAJARAN

			# START MASTER > MATERI ELEARNING
			Route::controller(MateriElearningController::class)
				->prefix('materi-elearning')
				->as('materiElearning.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > MATERI ELEARNING
		});
	# END MIDDLEWARE ADMIN

	# START MIDDLEWARE GURU
	Route::middleware(['guru'])
		->prefix('guru')
		->as('guru.')
		->group(function () {
			# START PROFIL GURU
			Route::controller(ProfilGuruController::class)
				->prefix('profil-guru')
				->as('profilGuru.')
				->group(function () {
					Route::get('/', 'mainGuru')->name('main');
				});
			# END PROFIL GURU

			# START MATERI
			Route::controller(MateriController::class)
				->prefix('materi')
				->as('materi.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
				});
			# END MATERI

			# START SOAL TULIS
			Route::controller(SoalTulisController::class)
				->prefix('soal-tulis')
				->as('soalTulis.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/create-soal', 'createSoal')->name('createSoal');
					Route::post('/pertanyaan-form', 'pertanyaanForm')->name('pertanyaanForm');
				});
			# END SOAL TULIS
		});
	# END MIDDLEWARE GURU

});
# END MIDDLEWARE AUTH
