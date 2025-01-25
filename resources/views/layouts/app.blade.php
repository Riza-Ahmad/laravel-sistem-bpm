<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Sistem BPM</title>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Header tetap di atas */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #2654A1;
            padding: 0.5rem 2rem;
        }

        /* Memberikan padding top pada konten utama untuk menghindari tumpang tindih dengan header */
        body {
            padding-top: 4rem;

        }

        /* Footer di bawah konten */
        footer {
            background-color: rgb(38, 84, 161);
            color: white;
            position: relative;
            width: 100%;
            bottom: 0;
        }

        /* Pastikan konten tidak tertutup footer */
        .main-content {
            margin-bottom: 100px;
            /* Sesuaikan dengan tinggi footer */
        }

        /* Agar konten dapat digulir dengan baik */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .container {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            min-height: 85vh
                /* Sesuaikan dengan tinggi header */
        }

        /* Global Navigation Styles */
        .nav * {
            font-size: 16px;
            font-weight: 600;
        }

        .nav-item .nav-link {
            color: #FFF;
        }

        /* Nav Desktop View */
        @media (min-width: 1200px) {
            .nav .nav-item {
                margin-right: 15px;
            }

            .dropdown-submenu {
                position: relative;
            }

            .dropdown-submenu .dropdown-menu {
                top: 0;
                left: 100%;
                margin-top: -8px;
                display: none;
            }

            .dropdown:hover>.dropdown-menu,
            .dropdown-submenu:hover>.dropdown-menu {
                display: block;
                animation: fadeUp 0.5s ease forwards;
            }

            .dropdown-item:hover {
                background-color: #d3e3ff;
            }

            .nav-item:hover {
                background-color: #183667;
                border-radius: 12px;
            }
        }

        /* Nav Mobile View */
        @media (max-width: 60vh) {
            .navmenu {
                position: absolute;
                left: 0;
                top: 1rem;
                width: 100%;
                transition: top 0.4s, opacity 0.3s;
            }

            .navmenu::-webkit-scrollbar {
                width: 0;
            }

            .nav {
                background-color: #2654A1;
                padding-top: 1rem;
                display: block;
                width: 100vh;
            }

            .dropdown-submenu {
                position: relative;
            }

            .dropdown-submenu .dropdown-menu {
                top: 0;
                margin-top: 0;
                display: none;
            }

            .dropdown:hover>.dropdown-menu,
            .dropdown-submenu:hover>.dropdown-menu {
                display: block;
            }

            .dropdown-item:hover {
                background-color: #d3e3ff;
            }

            .nav-item:hover {
                background-color: #183667;
                border-radius: 8px;
            }
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                transform: translateY(10px);
            }

            to {
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                transform: translateX(-10px);
            }

            to {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="d-flex justify-content-between align-items-center fixed-top p-1" style="background-color: #2654A1;">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/bpm-logo.png') }}" alt="Logo AstraTech" class="navbar-brand" style="height: 4rem; margin-left: 2rem;">
            </div>
            <ul class="nav">
                <!-- Beranda -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                </li>

                <!-- Tentang -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tentang') }}">Tentang</a>
                </li>

                <!-- Berita -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/berita') }}">Berita</a>
                </li>

                <!-- Kegiatan -->
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" onclick="toggleDropdown('kegiatan')">Kegiatan</button>
                    <ul class="dropdown-menu" id="dropdown-kegiatan" style="display: none;">
                        <li>
                            <a class="dropdown-item" href="{{ url('/kegiatan/jadwal') }}">Jadwal Kegiatan BPM</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/kegiatan/dokumentasi') }}">Dokumentasi Kegiatan BPM</a>
                        </li>
                    </ul>
                </li>

               {{-- <li class="nav-item dropdown">
                    <!-- Menggunakan data-bs-toggle untuk mengaktifkan dropdown secara otomatis -->
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPeraturan" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Peraturan
                    </a>
                    <!-- Menambahkan dropdown-menu-end untuk menempatkan dropdown di sebelah kanan -->
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPeraturan">
                        <li>
                            <a class="dropdown-item" href="{{ url('/peraturan/kebijakan') }}">Kebijakan Peraturan</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/peraturan/eksternal') }}">Peraturan Eksternal</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/peraturan/aps') }}">Instrumen APS</a>
                        </li>
                    </ul>
                </li> --}}

                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPeraturan" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Peraturan
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPeraturan">
        <li>
            <a class="dropdown-item" href="{{ url('/peraturan/kebijakan') }}">Kebijakan Peraturan</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ url('/peraturan/eksternal') }}">Peraturan Eksternal</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ url('/peraturan/instrument') }}">Instrumen APS</a>
        </li>
    </ul>
