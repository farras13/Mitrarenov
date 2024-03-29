<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
<title>Artikel | MITRARENOV.COM</title>
<meta name="description" content="Anda ingin bangun atau renovasi rumah anda? Hubungi kami sekarang! Tim Mitrarenov siap membantu anda dalam segala kebutuhan rumah anda">
<meta name="keywords" content="Hubungi mitrarenov sekarang">
<?= $this->endSection(); ?>
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
                <h5 class="text-primary mt-5">Kategori Artikel</h5>

                <ul class="nav nav-article-cat flex-column">
                  <?php foreach ($kategori as $h) : if ($h->category != null || $h->category != '') : ?>
                      <li class="nav-item">
                        <?php $link = str_replace(' ', '-', $h->category); ?>
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

          <h5 class="mt-5 text-primary"> <?php if (!empty($key['cari'])) : ?> <?= $judul; ?> <?php else : ?>Result Search<?php endif; ?></h5>

          <?php if ($terbaru != null) : ?>
            <div class="article-list-small">
              <?php foreach ($terbaru as $tb) : ?>
                <div class="d-flex article-item-small">
                  <div class="article-sm-img">
                    <div class="article-sm-img-inner">
                      <img src="https://office.mitrarenov.com/assets/main/images/news/<?= $tb['image'] ?>" alt="">
                    </div>
                  </div>
                  <div class="w-100 pl-4">
                    <a href="<?= base_url('berita/' . $tb['slug']) ?>">
                      <h4 class="mb-2"><?= $tb['title'] ?></h4>
                    </a>
                    <p class="text-grey mb-0"><?= !empty($tb['penulis']) ? $tb['penulis'] : "Admin Mitrarenov"; ?></p>
                    <p class="text-grey mb-0">Diterbitkan <?php $time = $tb['date'];
                                                          $date = new DateTime("@$time");
                                                          echo $date->format('d M Y'); ?></p>
                    <p>
                      <?= $h->meta_description ?> ...
                    </p>
                    <div class="text-right">
                      <a href="<?= base_url('berita/' . $tb['slug']) ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <?= $pager->links('berita', 'bootstrap_pagination') ?>
          <?php else : ?>
            <h4>Data tidak ditemukan!</h4>
          <?php endif; ?>
          <?php if (count($terbaru) < 5) { ?>
            <h5 class="mt-5 text-primary"> Rekomendasi Artikel </h5>
            <div class="article-list-small">
              <?php foreach ($artikel as $tb) : ?>
                <div class="d-flex article-item-small">
                  <div class="article-sm-img">
                    <div class="article-sm-img-inner">
                      <img src="https://office.mitrarenov.com/assets/main/images/news/<?= $tb['image'] ?>" alt="">
                    </div>
                  </div>
                  <div class="w-100 pl-4">
                    <a href="<?= base_url('berita/' . $tb['slug']) ?>">
                      <h4 class="mb-2"><?= $tb['title'] ?></h4>
                    </a>
                    <p class="text-grey mb-0"><?= !empty($tb['penulis']) ? $tb['penulis'] : "Admin Mitrarenov"; ?></p>
                    <p class="text-grey mb-0">Diterbitkan <?php $time = $tb['date'];
                                                          $date = new DateTime("@$time");
                                                          echo $date->format('d M Y'); ?></p>
                    <p>
                      <?= empty($h->meta_description) ? '' : $h->meta_description; ?> ...
                    </p>
                    <div class="text-right">
                      <a href="<?= base_url('berita/' . $tb['slug']) ?>" class="font-weight-bold">Baca Selengkapnya..</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>