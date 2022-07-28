<?= $this->extend('template') ?>

<?= $this->section('content') ?>
 <div class="content-wrapper">
        <div class="page-title">
            <h1>Order Jasa</h1>
        </div>

        <div class="container-md account-section">
            <div class="card card-border">
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-5 alur-order mb-5">
                            <h4>Alur Order</h4>
                            <div class="card card-shadow radius-10">
                                <div class="card-body px-5 py-4">
                                    <div class="row align-items-center">
                                        <div class="col-6 mb-4">
                                            <img src="public/main/images/alur-order-1.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Order Online</h3>
                                            <p>
                                                Order Pekerjaan Renovasi atau Pembangunan rumah anda melalui aplikasi atau website
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Survey Lokasi</h3>
                                            <p>
                                                Tim lapangan kami akan menghubungi anda untuk melakukan survey dan diskusi
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="public/main/images/alur-order-2.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="public/main/images/alur-order-3.svg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Pembuatan RAB</h3>
                                            <p>
                                                Tim lapangan kami akan membuatkan Rencana biaya renovasi atau bangun kepada anda
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h3 class="text-primary mb-3">Tanda Tangan Surat Kontrak</h3>
                                            <p>
                                                Setelah anda menyetujui harga, anda akan menandatangani kontrak
                                            </p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <img src="public/main/images/alur-order-4.svg" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 right-jasa mb-5">
                            <div class="jasa-content">
                                <div class="nav-scroller">
                                    <!-- <ul class="nav justify-content-center nav-tab-rounded">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link nav-design">DENGAN DESAIN</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link nav-non-design">TANPA DESAIN</a>
                                        </li> -->
                                    </ul>
                                </div>
                                <div class="mt-5">
                                    <form onsubmit="get_action(this);" method="POST" enctype="multipart/form-data" id="formorder">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" id="totalHarga" name="totalHarga">
                                        <input type="hidden" id="order" name="tipe_order">
                                        <input type="hidden" id="lang" name="lat">
                                        <input type="hidden" id="long" name="long">
                                        <input type="hidden" id="jenis" name="jenis_order" value="<?= $jenis ?>">
                                        <div class="collapse show" id="designOrder">
                                            <div class="mb-5">
                                                <p class="text-22 text-primary font-weight-bold">Pilih Tipe Rumah</p>

                                                <div class="type-rumah">
                                                    <?php foreach ($tipe_rumah as $tr) : ?>
                                                        <div class="custom-control custom-radio type-radio">
                                                            <input type="radio" id="type<?= $tr->id ?>" name="customRadioInline" class="custom-control-input">
                                                            <label class="custom-control-label" for="type<?= $tr->id ?>">
                                                                <?= $tr->type ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <!-- <div class="mb-5">
                                                <p class="text-22 text-primary font-weight-bold">Denah Rumah</p>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="images-upload">
                                                            <input type="file" id="denahRumah" name="denah" hidden="" accept="image/*">
                                                            <label for="denahRumah" class="btn box-label">
                                                                <div class="label-inner">
                                                                    <i class="ico ico-picture"></i>
                                                                    <div class="mt-2">Upload Denah Rumah</div>
                                                                </div>
                                                                <div class="img-holder" data-image="denahRumah"></div>
                                                            </label>
                                                        </div>
                                                        <div class="text-grey mt-3"><i>*Format ( JPG/JPEG/PNG )</i></div>
                                                    </div>
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Ruang Keluarga
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="ruang_keluarga"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Kamar Tidur
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="kamar_tidur"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Kamar Mandi
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="kamar_mandi"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-6 text-grey">
                                                                Dapur
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="d-flex">
                                                                    <div class="pr-3">:</div>
                                                                    <div style="width: 68px;"><input type="text" class="form-denah" name="dapur"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="mb-5">
                                            <p class="text-22 text-primary font-weight-bold">Detail Bangunan</p>

                                            <div class="input-inline mb-3">
                                                <input type="number" class="form-control form-shadow" placeholder="Luas Bangunan" name="luas" id="luasbang" required>
                                                <span class="input-icon">
                                                    <i class="ico ico-m2"></i>
                                                </span>
                                            </div>
                                            <div class="images-upload">
                                                <input type="file" id="fotoRumah" hidden="" accept="image/*" name="gambar_rumah" required>
                                                <label for="fotoRumah" class="btn foto-rumah">
                                                    <div class="label-inner">
                                                        <div class="w-100">Upload Foto Rumah</div>
                                                        <i class="ico ico-picture"></i>
                                                    </div>
                                                    <div class="img-holder" data-image="fotoRumah"></div>
                                                </label>
                                            </div>
                                            <div class="mt-2 mb-3 text-grey"><i>*Format ( JPG/JPEG/PNG )</i></div>

                                            <div class="mb-3">
                                                <textarea class="form-control form-shadow" cols="30" rows="4" placeholder="Deskripsi" name="deskripsi" required></textarea>
                                                <div class="mt-2 text-grey"><i>*Jelaskan sedetail mungkin kebutuhan anda, spesifikasi dan juga
                                                        budget pembangunan anda</i></div>
                                            </div>
                                            <div class="mb-3">
                                                <input id="autocomplete" class="form-control form-shadow controls" type="text" placeholder="Alamat" name="alamat" />
                                                <input id="city" class="form-control form-shadow controls" type="text" placeholder="City" name="city" />
                                            </div>

                                            <div class="form-group mb-4">
                                                <div id="mapSearch"></div>
                                            </div>

                                            <div class="py-4 text-center">
                                                <p class="text-20">Ingin tahu harga real?</p>
                                                <p class="text-20 mb-0">Yuk Konsultasi dan Survey <b>GRATIS !</b></p>
                                            </div>

                                            <div class="py-5">
                                                <p class="text-22 text-primary font-weight-bold">Isi Data Order</p>
                                                <div class="form-group">
                                                    <select class="choose-spec w-100" id="spek"> 
                                                        <option></option>
                                                        <?php $no = 0;
                                                        $bnyk = count($spek);
                                                        foreach ($spek as $s) : ?>
                                                            <option value="spesifikasi<?= $no ?>" data-harga="<?= $s->product_price ?>">Spesifikasi <?= ucfirst($s->type_price); ?></option>
                                                        <?php $no++;
                                                        endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <?php $no = 0; 
                                                    foreach ($spek as $s) : ?>
                                                        <div class="spec-lst" data-spec="spesifikasi<?= $no ?>">
                                                            <a href="#spesifikasi<?= $no ?>" data-toggle="collapse" aria-expanded="false">
                                                                Lihat Spesifikasi
                                                            </a>
                                                            <div class="collapse" id="spesifikasi<?= $no ?>">
                                                                <div class="spec-content card p-3 mt-2">
                                                                    <!-- <p class="font-weight-bold text-dark">Harga /<?= $s->satuan ?> : Rp <?= number_format($s->product_price, 2); ?> m2</p> -->
                                                                    <?= $s->spesifikasi ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php $no++;
                                                    endforeach; ?>
                                                </div>
                                                <div class="form-group">
                                                    <select class="methode_pembayaran w-100" name="metodpay">
                                                        <option></option>
                                                        <option value="cash">Cash</option>
                                                        <option value="kredit">KPR</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Nama Lengkap" name="nama_lengkap" <?php if ($nama != null) {
                                                                                                                                                            echo 'value=' . $nama;
                                                                                                                                                        }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Nomor Telepon" name="telepon" <?php if ($phone != null) {
                                                                                                                                                        echo 'value=' . $phone;
                                                                                                                                                    }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="email" class="form-control form-shadow" placeholder="Email" name="email" <?php if ($email != null) {
                                                                                                                                                echo 'value=' . $email;
                                                                                                                                            }  ?> required>

                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Kode Promo" name="promo">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-shadow" placeholder="Kode Referal" name="referal">
                                                </div>

                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label text-grey" for="customCheck1">
                                                    <span></span>
                                                    Saya ingin mendapatkan informasi Promosi, Katalog dan
                                                    Newslatter
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block btn-lg btn-rounded py-3 mt-5">SUBMIT</button>
                                            <p class="text-22 text-primary font-weight-bold mt-5">Estimasi Harga</p>
                                            <div class="spec">
                                                <div class="spec-content border-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            Luas Rumah
                                                        </div>
                                                        <div class="col-md-6" id="nluas">
                                                            : 0 m2
                                                        </div>
                                                        <div class="col-md-6">
                                                            Estimasi Biaya
                                                        </div>
                                                        <div class="col-md-6" id="nharga">
                                                            : Rp. 0
                                                        </div>
                                                        <div class="col-md-6">
                                                            Jenis Pekerjaan
                                                        </div>
                                                        <div class="col-md-6">
                                                            : <?= $jenis ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->section('script') ?>
