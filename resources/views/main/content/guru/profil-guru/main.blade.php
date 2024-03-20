@extends('main.layouts.index')

@php
$tambah = true;
@endphp

@push('style')
<style>
	.gradient-green-yellow {
		background-color: #45ab73;
		background-image: linear-gradient(74deg, #45ab73 0%, #e4e07f 75%, #ffffff 100%);
		color: #ffffff;
	}
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if ($tambah)
				Tambah
				@else
				Edit
				@endif
				Profil Guru
			</div>
			<div class="card-body">
				<form id="formAgenda">
					<div class="row">
						<div class="col-4">
							<div class="mb-3">
								<label for="nama" class="form-label">Nama Lengkap *</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap">
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3">
								<label for="nip" class="form-label">NIP</label>
								<input type="text" class="form-control" name="nip" id="nip" placeholder="123xxxxx">
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3">
								<label for="foto" class="form-label">Foto Guru *</label>
								<input type="file" class="form-control" name="foto" id="foto">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="row p-2">
								<div class="col-6 p-2 gradient-green-yellow mb-2">
									<label for="">Tugas Utama</label>
								</div>
								<div class="col-6"></div>
								<div class="col-5">
									<div class="mb-3">
										<label for="kelas" class="form-label">Kelas</label>
										<select name="kelas[]" id="kelas" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-5">
									<div class="mb-3">
										<label for="mapel" class="form-label">Mapel</label>
										<select name="mapel[]" id="mapel" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-2 pt-4">
									<button type="button" class="btn btn-success"><i class='bx bx-plus'></i></button>
								</div>
								<div class="p-1">
									<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTableMapel">
										<thead>
											<tr>
												<th>No</th>
												<th>Kelas</th>
												<th>Mapel</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>12345</td>
												<td>Hari Sumpah Pemuda</td>
												<td>
													{{-- <button class="btn btn-dark btn-purple p-2"><i class='bx bx-edit-alt mx-1'></i></button> --}}
													<button class="btn btn-danger p-2"><i class='bx bx-trash mx-1'></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="row p-2">
								<div class="col-6 p-2 gradient-green-yellow mb-2">
									<label for="">Tugas Tambahan</label>
								</div>
								<div class="col-6"></div>
								<div class="col-10">
									<div class="mb-3">
										<label for="tugas" class="form-label">Nama Tugas</label>
										<select name="tugas[]" id="tugas" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-2 pt-4">
									<button type="button" class="btn btn-success"><i class='bx bx-plus'></i></button>
								</div>
								<div class="p-1">
									<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTableMapel">
										<thead>
											<tr>
												<th>No</th>
												<th>Tugas Tambahan</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Hari Sumpah Pemuda</td>
												<td>
													{{-- <button class="btn btn-dark btn-purple p-2"><i class='bx bx-edit-alt mx-1'></i></button> --}}
													<button class="btn btn-danger p-2"><i class='bx bx-trash mx-1'></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="d-flex gap-2">
						<button class="btn btn-secondary px-4">KEMBALI</button>
						<button class="btn btn-primary px-4">SIMPAN</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
	})
</script>
@endpush