</li>

                
                <li class="nav-item">
                    <button class="btn bg-white shadow-sm border-0">Masuk</button>
                </li>

            </ul>
        </div>
    </header>

    <div class="content">
        @yield('content')
        @yield('scripts')
    </div>

    <footer class="footer" style="background-color: rgb(38, 84, 161); color: white;">
        <div class="container">
            <div class="row text-white m-3">
                <!-- Column 1: About BPM -->
                <div class="col-lg-5 col-md-6 mb-4">
                    <img
                        src="{{ asset('storage/bpm-logo.png') }}"
                        alt="Logo BPM"
                        style="width: 200px; height: auto;">
                    <p style="text-align: justify;">
                        Badan Penjamin Mutu (BPM) melaksanakan proses penetapan dan pemenuhan standar mutu pengelolaan Politeknik Astra secara berkelanjutan guna menjaga dan meningkatkan mutu penyelenggaraan program pendidikan dan kegiatan akademik di Politeknik Astra, dalam mewujudkan visi dan misi institusi, serta memenuhi kebutuhan <i>stakeholders</i>.
                    </p>
                    <p class="pull-left mt-3 mb-0">
                        &copy; 2024. Politeknik Astra / Badan Penjaminan Mutu
                    </p>
                </div>

                <!-- Column 2: Related Links -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="mt-5">LINK TERKAIT</h6>
                    <ul class="list-unstyled small-text">
                        @php
                        $links = [
                        ["name" => "LLDIKTI WILAYAH III", "link" => "https://lldikti3.kemdikbud.go.id/"],
                        ["name" => "PDDIKTI", "link" => "https://pddikti.kemdikbud.go.id/"],
                        ["name" => "SPMI Kemdikbud", "link" => "https://spmi.kemdikbud.go.id/"],
                        ["name" => "BAN-PT", "link" => "https://banpt.or.id/"],
                        ["name" => "LAM Teknik", "link" => "https://lamteknik.or.id/"],
                        ["name" => "LAM Infokom", "link" => "https://laminfokom.or.id/official/"]
                        ];
                        @endphp

                        @foreach ($links as $item)
                        <li>
                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" class="text-white">
                                {{ $item['name'] }}
                            </a>
                        </li> @endforeach
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div class="col-lg-4
        col-md-6 mb-4">
    <h6 class="mt-5">KONTAK</h6>
    <p>
        <strong>Badan Penjaminan Mutu (BPM)</strong><br>
        Email: <a href="mailto:bpm@polytechnic.astra.ac.id" class="text-white">bpm@polytechnic.astra.ac.id</a><br>
        Politeknik Astra<br>
        Kawasan Industri Delta Silicon 2<br>
        Jl. Gaharu Blok F3 No. 1<br>
        Cibatu, Kec. Cikarang Selatan, Kab. Bekasi
    </p>

    <!-- Social Media Icons -->
    <a href="https://www.instagram.com/astrapolytechnic/" target="_blank" class="btn px-1 py-0 text-white"
        title="Visit our Instagram">
        <i class="fi fi-brands-instagram" style="font-size: 20px; margin: 10px 5px;"></i>
    </a>
    <a href="https://www.youtube.com/c/PolmanAstrachannel" target="_blank" class="btn px-1 py-0 text-white"
        title="Visit our YouTube channel">
        <i class="fi fi-brands-youtube" style="font-size: 20px; margin: 10px 5px;"></i>
    </a>
    <a href="https://www.facebook.com/Astrapolytechnic/" target="_blank" class="btn px-1 py-0 text-white"
        title="Visit our Facebook page">
        <i class="fi fi-brands-facebook" style="font-size: 20px; margin: 10px 5px;"></i>
    </a>
    <a href="https://api.whatsapp.com/send/?phone=6281295582134" target="_blank" class="btn px-1 py-0 text-white"
        title="Contact us on WhatsApp">
        <i class="fi fi-brands-whatsapp" style="font-size: 20px; margin: 10px 5px;"></i>
    </a>

    </div>
    </div>
    </div>
    </footer>

    <script>
        let openDropdown = null;

        function toggleDropdown(menu) {
            if (openDropdown === menu) {
                document.getElementById(`dropdown-${menu}`).style.display = 'none';
                openDropdown = null;
            } else {
                // Close other open dropdowns
                if (openDropdown) {
                    document.getElementById(`dropdown-${openDropdown}`).style.display = 'none';
                }
                // Open the selected dropdown
                document.getElementById(`dropdown-${menu}`).style.display = 'block';
                openDropdown = menu;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
