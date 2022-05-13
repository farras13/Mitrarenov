<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('public/main/css/styles.css') ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="icon" type="image/png" href="<?= base_url('public/main/images/favico.png') ?>" />
     <!-- toast -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <title>Mitrarenov</title>
</head>

<body>
    <div class="auth-container">

        <div class="auth-inner">
            <a href="javascript:window.history.go(-1);" class="back-btn"><i class="ico ico-back"></i></a>

            <div class="auth-logo">
                <img src="<?= base_url('public/main/images/logo-mitrarenov.png') ?>" class="img-fluid" alt="">
            </div>
            <div class="mt-5">
                <?php if (isset($validation)) : ?>
                    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                <?php endif; ?>
                <?php if (isset($valmail)) : ?>
                    <div class="alert alert-danger"><?= $valmail ?></div>
                <?php endif; ?>
                <form action="<?= base_url('regis') ?>" method="POST">
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-user"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Nama Lengkap" name="name">
                    </div>
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-mail"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-phone"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Nomor Telepon" name="phone">
                    </div>
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-password"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Password" name="password">
                    </div>
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-password"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Ulangi Password" name="confpassword">
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold">REGISTRASI</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
	$(document).ready(() => {
		<?php if (session()->get('toast')) { ?>
            toastr.options.closeButton = true;
            var toastvalue = "<?php echo session()->get('toast') ?>";
            var status = toastvalue.split(":")[0];
            var message = toastvalue.split(":")[1];
            if (status === "success") {
            toastr.success(message, status);
            } else if (status === "error") {
            toastr.error(message, status);
            } else if (status == "warn") {
            toastr.warning(message, status);
            }
      <?php } ?>
	});
</script>
</body>

</html>