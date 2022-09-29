<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Transactional Email</title>
    <style type="text/css">
      /* -------------------------------------
                GLOBAL RESETS
            ------------------------------------- */
              /* -------------------------------------
                BODY & CONTAINER
            ------------------------------------- */
              /* -------------------------------------
                HEADER, FOOTER, MAIN
            ------------------------------------- */
              /* -------------------------------------
                TYPOGRAPHY
            ------------------------------------- */
              /* -------------------------------------
                BUTTONS
            ------------------------------------- */
              /* -------------------------------------
                OTHER STYLES THAT MIGHT BE USEFUL
            ------------------------------------- */
              /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
              @media only screen and (max-width: 620px) {
                  table.body h1 {
                      font-size: 28px !important;
                      margin-bottom: 10px !important;
                  }
                  table.body p,
                  table.body ul,
                  table.body ol,
                  table.body td,
                  table.body span,
                  table.body a {
                      font-size: 16px !important;
                  }
                  table.body .wrapper,
                  table.body .article {
                      padding: 10px !important;
                  }
                  table.body .content {
                      padding: 0 !important;
                  }
                  table.body .container {
                      padding: 0 !important;
                      width: 100% !important;
                  }
                  table.body .main {
                      border-left-width: 0 !important;
                      border-radius: 0 !important;
                      border-right-width: 0 !important;
                  }
                  table.body .btn table {
                      width: 100% !important;
                  }
                  table.body .btn a {
                      width: 100% !important;
                  }
                  table.body .img-responsive {
                      height: auto !important;
                      max-width: 100% !important;
                      width: auto !important;
                  }
              }
              /* -------------------------------------
                PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
              @media all {
                  .ExternalClass {
                      width: 100%;
                  }
                  .ExternalClass,
                  .ExternalClass p,
                  .ExternalClass span,
                  .ExternalClass font,
                  .ExternalClass td,
                  .ExternalClass div {
                      line-height: 100%;
                  }
                  .apple-link a {
                      color: inherit !important;
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      text-decoration: none !important;
                  }
                  #MessageViewBody a {
                      color: inherit;
                      text-decoration: none;
                      font-size: inherit;
                      font-family: inherit;
                      font-weight: inherit;
                      line-height: inherit;
                  }
                  .btn-primary table td:hover {
                      background-color: #34495e !important;
                  }
                  .btn-primary a:hover {
                      background-color: #34495e !important;
                      border-color: #34495e !important;
                  }
              }
    </style>
  </head>
  <body onload="window.print()" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
    <!-- <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span> -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
      <tr>
        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
        <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:680px;padding:10px;width:680px;margin:0 auto !important;">
          <div class="content" style="box-sizing:border-box;display:block;margin:0 auto;max-width:680px;padding:10px;">
            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                        PT. NAVAKA MITRARENOV INDOTAMA <br>
                        Rukan Taman Pondok Kelapa Blok F No.1 <br>
                        Duren Sawit - Jakarta Timur
                                        </td>
                      <td class="align-right" style="font-family:sans-serif;font-size:14px;vertical-align:top;text-align:right;">
                        <img src="https://mitrarenov.soldig.co.id/public/main/images/logo-mitrarenov.png" class="logo" alt="" style="border:none;-ms-interpolation-mode:bicubic;max-width:100%;max-width:200px;">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                  <h1 class="align-left title-i" style="color:#000000;font-family:sans-serif;font-weight:400;line-height:1.4;margin:0;margin-bottom:30px;font-size:35px;font-weight:300;text-align:center;text-transform:capitalize;text-align:left;font-weight:600;background:#188ad9;color:#fff;display:inline-block;padding:10px 20px 5px 20px;margin-bottom:0;">INVOICE</h1>
                </td>
              </tr>
              <tr>
                <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
                  <table class="main" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;margin-bottom: 30px;">
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                        <table class="main main-inner" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Nomor Invoice</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>: <?= $bayar->nomor_invoice; ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Tanggal Invoice</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>: <?= date('d F Y'); ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;padding-bottom:30px;">Jatuh Tempo</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;padding-bottom:30px;"><strong>: <?= date('d F Y', strtotime($bayar->due_date)); ?></strong></td>
                          </tr>
                         
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Nomor Kontrak</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>: <?= $project->nomor_kontrak != null ? $project->nomor_kontrak : "-"; ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Tanggal Kontrak</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>: <?= $persetujuan != null ? date('d F Y', strtotime($persetujuan->tanggal_dimulai)) : date('d F Y', $bayar->tanggal_dibuat); ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Nilai Kontrak</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>: Rp <?= $persetujuan != null ? number_format($persetujuan->rab,0,',','.') : number_format($bayar->biaya,0,',','.')  ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Status</td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;<?php if($bayar->status != 'sudah dibayar'){ ?>color: red;<?php } ?>"><strong>: <?= $bayar->status ?></strong></td>
                          </tr>
                        </table>
                      </td>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                        <table class="main main-inner" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Kepada Yth,</td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong><?= $detail->customer; ?></strong></td>
                          </tr>
                          <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">Di Tempat</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  <table class="main table-inv" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;margin-bottom: 30px;">
                    <thead>
                      <tr>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;">No</td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;width: 40%;">Uraian</td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;">Tagihan Terbayarkan</td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;">Total Tagihan</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><strong>I</strong></td>
                        <td colspan="3" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><strong>TERBAYARKAN</strong></td>
                      </tr>
                      <!--  -->
                      <?php $n=1; if($pembayaran_done != null){ foreach($pembayaran_done as $pd):  $biaya = str_replace('.','',$pd->biaya);?>
                      <tr>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><?= $n++; ?></td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;">
                         <?= $pd->keterangan != '' ? $pd->keterangan : '-'; ?>
                                            </td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;">
                          <table class="main tt-bb" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                            <tr>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;">Rp</td>
                              <td class="align-right" style="font-family:sans-serif;font-size:14px;vertical-align:top;text-align:right;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;"><?= $biaya != '' ? number_format($biaya, 0, ',', '.') : '-';?></td>
                            </tr>
                          </table>
                        </td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"></td>
                      </tr>
                      <?php endforeach; }?>
                      <tr>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><strong>II</strong></td>
                        <td colspan="3" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><strong>Tagihan</strong></td>
                    </tr>
                    <!--  -->
                    <?php $tagihan =0;  ?>
                      <?php foreach($pembayaran as $p): ?>
                        <tr>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"><?= $n++; ?></td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;">
                            <strong>
                                <?= $p->keterangan; ?>
                                                    </strong>
                            </td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;"></td>
                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;">
                            <table class="main tt-bb" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                                <tr>
                                <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;">Rp</td>
                                <td class="align-right" style="font-family:sans-serif;font-size:14px;vertical-align:top;text-align:right;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;"><?=  number_format(str_replace('.','',$p->biaya), 0, ',', '.'); ?></td>
                                </tr>                           
                            </table>  
                            </td>
                        </tr>
                        <?php $tagihan += str_replace('.','',$p->biaya);  ?>
                      <?php endforeach;  ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="align-right" style="font-family:sans-serif;font-size:14px;vertical-align:top;text-align:right;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;">Total Tagihan</td>
                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;background:#000;color:#fff;font-weight:600;border-color:#000;">
                          <table class="main tt-bb" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                            <tr>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;background:#000;color:#fff;font-weight:600;border-color:#000;">Rp</td>
                              <td class="align-right" style="font-family:sans-serif;font-size:14px;vertical-align:top;text-align:right;padding:15px;vertical-align:middle;border:1px #dbdbdb solid;background:#fbfbfb;padding:0;border:0;background:#000;color:#fff;font-weight:600;border-color:#000;"><?= number_format($tagihan, 0, ',', '.'); ?></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                  <table class="main main-inner" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#ffffff;border-radius:3px;width:100%;">
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>Terbilang: <?= $terbilang ?></strong></td>
                    </tr>
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>Sisa Tagihan : Rp <?= number_format($tagihan, 0, ',', '.'); ?></strong></td>
                    </tr>
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>Mohon Lakukan Pembayaran Tagihan Anda <a href="#" style="color:#3498db;text-decoration:underline;">Klik Disini</a></strong></td>
                    </tr>
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;"><strong>Atau Scan QR Berikut</strong></td>
                    </tr>
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-top:5px;padding-bottom:5px;">
                        <img src="https://www.imgonline.com.ua/examples/qr-code.png" style="border:none;-ms-interpolation-mode:bicubic;max-width:100%;max-width: 100px;" alt="">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
