<style>
	.filled-file {
		background-color: #d6d4ff;
	}
	.filled-pilihan {
		background-color: #f8facd;
	}
	.filled-jawaban {
		background-color: #cefacd;
	}
	.square-box-parent {
		width: 60px;
		height: 60px;
		display: flex;
	}
	.square-box-child {
		width: 20px;
		height: 20px;
		text-align: center;
	}
	#header-side-soal {
		background-color: #a8a8f7;
	}
	.select-pertanyaan-area:hover {
		box-shadow: 0 1rem 1rem rgba(25,255,255,.5)!important;
		transform: scale(1.1);
	}
	.form-check {
		padding-left: 0.5rem !important;
		min-width: 110px;
	}
	.file {
		width: 200px;
	}
	.map-soal {
		padding: 0;
		background-color: azure;
	}
	.btnTambahPilihan {
		color: #0000FF;
		border: dashed 2px #0000FF;
	}
	.spinner-div {
		position: absolute;
		top: 0;
		width: 100%;
		height: 100%;
		display: flex;
		background-color: rgba(255, 255, 255, 0.76);
	}
	.spinner-div span {
		display: ruby-text;
		margin: auto;
	}
</style>
@php
	$alphabet = range('A','Z');
	function jawabanBenar($pilihan) {
		$alphabet = range('A','Z');
		$j = 'X';
		foreach ($pilihan as $key => $value) {
			if ($value->benar) {
				$j = $alphabet[$key];
			}
		}
		return $j;
	}
