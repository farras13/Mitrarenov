<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('main/css/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('main/css/custom.css') ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="icon" type="image/png" href="images/favico.png" />
     <!-- toast -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<link rel="icon" type="image/png" href="<?= base_url('main/images/favico.png ') ?>" />
    <title>Login Untuk Pantau Progress Online | Mitrarenov</title>
    <meta name="description" content="Login akun Mitrarenov untuk pantau progress pembangunan dan renovasi rumah secara online">
    <meta name="keywords" content="Login akun Mitrarenov">
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110308463-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-110308463-1');
    </script>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XRR3LQSP4D"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-XRR3LQSP4D');
    </script>
</head>

<body>
    <div class="auth-container">
        <div class="auth-inner">
            <a href="<?= base_url() ?>" class="back-btn"><i class="ico ico-back"></i></a>
            <div class="auth-logo">
                <img src="<?= base_url('main/images/logo-mitrarenov.png') ?>" class="img-fluid" alt="">
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
                        <input type="password" name="password" class="form-control" placeholder="Password" id="myInput">
                    </div>
                   <div class="input-group text-grey">
                       <input type="checkbox" class="mr-2" onclick="myFunction()"> Show Password 
                   </div>
                    
                    <div class="text-right mb-4">
                        <a href="<?= base_url('member/lupa-password') ?>" class="text-grey">Lupa Password ?</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block font-weight-bold">MASUK</button>

                    <div class="mt-4 text-center text-grey">
                        Belum Punya Akun ? <span><a href="<?= base_url('member/register') ?>" class="text-warning font-weight-bold">REGISTRASI SEKARANG</a></span>
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
    function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

</script>
</body>

</html>