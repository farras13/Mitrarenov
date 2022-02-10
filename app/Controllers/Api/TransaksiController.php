<?php

namespace App\Controllers\Api;

use App\Models\GeneralModel;
use CodeIgniter\RESTful\ResourceController;

class TransaksiController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function tipe_rumah()
    {
        $mdl = new GeneralModel();
        $data = $mdl->getAll('tipe_rumah')->getResult();

        if (!$data) {
            return $this->fail('Gagal mendapatkan tipe rumah!');
        }

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        
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
        if(!$input){
            $this->respond('Pilih barang terlebih dahulu !', 500);
        }

        $idm = $input['id_material'];

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$idm' and item_category = 1")->getRow();

        if(!$cekMaterial){
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
      
        if(!$add){
           return $this->failResourceGone('gagal melakukan insert');
        }

        $res = [
            'message' => 'berhasil gan!',
            'data' => $data,
            'status' => 200,
        ];

        return $this->respond($res, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function index()
    {
        $mdl = new GeneralModel(); 
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $data = $mdl->getWhere('cart', ['id_user' => $id])->getResult();

        if(!$data){
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
        $cart = $mdl->getWhere('cart', ['id_material' => $input['id_material'], 'id_user' => $id ])->getRow();
        $qty += $cart->qty;

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$cart->id_material' and item_category = 1")->getRow();

        if(!$cekMaterial){
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

        if($mdl->upd('cart', ['id' => $cart->id], $data)){
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
        $cart = $mdl->getWhere('cart', ['id_material' => $input['id_material'], 'id_user' => $id ])->getRow();
        $qty = $cart->qty - 1;

        $db = db_connect();
        $cekMaterial = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$cart->id_material' and item_category = 1")->getRow();

        if(!$cekMaterial){
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

        if($mdl->upd('cart', ['id' => $cart->id], $data)){
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
        $id = (int)$cekUser->member_id;
        $temp_idp = $mdl->lastId('projects')->getRow();
        $id_projek = (int)$temp_idp->id + 1;
// var_dump($id_projek);die;
        $input = $this->request->getVar();
        $tdate = date('Y-m-d');
        $date = strtotime($tdate);
        $temp_produk = $mdl->getWhere('product', ['id' => $input['type']])->getRow();
        $type = $temp_produk->paket_name;

        $file_rumah = $this->request->getFile('gambar_rumah');
        $rules = [
            "deskripsi" => "required",
            "alamat" => "required",
            "jangka_waktu" => "required",
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
            if($temp_produk->price == "0"){
                $temp_price = $mdl->getWhere('product_price', ['id' => $input['spek']])->getRow();
                if(!$temp_price){
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
            // var_dump($price);die;
            $total = $price * $input['luas']; 
            $insert = [
                'project_number' => "PRJ" . date("dmY", time())."".rand(10,100),
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
                'created' => time()
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
                    $file_denah = $this->request->getFile('denah');
                    $rule = [
                        "tipe_rumah" => "required",
                        "ruang_keluarga" => "required",
                        "kamar_tidur" => "required",
                        "kamar_mandi" => "required",
                        "dapur" => "required",
                        "luas" => "required",
                        'denah' => 'uploaded[denah]|mime_in[denah,image/jpg,image/jpeg,image/gif,image/png]|max_size[denah,2048]',

                    ];
                    $msg['denah'] =  [
                            'uploaded' => 'Harus Ada File yang diupload',
                            'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
                            'max_size' => 'Ukuran File Maksimal 2 MB'
                    ];
                    if (!$this->validate($rule, $msg)) {

                        $response = [
                            'status' => 500,
                            'error' => true,
                            'message' => $this->validator->getErrors(),
                            'data' => []
                        ];
                        return $this->respond($response, 500);
                    }
                    $path = "./public/images/desain_rumah_user";
                    $uploadImgdenah = $this->uploadImage($file_denah, $path);
                    $insert_design = [
                        'tipe_rumah' => $input['tipe_rumah'],
                        'ruang_keluarga' => $input['ruang_keluarga'],
                        'kamar_tidur' => $input['kamar_tidur'],
                        'kamar_mandi' => $input['kamar_mandi'],
                        'dapur' => $input['dapur'],
                        'denah' => $uploadImgdenah['data']['file_name'],
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
        }
        $res = array(
            'status' => $json_status,
            'message' => $json_text,
            'data' => $return,
            'type' => $type
        );

        return $this->respond($res, 200);
    }

    public function uploadImage($file, $path)
    {
        helper(['form']);
        $date = date('Y-m-d');
        $profile_image = $file->getName();
        $nameNew = $date.substr($profile_image,0,5).rand(0,20);
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
                    "file_name" => $profile_image,
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

        foreach($data as $d){
            $total += $d->total;
        }

        if ($mdl->del('cart', ['id_user' => $id])) {
           return $this->respond('Berhasil Checkout', 200);
        }
    }

    
}
