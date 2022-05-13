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
             <?= $tentang->description ?>
            <!-- <div class="faq-list">
                <a href="#faq1" class="faq-btn" data-toggle="collapse" aria-expanded="false">Lorem Ipsum Dolor sit
                    amet</a>
                <div class="collapse" id="faq1" data-parent="#faq">
                    <div class="faq-content">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore, expedita! Neque, veritatis
                        commodi. Explicabo, commodi deleniti ex asperiores aut alias eligendi, atque molestias, odio
                        distinctio hic beatae vitae est totam?
                    </div>
                </div>
            </div>
            <div class="faq-list">
                <a href="#faq2" class="faq-btn" data-toggle="collapse" aria-expanded="false">Lorem Ipsum Dolor sit
                    amet</a>
                <div class="collapse" id="faq2" data-parent="#faq">
                    <div class="faq-content">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore, expedita! Neque, veritatis
                        commodi. Explicabo, commodi deleniti ex asperiores aut alias eligendi, atque molestias, odio
                        distinctio hic beatae vitae est totam?
                    </div>
                </div>
            </div>
            <div class="faq-list">
                <a href="#faq3" class="faq-btn" data-toggle="collapse" aria-expanded="false">Lorem Ipsum Dolor sit
                    amet</a>
                <div class="collapse" id="faq3" data-parent="#faq">
                    <div class="faq-content">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore, expedita! Neque, veritatis
                        commodi. Explicabo, commodi deleniti ex asperiores aut alias eligendi, atque molestias, odio
                        distinctio hic beatae vitae est totam?
                    </div>
                </div>
            </div>
            <div class="faq-list">
                <a href="#faq4" class="faq-btn" data-toggle="collapse" aria-expanded="false">Lorem Ipsum Dolor sit
                    amet</a>
                <div class="collapse" id="faq4" data-parent="#faq">
                    <div class="faq-content">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore, expedita! Neque, veritatis
                        commodi. Explicabo, commodi deleniti ex asperiores aut alias eligendi, atque molestias, odio
                        distinctio hic beatae vitae est totam?
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>