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
        $key = $this->request->getGet();
        $page = 0;

        // var_dump($count);die;
        if (array_key_exists("page", $key) || array_key_exists("cari", $key)) {

            $page  += (int) $key['page'];
            $cari  = $key['cari'];

            if ($page != 0 && $cari != null) {
                $default = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date, a.news_category, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 0 AND title LIKE '%$cari%' ORDER BY a.created desc")->getResult();
                $temp_count = count($default);
                $count = round($temp_count / 8);
                $limit = 8;
                $ofs = $page * $limit - 8;
                $data = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date,a.news_category, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 0 AND title LIKE '%$cari%' ORDER BY a.created desc LIMIT $ofs,$limit ")->getResult();
            } elseif ($page != 0) {
                $default = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date,a.news_category, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 0 ORDER BY a.created desc")->getResult();
                $limit = 8;
                $ofs = $page * $limit - 8;
                $data = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date,a.news_category, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 0 ORDER BY a.created desc LIMIT $ofs,$limit ")->getResult();
                $temp_count = count($default);
                $count = round($temp_count / 8);
            } elseif ($cari != null) {

                $data = $db->query("SELECT a.id, a.title, a.slug, a.image, a.created, a.date,a.news_category, a.meta_description, a.hits, a.tagline, a.analyticsviews, member_detail.name AS penulis FROM news as a join member on member.id = a.created_by JOIN member_detail on member.id = member_detail.member_id WHERE a.is_publish = 0 AND title LIKE '%$cari%' ORDER BY a.created desc")->getResult();
                $temp_count = count($data);
                $count = round($temp_count / 8);
            }
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

        foreach ($data as $d) {
            $time = $d->created;
            $date = new DateTime("@$time");
            $d->image = 'https://admin.mitrarenov.soldig.co.id/assets/main/images/news/' . $d->image;
            $d->date = $date->format('d M Y');
        }

        $temp = [
            "base_image" => 'https://admin.mitrarenov.soldig.co.id/assets/main/images/news/',
            "total_page" => $count,
            "page" => $page,
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
        $time = $data->created;
        $date = new DateTime("@$time");
        $data->image = $url . '/public/images/news/thumbs/' . $data->image;
        $data->date = $date->format('d M Y');
        $res = [
            "status" => TRUE,
            "messages" => "Sukses",
            'data' => $data
        ];
        return $this->respond($res, 200);
    }

    public function category()
    {
        $db = db_connect();
        $data = $db->query("SELECT * FROM news_category")->getResult();
        if ($data == null) {
            $res = [
                "status" => TRUE,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
        $url = base_url();
        $res = [
            "status" => TRUE,
            "messages" => "Sukses",
            'data' => $data
        ];
        return $this->respond($res, 200);
    }

    public function category_detail($id = null)
    {
        $db = db_connect();
        $data = $db->query("SELECT * FROM news where news_category = $id")->getResult();
        if ($data == null) {
            $res = [
                "status" => TRUE,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }
        $url = base_url();
        foreach ($data as $d) {
            $time = $d->created;
            $date = new DateTime("@$time");
            $d->image = $url . '/public/images/news/thumbs/' . $d->image;
            $d->date = $date->format('d M Y');
            $d->url = $url . '/artikel/' . $d->id . '/detail';
        }
        $res = [
            "status" => TRUE,
            "messages" => "Sukses",
            'data' => $data
        ];
        return $this->respond($res, 200);
    }
}
