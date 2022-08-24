<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class Cctv extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    
    public function configtuya()
    {
        $config =
        [
            'accessKey' 	=> 'kjk8nwr83x3typ9v4edg' ,
            'secretKey' 	=> '604f14060b5e457dad9d411ed58ed6d9' ,
            'baseUrl'	=> 'https://openapi.tuyaus.com'
        ];
        $tuya = new \tuyapiphp\TuyaApi($config);
        return $tuya;
    }

    private function app_id()
    {
        $app_id = 'az1592366336242TcxIR'; 
        return $app_id;
    }

    public function getToken()
    {
        $tuya = $this->configtuya();
        $data = $tuya->token->get_new( );	
        $data = $tuya->token->get_refresh( $data->result->refresh_token );
        return $data;
    }

    public function accessToken()
    {   
        $res = $this->getToken();
        return $this->respond($res, 200);
    }

    public function getAlldevice()
    {   
        $data =  $this->request->getVar();
        $tuya = $this->configtuya();
        
        $token = $data['token'];  
        // $token = $tuya->token->get_new( )->result->access_token;
        // Get list of devices connected with android app
        $data = $tuya->devices( $token )->get_app_list( $this->app_id() );
        $res = $data;
        return $this->respond($res, 200);
    }

    public function getDevice()
    {
        $data =  $this->request->getVar();
        $tuya = $this->configtuya();  

        $device_id = $data['id'];
        $token = $data['token'];
        // $res = [$device_id, $token];
        $res = $tuya->devices( $token )->get_details( $device_id );
        return $this->respond($res, 200);
    }

    public function stream()
    {
        $data =  $this->request->getVar();
        $tuya = $this->configtuya();  
        $device_id = $data['id'];
        $token = $data['token'];
        // $res = [$device_id, $token];
        $res = $tuya->devices( $token )->post_stream_allocate( $this->app_id() , $device_id , [ 'type' => 'rtsp' ] );
        return $this->respond($res, 200);
    }    
    
}
