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

        $data = $models->getAll('product_price')->getResult();

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

    // public function orderProjek()
    // {
    //     $mdl = new GeneralModel();
    //     $headers = $this->request->headers();
    //     $token = $headers['X-Auth-Token']->getValue();
    //     $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
    //     $id = (int)$cekUser->member_id;

    //     $input = $this->request->getVar();

    //     $type = $input['type'];
    //     $insert = [
    //         'project_number' => date("dmY", time()) . "" . rand(10, 100),
    //         'total' => $input['total'],
    //         'alamat_pengerjaan' => $input['alamat_pengerjaan'],
    //         'catatan_alamat' => $input['catatan_alamat'],
    //         'luas' => $input['luas'],
    //         'description' => $input['description'],
    //         'metode_payment' => $input['metode_payment'],
    //         'status_project' => 'quotation',
    //     ];

    //     $insert_data = [
    //         'name' => $input['name'],
    //         'address' => $input['address'],
    //         'email' => $input['email'],
    //         'phone' => $input['phone'],
    //         'created' => time(),
    //     ];

    //     $insert_detail = [
    //         'product_id' => $input['product_id'],
    //         'product_price_id' => $input['tipe_paket'],
    //     ];       

    //     $query = $this->general_model->insertTable('projects', $insert);

    //     if ($query) {

    //         $insert_data['project_id'] = $query;
    //         $query_data = $this->general_model->insertTable('project_data_customer', $insert_data);

    //         $insert_detail['project_id'] = $query;
    //         $query_detail = $this->general_model->insertTable('projects_detail', $insert_detail);

    //         /* start upload */
    //         $image =  $_FILES['image_upload']['name'];
    //         $uploadImageResultJson = json_decode($this->upload_image_project($image, $query), true);
    //         foreach ($uploadImageResultJson as $a => $value) {
    //             if ($a == 'status') {
    //                 $upload_status = $value;
    //             }
    //             if ($a == 'message') {
    //                 $upload_message = $value;
    //             }
    //             if ($a == 'path_image') {
    //                 $upload_path = $value;
    //             }
    //         }
    //         if ($upload_status != 0) {
    //             $path_image = $upload_path;
    //             $json_text = $upload_message;
    //         } else {
    //             $path_image = '';
    //             $json_text = $upload_message;
    //         }
    //         /* end upload */
    //         $product_id = array('id' => $input['product_id']);
    //         $getProduct = $this->general_model->getfieldById('paket_name', 'product', $product_id);

    //         $return = array(
    //             $query, $insert['project_number'], $getProduct->paket_name, $insert['luas'], number_format($insert['total']), $insert['metode_payment'], $insert_data['name'], $insert_data['phone'], date("d M Y", $insert_data['created']), $insert_data['email'], $insert['marketing_name'], $this->access['action'], $insert['catatan_alamat']
    //         );
    //         $json_status = 1;
    //     } else {
    //         $json_status = 0;
    //     }

    //     $res = array(
    //         'status' => $json_status,
    //         'message' => $json_text,
    //         'data' => $return,
    //         'type' => $type
    //     );

    //     return $this->respond($res,200);
    // }

    // public function uploadImage($id)
    // {
    //     $file = $this->request->getFile('image');
       
    //     $profile_image = $file->getName();

    //     // Renaming file before upload
    //     $temp = explode(".", $profile_image);
    //     $newfilename = round(microtime(true)) . '.' . end($temp);

    //     if ($file->move("images/image_foder/", $newfilename)) {

    //         $data = [
    //             "file_name" => $newfilename,
    //             "file_path" => "images/members/" . $newfilename
    //         ];

    //         $fileModel = new AuthDetailModel();

    //         if ($fileModel->update($id, $data)) {

    //             $response = [
    //                 'status' => 200,
    //                 'error' => false,
    //                 'message' => 'File uploaded successfully',
    //                 'data' => []
    //             ];
    //         } else {

    //             $response = [
    //                 'status' => 500,
    //                 'error' => true,
    //                 'message' => 'Failed to save image',
    //                 'data' => []
    //             ];
    //         }
    //     } else {

    //         $response = [
    //             'status' => 500,
    //             'error' => true,
    //             'message' => 'Failed to upload image',
    //             'data' => []
    //         ];
    //     }

    //     return $this->respondCreated($response);
    // }

    // public function upload_image_project($image, $query)
    // {
    //    return true;
    // }
}
