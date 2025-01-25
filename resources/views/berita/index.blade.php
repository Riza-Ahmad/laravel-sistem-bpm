@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <style>
        .cardStyle {
            border-radius: 15px;
            box-shadow: 3px 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            max-width: 99%;
            padding: 20px;
            padding-bottom: 0px;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .imgStyle {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
        }

        .textContainerStyle {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            flex: 1 1 auto;
        }

        .textContentStyle {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }

        .textStyle {
            font-size: 14px;
            color: #555;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
        }
    </style>
    <div class="bg-image position-relative"
        style="background-image: url('{{ asset('storage/assets/gedung-astra-biru.png') }}'); background-size: cover; background-position: center; height: 100vh;">
        <div class="position-absolute top-0 end-0 p-5 mb-3" style="z-index: 20;">
            <a class="btn btn-primary" href="{{ route('berita.read') }}">Kelola Berita</a>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="position-absolute" style="top: 10%; padding: 3rem; margin: 3rem;">
                    <header>
                        <h2 style="color: white; text-align: left; font-weight: 700; width: 400px; font-size: 200%;">
                            <span>Berita Badan<br />Penjamin Mutu (BPM)<br />Politeknik Astra</span>
                        </h2>
                    </header>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <img src="{{ asset('storage/assets/mahasiswa.png') }}" alt="Orang"
                    style="position: absolute; right: 0; bottom: 0; width: 75%; height: auto; min-width: 700px; padding-right: 20px; max-width: 75%;" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-5"
        style="position: relative; top: -10rem; z-index: 1; min-height: 70vh; padding: 3rem; margin-bottom: -5rem;">

        <div class="container">
            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('berita.search') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Cari berita berdasarkan judul"
                        value="{{ request('query') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

            @forelse ($berita as $index => $data)
                @if ($index % 4 == 0)
                    <div class="row mb-4">
                @endif

                <div class="col-lg-3">
                    <div class="cardStyle">

                        <img class="imgStyle" src="{{ asset('storage/' . $data->ber_foto1) }}" />

                        <div class="textContainerStyle">
                            <div class="textContentStyle">
                                <h6
                                    style="size: 18px; color:black; font-weight:700; text-align:left; margin: 20px 0px 10px 0px">
                                    {{ $data->ber_judul }}
                                </h6>

                                <p
                                    style="
                color: #007bff;
                font-size: 14px;
                margin-bottom: 10px;
              ">
                                    Oleh {{ $data->ber_penulis }} |
                                    {{ \Carbon\Carbon::parse($data->ber_tgl)->locale('id')->translatedFormat('l, d F Y') }}
                                </p>

                                <p class="textStyle">
                                    {!! Str::limit(strip_tags($data->ber_isi), 100) !!}
                                </p>

                            </div>
                        </div>

                        <a href="{{ route('berita.see', $data->ber_id) }}" class="btn btn-primary mb-3">
                            Selengkapnya
                        </a>
                    </div>
                </div>

                @if (($index + 1) % 4 == 0 || $loop->last)
        </div>
        @endif
    @empty
        <p colspan="6" class="text-center">Data tidak tersedia.</p>
        @endforelse
    </div>

    </div>
@endsection
