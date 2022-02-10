<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class MaterialController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    protected $modelName = 'App\Models\MaterialModel';
    protected $format    = 'json';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $db = db_connect();
        $data = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_category = 1 ORDER BY `dbo.mst_item`.`item_code` ASC")->getResult();
       
        if (!$data) {
            return $this->failNotFound('data masih kosong');
        }

        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
           
            $limit  = (int) $key['limit'];
            $data = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_category = 1 ORDER BY `dbo.mst_item`.`item_code` ASC LIMIT $limit")->getResult();
        }

        $res = [
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function show($id = null)
    {
        $db = db_connect();
        $data = $db->query("SELECT * FROM `dbo.mst_item` WHERE item_code = '$id' and item_category = 1")->getResult();
        // var_dump($data);die;
        $res = [
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
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
