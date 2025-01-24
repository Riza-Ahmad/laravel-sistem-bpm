@extends('layouts.app')

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
            <span style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;" onclick="window.location.href='/peraturan'">&#x2039;</span>
            <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">Kelola Peraturan</h2>
        </div>
    </div>

    <div class="shadow p-5 m-5 mt-3 bg-white rounded">
        <h2 class="mb-5" style="color: #5F5858; text-align: center;">Formulir Peraturan</h2>
        <form method="POST" action="{{ route('peraturan.save') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="dok_judul" class="form-label fw-bold">Judul Peraturan</label>
                <input type="text" name="dok_judul" id="dok_judul" class="form-control">
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="dok_nomor_induk" class="form-label fw-bold">Nomor Induk Dokumen</label>
                        <input type="text" name="dok_nomor_induk" id="dok_nomor_induk" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="dok_tgl_berlaku" class="form-label fw-bold">Tahun Dokumen</label>
                        <input type="date" name="dok_tgl_berlaku" id="dok_tgl_berlaku" class="form-control">
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
                        <input type="date" name="dok_tgl_kadaluarsa" id="dok_tgl_kadaluarsa" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="dok_file" class="form-label fw-bold">Unggah Dokumen</label>
                <input type="file" name="dok_file" id="dok_file" class="form-control" accept="file/*">
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('peraturan.index') }}" class="btn btn-danger w-100">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Handle file preview
    document.getElementById('dok_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('preview').style.display = 'block';
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // Display success message after form submission
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


</script>
@endsection
