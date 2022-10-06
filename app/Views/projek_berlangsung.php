<?= $this->extend('template2') ?>

<?= $this->section('content') ?>
<div class="col-lg-9 col-right">
    <?php foreach($projekBerjalan as $key => $pb): ?>
    <div class="card-nav">
        <div class="slider-section">
            <span class="pagingInfo"></span>
            <div class="slideshow">
                <?php if($pb->dokumentasi != null): $countslide = count($pb->dokumentasi); foreach($pb->dokumentasi as $dk): ?>
                <div class="slider-item">
                    <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/project_update/<?= $dk->image ?>" style="width: inherit;" class="img-fluid" alt="">
                    <div class="ket-project">
                        Ket. Foto : <?= $dk->description; ?>
                    </div>
                </div>
                <?php endforeach; else: $countslide = 1;?>
                    <div class="slider-item">
                    <img src="<?= base_url('public/main/images/gambar-blum-update.png') ?>" class="img-fluid" style="width: inherit;" alt="">
                    <div class="ket-project">
                        Ket. Foto : -
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="px-3">
            <div class="row mt-4">
                <div class="col-md-7">
                    <p class="font-weight-bold mb-2 text-primary">Project - <?= $pb->nomor_kontrak; ?></p>
                    <h2 class="text-30"><?= $pb->paket_name; ?></h2>
                </div>
                <div class="col-md-5">
                    <div class="text-right">
                        <p class="font-weight-bold mb-2 text-grey">Progress</p>
                        <p class="text-30 font-weight-bold text-success"><?= $pb->presentase_progress != '' ? $pb->presentase_progress : "0%"; ?></p>
                    </div>
                </div>
            </div>
            <span class="label-update mb-4">Updated: <?= $pb->created; ?></span>

            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-paper"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Nomor Surat Kontrak</p>
                            <p class="text-23 mb-0"><?= $pb->nomor_kontrak; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="<?= 'https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'.$pb->dokumen; ?>" target="_BLANK" class="text-warning">Lihat Kontrak</a>
                </div>
            </div>
            <hr>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-money"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Nilai Project</p>
                            <p class="text-23 mb-0">Rp. <?= number_format($pb->rab, 0,',','.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <a href="<?= 'https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'.$pb->dokumen_rab; ?>" target="_BLANK" class="text-warning">Lihat RAB</a>
                </div>
            </div>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-plus-circle"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pekerjaan Tambah</p>                            
                            <!-- <p class="text-23 mb-0"><?= $pb->tambah[0]->ket_enum != '' ? $pb->tambah[0]->ket_enum : "-"; ?></p> -->
                            <p class="text-23 mb-0">Rp. <?= number_format($pb->tambah[0]->total, 0,',','.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <button onclick="tambah(<?= $pb->id; ?>)" class="text-warning btn btn-link" data-id="<?= $pb->id ?>">Lihat Selengkapnya</button>
                    <!-- <a href="#" class="text-warning" id="pt"> -->
                </div>
            </div>
            <div class="row align-items-center py-3">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-minus-circle"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pekerjaan Kurang</p>
                            <!-- <p class="text-23 mb-0"><?= $pb->kurang[0]->ket_enum != '' ? $pb->kurang[0]->ket_enum : "-"; ?></p> -->
                            <p class="text-23 mb-0">Rp. <?= number_format($pb->kurang[0]->total, 0,',','.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <button onclick="kurang(<?= $pb->id; ?>)" class="text-warning btn btn-link" data-id="<?= $pb->id ?>">Lihat Selengkapnya</button>
                </div>
            </div>
            <hr>
            <p class="font-weight-bold mb-3 text-primary">Transaksi Proyek</p>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-user-alt"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Pemilik Proyek</p>
                            <p class="text-23 mb-0"><?= $pb->name; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-call"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">No. Telepon</p>
                            <p class="text-23 mb-0"><?= $pb->phone; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-cash"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Metode Pembayaran</p>
                            <p class="text-23 mb-0"><?= $pb->metode_payment == "kredit" ? "Kredit/KPR" : "Cash/Transfer"; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-building"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">Luas Bangunan</p>
                            <p class="text-23 mb-0"><?= $pb->luas; ?> m2</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-user-friends"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">PIC</p>
                            <p class="text-23 mb-0"><?= $pb->subkon; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex">
                        <div class="project-icon">
                            <i class="ico ico-call"></i>
                        </div>
                        <div class="w-100 pl-3">
                            <p class="text-grey mb-0">No. Telepon PIC</p>
                            <p class="text-23 mb-0"><?= $pb->phone_subkon; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="text-center">
                <a href="#" class="btn btn-primary btn-lg px-5 py-3 btn-rounded">LIHAT DOKUMENTASI</a>
            </div> -->
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="projektambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-category modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="ico ico-close"></i>
        </button>

        <div class="row align-items-center">          
          <div class="col-md-12 col-8">
            <h4 class="mb-0 title-category-modal">List Pekerjaan</h4>
          </div>
        </div>
        <hr class="my-5">

        <!-- <h5 class="sub-title-cat">List Pekerjaan tambah</h5> -->

        <div class="row" id="showresult">
          
            <!-- <div class="col-md-6 my-4">
              <div class="d-flex align-items-center">

                <div class="cat-img-i">
                  <img src="https://admin.mitrarenov.soldig.co.id/assets/main/images/product_icon/<?= $m->image_icon ?>" class="img-fluid" alt="">
                </div>
                <div class="w-100 text-15 pl-3">
                  <a href="#" target="_blank" style="color:black;"> Download PDF </a>
                </div>

              </div>
            </div> -->
           
        </div>
      </div>
    </div>
  </div>
</div>



<?= $this->endSection() ?>
<?= $this->section('script') ?>

<script>
   function tambah(id) {
        var SITEURL = "<?php echo base_url(); ?>";  
        $.ajax({
            type  : 'GET',
            url   : '<?php echo base_url()?>/member/projek/tambah',
            data: {id:id},               
            dataType : 'JSON',
            success : function(data){
                var html = '';
                if(data.length === 0){
                    html += '<div class="col-md-12 my-4"><div class="d-flex align-items-center">'+
                        '<div class="w-100 text-15 pl-3">'+
                        '<img src="https://mitrarenov.soldig.co.id/public/main/images/tidak-ada-pekerjaan-tambah-atau-kurang.png" class="img-fluid" alt="">'+ 
                        '</div></div> </div>';
                }else{
                    for (let i = 0; i < data.length; i++) {
                        if(data[i].berkas != ''){
                            html += '<div class="col-md-12 my-4"><div class="d-flex align-items-center"><div class="w-100">'+
                                    '<h5>'+data[i].keterangan+'</h5>'+
                                    '<p style="color:black;">'+ data[i].biaya +
                                    '</p></div><div class="w-100 text-15 pl-3">'+
                                    '<a href="https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'+ data[i].berkas +'" target="_blank" style="color:black;">'+ 
                                    'Download PDF </a> </div> </div> </div>';
                        }else{
                            html += '<div class="col-md-6 my-4"><div class="d-flex align-items-center"><div class="w-100">'+
                                    '<h5>'+data[i].keterangan+'</h5>'+
                                    '<p style="color:black;">'+ data[i].biaya +
                                    '</p></div><div class="w-100 text-15 pl-3">'+
                                    '<a href="#" style="color:black;"> Download PDF </a> </div> </div> </div>';
                        }
    
                    }
                }
                $("#showresult").html(html);
                $("#projektambah").modal('show');
                // console.log(html);
            }                   
        });   
    };
    function kurang(id) {
        var SITEURL = "<?php echo base_url(); ?>";  
        $.ajax({
            type  : 'GET',
            url   : '<?php echo base_url()?>/member/projek/kurang',
            data: {id:id},               
            dataType : 'JSON',
            success : function(data){
                var html = '';
                if(data.length === 0){
                    html += '<div class="col-md-12 my-4"><div class="d-flex align-items-center">'+
                        '<div class="w-100 text-15 pl-3">'+
                        '<img src="https://mitrarenov.soldig.co.id/public/main/images/tidak-ada-pekerjaan-tambah-atau-kurang.png" class="img-fluid" alt="">'+ 
                        '</div></div> </div>';
                }else{
                    for (let i = 0; i < data.length; i++) {
                        if(data[i].berkas != ''){
                            html += '<div class="col-md-12 my-4"><div class="d-flex align-items-center"><div class="w-100 pl-3">'+
                                    '<h5>'+data[i].keterangan+'</h5>'+
                                    '<p style="color:black;">'+ data[i].biaya +
                                    '</p></div><div class="w-100 text-15 pl-3">'+
                                    '<a href="https://admin.mitrarenov.soldig.co.id/assets/main/berkas/'+ data[i].berkas +'" target="_blank" style="color:black;">'+ 
                                    'Download PDF </a> </div> </div> </div>';
                        }else{
                            html += '<div class="col-md-6 my-4"><div class="d-flex align-items-center"><div class="w-100">'+
                                    '<h5>'+data[i].keterangan+'</h5>'+
                                    '<p style="color:black;">'+ data[i].biaya +
                                    '</p></div><div class="w-100 text-15 pl-3">'+
                                    '<a href="#" style="color:black;"> Download PDF </a> </div> </div> </div>';
                        }
        
                    }
                }
                $("#showresult").html(html);
                $("#projektambah").modal('show');
                console.log(html);
            }                   
        });   
    };
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        alert("Copy to clipboard");
        $temp.remove();
    }

    var $status = $('.pagingInfo');
    var $slickElement = $('.slideshow');
    
    $slickElement.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        $status.text(i + '/' + slick.slideCount);
    });

    $slickElement.slick({
        arrows: false
    });
</script>
<?= $this->endSection() ?>