<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\DboModel;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;
use App\Models\PortoModel;

class Chat extends BaseController
{
    function __construct()
    {

    }
    
    public function index()
    {
        $model = new GeneralModel();
        $dbo = new DboModel();
        $sess = session();
        $id = $sess->get('user_id');
        if($id != null){
            $temp = $model->getQuery("SELECT id, kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id")->getResult();
            $key = $this->request->getGet();
            $detail = $this->request->getVar();
        
            if(array_key_exists("limit",$key) ){
                $limit  = (int) $key['limit'];
                $temp = $model->getQuery("SELECT id, kategori, message, DATE_FORMAT(FROM_UNIXTIME(date), '%e %b %Y') AS 'date', status FROM notifikasi WHERE member_id = $id LIMIT $limit")->getResult();
            }
            // var_dump($temp);die;
            $no = 0;
            $chat = 0;
            foreach ($temp as $key => $value) {
                if($value->status == 0){
                    $no++;
                }
                if($value->kategori == "chat" && $value->status == 0){
                    $chat++;
                }
            }
        }        

        $cekUser = $model->getWhere('member', ['id' => $id])->getRow();
        $group = $model->getWhere('usergroup',['id' => $cekUser->usergroup_id])->getRow();
        $data['notif'] = $temp;
        $data['notif_total'] = $no;
        $data['chat_total'] = $chat;
        if($group->name == 'User'){ 
            $gn = 'customer'; 
        }else{  
            $gn = $group->name; 
        }
        $data['group'] = $gn;
        $data_chat = $dbo->getListChat($group->name, $id);
        $data['list_chat'] = $data_chat;
        if($detail['83rc2kp'] != null){
            $data_chat = $dbo->dchat(base64_decode($detail['83rc2kp']));
            $data['detail_chat'] = $data_chat;
            $data['idlist'] = $detail['83rc2kp']; 
        }else{
            $data['detail_chat'] = $model->getWhere('chat', ['project_id' => $data_chat[0]->project_id])->getResult();
            $data['idlist'] = base64_encode($data_chat[0]->project_id); 
        }
        return view('percakapan', $data);
    }

    public function kirim()
    {
        $model = new GeneralModel();
        $sess = session();
        $user_id = $sess->get('user_id');
        $cekUser = $model->getWhere('member', ['id' => $user_id])->getRow();
        $group = $model->getWhere('usergroup',['id' => $cekUser->usergroup_id])->getRow();
        if($group->name == 'User'){ 
            $user = 'customer'; 
        }else{  
            $user = $group->name; 
        }
        $input = $this->request->getVar();
        $data = array(
            'project_id' => base64_decode($input['idcht']),
            'user' => $user, 
            'message' => $input['cht'], 
            'date' => time(),
            'type' => 'text' 
        );
        $model->ins('chat', $data);
        return redirect()->to('chat?83rc2kp='.$input['idcht']);
    }

    public function onclicknotif($type,$id)
    {
        $model = new GeneralModel();
        $model->upd('notifikasi', ['id' => $id], ['status' => 1]);
        if ($type == "chat") {
            return redirect()->to('chat');        
        }else if($type == "project"){
            return redirect()->to('member/akun');        
        }else if($type == "promo"){
            return redirect()->to('/');        
        }
    }

    public function seenallnotif()
    {
        $model = new GeneralModel();
        $sess = session();
        $user_id = $sess->get('user_id');
        $model->upd('notifikasi', ['member_id' => $user_id], ['status' => 1]);
        return $user_id;
    }
}