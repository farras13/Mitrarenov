 <?= $this->section('content') ?>
    <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="ico ico-close"></i>
            </button>

            <div class="row align-items-center">
            <div class="col-md-6 col-4">
                <img src="<?= base_url('public/main/images/icon-mitrarenov-jasa-01.svg') ?>" class="img-fluid" alt="">
            </div>
            <div class="col-md-6 col-8 text-right">
                <h4 class="mb-0 title-category-modal">Membangun</h4>
            </div>
            </div>
            <hr class="my-5">

            <h5 class="sub-title-cat">Pilihan Jasa</h5>

            <div class="row">
            <?php foreach($kategori as $m): ?>
            <div class="col-md-6 my-4">
                <div class="d-flex align-items-center">
                
                    <div class="cat-img-i">
                    <img src="<?= $m->image_icon ?>" class="img-fluid" alt="">
                    </div>
                    <div class="w-100 text-15 pl-3">
                    <a href="<?= base_url('order?type='.$m->category_id.'&jenis='.$m->paket_name) ?>" target="_blank" style="color:black;" > <?= $m->paket_name ?> </a>
                    </div>
                
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
     <?= $this->endSection() ?>
