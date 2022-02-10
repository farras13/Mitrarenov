<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

class ArtikelController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    protected $modelName = 'App\Models\ArtikelModel';
    protected $format    = 'json';
    
    public function index()
    {
        $db = db_connect();
        $data = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 1 ORDER BY a.created desc")->getResult();
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $data = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 1 ORDER BY a.created desc LIMIT $limit")->getResult();
        }

        if ($data == null) {
            $res = [
                "status" => TRUE,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
       
        $url = base_url();

        foreach($data as $d){
            $time = $d->created; $date = new DateTime("@$time");
            $d->image = $url.'/public/images/news/thumbs/'. $d->image;
            $d->date = $date->format('d M Y'); 
        }

        $temp = [
            "base_image" => $url.'/public/images/news/thumbs/',
            "artikel" => $data
        ];

        $res = [
            'status' => TRUE,
            'messages' => "Sukses",         
            'data' => $temp
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
        $db = db_connect();
        $data = $db->query("SELECT a.*, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.id = $id")->getRow();
        if ($data == null) {
            $res = [
                "status" => TRUE,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
        $url = base_url();
        $time = $data->created; $date = new DateTime("@$time");
        $data->image = $url.'/public/images/news/thumbs/'. $data->image;
        $data->date = $date->format('d M Y'); 
        $res = [
            "status" => TRUE,
            "messages" => "Sukses",
            'data' => $data
        ];
        return $this->respond($res, 200);
    }
}
