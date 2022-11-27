<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\DboModel;
use App\Models\GeneralModel;


class Artikel extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
        $this->artikel = new ArtikelModel();
    }

    public function artikel()
    {
        $key = $this->request->getVar();
        if($key){
            if ($key['cari'] != null) {
                $data['hot'] = $this->artikel->orderBy('created', 'ASC')->search_hot($key['cari']);
                $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where(['is_publish' => '0', 'date <=' => time()])->like('title', $key['cari'])->orderBy('date', 'DESC')->paginate(5, 'page_berita');
            }else {
                $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where(['is_publish' => '0', 'date <=' => time()])->orderBy('date', 'DESC')->paginate(5, 'page_berita');
                $data['hot'] = $this->artikel->orderBy('created', 'ASC')->hot();
            } 
        }else {
            $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where(['is_publish' => '0', 'date <=' => time()])->orderBy('date', 'DESC')->paginate(5, 'page_berita');
            $data['hot'] = $this->artikel->orderBy('created', 'ASC')->hot();
        }
        $data['kategori'] = $this->model->getAll('news_category')->getResult();
        $data['pager'] = $this->artikel->pager;
        $data['key'] = empty($key['cari']) ? "" : $key['cari'];

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
        

        return view("artikel", $data);
    }

    public function kategori($name)
    {
        $key = $this->request->getVar();
        $kat = str_replace('-', ' ', $name);
        $kategori = $this->model->getWhere('news_category', ['category' => $kat])->getRow();

        if (!empty($key['cari'])) {
            $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->like('title', $key['cari'])->orderBy('date', 'DESC')->paginate(5, 'berita');
        } else {
            $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('news_category', $kategori->id_news_category)->where('is_publish', '0')->orderBy('date', 'DESC')->paginate(5, 'berita');
        }
        
        if (count($data['terbaru']) < 5) {
            $limit = 5 - count($data['terbaru']);
            $data['artikel'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->orderBy('id', 'random')->paginate($limit, 'otherberita');
        }
        
        $data['kategori'] =  $this->model->getAll('news_category')->getResult();
        $data['judul'] = $kat;
        $data['hot'] = $this->artikel->orderBy('created', 'ASC')->hot();
        $data['pager'] = $this->artikel->pager;
        if ($key) {
            if($key['cari']){
                $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->like('title', $key['cari'])->orderBy('date', 'DESC')->paginate(5, 'berita');
                $data['hot'] = $this->artikel->orderBy('created', 'ASC')->search_hot($key['cari']);
                $data['pager'] = $this->artikel->pager;
                $data['key'] = $key['cari'];
            }
        } 
        $data['key'] = $key;
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
            $chat = 0;
            $no = 0;
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
       
        return view("artikel-kategori", $data);
    }

    public function d_artikel($id)
    {
        $key = $this->request->getVar();
        // $data['kategori'] = $model->kategori();
        $data['kategori'] = $this->model->getAll('news_category')->getResult();
        $tagline = $this->artikel->where(['is_publish' => '0', 'date <=' => time(), 'slug' => $id])->get()->getRow();
        if(empty($tagline)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

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

        $key = $this->request->getGet();
        $data['terkait'] = $this->artikel->terkait($tagline->tagline);
        $data['berita'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->find($tagline->id);
        $data['hot'] = $this->artikel->orderBy('created', 'ASC')->hot();
        if ($key) {
            if($key['cari']){
                $data['terbaru'] = $this->artikel->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
                $data['hot'] = $this->artikel->orderBy('created', 'ASC')->search_hot($key['cari']);
                $data['pager'] = $this->artikel->pager;
                $data['key'] = $key['cari'];
            }
        } 
        return view("artikel-detail", $data);
    }
}