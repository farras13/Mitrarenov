<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <div class="card-nav">
        <div class="row align-items-center ">
            <div class="col-md-8 mb-4">
                <h3 class="text-30 text-primary mb-0">Tanya Jawab</h3>
            </div>
            <div class="col-md-4 mb-4">
                <div class="input-inline">
                    <input type="text" class="form-control" placeholder="Cari Artikel">
                    <span class="input-icon"><i class="ico ico-search"></i></span>
                </div>
            </div>
        </div>
        <div class="faq" id="faq">
            <?php foreach($tentang as $t): ?>
            <div class="faq-list">
                <a href="#faq<?= $t->id ?>" class="faq-btn" data-toggle="collapse" aria-expanded="false"><?= $t->pertanyaan ?></a>
                <div class="collapse" id="faq<?= $t->id ?>" data-parent="#faq">
                    <div class="faq-content">
                        <?= $t->jawaban ?>
                    </div>
                </div>
            </div>                
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>