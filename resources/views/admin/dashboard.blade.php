<!-- Content Login Start -->
@include('admin.header-dashboard')

@include('admin.navbar-dashboard')

@include('admin.sidebar-dashboard')
<!-- partial -->
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Pengguna
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
				<a href="{{ route('admin.tambah-pengguna') }}" class="btn btn-success">Tambah Data Pengguna</a>
			</div>
		</div>
		<div class="row">
			<div class="col-12 grid-margin">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Data Pengguna</h4>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th> No </th>
										<th> Nama </th>
										<th> Username </th>
										<th> Email </th>
										<th> Jenis Kelamin </th>
										<th> Tanggal Lahir </th>
										<th> Alamat </th>
										<th> No. Telepon </th>
										<th> Tipe Pengguna </th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($users as $index => $user)
									<tr>
										<td>{{ $index + 1 }}</td>
										<td>{{ $user->name }}</td>
										<td>{{ $user->username }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ $user->jenis_kelamin }}</td>
										<td>{{ $user->tanggal_lahir }}</td>
										<td>{{ $user->alamat }}</td>
										<td>{{ $user->no_telepon }}</td>
										<td>{{ $user->tipe_pengguna }}</td>
										<td>{{ $user->status }}</td>
										<td>
											@if($user->status == 'pending')
												<a href="{{ route('admin.approveUser', $user->id) }}" class="btn btn-success">Approve</a>
												<a href="{{ route('admin.rejectUser', $user->id) }}" class="btn btn-danger">Reject</a>
											@else
												<span class="badge {{ $user->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
													{{ ucfirst($user->status) }}
												</span>
											@endif
										</td>
										<td>
											<a href="{{ route('admin.edit-pengguna', $user->id) }}" class="btn btn-primary">Edit</a>
											<form action="{{ route('deletePengguna', $user->id) }}" method="POST" style="display:inline;">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Delete</button>
											</form>
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
	<!-- content-wrapper ends -->
	<!-- partial:partials/_footer.html -->
	<footer class="footer">
		<div class="container-fluid d-flex justify-content-between">
			<span class="text-muted d-block text-center text-sm-start d-sm-inline-block"></span>
			<span class="float-none float-sm-end mt-1 mt-sm-0 text-end"></span>
		</div>
	</footer>
	<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>

@include('admin.footer-dashboard')