<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <div class="card-nav">
         <div class="card-half mt-5">
            <div class="card card-shadow radius-15 mb-4">
                <div class="card-body p-5">
                    <form action="<?= base_url('member/change_password') ?>" method="POST">
                     <?= csrf_field(); ?>
                        <div class="form-group">
                            <input type="text" class="form-control form-material" placeholder="Password Lama" name="passlama">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-material" placeholder="Password Baru" name="passbaru">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-material" placeholder="Konfirmasi Password" name="passK">
                        </div>

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5 py-2 btn-rounded">SIMPAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>