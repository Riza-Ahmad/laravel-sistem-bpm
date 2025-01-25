@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

<div class="d-flex flex-column min-vh-100 p-5 pt-0">
    <div class="ms-5 ps-3">
        <div style="display: flex; align-items: center; ">
            <span style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;" onclick="window.location.href='/berita'">&#x2039;</span>
            <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">Kelola Berdita</h2>
        </div>
    </div>

    <div class="ms-5 ps-5">
        <a class="btn btn-primary" href="{{ route('berita.add') }}">Tambah Berita</a>
    </div>

    <div class="ms-5 p-5 pt-0 pb-0 my-3">
        <form action="{{ route('berita.searchRead') }}" method="GET" class="d-flex">
            <input type="text" name="query" class="form-control me-2" placeholder="Cari berdasarkan judul berita..." value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    <div class="table-container bg-white p-3 ps-5 m-5 mt-0 rounded">
        <table class="table table-hover table-striped table-bordered">
            <thead style="text-align: center;">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Judul Berita</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($berita as $data)
                <tr class="align-middle">
                    <td>{{$i++}}</td>
                    <td style="max-width: 300px;">{{ $data->ber_judul }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->ber_tgl)->locale('id')->translatedFormat('l, d F Y') }}</td>
                    <td style="max-width: 150px;"><img src="{{ asset('storage/' . $data->ber_foto1) }}" class="rounded" style="max-width: 150px"></td>
                    <td style="width: 200px;">
                        <form style="text-align: center;"
                            id="delete-form-{{ $data->ber_id }}"
                            action="{{ route('berita.delete', $data->ber_id) }}"
                            method="POST"
                            onsubmit="return confirmDelete(event, {{ $data->ber_id }});">

                            <a href="{{ route('berita.show', $data->ber_id) }}" class=" btn btn-success btn-sm me-1">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <a href="{{ route('berita.edit', $data->ber_id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="mdi mdi-pencil"></i>
                            </a>

                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(event, {{ $data->ber_id }})" class="btn btn-danger btn-sm me-1">
                                <i class="mdi mdi-delete"></i>
                            </button>

                        </form>
                    </td>
                </tr>
                @empty
                <tr class="align-middle">
                    <td colspan="6" class="text-center">Data tidak tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
<script>
    // Menampilkan SweetAlert jika ada pesan flash 'success'
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: @json(session('success')),
        icon: 'success',
        confirmButtonText: 'Tutup'
    });
    @endif

    // SweetAlert confirmation before delete
    function confirmDelete(event, id) {

        console.log(`Mencoba menemukan form dengan ID: delete-form-${id}`); // Log untuk debug
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
                const form = document.getElementById(`delete-form-${id}`);
                if (form) {
                    console.log(`Form ditemukan untuk ID: delete-form-${id}`); // Log sukses
                    form.submit();
                } else {
                    console.error(`Form tidak ditemukan untuk ID: delete-form-${id}`); // Log error
                }
            }
        });
        return false; // Menghentikan pengiriman default hingga konfirmasi selesai
    }
</script>

@endsection

@endsection