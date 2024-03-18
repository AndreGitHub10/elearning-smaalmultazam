@php
$title = 'Logo';
@endphp

<div class="sidebar-wrapper sds" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{isset($identitas)?asset('uploads/identitas/'.$identitas->logo_kiri):asset('admin/assets/images/logo-profile.png')}}" width="30" alt="logo icon">
		</div>
		<div>
			<h5 class="logo-text" style="font-size: 14px;">{{isset($identitas)?$identitas->nama_web:'SMAS AL MULTAZAM'}}</h5>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-chevron-left-circle'></i></div>
	</div>
	<ul class="metismenu" id="menu">
		@auth
		@if (Auth::User()->level_user=='4') <!-- Siswa -->
			<li class="{{ ($title == 'Dashboard') ? 'mm-active' : ''}}">
				<a href="{{route('elearning.dashboard.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Materi Elearning') ? 'mm-active' : ''}}">
				<a href="{{route('elearning.materiElearning.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Materi Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Uji Kompetensi') ? 'mm-active' : ''}}">
				<a href="{{route('elearning.ujiKompetensi.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Uji Kompetensi</div>
				</a>
			</li>
			<li class="{{ ($title == 'Data Nilai') ? 'mm-active' : ''}}">
				<a href="{{route('elearning.dataNilai.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Data Nilai</div>
				</a>
			</li>
		@endif

		@if (Auth::User()->level_user=='3') <!-- Guru -->
			<li class="{{ ($title == 'Dashboard') ? 'mm-active' : ''}}">
				<a href="{{route('dashboard')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>
			<li class="{{ ($title == 'Profil Guru') ? 'mm-active' : ''}}">
				<a href="{{route('guru.profilGuru.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-globe'></i>
					</div>
					<div class="menu-title">Profil Guru</div>
				</a>
			</li>
			<li class="{{ (in_array($title, ['Materi','Soal Tulis','Soal Listening','Pengerjaan Siswa','Nilai Siswa'])) ? 'mm-active' : ''}}">
				<a href="javascript:;" class="has-arrow">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-folder'></i>
					</div>
					<div class="menu-title">Elearning</div>
				</a>
				<ul>
					<li class="{{ ($title == 'Materi') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.materi.main')}}"><i class="bx bx-radio-circle"></i>Materi</a>
					</li>
					<li class="{{ ($title == 'Soal Tulis') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.soalTulis.main')}}"><i class="bx bx-radio-circle"></i>Soal Tulis</a>
					</li>
					<li class="{{ ($title == 'Soal Listening') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.soalTulis.main')}}"><i class="bx bx-radio-circle"></i>Soal Listening</a>
					</li>
					<li class="{{ ($title == 'Pengerjaan Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('main.menuUtama.prestasiSiswa.main')}}"><i class="bx bx-radio-circle"></i>Pengerjaan Siswa</a>
					</li>
					<li class="{{ ($title == 'Nilai Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('main.menuUtama.pengumuman.main')}}"><i class="bx bx-radio-circle"></i>Nilai Siswa</a>
					</li>
				</ul>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Dokumen') ? 'mm-active' : ''}}">
				<a href="{{route('main.sliderGambar.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Dokumen</div>
				</a>
			</li>
			<li class="{{ ($title == 'Jurnal Guru') ? 'mm-active' : ''}}">
				<a href="{{route('main.sliderGambar.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Jurnal Guru</div>
				</a>
			</li>
		@endif

		@if (Auth::User()->level_user=='2') <!-- Admin Elearning -->
			<li class="{{ ($title == 'Dashboard') ? 'mm-active' : ''}}">
				<a href="{{route('dashboard')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>
			<li class="{{ (in_array($title, ['Tahun Ajaran','Data Guru','Data Kelas','Data Siswa','Mata Pelajaran'])) ? 'mm-active' : ''}}">
				<a href="javascript:;" class="has-arrow">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-folder'></i>
					</div>
					<div class="menu-title">Data Master</div>
				</a>
				<ul>
					<li class="{{ ($title == 'Tahun Ajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.tahunAjaran.main')}}"><i class="bx bx-radio-circle"></i>Tahun Ajaran</a>
					</li>
					<li class="{{ ($title == 'Data Guru') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataGuru.main')}}"><i class="bx bx-radio-circle"></i>Data Guru</a>
					</li>
					<li class="{{ ($title == 'Data Kelas') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataKelas.main')}}"><i class="bx bx-radio-circle"></i>Data Kelas</a>
					</li>
					<li class="{{ ($title == 'Data Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataSiswa.main')}}"><i class="bx bx-radio-circle"></i>Data Siswa</a>
					</li>
					<li class="{{ ($title == 'Mata Pelajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.mataPelajaran.main')}}"><i class="bx bx-radio-circle"></i>Mata Pelajaran</a>
					</li>
				</ul>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Materi Elearning') ? 'mm-active' : ''}}">
				<a href="{{route('materiElearning.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Materi Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Soal Elearning') ? 'mm-active' : ''}}">
				<a href="{{route('soalElearning.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Soal Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Pengerjaan Siswa') ? 'mm-active' : ''}}">
				<a href="{{route('pengerjaanSiswa.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Pengerjaan Siswa</div>
				</a>
			</li>
			<li class="{{ ($title == 'Data Nilai') ? 'mm-active' : ''}}">
				<a href="{{route('dataNilai.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Data Nilai</div>
				</a>
			</li>
		@endif
		@endauth
	</ul>
	<!--end navigation-->
</div>
