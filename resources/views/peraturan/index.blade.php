@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

@php
    $title = "";
    $breadcrumbs = [];

    if (isset($idMenu) && $idMenu === 39) {
        $title = "Dokumen Peraturan";
        $breadcrumbs = [
            ["label" => "Peraturan", "href" => "/peraturan/kebijakan"],
            ["label" => "Dokumen Kebijakan Peraturan"],
        ];
    } elseif (isset($idMenu) && $idMenu === 40) {
        $title = "Dokumen Peraturan Eksternal";
        $breadcrumbs = [
            ["label" => "Peraturan", "href" => "/peraturan/eksternal"],
            ["label" => "Dokumen Kebijakan Eksternal"],
        ];
    } elseif (isset($idMenu) && $idMenu === 41) {
        $title = "Instrumen APS";
        $breadcrumbs = [
            ["label" => "Instrumen APS", "href" => "/peraturan/aps"],
            ["label" => "Dokumen Instrumen APS"],
        ];
    }
@endphp

<div class="d-flex flex-column min-vh-100 p-5 pt-0">
    <div class="ms-5 ps-3">
        <div style="display: flex; align-items: center;">
            <span style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;" onclick="window.location.href='{{ $breadcrumbs[0]['href'] ?? '#' }}'">&#x2039;</span>
            <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">{{ $title }}</h2>
        </div>
    </div>

    <div class="ms-5 ps-5">
        {{-- <a class="btn btn-primary" href="{{ route('peraturan.add') }}">Tambah Peraturan</a> --}}
        <a class="btn btn-primary" href="#">Tambah Peraturan</a>
    </div>

    <div class="ms-5 p-5 pt-0 pb-0 my-3">
        {{-- <form action="{{ route('peraturan.searchRead') }}" method="GET" class="d-flex"> --}}
            <a class="btn btn-primary" href="#">Tambah Peraturan</a>
            <input type="text" name="query" class="form-control me-2" placeholder="Cari berdasarkan judul peraturan..." value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    <div class="table-container bg-white p-3 ps-5 m-5 mt-0 rounded">
        <table class="table table-hover table-striped table-bordered">
            <thead style="text-align: center;">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Judul Peraturan</th>
                    <th scope="col">Nomor Induk</th>
                    <th scope="col">Tanggal Berlaku</th>
                    <th scope="col">Tanggal Kadaluarsa</th>
                    <th scope="col">File</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($peraturan as $data)
                <tr class="align-middle">
                    <td>{{ $i++ }}</td>
                    <td style="max-width: 300px;">{{ $data->dok_judul ?? 'Tidak tersedia' }}</td>
                    <td style="max-width: 300px;">{{ $data->dok_nomor_induk ?? 'Tidak tersedia' }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->dok_tgl_berlaku)->locale('id')->translatedFormat('l, d F Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->dok_tgl_kadaluarsa)->locale('id')->translatedFormat('l, d F Y') }}</td>
                    <td style="max-width: 150px;">
                        @if($data->dok_file)
                            <img src="{{ asset('storage/' . $data->dok_file) }}" class="rounded" style="max-width: 150px">
                        @else
                            Tidak ada file
                        @endif
                    </td>
                    <td style="width: 200px; text-align: center;">
                        <form id="delete-form-{{ $data->dok_id }}" action="{{ route('peraturan.delete', $data->dok_id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')

    <!-- Tombol Tindakan -->
    <a href="{{ route('peraturan.show', $data->dok_id) }}" class="btn btn-success btn-sm me-1">
        <i class="mdi mdi-eye"></i>
    </a>
    <a href="{{ route('peraturan.edit', $data->dok_id) }}" class="btn btn-primary btn-sm me-1">
        <i class="mdi mdi-pencil"></i>
    </a>

    <!-- Tombol Hapus dengan Konfirmasi -->
    <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $data->dok_id }});">
        <i class="mdi mdi-delete"></i>
    </button>
</form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Data tidak tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: @json(session('success')),
        icon: 'success',
        confirmButtonText: 'Tutup'
    });
    @endif

    function confirmDelete(event, id) {
    event.preventDefault(); // Mencegah pengiriman form secara langsung
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika konfirmasi di klik, kirimkan form penghapusan
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

</script>
@endsection

@endsection
