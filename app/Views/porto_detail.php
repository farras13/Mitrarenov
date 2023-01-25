<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
<title> <?= !empty($porto->meta_title) ? $porto->meta_title: $title; ?></title>
<meta name="description" content="<?= !empty($porto->meta_description) ? $porto->meta_description: 'Anda ingin bangun atau renovasi rumah anda? Hubungi kami sekarang! Tim Mitrarenov siap membantu anda dalam segala kebutuhan rumah anda' ; ?>">
<meta name="keywords" content="<?= !empty($porto->meta_keyword) ? $porto->meta_keyword: 'Hubungi mitrarenov sekarang' ; ?>">
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
    <div class="content-wrapper">
        <div class="page-title">
            <h1> <?= $porto->title; ?> </h1>
            <!-- <p class="text-white">diliput oleh <?= $penulis->name; ?></p> -->
        </div>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="slider-porto">
                        <div class="img-detail">
                            <img src="<?= $link_gambar.$porto->image ?>" class="img-fluid" alt="">
                            <?php foreach ($gambar as $g) : ?>
                                <div>
                                    <img src="<?= $link_gambar.$g->image ?>" class="img-fluid" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="img-nav-container">
                            <div class="img-nav">
                                <!-- <img src="<?= $link_gambar.$porto->image ?>" class="img-fluid" alt=""> -->
                                <?php foreach ($gambar as $g) : ?>
                                    <div>
                                        <img src="<?= $link_gambar.$g->image ?>" class="img-fluid" alt="">
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
                            <a href="<?= $linkdetail.'/'.strtolower($l->slug); ?>" class="gallery-item">
                                <img src="<?= $link_gambar.$l->image ?>" class="img-fluid" alt="">
                                <div class="gallery-cnt">
                                    <h5 class="mb-1"><?= $l->title; ?></h5>
                                    
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