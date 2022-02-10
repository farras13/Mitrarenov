<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('public/main/css/styles.css') ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="icon" type="image/png" href="images/favico.png"/>
    <title>Mitrarenov</title>
  </head>

<body>
    <div class="auth-container">
        <div class="auth-inner">
            <div class="auth-logo">
                <img src="<?=  base_url('public/main/images/logo-mitrarenov.png') ?>" class="img-fluid" alt="">
            </div>
            <div class="mt-5">
                <form action="<?= base_url('login') ?>" method="POST">
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-mail"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Email / Nomor Telepon" name="email">
                    </div>
                    <div class="input-group-icon mb-4">
                        <span class="input-icon">
                            <i class="ico ico-password"></i>
                        </span>
                        <input type="text" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="text-right mb-4">
                        <a href="#" class="text-grey">Lupa Password ?</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block font-weight-bold">MASUK</button>
    
                    <div class="mt-4 text-center text-grey">
                        Belum Punya Akun ? <span><a href="<?= base_url('member/register') ?>" class="text-warning font-weight-bold">REGISTRASI SEKARANG</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>