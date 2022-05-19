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
                                        <img src="<?= base_url('main/images/alur-order-1.svg') ?>" class="img-fluid" alt="">
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
                                        <img src="<?= base_url('main/images/alur-order-2.svg') ?>" class="img-fluid" alt="">
                                    </div>
                                    <div class="col-6 mb-4">
                                        <img src="<?= base_url('main/images/alur-order-3.svg') ?>" class="img-fluid" alt="">
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
                                        <img src="<?= base_url('main/images/alur-order-4.svg') ?>" class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 right-jasa mb-5">
                        <div class="jasa-content">
                            <div class="nav-scroller">
                                <ul class="nav justify-content-center nav-tab-rounded">

                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-design">DENGAN DESAIN</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-non-design">TANPA DESAIN</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-5">
                                <form onsubmit="get_action(this);" method="POST" enctype="multipart/form-data" id="formorder">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" id="order" name="tipe_order">
                                    <input type="hidden" id="lang" name="lat">
                                    <input type="hidden" id="long" name="long">
                                    <input type="hidden" id="jenis" name="jenis_order" value="<?= $jenis ?>">
                                    <div class="collapse show" id="designOrder">
                                        <div class="mb-5">
                                            <p class="text-22 text-primary font-weight-bold">Pilih Tipe Rumah</p>
                                            <div class="d-flex flex-wrap type-row">
                                                <?php foreach ($tipe_rumah as $tr) : ?>
                                                    <div class="custom-control custom-radio type-radio mr-4 mb-4">
                                                        <input type="radio" id="type<?= $tr->id ?>" name="customRadioInline" class="custom-control-input">
                                                        <label class="custom-control-label" for="type<?= $tr->id ?>"><?= $tr->type ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="mb-5">
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

                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <p class="text-22 text-primary font-weight-bold">Detail Bangunan</p>

                                        <div class="input-inline mb-3">
                                            <input type="text" class="form-control form-shadow" placeholder="Luas Bangunan" name="luas" id="luasbang" required>
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
                                            <textarea class="form-control form-shadow" cols="30" rows="4" placeholder="Alamat" name="alamat" required></textarea>
                                            <div class="mt-2 text-grey"><i>*Jelaskan sedetail mungkin kebutuhan anda, spesifikasi dan juga
                                                    budget pembangunan anda</i></div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="map-pin mt-3">
                                                <div class="map-view" id="map"></div>
                                            </div>
                                        </div>

                                        <!-- <a href="#allSpec" class="btn btn-success btn-block btn-lg btn-rounded py-3" data-toggle="collapse">LIHAT SPESIFIKASI</a>

                                        <div class="collapse" id="allSpec"> -->
                                        <div class="py-5">
                                            <p class="text-22 text-primary font-weight-bold">Spesifikasi</p>
                                            <?php foreach ($spek as $s) : ?>
                                                <div class="spec">
                                                    <a href="#spesifikasi<?= $s->id ?>" class="spec-btn" data-toggle="collapse" aria-expanded="false">
                                                        Spesifikasi <?= ucfirst($s->type_price); ?>
                                                    </a>
                                                    <div class="collapse" id="spesifikasi<?= $s->id ?>">
                                                        <div class="spec-content">
                                                            <p class="font-weight-bold text-dark">Harga /m2 : Rp <?= number_format($s->product_price, 2) ?> m2</p>
                                                            <?= $s->spesifikasi ?>
                                                            <!-- Struktur: <br>
                                                                <ul class="pl-3 mt-0">
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                </ul>

                                                                Dinding: <br>   
                                                                <ul class="pl-3 mt-0">
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                    <li>Lorem ipsum</li>
                                                                </ul> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <p class="text-22 text-primary font-weight-bold mt-5">Estimasi Harga</p>
                                            <div class="spec">
                                                <div class="spec-content border-0">
                                                    <div class="row">
                                                        <?php if ($type < 3) : ?>
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
                                                        <?php else : ?>
                                                            <div class="col-md-12">
                                                                <center>
                                                                    <h4> Contact Us </h4>
                                                                </center>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-3 mb-4">
                                                    <img src="<?= base_url('main/images/mitrarenove-a-2.svg') ?>" class="img-fluid" alt="">
                                                </div>
                                                <div class="col-md-9 mb-4">
                                                    <p class="text-20 text-grey">Ingin tahu harga real?</p>
                                                    <p class="text-20 text-grey mb-0">Yuk Konsultasi dan Survey <b>GRATIS !</b></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- </div> -->

                                        <!-- <a href="#kosultasi" class="btn btn-success btn-block btn-lg btn-rounded py-3 mt-5" data-toggle="collapse">KONSULTASI DAN SURVEY</a>

                                        <div class="collapse" id="kosultasi"> -->
                                        <div class="pt-5">
                                            <p class="text-22 text-primary font-weight-bold">Isi Data Order</p>
                                            <div class="mb-3">
                                                <select class="choose-spec w-100" name="spek" id="spek" required>
                                                    <option value=""></option>
                                                    <?php foreach ($spek as $sp) : ?>
                                                        <option value="<?= $sp->id ?>" data-harga="<?= $s->product_price ?>">Spesifikasi <?= ucfirst($sp->type_price); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <select class="form-control form-shadow w-100" name="metodpay" id="metodpay" required>
                                                    <option value="" selected disabled>Metode Pembayaran</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="KPR">KPR</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Nama Lengkap" name="nama_lengkap" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Nomor Telepon" name="telepon" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="email" class="form-control form-shadow" placeholder="Email" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Nama Marketing" name="marketing" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Kode Promo" name="promo">
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-shadow" placeholder="Kode Referal" name="referal">
                                            </div>



                                        </div>
                                        <!-- </div> -->
                                        <div class="custom-control custom-checkbox mt-4">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="cekkonfrim">
                                            <label class="custom-control-label text-grey" for="customCheck1">
                                                <span></span>
                                                Saya ingin mendapatkan informasi Promosi, Katalog dan
                                                Newslatter
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block btn-lg btn-rounded py-3 mt-5">SUBMIT</button>

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
<script type="text/javascript" src="<?= base_url('main/js/script.min.js') ?>"></script>
<script>
    var baseURL = "<?php echo base_url(); ?>";
    var nluas = $("#luasbang").val();
    var nharga;

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
        $('#nharga').text(': ' + formatRupiah(total));
    });

    $('#spek').change(function() {
        nharga = $('option:selected', this).attr('data-harga');
        nluas = $("#luasbang").val();
        if (nluas == null || nluas == 0) {
            nluas = 1;
        }
        var total = nluas * nharga;
        $('#nharga').text(': ' + formatRupiah(total));
    });
