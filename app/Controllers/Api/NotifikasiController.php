<?php

namespace App\Controllers\Api;

use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class NotifikasiController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
      
        $user = $model->getWhere('token_login', ['token' => $token])->getRow();
        $temp = $model->getQuery("SELECT id, kategori as title, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $user->member_id  OR kategori = 'global' ORDER BY id desc")->getResult();
        $unread = $model->getQuery("SELECT count(id) as unread FROM notifikasi WHERE (member_id = $user->member_id  OR kategori = 'global') and status = 0  ORDER BY id desc")->getRow();
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $temp = $model->getQuery("SELECT id, kategori as title, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $user->member_id  OR kategori = 'global' ORDER BY id desc LIMIT $limit")->getResult();
            $unread = $model->getQuery("SELECT count(id) as unread FROM notifikasi WHERE (member_id = $user->member_id  OR kategori = 'global') and status = 0  ORDER BY id desc")->getRow();
        }
       
        $data = $temp;
        

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
            "messages" => "data ditemukan !",
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function updateNotif($id)
    {
        $model = new GeneralModel();
        $model->upd('notifikasi', ['id' => $id], ['status' => 1]);
        $res = [
            "status" => 200,
            "messages" => "Berhasil update status notif!",
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function updateAll()
    {
        $model = new GeneralModel();
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $user = $model->getWhere('token_login', ['token' => $token])->getRow();
        $model->upd('notifikasi', ['member_id' => $user->member_id], ['status' => 1]);
        $res = [
            "status" => 200,
            "messages" => "Berhasil tandai baca semua notif!",
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