<!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn4Ah0LbR1ArWtKBbXUZm6Sovv85JDlqU&callback=initialize&libraries=places&v=weekly"
    defer></script> -->
  <script
    src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initialize&key=AIzaSyCn4Ah0LbR1ArWtKBbXUZm6Sovv85JDlqU"
    defer></script>
<script>
    var baseURL = "<?php echo base_url(); ?>";
    var nluas = $("#luasbang").val();
    var nharga;

    
    var base_url = window.location.origin;
    var url_string = window.location;
    var url = new URL(url_string);
    var type = url.searchParams.get("type");
    var awal = document.getElementById("lang");
    var akhir = document.getElementById("long");
    var link_desain = base_url + '/order/desain';
    var order = document.getElementById("order");
    var link_nodesain = base_url + '/order/no_desain';
    var action; 
    
    const formatRupiah = (money) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(money);
    };  

    $('#luasbang').on('keyup', function() {
        nluas = $("#luasbang").val();
        nharga = $('option:selected', '#spek').attr('data-harga');

        $('#nluas').text(': ' + nluas + ' m2');

        if (nharga == null || nharga == 0) {
            nharga = 1;
        }
        var total = nluas * nharga;
        $("#totalHarga").val(total);
        $('#nharga').text(': ' + formatRupiah(total));
    });

    function get_action(form) {
        form.action = action;
        // console.log(action);
        return true;
    }
