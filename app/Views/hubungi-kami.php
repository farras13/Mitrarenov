<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="page-title">
        <h1>Hubungi Kami</h1>
    </div>

    <div class="container-md my-5">
        <p>Team Mitrarenov siap membantu segala kebutuhan rumah anda. Silahkan isi form dibawah :</p>

        <form action="#">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-4">Informasi Pribadi</h5>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nama Lengkap">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nomor Telepon">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control pl-35" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <select class="provinsi w-100">
                            <option value=""></option>
                            <option value="1">Lorem </option>
                            <option value="1">Ipsum</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="area w-100">
                            <option value=""></option>
                            <option value="1">Lorem </option>
                            <option value="1">Ipsum</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control pl-35" cols="30" rows="4" placeholder="Pesan"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-4">Perihal</h5>
                    <div class="form-group">
                        <select class="perihal w-100">
                            <option value=""></option>
                            <option value="1">Lorem </option>
                            <option value="1">Ipsum</option>
                        </select>
                    </div>
                    <h5 class="mb-4">Detail Informasi</h5>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nama Bisnis">
                    </div>
                    <div class="row form-group align-items-center my-4">
                        <div class="col-6">
                            <div>Lampirkan Penawaran</div>
                            <div class="text-grey">Upload File : Max 1Mb</div>
                        </div>
                        <div class="col-6 text-right">
                            <div class="single-file-upload no-label">
                                <input type="file" id="upload_foto" hidden="">
                                <label for="upload_foto" class="btn btn-upload border-10 mb-0">
                                    Choose File
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success btn-lg btn-radius btn-block">KIRIM PESAN</button>
                    </div>
                </div>
            </div>
        </form>

        <h5 class="mb-4 mt-5">Area Kami</h5>
        <div class="row">
            <?php foreach ($lokasi as $l) : ?>
                <div class="col-md-4 mb-4">
                    <div class="map-container">
                        <iframe src="<?= $l->maps ?>" width="600" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <div class="text-center mt-3">
                        <?= $l->title ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->section('script') ?>
<script>
    $(document).ready(function() {

        $(".provinsi").select2({
            placeholder: "Pilih Provinsi",
            minimumResultsForSearch: -1,
            selectionCssClass: "pl-35",
        });
        $(".area").select2({
            placeholder: "Pilih Area",
            minimumResultsForSearch: -1,
            selectionCssClass: "pl-35",
        });
        $(".perihal").select2({
            placeholder: "Menjadi Rekanan",
            minimumResultsForSearch: -1,
            selectionCssClass: "pl-35",
        });
    })
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>