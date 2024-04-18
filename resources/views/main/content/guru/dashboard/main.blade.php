@extends('main.layouts.index')

@section('content')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
		<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
	</symbol>
</svg>
@if ($jurnal=='')
<div class="row">
	<div class="col-12">
		<div class="alert alert-danger d-flex align-items-center" role="alert">
			<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
			<div>
				Anda belum mengisi jurnal hari ini (<a href="{{route('guru.jurnal.main')}}">Klik disini untuk mengisi!</a>)
			</div>
		</div>
	</div>
</div>
@else
<div class="row">
	<div class="col-12">
		<div class="alert alert-success d-flex align-items-center" role="alert">
			<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
			<div>
				Anda sudah mengisi jurnal hari ini, Keren!
			</div>
		</div>
	</div>
</div>
@endif
	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-success">
							<h6 class="my-1">{{$materi}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Materi</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-secondary">
							<h6 class="my-1">{{$soal}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Soal</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-primary">
							<h6 class="my-1">{{$mapel}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Mapel Diampuh</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-warning">
							<h6 class="my-1">{{$praktek}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Praktek Baik Guru</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection