<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
<title>Berbagai Tips Bangun dan Renovasi Rumah | Artikel Mitrarenov</title>
<meta name="description" content="Berbagai Tips Bangun, renovasi dan perawatan rumah, kantor, gedung hingga gudang">
<meta name="keywords" content="Artikel Mitrarenov">

<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="page-title">
    <h1>Artikel</h1>
  </div>

  <div class="container my-5">
    <div class="row main-content">
      <div class="col-lg-4 mb-5">
        <div id="trigger1"></div>
        <div class="sidebar">
          <div class="card rounded-0 sidebar-inner" id="pin1">
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
                <h5 class="text-primary mt-5">Kategori Artikel</h5>

                <ul class="nav nav-article-cat flex-column">
                  <?php foreach ($kategori as $h) : if ($h->category != null || $h->category != '') : ?>
                      <li class="nav-item">
                        <?php $kategori = strtolower($h->category); ?>
                        <?php $link = str_replace(' ', '-', $kategori); ?>
                        <a href="<?= base_url('berita/kategori/' . $link) ?>" class="nav-link px-0"><?= $h->category ?></a>
                      </li>
                  <?php endif;
                  endforeach; ?>
                </ul>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 mb-5 content-article">

        <div class="article-list">
          <?php if (!$key) : ?>
            <h5 class="mb-4 text-primary">Artikel Populer</h5>
            <div class="article-slider-outer">
              <div class="article-slider">
                <?php foreach ($hot as $h) : ?>
                  <div class="article-slider-item">
                    <div class="article-img">
                      <img src="https://office.mitrarenov.com/assets/main/images/news/<?= $h->image ?>" alt="">
                    </div>
                    <a href="<?= base_url('berita') . '/' . $h->slug ?>">
                      <h4 class="mt-3 mb-2"><?= $h->title ?></h4>
                    </a>
                    <p class="text-grey mb-0"><?= !empty($h->penulis) ? $h->penulis : "Admin Mitrarenov"; ?></p>
                    <p class="text-grey mb-0">Diterbitkan <?php $time = $h->date;
                                                          $date = new DateTime("@$time");
                                                          echo $date->format('d M Y'); ?></p>
                    <p>
                      <?= $h->meta_description ?> ...
                    </p>
                    <div class="text-right">
                      <a href="<?= base_url('berita') . '/' . $h->slug ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <div class="btn-slide article-btn-prev"><i class="ico ico-prev"></i></div>
              <div class="btn-slide article-btn-next"><i class="ico ico-next"></i></div>
            </div>
          <?php endif; ?>


          <h5 class="mt-5 text-primary"> <?php if (!$key) : ?>Artikel Terbaru<?php else : ?>Result Search<?php endif; ?></h5>

          <div class="article-list-small">
            <?php foreach ($terbaru as $tb) : ?>
              <div class="d-flex article-item-small">
                <div class="article-sm-img">
                  <div class="article-sm-img-inner">
                    <img src="https://office.mitrarenov.com/assets/main/images/news/<?= $tb['image'] ?>" alt="">
                  </div>
                </div>
                <div class="w-100 pl-4">
                  <a href="<?= base_url('berita') . '/' . $tb['slug'] ?>">
                    <h4 class="mb-2"><?= $tb['title'] ?></h4>
                  </a>
                  <p class="text-grey mb-0"><?= !empty($tb['penulis']) ? $tb['penulis'] : "Admin Mitrarenov"; ?></p>
                  <p class="text-grey mb-0">Diterbitkan <?php $time = $tb['date']; echo date('d M Y', $time); ?></p>
                  <p>
                    <?= $tb['meta_description'] ?> ...
                  </p>
                  <div class="text-right">
                    <a href="<?= base_url('berita/' . $tb['slug']) ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?= $pager->links('page_berita', 'bootstrap_pagination') ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->section('script') ?>
<script src="<?= base_url('main/js/ScrollMagic.min.js') ?>"></script>


<script>
  $(document).ready(function() {
    const postDetails = document.querySelector(".content-article");
    const postSidebar = document.querySelector(".sidebar");
    const postSidebarContent = document.querySelector(".sidebar-inner");
    const content = $(".article-list").height();
    var controller = new ScrollMagic.Controller();
    var scene = new ScrollMagic.Scene({
        triggerElement: "#trigger1",
        duration: content,
        triggerHook: 0,
        offset: -100,
      })
      .setPin("#pin1")
      .addTo(controller);

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
  })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>