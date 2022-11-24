<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\AuthModel;
use App\Models\DboModel;
use App\Models\GeneralModel;

class Order extends BaseController
{
    function __construct()
    {
		$this->model = new GeneralModel();
    }   
    
    // ORDER PROJECT
    public function index($id)
    {
        $sess = session();
        $idn = $sess->get('user_id');
        if ($idn != null) {
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori,id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
            }
            $no = 0;
            $chat = 0;
            foreach ($temp as $key => $value) {
                if($value->status == 0){
                    $no++;
                }
                if($value->status == 0 && $value->kategori == "chat"){
                    $chat++;
                }
            }
			$data['notif'] = $temp;
			$data['notif_total'] = $no;
			$data['chat_total'] = $chat;
        }
        
        $req = $this->request->getVar();		
		$jenis = str_replace('-',' ',$id);
        $produk = $this->model->getWhere('product', ['paket_name' => $jenis])->getRow();
        $category = $this->model->getWhere('category', ['id' => $produk->category_id])->getRow();
        $spek = $this->model->getWhere('product_price', ['product_id' => $produk->id])->getResult();
		$tipe_rumah = $this->model->getWhere('tipe_rumah', ['product_id' => $produk->id])->getResult();
		// var_dump($produk->id);die;
		if(empty($tipe_rumah)){
			$tipe_rumah = $this->model->getWhere('tipe_rumah', ['product_id' => 0])->getResult();
		}
        $data['alur'] = $this->model->getAll('rules')->getResult();
		$data['type'] = $category->id;
        $data['jenis'] = $jenis;
        $data['nama'] = $sess->get('user_name');
        $data['phone'] = $sess->get('user_phone');
        $data['email'] = $sess->get('user_email');
        $data['tipe_rumah'] = $tipe_rumah;
        $data['spek'] = $spek;
		$data['produk'] = $produk;
        return view('order', $data);
    }

	public function searchPromo()
	{
		$req = $this->request->getVar();
        $temp = $req['promo'];
		$data = $this->model->getWhere("promomobile", ['promoCode' => $temp])->getRow();		
		if(empty($data)){
			$data = false;
		}else{
			if(strtotime($data->expired) > time()){
				$data = true;
			}else{
				$data = false;
			}
		}
		echo json_encode($data);
	}

    public function order_ins()
    {
        $auth = new AuthModel();
        $sess = session();
        $input = $this->request->getVar();
        
        // cek akun
        if(!empty($sess->get('user_id'))){
            $log_sign = TRUE;
            $id = $sess->get('user_id');
        }else{
            $log_sign = FALSE;
            $cek_member = $this->model->getWhere('member', ['email' => $input['email']])->getRow();
			
            if(empty($cek_member)){
                $insert_h['usergroup_id'] = 5;
                $insert_h['email'] = $input['email'];
                $insert_h['password'] = md5('123456');
                $insert_h['created'] = time();
                $insert_h['last_login'] = time();
                $insert_h['created_by'] = 2;
                // ins new account
                $this->model->insB('member', $insert_h);
                // get last id account
                $id = $this->model->lastId('member', 1)->getRow()->id;
                // make referal code
                $singkatan = str_replace(' ', '', $input['name']);
                $fourname = substr($singkatan, 0, 4);
                $last = $auth->hitung();
                $referal = '' . $fourname . '' . $last;
                // detail member
                $insert_d['name'] = $input['nama_lengkap'];
                $insert_d['telephone'] = $input['telepon'];
                $insert_d['handphone'] = $input['telepon'];
                $insert_d['member_id'] = $id;
                $insert_d['referal'] = $referal;
                $insert_d['created'] = time();
                $insert_d['city_id'] = 0;
                $insert_d['address'] = $input['alamat'];
                $insert_d['created_by'] = 2;
                // ins detail member
                $this->model->ins('member_detail', $insert_d);
            }else{
                $id = $cek_member->id;
                $this->model->upd('member', ['id' => $id], ['last_login' => time()]);
            }
        }
        // cek area mitrarenov
        $cek_area = $this->model->getAll('area')->getResult();
        $log_area = false;
        $area = "";
        foreach ($cek_area as $c) {
            if (strpos(strtolower($input['city']), strtolower('bks')) > -1) {
                $input['city'] = "Bekasi";
            }
            if (strpos(strtolower($input['city']), strtolower($c->nama_area)) > -1) {
                $log_area = true;
                $area = $c->id_area;
            }
        }
        if ($log_area == false) {
            session()->setFlashdata('toast', 'error:Maaf area anda belum kami jangkau!.');
            return redirect()->back()->withInput();
        }

        // initial input form
        if(is_numeric($input['luas']) != 1){
            session()->setFlashdata('toast', 'error:Maaf pastikan inputan luas berupa angka 0-9!.');
            return redirect()->back()->withInput();
        }
        $path_uploadImg = "./public/images/projek";
        $file_rumah = $this->request->getFile('gambar_rumah');
        $jenis_order = $input['jenis_order'];
        $tipe_rumah = $input['tiperumah'];
        $total = $input['totalHarga'];
        $id_spek =  $input['spek'];
        $uploadImg = $this->uploadImage($file_rumah, $path_uploadImg);
        if ($uploadImg != null) {
            $img_rumah = $uploadImg['data']['file_name'];
        }
        // make noprojek
        $db = db_connect();
        $noprojek = $db->query("SELECT COUNT(id) as hitung FROM projects WHERE DATE_FORMAT(FROM_UNIXTIME(created), '%Y%m') = EXTRACT(YEAR_MONTH FROM CURRENT_DATE()) ")->getRow();
        $temp_nopro = (int)$noprojek->hitung + 1;
        $coun = strlen($noprojek->hitung);
        if ($coun == 1) {
            $ht = '0000' . '' . $temp_nopro;
        } elseif ($coun == 2) {
            $ht = '000' . '' . $temp_nopro;
        } elseif ($coun == 3) {
            $ht = '00' . '' . $temp_nopro;
        } elseif ($coun == 4) {
            $ht = '0' . '' . $temp_nopro;
        } else {
            $ht = $temp_nopro;
        }

		//promo 
		if($input['promo'] != null || $input['promo'] != '' ){
			$promo = $this->model->getWhere('promomobile',['promocode' => $input['promo']])->getRow();
			if (strtotime($promo->expired) < time()) {
				session()->setFlashdata('toast', 'error:Maaf promo expired!.');
            	return redirect()->back()->withInput();
			}
			if(empty($promo)){
				session()->setFlashdata('toast', 'error:Maaf promo tidak tersedia!.');
            	return redirect()->back()->withInput();
			}
			$nilai_promo = $total * $promo->promo / 100;
			$total = $total - $nilai_promo;
		}
       
        // insert projects
        $insert = [
            'project_number' => date("dmY", time()) . "" . $ht,
            'type' => $jenis_order,
            'status_project' => 'quotation',
            'luas' => $input['luas'],
            'alamat_pengerjaan' => $input['alamat'],
            'description' => $input['deskripsi'],
            'image_upload' => $img_rumah,
            'total' => $total,
            'total_real' => $total,
            'marketing_name' => $input['marketing'],
            'metode_payment' => $input['metodpay'],
            'created' => time(),
            'latitude' => $input['lat'],
            'longitude' => $input['long'],
            'catatan_alamat' => $input['catatan_alamat'],
            'kode_referal' =>  $input['referal'],
            'kode_promo' => $input['promo'],
            'id_area' => $area,
            'device' => 3
        ];
        $temp_id_projek = $this->model->lastId('projects', 1)->getRow()->id;

        // insert projects_desain
        $id_projek = $temp_id_projek + 1;
        $insert_data = [
            'project_id' => $id_projek,
            'name' => $input['nama_lengkap'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'phone' => $input['telepon'],
            'created' => time(),
            'member_id' => $id,
            'device' => 3
        ];

        $idd = $this->model->lastId('projects_desain')->getRow()->id;

        // insert detail project
        $temp_produk = $this->model->getWhere('product_price', ['id' => $id_spek])->getRow();    

        $insert_detail = [
            'project_id' => $id_projek,
            'product_id' => $temp_produk->product_id,
            'product_price_id' => $id_spek,
            'desain_id' => (int)$idd + 1
        ];

		$cek_subkon = $this->model->getWhere('product', ['id' => $temp_produk->product_id])->getRow();
		if($cek_subkon != null && !empty($cek_subkon)){
			$insert['tukang_id'] = $cek_subkon->tukang_id;
		}
        
        // insert design or not
        if($tipe_rumah != null){
            $insert_design = [
                'tipe_rumah' => $tipe_rumah,
                'created' => date('Y-m-d H:i:s')
            ];
            $this->model->ins('projects_desain', $insert_design);
        }
        $this->model->ins('projects', $insert);
        $this->model->ins('project_data_customer', $insert_data);
        $this->model->ins('projects_detail', $insert_detail);
        
        // send email 
        if ($log_sign == TRUE) {
            $emailData = array(
                'from' => 'testing@mitrarenov.com',
                'name' => 'testing@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Permintaan Jasa di Mitrarenov.com"             
            );
        } else {
            $emailData = array(
                'from' => 'testing@mitrarenov.com',
                'name' => 'testing@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Informasi Akun & Permintaan Jasa di Mitrarenov.com"            
            );
        }

        $this->sendEmailAktifasi($emailData, $log_sign);

        $data2 = array(
            'nama' => $input['nama_lengkap'],
            'no_telp' => $input['telepon'],
            'project_number' => $insert['project_number'],
            'nama_tukang' => '',
            'hp_tukang' => '',
            'subject' => "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'],
            'ins_project' => $id_projek,
            'product_id' => $insert_detail['product_id'],
            'provinceVal' => $insert['longitude'] . "," . $insert['latitude'],
            'catatan_alamat' => $insert['catatan_alamat'],
            'marketing_name' => $insert['marketing_name']
        );

        $decode_tukang = $this->model->findAllTukang($area);

        $subject_tukang = "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'];
        foreach ($decode_tukang as $key => $d2) {
            if ($d2->email_tukang !== null or $d2->email_tukang !== '') {

                $email = \Config\Services::email();
                $email->setFrom('testing@mitrarenov.com', 'testing@mitrarenov.com');
                $email->setTo($d2->email_tukang);
                $email->setSubject($subject_tukang);
                $email->setMessage($this->contenttukang($data2, $d2));

                if (!$email->send()) {
                    // Generate error
                    //echo "Email is not sent!!";
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'gagal');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                } else {
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'terkirim');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                }
            }
        }

        session()->setFlashdata('toast', 'success:Selamat pengajuan Order berhasil dikirim !.');
        return redirect()->to('order/sukses');
    }    

    public function order_sukses()
    {
        $sess = session();
        $idn = $sess->get('user_id');
        
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
            }
            // var_dump($temp);die;
            $no = 0;
            $chat = 0;
            foreach ($temp as $key => $value) {
                if($value->status == 0){
                    $no++;
                }
                if($value->status == 0 && $value->kategori == "chat"){
                    $chat++;
                }
            }
        }
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        
        $data['nama'] = $sess->get('user_name');
        $data['phone'] = $sess->get('user_phone');
        $data['email'] = $sess->get('user_email');
        return view('order-sukses', $data);
    }

    public function sendEmailAktifasi($emailData = array(), $tipe)
    {
        $temp = $this->model->getWhere('email_ebook', array('id' => '1'))->getResult();
        $email = \Config\Services::email();
        $email->setFrom('testing@mitrarenov.com', 'testing@mitrarenov.com');
        $email->setTo($emailData['to']);
        $email->setSubject($emailData['subject']);
        if ($tipe == 0) {
            $msg = $this->content3($temp, $emailData['to']);

            $email->setMessage($msg);
        } else {
            $msg = $this->content2($temp, $emailData['to']);
            $email->setMessage($msg);
        }

        if (!$email->send()) {
            $this->tambah_log_email_db('permintaan jasa', $emailData['to'], 'customer', 'gagal');
        } else {
            $this->tambah_log_email_db('permintaan jasa', $emailData['to'], 'customer', 'terkirim');
        }
    }

	private function jasaType($product_id = "")
    {
        $jasa = "";
        if ($product_id == 1) {
            $jasa = "Membangun Rumah";
        } else if ($product_id == 2) {
            $jasa = "Membuat Dak";
        } else if ($product_id == 3) {
            $jasa = "Menambah Ruangan";
        } else if ($product_id == 4) {
            $jasa = "Meningkat Rumah";
        } else if ($product_id == 5) {
            $jasa = "Perbaikan Genteng";
        } else if ($product_id == 6) {
            $jasa = "Pengecatan";
        } else if ($product_id == 7) {
            $jasa = "Membuat Carport";
        } else if ($product_id == 8) {
            $jasa = "Membangun Rumah Kost";
        } else if ($product_id == 9) {
            $jasa = "Membangun Ruko";
        } else if ($product_id == 10) {
            $jasa = "Pembuatan Kolam Renang";
        } else if ($product_id == 11) {
            $jasa = "Pekerjaan Interior";
        } else {
            $jasa = "Pekerjaan Lain";
        }

        return $jasa;
    }

    public function uploadImage($file, $path)
    {
        helper(['form']);
        $date = date('Y-m-d');
        $profile_image = $file->getName();
        $nameNew = $date . substr($profile_image, 0, 5) . rand(0, 20).'.'.$file->guessExtension();
        // var_dump($newfilename);die;
        if (!$file->isValid()) {
            return $response = $file->getErrorString();
        }

        if ($file->move($path, $nameNew)) {
            $response = [
                'data' => [
                    "file_name" => $nameNew,
                    "file_path" => $path,
                ]
            ];
        } else {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => 'Failed to upload image',
                'data' => []
            ];
        }

        return $response;
    }

    public function tambah_log_email_db($tipe, $email, $role, $status)
    {
        $inserts['tipe'] = $tipe;
        $inserts['email'] = $email;
        $inserts['role'] = $role;
        $inserts['status'] = $status;
        $query = $this->model->insB('log_email', $inserts);
    }

    private function content3($temp, $email)
    {
        return '
			<!DOCTYPE html>

			<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
			<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				
				<style type="text/css">
					body {
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						margin: 0 !important;
						width: 100% !important;
						-webkit-text-size-adjust: 100% !important;
						-ms-text-size-adjust: 100% !important;
						-webkit-font-smoothing: antialiased !important;
					}

					.tableContent img {
						border: 0 !important;
						display: block !important;
						outline: none !important;
					}

					a {
						color: #382F2E;
					}

					p, h1 {
						color: #382F2E;
						margin: 0;
					}

					p {
						text-align: left;
						color: #999999;
						font-size: 14px;
						font-weight: normal;
						line-height: 19px;
					}

					a.link1 {
						color: #382F2E;
					}

					a.link2 {
						font-size: 16px;
						text-decoration: none;
						color: #ffffff;
					}

					h2 {
						text-align: center;
						color: #222222;
						font-size: 19px;
						font-weight: normal;
					}

					div, p, ul, h1 {
						margin: 0;
					}

					.bgBody {
						background: #ffffff;
					}

					.bgItem {
						background: #ffffff;
					}

					.btn {
						background: darkorange;
						background-image: -webkit-linear-gradient(top, darkorange, darkorange);
						background-image: -moz-linear-gradient(top, darkorange, darkorange);
						background-image: -ms-linear-gradient(top, darkorange, darkorange);
						background-image: -o-linear-gradient(top, darkorange, darkorange);
						background-image: linear-gradient(to bottom, darkorange, darkorange);
						-webkit-border-radius: 28;
						-moz-border-radius: 28;
						border-radius: 28px;
						font-family: Arial;
						color: #ffffff;
						font-size: 20px;
						padding: 10px 20px 10px 20px;
						text-decoration: none;
					}

						.btn:hover {
							background: #3cb0fd;
							background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
							background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
							text-decoration: none;
						}
				</style>
				<script type="colorScheme" class="swatch active">
					{
					"name":"Default",
					"bgBody":"ffffff",
					"link":"382F2E",
					"color":"999999",
					"bgItem":"ffffff",
					"title":"222222"
					}
				</script>

			</head>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">

					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">

											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->

											<tr>
												<td class="movableContentContainer" valign="top">

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">SELAMAT</h2>
																		</div>
																		<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa Anda Telah Kami Terima</strong></label>
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															

															<tr><td height="15"> </td></tr>

															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu, <br /> <br />
																				Permintaan jasa Anda telah kami terima, silahkan login dengan akun dibawah ini :<br />
                                                                                <b>Email : ' . $email . ' <br />
                                                                                Password : 123456 <br /><br />
                                                                                Silahkan Download dan Login pada aplikasi android mitrarenov dengan mengakses link dibawah ini : <br /><br />
                                                                                
      <a style="color:#337ab7" href="https://play.google.com/store/apps/details?id=hugaf.mitrarenov.com"><b><u>DOWNLOAD APLIKASI ANDROID MITRARENOV</u></b></a>	
																			
																				<br>
					  
																				
																				<br /><br />
																				Jika memerlukan informasi lebih lanjut dapat mengirimkan ke email: <a href="mailto:info@mitrarenov.com">info@mitrarenov.com </a> atau telepon di 0822-9000-9990

																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>

															<tr>
																<td height="20">
																	<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																		<a href="' . $temp[0]->link_iklan . '" ><img src="http://mitrarenov.com/cdn/images/email_setting/' . $temp[0]->banner_iklan . '" style="width:100%;" /> </a>
																	</p>


																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>

																					</div>
																				</div>
																			</td>


																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />

												</td>
											</tr>



											<!-- =============================== footer ====================================== -->



										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr><td height="88"></td></tr>


				</table>

			</body>
			</html>
		';
    }

    private function content2($temp, $email)
    {
        return '
			<!DOCTYPE html>

			<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
			<head>
				
				<style type="text/css">
					body {
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						margin: 0 !important;
						width: 100% !important;
						-webkit-text-size-adjust: 100% !important;
						-ms-text-size-adjust: 100% !important;
						-webkit-font-smoothing: antialiased !important;
					}

					.tableContent img {
						border: 0 !important;
						display: block !important;
						outline: none !important;
					}

					a {
						color: #382F2E;
					}

					p, h1 {
						color: #382F2E;
						margin: 0;
					}

					p {
						text-align: left;
						color: #999999;
						font-size: 14px;
						font-weight: normal;
						line-height: 19px;
					}

					a.link1 {
						color: #382F2E;
					}

					a.link2 {
						font-size: 16px;
						text-decoration: none;
						color: #ffffff;
					}

					h2 {
						text-align: center;
						color: #222222;
						font-size: 19px;
						font-weight: normal;
					}

					div, p, ul, h1 {
						margin: 0;
					}

					.bgBody {
						background: #ffffff;
					}

					.bgItem {
						background: #ffffff;
					}

					.btn {
						background: darkorange;
						background-image: -webkit-linear-gradient(top, darkorange, darkorange);
						background-image: -moz-linear-gradient(top, darkorange, darkorange);
						background-image: -ms-linear-gradient(top, darkorange, darkorange);
						background-image: -o-linear-gradient(top, darkorange, darkorange);
						background-image: linear-gradient(to bottom, darkorange, darkorange);
						-webkit-border-radius: 28;
						-moz-border-radius: 28;
						border-radius: 28px;
						font-family: Arial;
						color: #ffffff;
						font-size: 20px;
						padding: 10px 20px 10px 20px;
						text-decoration: none;
					}

						.btn:hover {
							background: #3cb0fd;
							background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
							background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
							text-decoration: none;
						}
				</style>
				<script type="colorScheme" class="swatch active">
					{
					"name":"Default",
					"bgBody":"ffffff",
					"link":"382F2E",
					"color":"999999",
					"bgItem":"ffffff",
					"title":"222222"
					}
				</script>

			</head>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">

					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">

											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->

											<tr>
												<td class="movableContentContainer" valign="top">

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">SELAMAT</h2>
																		</div>
																		<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa Anda Telah Kami Terima</strong></label>
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															

															<tr><td height="15"> </td></tr>

															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu, <br /> <br />
																				Permintaan jasa Anda telah kami terima.<br /><br />
																				Silahkan Download dan Login pada aplikasi android mitrarenov dengan mengakses link dibawah ini : <br /><br />
                                                                                
      <a style="color:#337ab7" href="https://play.google.com/store/apps/details?id=hugaf.mitrarenov.com"><b><u>DOWNLOAD APLIKASI ANDROID MITRARENOV</u></b></a>	
																			
																				<br>

																			
																				<br>
																				<!--Silahkan klik button "DOWNLOAD" untuk mendapatkan free ebook
																				<br /><br /> 
																				<a href="' . $temp[0]->link_ebook . '" style="background: darkorange;
																					background-image: -webkit-linear-gradient(top, darkorange, darkorange);
																					background-image: -moz-linear-gradient(top, darkorange, darkorange);
																					background-image: -ms-linear-gradient(top, darkorange, darkorange);
																					background-image: -o-linear-gradient(top, darkorange, darkorange);
																					background-image: linear-gradient(to bottom, darkorange, darkorange);
																					-webkit-border-radius: 28;
																					-moz-border-radius: 28;
																					border-radius: 28px;
																					font-family: Arial;
																					color: #ffffff;
																					font-size: 20px;
																					padding: 10px 20px 10px 20px;
																					text-decoration: none;">DOWNLOAD</a>
																				-->
																				<br /><br />
																				Jika memerlukan informasi lebih lanjut dapat mengirimkan ke email: <a href="mailto:info@mitrarenov.com">info@mitrarenov.com </a> atau telepon di 0822-9000-9990

																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>

															<tr>
																<td height="20">
																	<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																		<a href="' . $temp[0]->link_iklan . '" ><img src="http://mitrarenov.com/cdn/images/email_setting/' . $temp[0]->banner_iklan . '" style="width:100%;" /> </a>
																	</p>


																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>

																					</div>
																				</div>
																			</td>


																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />

												</td>
											</tr>



											<!-- =============================== footer ====================================== -->



										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr><td height="88"></td></tr>


				</table>

			</body>
			</html>
		';
    }

    private function contenttukang($data2, $d2)
    {
        return '
			    <style type="text/css">
				body {
					padding-top: 0 !important;
					padding-bottom: 0 !important;
					padding-top: 0 !important;
					padding-bottom: 0 !important;
					margin: 0 !important;
					width: 100% !important;
					-webkit-text-size-adjust: 100% !important;
					-ms-text-size-adjust: 100% !important;
					-webkit-font-smoothing: antialiased !important;
				}

				.tableContent img {
					border: 0 !important;
					display: block !important;
					outline: none !important;
				}

				a {
					color: #382F2E;
				}

				p, h1 {
					color: #382F2E;
					margin: 0;
				}

				p {
					text-align: left;
					color: #999999;
					font-size: 14px;
					font-weight: normal;
					line-height: 19px;
				}

				a.link1 {
					color: #382F2E;
				}

				a.link2 {
					font-size: 16px;
					text-decoration: none;
					color: #ffffff;
				}

				h2 {
					text-align: center;
					color: #222222;
					font-size: 19px;
					font-weight: normal;
				}

				div, p, ul, h1 {
					margin: 0;
				}

				.bgBody {
					background: #ffffff;
				}

				.bgItem {
					background: #ffffff;
				}

				.btn {
					background: darkorange;
					background-image: -webkit-linear-gradient(top, darkorange, darkorange);
					background-image: -moz-linear-gradient(top, darkorange, darkorange);
					background-image: -ms-linear-gradient(top, darkorange, darkorange);
					background-image: -o-linear-gradient(top, darkorange, darkorange);
					background-image: linear-gradient(to bottom, darkorange, darkorange);
					-webkit-border-radius: 28;
					-moz-border-radius: 28;
					border-radius: 28px;
					font-family: Arial;
					color: #ffffff;
					font-size: 20px;
					padding: 10px 20px 10px 20px;
					text-decoration: none;
				}

					.btn:hover {
						background: #3cb0fd;
						background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
						background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
						text-decoration: none;
					}
			</style>
			<script type="colorScheme" class="swatch active">
				{
				"name":"Default",
				"bgBody":"ffffff",
				"link":"382F2E",
				"color":"999999",
				"bgItem":"ffffff",
				"title":"222222"
				}
			</script>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">
					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->
											<tr>
												<td class="movableContentContainer" valign="top">
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">PEMBERITAHUAN</h2>
																		</div>
																		<!--<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa dari <strong>' . $data2["nama"] . '</label>
																		</div>
																		-->
																	</div>
																</td>
															</tr>
														</table>
													</div>
													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<!--<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<h2>' . $data2["title"] . '</h2>
																		</div>
																	</div>
																</td>
															</tr>-->
															<tr><td height="15"> </td></tr>
															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu - ' . $d2->nama_tukang . ', anda terpilih untuk mengerjakan project dengan detail sebagai berikut: <br /> <br />
																				Nama : ' . $data2["nama"] . ' <br>
																				Telp : ' . $data2["no_telp"] . ' <br>
																				
																			</p>
																			<hr style="margin-top:10px; margin-bottom:10px; border: 1px solid blue;" />
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				Project Number : ' . $data2["project_number"] . ' <br>
																				Jasa : ' . $this->jasaType($data2["product_id"]) . ' <br>
																				Kebutuhan : ' . $data2["ins_project"]["priority"] . ' <br>
																				Luas : ' . $data2["ins_project"]["luas"] . ' <br>
																				Longitude & Latitude : ' . $data2["provinceVal"] . ' <br>
																				Lokasi : ' . $data2["ins_project"]["alamat_pengerjaan"] . ' <br>
																				Catatan Alamat : ' . $data2["ins_project"]["catatan_alamat"] . ' <br>
																				Description : ' . $data2["ins_project"]["description"] . ' <br>
																				Metode Payment : ' . $data2["ins_project"]["metode_payment"] . ' <br>
																				Total : ' . $data2["ins_project"]["total"] . ' <br>
																				Marketing Name : ' . $data2["marketing_name"] . ' <br>
																				
																				<br>
																				<div class="details">
																					Untuk informasi lengkap silahkan login pada akun anda di aplikasi
																				</div>
																				<!--Silahkan klik button "DOWNLOAD" untuk mendapatkan free ebook
																				<br /><br /> -->
																				
																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td height="20">

																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>
																					</div>
																				</div>
																			</td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />
												</td>
											</tr>

											<!-- =============================== footer ====================================== -->

										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td height="88"></td></tr>

				</table>
			</body>
		';
    }    
}