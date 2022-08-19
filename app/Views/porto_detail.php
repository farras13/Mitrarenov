<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="content-wrapper">
        <div class="page-title">
            <h1> <?= $porto->title; ?> </h1>
            <p class="text-white">diliput oleh <?= $penulis->name; ?></p>
        </div>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="slider-porto">
                        <div class="img-detail">
                            <?php foreach ($gambar as $g) : ?>
                                <div>
                                    <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/merawat/<?= $g->image ?>" class="img-fluid" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="img-nav-container">
                            <div class="img-nav">
                                <?php foreach ($gambar as $g) : ?>
                                    <div>
                                        <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/merawat/<?= $g->image ?>" class="img-fluid" alt="">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="btn-slide btn-nav-prev"><i class="ico ico-prev"></i></div>
                            <div class="btn-slide btn-nav-next"><i class="ico ico-next"></i></div>
                        </div>

                    </div>

                    <div class="mt-4 mb-5">
                        <p>
                           <?= $porto->description; ?>
                        </p>
                    </div>
                    <hr>
                    <div class="row mt-5">
                        <div class="col-12">
                            <h4 class="text-center">Portofolio Lainnya</h4>
                        </div>
                        <?php foreach($lain as $l): ?>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('portofolio/'.$l->id.'/detail'); ?>" class="gallery-item">
                                <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/merawat/<?= $l->image ?>" class="img-fluid" alt="">
                                <div class="gallery-cnt">
                                    <h5 class="mb-1"><?= $l->title; ?></h5>
                                    <p class="mb-0">diliput oleh Admin</p>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                       
                    </div>
                </div>
            </div>
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