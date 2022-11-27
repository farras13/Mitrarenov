<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\DboModel;
use App\Models\GeneralModel;


class Akun extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
    }

    public function akun()
    {
        $sess = session();
        $mdl = new DboModel();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        $data['projekBerjalan'] = $mdl->getProjectUser($sess->get('user_id'), null, 'project');
        // $detail = $mdl->getProjectUserD($sess->get('user_id'), null, 'project');
        $data['kurang'] = $mdl->getProjectUser($sess->get('user_id'), null, 'project');
        
        foreach($data['projekBerjalan'] as $pj){
            $dokumentasi = $this->model->getWhere('projects_update', ['project_id' => $pj->id])->getResult();
            $pj->dokumentasi = $dokumentasi;
        }

        $id = $sess->get('user_id');
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
            // $data['notif'] = $temp;
            // $data['notif_total'] = $no;
            // $data['chat_total'] = $chat;
        }
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        // echo "<pre>"; print_r($data['projekBerjalan']); echo"</pre>";
        echo view("projek_berlangsung", $data);
    }
    
    public function projektambah()
    {
        $mdl = new DboModel();
        $input = $this->request->getVar();
        $id = $input['id'];
        $data = $mdl->addenum("tambah", $id);
        
       
        echo json_encode($data);
    }

    public function projekkurang()
    {
        $mdl = new DboModel();
        $input = $this->request->getVar();
        $id = $input['id'];
        $data = $mdl->addenum("kurang", $id);
       
        echo json_encode($data);
    }

    public function edit_profile()
    {
        $sess = session();
        $idn = $sess->get('user_id');
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        
        $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc")->getResult();
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn ORDER BY id desc LIMIT $limit")->getResult();
        }
        // var_dump($temp);die;
        $no = 0;$chat=0;
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
      
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        return view('ubah_profile', $data);
    }

    public function update_profile()
    {
        helper(['form']);
        $session = session();
        if ($session->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }

        $request = $this->request->getVar();
        $gambar = $this->request->getFile('file');

        $data = array('email' => $request['email']);

        if (!$gambar->isValid()) {
            $data_detail = array(
                'name' => $request['nama'],
                'telephone' => $request['telephone']
            );
        } else {
            $fileName = $gambar->getRandomName();
            $path = "./public/images/pp";
            $gambar->move($path, $fileName);
            $data_detail = array(
                'name' => $request['nama'],
                'telephone' => $request['telephone'],
                'photo' => $fileName,
            );
        }
        $wd = array('member_id' => $session->get('user_id'));
        $w = array('id' => $session->get('user_id'));
        $detail = $this->model->upd('member_detail', $wd, $data_detail);
        $member = $this->model->upd('member', $w, $data);

        if (!$detail) {
            $session->setFlashdata('toast', 'errorr:Gagal update member!');
            return redirect()->back()->withInput();
        }
        if (!$member) {
            $session->setFlashdata('toast', 'errorr:Gagal update email member!');
            return redirect()->back()->withInput();
        }

        $session->setFlashdata('toast', 'success:Berhasil update data member!');
        return redirect()->back();
    }

    public function uploadImage($file, $path)
    {
        helper(['form']);
        $date = date('Y-m-d');
        $profile_image = $file->getName();
        $nameNew = $date . substr($profile_image, 0, 5) . rand(0, 20).'.'.$file->guessExtension();
        // var_dump($newfilename);die;
        if (!$file->isValid()) {
            return $response = $file->getErrorString();
        }

        if ($file->move($path, $nameNew)) {
            $response = [
                'data' => [
                    "file_name" => $nameNew,
                    "file_path" => $path,
                ]
            ];
        } else {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => 'Failed to upload image',
                'data' => []
            ];
        }

        return $response;
    }

    public function riwayat_projek()
    {
        $sess = session();
        $mdl = new DboModel();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        
        $idn = $sess->get('user_id');
        
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

        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        $data['projek'] = $mdl->getProjectUserS($idn, 'done');
        return view('riwayatProjek', $data);
    }

    public function changePass()
    {
        $sess = session();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $idn = $sess->get('user_id');
        
        $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn")->getResult();
        $key = $this->request->getGet();
    
        if(array_key_exists("limit",$key) ){
            $limit  = (int) $key['limit'];
            $temp = $this->model->getQuery("SELECT id, kategori, id_kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $idn LIMIT $limit")->getResult();
        }
        // var_dump($temp);die;
        $no = 0; $chat = 0;
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
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        return view('reset_pass', $data);
    }

    public function updPass()
    {
        $session = session();
        if ($session->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }

        $model = new GeneralModel();
        $request = $this->request->getVar();

        $id = $session->get('user_id');
        $old = $request['passlama'];
        $new = $request['passbaru'];
        $newK = $request['passK'];

        $where = array('id' => $id, 'password' => md5($old));
        $cek = $model->getWhere('member', $where)->getRow();

        if (!$cek) {
            $session->setFlashdata('toast', 'error:Password lama anda salah!');
            return redirect()->back()->withInput();
        }

        if ($newK != $new) {
            $session->setFlashdata('toast', 'error:Password dan password konfrimasi tidak match!');
            return redirect()->back()->withInput();
        }

        $w = array('id' => $id);
        $data = array('password' => md5($new));
        $update = $model->upd('member', $w, $data);

        if (!$update) {
            $session->setFlashdata('toast', 'error:Update Password Gagal !');
            return redirect()->back()->withInput();
        }

        $session->setFlashdata('toast', 'success:Update Password berhasil !');
        return redirect()->back();
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
        $data['tentang'] = $model->getAll('qa')->getResult();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('qa', $data);
    }

    public function snk()
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
        }
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        $data['tentang'] = $model->getWhere('footer', ['id' => 4])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('snk', $data);
    }

    
}