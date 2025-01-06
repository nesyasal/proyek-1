<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Konsultasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Berikan Review Anda</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form Review -->
                        <form action="{{ route('review.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="konsultasi_id" value="{{ $konsultasi->konsultasi_id }}">

                            <!-- Rating -->
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating (1-5)</label>
                                <select name="rating" id="rating" class="form-select" required>
                                    <option value="" disabled selected>Pilih Rating</option>
                                    <option value="1">1 - Sangat Buruk</option>
                                    <option value="2">2 - Buruk</option>
                                    <option value="3">3 - Cukup</option>
                                    <option value="4">4 - Baik</option>
                                    <option value="5">5 - Sangat Baik</option>
                                </select>
                            </div>

                            <!-- Pesan -->
                            <div class="mb-3">
                                <label for="pesan" class="form-label">Pesan</label>
                                <textarea name="pesan" id="pesan" class="form-control" rows="5" placeholder="Tulis pesan atau ulasan Anda di sini..." required></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Kirim Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>