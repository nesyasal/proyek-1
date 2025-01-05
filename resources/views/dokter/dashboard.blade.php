<!-- Content Login Start -->
@include('dokter.header-dashboard')

@include('dokter.navbar-dashboard')

@include('dokter.sidebar-dashboard')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-secondary card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Pasien
                        </h4>
                        <h2 class="mb-5">{{ $sum_pasien }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Keluhan Terjawab
                        </h4>
                        <h2 class="mb-5">{{ $total_terjawab }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Keluhan Belum Dijawab
                        </h4>
                        <h2 class="mb-5">{{ $total_pesan_blm_dijawab }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Keluhan reviewed
                        </h4>
                        <h2 class="mb-5">{{ $total_reviewed }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-home"></i>
                </span> Daftar Keluhan Pasien
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
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Keluhan</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pasien</th>
                                        <th>Nama Dokter</th>
                                        <th>Tanggal Konsultasi</th>
                                        <th>Status</th>
                                        <th>Keluhan Pasien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($konsultasi as $index => $konsul)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $konsul->nama_pasien }}</td>
                                        <td>{{ $konsul->nama_dokter }}</td>
                                        <td>{{ $konsul->tanggal_konsultasi }}</td>
                                        <td>
                                            @if ($konsul->status == 'terjawab')
                                                <a href="{{ route('chat',  ['konsultasiId' => $konsul->konsultasi_id]) }}" class="btn btn-primary">Mulai Chat</a>
                                            @else
                                                <button class="btn btn-danger" disabled>Belum Siap untuk Chat</button>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $konsul->keluhan_pasien }}</td>
                                        <td><a href="{{ route('dokter.respon', $konsul->konsultasi_id) }}" class="btn btn-success">Lihat detail</a></td> --}}
                                        <td>
                                            <form action="{{ route('konsultasi.terima', $konsul->konsultasi_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Terima Konsultasi</button>
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

@include('dokter.footer-dashboard')