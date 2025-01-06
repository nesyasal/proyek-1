<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Konsultasi;

class ReviewController extends Controller
{
    public function tambahReview(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasi,konsultasi_id', // validasi ID konsultasi
            'rating' => 'required|string|max:20', // validasi rating antara 1 hingga 5
            'pesan' => 'required|string', // validasi review maksimal 255 karakter
        ]);

        // Cek apakah review untuk konsultasi ini sudah ada
        if (Review::where('konsultasi_id', $request->konsultasi_id)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Review sudah pernah dibuat untuk konsultasi ini.']);
        }

        // Simpan review
        Review::create([
            'konsultasi_id' => $request->konsultasi_id,
            'rating' => $request->rating,
            'pesan' => $request->pesan,
        ]);

        // Update status konsultasi
        $konsultasi = Konsultasi::find($request->konsultasi_id);
        $konsultasi->status = 'reviewed';
        $konsultasi->save();

        // redirect kembali dengan pesan sukses
        return redirect()->route('pasien.dashboard')->with('success', 'Review berhasil disimpan.');
    }

    public function create($konsultasiId)
    {
        // Validasi apakah konsultasi tersebut ada dan belum direview
        $konsultasi = Konsultasi::where('konsultasi_id', $konsultasiId)
        ->where('status', '!=', 'reviewed')
        ->firstOrFail();

        return view('review', compact('konsultasi'));
    }
}
