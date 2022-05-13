<?= $this->extend('template2') ?>
<?php $sess = session(); ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <div class="card-nav">
        <div class="card-half">
            <div class="card card-shadow radius-15 mb-4">
                <div class="card-body p-5">
                    <form action="<?= base_url('member/update_profile') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                        <div class="mb-5">
                            <div class="profile-upload m-auto ">
                                <div id="image-holder" class="img-holder">
                                    <?php if(empty($akun->photo)): ?>
                                        <img src="<?= base_url('public/main/images/article-sd.jpg') ?>" alt="">
                                    <?php else: ?>
                                        <img src="<?= base_url('public/images/pp/'. $akun->photo ) ?>" alt="">
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="file" id="fileUpload" hidden="" accept="image/*">
                                <label for="fileUpload" class="btn">
                                    <i class="ico ico-pencil"></i>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="text" name="nama" class="form-control form-material" value="<?= $akun->name ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control form-material" value="<?= $sess->get('user_email'); ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="telephone" class="form-control form-material" value="<?= $akun->telephone ?>">
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

<?= $this->section('script') ?>
<script>
    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }


    $("#fileUpload").on('change', function () {

      if (typeof (FileReader) != "undefined") {

        var image_holder = $("#image-holder");
        image_holder.empty();

        var reader = new FileReader();
        reader.onload = function (e) {
          $("<img />", {
            "src": e.target.result,
            "class": "thumb-image"
          }).appendTo(image_holder);

        }
        image_holder.show();
        reader.readAsDataURL($(this)[0].files[0]);
      } else {
        alert("This browser does not support FileReader.");
      }
    });

  </script>
<?= $this->endSection() ?>
