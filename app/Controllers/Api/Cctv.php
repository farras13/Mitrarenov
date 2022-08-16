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

    public function accessToken()
    {
        $tuya = $this->configtuya();
        $data = $tuya->token->get_new( );	
        $data = $tuya->token->get_refresh( $data->result->refresh_token );
        $res = array('error' => false, 'token_tuya' => $data);
        return $this->respond($res, 200);
    }

    public function getAlldevice()
    {
        $tuya = $this->configtuya();
        // $device = $tuya->devices( $token )->post_commands( $device_id , [ 'commands' => [ $payload ] ] );
    }
    
    
}
