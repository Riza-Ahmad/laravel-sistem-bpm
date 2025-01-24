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
            <span 
                style="color: #2654A1; font-size: 5rem; margin-right: 10px; cursor: pointer;" 
                onclick="window.location.href='/peraturan/index'">
                &#x2039;
            </span>
            <h2 style="color: #2654A1; margin: 0; padding-top:1rem;">Kelola Peraturan</h2>
        </div>
    </div>

    <div class="shadow p-5 m-5 mt-3 bg-white rounded">
        <h2 class="mb-5 text-center" style="color: #5F5858;">Detail Peraturan</h2>

        <div class="form-group mb-3">
            <label for="dok_judul" class="form-label fw-bold">Judul Peraturan</label>
            <p>{{ $peraturan->dok_judul }}</p>
        </div>

        <div class="row">
            <div class="form-group mb-3">
                <label for="dok_nomor_induk" class="form-label fw-bold">Nomor Induk Dokumen</label>
                <p>{{ $peraturan->dok_nomor_induk }}</p>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="dok_tgl_berlaku" class="form-label fw-bold">Tahun Dokumen</label>
                    <p>{{ \Carbon\Carbon::parse($peraturan->dok_tgl_berlaku)->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="dok_tgl_kadaluarsa" class="form-label fw-bold">Tahun Kadaluarsa</label>
                    <p>{{ \Carbon\Carbon::parse($peraturan->dok_tgl_kadaluarsa)->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <label class="form-label fw-bold">Dibuat oleh</label>
                <p>{{ $peraturan->dok_created_by }}</p>

                <label class="form-label fw-bold">Dibuat pada</label>
                <p>{{ \Carbon\Carbon::parse($peraturan->dok_created_date)->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="col-lg-6 col-md-6">
                <label class="form-label fw-bold">Diubah oleh</label>
                <p>{{ $peraturan->dok_modif_by }}</p>

                <label class="form-label fw-bold">Diubah pada</label>
                <p>{{ \Carbon\Carbon::parse($peraturan->dok_modif_date)->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
@endsection
@endsection
