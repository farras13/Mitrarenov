<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="page-title">
    <h1>Artikel</h1>
  </div>

  <div class="container my-5">
    <div class="row main-content">
      <div class="col-lg-4 mb-5">
        <div class="sidebar">
          <div class="card rounded-0 sidebar-inner">
            <div class="card-body p-5 card-sidebar">
              <div class="d-flex align-items-center">
                <div class="mr-3 nav-collapse">
                  <a href="#catColapse" data-toggle="collapse">
                    <div class="humburger-menu">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                  </a>
                </div>
                <div class="input-inline w-100">
                  <form action="" method="get" name="artikel_search">
                    <input type="text" name="cari" class="form-control pl-4" placeholder="Cari Artikel">
                    <span class="input-icon" onclick="artikel_search.submit()"><i class="ico ico-search"></i></span>
                  </form>
                </div>
              </div>

              <div class="collapse dont-collapse-sm" id="catColapse">
                <h5 class="text-primary mt-5">Hot Kategori</h5>

                <ul class="nav nav-article-cat flex-column">
                  <?php foreach ($hot as $h) : ?>
                    <li class="nav-item">
                      <a href="<?= base_url('artikel/' . $h->id . '/detail') ?>" class="nav-link px-0"><?= $h->title ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 mb-5 content-article">
        <div class="article-list">
          <h5 class="mb-4 text-primary">Artikel Populer</h5>
          <div class="article-slider-outer">
            <div class="article-slider">
              <?php foreach ($hot as $h) : ?>
                <div class="article-slider-item">
                  <div class="article-img">
                    <img src="<?= base_url('public/images/news') . '/' . $h->image ?>" alt="">
                  </div>
                  <a href="<?= base_url('artikel') . '/' . $h->id . '/detail' ?>">
                    <h4 class="mt-3 mb-2"><?= $h->title ?></h4>
                  </a>
                  <p class="text-grey mb-0"><?= $h->penulis ?></p>
                  <p class="text-grey mb-0">Diterbitkan <?php $time = $h->created;
                                                        $date = new DateTime("@$time");
                                                        echo $date->format('d M Y'); ?></p>
                  <p>
                    <?= $h->meta_description ?> ...
                  </p>
                  <div class="text-right">
                    <a href="<?= base_url('artikel') . '/' . $h->id . '/detail' ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="btn-slide article-btn-prev"><i class="ico ico-prev"></i></div>
            <div class="btn-slide article-btn-next"><i class="ico ico-next"></i></div>
          </div>

          <h5 class="mt-5 text-primary">Artikel Terbaru</h5>

          <div class="article-list-small">
            <?php foreach ($terbaru as $tb) : ?>
              <div class="d-flex article-item-small">
                <div class="article-sm-img">
                  <div class="article-sm-img-inner">
                    <img src="<?= base_url('public/images/news/thumbs') . '/' . $tb['image'] ?>" alt="">
                  </div>
                </div>
                <div class="w-100 pl-4">
                  <a href="<?= base_url('artikel') . '/' . $h->id . '/detail' ?>">
                    <h4 class="mb-2"><?= $tb['title'] ?></h4>
                  </a>
                  <p class="text-grey mb-0"><?= $tb['penulis'] ?></p>
                  <p class="text-grey mb-0">Diterbitkan <?php $time = $tb['created'];
                                                        $date = new DateTime("@$time");
                                                        echo $date->format('d M Y'); ?></p>
                  <p>
                    <?= $h->meta_description ?> ...
                  </p>
                  <div class="text-right">
                    <a href="<?= base_url('artikel/' . $tb['id'] . '/detail') ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?= $pager->links('berita', 'bootstrap_pagination') ?>
          <!-- <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center mt-4">
                <li class="page-item"><a class="page-link active" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
              </ul>
            </nav> -->
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->section('script') ?>
<script src="<?= base_url('public/main/js/ScrollMagic.min.js') ?>"></script>

<script>
  const postDetails = document.querySelector(".content-article");
  const postSidebar = document.querySelector(".sidebar");
  const postSidebarContent = document.querySelector(".sidebar-inner");

  const controller = new ScrollMagic.Controller();
  const scene = new ScrollMagic.Scene({
    triggerElement: postSidebar,
    triggerHook: 0,
    duration: getDuration,
    offset: -100
  }).addTo(controller);

  if (window.matchMedia("(min-width: 768px)").matches) {
    scene.setPin(postSidebar, {
      pushFollowers: false
    });
  }

  window.addEventListener("resize", () => {
    if (window.matchMedia("(min-width: 768px)").matches) {
      scene.setPin(postSidebar, {
        pushFollowers: false
      });
    } else {
      scene.removePin(postSidebar, true);
    }
  });

  function getDuration() {
    return postDetails.offsetHeight - postSidebarContent.offsetHeight;
  }
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>