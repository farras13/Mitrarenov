<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="page-title">
    <h1>Artikel Detail</h1>
  </div>

  <div class="container my-5">
    <div class="row">
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
                <h5 class="text-primary mt-5">Artikel Populer</h5>

                <ul class="nav nav-article-cat flex-column">
                  <?php foreach ($hot as $h) : ?>
                    <li class="nav-item">
                      <a href="<?= base_url('artikel/' . $h->id . '/detail') ?>" class="nav-link px-0"><?= $h->title ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <h5 class="text-primary mt-4">Hot Kategori</h5>

                <ul class="nav nav-article-cat flex-column">
                  <?php foreach ($kategori as $k) : ?>
                    <li class="nav-item">
                      <a href="<?= base_url('artikel/' . $k->id . '/detail') ?>" class="nav-link px-0"><?= $k->title ?></a>
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
          <h1 class="mb-4 article-title">
            <?= $berita['title'] ?>
          </h1>
          <div class="article-img">
            <img src="<?= base_url('public/images/news') . '/' . $berita['image'] ?>" alt="">
          </div>
          <div class="row align-items-center py-4">
            <div class="col-8">
              <p class="text-grey mb-0">Penulis <?= $berita['penulis'] ?></p>
              <p class="text-grey mb-0">Diterbitkan <?php $time = $berita['created'];
                                                    $date = new DateTime("@$time");
                                                    echo $date->format('d M Y'); ?></p>
            </div>
            <div class="col-4 text-right">
              <a href="#">
                <i class="ico ico-share"></i>
              </a>
            </div>
          </div>
          <div class="article-description">
            <?= $berita['description'] ?>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <h5 class="text-primary">Artikel Serupa</h5>
        <?php foreach($terkait as $tk): ?>
          <div class="d-flex article-item-small is-small">
            <div class="article-sm-img">
              <div class="article-sm-img-inner">
                <img src="<?= base_url('public/images/news') . '/' . $tk->image ?>" alt="">
              </div>
            </div>
            <div class="w-100 pl-4">
              <h4 class="mb-2"><?= $tk->title ?></h4>
              <p class="text-grey mb-0">Penulis Admin</p>
              <p class="text-grey mb-0">Diterbitkan 22 Maret 2021</p>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent aliquam odio mau
                ris, ut vestibulum velit auctor quis. Donec interdum pellentesque felis et ...
              </p>
              <div class="text-right">
                <a href="artikel-detail.html" class="font-weight-bold">Baca Selengkapnya..</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
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