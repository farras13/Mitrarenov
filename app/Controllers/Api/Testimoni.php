<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;
    
class Testimoni extends ResourceController
{
    
    use RequestTrait;
    use ResponseTrait;
    protected $modelName = 'App\Models\TestimoniModel';
    protected $format    = 'json';
    
    public function index()
    {
        $data = $this->model->findAll();
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
        $url = base_url();
        $path = $url.'/public/images/testimoni/';
        for ($i=0; $i < count($data); $i++) { 
            $list[$i] = array(
                'name' => $data[$i]['name'] , 
                'company' => strip_tags($data[$i]['company']) , 
                'testimoni' => $data[$i]['testimoni'] , 
                'slug' => $data[$i]['slug'] , 
                'image' => $path.''.$data[$i]['image'] , 
                'is_publish' => $data[$i]['is_publish'] , 
                'created' => $data[$i]['created'] , 
                'modified' => $data[$i]['modified'] , 
             );
        }

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $list
        ];
        return $this->respond($res, 200);
    }
}