</script>
 <script>
    function initialize() {
      geolocate();
      initMap();
      initAutocomplete();
    }

    var map, marker;

    function initMap() {
      map = new google.maps.Map(document.getElementById('mapSearch'), {
        center: { lat: -6.200000, lng: 106.816666 },
        zoom: 17
      });
    }
    function initAutocomplete() {
      autocomplete = new google.maps.places.Autocomplete(
        (document.getElementById('autocomplete')), {
        types: ['geocode']
      });
      autocomplete.addListener('place_changed', fillInAddress);
    //   console.log(autocomplete);
    }
    function fillInAddress() {
      var place = autocomplete.getPlace();
      var add = place.formatted_address ;
        var value = add.split(",");
            count=value.length;
            country=value[count-1];
            state=value[count-2];
            city=value[count-3];
            $('#city').val(city);
      console.log(place);
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
        // console.log('tsss');
        
      }
      // Clear out the old markers.
      markers = [];
      markers.forEach((marker) => {
        marker.setMap(null);
      });
      if (!marker) {
        marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });
      } else marker.setMap(null);
      marker.setOptions({
        position: place.geometry.location,
        map: map
      });
    }
    function geolocate() {
      infoWindow = new google.maps.InfoWindow();
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            var marker = new google.maps.Marker({
              position: pos,
              map: map,
            });
            infoWindow.setPosition(pos);
            infoWindow.setContent("Lokasi Anda.");
            infoWindow.open(map, marker);
            map.setCenter(pos);
            map.setZoom(17);
            var google_maps_geocoder = new google.maps.Geocoder();
            google_maps_geocoder.geocode(
              { 'latLng': pos },
              function (results, status) {
                if (status == google.maps.GeocoderStatus.OK && results[0]) {
                  $('#autocomplete').val(results[0].formatted_address);
                  var add = results[0].formatted_address ;
                  var value = add.split(",");
                    count=value.length;
                    country=value[count-1];
                    state=value[count-2];
                    city=value[count-3];
                    $('#city').val(city);
                }
              }
            );
            google.maps.event.addListener(map, "click", function (e) {
              latLng = e.latLng;
              // console.log(e.latLng.lat());
              // console.log(e.latLng.lng());
              if (marker && marker.setMap) {
                marker.setMap(null);
              }
              marker = new google.maps.Marker({
                position: latLng,
                map: map
              });
              google_maps_geocoder.geocode(
                { 'location': e.latLng }, 
                function (results, status) {
                if (status === 'OK') {
                  if (results[0]) {
                    $('#autocomplete').val(results[0].formatted_address);
                    var add = results[0].formatted_address ;
                    var value = add.split(",");
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        $('#city').val(city);
                  }
                }

              });
            });

          },
          () => {
            handleLocationError(true, infoWindow, map.getCenter());
          }
        );
      } else {
        handleLocationError(false, infoWindow, map.getCenter());
      }
    }
    $('.spec-lst').hide();
    $('.type-rumah').slick({
      centerMode: true,
      slidesToShow: 2,
      arrows: false,
      // variableWidth: true
    });
    $(document).ready(function () {
      $("#form-order").on("keypress", function (event) {
        var keyPressed = event.keyCode || event.which;
        if (keyPressed === 13) {
          event.preventDefault();
          return false;
        }
      });
      var total_spek = <?php echo count($spek)  ?>;
      $(".choose-spec").select2({
        placeholder: "Pilihan Spesifikasi",
        minimumResultsForSearch: -1,
        selectionCssClass: "form-shadow",
      }).on('select2:select', function (e) {
            var data = e.params.data.element;
            $('.spec-lst').hide();
            $('.spec-lst[data-spec=' + data.value).show();
           
            nharga = $('option:selected', this).attr('data-harga');
            console.log(nharga);
            nluas = $("#luasbang").val();
            if (nluas == null || nluas == 0) {
                nluas = 1;
            }
            var total = nluas * nharga;
            $("#totalHarga").val(total);
            $('#nharga').text(': ' + formatRupiah(total));
            
      });;
      $(".methode_pembayaran").select2({
        placeholder: "Methode Pembayaran",
        minimumResultsForSearch: -1,
        selectionCssClass: "form-shadow",
      });
      if (type != 1) {
            $('.nav-non-design').addClass('active');
            $('#designOrder').collapse('hide');
            order.value = "nondesain";          
            action = link_nodesain;
        } else {
            $('.nav-design').addClass('active');
            order.value = "desain";
            action = link_desain;
        }
    });
    $('.nav-design').click(function (e) {
      e.preventDefault();
      $('.nav-tab-rounded .nav-link').removeClass('active');
      $(this).addClass('active');
      $('#designOrder').collapse('show')
      action = link_desain;

    })
    $('.nav-non-design').click(function (e) {
      e.preventDefault();
      $('.nav-tab-rounded .nav-link').removeClass('active');
      $(this).addClass('active');
      $('#designOrder').collapse('hide')
      action = link_nodesain;


    })
   

    $('.images-upload').click(function () {
      var x = $(this).find('input[type="file"]').attr("id");
      $("#" + x).change(function () {
        if (typeof (FileReader) != "undefined") {
          var image_holder = $("[data-image='" + x + "']");
          image_holder.empty();
          image_holder.addClass('value-img');
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
      })
    });

  </script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>