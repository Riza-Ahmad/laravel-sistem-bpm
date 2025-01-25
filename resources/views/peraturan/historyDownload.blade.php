@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    {{-- @php
        $title = '';
        $breadcrumbs = [];

        if (isset($idMenu) && $idMenu === 39) {
            $title = 'Dokumen Peraturan';
            $breadcrumbs = [
                ['label' => 'Peraturan', 'href' => '/peraturan/kebijakan'],
                ['label' => 'Dokumen Kebijakan Peraturan'],
            ];
        } elseif (isset($idMenu) && $idMenu === 40) {
            $title = 'Dokumen Peraturan Eksternal';
            $breadcrumbs = [
                ['label' => 'Peraturan', 'href' => '/peraturan/eksternal'],
                ['label' => 'Dokumen Kebijakan Eksternal'],
            ];
        } elseif (isset($idMenu) && $idMenu === 41) {
            $title = 'Instrumen APS';
            $breadcrumbs = [
                ['label' => 'Instrumen APS', 'href' => '/peraturan/aps'],
                ['label' => 'Dokumen Instrumen APS'],
            ];
        }
    @endphp --}}


    @php
        $titles = [
            // 'kebijakan' => 'Kebijakan Peraturan',
            // 'eksternal' => 'Peraturan Eksternal',
            // 'instrument' => 'Instrumen APS',
        ];
    @endphp

    {{-- <h1>{{ $titles[$type] ?? 'Daftar Peraturan' }}</h1> --}}



    <div class="d-flex flex-column min-vh-100 p-5 pt-0">
        <div class="ms-5 ps-3">
            <div style="display: flex; align-items: center;">
                <span style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;"
                    onclick="redirectToParentURL()">
                    &#x2039;
                </span>
                <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">
                    @if ($type === 'kebijakan')
                        Dokumen Peraturan
                    @elseif ($type === 'eksternal')
                        Dokumen Peraturan Eksternal
                    @elseif ($type === 'instrument')
                        Instrument APS
                    @endif
                </h2>
            </div>
        </div>


        <div class="table-container bg-white p-3 ps-5 m-5 mt-0 rounded">
            <table class="table table-hover table-striped table-bordered">
                <thead style="text-align: center;">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal Unduh</th>
                        <th scope="col">Judul Dokumen</th>
                        <th scope="col">Nama Berkas (File)</th>
                        <th scope="col">Jenis Penyalinan</th>
                        <th scope="col">Nama Pengunduh</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse ($history as $data)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->udo_tgl_unduh)->format('d M Y H:i') }}</td>
                            <td>{{ $data->dok_judul }}</td>
                            <td>
                                @if ($data->dok_file)
                                    <a href="{{ asset('storage/' . $data->dok_file) }}" target="_blank">
                                        {{ basename($data->dok_file) }}
                                    </a>
                                @else
                                    Tidak Ada File
                                @endif
                            </td>
                            <td>{{ $data->udo_jenis_penyalinan }}</td>
                            <td>{{ $data->udo_created_by }}</td>
                            <td>-</td>
                            <td>{{ $data->udo_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Data tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if (session('success'))
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
        <script>
            function redirectToParentURL() {
                // Ambil URL saat ini
                const currentURL = window.location.pathname;

                // Hilangkan segment terakhir (/3/show)
                const parentURL = currentURL.split('/').slice(0, -2).join('/');

                // Redirect ke URL induk
                window.location.href = parentURL;
            }
        </script>
    @endsection
@endsection
