@extends('main.layouts.index')

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Mata Pelajaran</th>
								<th>Judul Soal</th>
								<th>Tanggal Mulai</th>
								<th>Tanggal Selesai</th>
								<th>Jumlah Soal</th>
								<th>Nilai KKM</th>
								<th>Jenis Soal</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Nama Mata Pelajaran</td>
								<td>Judul Soal</td>
								<td>Tanggal Mulai</td>
								<td>Tanggal Selesai</td>
								<td>Jumlah Soal</td>
								<td>Nilai KKM</td>
								<td>Jenis Soal</td>
								<td><button class="btn btn-success btnKerjakan"><i class="bx bx-loader-alt"></i>Revisi</button></td>
							</tr>
							<tr>
								<td>2</td>
								<td>Nama Mata Pelajaran</td>
								<td>Judul Soal</td>
								<td>Tanggal Mulai</td>
								<td>Tanggal Selesai</td>
								<td>Jumlah Soal</td>
								<td>Nilai KKM</td>
								<td>Jenis Soal</td>
								<td><button class="btn btn-primary btnKerjakan"><i class="bx bx-key"></i>Kerjakan</button></td>
							</tr>
							<tr>
								<td>3</td>
								<td>Nama Mata Pelajaran</td>
								<td>Judul Soal</td>
								<td>Tanggal Mulai</td>
								<td>Tanggal Selesai</td>
								<td>Jumlah Soal</td>
								<td>Nilai KKM</td>
								<td>Jenis Soal</td>
								<td><button class="btn btn-primary btnKerjakan" disabled><i class="bx bx-key"></i>Kerjakan</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="pendahuluanModal" tabindex="-1" aria-labelledby="pendahuluanModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pendahuluanModalLabel">Pendahuluan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Waktu pengerjaan 60 menit</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnMulai">MULAI KERJAKAN</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeKerjakan = "{{route('elearning.main.kerjakan')}}"
	
	var myModal = new bootstrap.Modal(document.getElementById('pendahuluanModal'), {
		keyboard: false
	})
	$(document).ready(()=>{
		$('#dataTable').DataTable({
			scrollX:true
		})
	})
	
	$('.btnKerjakan').click((e)=>{
		e.preventDefault()
		myModal.show()
	})
	
	$('.btnMulai').click((e)=>{
		e.preventDefault()
		window.location = routeKerjakan
		// console.log(routeKerjakan);
	})

</script>
@endpush