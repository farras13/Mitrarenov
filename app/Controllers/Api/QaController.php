<?php

namespace App\Controllers\Api;

use App\Models\DboModel;
use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class QaController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    protected $modelName = 'App\Models\QaModel';
    protected $format    = 'json';
    
    public function index()
    {
        $data = $this->model->findAll();
        
        if (!$data) {
            return $this->failNotFound('data masih kosong');
        }

        $key = $this->request->getGet();
    
        if(array_key_exists("key",$key) ){
            $data = $this->model->like('pertanyaan', $key['key'], 'both')
                                ->orLike('jawaban', $key['key'], 'both')
                                ->findall();
        }

        $res = [
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function detail_chat()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekToken = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $cekUser = $mdl->getWhere('member', ['id' => $cekToken->member_id])->getRow();
        $group = $mdl->getWhere('usergroup',['id' => $cekUser->usergroup_id])->getRow();

        $input = $this->request->getVar();
        $url = base_url();

        $data_chat = $mdl->getWhere('chat', ['project_id' => $input['project_id']])->getResult();
        foreach ($data_chat as $key => $chat) {
            $chat->date = date('d F Y', $chat->date);
        }
        if (!$data_chat) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }

        foreach ($data_chat as $key => $value) {
            if($value->user == "customer"){
                $value->bolean_user = TRUE;
            }else{
                $value->bolean_user = FALSE;
            }
        }
        
        if($group->name == 'User'){ 
            $gn = 'Customer'; 
        }else{  
            $gn = $group->name; 
        }
        
        $data = [           
            'usergroup' => $gn,
            'base_image' => $url.''.'/public/images/chat/',
            'chat' => $data_chat
        ];
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];

        return $this->respond($res, 200);
        
    }

    public function listChat()
    {
        $mdl = new GeneralModel();
        $mdle = new DboModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekToken = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $cekUser = $mdl->getWhere('member', ['id' => $cekToken->member_id])->getRow();
        $group = $mdl->getWhere('usergroup',['id' => $cekUser->usergroup_id])->getRow();

        $input = $this->request->getVar();
        $url = base_url();

        $data_chat = $mdle->getListChat($group->name, $cekToken->member_id);
        
        if (!$data_chat) {
            $res = [
                "status" => 200,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }else{
            foreach ($data_chat as $key => $value) {
                $value->image_profile = "https://mitrarenov.soldig.co.id/public/main/images/logo-mitrarenov.png";
            }
        }
       
        if($group->name == 'User'){ 
            $gn = 'Customer'; 
        }else{  
            $gn = $group->name; 
        }
        
        $data = [           
            'usergroup' => $gn,
            'list_chat' => $data_chat
        ];
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];
        return $this->respond($res, 200);
    }
    
    public function store_chat()
    {
        $mdl = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekToken = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $cekUser = $mdl->getWhere('member', ['id' => $cekToken->member_id])->getRow();
        $group = $mdl->getWhere('usergroup',['id' => $cekUser->usergroup_id])->getRow();

        if($group->name == "User"){
            $user = "Customer";
        }elseif($group->name == "Tukang"){
            $user = "tukang";
        }else{
            $user = "admin";
        }
        
        $input = $this->request->getVar();
        $type = $input['type'];
        // var_dump($input);die;
        if($input['chat'] == ''){
            helper(['form']);

            $file =  $this->request->getFile('chat');
            

            if (!$file->isValid()) {
                return $this->fail($file->getErrorString());
            }
            $url = base_url();
            $profile_image = $file->getName();
            $path = './public/images/chat';
            
            if ($file->move($path)) {
                
                $data = [
                    'project_id' => $input['project_id'],
                    'user' => $user,
                    'message' => $url.''.'/public/images/chat/'.$profile_image,
                    'date' => time(),
                    'type' => 'image'
                ];
    
                $a = $mdl->ins('chat', $data);
                if (!$a) {
                    return $this->fail('Gagal upload Chat');
                }                
                $data['path'] = $url.''.'/public/images/chat/';
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'messages' => 'Failed to upload image',
                    'data' => []
                ];
                return $this->respond($response, 500);
            }
            $res = [
                'status' => 200,
                'messages' => 'Chat image berhasil di kirim',
                'data'  => $data,                
            ];
            return $this->respond($res, 200);

        }else{
            $data = [
                'project_id' => $input['project_id'],
                'user' => $user,
                'message' => $input['chat'],
                'date' => time(),
                'type' => 'text'
            ];

            $a = $mdl->ins('chat', $data);
            if (!$a) {
                return $this->fail('Gagal upload Chat');
            }
            $res = [
                'status' => 200,
                'messages' => 'Chat berhasil di kirim',
                'data'  => $data,                
            ];
            return $this->respond($res,200);
        }
    }
}
