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
        $tuya = $this->configtuya();  
        $app_id = 'az1592366336242TcxIR'; 
        $token = $tuya->token->get_new( )->result->access_token;
        // Get list of devices connected with android app
        $data = $tuya->devices( $token )->get_app_list( $app_id );
        $res = $data;
        return $this->respond($res, 200);
        // Get device status
        // $tuya->devices( $token )->get_status( $device_id );

        // Set device name
        // $tuya->devices( $token )->put_name( $device_id , [ 'name' => 'FAN' ] );	
        // $device = $tuya->devices( $token )->post_commands( $device_id , [ 'commands' => [ $payload ] ] );
    }   
    
}
