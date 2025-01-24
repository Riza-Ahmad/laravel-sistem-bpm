<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class PeraturanController extends Controller
{
    public function eksternal(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->where('kategori', 'eksternal')->get();
        return view('peraturan.index', compact('peraturan'));
    }

    public function aps(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->where('kategori', 'aps')->get();
        return view('peraturan.index', compact('peraturan'));
    }

    public function kebijakan(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->where('kategori', 'kebijakan')->get();
        return view('peraturan.index', compact('peraturan'));
    }

    public function index(Request $request): View
    {
        $type = $request->route()->getName(); // Mendapatkan nama rute
        $peraturan = Peraturan::where('dok_status', 'aktif')->get();

        return view('peraturan.index', compact('peraturan', 'type'));
    }

    public function read(): View
    {
        $peraturan = Peraturan::where('dok_status', 'aktif')->get();
        return view('peraturan.read', compact('peraturan'));
    }

    public function add(): View
    {
        return view('peraturan.add');
    }

    public function edit($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.edit', compact('peraturan'));
    }

     public function show($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.show', compact('peraturan'));
    }

    public function see($id): View
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('peraturan.see', compact('peraturan'));
    }

    public function save(Request $request)
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
            'dok_judul' => $request->input('dok_judul'),
            'dok_nomor_induk' => $request->input('dok_nomor_induk'),
            'dok_tgl_berlaku' => $request->input('dok_tgl_berlaku'),
            'dok_tgl_kadaluarsa' => $request->input('dok_tgl_kadaluarsa'),
            'dok_file' => $filePath,
            'dok_status' => 'Aktif',
            'dok_created_by' => 'admin',
            'dok_created_date' => now(),
        ]);

        return redirect()->route('peraturan.index')->with('success', 'Peraturan berhasil disimpan!');
    }

    public function update(Request $request, $id)
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

        $peraturan->update($request->only(['dok_judul', 'dok_nomor_induk', 'dok_tgl_berlaku', 'dok_tgl_kadaluarsa']));

        return redirect()->route('peraturan.index')->with('success', 'Peraturan berhasil diperbarui!');
    }

    public function delete($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->dok_status = 'Tidak Aktif';
        $peraturan->save();

        return redirect()->route('peraturan.index')->with('success', 'Peraturan berhasil dihapus');
    }

    public function search(Request $request): View
    {
        $query = $request->input('query');

        $peraturan = Peraturan::where('dok_status', 'aktif')
            ->where('dok_judul', 'like', '%' . $query . '%')
            ->get();

        return view('peraturan.index', compact('peraturan', 'query'));
    }

    public function searchRead(Request $request): View
    {
        $query = $request->input('query');

        $peraturan = Peraturan::where('dok_status', 'aktif')
            ->where('dok_judul', 'like', '%' . $query . '%')
            ->get();

        return view('peraturan.index', compact('peraturan', 'query'));
    }
}
