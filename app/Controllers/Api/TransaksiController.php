<?php

namespace App\Controllers\Api;

use App\Models\GeneralModel;
use CodeIgniter\RESTful\ResourceController;

use App\Libraries\Midtranspayment;

class TransaksiController extends ResourceController
{
	public function tipe_rumah()
    {
        $mdl = new GeneralModel();
		$input = $this->request->getVar();

		if(!empty($input)){
			$data = $mdl-> getWhere('tipe_rumah', ['product_id' => $input['id']])->getResult();
		}else{
			$data = $mdl-> getAll('tipe_rumah')->getResult();
		}

        if (!$data) {
			$res = [
				"status" => 200,
				"messages" => "tipe rumah kosong!",
				"data" => null
			];
        }else{
			$res = [
				"status" => 200,
				"messages" => "Sukses",
				"data" => $data
			];
		}

        return $this->respond($res, 200);
    }
    public function addCart()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $input = $this->request->getVar();
        if (!$input) {
            $this->respond('Pilih barang terlebih dahulu !', 500);
        }

        $idm = $input['id_material'];

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$idm' and item_category = 1")->getRow();

        if (!$cekMaterial) {
            return $this->respond('Material tidak tersedia !', 500);
        }

        if ($cekMaterial->item_qty < 1 || $cekMaterial->item_qty < $input['qty']) {
            return $this->respond('Stok Material tidak mencukupi !', 500);
        }

        $harga = $cekMaterial->item_price;
        $total = $cekMaterial->item_price * $input['qty'];
        $data = [
            'id_material' => $idm,
            'qty' => $input['qty'],
            'sub_total' => $harga,
            'total' => $total,
            'id_user' => $id,
        ];

        $add = $mdl->ins('cart', $data);

        if (!$add) {
            return $this->failResourceGone('gagal melakukan insert');
        }

        $res = [
            'message' => 'berhasil gan!',
            'data' => $data,
            'status' => 200,
        ];

