<?php

namespace App\Controllers\Api;

use App\Models\AuthDetailModel;
use App\Models\DboModel;
use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class ProjectController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;

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

    public function index()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        $url = base_url();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id = (int)$cekUser->member_id;
        
        $key = $this->request->getGet();
      
        if (array_key_exists("limit", $key) && array_key_exists("status", $key)) {
            $limit  = (int) $key['limit'];
            $status  = $key['status'];
            $data = $models->getProjectUser($id, $limit, $status);
        }else if(array_key_exists("status", $key)){
            $status  = $key['status'];
            $data = $models->getProjectUser($id, null, $status);
        }else if(array_key_exists("limit", $key)){
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUser($id, $limit, null);
        }
        else{
            $data = $models->getProjectUser($id);
        }

        if (!$data) {
            return $this->failNotFound('data tidak ditemukan! Belum pernah melakukan transaksi.');
        }
        $path = "/public/images/projek/";   
         
        foreach ($data as $p) {
           if($p->image_upload == null){
                $p->image_upload = $url.'/public/images/no-picture/no_logo.png';
           }else{
                $p->image_upload = $url.$path.$p->image_upload;
           }          
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function projekBerjalan()
    {
        
    }

    public function listProgresImage($id)
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();
        $id_user = (int)$cekUser->member_id;

        $path_dokumen = "https://admin.mitrarenov.soldig.co.id/assets/main/berkas/";
        $data = $model->getWhere('projects_update', ['project_id' => $id])->getResult();
        if (!$data) {
            return $this->failNotFound('data tidak ditemukan!');         
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function detail($id)
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        $url = base_url();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id_user = (int)$cekUser->member_id;
          
        $data = $models->getProjectUserD($id);
        $berkas = $models->getProjectUser($id_user, null, 'project');
        $path_dokumen = "https://admin.mitrarenov.soldig.co.id/assets/main/berkas/";
        $data['berkas'] = $path_dokumen.$berkas[0]->dokumen;
        $data['berkas_rab'] = $path_dokumen.$berkas[0]->dokumen_rab;
        $data['image_default'] = $url."public/main/images/gambar-blum-update.png";

        if (!$data) {
            return $this->failNotFound('data tidak ditemukan! Belum pernah melakukan transaksi.');
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function spek()
    {
        $models = new GeneralModel();
        $key = $this->request->getGet();
       
        $produk =  $models->getWhere('product', ['id' => $key['id']])->getRow();
        if($key != null){
            $spek =  $models->getWhere('product_price', ['product_id' => $produk->id])->getResult();
            if($spek == null){
                $spek =  $models->getWhere('product_price', ['product_id' => $produk->category_id])->getResult();
                if ($spek == null) {
                    $spek =  $models->getWhere('product', ['paket_name' => $key['name']])->getResult();
                    foreach ($spek as $key => $value) {
                        $value->type_price = $value->paket_name;
                        $value->product_price = $value->start_from_text;
                        $value->spesifikasi = $value->spesifikasi_renov;
                    }
                }
            }
            // $data = $models->getWhere('product_price', ['id' => $key['id']])->getRow();
            $data = $spek;
        }else{
            $data = $models->getAll('product_price')->getResult();
        }

        $res = [
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function type_order()
    {
        $models = new GeneralModel();

        $data = $models->getAll('type_order')->getResult();

        $res = [
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function detailInvoice()
    {
        $models = new DboModel();
        $key = $this->request->getVar();

        $data = $models->detailInvoice($key->no_invoice);

        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function projectDone()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        
        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();
        
        $id = (int)$cekUser->member_id;

        $data = $models->getProjectUserS($id, 'done');
        $user = $model->getwhere('project_data_customer', ['member_id' => $id])->getResult();

        $key = $this->request->getGet();

        if (array_key_exists("limit", $key)) {
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUserD($id, 'done', $limit);
        }

        $res = [
            'X-Auth-Token' => $token,
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function projectProgres()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id = (int)$cekUser->member_id;
        $path = "./public/images/desain_rumah_user";
        $path1 = "./public/images/projek";
        $data = $models->getProjectUserS($id, 'project');
        // var_dump($data);die;

        $user = $model->getwhere('project_data_customer', ['member_id' => $id])->getResult();

        $key = $this->request->getGet();

        if (array_key_exists("limit", $key)) {
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUserS($id, 'project', $limit);
        }

        $res = [
            'X-Auth-Token' => $token,
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }
}
