<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\DboModel;
use App\Models\GeneralModel;


class Page extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
    }
    
    public function index($Id)
    {
        $sess = session();
        $id = $sess->get('user_id');
        if($id != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id ORDER BY id desc LIMIT $limit")->getResult();
            }
            // var_dump($temp);die;
            $no = 0;
            $chat = 0;
            foreach ($temp as $key => $value) {
                if($value->status == 0){
                    $no++;
                }
                if($value->status == 0 && $value->kategori == "chat"){
                    $chat++;
                }
            }
        }
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        $data['data'] = $this->model->getWhere('page_website', ['url_page' => $Id])->getRow(); 
        // var_dump($data);die;
        return view('dynamic-page', $data);
    }
}