@endphp
<div class="row">
	<div class="col-12">
		<form id="formPertanyaan">
			<div class="card">
				<div class="card-body row">
					<div class="col-12">
						<div class="mb-3">
							<label for="judul_soal" class="form-label">Judul Soal</label>
							<input type="text" class="form-control" name="judul_soal" id="judul_soal" placeholder="Judul Materi" @isset($soal) value="{{$soal->judul_soal}}" @endisset disabled>
						</div>
					</div>
					<hr>
					<div class="col-10 position-relative">
						<input type="hidden" name="id_pertanyaan" id="id_pertanyaan" value="">
						<div class="mb-3">
							<label for="pertanyaan_text" class="form-label">Pertanyaan *</label>
							<textarea type="text" name="pertanyaan_text" id="pertanyaan_text"></textarea>
						</div>
						<div class="spinner-div" id="loadingPertanyaan">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-2 map-soal position-relative">
						<div class="w-100 mb-3 shadow p-2 rounded" id="header-side-soal">
							<h5 id="totalSelesai" class="text-white"></h5>
						</div>
						<div class="overflow-y-scroll d-flex flex-wrap gap-1 w-100 pertanyaanMap" style="max-height: 400px;overflow-y:scroll;overflow-x:visible">
							@foreach ($pertanyaans as $p)
								<div class="shadow m-auto bg-white square-box-parent d-flex flex-wrap cursor-pointer select-pertanyaan-area" onclick="getPertanyaan('{{$p->id_pertanyaan}}')">
									<div class="text-center" style="width: 60px;height:40px"><h4>{{$loop->index+1}}</h4></div>
									<div class="square-box-child filled-pilihan">{{count($p->pilihan_jawaban)}}</div>
									<div class="square-box-child filled-jawaban @if(jawabanBenar($p->pilihan_jawaban)=='X') text-danger @endif">{{jawabanBenar($p->pilihan_jawaban)}}</div>
									<div class="square-box-child filled-file">{{count($p->pertanyaan_file)}}</div>
								</div>
							@endforeach
						</div>
						<div class="spinner-div" id="loadingMapNomor">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-12 position-relative">
						<label for="pilihan_jawaban" class="form-label">Pilihan Jawaban</label>
						<div class="mb-3" id="jawaban_area">
							<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">{{$alphabet[0]}}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[0][pilihan_text]" id="pilihan_text_0" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[0][file]" id="file_0" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="pilihan_jawaban[0][benar]" id="benar_0">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>
						</div>
						<button class="btn btn-default btnTambahPilihan"><i class='bx bx-plus-medical'></i> Tambah Pilihan Jawaban</button>
						<div class="spinner-div" id="loadingPilihanJawaban">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-12 mt-3">
						<button class="btn btn-primary btnSimpanPertanyaan">SIMPAN PERTANYAAN</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	var routeGetPertanyaan = "{{route('guru.soalTulis.pertanyaanForm')}}"
	var alphabet = ['A','B','C','D','E']
	$(()=>{
		var pertanyaans = {{Illuminate\Support\Js::from($pertanyaans)}}
		hitungSelesai(pertanyaans)
		if (pertanyaans.length==0) {
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Data pertanyaan tidak ditemukan',
				showConfirmButton: true,
			})
		} else {
			getPertanyaan(pertanyaans[0].id_pertanyaan)
		}
	})

	$('.btnTambahPilihan').click((e)=>{
		e.preventDefault()
		let jmlJawaban = $('.jawaban').length
		$('#jawaban_area').append(`<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[jmlJawaban]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[${jmlJawaban}][pilihan_text]" id="pilihan_text_${jmlJawaban}" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[${jmlJawaban}][file]" id="file_${jmlJawaban}" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="pilihan_jawaban[${jmlJawaban}][benar]" id="benar_${jmlJawaban}">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`)
		if (jmlJawaban>=4) {
			$('.btnTambahPilihan').hide()
		}
	})

	$('.btnSimpanPertanyaan').click((e)=>{
		e.preventDefault()
		console.log('aaa');
		simpanPertanyaan()
	})

	function simpanPertanyaan() {
		var data = new FormData($('#formPertanyaan')[0])
		var pertanyaan_text = CKEDITOR.instances.pertanyaan_text.getData();
		data.append('pertanyaan_text',pertanyaan_text);
		// $.post("{{route('guru.soalTulis.pertanyaanStore')}}",{data:data})
		// $.post("{{route('guru.soalTulis.pertanyaanStore')}}")
		$.ajax({
			type: "POST",
			url: "{{route('guru.soalTulis.pertanyaanStore')}}",
			data: data,
			processData: false,
			contentType: false,
		})
		console.log(data);
	}

	var pertanyaan_text = CKEDITOR.replace('pertanyaan_text', {
		// uiColor: '#CCEAEE'
		toolbarCanCollapse:false,
	});

	function autoSave() {
		
	}

	function hitungSelesai(pertanyaans) {
		let totalSelesai = 0
		pertanyaans.forEach(element => {
			if (element.pilihan_jawaban.map((x) => x.benar==true).length>0) {
				totalSelesai++
			}
		});
		$('#totalSelesai').html('Selesai '+totalSelesai+'/'+pertanyaans.length)
	}

	function getPertanyaan(id_pertanyaan) {
		$('.spinner-div').show()
		$.post(routeGetPertanyaan, {id_pertanyaan:id_pertanyaan,id_soal:{{Illuminate\Support\Js::from($soal->id_soal)}}})
		.done(function(data){
			console.log(data);
			if(data.status == 'success'){
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
				$('#id_pertanyaan').val(data.data.pertanyaan.id_pertanyaan)
				// $('#pertanyaan_text').val(data.data.pertanyaan.pertanyaan_text)
				CKEDITOR.instances.pertanyaan_text.setData(data.data.pertanyaan.pertanyaan_text)
				if (data.data.pilihan_jawaban.length>0) {
					let pilihan_jawaban = ''
					data.data.pilihan_jawaban.forEach((element,index) => {
						pilihan_jawaban += `<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[index]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[${index}][pilihan_text]" id="pilihan_text_${index}" rows="1">${element.pilihan_text}</textarea>
								<input type="hidden" name="pilihan_jawaban[${index}][id_pilihan_jawaban]">
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[${index}][file]" id="file_${index}" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="pilihan_jawaban[${index}][benar]" id="benar_${index}" ${element.benar?'checked':''}>
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`
					});
					hitungSelesai(data.data.pertanyaans)
					$('#jawaban_area').html(pilihan_jawaban)
				} else {
					$('#jawaban_area').html(`<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[0]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[0][pilihan_text]" id="pilihan_text_0" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[0][file]" id="file_0" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="pilihan_jawaban[0][benar]" id="benar_0">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`)
				}
				$('.spinner-div').hide()
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Whoops',
					text: data.message,
					showConfirmButton: false,
					timer: 1300,
				})
				$('#loadingMapNomor').hide()
			}
		})
		.fail(() => {
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('#loadingMapNomor').hide()
		})
	}

	$('input[type=radio]').change(()=>{
		console.log('aa');
		$('.benar').prop('checked',false)
		$(this).prop('checked',true)
	})
</script>