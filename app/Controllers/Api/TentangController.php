<?php

namespace App\Controllers\Api;

use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class TentangController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    protected $modelName = 'App\Models\TentangModel';
    protected $format    = 'json';
    
    public function QA()
    {
        $w = ['id' => 2];
        $data = $this->model->find($w);
        
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }   
            
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

    public function about()
    {
        $w = ['id' => 1];
      
        $data = $this->model->find($w);
      
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

    public function syarat()
    {
        $model= new GeneralModel();
        $data = $model->getAll('snk')->getResult();

        $key = $this->request->getGet();
    
        // if(array_key_exists("key",$key) ){
        //     $data = $model->like('pertanyaan', $key['key'], 'both')
        //                         ->orLike('jawaban', $key['key'], 'both')
        //                         ->findall();
        // }

        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

    public function snk_kpr()
    {
        $w = ['id' => 4];
        $data = $this->model->find($w);
        
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }   
            
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

    public function RulesOrder()
    {
        $model = new GeneralModel();
        $data = $model->getAll('rules')->getResult();
        if (!$data) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }

}
