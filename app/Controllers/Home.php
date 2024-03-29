<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\DboModel;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;
use App\Models\PortoModel;
use App\Models\DesignModel;
use App\Models\GalleryModel;
use CodeIgniter\I18n\Time;
use DOMDocument;
use mysqli;

class Home extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
    }
    // DASHBOARD
    public function index()
    {
        $model = new ArtikelModel();
        $sess = session();
        $id = $sess->get('user_id');
        if ($id != null) {
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
        $data['alur'] = $this->model->getAll('rules')->getResult();
        $data['keunggulan'] = $this->model->getAll('keunggulan')->getResult();
        $artikel = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by', 'left')->where('is_publish', '0')->orderBy('date', 'DESC')->get(10)->getResult();
        $temp_artikel = [];
        foreach ($artikel as $key => $value) {
            if(date('Y-m-d',$value->date) <= date('Y-m-d')){
                $temp_artikel[] = $value;
            }
        }
       
        $data['artikel'] = $temp_artikel; 
        $data['testimoni'] = $this->model->getAll('testimoni')->getResult();
        $data['promo'] = $this->model->getWhere('promomobile', ['is_publish' => 0], null, 'posisi', 'asc')->getResult();
        $data['galery'] = $this->model->getOrderBy('gallery_pekerjaan', 'id', 'desc', 8)->getResult();
        $data['merawat'] = $this->model->getOrderBy('merawat', 'created', 'desc', 8)->getResult();
        $data['design_rumah'] = $this->model->getWhere('design_rumah', ['is_publish' => 1], 8, 'id', 'desc')->getResult();
        $data['liputan'] = $this->model->getAll('liputan')->getResult();
        $data['partner'] = $this->model->getOrderBy('partner', 'position', 'asc')->getResult();
        $data['lokasi'] = $this->model->getAll('location')->getResult();
        $data['kategori_old'] = $this->model->getWhere('category', ['id !=' => 3])->getResult();
        $data['kategori'] = $this->model->getQuery("SELECT category.*, COUNT(product.id) as total, product.paket_name FROM `category` JOIN product ON product.category_id = category.id WHERE category.id != 3 GROUP BY category.id Order By sort")->getResult();
        $data['jasa'] = $this->model->getQuery("SELECT DISTINCT product.* FROM product_price JOIN product ON product.id = product_price.product_id WHERE product.category_id != 3 ORDER BY product.category_id")->getResult();
        $data['membangun'] = $this->model->getWhere('product', ['category_id' => 1])->getResult();
        $data['renovasi'] = $this->model->getWhere('product', ['category_id' => 2])->getResult();
        $agent = $this->request->getUserAgent();
        if ($agent->isMobile()) {
            $currentAgent = $agent->getBrowser() . ' ' . $agent->getMobile();
        } elseif ($agent->isRobot()) {
            $currentAgent = $agent->getRobot();
        } elseif ($agent->isBrowser()) {
            $currentAgent = $agent->getMobile();
        } else {
            $currentAgent = 'Unidentified User Agent';
        }
        $data['agen'] = $currentAgent;
        // var_dump($temp_lokasi);
        return view('index', $data);
    }

    public function portofolio()
    {
        $model = new PortoModel();
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
        $data['title'] = "Portofolio";
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        $data['link_gambar'] = "https://office.mitrarenov.com/assets/main/images/merawat/";

        $data['porto'] = $model->select('merawat.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = merawat.created_by')->paginate(12, 'berita');
        $data['pager'] = $model->pager;

        return view('portofolio', $data);
    }

    public function detail_porto($id)
    {
        $data['title'] = "Portofolio";
        
		$jenis = str_replace('-',' ',$id);
		$explod = explode("-",$id);
        
	    foreach($explod as $v){
        	$foundCharacter = strpos($v, ".");
            if($foundCharacter !== false){
                $cek = explode(".", $v);
    		    foreach($cek as $c){
                    if (!empty($c) && !is_numeric($c) && !ctype_lower($c)) {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                    }
                }
            }else{
                if($v != "&"){
                    if (!empty($v) && !is_numeric($v) && !ctype_lower($v)) {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                    } 
                }
            }
        }
        $data['porto'] = $this->model->getWhere('merawat', ['slug' => $id])->getRow();
        if (empty($data['porto'])) {
        	throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    	} 
        $id = $data['porto']->created_by;
        $data['penulis'] = $this->model->getWhere('member_detail', ['member_id' => $id])->getRow();
        $data['lain'] = $this->model->getWhere('merawat', ['id !=' => $id], 4)->getResult();
        $data['gambar'] = $this->model->getWhere('gambar_portofolio', ['porto_id' => $data['porto']->id])->getResult();
        $sess = session();
        $key = $this->request->getGet();
        $id = $sess->get('user_id');
        if($id != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id ORDER BY id desc")->getResult();
            
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
        $data['linkdetail'] = base_url('portofolio');
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        $data['link_gambar'] = "https://office.mitrarenov.com/assets/main/images/merawat/";

        return view('porto_detail', $data);
    }

    public function design_rumah()
    {
        $model = new DesignModel();
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
        $data['title'] = "Design Rumah";
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;        
        $data['porto'] = $model->select('*')->where('is_publish', '1')->orderBy('id', 'DESC')->paginate(12, 'berita');
        $data['pager'] = $model->pager;
        $data['link_gambar'] = "https://office.mitrarenov.com/assets/main/images/design_rumah/";

        return view('portofolio', $data);
    }

    public function detail_design_rumah($id)
    {
        $jenis = str_replace('-',' ',$id);
		$explod = explode("-",$id);
        
	    foreach($explod as $v){
        	if (!ctype_lower($v) && !empty($v)) {
            	throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        	} 
        }
        $data['porto'] = $this->model->getWhere('design_rumah', ['slug' => $id])->getRow();
        if (empty($data['porto'])) {
        	throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    	} 
        $id_member = $data['porto']->created_by;
        $data['penulis'] = $this->model->getWhere('member_detail', ['member_id' => $id_member])->getRow();
        $data['lain'] = $this->model->getWhere('design_rumah', ['slug !=' => $id], 4)->getResult();
        $data['gambar'] = $this->model->getWhere('gambar_portofolio', ['porto_id' => $id])->getResult();
        $sess = session();
        $key = $this->request->getGet();
        $id = $sess->get('user_id');
        if($id != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id ORDER BY id desc")->getResult();
            
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
        $data['title'] = "Design Rumah";
        $data['linkdetail'] = base_url('desain-rumah');
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        $data['link_gambar'] = "https://office.mitrarenov.com/assets/main/images/design_rumah/";

        return view('porto_detail', $data);
    }

    public function d_promo($id)
    {
        $model = new GeneralModel();
        $w = array('id' => (int)$id, 'is_publish' => 0);
        $data['promo'] = $model->getWhere('promomobile', $w)->getRow();
        
        $sess = session();
        $idn = $sess->get('user_id');
        
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
            // var_dump($temp);die;
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
            }
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

        return view("promo-detail", $data);
    }

    public function gallery()
    {
        $model = new GalleryModel();
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
        $data['title'] = "Gallery Progress";
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;

        $data['porto'] = $model->select('gallery_pekerjaan.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = gallery_pekerjaan.created_by')->orderBy('gallery_pekerjaan.id', 'desc')->paginate(8, 'gallery');
        $data['pager'] = $model->pager;

        return view('gallery', $data);
    }

    // Simulasi-kpr
    public function simulasi()
    {
        $model = new DboModel();
        $sess = session();
        $idn = $sess->get('user_id');
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori,  message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori,  message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
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
        $data['prov'] = $model->getProv();
        $data['snk'] = $model->getSnk();
        return view("simulasi-kpr", $data);
    }

    public function get_area()
    {
        $id = $this->request->getVar('id');
        $model = new DboModel();
        $data = $model->getArea($id);
        echo json_encode($data);
    }

    public function add_kpr()
    {
        helper(['form']);
        $model = new DboModel();
        $input = $this->request->getVar();

        $json_tukang =  $model->findTukang($input['area']);

        $id_tukang = $json_tukang->id_tukang;
        $email_tukang = $json_tukang->email_tukang;
        $nama_tukang = $json_tukang->nama_tukang;
        $hp_tukang = $json_tukang->telephone;

        $date_jadwal = date_create($input['jadwal_survey']);
        $dataBerkas = $this->request->getFile('file');
        $fileName = $dataBerkas->getRandomName();
        $path = "./public/images/kpr";
        $dataBerkas->move($path, $fileName);
        $ins_project = [
            'jenis_kredit' => 'bangun',
            'id_area' => $input['area'],
            'alamat' => $input['alamat'],
            'luas_bangun' => $input['luas_bangun'],
            'deskripsi' => $input['deskripsi'],
            'jangka_waktu' => $input['jangka_waktu'],
            'nama' => $input['nama'],
            'no_telp' => $input['no_telp'],
            'email' => $input['email'],
            'file_upload' => $fileName,
            'created' => date("Y-m-d H:i:s"),
            'tukang_id' => $id_tukang,
            'jadwal_survey' => date_format($date_jadwal, "Y-m-d"),
            'kode_referal' => $input['kode_referal'],
        ];
        if ($input['cekbok'] == null) {
            session()->setFlashdata('toast', 'warning:!.');
            return redirect()->to('simulasi-kpr');
        }
        // var_dump($ins_project);die;
        // $provinceVal = $input('provinceVal');

        $insert1 = $model->insA('pengajuan_kpr', $ins_project);

        if (!$insert1) {
            session()->setFlashdata('toast', 'error:Pengajuan anda gagal !.');
            return redirect()->to('simulasi-kpr');
        }

        session()->setFlashdata('toast', 'success:Selamat pengajuan anda berhasil !.');
        return redirect()->to('simulasi-kpr');
    }

    // tentang-Kami
    public function tentang_kami()
    {
        $sess = session();
        $idn = $sess->get('user_id');
        $temp = null; $no = 0; $chat = 0;
        if($idn != null){
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
            $key = $this->request->getGet();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
            }
            // var_dump($temp);die;
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
        
        echo view("tentang-kami", $data);
    }

    // Hubungi Kami
    public function Hubungi()
    {
        $model = new DboModel();
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
        
        $data['lokasi'] = $this->model->getWhere('location', ['maps !=' => ''])->getResult();
        $data['prov'] = $model->getProv();
        echo view("hubungi-kami", $data);
    }

    public function add_hubungi()
    {
        helper(['form']);
        $input = $this->request->getVar();
        $model = new DboModel();

        $dataBerkas = $this->request->getFile('file');
        $fileName = $dataBerkas->getRandomName();
        $path = "./public/images/hubungi";
        // var_dump($fileName);

        $dataBerkas->move($path, $fileName);

        $data = array(
            'name' =>  $input['nama'],
            'business_name' => $input['detail_info'],
            'provinsi_id' => $input['provinsi'],
            'area_id' => $input['area'],
            'perihal' => $input['perihal'],
            'email' => $input['email'],
            'notelp' => $input['notlp'],
            'lampiran' => $fileName,
            'message' => $input['pesan'],
            'created' => time(),
        );
        $insert = $model->insA('hubungi_kami', $data);

        if (!$insert) {
            echo '<script language="javascript">';
            echo 'alert("Pengajuan gagal !")';
            echo '</script>';
            session()->setFlashdata('toast', 'error:Gagal dikirm ! Pastikan form sudah terisi semua !!.');
            return redirect()->to('kontak');
        }
        session()->setFlashdata('toast', 'success:Berhasil dikirm !.');
        return redirect()->to('halaman/hubungi-kami');
    }
    
    public function onclicknotif($id)
    {
        $model = new GeneralModel();
        $model->upd('notifikasi', ['id' => $id], ['status' => 1]);
        return redirect()->to('chat');        
    }
    
    public function sitemap(){
        
         echo view("sitemap");
    }
    
    public function sitemap_backup(){
        $datetime = Time::parse('2020-11-25 17:48:31', 'Asia/Jakarta');
        $result = $datetime->format('Y-m-d\TH:i:sP');
        $hasil = array('berita','gallery_pekerjaan','portofolio','design_rumah','simulasi-kpr','halaman/tentang-kami','halaman/cara-kerja','halaman/hubungi-kami','halaman/jadilah-rekanan-kami','halaman/career');
          
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
              <?xml-stylesheet type="text/xsl" href="https://mitrarenov.com/Home/sitemap_style2"?>
              <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                      http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
              <url>
                  <loc>https://mitrarenov.com/</loc>
                  <priority>1.0</priority>
                  <lastmod>'.$result.'</lastmod>
              </url>';
              $hasil = array('berita','gallery_pekerjaan','portofolio','design_rumah','simulasi-kpr','halaman/tentang-kami','halaman/cara-kerja','halaman/hubungi-kami','halaman/jadilah-rekanan-kami','halaman/career');
          foreach($hasil as $h){
           $xmlString .=   '<url>';
           $xmlString .=  '<loc>'.base_url(''.$h).'</loc>';
           $xmlString .=  '<lastmod>'.$result.'</lastmod>';
           $xmlString .=  '<priority>0.80</priority>';
           $xmlString .=  '</url>';
          }
          
          $xmlString .= '</urlset>';
          $dom = new DOMDocument();
          $dom->preserveWhiteSpace = FALSE;
          $dom->loadXML($xmlString);
          echo $dom->saveXML();
    }

    public function add_langganan()
    {
        $input = $this->request->getVar();
        $cek = $this->model->getWhere('newsletter_send', ['email' => $input['email']])->getRow();
        if(empty($cek)){
            $rules = [
                'email'  => 'required|valid_email',
            ];

            if ($this->validate($rules)) {
                $name = "Cust-".date('dmY');
                $this->model->ins('newsletter_send', ['email' => $input['email'], 'name' => $name, 'tanggal' => date('Y-m-d')]);
                session()->setFlashdata('toast', 'success:Terima kasih telah berlangganan !.');
                return redirect()->to('Home');
            }else{
                session()->setFlashdata('toast', 'error:Pastikan inputan berupa email yang valid !.');
                return redirect()->to('Home');
            }
        }else{
            session()->setFlashdata('toast', 'warn:Email anda sudah berlangganan !.');
            return redirect()->to('Home');
        }
    }
}
