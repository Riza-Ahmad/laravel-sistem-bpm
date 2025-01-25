<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeraturanController extends Controller
{
    // Menampilkan peraturan berdasarkan kategori kebijakan
    public function kebijakanIndex(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->get();


        return view('peraturan.kebijakan.index', compact('peraturan'));
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
