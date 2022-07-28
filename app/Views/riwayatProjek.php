<?= $this->extend('template2') ?>

<?= $this->section('content') ?>

<div class="col-lg-9 col-right">
    <div class="card-nav">
        <div class="card card-shadow radius-15 mb-4 border-0">
            <?php foreach($projek as $p): ?>
            <div class="card-body">
                <img src="<?= base_url('public/main/images/riwayat-project.jpg') ?>" class="img-fluid" alt="">
                <div class="px-3">
                    <div class="row mt-4">
                        <div class="col-md-7">
                            <p class="font-weight-bold mb-2 text-primary">Project - <?= $p->nomor_kontrak; ?>
                            </p>
                            <h2 class="text-30">Renovasi Ruang Kerja</h2>
                        </div>
                        <div class="col-md-5">
                            <div class="text-right">
                                <p class="font-weight-bold mb-2 text-grey">Progress</p>
                                <p class="text-30 font-weight-bold text-success">Finished</p>
                            </div>
                        </div>
                    </div>
                    <span class="label-finish mb-4">Finished : <?= date('d F Y', $p->target_selesai); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
                    

<?= $this->section('script') ?>
<script>
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    var $status = $('.pagingInfo');
    var $slickElement = $('.slideshow');

    $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        $status.text(i + '/' + slick.slideCount);
    });

    $slickElement.slick({
        autoplay: true,
        arrows: false
    });
</script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>