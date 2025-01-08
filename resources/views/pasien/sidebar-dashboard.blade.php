<!-- partial -->
<div class="container-fluid page-body-wrapper">
	<!-- partial:partials/_sidebar.html -->
	<nav class="sidebar sidebar-offcanvas" id="sidebar">
		<ul class="nav">
			<li class="nav-item">
				<a href="{{ route('pasien.profile') }}" class="nav-link">
					<div class="nav-profile-image">
						<!-- <img src="{{asset('assets/images/faces/face1.jpg')}}" alt="profile"> -->
						<span class="login-status online"></span>
						<!--change to offline or busy as needed-->
					</div>
					<div class="nav-profile-text d-flex flex-column">
						<span class="font-weight-bold mb-2">{{ $pasien->nama_pasien }}</span>
						<span class="text-secondary text-small">Pasien</span>
					</div>
				</a>
				<a class="nav-link" href="{{ route('pasien.dashboard') }}">
					<span class="menu-title">Ajukan Keluhan</span>
					<i class="mdi mdi-home menu-icon"></i>
				</a>
			</li>
		</ul>
	</nav>