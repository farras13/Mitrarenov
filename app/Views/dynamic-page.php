<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
    <meta name="description" content="">
    <meta name="keywords" content="">
<?= $this->endSection(); ?>
<?= $this->section('meta') ?>
<title><?= $data->meta_title; ?> | MITRARENOV.COM</title>
<meta name="description" content="<?= $data->meta_desc; ?>">
<meta name="keywords" content="<?= $data->meta_keyword; ?>">
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<<div class="content-wrapper">
  <div class="page-title">
    <h1><?= $data->judul ?> </h1>
  </div>
  <div class="container my-5">
    
        <prev>
            <?= $data->page; ?>
        </prev>
    
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