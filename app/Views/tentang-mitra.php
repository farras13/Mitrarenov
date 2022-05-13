<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <div class="card-nav">
        <div class="text-center">
            <h3 class="text-30 text-primary">Tentang Mitrarenov</h3>
            <div class="my-6">
                <img src="<?= base_url('public/main/images/logo-mitrarenov.png') ?>" class="img-fluid" style="max-width: 312px;" alt="">
            </div>
        </div>
        <?= $tentang->description ?>
        <!-- <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
            et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
            dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
            officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
            et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
            dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
            officia deserunt mollit anim id est laborum.
        </p> -->

        <div class="text-center mt-5">
            <a href="#" class="mx-2 my-3"><img src="<?= base_url('public/main/images/ico-facebook-color.svg')?>" alt=""></a>
            <a href="#" class="mx-2 my-3"><img src="<?= base_url('public/main/images/ico-twitter-color.svg')?>" alt=""></a>
            <a href="#" class="mx-2 my-3"><img src="<?= base_url('public/main/images/ico-instagram-color.svg')?>" alt=""></a>
            <a href="#" class="mx-2 my-3"><img src="<?= base_url('public/main/images/ico-youtube-color.svg')?>" alt=""></a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>