<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<link rel="shortcut icon" href="{{isset($identitas)?asset('uploads/identitas/'.$identitas->favicon):asset('assets/img/logo/logo.png')}}" type="image/x-icon">
	<title>ELEARNING - LEMBAR KERJA SISWA</title>
	@include('main.include.style') <!--importCSS-->
</head>
<body class="bg-white">
	<div class="container">
		<div class="row">
			<div class="col-12 d-flex">
				<div class="p-1 mx-auto d-grid text-center fw-bold" style="line-height: 0.1">
					<img src="{{ asset('admin/assets/images/avatars/no-avatar.png')}}" class="rounded-circle mx-auto my-4" alt="..." width="70">
					<p>NISN - Nama Lengkap Siswa</p>
					<p>Kelas Saat ini - Nama Mata Pelajaran</p>
					<p>Judul Soal</p>
				</div>
			</div>
			<hr>
			<div class="col-12">
				<table class="tblSoal" border="0">
					<tbody>
						<tr>
							<td class="nomor align-baseline"><span>1.</span></td>
							<td>
								<p>Perhatikan gambar berikut ini!</p>
								<p>Pada ekosistem terjadi daur biogiokimia karena setiap komponen saling berinteraksi. Peran komponen X dalam daur biogiokimia tersebut adalah...</p>
								<img src="{{ asset('uploads/elearning/images/contoh-soal.png') }}" alt="soal" class="img-fluid" width="300">
								<div class="pilihan d-flex flex-column my-4">
									@php
										$pilihan = ['A','B','C','D','E'];
									@endphp
									@foreach ($pilihan as $item)
									<div class="d-flex gap-2">
										<input type="radio" name="jawaban" id="jawaban" value="">
										<span>{{$item}}. Pilihan {{$item}}</span>
									</div>
									@endforeach
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<hr>
			<div class="col-12 d-flex mb-4">
				<div class="p-1 mx-auto">
					<button class="btn btn-secondary btnPrev d-inline">Sebelumnya</button>
					<button class="btn btn-primary btnNext">Selanjutnya</button>
					<button class="btn btn-danger btnSelesai">Selesai</button>
				</div>
			</div>
		</div>
	</div>
	@include('main.include.script') <!--importJavaScript-->
	<script>
		$(document).ready(()=>{
			$('.btnSelesai').hide()
		})
		$('.btnNext').click((e)=>{
			e.preventDefault()
			$('.btnSelesai').show()
			$('.btnNext').hide()
		})
		$('.btnPrev').click((e)=>{
			e.preventDefault()
			$('.btnSelesai').hide()
			$('.btnNext').show()
		})
	</script>
</body>
</html>