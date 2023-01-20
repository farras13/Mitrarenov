<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
<title>MITRARENOV – 0822 9000 9990 – Jasa Bangun Rumah, Jasa Renovasi Rumah, Jasa Kontraktor Rumah, Jasa Bangun Ruko, Jasa Renovasi Kantor, Jasa Tukang Borongan, Jasa Pemborong Bangunan Rumah Kost, Gudang, dan Gedung</title>
<meta name="description" content="PROMO Mitrarenov - Jasa Kontraktor bangun dan renovasi rumah MURAH hingga MEWAH, TERPERCAYA, BERPENGALAMAN dan BERGARANSI dengan pekerjaan Jasa Desain Rumah dan Jasa Arsitek Rumah di area DKI Jakarta, Bogor, Depok, Tangerang, Bekasi, BSD, Bandung, Cirebon, Semarang, Yogyakarta dan Surabaya ">
<meta name="keywords" content="jasa bangun rumah, jasa bangun rumah murah, kontraktor bangun rumah, jasa renovasi rumah, jasa renovasi rumah murah, kontraktor renovasi rumah, jasa kontraktor rumah, kontraktor jasa renovasi rumah, kontraktor jasa bangun rumah, jasa bangun ruko, kontraktor bangun ruko, jasa renovasi kantor, kontraktor renovasi kantor, jasa renovasi rumah bekasi, jasa bangun rumah bekasi, jasa kontraktor rumah bekasi, kontraktor rumah bekasi, cari tukang bangunan yang bagus, tukang bangunan rumah, jasa pemborong rumah, jasa tukang borongan, ">
<?= $this->endSection(); ?>
<?= $this->section('css') ?>
<style>
  .testi-content a {
    color: white;
  }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
  <div class="main-banner position-relative desktop-slider">
    <div class="top-banner">
      <?php foreach ($promo as $p) : if ($p->kategori == 2) : ?>
          <div class="slick-slide slide-item">
            <div class="banner-top-container">
              <a href="<?= base_url('detail-promo') . '/' . $p->id ?>"><img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/promo/<?= $p->image ?>" class="w-100" alt=""></a>
              <div class="slide-content">
                <!-- <div class="discount-badge">
                  Diskon <?= $p->promo ?>%
                </div> -->
                <div class="slide-text">
                  <div class="row">
                    <div class="col-md-8">
                      <!-- <?php if ($p->head == 1) { ?>
                        <h1><?= $p->title ?></h1>
                      <?php } elseif ($p->head == 2) { ?>
                        <h2><?= $p->title ?></h2>
                      <?php } elseif ($p->head == 3) { ?>
                        <h3><?= $p->title ?></h3>
                      <?php } elseif ($p->head == 4) { ?>
                        <h4><?= $p->title ?></h4>
                      <?php } elseif ($p->head == 5) { ?>
                        <h5><?= $p->title ?></h5>
                      <?php } elseif ($p->head == 6) { ?>
                        <h6><?= $p->title ?></h6>
                      <?php } elseif ($p->head == 7) { ?>
                        <p><?= $p->title ?></p>
                      <?php } ?>
                      <?php $date = new DateTime($p->expired); ?>
                      <p class="mb-0">Masa berlaku s/d <?= $date->format('F Y'); ?></p> -->
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php else : ?>
          <div class="slick-slide slide-item">
            <div class="banner-top-container">
              <a href="#"><img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/promo/<?= $p->image ?>" class="w-100" alt=""></a>
              <div class="slide-content">
                <!-- <div class="discount-badge">
                Diskon <?= $p->promo ?>%
              </div> -->
                <div class="slide-text">
                  <div class="row">
                    <div class="col-md-8">
                      <!-- <?php if ($p->head == 1) { ?>
                        <h1><?= $p->title ?></h1>
                      <?php } elseif ($p->head == 2) { ?>
                        <h2><?= $p->title ?></h2>
                      <?php } elseif ($p->head == 3) { ?>
                        <h3><?= $p->title ?></h3>
                      <?php } elseif ($p->head == 4) { ?>
                        <h4><?= $p->title ?></h4>
                      <?php } elseif ($p->head == 5) { ?>
                        <h5><?= $p->title ?></h5>
                      <?php } elseif ($p->head == 6) { ?>
                        <h6><?= $p->title ?></h6>
                      <?php } elseif ($p->head == 7) { ?>
                        <p><?= $p->title ?></p>
                      <?php } ?> -->
                      <!-- <?php $date = new DateTime($p->expired); ?>
                    <p class="mb-0">Masa berlaku s/d <?= $date->format('F Y'); ?></p> -->
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

    </div>
    <div class="btn-slide btn-prev"><i class="ico ico-prev"></i></div>
    <div class="btn-slide btn-next"><i class="ico ico-next"></i></div>
  </div>
  <div class="main-banner position-relative mobile-slider">
    <div class="top-banner-mobile">
      <?php foreach ($promo as $p) : if ($p->kategori == 2) : ?>
          <div class="slick-slide slide-item">
            <div class="banner-top-container">
              <a href="<?= base_url('detail-promo') . '/' . $p->id ?>"><img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/promo/<?= $p->imagecontent ?>" class="w-100" alt=""></a>
              <div class="slide-content">
                <div class="discount-badge">
                  Diskon <?= $p->promo ?>%
                </div>
                <div class="slide-text">
                  <div class="row">
                    <div class="col-md-8">
                      <?php if ($p->head == 1) { ?>
                        <h1><?= $p->title ?></h1>
                      <?php } elseif ($p->head == 2) { ?>
                        <h2><?= $p->title ?></h2>
                      <?php } elseif ($p->head == 3) { ?>
                        <h3><?= $p->title ?></h3>
                      <?php } elseif ($p->head == 4) { ?>
                        <h4><?= $p->title ?></h4>
                      <?php } elseif ($p->head == 5) { ?>
                        <h5><?= $p->title ?></h5>
                      <?php } elseif ($p->head == 6) { ?>
                        <h6><?= $p->title ?></h6>
                      <?php } elseif ($p->head == 7) { ?>
                        <p><?= $p->title ?></p>
                      <?php } ?>
                      <?php $date = new DateTime($p->expired); ?>
                      <p class="mb-0">Masa berlaku s/d <?= $date->format('F Y'); ?></p>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php else : ?>
          <div class="slick-slide slide-item">
            <div class="banner-top-container">
              <a href="#"><img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/promo/<?= $p->imagecontent ?>" class="w-100" alt=""></a>
              <div class="slide-content">
                <!-- <div class="discount-badge">
                Diskon <?= $p->promo ?>%
              </div> -->
                <div class="slide-text">
                  <div class="row">
                    <div class="col-md-8">
                      <?php if ($p->head == 1) { ?>
                        <h1><?= $p->title ?></h1>
                      <?php } elseif ($p->head == 2) { ?>
                        <h2><?= $p->title ?></h2>
                      <?php } elseif ($p->head == 3) { ?>
                        <h3><?= $p->title ?></h3>
                      <?php } elseif ($p->head == 4) { ?>
                        <h4><?= $p->title ?></h4>
                      <?php } elseif ($p->head == 5) { ?>
                        <h5><?= $p->title ?></h5>
                      <?php } elseif ($p->head == 6) { ?>
                        <h6><?= $p->title ?></h6>
                      <?php } elseif ($p->head == 7) { ?>
                        <p><?= $p->title ?></p>
                      <?php } ?>
                      <!-- <?php $date = new DateTime($p->expired); ?>
                    <p class="mb-0">Masa berlaku s/d <?= $date->format('F Y'); ?></p> -->
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

    </div>
    <!-- <div class="btn-slide btn-prev"><i class="ico ico-prev"></i></div>
    <div class="btn-slide btn-next"><i class="ico ico-next"></i></div> -->
  </div>
  <div class="section section-1 pt-4 pb-5" id="ckk">
    <div class="text-center">
      <h2 class="title text-primary">Cara Kerja Kami</h2>
    </div>
    <div class="section-inner">
      <div class="row align-items-center">
        <?php $no = 1;
        foreach ($alur as $a) { ?>
          <div class="col-md-6 my-4">
            <div class="d-flex align-items-center">
              <div class="icon-how">
                <img src="<?= $a->image ?>" class="img-fluid" alt="">
              </div>
              <div class="w-100 pl-4">
                <h4><span><?= $no; ?>.</span> <?= $a->type; ?></h4>
                <p class="mb-0">
                  <?= $a->description; ?>
                </p>
              </div>
            </div>
          </div>

        <?php $no++;
        } ?>
      </div>
    </div>
  </div>

  <div class="section section-2 py-4">
    <h3 class="title text-primary text-center mb-5">
      Keunggulan Kami
    </h3>
    <div class="section-inner">
      <div class="row">
        <?php foreach ($keunggulan as $k) { ?>
          <div class="col-md-4 col-6 text-center">
            <div class="image-keunggulan mb-4">
              <img src="<?= $k->image ?>" class="img-fluid" alt="">
            </div>
            <h4 class="text-primary">
              <?= $k->keunggulan; ?>
            </h4>
            <p class="px-5">
              <?= $k->deskripisi; ?>
            </p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="section section-3 py-5" id="jasa">
    <div class="section-inner">
      <div class="text-center">
        <h2 class="title text-primary">Pilihan Jasa</h2>
      </div>
      <div class="row align-items-center justify-content-center mt-4">
        <!-- <div class="col-md-3 order-md-1 order-2 category-btn-cn">
          <a href="#" class="btn btn-category px-4 py-3"><i class="ico ico-menu mr-3"></i> SEMUA KATEGORI</a>
        </div> -->
        <div class="col-md-9 order-md-2 order-1">
          <div class="row row-sm">
            <?php foreach ($kategori as $k) : ?>
              <div class="col-lg-2 col-md-3 col-6 my-4">
                <?php $string = strtolower($k->paket_name);
                $str = str_replace(' ', '-', $string);
                if ($k->total == 1) : ?>
                  <a href="<?= base_url('jasa/' . $str) ?>" target="__BLANK">
                  <?php elseif ($k->total > 1) : ?>
                    <a href="#modal-detail-category<?= $k->id ?>" data-toggle="modal">
                    <?php endif; ?>
                    <div class="jasa-container">
                      <img src="<?= $k->image ?>" class="img-fluid" alt="">
                      <p class="mb-0"><?= $k->category_name ?></p>
                    </div>
                    </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="testimoni-wrapper">
    <div class="section section-testi">
      <h3 class="title text-center mb-3">
        Testimonial
      </h3>
      <div class="testi-inner position-relative">
        <div class="testi-slide">
          <?php foreach ($testimoni as $t) : ?>
            <div class="testi-item">
              <div class="testi-img">
                <img class="testi-img" src="https://admin.mitrarenov.soldig.co.id/assets/main/images/testimoni/<?= $t->image ?>" alt="">
              </div>
              <div class="testi-content">
                <p class="font-weight-bold mb-0"><?= $t->name ?></p>
                <p class="mb-2"><?= strip_tags($t->company) ?></p>
                <div class="text-14 testidesc">
                  <?= $t->testimoni ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="btn-slide testi-prev"><i class="ico ico-prev"></i></div>
        <div class="btn-slide testi-next"><i class="ico ico-next"></i></div>
      </div>
    </div>
  </div>

  <div class="section section-gallery py-5 mt-5">
    <div class="section-inner">
      <h3 class="title text-center text-primary">
        Gallery Progress
      </h3>

      <div class="text-center">
        <ul class="nav justify-content-center nav-gallery my-5">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#pekerjaan">Progress</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#portofolio">Portofolio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#desain_rumah">Desain</a>
          </li>
        </ul>
      </div>
      <!-- <div class="nav-gallery-mobile">
        <div>
          <a class="nav-link active" data-toggle="tab" href="#pekerjaan">Progress</a>
        </div>
        <div>
          <a class="nav-link" data-toggle="tab" href="#portofolio">Portofolio</a>
        </div>
        <div>
          <a class="nav-link" data-toggle="tab" href="#desain_rumah">Desain</a>
        </div>
      </div> -->
      <div class="tab-content">
        <div class="tab-pane fade show active" id="pekerjaan">
          <div class="row" id="pekerjaanGallery">
            <?php foreach ($galery as $g) { ?>
              <div class="col-md-3 mb-4 col-gallery">
                <a href="https://admin.mitrarenov.soldig.co.id/assets/main/images/photo_promo_paket/<?= $g->image ?>" class="gallery-item" title="<?= $g->judul ?>" data-description="<?= $g->judul ?>">
                  <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/photo_promo_paket/<?= $g->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $g->judul ?></h5>
                    <p class="mb-0"></p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
          <div class="text-center mt-4">
            <a href="<?= base_url('gallery'); ?>" class="readmore-link" id="morePortfolio">Lihat Selengkapnya</a>
          </div>
        </div>
        <div class="tab-pane fade" id="portofolio">
          <div class="row">
            <?php foreach ($merawat as $m) { ?>
              <div class="col-md-3 mb-4">
                <a href="<?= base_url('portofolio/' . $m->slug); ?>" class="gallery-item">
                  <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/merawat/<?= $m->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $m->title ?></h5>
                    <p class="mb-0"></p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
          <div class="text-center mt-4">
            <a href="<?= base_url('portofolio'); ?>" class="readmore-link" id="morePortfolio">Lihat Selengkapnya</a>
          </div>
        </div>

        <div class="tab-pane fade" id="desain_rumah">
          <div class="row" id="desainGallery">
            <?php foreach ($design_rumah as $dr) { ?>
              <div class="col-md-3 mb-4 col-gallery-desain">
                <a href="https://admin.mitrarenov.soldig.co.id/assets/main/images/design_rumah/<?= $dr->image ?>" class="gallery-item" title="<?= $dr->title ?>" data-description="<?= $dr->title ?>">
                  <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/design_rumah/<?= $dr->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $dr->title ?></h5>
                    <p class="mb-0"></p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
          <div class="text-center mt-4">
            <a href="<?= base_url('desain_rumah'); ?>" class="readmore-link" id="morePortfolio">Lihat Selengkapnya</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section section-liputan position-relative my-5">
    <h3 class="title text-center text-primary">
      Diliput Oleh
    </h3>
    <div class="liputan-slide ">
      <?php foreach ($liputan as $lp) { ?>
        <div class="client-item">
          <a href="<?= $lp->judul; ?>" target="_blank"> <img src="<?= $lp->image; ?>" class="img-fluid" alt=""></a>
        </div>
      <?php } ?>
    </div>
    <div class="btn-slide liputan-prev"><i class="ico ico-prev"></i></div>
    <div class="btn-slide liputan-next"><i class="ico ico-next"></i></div>
  </div>


  <div class="section-blog py-5 mt-5">
    <h3 class="title text-center text-primary">
      Artikel Populer dan Terbaru
    </h3>
    <div class="section-inner position-relative mt-5">
      <div class="article-slide">
        <?php foreach ($artikel as $art) : ?>
          <div class="article-item">
            <div class="article-img">
              <img src="<?= "https://admin.mitrarenov.soldig.co.id/assets/main/images/news/" . $art->image ?>" alt="">
            </div>
            <div class="article-dsc">
              <h4><?= $art->title ?></h4>
              <p class="mb-0">Penulis <?= $art->penulis; ?></p>
              <p>Diterbitkan <?php $time = $art->date;
                              $date = new DateTime("@$time");
                              echo $date->format('d F Y'); ?></p>
              <a href="<?= base_url('berita/' . $art->slug) ?>">Baca Selengkapnya...</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="btn-slide article-prev"><i class="ico ico-prev"></i></div>
      <div class="btn-slide article-next"><i class="ico ico-next"></i></div>
    </div>
  </div>

  <div class="section section-partner my-5">
    <h3 class="title text-center text-primary">
      Partner Kami
    </h3>
    <div class="section-inner position-relative mt-5">
      <div class="client-slide">
        <?php foreach ($partner as $p) : ?>
          <div class="client-item">
            <img src="<?= "https://admin.mitrarenov.soldig.co.id/assets/main/images/partner_icon/" . $p->image ?>" class="img-fluid" alt="">
          </div>
        <?php endforeach ?>

      </div>
      <div class="btn-slide client-prev"><i class="ico ico-prev"></i></div>
      <div class="btn-slide client-next"><i class="ico ico-next"></i></div>
    </div>
  </div>

  <div class="section section-area py-5">
    <div class="d-flex align-items-center justify-content-center bg-area">
      <div class="section-inner">
        <h3 class="title text-center text-primary">
          Area Kerja Kami
        </h3>
        <div class="d-flex justify-content-center flex-wrap city-area mt-5">
          <?php foreach ($lokasi as $l) : ?>
            <a target="_blank" href="<?= $l->maps_location ?>" class="area-item"><?= $l->title ?></a>
          <?php endforeach ?>
        </div>
      </div>
    </div>

  </div>

  <div class="section section-unduh py-5">
    <h3 class="title text-center text-primary">
      Unduh Aplikasi Sekarang
    </h3>
    <div class="unduh-container mt-5">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-4 mobile-off">
          <img src="<?= base_url('public/main/images/unduh-bg-1.png') ?>" class="img-fluid" alt="">
        </div>
        <div class="col-md-4 col-10">
          <div class="d-flex download-btn">
            <a href="#">
              <img src="<?= base_url('public/main/images/google-play-btn.png') ?>" class="img-fluid" alt="">
            </a>
            <a href="#">
              <img src="<?= base_url('public/main/images/app-store-btn.png') ?>" class="img-fluid" alt="">
            </a>
          </div>
        </div>
        <div class="col-md-4 mobile-off">
          <img src="<?= base_url('public/main/images/unduh-bg-2.png') ?>" class="img-fluid" alt="">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail Category -->
<?php foreach ($kategori as $kt) : ?>
  <div class="modal fade" id="modal-detail-category<?= $kt->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-category modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="ico ico-close"></i>
          </button>

          <div class="row align-items-center">
            <div class="col-md-6 col-4">
              <img src="<?= $kt->image ?>" class="img-fluid" alt="">
            </div>
            <div class="col-md-6 col-8 text-right">
              <h4 class="mb-0 title-category-modal"><?= $kt->category_name ?></h4>
            </div>
          </div>
          <hr class="my-5">

          <h5 class="sub-title-cat">Pilihan Jasa</h5>

          <div class="row">
            <?php foreach ($jasa as $m) : if ($kt->id == $m->category_id) : $string = $m->slug;
                $str = str_replace(' ', '-', $string); ?>
                <div class="col-md-6 my-4">
                  <div class="d-flex align-items-center">

                    <div class="cat-img-i">
                      <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/product_icon/<?= $m->image_icon ?>" class="img-fluid" alt="">
                    </div>
                    <div class="w-100 text-15 pl-3">
                      <a href="<?= base_url('jasa/' . $str) ?>" target="_blank" style="color:black;"> <?= $m->paket_name ?> </a>
                    </div>

                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<?= $this->section('script') ?>
<script>
  $(document).ready(function() {
    $(".col-gallery").slice(0, 8).show();
    $("#morePekerjaan").on('click', function(e) {
      e.preventDefault();
      $(".col-gallery:hidden").slice(0, 8).slideDown();
      if ($(".col-gallery:hidden").length == 0) {
        $("#morePekerjaan").fadeOut('slow');
      }
    });

    $(".col-gallery-desain").slice(0, 8).show();
    $("#moreDesain").on('click', function(e) {
      e.preventDefault();
      $(".col-gallery-desain:hidden").slice(0, 8).slideDown();
      if ($(".col-gallery-desain:hidden").length == 0) {
        $("#moreDesain").fadeOut('slow');
      }
    });

  });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>