</script>
<script>
    var base_url = window.location.origin;
    var url_string = window.location;
    var url = new URL(url_string);
    var type = url.searchParams.get("type");
    var order = document.getElementById("order");
    var awal = document.getElementById("lang");
    var akhir = document.getElementById("long");
    var link_desain = base_url + '/order/desain';
    var link_nodesain = base_url + '/order/no_desain';
    var action;

    $(document).ready(function() {
        $(".choose-spec").select2({
            placeholder: "Pilihan Spesifikasi",
            minimumResultsForSearch: -1,
            selectionCssClass: "form-shadow",
        });

        if (type != 1 && type != 2) {
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

    if (type == 1 || type == 2) {
        $('.nav-design').click(function(e) {
            e.preventDefault();
            $('.nav-tab-rounded .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#designOrder').collapse('show');
            order.value = "desain";
            action = link_desain;
        })
    }

    $('.nav-non-design').click(function(e) {
        e.preventDefault();
        $('.nav-tab-rounded .nav-link').removeClass('active');
        $(this).addClass('active');
        $('#designOrder').collapse('hide');
        order.value = "nondesain";
        action = link_nodesain;
    })

    function get_action(form) {
        form.action = action;
        // console.log(action);
        return true;
    }

    $('.images-upload').click(function() {
        var x = $(this).find('input[type="file"]').attr("id");
        $("#" + x).change(function() {
            if (typeof(FileReader) != "undefined") {
                var image_holder = $("[data-image='" + x + "']");
                image_holder.empty();
                image_holder.addClass('value-img');
                var reader = new FileReader();
                reader.onload = function(e) {
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

    var apiKey = "AAPK8f3ce18d85104248b6d135a97259d040wJTMmeZlLrEFeVk02Xo2syJxUkqmcxsImybY1apikC1jFBLhkwqOAyFXTcz1rGZY";

    // Maps Edit
    var map = L.map("map").setView([-1.3575326502401202, 114.3861291767156], 4);
    var tiles = L.esri.basemapLayer("Streets").addTo(map);

    // create the geocoding control and add it to the map
    var searchControl = L.esri.Geocoding.geosearch({
        providers: [
            L.esri.Geocoding.arcgisOnlineProvider({
                // API Key to be passed to the ArcGIS Online Geocoding Service
                apikey: apiKey
            })
        ]
    }).addTo(map);

    // create an empty layer group to store the results and add it to the map
    var results = L.layerGroup().addTo(map);
    var geocodeService = L.esri.Geocoding.geocodeService({
        apikey: apiKey // replace with your api key - https://developers.arcgis.com
    });

    async function getAddressEdit(e) {
        geocodeService.reverse().latlng(e.latlng).run(async function(error, result) {
            if (error) {
                return;
            }
            if (marker) {
                map.removeLayer(marker);
            }
            results.clearLayers();
            var coord = await e.latlng.toString().split(',');
            var lat = coord[0].split('(');
            var lng = coord[1].split(')');
            console.log(lat[1] + " " + lng[0]);

            awal.value = lat[1];
            akhir.value = lng[0];
            var addressName = result.address.Match_addr
            marker = L.marker(result.latlng).addTo(map).bindPopup(addressName).openPopup();
        });
    }
    // listen for the results event and add every result to the map
    searchControl.on("results", function(data) {
        results.clearLayers();
        for (var i = data.results.length - 1; i >= 0; i--) {
            getAddressEdit(data);
        }
    });
    var marker;
    map.on('click', function(e) {
        getAddressEdit(e);
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>