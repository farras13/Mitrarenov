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
            $data['notif'] = $temp;
            $data['notif_total'] = $no;
            $data['chat_total'] = $chat;
        }
        $data['data'] = $this->model->getWhere('page_website', ['url_page' => $Id])->getRow(); 
        if(empty($data['data'])){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('dynamic-page', $data);
    }

    public function snk()
    {
        $sess = session();
        $idn = $sess->get('user_id');
        
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
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
            $data['notif'] = $temp;
            $data['notif_total'] = $no;
            $data['chat_total'] = $chat;
        }
        $data['tentang'] = $this->model->getWhere('footer', ['id' => 4])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('snk', $data);
    }

    public function qa()
    {
        $sess = session();
        $model = new GeneralModel();
        $idn = $sess->get('user_id');
        
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
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
            $data['notif'] = $temp;
            $data['notif_total'] = $no;
            $data['chat_total'] = $chat;
        }
        $data['tentang'] = $model->getWhere('footer', ['id' => 7])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('qa', $data);
    }

    public function tentang_mitra()
    {
        $sess = session();
        $model = new GeneralModel();
        $idn = $sess->get('user_id');
        
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
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
            $data['notif'] = $temp;
            $data['notif_total'] = $no;
            $data['chat_total'] = $chat;
        }
        $data['tentang'] = $model->getWhere('footer', ['id' => 1])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('tentang-mitra', $data);
    }
}