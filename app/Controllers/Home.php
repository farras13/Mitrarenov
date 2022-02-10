<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;

class Home extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
    }

    public function index()
    {
        $model = new ArtikelModel();
        $data['artikel'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->orderBy('created', 'DESC')->get()->getResult();
        $data['testimoni'] = $this->model->getAll('testimoni')->getResult();
        $data['promo'] = $this->model->getWhere('promomobile', ['is_publish' => 0])->getResult();
        $data['galery'] = $this->model->getAll('gallery_pekerjaan', 8)->getResult();
        $data['merawat'] = $this->model->getAll('merawat', 8)->getResult();
        $data['design_rumah'] = $this->model->getAll('design_rumah', 8)->getResult();
        $data['partner'] = $this->model->getAll('testimoni')->getResult();
        return view('index', $data);
    }

    public function login()
    {
        helper(['form']);
        echo view("login");
    }

    public function reg()
    {
        //include helper form
        helper(['form']);
        //set rules validation form
        $rules = [
            'name'          => 'required|min_length[3]|max_length[20]',
            'phone'         => 'required|min_length[9]|max_length[13]',
            'email'         => 'required|min_length[6]|max_length[50]|valid_email',
            'password'      => 'required|min_length[6]|max_length[200]',
            'confpassword'  => 'matches[password]'
        ];

        if ($this->validate($rules)) {
            $model = new AuthModel();
            $models = new AuthDetailModel();
            
            $use = $this->model->getWhere('member', ['email' => $this->request->getVar('email')])->getRow();
            // var_dump($use);die;
            if($use != null){
                $data['valmail'] = "Email sudah terdaftar!";
                return view('register', $data);
            }

            $data = [
                'email'    => $this->request->getVar('email'),
                'password' => md5($this->request->getVar('password')),
                'usergroup_id' => 5
            ];
            $model->save($data);

            $last = $this->model->lastId('member', 1)->getRow();
            $datas = [
                'member_id'    => $last->id,
                'name'    => $this->request->getVar('name'),
                'telephone' => $this->request->getVar('phone')
            ];
            $models->save($datas);
            return redirect()->to('member/login');
        } else {
            $data['validation'] = $this->validator;
            echo view('register', $data);
        }
    }

    public function pros_log()
    {
        $model = new GeneralModel();
        $request = $this->request->getVar();

        $w = array(
            'email' => $request['email'],
            'password' => md5($request['password']),
        );

        $cek = $model->getWhere('member', $w)->getRow();
        if (!$cek) {
            echo '<script language="javascript">';
            echo 'alert("Akun di temukan , pastikan inputan anda benar")';
            echo '</script>';
            redirect('member/Login', 'refresh');
        }

        $cek_detail = $model->getWhere('member_detail', ['member_id' => $cek->id])->getRow();

        $ctoken = $model->getWhere('token_login', ['member_id' => $cek->id])->getRow();

        $headers = $this->request->headers();
        $device = $headers['User-Agent']->getValue();

        // if (!$ctoken) {
        //     $token = random_string('alnum', 30);
        //     $dtoken = array(
        //         'member_id' => $cek[0]['id'],
        //         'token' => $token,
        //         'user_agent' => $device,
        //     );
        //     $model->ignore(true)->insert($dtoken);
        // }
        $session = session();
        $ses_data = [
            'user_id'       => $cek->id,
            'user_name'     => $cek_detail->name,
            'user_email'    => $cek->email,
            'logged_in'     => TRUE
        ];
        $session->set($ses_data);
        return redirect()->to('home');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('member/login');
    }

    public function akun()
    {
    }

    public function kpr()
    {
    }

    public function register()
    {
        echo view("register");
    }

    public function simulasi()
    {
        echo view("simulasi-kpr");
    }

    public function tentang_kami()
    {
        echo view("tentang-kami");
    }

    public function cara_kerja()
    {
        echo view("index");
    }

    public function Hubungi()
    {
        $data['lokasi'] = $this->model->getAll('location')->getResult();
        $data['area'] = $this->model->getAll('area')->getResult();
        $data['prov'] = $this->model->getAll('province')->getResult();
        echo view("hubungi-kami", $data);
    }

    public function artikel()
    {
        $model = new ArtikelModel();
        // $data['berita'] = $this->model->getAll('news')->getResult(); 
        $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->orderBy('created', 'DESC')->paginate(5, 'berita');;
        // $data['kategori'] = $this->model->getAll('category_aritkel')->getResult();
        // var_dump($data['terbaru']);die;
        $data['hot'] = $model->orderBy('created', 'ASC')->hot();
        $data['pager'] = $model->pager;
        return view("artikel", $data);
    }

    public function d_artikel($id)
    {
        $model = new ArtikelModel();
        $data['berita'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->find($id);
        $data['hot'] = $model->orderBy('created', 'ASC')->hot();
        return view("artikel-detail", $data);
    }

    public function d_promo($id)
    {
        $model = new GeneralModel();
        $w = array('id' => (int)$id, 'is_publish' => 0);
        $data['promo'] = $model->getWhere('promomobile', $w)->getRow();

        return view("promo-detail", $data);
    }

    public function material()
    {
        echo view("index");
    }
}
