<?php

namespace App\Controllers\Api;

use App\Models\GeneralModel;
use App\Models\HomeModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

class Home extends ResourceController
{
    use ResponseTrait;
    use RequestTrait;

    public function KategoriJasa()
    {
        $headers = $this->request->headers();
        // $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $db = db_connect();
        // $data = $model->getWhere('category', ['id !=' => 3])->getResult();
        $data = $db->query("SELECT category.* FROM category join product on category.id = product.category_id where category.id != 3 group by category.id")->getResult();

        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            // $data = $model->getWhere('category', ['id !=' => 3], $limit)->getResult();
            $data = $db->query("SELECT category.* FROM category join product on category.id = product.category_id where category.id != 3 group by category.id LIMIT $limit")->getResult();
        }
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }

        $res = [
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function subKategori($id)
    {
        $model = new GeneralModel();
        $subdata = $model->getWhere('product', ['category_id' => $id])->getResult();
        
        if (!$subdata) {
            return $this->respondNoContent('data tidak ditemukan');
        }
        
        $url = base_url();
        foreach ($subdata as $key => $value) {
            $value->image_icon = 'https://office.mitrarenov.com/assets/main/images/product_icon/'.$value->image_icon;
        }
        $data = [
            "base_image" => 'https://office.mitrarenov.com/assets/main/images/product_icon/',
            "data" => $subdata
        ];

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data,
        ];

        return $this->respond($res, 200);

    }

    public function PromoAll()
    {
        $headers = $this->request->headers();
        // $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        
        $data = $model->getWhere('promomobile', ['is_publish' => 0, 'kategori' => 2])->getResult();
        
        $url = base_url();
        $base_image = 'https://office.mitrarenov.com/assets/main/images/promo/';
        
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $data = $model->getWhere('promomobile', ['is_publish' => 0], $limit)->getResult();
        }
       
        if (!$data) {
             $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }       
        foreach($data as $d){
            $date = new DateTime($d->expired);
            $d->expired = $date->format('F Y');
            if($d->image != null){
                $d->image = $base_image.$d->image;
                $d->imagecontent = $base_image.$d->imagecontent;
            }else{
                $d->image = $url.'/public/main/images/slider-1.jpg';
                $d->imagecontent = $url.'/public/main/images/slider-2.jpg';
            }
        }
        $res = [
            "status" => 200,
            "messages" => "sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

    public function showPromo($id = null)
    {
        $model = new GeneralModel();
        $w = array('id' => (int)$id, 'is_publish' => 0);
        $data = $model->getWhere('promomobile', $w)->getRow();
        
        if (!$data) {
           $res = [
                "status" => 200,
                "messages" => "data tidak ditemukan",
                "data" => null
            ];
            return $this->respond($res, 200);
        }  

        $url = base_url();
        $base_image = 'https://office.mitrarenov.com/assets/main/images/promo/';
        if($data->image != null){
            $data->image = $base_image.$data->image;
            $data->imagecontent = $base_image.$data->imagecontent;
        }else{
            $data->image = $url.'/public/main/images/slider-1.jpg';
            $data->imagecontent = $url.'/public/main/images/slider-2.jpg';
        } 
        
        $date = new DateTime($data->expired);
        $data->expired = $date->format('F Y');

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data,
        ];
       
        return $this->respond($res, 200);
    }

    public function cekPromo()
    {
        $model = new GeneralModel();
        
        $data = $model->getWhere('promomobile', ['is_publish' => 0, 'kategori' => 2])->getResult();
        
        $url = base_url();
        $base_image = 'https://office.mitrarenov.com/assets/main/images/promo/';
        
        $key = $this->request->getGet();
        $cari = $key['code'];
        
        $w = array('promoCode' => $cari, 'is_publish' => 0);
        $data = $model->getWhere('promomobile', $w)->getRow();
        
        if (!$data) {
           $res = [
                "status" => 200,
                "messages" => "data tidak ditemukan",
                "data" => null
            ];
            return $this->respond($res, 200);
        }  

        $url = base_url();
        $base_image = 'https://office.mitrarenov.com/assets/main/images/promo/';
        if($data->image != null){
            $data->image = $base_image.$data->image;
            $data->imagecontent = $base_image.$data->imagecontent;
        }else{
            $data->image = $url.'/public/main/images/slider-1.jpg';
            $data->imagecontent = $url.'/public/main/images/slider-2.jpg';
        } 
        
        $date = new DateTime($data->expired);
        $data->expired = $date->format('F Y');

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data,
        ];
       
    }

    public function updurl()
    {
        $model = new GeneralModel;
        $datas = $model->getAll('news')->getResult();
        foreach ($datas as $key => $value) {
            $model->upd('news', ['id' => $value->id], ['url' => $value->slug]);
        }
        $resp = $model->getAll('news')->getResult();
        return $this->respond($resp, 200);
    }
}
