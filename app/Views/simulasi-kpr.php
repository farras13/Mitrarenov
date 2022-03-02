<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="content-wrapper">
  <div class="page-title">
    <h1>Kredit Bangun & Renovasi</h1>
  </div>

  <div class="container mb-5">
    <div class="row mt-4">
      <div class="col-md-6 mb-4">
        <div class="card radius-20 card-shadow">
          <div class="card-body p-5">
            <h3 class="text-30 text-center text-primary mb-4">
              Perhitungan KPR
            </h3>
            <form action="#">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp.</span>
                </div>
                <input type="text" id="hb" class="form-control text-center number-separator" placeholder="Harga Bangun / Renovasi Rumah">
                <input type="hidden" id="result_input" name="">
              </div>
              <div class="input-group mb-3">
                <input type="text" id="um" class="form-control text-center" placeholder="Masukkan Uang Muka ( Contoh : 30 ) ">
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
              </div>
              <div class="mb-3">
                <p class="mb-0">
                  Jumlah Uang Muka : <span id="uangmuka">-</span>
                </p>
                <p class="mb-0">
                  Pokok Pembiayaan : <span id="pembiayaan">-</span>
                </p>
              </div>
              <div class="input-group mb-3">
                <input type="text" id="bunga" class="form-control text-center" placeholder="Masukkan Bunga Bank ( Contoh : 20 ) ">
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
              </div>
              <div class="mb-3">
                <select class="waktu-pinjaman w-100" id="jangka">
                  <option value=""></option>
                  <option value="5">5 Tahun</option>
                  <option value="10">10 Tahun</option>
                  <option value="15">15 Tahun</option>
                </select>
              </div>
              <div class="text-center">
                <button type="button" onclick="hitungKpr()" class="btn btn-primary btn-lg btn-radius btn-hitung px-5">HITUNG</button>
              </div>

              <div class="row mt-4">
                <div class="col-md-6">
                  <div class="perbulan">
                    Angsuran Perbulan
                    <div class="h3" id="angsuran"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="penghasilan">
                    Minimum Penghasilan ( Gaji )
                    <div class="h3" id="minimg"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="syarat-ketentuan">
          <h5 class="mb-4">Syarat dan Ketentuan</h5>
          <ol class="pl-3">
            <li>
              WNI dan berdomisili di Indonesia
            </li>
            <li>
              Karyawan tetap dengan pengalaman kerja minimal 2 tahun atau Wiraswasta dengan pengalaman usaha minimal 2
              tahun atau Profesional dengan pengalaman praktek minimal 2 tahun
            </li>
            <li>
              Usia minimal pada saat pembiayaan diberikan adalah 21 tahun, dan maksimal usia pensiun untuk karyawan
              atau
              65 tahun untuk wiraswasta dan profesional
            </li>
            <li>
              Memiliki pendapatan bersih bulanan minimum sebesar UMR (Upah Minimum Regional) untuk karyawan,
              wiraswasta,
              dan profesional (informasi untuk pengajuan wilayah sekitar Jabodetabek)
            </li>
            <li>
              Melengkapi dokumen-dokumen yang diperlukan, antara lain: <br>
              Karyawan: Kartu Tanda Penduduk (KTP), Sertifikat, IMB, Kartu Keluarga, dan Surat Nikah, Slip Gaji
              terakhir
              atau Surat Keterangan Gaji, Rekening koran tabungan 3 bulan terakhir, NPWP pribadi <br>
              Wiraswasta: Kartu Tanda Penduduk (KTP), Sertifikat, IMB, Kartu Keluarga, dan Surat Nikah, Rekening
              koran/tabungan 3 bulan terakhir, Laporan Keuangan 2 tahun terakhir, Legalitas Usaha (Akte Pendirian
              berikut perubahan terakhir, TDP, SIUP, NPWP), NPWP Pribadi <br>
              Profesional: Kartu Tanda Penduduk (KTP), Sertifikat, IMB, Kartu Keluarga, dan Surat Nikah, Rekening
              koran/tabungan 3 bulan terakhir, izin praktek yang masih berlaku, NPWP Pribadi
            </li>

          </ol>
        </div>
      </div>
    </div>

    <h2 class="text-primary text-center my-5">
      Pengajuan Kredit Bangun & Renovasi Rumah
    </h2>

    <form action="#">
      <div class="row pengajuan-kredit">
        <div class="col-md-6 mb-5">
          <div class="px-5">
            <h5 class="mb-4">Detail Informasi</h5>
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
              <input type="text" class="form-control pl-35" placeholder="Alamat">
            </div>
            <div class="form-group">
              <input type="text" class="form-control pl-35" placeholder="Luas Bangun">
              <div class="mt-2 text-grey">
                <i>* masukan luas rumah yang hendak dibangun atau renovasi</i>
              </div>
            </div>
            <div class="form-group">
              <textarea class="form-control pl-35" cols="30" rows="3" placeholder="Deskripsi"></textarea>
              <div class="mt-2 text-grey">
                <i>* Jelaskan sedetail mungkin kebutuhan anda, spesifikasi, bahan dan
                  material yang anda impikan, juga budget pembangunan anda</i>
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control pl-35" placeholder="Jangka Waktu">
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-5">
          <div class="px-5">
            <h5 class="mb-4">Kontak Pribadi</h5>
            <div class="form-group">
              <input type="text" class="form-control pl-35" placeholder="Nama Lengkap">
            </div>
            <div class="form-group">
              <input type="text" class="form-control pl-35" placeholder="Telepon">
            </div>
            <div class="form-group">
              <input type="email" class="form-control pl-35" placeholder="Email">
            </div>
            <div class="row form-group align-items-center my-4">
              <div class="col-6">
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
            <div class="input-inline mb-3">
              <input type="text" class="form-control pl-35 date" placeholder="Jangka Waktu">
              <span class="input-icon"><i class="ico ico-calendar"></i></span>
            </div>
            <div class="form-group">
              <input type="text" class="form-control pl-35" placeholder="Kode Referal">
              <div class="mt-2 text-grey">
                <i>* Opsional</i>
              </div>
            </div>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">
                <span></span>
                Saya telah membaca dan menyetujui <a href="#">syarat dan ketentuan</a> yang berlaku.
              </label>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-success btn-lg btn-radius btn-block">SUBMIT</button>
            </div>
            <div class="mt-4">
              Jika memiliki pertanyaan silahkan hubungi kami di email <a href="mailto:info@mitrarenov.com">info@mitrarenov.com</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?= $this->section('script') ?>
