@extends('layouts.app')

@section('content')
    <style>
        .preview-image {
            max-width: 100%;
            margin-top: 10px;
            display: none;
        }
    </style>
    <div class="d-flex flex-column min-vh-100 p-5 pt-0">
        <div class="ms-5 ps-3">
            <div style="display: flex; align-items: center;">
                <span style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;"
                    onclick="redirectToParentURL()">
                    &#x2039;
                </span>
                <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">Kelola Peraturan</h2>
            </div>
        </div>

        <div class="shadow p-5 m-5 mt-3 bg-white rounded">
            <h2 class="mb-5" style="color: #5F5858; text-align: center;">Formulir Edit Peraturan</h2>
            <form method="POST" action="{{ route('peraturan.update', ['type' => $type, 'id' => $peraturan->dok_id]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="dok_judul" class="form-label fw-bold">Judul Peraturan</label>
                    <input type="text" name="dok_judul" id="dok_judul" class="form-control"
                        value="{{ $peraturan->dok_judul }}">
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="dok_nomor_induk" class="form-label fw-bold">Nomor Induk Dokumen</label>
                            <input type="text" name="dok_nomor_induk" id="dok_nomor_induk" class="form-control"
                                value="{{ $peraturan->dok_nomor_induk }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="dok_tgl_berlaku" class="form-label fw-bold">Tahun Dokumen</label>
                            <input type="date" name="dok_tgl_berlaku" id="dok_tgl_berlaku" class="form-control"
                                value="{{ $peraturan->dok_tgl_berlaku }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="dok_control" class="form-label fw-bold">Control Type</label>
                            <select name="dok_control" id="dok_control" class="form-select">
                                <option value="">Pilih Control Type</option>
                                <option value="controlled">Controlled</option>
                                <option value="uncontrolled">Uncontrolled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="dok_tgl_kadaluarsa" class="form-label fw-bold">Tahun Kadaluarsa</label>
                            <input type="date" name="dok_tgl_kadaluarsa" id="dok_tgl_kadaluarsa" class="form-control"
                                value="{{ $peraturan->dok_tgl_kadaluarsa }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="dok_file" class="form-label fw-bold">Unggah Dokumen</label>
                    <input type="file" name="dok_file" id="dok_file" class="form-control" accept="file/*"
                        value="{{ $peraturan->dok_control }}">
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary w-100">Perbarui</button>
                    </div>
                    <div class="col-lg-6">
                        <a href="{{ url('/peraturan/' . request()->segment(2)) }}" class="btn btn-danger w-100">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
        document.getElementById('dok_file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('preview').style.display = 'block';
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
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
