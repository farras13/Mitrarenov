<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <div class="card-nav">
        <h3 class="text-30 text-primary mb-5 text-center">Syarat dan Ketentuan</h3>
         <?= $tentang->description ?>
    </div>
</div>
<?= $this->endSection() ?>