<script src="<?= base_url('public/main/js/easy-number-separator.js') ?>"></script>
<script>
  $(document).ready(function() {
    easyNumberSeparator({
      selector: '.number-separator',
      separator: '.',
      resultInput: '#result_input',
    })
    $(".waktu-pinjaman").select2({
      placeholder: "Jangka Waktu Pinjaman",
      minimumResultsForSearch: -1,
      selectionCssClass: "text-center",
    });
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
    $('.date').datepicker()
  })
</script>
<script>


  function formatNumber(num) {
    return (
      num
      .toFixed(0)
      .replace('.', '')
      .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    )
  }

  function hitungKpr() {
    var harga = document.getElementById('result_input').value;
    var dp = document.getElementById('um').value;
    var bunga = document.getElementById('bunga').value;
    var jw = $('#jangka').val();;
    var bulan = jw * 12;
    var dp_persen = dp / 100;
    var bungaEfektif = bunga / 100;
    var temp_dp = harga * dp_persen;
    var pokok = harga - temp_dp;

    var _bungaPerBulan = bungaEfektif / 12;

    var _bungaPerBulan1 = 1 + (bungaEfektif / 12);

    var _bungaPerBulan1Exp = Math.pow(_bungaPerBulan1, bulan);

    var _bungaPerBulan2Exp = Math.pow(_bungaPerBulan1, bulan) - 1;

    var _angsuranPerBulan1 = pokok * _bungaPerBulan * _bungaPerBulan1Exp;

    var angsuranPerBulan = Math.round(_angsuranPerBulan1 / _bungaPerBulan2Exp);
    var gajiMinumun = Math.round(angsuranPerBulan / 0.4);

    document.getElementById("uangmuka").innerHTML = formatNumber(temp_dp);
    document.getElementById("pembiayaan").innerHTML = formatNumber(pokok);
    document.getElementById("angsuran").innerHTML = formatNumber(angsuranPerBulan) ;
    document.getElementById("minimg").innerHTML = formatNumber(gajiMinumun);

  }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>