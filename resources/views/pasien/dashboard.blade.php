<!-- Content Login Start -->
@include('pasien.header-dashboard')

@include('pasien.navbar-dashboard')

@include('pasien.sidebar-dashboard')
<!-- partial -->
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Pengajuan Keluhan Pasien
			</h3>
			<nav aria-label="breadcrumb">
				<ul class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
					</li>
				</ul>
			</nav>
		</div>
		<div class="row">
			<div class="card-body">
				<a href="{{ route('pasien.dashboard-keluhan') }}" class="btn btn-success">Tambah Keluhan</a>
			</div>
		</div>
		<div class="row">
			<div class="col-12 grid-margin">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Data Keluhan</h4>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th> No </th>
										<th> Nama Pasien </th>
										<th> Dokter yang Menangani </th>
										<th> Tanggal Konsultasi </th>
										<th> Status Konsultasi </th>
										<th> Keluhan </th>
										<th> Balasan Dokter </th>
										<th> Penilaian </th>
									</tr>
								</thead>
								<tbody>
									@foreach($consultations as $index => $consultation)
									<tr>
										<td>{{ $index + 1 }}</td>
										<td>{{ $consultation->nama_pasien }}</td>
										<td>{{ $consultation->nama_dokter }}</td>
										<td>{{ $consultation->tanggal_konsultasi }}</td>
										<td>
											@if ($consultation->status == 'belum dijawab')
											<button class="btn btn-danger">Belum Dijawab</button>
											@elseif ($consultation->status == 'terjawab')
											<button class="btn btn-primary">Terjawab</button>
											@elseif ($consultation->status == 'reviewed')
											<button class="btn btn-success">Reviewed</button>
											@endif
										</td>
										<td>{{ $consultation->keluhan_pasien }}</td>
										<td>
											@if ($consultation->status == 'terjawab')
											<a href="{{ route('konsultasi.pdf', $consultation->konsultasi_id) }}" class="btn btn-secondary">Download PDF</a>
											@elseif ($consultation->status == 'reviewed')
											<a href="{{ route('konsultasi.pdf', $consultation->konsultasi_id) }}" class="btn btn-secondary">Download PDF</a>
											@else
											<span> - </span>
											@endif
										</td>
										<td>
											@if ($consultation->status == 'terjawab')
											<button class="btn btn-primary review-button" data-consultation-id="{{ $consultation->konsultasi_id }}">Review</button>
											@elseif ($consultation->status == 'belum dijawab')
											<button class="btn btn-danger">Keluhan Anda Belum Terjawab</button>
											@elseif ($consultation->status == 'reviewed' && $consultation->rating)
											<button class="btn btn-success">Nilai: {{ $consultation->rating }}</button>
											@endif
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="reviewModalLabel">Review Konsultasi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="reviewForm" action="{{ route('tambahReview') }}" method="POST">
						@csrf
						<input type="hidden" name="konsultasi_id" id="consultationId">
						<div class="mb-3">
							<label for="rating" class="form-label">Rating</label>
							<select class="form-select" name="rating" id="rating">
								<option value="1">1 - Sangat Buruk</option>
								<option value="2">2 - Buruk</option>
								<option value="3">3 - Cukup</option>
								<option value="4">4 - Baik</option>
								<option value="5">5 - Sangat Baik</option>
							</select>
						</div>
						<div class="mb-3">
							<label for="pesan" class="form-label">Pesan Review</label>
							<textarea class="form-control" name="pesan" id="pesan" rows="3"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- content-wrapper ends -->
	<!-- partial:partials/_footer.html -->
	<footer class="footer">
		<div class="container-fluid d-flex justify-content-between">
			<span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
			<span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
		</div>
	</footer>
	<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>

@include('pasien.footer-dashboard')