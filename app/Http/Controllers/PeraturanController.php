<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use App\Models\UnduhDokumen;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;



class PeraturanController extends Controller
{
    // Menampilkan peraturan berdasarkan kategori kebijakan
    // public function kebijakanIndex(): View
    // {
    //     $peraturan = Peraturan::where('dok_status', 'aktif')->get();


    //     return view('peraturan.kebijakan.index', compact('peraturan'));
    // }

    public function index($type)
    {
        $peraturan = Peraturan::where('dok_revisi', 0)->get();

        return view('peraturan.index', [
            'type' => $type,
            'peraturan' => $peraturan,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string|max:100',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'nullable|date|after_or_equal:dok_tgl_berlaku',
            'dok_control' => 'required|string|in:controlled,uncontrolled',
            'dok_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Simpan file jika ada
        $filePath = null;
        if ($request->hasFile('dok_file')) {
            $filePath = $request->file('dok_file')->store('dokumen', 'public');
        }

        $peraturan = Peraturan::create([
            'dok_judul' => $request->dok_judul,
            'dok_nomor_induk' => $request->dok_nomor_induk,
            'dok_tgl_berlaku' => $request->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $request->dok_tgl_kadaluarsa,
            'dok_control' => $request->dok_control,
            'dok_file' => $filePath,
            'dok_revisi' => '0',
            'dok_status' => 'Aktif',
            'dok_created_by' => 'Admin',
            'dok_created_date' => now(),
        ]);

        $peraturan->update([
            'dok_referensi' => $peraturan->dok_id,
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function show($type, $id)
    {
        $peraturan = Peraturan::findOrFail($id);

        return view('peraturan.show', [
            'type' => $type,
            'peraturan' => $peraturan,
        ]);
    }

    public function edit($type, $id)
    {
        // Ambil data berdasarkan dok_id
        $peraturan = Peraturan::findOrFail($id);

        // Kirim data dan tipe ke view edit.blade.php
        return view('peraturan.edit', [
            'type' => $type,
            'peraturan' => $peraturan,
        ]);
    }

    public function update(Request $request, $type, $id)
    {
        // Validasi input
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string|max:100',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'nullable|date|after_or_equal:dok_tgl_berlaku',
            'dok_control' => 'required|string|in:controlled,uncontrolled',
            'dok_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Ambil data berdasarkan dok_id
        $peraturan = Peraturan::findOrFail($id);

        // Simpan file jika ada
        $filePath = $peraturan->dok_file; // Pertahankan file lama
        if ($request->hasFile('dok_file')) {
            // Hapus file lama jika ada
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            // Simpan file baru
            $filePath = $request->file('dok_file')->store('dokumen', 'public');
        }

        // Perbarui data di database
        $peraturan->update([
            'dok_judul' => $request->dok_judul,
            'dok_nomor_induk' => $request->dok_nomor_induk,
            'dok_tgl_berlaku' => $request->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $request->dok_tgl_kadaluarsa,
            'dok_control' => $request->dok_control,
            'dok_file' => $filePath,
            'dok_modif_by' => 'Admin', // Sesuaikan jika menggunakan autentikasi
            'dok_modif_date' => now(),
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('peraturan.index', ['type' => $type])
            ->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function download($type, $id)
    {
        // Ambil data dengan dok_referensi = $id dan dok_id terbesar
        $peraturan = Peraturan::where('dok_referensi', $id)
            ->orderBy('dok_id', 'desc')
            ->first();

        // Jika tidak ditemukan, gunakan dokumen dengan dok_id = $id
        if (!$peraturan) {
            $peraturan = Peraturan::findOrFail($id);
        }

        // Periksa apakah file ada
        if ($peraturan->dok_file && Storage::disk('public')->exists($peraturan->dok_file)) {
            // Insert data ke tabel unduh_dokumen
            UnduhDokumen::create([
                'dok_id' => $id, // ID dokumen yang diunduh
                'udo_tgl_unduh' => now(), // Waktu pengunduhan
                'udo_status' => 'Berhasil Diunduh', // Status
                'udo_jenis_penyalinan' => $peraturan->dok_control, // Jenis penyalinan berdasarkan dok_control
                'udo_created_by' => 'Admin', // Siapa yang mengunduh, atur sesuai kebutuhan
                'udo_created_date' => now(),
            ]);

            // Unduh file
            return Storage::disk('public')->download($peraturan->dok_file);
        }

        // Jika file tidak ditemukan
        return redirect()->route('peraturan.index', ['type' => $type])
            ->with('error', 'File tidak ditemukan.');
    }




    public function toggleStatus($type, $id)
    {
        // Ambil data berdasarkan dok_id
        $peraturan = Peraturan::findOrFail($id);

        // Tentukan status baru berdasarkan status saat ini
        $newStatus = $peraturan->dok_status === 'Aktif' ? 'Nonaktif' : 'Aktif';

        // Perbarui status di database
        $peraturan->update([
            'dok_status' => $newStatus,
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('peraturan.index', ['type' => $type])
            ->with('success', 'Status peraturan berhasil diubah menjadi ' . $newStatus . '.');
    }

    public function showUploadForm($type, $id)
    {
        // Ambil data berdasarkan dok_id
        $peraturan = Peraturan::findOrFail($id);

        // Kirim data ke view unggah.blade.php
        return view('peraturan.unggah', [
            'type' => $type,
            'peraturan' => $peraturan,
        ]);
    }

    public function updateUnggah(Request $request, $type, $id)
    {
        $request->validate([
            'dok_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $existingPeraturan = Peraturan::findOrFail($id);

        $latestPeraturan = Peraturan::where('dok_referensi', $id)
            ->orderBy('dok_id', 'desc')
            ->first();

        $newDokRevisi = $latestPeraturan ? ($latestPeraturan->dok_revisi + 1) : 1;

        $filePath = null;
        if ($request->hasFile('dok_file')) {
            if ($existingPeraturan->dok_file) {
                Storage::disk('public')->delete($existingPeraturan->dok_file);
            }
            $filePath = $request->file('dok_file')->store('dokumen', 'public');
        }

        $newPeraturan = Peraturan::create([
            'dok_judul' => $existingPeraturan->dok_judul,
            'dok_nomor_induk' => $existingPeraturan->dok_nomor_induk,
            'dok_tgl_berlaku' => $existingPeraturan->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $existingPeraturan->dok_tgl_kadaluarsa,
            'dok_control' => $existingPeraturan->dok_control,
            'dok_file' => $filePath,
            'dok_referensi' => $id,
            'dok_revisi' => $newDokRevisi,
            'dok_status' => $existingPeraturan->dok_status,
            'dok_created_by' => 'Admin',
            'dok_created_date' => now(),
        ]);

        return redirect()->route('peraturan.index', ['type' => $type])
            ->with('success', 'File berhasil diunggah dan revisi diperbarui.');
    }

    public function showHistory($type, $id)
    {
        // Ambil semua revisi terkait dokumen berdasarkan dok_referensi atau dok_id
        $history = Peraturan::where('dok_referensi', $id)
            ->orWhere('dok_id', $id)
            ->orderBy('dok_revisi', 'asc')
            ->get();

        // Kirim data ke view history
        return view('peraturan.history', [
            'type' => $type,
            'peraturan' => $history,
        ]);
    }

    public function showHistoryDownload($type, $id)
    {
        // Ambil data riwayat unduhan dan gabungkan dengan tabel bpm_msdokumen
        $history = UnduhDokumen::select('bpm_trunduhdokumen.*', 'bpm_msdokumen.dok_judul', 'bpm_msdokumen.dok_file')
            ->join('bpm_msdokumen', 'bpm_trunduhdokumen.dok_id', '=', 'bpm_msdokumen.dok_id')
            ->where('bpm_trunduhdokumen.dok_id', $id)
            ->orderBy('bpm_trunduhdokumen.udo_tgl_unduh', 'desc')
            ->get();

        // Kirim data ke view
        return view('peraturan.historyDownload', [
            'type' => $type,
            'history' => $history,
        ]);
    }








    // Menampilkan form untuk menambah peraturan kebijakan
    public function kebijakanAdd(): View
    {
        return view('peraturan.kebijakan.add');
    }

    // Menyimpan peraturan kebijakan
    public function kebijakanSave(Request $request)
    {
        // Validasi inputan dari pengguna
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string|max:100',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        // Cek apakah ada file yang diupload
        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
        }

        // Membuat peraturan kebijakan baru
        Peraturan::create([
            'dok_judul' => $request->dok_judul,
            'dok_nomor_induk' => $request->dok_nomor_induk,
            'dok_tgl_berlaku' => $request->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $request->dok_tgl_kadaluarsa,
            'dok_file' => $filePath,
            'dok_status' => 'Aktif',
            'dok_created_by' => 'admin', // ini bisa disesuaikan dengan user yang login
            'dok_created_date' => now(),
            'kategori' => 'kebijakan',
        ]);

        return redirect()->route('peraturan.kebijakan.index')->with('success', 'Peraturan kebijakan berhasil disimpan!');
    }

    // Menampilkan form untuk mengedit peraturan kebijakan berdasarkan ID
    public function kebijakanEdit($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.kebijakan.edit', compact('peraturan'));
    }

    // Memperbarui peraturan kebijakan
    public function kebijakanUpdate(Request $request, $id)
    {
        // Validasi inputan dari pengguna
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Mengambil peraturan berdasarkan ID
        $peraturan = Peraturan::findOrFail($id);

        // Jika ada file yang diupload, simpan file baru
        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
            $peraturan->dok_file = $filePath;
        }

        // Update data peraturan
        $peraturan->update($request->only([
            'dok_judul',
            'dok_nomor_induk',
            'dok_tgl_berlaku',
            'dok_tgl_kadaluarsa'
        ]));

        return redirect()->route('peraturan.kebijakan.index')->with('success', 'Peraturan kebijakan berhasil diperbarui!');
    }

    // Menghapus (deactivate) peraturan kebijakan berdasarkan ID
    public function kebijakanDelete($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->dok_status = 'Tidak Aktif';
        $peraturan->save();

        return redirect()->route('peraturan.kebijakan.index')->with('success', 'Peraturan kebijakan berhasil dihapus');
    }

    // Menampilkan peraturan berdasarkan kategori eksternal
    public function eksternalIndex(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->get();


        return view('peraturan.eksternal.index', compact('peraturan'));
    }

    // Menampilkan form untuk menambah peraturan eksternal
    public function eksternalAdd(): View
    {
        return view('peraturan.eksternal.add');
    }

    // Menyimpan peraturan eksternal
    public function eksternalSave(Request $request)
    {
        // Validasi inputan dari pengguna
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string|max:100',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        // Cek apakah ada file yang diupload
        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
        }

        // Membuat peraturan eksternal baru
        Peraturan::create([
            'dok_judul' => $request->dok_judul,
            'dok_nomor_induk' => $request->dok_nomor_induk,
            'dok_tgl_berlaku' => $request->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $request->dok_tgl_kadaluarsa,
            'dok_file' => $filePath,
            'dok_status' => 'Aktif',
            'dok_created_by' => 'admin',
            'dok_created_date' => now(),
            'kategori' => 'eksternal',
        ]);

        return redirect()->route('peraturan.eksternal.index')->with('success', 'Peraturan eksternal berhasil disimpan!');
    }

    // Menampilkan form untuk mengedit peraturan eksternal berdasarkan ID
    public function eksternalEdit($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.eksternal.edit', compact('peraturan'));
    }

    // Memperbarui peraturan eksternal
    public function eksternalUpdate(Request $request, $id)
    {
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $peraturan = Peraturan::findOrFail($id);

        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
            $peraturan->dok_file = $filePath;
        }

        $peraturan->update($request->only([
            'dok_judul',
            'dok_nomor_induk',
            'dok_tgl_berlaku',
            'dok_tgl_kadaluarsa'
        ]));

        return redirect()->route('peraturan.eksternal.index')->with('success', 'Peraturan eksternal berhasil diperbarui!');
    }

    // Menghapus peraturan eksternal berdasarkan ID
    public function eksternalDelete($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->dok_status = 'Tidak Aktif';
        $peraturan->save();

        return redirect()->route('peraturan.eksternal.index')->with('success', 'Peraturan eksternal berhasil dihapus');
    }

    // Menampilkan peraturan berdasarkan kategori instrumen APS
    public function instrumenApsIndex(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')
            ->where('kategori', 'instrumen-aps')
            ->get();

        return view('peraturan.instrumenAps.index', compact('peraturan'));
    }

    // Menampilkan form untuk menambah peraturan instrumen APS
    public function instrumenApsAdd(): View
    {
        return view('peraturan.instrumenAps.add');
    }

    // Menyimpan peraturan instrumen APS
    public function instrumenApsSave(Request $request)
    {
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string|max:100',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
        }

        Peraturan::create([
            'dok_judul' => $request->dok_judul,
            'dok_nomor_induk' => $request->dok_nomor_induk,
            'dok_tgl_berlaku' => $request->dok_tgl_berlaku,
            'dok_tgl_kadaluarsa' => $request->dok_tgl_kadaluarsa,
            'dok_file' => $filePath,
            'dok_status' => 'Aktif',
            'dok_created_by' => 'admin',
            'dok_created_date' => now(),
            'kategori' => 'instrumen-aps',
        ]);

        return redirect()->route('peraturan.instrumenAps.index')->with('success', 'Peraturan instrumen APS berhasil disimpan!');
    }

    // Menampilkan form untuk mengedit peraturan instrumen APS berdasarkan ID
    public function instrumenApsEdit($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.instrumenAps.edit', compact('peraturan'));
    }

    // Memperbarui peraturan instrumen APS
    public function instrumenApsUpdate(Request $request, $id)
    {
        $request->validate([
            'dok_judul' => 'required|string|max:255',
            'dok_nomor_induk' => 'required|string',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $peraturan = Peraturan::findOrFail($id);

        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen', 'public');
            $peraturan->dok_file = $filePath;
        }

        $peraturan->update($request->only([
            'dok_judul',
            'dok_nomor_induk',
            'dok_tgl_berlaku',
            'dok_tgl_kadaluarsa'
        ]));

        return redirect()->route('peraturan.instrumenAps.index')->with('success', 'Peraturan instrumen APS berhasil diperbarui!');
    }

    // Menghapus peraturan instrumen APS berdasarkan ID
    public function instrumenApsDelete($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->dok_status = 'Tidak Aktif';
        $peraturan->save();

        return redirect()->route('peraturan.instrumenAps.index')->with('success', 'Peraturan instrumen APS berhasil dihapus');
    }

    public function save(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'dok_judul' => 'required|string',
            'dok_nomor_induk' => 'required|string',
            'dok_tgl_berlaku' => 'required|date',
            'dok_tgl_kadaluarsa' => 'required|date',
            'dok_control' => 'required|string',
            'dok_file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        // Simpan data
        $peraturan = new Peraturan;
        $peraturan->dok_judul = $request->dok_judul;
        $peraturan->dok_nomor_induk = $request->dok_nomor_induk;
        $peraturan->dok_tgl_berlaku = $request->dok_tgl_berlaku;
        $peraturan->dok_tgl_kadaluarsa = $request->dok_tgl_kadaluarsa;
        $peraturan->dok_control = $request->dok_control;

        // Proses upload file
        if ($request->hasFile('dok_file')) {
            $file = $request->file('dok_file');
            $filePath = $file->store('dokumen');
            $peraturan->dok_file = $filePath;
        }

        $peraturan->save();

        // Redirect dengan pesan sukses
        return redirect()->route('peraturan.index')->with('success', 'Dokumen berhasil disimpan!');
    }
}
