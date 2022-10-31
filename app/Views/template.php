<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= base_url('public/main/css/custom.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/main/css/styles.css') ?>">
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link rel="icon" type="image/png" href="<?= base_url('public/main/images/favico.png ') ?>" />
  <!-- toast -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <title>Mitrarenov</title>
</head>

<body oncontextmenu="return false;">
  <header class="header">
    <div class="header-top">
      <div class="header-inner">
        <div class="d-flex align-items-center">
          <div class="w-100">
            Belum memiliki akun ? <a href="<?= base_url('member/register') ?>" class="text-warning font-weight-bold">Registrasi sekarang</a>
          </div>
          <div class="w-100 text-right">
            <ul class="nav justify-content-end">
              <li class="nav-item">
                <a href=" https://bit.ly/whatsapp-mitrarenov" target="_blank" class="nav-link">
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
            <a href="<?= base_url('/') ?>">
              <img src="<?= base_url('public/main/images/logo-mitrarenov.png') ?>" class="img-fluid" alt="">
            </a>
          </div>
          <div class="header-main-nav">
            <div class="login-mobile">
              <?php $sess = session(); if ($sess->get('logged_in') != TRUE) { ?>
                <a href="<?= base_url('member/login') ?>" class="nav-link px-0">
                  <i class="ico ico-user"></i> Login / Daftar
                </a>
              <?php }else { ?>
                <a href="#" class="nav-link px-0">
                  <i class="ico ico-user"></i> <?= $sess->get('user_name') ?>
                </a>
              <?php } ?>
            </div>
            <?php $currentURL = current_url(); ?>
            <ul class="nav main-nav">
              <li class="nav-item">

                <a href="<?= base_url() . '/' ?>" class="nav-link <?php if ($currentURL == base_url() . '/') echo "active"; ?>">Home</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('simulasi-kpr') ?>" class="nav-link <?php if ($currentURL == base_url('simulasi-kpr')) echo "active"; ?>">Simulasi KPR</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('tentang-kami') ?>" class="nav-link <?php if ($currentURL == base_url('tentang-kami')) echo "active"; ?>">Tentang Kami</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/#ckk') ?>" class="nav-link <?php if ($currentURL == base_url('/#ckk')) echo "active"; ?>">Cara Kerja</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('kontak') ?>" class="nav-link <?php if ($currentURL == base_url('kontak')) echo "active"; ?>">Hubungi Kami</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('artikel') ?>" class="nav-link <?php if ($currentURL == base_url('artikel')) echo "active"; ?>">Artikel</a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/#jasa') ?>" class="nav-link <?php if ($currentURL == base_url('/#jasa')) echo "active"; ?>">Order Jasa</a>
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
                <!-- <a href="keranjang.html" class="nav-link">
                  <i class="ico ico-cart"></i>
                  <span class="badge">1</span>
                </a> -->
              </li>
              <?php if ($sess->get('logged_in') == TRUE) { ?>
                <li class="nav-item">
                  <a href="<?= base_url('chat') ?>" class="nav-link">
                    <i class="ico ico-chat"></i>
                      
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" id="notifDropdown" role="button" data-toggle="dropdown" data-offset="40" aria-expanded="false">
                    <i class="ico ico-bell"></i>
                    <span class="badge" <?= $notif_total == 0 ? "hidden": "";?>><?= $notif_total ?></span>
                  </a>
                  <div class="dropdown-menu notif-dropdown dropdown-menu-right" aria-labelledby="notifDropdown">
                    <div class="mt-3">
                      <div class="row">
                        <div class="col-md-4 mb-3 pl-4 text-primary font-weight-bold">Notifikasi</div>
                        <div class="col-md-8 mb-3 text-right">
                          <a onclick="seenallnotif()" class="read-all-notif">Tandai sudah dibaca semua <i class="ico ico-check-circle"></i></a>
                        </div>
                      </div>
                    </div>
                    <div class="notif-list">
                      <?php foreach ($notif as $key => $value) { ?>
                          <?php if($value->kategori == "chat"){ 
                              $link=base_url('notif/chat/'.$value->id); 
                          }else if($value->kategori == "Project"){ 
                              $link=base_url('notif/project/'.$value->id);
                          }else if($value->kategori == "ProjectUpdate"){ 
                            $link=base_url('notif/project/'.$value->id); 
                          }else if($value->kategori == "promo"){ 
                            $link=base_url('detail-promo/'.$value->id_kategori); 
                          }else if($value->kategori == "transaction"){ 
                            $link=base_url('notif/transaction/'.$value->id); 
                        } ?>                                   
                          <?php if($value->status == 0){ ?>  
                              <a class="dropdown-item new-notif" href="<?= $link ?>">
                          <?php }else{ ?>    
                              <a class="dropdown-item" href="<?= $link ?>">
                          <?php } ?>  
                              <p class="font-weight-bold"><?= $value->kategori ?></p>
                              <p>
                                  <?= $value->message; ?>
                              </p>
                              <p class="text-right mb-0"><?= $value->date ?></p>
                          </a>
                      <?php } ?>
                    </div>                                            
                  </div>
                </li>
              <?php } ?>
              <?php if ($sess->get('logged_in') == FALSE) { ?>
                <li class="nav-item mobile-off">
                  <a href="<?= base_url('member/login') ?>" class="nav-link btn btn-outline-primary ml-3">
                    Login
                  </a>
                </li>
                <li class="nav-item mobile-off">
                  <a href="<?= base_url('member/register') ?>" class="nav-link btn btn-primary ml-3">
                    Register
                  </a>
                </li>
              <?php } else { ?>
                <li class="nav-item mobile-off">
                  <a href="<?= base_url('member/akun') ?>" class="nav-link">
                    <i class="ico ico-user"></i>
                  </a>
                </li>
              <?php } ?>
              <!-- <li class="nav-item mobile-off">
                <?php if ($sess->get('logged_in') == FALSE) { ?>
                  <a href="<?= base_url('member/login') ?>" class="nav-link btn btn-primary ml-3">
                    Login
                  </a>
                <?php } else { ?>
                  <a href="<?= base_url('member/logout') ?>" class="nav-link">
                    <i class="ico ico-user"></i>
                  </a>
                <?php } ?>
              </li> -->
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

  <?= $this->renderSection('content') ?>

  <div class="btn-whatsapp">
    <a href=" https://bit.ly/whatsapp-mitrarenov" target="_blank">
      <div class="whatsapp-inner">
        <i class="ico ico-whatsapp"></i>
      </div>
    </a>
  </div>

  <footer class="footer">
    <div class="section-inner py-5">
      <div class="row">
        <div class="col-12 mb-4">
          <a href="#">
            <img src="<?= base_url('public/main/images/logo-mitrarenov-white.svg') ?>" class="img-fluid" alt="">
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
              <!-- <h5>Peroleh Bantuan</h5>
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
              </div> -->
              <h5>Peroleh Bantuan</h5>
              <div class="row">
                <div class="col-lg-7">
                  <ul class="nav flex-column">
                    <li class="nav-item">
                      <a href="<?= base_url('tentang-kami') ?>" class="nav-link px-0">
                        Tentang Kami
                      </a>
                      <a href="<?= base_url('kontak') ?>" class="nav-link px-0">
                        Hubungi Kami
                      </a>
                      <a href="#" class="nav-link px-0">
                        Pemberitahuan privasi
                      </a>
                      <a href="#" class="nav-link px-0">
                        Syarat & Ketentuan
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-5">
                  <ul class="nav flex-column">
                    <li class="nav-item">
                      <a href="<?= base_url('kontak') ?>" class="nav-link px-0">
                        Partner
                      </a>
                      <a href="<?= base_url('simulasi-kpr') ?>" class="nav-link px-0">
                        Simulasi KPR
                      </a>
                      <a href="#" class="nav-link px-0">
                        Bank Rekanan
                      </a>
                      <a href="#" class="nav-link px-0">
                        Tanya Jawab
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
              <!-- <h5>PARTNER</h5>
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
              </ul> -->
            </div>
            <div class="col-lg-7">
              <h5 class="mb-3">Ikuti Kami di Sosial media</h5>
              <a target="_BLANK" href="https://www.facebook.com/" class="mr-4 mb-3">
                <i class="ico ico-facebook"></i>
              </a>
              <a target="_BLANK" href="https://www.instagram.com/" class="mr-4 mb-3">
                <i class="ico ico-instagram"></i>
              </a>
              <a target="_BLANK" href="https://www.twitter.com/" class="mr-4 mb-3">
                <i class="ico ico-twitter"></i>
              </a>
              <a target="_BLANK" href="https://www.youtube.com/" class="mr-4 mb-3">
                <i class="ico ico-youtube"></i>
              </a>
              <a target="_BLANK" href="https://www.tiktok.com/" class="mr-4 mb-3">
                <i class="ico ico-tiktok"></i>
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
                Tetap up to date dengan berita terbaru dan penawaran khusus kami.
              </p>
            </div>

          </div>
        </div>
      </div>
      <h5>We accept:</h5>
      <div class="payment-bank">
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/mastercard-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/visa-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/shopepay-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/akulaku-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/uob-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/octo-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/bca-clickpay-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/indomaret-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/alfamart-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/bri-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/bca-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/bca-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/bni-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/mandiri-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="bank-logo">
          <img src="<?php echo base_url('public/main/images/payment/danamon-logo.png'); ?>" class="img-fluid" alt="">
        </div>
        <div class="row payment-options">

          <div class="col-3 mb-2">

          </div>
          <div class="col-3 mb-2">

          </div>
          <div class="col-3 mb-2">

          </div>
          <div class="col-3 mb-2">

          </div>
        </div>
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

  <script type="text/javascript" src="<?= base_url('public/main/js/script-bundle.min.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/main/js/leaflet.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/main/js/leaflet-src.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/main/js/esri-leaflet-debug.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/main/js/esri-leaflet-geocoder-debug.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/main/js/script.js') ?>"></script>
  <!-- toast -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <?= $this->renderSection('script') ?>
  <script>
    $(document).ready(() => {
      <?php if (session()->get('toast')) { ?>
        toastr.options.closeButton = true;
        var toastvalue = "<?php echo session()->get('toast') ?>";
        var status = toastvalue.split(":")[0];
        var message = toastvalue.split(":")[1];
        if (status === "success") {
          toastr.success(message, status);
        } else if (status === "error") {
          toastr.error(message, status);
        } else if (status == "warn") {
          toastr.warning(message, status);
        }
      <?php } ?>
    });
  </script>
  <script>
    function closePopup() {
      $.magnificPopup.close();
    }
    $("#pekerjaanGallery, #portofolioGallery, #desainGallery").magnificPopup({
      type: 'image',
      delegate: 'a',
      closeOnContentClick: false,
      closeOnBgClick: false,
      tLoading: 'Loading',
      mainClass: 'mfp-zoom-in mfp-img-mobile',
      image: {
        markup: '<div class="mfp-figure">' +
          '<button type="button" class="close-gallery" onClick="closePopup();"><i class="ico ico-close"></i></button>' +
          '<div class="gallery-container-popup">' +
          '<div class="mfp-img"></div>' +
          '<div class="content-gl">' +
          '<div class="mfp-title"></div>' +
          '<div class="mfp-author"></div>' +
          '</div>' +
          '</div>' +
          '<div class="mfp-description"></div>' +
          '</div>',
        // cursor: 'mfp-zoom-out-cur',
        tError: '<a href="%url%">The image</a> could not be loaded.', // Error message
      },
      preloader: true,

      gallery: {
        enabled: true,
        arrowMarkup: '<button type="button" class="mfp-arrow-%dir%">%title%</button>', // markup of an arrow button
        tPrev: '<i class="ico ico-prev"></i>', // title for left button
        tNext: '<i class="ico ico-next"></i>', // title for right button
      },
      zoom: {
        enabled: true,
        duration: 300
      },
      // removalDelay: 300,
      callbacks: {
        change: function() {
          $(this.content)
            .find('.mfp-description')
            .html($(this.currItem.el).attr('data-description'));
          $(this.content)
            .find('.mfp-author')
            .html($(this.currItem.el).attr('data-author'));
        }
      }
    });
  </script>
  <script>
function seenallnotif(){
    $.ajax({
          method: "POST",
          url: "<?php echo base_url('seenallnotif');?>",
          success:function(data){
            console.log("remove class");
            $("a").removeClass("new-notif");
          },
        });

}
</script>
</body>

</html>