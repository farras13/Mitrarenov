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
        $temp = $model->getQuery("SELECT id, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE id_member = $user->member_id  OR tipe = 'global' ORDER BY id desc")->getResult();
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $temp = $model->getQuery("SELECT id, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE id_member = $user->member_id  OR tipe = 'global' ORDER BY id desc LIMIT $limit")->getResult();
            for ($i=0; $i < $limit; $i++) { 
                $temp[0]->title = "Mitrarenov";       
            }
        }
        for($k = 0; $k < count($temp); $k++){
            $temp[0]->title = "Mitrarenov";       
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
    public function update($id = null)
    {
        //
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
