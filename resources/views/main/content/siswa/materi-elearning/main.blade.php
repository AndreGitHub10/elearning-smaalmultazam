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
								<th>Judul Materi</th>
								<th>Dibuat Oleh</th>
								<th>Tanggal Upload</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Nama Mata Pelajaran</td>
								<td>Judul Materi</td>
								<td>Nama Guru Yang Membuat</td>
								<td>22/02/2022</td>
								<td>
									<button class="btn btn-primary btnDownload"><i class="bx bx-download mx-auto"></i></button>
									<button class="btn btn-success btnLihat"><i class="bx bx-book-open mx-auto"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
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
	$(document).ready(()=>{
		$('#dataTable').DataTable({
			scrollX:true
		})
	})
	
	$('.btnKerjakan').click((e)=>{
		e.preventDefault()
	})
	
	$('.btnMulai').click((e)=>{
		e.preventDefault()
		window.location = routeKerjakan
		// console.log(routeKerjakan);
	})

</script>
@endpush