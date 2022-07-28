<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="icon" type="image/png" href="images/favico.png" />
    <title>Mitrarenov</title>
</head>

<body>
    <header class="header">
        <div class="header-top">
            <div class="header-inner">
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        Belum memiliki akun ? <a href="register.html" class="text-warning font-weight-bold">Registrasi sekarang</a>
                    </div>
                    <div class="w-100 text-right">
                        <ul class="nav justify-content-end">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="ico ico-phone"></i> <span class="font-weight-bold">Call Center</span> 0822
                                    9000 9990
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="ico ico-download"></i> <span class="font-weight-bold">Unduh Aplikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="header-inner">
                <div class="d-flex align-items-center header-row">
                    <div class="header-logo">
                        <a href="index.html">
                            <img src="images/logo-mitrarenov.png" class="img-fluid" alt="">
                        </a>
                    </div>
                    <div class="header-main-nav">
                        <div class="login-mobile">
                            <a href="login.html" class="nav-link px-0">
                                <i class="ico ico-user"></i> Login / Daftar
                            </a>
                        </div>
                        <ul class="nav main-nav">
                            <li class="nav-item">
                                <a href="index.html" class="nav-link @@home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="simulasi-kpr.html" class="nav-link @@simulasi">Simulasi KPR</a>
                            </li>
                            <li class="nav-item">
                                <a href="tentang-kami.html" class="nav-link @@about">Tentang Kami</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link @@carakerja">Cara Kerja</a>
                            </li>
                            <li class="nav-item">
                                <a href="hubungi-kami.html" class="nav-link @@contact">Hubungi Kami</a>
                            </li>
                            <li class="nav-item">
                                <a href="artikel.html" class="nav-link @@artikel">Artikel</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link @@material">Material</a>
                            </li>
                        </ul>
                        <ul class="nav justify-content-end mobile-call-center">
                            <li class="nav-item">
                                <a href="#" class="nav-link px-0">
                                    <i class="ico ico-phone"></i> <span class="font-weight-bold">Call Center</span> 0822
                                    9000 9990
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link px-0">
                                    <i class="ico ico-download"></i> <span class="font-weight-bold">Unduh Aplikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="header-nav-second">
                        <ul class="nav justify-content-end">
                            <li class="nav-item">
                                <a href="keranjang.html" class="nav-link">
                                    <i class="ico ico-cart"></i>
                                    <span class="badge">1</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="percakapan.html" class="nav-link">
                                    <i class="ico ico-chat"></i>
                                    <span class="badge">2</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="notifDropdown" role="button" data-toggle="dropdown" data-offset="40" aria-expanded="false">
                                    <i class="ico ico-bell"></i>
                                    <span class="badge">1</span>
                                </a>
                                <div class="dropdown-menu notif-dropdown dropdown-menu-right" aria-labelledby="notifDropdown">
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3 pl-4 text-primary font-weight-bold">Notifikasi</div>
                                            <div class="col-md-8 mb-3 text-right">
                                                <a href="#" class="read-all-notif">Tandai sudah dibaca semua <i class="ico ico-check-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="dropdown-item new-notif" href="#">
                                        <p class="font-weight-bold">Lorem ipsum dolor sit amet</p>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                            sed do eiusmod tempor incididunt ut labore et dolore
                                            magna aliqua.
                                        </p>
                                        <p class="text-right mb-0">11.30</p>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <p class="font-weight-bold">Lorem ipsum dolor sit amet</p>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                            sed do eiusmod tempor incididunt ut labore et dolore
                                            magna aliqua.
                                        </p>
                                        <p class="text-right mb-0">11.30</p>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <p class="font-weight-bold">Lorem ipsum dolor sit amet</p>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                            sed do eiusmod tempor incididunt ut labore et dolore
                                            magna aliqua.
                                        </p>
                                        <p class="text-right mb-0">11.30</p>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item mobile-off">
                                <a href="login.html" class="nav-link">
                                    <i class="ico ico-user"></i>
                                </a>
                            </li>
                            <li class="nav-item btn-nav-mobile">
                                <a href="#" class="nav-link pr-0">
                                    <div class="humburger-menu">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="page-title">
            <h1>Order Jasa</h1>
        </div>

        <div class="container-md account-section">
            <div class="card card-border">
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-5 alur-order mb-5">
                            <h4>Alur Order</h4>
                            <div class="card card-shadow radius-10">
                                <div class="card-body px-5 py-4">
                                    <div class="row align-items-center">
                                        <div class="col-6 mb-4">
                                            <img src="images/alur-order-1.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Order Online</h3>
                                            <p>
                                                Order Pekerjaan Renovasi atau Pembangunan rumah anda melalui aplikasi atau website
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Survey Lokasi</h3>
                                            <p>
                                                Tim lapangan kami akan menghubungi anda untuk melakukan survey dan diskusi
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="images/alur-order-2.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="images/alur-order-3.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Pembuatan RAB</h3>
                                            <p>
                                                Tim lapangan kami akan membuatkan Rencana biaya renovasi atau bangun kepada anda
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Tanda Tangan Surat Kontrak</h3>
                                            <p>
                                                Setelah anda menyetujui harga, anda akan menandatangani kontrak
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="images/alur-order-4.svg" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 right-jasa mb-5">
                            <div class="jasa-content">
                                <div class="nav-scroller">
                                    <ul class="nav justify-content-center nav-tab-rounded">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link nav-design active">DENGAN DESAIN</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link nav-non-design">TANPA DESAIN</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-5">
                                    <form onsubmit="get_action(this);" method="POST" enctype="multipart/form-data" id="formorder">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" id="totalHarga" name="totalHarga">
                                        <input type="hidden" id="order" name="tipe_order">
                                        <input type="hidden" id="lang" name="lat">
                                        <input type="hidden" id="long" name="long">
                                        <input type="hidden" id="jenis" name="jenis_order" value="<?= $jenis ?>">
                                        <div class="collapse show" id="designOrder">
                                            <div class="mb-5">
                                                <p class="text-22 text-primary font-weight-bold">Pilih Tipe Rumah</p>

                                                <div class="type-rumah">
                                                    <?php foreach ($tipe_rumah as $tr) : ?>
                                                        <div class="custom-control custom-radio type-radio">
                                                            <input type="radio" id="type<?= $tr->id ?>" name="customRadioInline" class="custom-control-input">
                                                            <label class="custom-control-label" for="type<?= $tr->id ?>">
                                                                <?= $tr->type ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <p class="text-22 text-primary font-weight-bold">Denah Rumah</p>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="images-upload">
                                                            <input type="file" id="denahRumah" name="denah" hidden="" accept="image/*">
                                                            <label for="denahRumah" class="btn box-label">
                                                                <div class="label-inner">
                                                                    <i class="ico ico-picture"></i>
                                                                    <div class="mt-2">Upload Denah Rumah</div>
                                                                </div>
                                                                <div class="img-holder" data-image="denahRumah"></div>
                                                            </label>
                                                        </div>
                                                        <div class="text-grey mt-3"><i>*Format ( JPG/JPEG/PNG )</i></div>
                                                    </div>
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Ruang Keluarga
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="ruang_keluarga"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Kamar Tidur
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="kamar_tidur"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Kamar Mandi
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="kamar_mandi"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Dapur
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="dapur"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <p class="text-22 text-primary font-weight-bold">Detail Bangunan</p>

                                            <div class="input-inline mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Luas Bangunan" name="luas" id="luasbang" required>
                                                <span class="input-icon">
                                                    <i class="ico ico-m2"></i>
                                                </span>
                                            </div>
                                            <div class="images-upload">
                                                <input type="file" id="fotoRumah" hidden="" accept="image/*" name="gambar_rumah" required>
                                                <label for="fotoRumah" class="btn foto-rumah">
                                                    <div class="label-inner">
                                                        <div class="w-100">Upload Foto Rumah</div>
                                                        <i class="ico ico-picture"></i>
                                                    </div>
                                                    <div class="img-holder" data-image="fotoRumah"></div>
                                                </label>
                                            </div>
                                            <div class="mt-2 mb-3 text-grey"><i>*Format ( JPG/JPEG/PNG )</i></div>

                                            <div class="mb-3">
                                                <textarea class="form-control form-shadow" cols="30" rows="4" placeholder="Deskripsi" name="deskripsi" required></textarea>
                                                <div class="mt-2 text-grey"><i>*Jelaskan sedetail mungkin kebutuhan anda, spesifikasi dan juga
                                                        budget pembangunan anda</i></div>
                                            </div>
                                            <div class="mb-3">
                                                <input id="autocomplete" class="form-control form-shadow controls" type="text" placeholder="Alamat" name="alamat" />
                                            </div>

                                            <div class="form-group mb-4">
                                                <div id="mapSearch"></div>
                                            </div>

                                            <div class="py-4 text-center">
                                                <p class="text-20">Ingin tahu harga real?</p>
                                                <p class="text-20 mb-0">Yuk Konsultasi dan Survey <b>GRATIS !</b></p>
                                            </div>

                                            <div class="py-5">
                                                <p class="text-22 text-primary font-weight-bold">Isi Data Order</p>
                                                <div class="form-group">
                                                    <select class="choose-spec w-100">
                                                        <option></option>
                                                        <?php $no = 1;
                                                        $bnyk = count($spek);
                                                        foreach ($spek as $s) : ?>
                                                            <option value="spesifikasi<?= $no ?>">Spesifikasi <?= ucfirst($s->type_price); ?></option>
                                                        <?php $no++;
                                                        endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <?php $no = 1;
                                                    foreach ($spek as $s) : ?>
                                                        <div class="spec-lst" data-spec="<?= $no ?>">
                                                            <a href="#spesifikasi<?= $no ?>" data-toggle="collapse" aria-expanded="false">
                                                                Lihat Spesifikasi
                                                            </a>
                                                            <div class="collapse" id="spesifikasi<?= $no ?>">
                                                                <div class="spec-content card p-3 mt-2">
                                                                    <p class="font-weight-bold text-dark">Harga /<?= $s->satuan ?> : Rp <?= number_format($s->product_price, 2); ?> m2</p>
                                                                    <?= $s->spesifikasi ?>
                                                                    <!-- Struktur: <br>
                                                                    <ul class="pl-3 mt-0">
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                    </ul>

                                                                    Dinding: <br>
                                                                    <ul class="pl-3 mt-0">
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                        <li>Lorem ipsum</li>
                                                                    </ul> -->

                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php $no++;
                                                    endforeach; ?>
                                                </div>
                                                <div class="form-group">
                                                    <select class="methode_pembayaran w-100">
                                                        <option></option>
                                                        <option value="cash">Cash</option>
                                                        <option value="kredit">KPR</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Nama Lengkap" name="nama_lengkap" <?php if ($nama != null) {
                                                                                                                                                            echo 'value=' . $nama;
                                                                                                                                                        }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Nomor Telepon" name="telepon" <?php if ($phone != null) {
                                                                                                                                                        echo 'value=' . $phone;
                                                                                                                                                    }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="email" class="form-control form-shadow" placeholder="Email" name="email" <?php if ($email != null) {
                                                                                                                                                echo 'value=' . $email;
                                                                                                                                            }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Kode Promo" name="promo">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Kode Referal" name="referal">
                                                </div>

                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label text-grey" for="customCheck1">
                                                    <span></span>
                                                    Saya ingin mendapatkan informasi Promosi, Katalog dan
                                                    Newslatter
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block btn-lg btn-rounded py-3 mt-5">SUBMIT</button>
                                            <p class="text-22 text-primary font-weight-bold mt-5">Estimasi Harga</p>
                                            <div class="spec">
                                                <div class="spec-content border-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            Luas Rumah
                                                        </div>
                                                        <div class="col-md-6" id="nluas">
                                                            : 0 m2
                                                        </div>
                                                        <div class="col-md-6">
                                                            Estimasi Biaya
                                                        </div>
                                                        <div class="col-md-6" id="nharga">
                                                            : Rp. 0
                                                        </div>
                                                        <div class="col-md-6">
                                                            Jenis Pekerjaan
                                                        </div>
                                                        <div class="col-md-6">
                                                            : <?= $jenis ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="btn-whatsapp">
        <a href="#">
            <div class="whatsapp-inner">
                <i class="ico ico-whatsapp"></i> Konsultasi Gratis
            </div>
        </a>
    </div>

    <footer class="footer">
        <div class="section-inner py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="#">
                        <img src="images/logo-mitrarenov-white.svg" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-lg-5">
                            <h5>Info Kontak</h5>

                            <div class="d-flex mb-2 align-items-center">
                                <div class="icon-cnt">
                                    <i class="ico ico-phone-white"></i>
                                </div>
                                <div class="pl-3">0822 9000 9990</div>
                            </div>
                            <div class="d-flex mb-2 align-items-center">
                                <div class="icon-cnt">
                                    <i class="ico ico-location"></i>
                                </div>
                                <div class="pl-3">
                                    Rukan Taman Pondok Kelapa Blok F No.1, Jaktim
                                </div>
                            </div>
                            <div class="d-flex mb-2 align-items-center">
                                <div class="icon-cnt">
                                    <i class="ico ico-mail-white"></i>
                                </div>
                                <div class="pl-3">
                                    info@mitrarenov.com
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-7">
                            <h5>Peroleh Bantuan</h5>
                            <div class="row">
                                <div class="col-lg-7">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link px-0">
                                                Informasi pengiriman
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Syarat & ketentuan penjualan
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Pengembalian uang
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Pemberitahuan privasi
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                FAQ Belanja
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-5">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link px-0">
                                                Partner
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Disclaimer
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                User Privacy
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Application privacy
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Complaint
                                            </a>
                                            <a href="#" class="nav-link px-0">
                                                Blog
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-lg-5">
                            <h5>PARTNER</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link px-0">
                                        Daftar Rekanan
                                    </a>
                                    <a href="#" class="nav-link px-0">
                                        Karir
                                    </a>
                                    <a href="#" class="nav-link px-0">
                                        Perawatan Rumah
                                    </a>
                                    <a href="#" class="nav-link px-0">
                                        Interior
                                    </a>
                                    <a href="#" class="nav-link px-0">
                                        Pengurusan IMB
                                    </a>
                                    <a href="#" class="nav-link px-0">
                                        Jasa Arsitek
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-7">
                            <h5 class="mb-3">Ikuti Kami di Sosial media</h5>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-facebook"></i>
                            </a>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-instagram"></i>
                            </a>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-twitter"></i>
                            </a>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-youtube"></i>
                            </a>

                            <h5 class="mt-4 mb-3">Unduh Aplikasi</h5>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-playstore"></i>
                            </a>
                            <a href="#" class="mr-4 mb-3">
                                <i class="ico ico-ios"></i>
                            </a>

                            <h5 class="mt-5 mb-3">TETAP TERHUBUNG</h5>
                            <div class="newsletter">
                                <input type="text" class="form-control" placeholder="Masukkan email anda disini">
                                <button type="submit" class="btn">LANGGANAN</button>
                            </div>
                            <p class="mt-3">
                                Tetap up to date dengan berita terbaru
                                dan penawaran khusus kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="acc">
                <h5>We accept:</h5>
                <img src="images/Logo-Midtrans.svg" class="img-fluid" alt="">
            </div>

        </div>
        <hr>
        <div class="section-inner py-3">
            <div class="row">
                <div class="col-md-6">
                    ©2021, mitrarenov
                </div>
                <div class="col-md-6 text-right">
                    All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript" src="./js/script-bundle.min.js"></script>
    <script type="text/javascript" src="js/leaflet.js"></script>
    <script type="text/javascript" src="js/leaflet-src.js"></script>
    <script type="text/javascript" src="js/esri-leaflet-debug.js"></script>
    <script type="text/javascript" src="js/esri-leaflet-geocoder-debug.js"></script>
    <script type="text/javascript" src="./js/script.min.js"></script>
    <!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn4Ah0LbR1ArWtKBbXUZm6Sovv85JDlqU&callback=initialize&libraries=places&v=weekly"
    defer></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initialize&key=AIzaSyCn4Ah0LbR1ArWtKBbXUZm6Sovv85JDlqU" defer></script>
    <script>
        const formatRupiah = (money) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(money);
        };

        $('#luasbang').on('keyup', function() {
            nluas = $("#luasbang").val();
            nharga = $('option:selected', '#spek').attr('data-harga');

            $('#nluas').text(': ' + nluas + ' m2');

            if (nharga == null || nharga == 0) {
                nharga = 1;
            }
            var total = nluas * nharga;
            $("#totalHarga").val(total);
            $('#nharga').text(': ' + formatRupiah(total));
        });

        function initialize() {
            geolocate();
            initMap();
            initAutocomplete();
        }

        var map, marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('mapSearch'), {
                center: {
                    lat: -6.200000,
                    lng: 106.816666
                },
                zoom: 17
            });
        }

        function initAutocomplete() {
            autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('autocomplete')), {
                    types: ['geocode']
                });
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            if (!marker) {
                marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });
            } else marker.setMap(null);
            marker.setOptions({
                position: place.geometry.location,
                map: map
            });
        }

        function geolocate() {
            infoWindow = new google.maps.InfoWindow();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        var marker = new google.maps.Marker({
                            position: pos,
                            map: map,
                        });
                        infoWindow.setPosition(pos);
                        infoWindow.setContent("Lokasi Anda.");
                        infoWindow.open(map, marker);
                        map.setCenter(pos);
                        map.setZoom(17);
                        var google_maps_geocoder = new google.maps.Geocoder();
                        google_maps_geocoder.geocode({
                                'latLng': pos
                            },
                            function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK && results[0]) {
                                    $('#autocomplete').val(results[0].formatted_address);
                                }
                            }
                        );
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }
        $('.spec-lst').hide();
        $('.type-rumah').slick({
            centerMode: true,
            slidesToShow: 2,
            arrows: false,
            // variableWidth: true
        });
        $(document).ready(function() {
            $("#form-order").on("keypress", function(event) {
                var keyPressed = event.keyCode || event.which;
                if (keyPressed === 13) {
                    event.preventDefault();
                    return false;
                }
            });
            $(".choose-spec").select2({
                placeholder: "Pilihan Spesifikasi",
                minimumResultsForSearch: -1,
                selectionCssClass: "form-shadow",
            }).on('select2:select', function(e) {
                var data = e.params.data.element;
                var banyak = <?= $bnyk ?>;
                for (let index = 1; index < banyak + 1; index++) {
                    if (data.value == i) {
                        $('.spec-lst').hide();
                        $('.spec-lst[data-spec="' + i + '"').show();
                    }

                }
                // if (data.value == 1) {
                //     $('.spec-lst').hide();
                //     $('.spec-lst[data-spec="1"').show();
                // } else if (data.value == 2) {
                //     $('.spec-lst').hide();
                //     $('.spec-lst[data-spec="2"').show();
                // } else if (data.value == 3) {
                //     $('.spec-lst').hide();
                //     $('.spec-lst[data-spec="3"').show();
                // }
            });;

            $(".methode_pembayaran").select2({
                placeholder: "Methode Pembayaran",
                minimumResultsForSearch: -1,
                selectionCssClass: "form-shadow",
            });
        });

        $('.nav-design').click(function(e) {
            e.preventDefault();
            $('.nav-tab-rounded .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#designOrder').collapse('show')
        })
        $('.nav-non-design').click(function(e) {
            e.preventDefault();
            $('.nav-tab-rounded .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#designOrder').collapse('hide')
        })

        $('.images-upload').click(function() {
            var x = $(this).find('input[type="file"]').attr("id");
            $("#" + x).change(function() {
                if (typeof(FileReader) != "undefined") {
                    var image_holder = $("[data-image='" + x + "']");
                    image_holder.empty();
                    image_holder.addClass('value-img');
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "thumb-image"
                        }).appendTo(image_holder);

                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("This browser does not support FileReader.");
                }
            })
        });
    </script>
</body>

</html>