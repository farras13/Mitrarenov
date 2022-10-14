<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<<div class="content-wrapper">
  <div class="page-title">
    <h1><?= $title ?> </h1>
  </div>
  <div class="container my-5">
    <div class="row mt-5">
        <?php foreach($porto as $p){ ?>
            <div class="col-md-3 mb-4">
                <a href="#" class="gallery-item">
                <img src="<?= base_url('public/images/photo_promo_paket') . '/' .$p["image"] ?>" class="img-fluid" alt="">
                <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $p["judul"] ?></h5>
                    <p class="mb-0">diliput oleh <?= $p["penulis"]; ?></p>
                </div>
                </a>
            </div>
        <?php } ?>
     
    </div>
    <?= $pager->links('berita', 'bootstrap_pagination') ?>
  </div>
  </div>
  <?= $this->section('script') ?>
    <script>
        $(document).ready(function () {
        $('.img-detail').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '.img-nav'
        });
        $('.img-nav').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.img-detail',
            centerMode: true,
            focusOnSelect: true,
            prevArrow: '.btn-nav-prev',
            nextArrow: '.btn-nav-next',
        });

        })
    </script>

  <?= $this->endSection() ?>
  <?= $this->endSection() ?>