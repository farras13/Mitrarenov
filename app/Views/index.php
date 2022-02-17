<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="content-wrapper">
  <div class="main-banner position-relative">
    <div class="top-banner">

      <?php foreach ($promo as $p) : ?>
        <div class="slide-item">
          <img src="<?= base_url('public/main/images/slider-1.jpg') ?>" class="w-100" alt="">

          <!-- <?php if ($p->image == null) { ?>
            <img src="<?= base_url('public/main/images/slider-1.jpg') ?>" class="w-100" alt="">
          <?php } else { ?>
            <img src="<?= base_url('public/images/promo') . '/' . $p->imageContent ?>" class="w-100 h-auto"  alt="">
          <?php } ?> -->
          <div class="slide-content">
            <div class="discount-badge">
              Diskon <?= $p->promo ?>%
            </div>
            <div class="slide-text">
              <div class="row">
                <div class="col-md-8">
                  <h3><?= $p->title ?></h3>
                  <?php $date = new DateTime($p->expired); ?>
                  <p class="mb-0">Masa berlaku s/d <?= $date->format('F Y'); ?></p>
                </div>
                <div class="col-md-4 text-right">
                  <a href="<?= base_url('detail-promo') . '/' . $p->id ?>" class="btn btn-success btn-rounded font-weight-bold px-4 py-2">LIHAT KODE PROMO</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
    <div class="btn-slide btn-prev"><i class="ico ico-prev"></i></div>
    <div class="btn-slide btn-next"><i class="ico ico-next"></i></div>
  </div>

  <div class="section section-1 pt-4 pb-5" id="ckk">
    <div class="text-center">
      <h2 class="title text-primary">Cara Kerja Kami</h2>
    </div>
    <div class="section-inner">
      <div class="row align-items-center">
        <div class="col-md-6 my-4">
          <div class="d-flex align-items-center">
            <div class="icon-how">
              <img src="<?= base_url('public/main/images/mitrarenove-a-1.svg') ?>" class="img-fluid" alt="">
            </div>
            <div class="w-100 pl-4">
              <h4><span>1.</span> Order Online</h4>
              <p class="mb-0">
                Order Pekerjaan Renovasi atau Pembangunan
                rumah anda melalui aplikasi atau website
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 my-4">
          <div class="d-flex align-items-center">
            <div class="icon-how">
              <img src="<?= base_url('public/main/images/mitrarenove-a-2.svg') ?>" class="img-fluid" alt="">
            </div>
            <div class="w-100 pl-4">
              <h4><span>2.</span> Survey Lokasi</h4>
              <p class="mb-0">
                Tim lapangan kami akan menghubungi anda
                untuk melakukan survey dan diskusi
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 my-4">
          <div class="d-flex align-items-center">
            <div class="icon-how">
              <img src="<?= base_url('public/main/images/mitrarenove-a-1.svg') ?>" class="img-fluid" alt="">
            </div>
            <div class="w-100 pl-4">
              <h4><span>3.</span> Pembuatan RAB</h4>
              <p class="mb-0">
                Tim lapangan kami akan membuatkan Rencana
                biaya renovasi atau bangun kepada anda
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 my-4">
          <div class="d-flex align-items-center">
            <div class="icon-how">
              <img src="<?= base_url('public/main/images/mitrarenove-a-3.svg') ?>" class="img-fluid" alt="">
            </div>
            <div class="w-100 pl-4">
              <h4><span>4.</span> Tanda Tangan Surat Kontrak</h4>
              <p class="mb-0">
                Setelah anda menyetujui harga, anda akan
                menandatangani kontrak
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section section-2 py-5">
    <h3 class="title text-primary text-center">
      Keunggulan Kami
    </h3>
    <div class="section-inner">
      <div class="row">
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-1.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Free Survey
          </h4>
          <p class="px-5">
            Nikmati layanan Free Survey & konsultasi
            bangun atau renovasi rumah Mitrarenov
            untuk wilayah Jabodetabek
          </p>
        </div>
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-2.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Free Design
          </h4>
          <p class="px-5">
            Rumah anda akan didesain oleh team
            arsitek yang berpengalaman secara
            GRATIS S&K Berlaku
          </p>
        </div>
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-4.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Kemudahan Pantau Progress
          </h4>
          <p class="px-5">
            Pertama di Indonesia, anda dapat melihat
            progress pembangunan rumah anda
            kapanpun melalui gadget
          </p>
        </div>
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-4.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Cash & KPR
          </h4>
          <p class="px-5">
            Anda tidak perlu pusing mengenai
            pembayaran karena bank rekanan kami
            siap membantu anda
          </p>
        </div>
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-5.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Bergaransi
          </h4>
          <p class="px-5">
            Kami memberikan GARANSI atas
            seluruh pekerjaan yang kami lakukan
          </p>
        </div>
        <div class="col-md-4 col-6 my-5 text-center">
          <div class="image-keunggulan mb-4">
            <img src="<?= base_url('public/main/images/keunggulan-mitrarenov-6.svg') ?>" class="img-fluid" alt="">
          </div>
          <h4 class="text-primary">
            Tukang Ahli & Berpengalaman
          </h4>
          <p class="px-5">
            Rumah anda akan dibangun oleh
            tukang yang ahli dan berpengalaman
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="section section-3 py-5" id="jasa">
    <div class="section-inner">
      <div class="text-center">
        <h2 class="title text-primary">Pilihan Jasa</h2>
      </div>
      <div class="row align-items-center mt-4">
        <div class="col-md-3 order-md-1 order-2 category-btn-cn">
          <a href="#" class="btn btn-category px-4 py-3"><i class="ico ico-menu mr-3"></i> SEMUA KATEGORI</a>
        </div>
        <div class="col-md-9 order-md-2 order-1">
          <div class="row row-sm">
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="#modal-detail-category" data-toggle="modal">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-01.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Membangun</p>
                </div>
              </a>
            </div>
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="<?= base_url('order?type=Renovasi') ?>">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-02.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Renovasi</p>
                </div>
              </a>
            </div>
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="#modal-detail-category" data-toggle="modal">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-03.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Perawatan Rumah</p>
                </div>
              </a>
            </div>
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="#modal-detail-category" data-toggle="modal">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-04.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Interior</p>
                </div>
              </a>
            </div>
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="#modal-detail-category" data-toggle="modal">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-05.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Pengurusan IMB</p>
                </div>
              </a>
            </div>
            <div class="col-lg-2 col-md-3 col-6 my-4">
              <a href="#modal-detail-category" data-toggle="modal">
                <div class="jasa-container">
                  <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-06.svg') ?>" class="img-fluid" alt="">
                  <p class="mb-0">Jasa Arsitek</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="testimoni-wrapper">
    <div class="section section-testi">
      <h3 class="title text-center mb-5">
        Testimonial
      </h3>
      <div class="testi-inner position-relative">
        <div class="testi-slide">
          <?php foreach ($testimoni as $t) : ?>
            <div class="testi-item">
              <div class="testi-img">
                <img class="testi-img" src="<?= base_url('public/images/testimoni/' . $t->image) ?>" alt="">
              </div>
              <div class="testi-content">
                <p class="font-weight-bold mb-0"><?= $t->name ?></p>
                <p class="mb-2"><?= strip_tags($t->company) ?></p>
                <i>"<?= strip_tags($t->testimoni) ?>"</i>
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

      <div class="nav-scroller">
        <ul class="nav justify-content-center nav-gallery my-5">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#pekerjaan">UPDATE PEKERJAAN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#portofolio">PORTOFOLIO</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#desain_rumah">DESAIN RUMAH</a>
          </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="pekerjaan">
          <div class="row" id="pekerjaanGallery">
            <?php foreach ($galery as $g) { ?>
              <div class="col-md-3 mb-4">
                <a href="<?= base_url('public/images/photo_promo_paket') . '/' . $g->image ?>" class="gallery-item" title="<?= $g->title ?>" data-author="diliput oleh Admin Mitrarenov" data-description="<?= $g->title ?>">
                  <img src="<?= base_url('public/images/photo_promo_paket') . '/' . $g->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $g->judul ?></h5>
                    <p class="mb-0">diliput oleh Admin Mitrarenov</p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
          <div class="text-center mt-4">
            <a href="#" class="readmore">Lihat Selengkapnya</a>
          </div>
        </div>
        <div class="tab-pane fade" id="portofolio">
          <div class="row" id="pekerjaanGallery">
            <?php foreach ($merawat as $m) { ?>
              <div class="col-md-3 mb-4">
                <a href="<?= base_url('public/images/merawat') . '/' . $m->image ?>" class="gallery-item" title="<?= $m->title ?>" data-author="diliput oleh Admin Mitrarenov" data-description="<?= $m->title ?>">
                  <img src="<?= base_url('public/images/merawat') . '/' . $m->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $m->title ?></h5>
                    <p class="mb-0">diliput oleh Admin Mitrarenov</p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="tab-pane fade" id="desain_rumah">
          <div class="row" id="pekerjaanGallery">
            <?php foreach ($design_rumah as $dr) { ?>
              <div class="col-md-3 mb-4">
                <a href="<?= base_url('public/images/design_rumah') . '/' . $dr->image ?>" class="gallery-item" title="<?= $dr->title ?>" data-author="diliput oleh Admin Mitrarenov" data-description="<?= $dr->title ?>">
                  <img src="<?= base_url('public/images/design_rumah') . '/' . $dr->image ?>" class="img-fluid" alt="">
                  <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $dr->title ?></h5>
                    <p class="mb-0">diliput oleh Admin Mitrarenov</p>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
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
              <img src="<?= base_url('public/main/images/article-img.jpg') ?>" alt="">
            </div>
            <div class="article-dsc">
              <h4><?= $art->title ?></h4>
              <p class="mb-0">Penulis <?= $art->penulis; ?></p>
              <p>Diterbitkan <?php $time = $art->created;
                              $date = new DateTime("@$time");
                              echo $date->format('d F Y'); ?></p>
              <a href="<?= base_url('artikel/' . $art->id . '/detail') ?>">Baca Selengkapnya...</a>
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
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_03.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_05.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_07.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_09.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_11.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_13.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_03.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_05.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_07.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_09.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_11.jpg') ?>" class="img-fluid" alt="">
        </div>
        <div class="client-item">
          <img src="<?= base_url('public/main/images/Image-31@2x_13.jpg') ?>" class="img-fluid" alt="">
        </div>

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
          <div class="area-item">Jakarta</div>
          <div class="area-item">Bogor</div>
          <div class="area-item">Depok</div>
          <div class="area-item">Tangerang</div>
          <div class="area-item">Bekasi</div>
          <div class="area-item">Bandung</div>
          <div class="area-item">Cirebon</div>
          <div class="area-item">Indramayu</div>
          <div class="area-item">Surabaya</div>
          <div class="area-item">Semarang</div>
          <div class="area-item">Jogjakarta</div>
        </div>
      </div>
    </div>

  </div>

  <div class="section section-unduh py-5">
    <h3 class="title text-center text-primary">
      Unduh Aplikasi Sekarang
    </h3>
    <div class="unduh-container mt-5">
      <div class="row align-items-center">
        <div class="col-md-4">
          <img src="<?= base_url('public/main/images/unduh-bg-1.png') ?>" class="img-fluid" alt="">
        </div>
        <div class="col-md-4">
          <div class="d-flex download-btn">
            <a href="#">
              <img src="<?= base_url('public/main/images/google-play-btn.png') ?>" class="img-fluid" alt="">
            </a>
            <a href="#">
              <img src="<?= base_url('public/main/images/app-store-btn.png') ?>" class="img-fluid" alt="">
            </a>
          </div>
        </div>
        <div class="col-md-4">
          <img src="<?= base_url('public/main/images/unduh-bg-2.png') ?>" class="img-fluid" alt="">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Category -->
<div class="modal fade" id="modal-detail-category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-category modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="ico ico-close"></i>
        </button>

        <div class="row align-items-center">
          <div class="col-6">
            <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-01.svg') ?>" class="img-fluid" alt="">
          </div>
          <div class="col-6 text-right">
            <h4 class="mb-0">Membangun</h4>
          </div>
        </div>
        <hr class="my-5">

        <h5 class="sub-title-cat">Pilihan Jasa</h5>

        <div class="row">
          <div class="col-md-6 my-4">
            <div class="d-flex align-items-center">
              <div class="cat-img-i">
                <img src="<?= base_url('public/main/images/icon-cat-detail-1.svg') ?>" class="img-fluid" alt="">
              </div>
              <div class="w-100 text-19 pl-3">
                Membangun Rumah
              </div>
            </div>
          </div>
          <div class="col-md-6 my-4">
            <div class="d-flex align-items-center">
              <div class="cat-img-i">
                <img src="<?= base_url('public/main/images/icon-cat-detail-2.svg') ?>" class="img-fluid" alt="">
              </div>
              <div class="w-100 text-19 pl-3">
                Membangun Rumah Minimalis
              </div>
            </div>
          </div>
          <div class="col-md-6 my-4">
            <div class="d-flex align-items-center">
              <div class="cat-img-i">
                <img src="<?= base_url('public/main/images/icon-cat-detail-3.svg') ?>" class="img-fluid" alt="">
              </div>
              <div class="w-100 text-19 pl-3">
                Membangun Rumah Kost
              </div>
            </div>
          </div>
          <div class="col-md-6 my-4">
            <div class="d-flex align-items-center">
              <div class="cat-img-i">
                <img src="<?= base_url('public/main/images/icon-cat-detail-4.svg') ?>" class="img-fluid" alt="">
              </div>
              <div class="w-100 text-19 pl-3">
                Membangun Ruko
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>