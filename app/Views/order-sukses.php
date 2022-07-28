<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="page-title">
      <h1>Thank You!</h1>
    </div>
    <div class="container-md account-section">
        <div class="card card-border">
            <div class="card-body py-5">
                <div class="order-success text-center">
                    <img src="<?= base_url('public/main/images/order-success.svg') ?>" class="img-fluid" width="300" alt="">
                    <h2 class="text-primary mt-5 mb-4">Pesanan Berhasil Dikirim</h2>
                    <p class="mb-4">
                        Tim lapangan kami akan mengubungi anda dalam waktu dekat. Mohon ditunggu yaa :)
                    </p>
                    <a href="<?= base_url('home') ?>" class="btn btn-success btn-lg btn-rounded py-3 px-5">KEMBALI KE BERANDA</a>

                    <div class="text-grey mt-4">
                        Jika ada pertanyaan atau informasi, dapat menghubungi Mitrarenov di nomor
                        0822 9000 9990
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
