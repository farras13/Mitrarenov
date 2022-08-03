<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <?php foreach($projekBerjalan as $key => $pb): ?>
    <div class="card-nav">
        <div class="slider-section">
            <span class="pagingInfo"></span>
            <div class="slideshow">
                <?php if($pb->dokumentasi != null): foreach($pb->dokumentasi as $dk): ?>
                <div class="slider-item">
                    <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/project_update/<?= $dk->image ?>" class="img-fluid" alt="">
                    <div class="ket-project">
                        Ket. Foto : <?= $dk->description; ?>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <div class="slider-item">
                    <img src="<?= base_url('public/main/images/project.jpg') ?>" class="img-fluid" alt="">
                    <div class="ket-project">
                        Ket. Foto : -
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="px-3">
            <div class="row mt-4">
                <div class="col-md-7">
                    <p class="font-weight-bold mb-2 text-primary">Project - <?= $pb->nomor_kontrak; ?></p>
                    <h2 class="text-30"><?= $pb->paket_name; ?></h2>
                </div>
                <div class="col-md-5">
                    <div class="text-right">
                        <p class="font-weight-bold mb-2 text-grey">Progress</p>
                        <p class="text-30 font-weight-bold text-success"><?= $pb->presentase_progress != '' ? $pb->presentase_progress : "0%"; ?></p>
                    </div>
                </div>
            </div>
            <span class="label-update mb-4">Updated: <?= $pb->created; ?></span>

            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-paper"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Nomor Surat Kontrak</p>
                            <p class="text-23 mb-0"><?= $pb->nomor_kontrak; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="<?= 'https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'.$pb->dokumen; ?>" target="_BLANK" class="text-warning">Lihat Kontrak</a>
                </div>
            </div>
            <hr>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-money"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Nilai Project</p>
                            <p class="text-23 mb-0">Rp. <?= number_format($pb->rab, 0,',','.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="<?= 'https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'.$pb->dokumen_rab; ?>" target="_BLANK" class="text-warning">Lihat RAB</a>
                </div>
            </div>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-plus-circle"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pekerjaan Tambah</p>
                            <p class="text-23 mb-0"><?= $pb->addenum[0]->ket_enum != '' ? $pb->addenum[0]->ket_enum : "-"; ?></p>
                            <!-- <p class="text-23 mb-0">Rp. <?= number_format($pb->addenum[0]->total, 0,',','.'); ?></p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="#" class="text-warning">Lihat Selengkapnya</a>
                </div>
            </div>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-minus-circle"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pekerjaan Kurang</p>
                            <p class="text-23 mb-0"><?= $pb->addenum[1]->ket_enum != '' ? $pb->addenum[1]->ket_enum : "-"; ?></p>
                            <!-- <p class="text-23 mb-0">Rp. <?= number_format($pb->addenum[1]->total, 0,',','.'); ?></p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="#" class="text-warning">Lihat Selengkapnya</a>
                </div>
            </div>
            <hr>
            <p class="font-weight-bold mb-3 text-primary">Transaksi Proyek</p>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-user-alt"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pemilik Proyek</p>
                            <p class="text-23 mb-0"><?= $pb->name; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-call"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">No. Telepon</p>
                            <p class="text-23 mb-0"><?= $pb->phone; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-cash"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Metode Pembayaran</p>
                            <p class="text-23 mb-0"><?= $pb->metode_payment == "kredit" ? "Kredit/KPR" : "Cash/Transfer"; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-building"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Luas Bangunan</p>
                            <p class="text-23 mb-0"><?= $pb->luas; ?> m2</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-user-friends"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">PIC</p>
                            <p class="text-23 mb-0"><?= $pb->pic; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-call"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">No. Telepon PIC</p>
                            <p class="text-23 mb-0"><?= $pb->phone_pic; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="#" class="btn btn-primary btn-lg px-5 py-3 btn-rounded">LIHAT DOKUMENTASI</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>

<script>
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        alert("Copy to clipboard");
        $temp.remove();
    }

    var $status = $('.pagingInfo');
    var $slickElement = $('.slideshow');

    $slickElement.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        $status.text(i + '/' + slick.slideCount);
    });

    $slickElement.slick({
        arrows: false
    });
</script>
<?= $this->endSection() ?>