        return $this->respond($res, 200);
    }

	public function index()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $data = $mdl->getWhere('cart', ['id_user' => $id])->getResult();

        if (!$data) {
            return $this->respond('Cart masih kosong !', 500);
        }

        $res = [
            'message' => 'berhasil gan!',
            'data' => $data,
            'error' => null,
        ];

        return $this->respond($res, 200);
    }

    public function add_by_qty()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $input = $this->request->getVar();
        $qty = 1;
        $cart = $mdl->getWhere('cart', ['id_material' => $input['id_material'], 'id_user' => $id])->getRow();
        $qty += $cart->qty;

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$cart->id_material' and item_category = 1")->getRow();

        if (!$cekMaterial) {
            return $this->respond('Material tidak tersedia !', 500);
        }

        if ($cekMaterial->item_qty < 1 || $cekMaterial->item_qty < $qty) {
            return $this->respond('Stok Material tidak mencukupi !', 500);
        }

        $total = $cart->total + $cart->sub_total;

        $data = [
            'qty' => $qty,
            'total' => $total,
        ];

        if ($mdl->upd('cart', ['id' => $cart->id], $data)) {
            return $this->respond('cart berhasil di tambahkan', 200);
        }
    }

    public function del_by_qty()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $input = $this->request->getVar();
        $cart = $mdl->getWhere('cart', ['id_material' => $input['id_material'], 'id_user' => $id])->getRow();
        $qty = $cart->qty - 1;

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$cart->id_material' and item_category = 1")->getRow();

        if (!$cekMaterial) {
            return $this->respond('Material tidak tersedia !', 500);
        }

        if ($cekMaterial->item_qty < 1 || $cekMaterial->item_qty < $qty) {
            return $this->respond('Stok Material tidak mencukupi !', 500);
        }

        $total = $cart->total - $cart->sub_total;

        $data = [
            'qty' => $qty,
            'total' => $total,
        ];

        if ($mdl->upd('cart', ['id' => $cart->id], $data)) {
            return $this->respond('cart berhasil di tambahkan', 200);
        }
    }

    public function orderProjekDesain()
    {
        $uri = current_url(true);
        $mdl = new GeneralModel();

        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();        
		$input = $this->request->getVar();

		if($cekUser == null){
			$cekEmail = $mdl->getWhere('member', ['email' => $input['email']])->getRow();        
			
			if(!$cekEmail){
				$status_cust = 0;
				$insert_h['usergroup_id'] = 5;
				
				$insert_h['email'] = $input['email'];
				$insert_d['name'] = $input['nama_lengkap'];
				$insert_d['handphone'] = $input['telepon'];
	
				$insert_h['password'] = md5('123456');
				$insert_h['created'] = time();
				$insert_h['created_by'] = 2;
				$query_h = $mdl->insId('member', $insert_h);
				if ($query_h) {
					$insert_d['member_id'] = $query_h;
					$insert_d['created'] = time();
					$insert_d['city_id'] = 0;
					$insert_d['address'] = $input['alamat'];
					$insert_d['created_by'] = 2;
					$query_d = $mdl->insB('member_detail', $insert_d);
				}
				$id = $query_h;
			}else{
				$id = $cekEmail->id;
			}
		}else{
			$id = (int)$cekUser->member_id;
			$status_cust = 1;
		}

        $temp_idp = $mdl->lastId('projects')->getRow();
        $id_projek = (int)$temp_idp->id + 1;
        // var_dump($id_projek);die;
        $tdate = date('Y-m-d');
        $date = strtotime($tdate);

        if ($input['type'] != null) {
            $temp_produk = $mdl->getWhere('product', ['id' => $input['type']])->getRow();
            $type = $temp_produk->paket_name;
        } else {
            $temp_produk = $mdl->getWhere('category', ['id' => $input['id_kategori']])->getRow();
            $type = $temp_produk->category_name;
        }

        $file_rumah = $this->request->getFile('gambar_rumah');
        $rules = [
            "deskripsi" => "required",
            "alamat" => "required",
            "spek" => "required",
            "nama_lengkap" => "required",
            "telepon" => "required",
            "email" => "required",
            "latitude" => "required",
            "longitude" => "required",
            "metode_pembayaran" => "required",
            'gambar_rumah' => 'uploaded[gambar_rumah]|mime_in[gambar_rumah,image/jpg,image/jpeg,image/gif,image/png]|max_size[gambar_rumah,2048]',
        ];
        $msg = [
            'gambar_rumah' => [
                'uploaded' => 'Harus Ada File yang diupload',
                'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
                'max_size' => 'Ukuran File Maksimal 2 MB'
            ]
        ];

        if (!$this->validate($rules, $msg)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
            return $this->respond($response, 500);

        } else {
			
            $price = $temp_produk->price;
            if ($temp_produk->price == "0") {
                $temp_price = $mdl->getWhere('product_price', ['id' => $input['spek']])->getRow();
                if (!$temp_price) {
                    $response = [
                        'status' => 500,
                        'error' => true,
                        'message' => 'pastikan inputan spek benar',
                        'data' => []
                    ];
                    return $this->respond($response, 500);
                }
                $price = $temp_price->product_price;
            }
			$total = $price * $input['luas'];
			if($input['kode_promo'] != null && $input['kode_promo'] != ""){
				$temp_promo = $mdl->getWhere('promomobile', ['promoCode' => $input['kode_promo']])->getRow();
				if(!empty($temp_promo)){
					$promo = $total * $temp_promo->promo / 100;
					$total = $total - $promo;
				}
			}

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

			$temp = 0;
            $cek_area = $mdl->getAll('area')->getResult();
            $area = "";
            foreach ($cek_area as $c) {
				if(strpos(strtolower($input['city']), strtolower('bks')) > -1){
					$input['city'] = "Bekasi";
				}
				if (strpos(strtolower($input['city']), strtolower($c->nama_area)) > -1) {
					$temp = 1;
					$area = $c->id_area;
				}			
               
            }
            if($area == ""){
                $response = [
                    'status' => 200,
                    'error' => true,
                    'message' => "Transaksi Gagal, Area anda belum kami jangkau !",
                    'data' => []
                ];
                return $this->respond($response, 200);
            }
            $insert = [
                'project_number' => date("dmY", time()) . "" . $ht,
                'type' => $type,
                'total' => $total,
                'alamat_pengerjaan' => $input['alamat'],
                'catatan_alamat' => $input['alamat'],
                'luas' => $input['luas'],
                'description' => $input['deskripsi'],
                'latitude' => $input['latitude'],
                'longitude' => $input['longitude'],
                'metode_payment' => $input['metode_pembayaran'],
                'status_project' => 'quotation',
                'id_area' => $area,
				'kode_promo' => $input['kode_promo'],
				'kode_referal' =>  $input['kode_referal'],
                'created' => time(),
                'device' => 2
            ];
           
            $insert_data = [
                'name' => $input['nama_lengkap'],
                'address' => $input['alamat'],
                'email' => $input['email'],
                'phone' => $input['telepon'],
                'created' => time(),
                'member_id' => $id
            ];

            $insert_detail = [
                'product_id' => $input['type'],
                'product_price_id' => $input['spek'],
            ];

            $query = $mdl->ins('projects', $insert);
            if ($query) {

                $insert_data['project_id'] = $id_projek;
                $query_data = $mdl->ins('project_data_customer', $insert_data);

                $insert_detail['project_id'] = $id_projek;

                if ($uri->getSegment(4) == "desain") {
                    // $file_denah = $this->request->getFile('denah');
                    $insert_design = [
                        'tipe_rumah' => $input['tipe_rumah'],
                        'created' => time()
                    ];
                    $idd = $mdl->lastId('projects_desain')->getRow()->id;
                    $mdl->ins('projects_desain', $insert_design);
                    $insert_detail['desain_id'] = (int)$idd + 1;
                }

                $query_detail = $mdl->ins('projects_detail', $insert_detail);

                /* start upload */
                $path = "./public/images/projek";
                $uploadImg = $this->uploadImage($file_rumah, $path);

                if ($uploadImg != null) {
                    $path_image = $path;
                    $json_text = $uploadImg['message'];
                    $mdl->upd('projects', ['id' => $id_projek], ['image_upload' => $uploadImg['data']['file_name']]);
                } else {
                    $path_image = '';
                    $json_text = $uploadImg['message'];
                }
                /* end upload */
                // $product_id = array('id' => $input['product_id']);
                // $getProduct = $this->general_model->getfieldById('paket_name', 'product', $product_id);

                // $return = array(
                //     $query, $insert['project_number'], $getProduct->paket_name, $insert['luas'], number_format($insert['total']), $insert['metode_payment'], $insert_data['name'], $insert_data['phone'], date("d M Y", $insert_data['created']), $insert_data['email'], $insert['marketing_name'], $this->access['action'], $insert['catatan_alamat']
                // );
                $return = [
                    'project'              =>    $insert,
                    'data_customer'        =>    $insert_data,
                    'detail_projek_desain' =>    $insert_design,
                    'detail_projek'        =>    $insert_detail
                ];
                $json_status = 1;
            } else {
                $json_status = 0;
            }

			// send email
			if ($status_cust == 1) {
				$emailData = array(
					'from' => 'notifikasi@mitrarenov.com',
					'name' => 'notifikasi@mitrarenov.com',
					'to' => $input['email'],
					'bcc' => "",
					'subject' => "Permintaan Jasa di Mitrarenov.com"  
				);
			} else {
				$emailData = array(
					'from' => 'notifikasi@mitrarenov.com',
					'name' => 'notifikasi@mitrarenov.com',
					'to' => $input['email'],
					'bcc' => "",
					'subject' => "Informasi Akun & Permintaan Jasa di Mitrarenov.com"             
				);
			}
			$data2 = array(
				'template' => $temp,
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

			$this->sendEmailAktifasi($emailData, $status_cust);
			$decode_tukang = $mdl->findAllTukang($area);
			
			$subject_tukang = "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'];
			foreach ($decode_tukang as $key => $d2) {
				if ($d2->email_tukang !== null or $d2->email_tukang !== '') {

					$email = \Config\Services::email();
					$email->setFrom('notifikasi@mitrarenov.com', 'notifikasi@mitrarenov.com');
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
			$notif = [
				'member_id' =>  $id,
				'kategori' => 'transaction',
				'id_kategori' => $id_projek,
				'message' => 'Permintaan Jasa anda telah dikirim !',
				'date' => time(),
				'status' => 0
			];
			$member = $mdl->getWhere('token_login', ['member_id' => $id], null)->getRow();
			if (!empty($member->fcm_id)) {
				$this->send_notif("Permintaan Jasa anda telah dikirim !", "Permintaan Jasa anda telah dikirim ! Mohon tunggu konfrimasi dari admin kami!", $member->fcm_id, array('title' => "Pesanan anda telah dikirim", 'message' => "Permintaan Jasa anda telah dikirim ! Mohon tunggu konfrimasi dari admin kami!", 'tipe' => 'transaksi', 'content' => array("id_projek" => $id_projek)));
			}
            $res = array(
                'status' => $json_status,
				'error' => false,
                'message' => $json_text,
                'data' => $return,
                'type' => $type
            );
            return $this->respond($res, 200);
        }

    }

    public function uploadImage($file, $path)
    {
        helper(['form']);
        $date = date('Y-m-d');
        $profile_image = $file->getName();
        $nameNew = $date . substr($profile_image, 0, 5) . rand(0, 20).'.'.$file->guessExtension();
        // var_dump($newfilename);die;
        if (!$file->isValid()) {
            return $this->fail($file->getErrorString());
        }

        if ($file->move($path, $nameNew)) {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'File uploaded successfully',
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

    public function checkout()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $data = $mdl->getWhere('cart', ['id_user' => $id])->getResult();

        $total = 0;

        foreach ($data as $d) {
            $total += $d->total;
        }

        if ($mdl->del('cart', ['id_user' => $id])) {
            return $this->respond('Berhasil Checkout', 200);
        }
    }

    public function payment()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $input = $this->request->getVar();
        
        $addition = 0;
        $deduction = 0;
        $harga = 0;
        $tracking_id = $input['no_invoice'];
        $projek_id = $input['id_projek'];
        $orderid = $input['orderid'];
		// $cek = $mdl->getWhere('projects_transaction', ['id_pembayaran' => $htrans->id], null, 'id', 'desc')->getRow();
		$cek = $mdl->getWhere('projects_transaction', ['transaction_id' => $orderid], null, 'id', 'desc')->getRow();
		$htrans = $mdl->getWhere('projects_pembayaran', ['id' => $cek->id_pembayaran,], null)->getRow();

		if($cek == null){
			$data_trans_ins = array(
				'id_pembayaran' => $htrans->id, 
				'project_id' => $htrans->project_id,
				'biaya' => $htrans->biaya,
				'transaction_id' => $htrans->project_id . '' . time(),
				'tanggal_dibuat' => time(),
				'status' => 'belum dibayar',
			);
			$mdl->insB('projects_transaction', $data_trans_ins);
			$trans = $mdl->getWhere('projects_transaction', ['id_pembayaran' => $htrans->id], null,'id', 'desc')->getRow();
			
		}else{
			$htrans = $mdl->getWhere('projects_pembayaran', ['id' => $cek->id_pembayaran,], null)->getRow();
			$date_cek = $cek->tanggal_dibuat;
			$expired_cek = strtotime("+1 day", $cek->tanggal_dibuat);
			$due_date = strtotime($htrans->due_date);
			$now = time();
			
			if($cek->status == "expire" || $cek->status == "failure" || $cek->status == "cancel"){
				if($now >= $due_date){
					$data_trans_ins = array(
						'id_pembayaran' => $htrans->id, 
						'project_id' => $htrans->project_id,
						'biaya' => $htrans->biaya,
						'transaction_id' => $htrans->project_id . '' . time(),
						'tanggal_dibuat' => time(),
						'status' => 'belum dibayar',
					);
					$mdl->insB('projects_transaction', $data_trans_ins);
					$cek = $mdl->getWhere('projects_transaction', ['id_pembayaran' => $htrans->id], null, 'id', 'desc')->getRow();
				}
			}
			$trans = $cek;
		}		
		
        $projek = $mdl->getWhere('projects', ['id' => $htrans->project_id], null)->getRow();
        $dtrans = $mdl->getWhere('projects_detail', ['project_id' => $htrans->project_id], null)->getRow();
        $produk = $mdl->getWhere('product', ['id' => $dtrans->product_id], null)->getRow();
        $member = $mdl->getWhere('project_data_customer', ['project_id' => $htrans->project_id], null)->getRow();
        
        // $track = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(24 / strlen($x)))), 1, 24);
        
        $totalpayment = 0;
        $totalqty = 0;
        
        if($projek->luas == null){
            $projek->luas = 1;
        }
        // if ($dtrans->product_price_id != 0) {
        //     $cek_harga = $mdl->getWhere('product_price', ['id' => $dtrans->product_price_id], null)->getRow();
        //     $harga += $cek_harga->product_price;
        //     $totalpayment = $harga * $projek->luas;
        // }else{
        //     $harga += $produk->price;
        //     $totalpayment = $harga * $projek->luas;
        // }
        $harga = str_replace(".","",$htrans->biaya);
		
        $cekpromo = $mdl->getWhere('promomobile', ['promoCode' => $projek->kode_promo], null)->getRow();
        
        if ($cekpromo != null ) {
            $temp_promo = $totalpayment * $cekpromo->promo / 100;
            $deduction += $temp_promo;
        }
        $totalpayment = $harga + $addition - $deduction;
        
        $transaction_details = array(
            'order_id' => $trans->transaction_id,
            'gross_amount' => $totalpayment, // no decimal allowed for creditcard
        );

        // // Optional
        // $item1_details = array(
        //     'id' => 'a1',
        //     'price' => $harga,
        //     'quantity' => $projek->luas,
        //     'name' => $produk->paket_name
        // );

        $item1_details = array(
            'id' => 'a1',
            'price' => $harga,
            'quantity' => 1,
            'name' => $produk->paket_name
        );
        
        $item_details = array ($item1_details);

        // // Optional
        $mail = str_replace(" ", "", $member->email);
        $customer_details = array(
            'first_name'    => "".$member->name,
            'last_name'     => " ",
            'email'         => "$mail",
            'phone'         => "".$member->phone,
        );
        // var_dump($customer_details);die;
        // // Optional, remove this to display all available payment methods
        $enable_payments = array('gopay','bank_transfer');
        // $enable_payments = array('credit_card','cimb_clicks','mandiri_clickpay','echannel');

        // Fill transaction details
        $transaction = array(
            'enabled_payments' => $enable_payments,
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );

        // echo "<pre>";echo print_r($snaptoken); echo "</pre>";die;
		if(strtotime($cek->expired_date) < time()){
			if($cek->reference_no != null){
				$snaptoken = $cek->reference_no;
			}else{
				$Midtranspayment = new Midtranspayment();
				$snaptoken = $Midtranspayment->get_token($enable_payments,$transaction_details,$customer_details,$item_details);
			}
			$update = [
				"token_midtrans" => $snaptoken
			];
			$updated = [
				"reference_no" => $snaptoken
			];
			$link = "https://app.midtrans.com/snap/v2/vtweb/" . $snaptoken;
			$mdl->upd('projects', ['id' => $htrans->project_id], $update);
			$mdl->upd('projects_transaction', ['id' => $cek->id], $updated);
			$msg = array(
				'status' => 200,
				'message' => 'Token Ditemukan ',
				'data' => [
					"order_id" => $tracking_id,
					"total_bayar" => $totalpayment,
					"name" => "$member->name",
					"email" => "$member->email",
					"phone" => "$member->phone",
					"token" => $snaptoken,
					'url' => $link,
				]
			);
		}else{
			$snaptoken = $cek->reference_no;
			$msg = array(
				'status' => 500,
				'message' => 'Expired Payment !',
				'data' => [
					"order_id" => $tracking_id,
					"total_bayar" => $totalpayment,
					"name" => "$member->name",
					"email" => "$member->email",
					"phone" => "$member->phone",
					"token" => $snaptoken,
				],				
			);
		}
         return $this->respond($msg, 200);
    }

    public function paymentstatus()
	{
		$m = new GeneralModel();
		$Midtranspayment = new Midtranspayment();
		$notif = $Midtranspayment->notification();
		//$notif = file_get_contents('php://input');
		$now = date('Y-m-d');

		$transactionstatus = $notif->transaction_status;
		$orderid = $notif->order_id;
		$billercode = "";
		$payment_type = $notif->payment_type;
		if ($payment_type == "gopay") {
			$bank = "gopay";
			$vanumber = "";
			$billercode = "";
		} else if ($payment_type == "bank_transfer") {
			if (!empty($notif->va_numbers[0])) {
				$va = $notif->va_numbers[0];
				$vanumber = $va->va_number;
				$bank = $va->bank;
				$billercode = "";
			} else if (!empty($notif->permata_va_number)) {
				$vanumber = $notif->permata_va_number;
				$bank = "permata";
				$billercode = "";
			}
		} else if ($payment_type == "echannel") {
			$billercode = $notif->biller_code;
			$vanumber = $notif->bill_key;
			$bank = "mandiri";
		} else if ($payment_type == "qris") {
			$qris = $notif->qris;
			$bank = $qris->acquirer;
			$vanumber = "";
			$billercode = "";
		} else if ($payment_type == "shopeepay") {
			$bank = "shopeepay";
			$billercode = "";
			$vanumber = "";
		} else if ($payment_type == "cstore") {
			$cstore = $notif->cstore;
			$bank = $cstore->store;
			$vanumber = "";
			$billercode = "";
		}

		// $htrans = T_htransactions::findOne($params);
		$htrans = $m->getWhere('projects_transaction', ['transaction_id' => $orderid], null)->getRow();
		$dtrans = $m->getWhere('projects_pembayaran', ['id' => $htrans->id_pembayaran], null)->getRow();
		$projek = $m->getWhere('project_data_customer', ['project_id' => $htrans->project_id], null)->getRow();
		if ($htrans) {
			// $member = M_members::find($projek->member_id);
			$member = $m->getWhere('token_login', ['member_id' => $projek->member_id], null)->getRow();
			// $dtrans = $md->dtrans($projek->project_id)->getRow();

			if ($transactionstatus == "capture" || $transactionstatus == "settlement") {
				$update = [
					"status" => $transactionstatus,
				];
				$update_pembayaran = [
					"status" => 'sudah dibayar',
					"status_midtrans" => $transactionstatus,
				];
				$notif = [
					'member_id' => $projek->member_id,
					'kategori' => 'transaction',
					'id_kategori' => $dtrans->nomor_invoice,
					'message' => 'Pesanan anda telah diverifikasi',
					'date' => time(),
					'status' => 0
				];
				$m->ins('notifikasi', $notif);
				$m->upd("projects_transaction", ["id" => $htrans->id], $update);
				$m->upd("projects_pembayaran", ["id" => $htrans->id_pembayaran], $update_pembayaran);
				// $this->send_email($member, $htrans, $dtrans, "success");
				if (!empty($member->fcm_id)) {
					$this->send_notif("Pesanan anda telah diverifikasi", "Transaksi anda " . $htrans->transaction_id . " telah diverifikasi", $member->fcm_id, array('title' => "Pesanan anda telah diverifikasi", 'message' => "Transaksi anda " . $htrans->transaction_id . " telah diverifikasi", 'tipe' => 'detail_transaksi', 'content' => array("id_transaksi" => $htrans->id, "order_id" => $htrans->transaction_id)));
				}
			} else if ($transactionstatus == "pending") {
				$notif = [
					'member_id' => $projek->member_id,
					'kategori' => 'transaction',
					'id_kategori' => $dtrans->nomor_invoice,
					'message' => 'Pesanan anda belum di bayar, Silahkan lakukan pembayaran',
					'date' => time(),
					'status' => 0
				];
				$m->ins('notifikasi', $notif);
				$update = [
					"status" => $transactionstatus,
				];
				$update_pembayaran = [
					"status" => 'belum dibayar',
					"status_midtrans" => $transactionstatus,
				];
				$m->upd("projects_transaction", ["id" => $htrans->id], $update);
				$m->upd("projects_pembayaran", ["id" => $htrans->id_pembayaran], $update_pembayaran);
			} else if ($transactionstatus == "deny" || $transactionstatus == "cancel" || $transactionstatus == "expire" || $transactionstatus == "refund") {
				if(strtotime($dtrans->due_date) < strtotime($now)){   
					$update_pembayaran = [
						"status" => 'expired',
						"status_midtrans" => $transactionstatus,
					];
					$update_midtrans = [
						"reference_no" => NULL,
						"status" => 'expired',
					];
				}else{
					$update_pembayaran = [
						"status" => 'belum dibayar',
						"status_midtrans" => $transactionstatus,
					];
					$update_midtrans = [
						"reference_no" => NULL,
						"status" => $transactionstatus,
					];
				}				
				
				$m->upd("projects_pembayaran", ["id" => $htrans->id_pembayaran], $update_pembayaran);				
				$m->upd("projects_transaction", ["id" => $htrans->id], $update_midtrans);
				
				// $this->send_email($member, $htrans, $dtrans, "cancel");
				if (!empty($member->fcm_id)) {
					$this->send_notif("Pesanan anda telah diverifikasi", "Transaksi anda " . $htrans->transaction_id . " telah diverifikasi", $member->fcm_id, array('title' => "Pesanan anda telah diverifikasi", 'message' => "Transaksi anda " . $htrans->transaction_id . " telah diverifikasi", 'tipe' => 'detail_transaksi', 'content' => array("id_transaksi" => $htrans->id, "order_id" => $htrans->transaction_id)));
				}
				if ($transactionstatus == "refund") {
					// $this->send_email($member, $htrans, $dtrans, $transactionstatus);
					if (!empty($member->fcm_id)) {
						$this->send_notif("Dana dikembalikan", "Dana transaksi anda " . $htrans->transaction_id . " telah dikembalikan ", $member->fcm_id, array('title' => "Dana dikemblikan", 'message' => "Dana transaksi anda " . $htrans->transaction_id . " telah dikembalikan", 'tipe' => 'detail_transaksi', 'content' => array("id_transaksi" => $htrans->id, "order_id" => $htrans->transaction_id)));
					}
					$notif = [
						'member_id' => $projek->member_id,
						'kategori' => 'transaction',
						'id_kategori' => $dtrans->nomor_invoice,
						'message' => 'Dana anda dikembalikan',
						'date' => time(),
						'status' => 0
					];
				} else {
					$notif = [
						'member_id' => $projek->member_id,
						'kategori' => 'transaction',
						'id_kategori' => $dtrans->nomor_invoice,
						'message' => 'Pesanan anda dibatalkan',
						'date' => time(),
						'status' => 0
					];
					if (!empty($member->fcm_id)) {
						$this->send_notif("Pesanan anda dibatalakan", "Transaksi anda " . $htrans->transaction_id . " telah dibatalakan", $member->fcm_id, array('title' => "Pesanan anda dibatalkan", 'message' => "Transaksi anda " . $htrans->transaction_id . " telah dibatalkan", 'tipe' => 'detail_transaksi', 'content' => array("id_transaksi" => $htrans->id, "order_id" => $htrans->transaction_id)));
					}
				}
				$m->ins('notifikasi', $notif);
			}
		}

		$result = [
			"status" => 1,
			"message" => "payment status $transactionstatus ",
			"data" => [
				"payment_status" => $transactionstatus
			]
		];
		$this->respond($result);
	}	

    public function irisnotif()
    {
        $md = new GeneralModel();
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result, 'true');
        $ins = array(
            'reference_no' => $result['reference_no'], 
            'amount' => $result['amount'], 
            'status' => $result['status'], 
            'updated_at' => strtotime($result['updated_at']), 
        );
        $md->insb('history_midtrans', $ins);
		$ho = $md->getWhere('pengajuan_ho', ['no_refrens' => $result['reference_no']])->getRow();
		$finance = $md->getWhere('pengajuan_finance', ['no_refrens' => $result['reference_no']])->getRow();

		if ($result['status'] == "completed") {
			$status = 1;
		} elseif ($result['status'] == "rejected") {
			$status = 3;
		} elseif ($result['status'] == "queued") {
			$status = 0;
		}
		
		$update = [
			'status' => $status
		];

		if($ho != null){			
			$md->upd("pengajuan_ho", ["no_refrens " => $result['reference_no']], $update);
		}

		if($finance != null){
			$md->upd("pengajuan_finance", ["no_refrens " => $result['reference_no']], $update);
		}
    }

	public function send_notif($title, $desc, $id_fcm, $data)
	{
		$Msg = array(
			'body' => $desc,
			'title' => $title
		);

		$fcmFields = array(
			'to' => $id_fcm,
			'notification' => $Msg,
			'data' => $data
		);
		$headers = array(
			'Authorization: key='. "AAAADRZL39M:APA91bHmsDvyG920nTieIsEKJCMG7cmh1qBxd7Lecjp8hmZL39vIjQpCtT1qr-RtifnyrKgW2L02xsslHbgvdRIOzpl8Oixj3l4kFyK-3WL67vsIJEb9QJjBiyjND5tJCzTGuqN-LW3C",
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
		$result = curl_exec($ch);
		curl_close($ch);

		$cek_respon = explode(',', $result);
		$berhasil = substr($cek_respon[1], strpos($cek_respon[1], ':') + 1);
		//echo $result."\n\n";
	}

    public function tambah_log_email_db($tipe, $email, $role, $status)
	{
        $mdl = new GeneralModel();
		$inserts['tipe'] = $tipe;
		$inserts['email'] = $email;
		$inserts['role'] = $role;
		$inserts['status'] = $status;
		$query = $mdl->insB('log_email', $inserts);
	}

    public function sendEmailAktifasi($emailData = array(), $tipe)
	{
        $mdl = new GeneralModel();
		$temp = $mdl->getWhere('email_ebook', array('id' => '1'))->getResult();
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

    private function content($data2)
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
																				Dear Bapak/Ibu terdapat project dengan detail sebagai berikut: <br /> <br />
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

	private function jasaType($product_id = "") {
		$jasa = "";
		if($product_id == 1){
			$jasa = "Membangun Rumah";
		}else if($product_id == 2){
			$jasa = "Membuat Dak";
		}else if($product_id == 3){
			$jasa = "Menambah Ruangan";
		}else if($product_id == 4){
			$jasa = "Meningkat Rumah";
		}else if($product_id == 5){
			$jasa = "Perbaikan Genteng";
		}else if($product_id == 6){
			$jasa = "Pengecatan";
		}else if($product_id == 7){
			$jasa = "Membuat Carport";
		}else if($product_id == 8){
			$jasa = "Membangun Rumah Kost";
		}else if($product_id == 9){
			$jasa = "Membangun Ruko";
		}else if($product_id == 10){
			$jasa = "Pembuatan Kolam Renang";
		}else if($product_id == 11){
			$jasa = "Pekerjaan Interior";
		}else{
			$jasa = "Pekerjaan Lain";
		}
		
		return $jasa;
	}

}
