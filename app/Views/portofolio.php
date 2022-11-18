<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="page-title">
    <h1><?= $title ?> </h1>
  </div>
  <div class="container my-5">
    <div class="row mt-5">
        <?php foreach($porto as $p){ ?>
            <div class="col-md-3 mb-4">
                <a href="<?= current_url().'/'.$p["slug"]; ?>" class="gallery-item">
                <img src="<?= $link_gambar.$p["image"] ?>" class="img-fluid" alt="">
                <div class="gallery-cnt">
                    <h5 class="mb-1"><?= $p["title"] ?></h5>
                    <p class="mb-0">diliput oleh <?= $p["penulis"]; ?></p>
                </div>
                </a>
            </div>
        <?php } ?>
     
    </div>
    <?= $pager->links('berita', 'bootstrap_pagination') ?>
    <!-- <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center mt-5">
        <li class="page-item disabled">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav> -->
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