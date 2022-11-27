<?= $this->extend('template') ?>
<?= $this->section('meta') ?>
<title>Hubungi Kami | MITRARENOV.COM</title>
<meta name="description" content="Anda ingin bangun atau renovasi rumah anda? Hubungi kami sekarang! Tim Mitrarenov siap membantu anda dalam segala kebutuhan rumah anda">
<meta name="keywords" content="Hubungi mitrarenov sekarang">
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="page-title">
        <h1>Hubungi Kami</h1>
    </div>

    <div class="container-md my-5">
        <p>Team Mitrarenov siap membantu segala kebutuhan rumah anda. Silahkan isi form dibawah :</p>

        <form action="<?= site_url('Home/add_hubungi') ?>" method="POST" enctype="multipart/form-data">
         <?= csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-4">Informasi Pribadi</h5>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nama Lengkap" name="nama" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nomor Telepon" name="notlp" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control pl-35" placeholder="Email" name="email" required>
                    </div>
                    <div class="form-group">
                        <select class="provinsi w-100" id="provinsi" name="provinsi" required>
                            <option value="">select province</option>
                            <?php foreach ($prov as $p): ?>
                            <option value="<?= $p->province_id ?>"><?=  $p->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="area w-100" id="area" name="area" required>
                            <option value=""></option>
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control pl-35" cols="30" rows="4" placeholder="Pesan" name="pesan" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-4">Perihal</h5>
                    <div class="form-group">
                        <select class="perihal w-100" name="perihal" id="perihal" required>
                            <option value=""></option>
                            <option value="mitra">Menjadi Mitra</option>
                            <option value="suplier">Menjadi Suplier</option>
                            <option value="kds">Konsultasi dan support</option>
                        </select>
                    </div>
                    <h5 class="mb-4" id="info">Detail Informasi</h5>
                    <div class="form-group">
                        <input type="text" class="form-control pl-35" placeholder="Nama Bisnis" id="bisnis" name="detail_info">
                    </div>
                    <div class="row form-group align-items-center my-4" >
                        <div class="col-6">
                            <div id="lampiran">Lampirkan Penawaran</div>
                            <div class="text-grey">Upload File : Max 1Mb</div>
                        </div>
                        <div class="col-6 text-right">
                            <div class="single-file-upload no-label">
                                <input type="file" id="upload_foto" hidden="" name="file" required>
                                <label for="upload_foto" class="btn btn-upload border-10 mb-0">
                                    Choose File
                                </label>
                            </div>
                        </div>
                        <!-- <span id="wrn" hidden><b><small>*Wajib di isi</small></b></span> -->
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="submit" class="btn btn-success btn-lg btn-radius btn-block">KIRIM PESAN</button>
                    </div>
                </div>
            </div>
        </form>

        <h5 class="mb-4 mt-5">Area Kami</h5>
        <div class="row">
            <?php foreach ($lokasi as $l) : ?>
                <div class="col-md-4 mb-4">
                    <div class="map-container">
                        <iframe id="id-<?= $l->title ?>" src="<?= $l->maps ?>" width="600" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <div class="text-center mt-3">
                       <a href="<?= $l->maps_location ?>"><?= $l->title ?></a> 
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
            selectionCssClass: "pl-35",
        });
        $(".area").select2({
            placeholder: "Pilih Area",
            selectionCssClass: "pl-35",
        });
        $(".perihal").select2({
            placeholder: "Menjadi Rekanan",
            minimumResultsForSearch: -1,
            selectionCssClass: "pl-35",
        });

        $('#provinsi').change(function(){ 
        var id=$(this).val();
        $.ajax({
            url : "<?php echo site_url('home/get_area');?>",
            method : "POST",
            data : {id: id},
            async : true,
            dataType : 'json',
            success: function(data){                
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value='+data[i].id_area+'>'+data[i].nama_area+'</option>';
                }
                $('#area').html(html);

            }
        });
        return false;
        }); 

         $(document).on('change', '#perihal', function() {
             var b = $(this).find(":selected").val();
             console.log(b);
             if(b == "kds"){
                var html = "Lampirkan Dokumen";

                $('#info').hide();
                $('#bisnis').hide();
                $('#lampiran').html(html);
             }else{
                var html = "Lampirkan Penawaran";

                $('#info').show();
                $('#bisnis').show();
                $('#lampiran').html(html);
                
             }

         });

            $('#submit').bind("click",function() 
            { 
                var imgVal = $('#upload_foto').val(); 
                if(imgVal=='') 
                { 
                    alert("empty input file"); 
                    return false; 
                } 
            }); 
    })
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>