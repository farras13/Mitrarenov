<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;

class Login extends BaseController
{
    function __construct()
    {
        $this->authm = new AuthModel();
        $this->authdm = new AuthDetailModel();
        $this->model = new GeneralModel();
    }

    public function login()
    {
        helper(['form']);
        echo view("login");
    }

    public function register()
    {
        echo view("register");
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
            $use = $this->model->getWhere('member', ['email' => $this->request->getVar('email')])->getRow();
            // var_dump($use);die;
            if ($use != null) {
                $data['valmail'] = "Email sudah terdaftar!";
                session()->setFlashdata('toast', 'error:' . $data);
                return view('register', $data);
            }

            $data = [
                'email'    => $this->request->getVar('email'),
                'password' => md5($this->request->getVar('password')),
                'usergroup_id' => 5
            ];
            $this->authm->save($data);

            $last = $this->model->lastId('member', 1)->getRow();
            
            $singkatan = str_replace(' ', '', $this->request->getVar('name'));
            $fourname = substr($singkatan, 0, 4);
            $count = $this->authm->hitung();

            $referal = '' . $fourname . '' . $count;
            $datas = [
                'member_id' => $last->id,
                'name'    => $this->request->getVar('name'),
                'telephone' => $this->request->getVar('phone'),
                'referal' => $referal
            ];
            $this->authdm->save($datas);
            session()->setFlashdata('toast', 'success:Akun berhasil dibuat! Silakan melakukan login.');
            return redirect()->to('member/login');
        } else {
            $data['validation'] = $this->validator;
            session()->setFlashdata('toast', 'error:' . $data);
            echo view('register', $data);
        }
    }

    public function pageEmail()
    {
        echo view('send_email_reset');
    }

    public function forgot_pass()
    {
        $data['segment'] = $this->request->uri->getSegment(2);
        echo view("forgot_pass", $data);
    }

    public function sendReset()
    {
        $request = $this->request->getVar();
        $session = session();

        $pass = $request['password'];
        $passK = $request['passwordK'];
        $token = $request['token'];

        if ($passK != $pass) {
            $session->setFlashdata('toast', 'errorr:Password dan password konfrimasi tidak match!');
            return redirect()->back()->withInput();
        }

        $where = array('token' => $token);
        $cek =  $this->model->getWhere('temp_reset_pass', $where)->getRow();
        $w = array('id' => $cek->member_id);
        $data = array('password' => md5($pass));
        $update =   $this->model->upd('member', $w, $data);

        if (!$update) {
            $session->setFlashdata('toast', 'error:Update Password Gagal !');
            return redirect()->back()->withInput();
        }

        $this->model->del('temp_reset_pass', ['token' => $token]);
        $session->setFlashdata('toast', 'success:Update Password berhasil !');
        return redirect()->to('member/login');
    }

    public function sendEmail()
    {
        helper('text');
        $session = session();

        $token = random_string('alnum', 35);
        $res = array('token_reset' => $token,);

        $input = $this->request->getVar();
        $data = ['email' => $input['email'],];
        $to = $input['email'];

        $cek = $this->authm->where($data)->first();

        if (!$cek) {
            $session->setFlashdata('toast', 'error:Email belum terdaftar !');
            return redirect()->back()->withInput();
        }
        $this->model->ins('temp_reset_pass', ['member_id' => $cek['id'], 'token' => $token]);
        $title = "Reset Password";
        $message = '<h2>Reset Password</h2><p>Untuk melakukan reset password anda dapat klik link berikut <b><a href="' . base_url('lupa_password') . '/' . $token . '">Link reset</a></b> </p>';

        $email = \Config\Services::email();
        $email->setFrom('info@mitrarenov.com', 'info@mitrarenov.com');
        $email->setTo($to);
        $email->setSubject($title);
        $email->setMessage($message);

        if (!$email->send()) {
            $session->setFlashdata('toast', 'error:Email Gagal dikirim !');
            return redirect()->back()->withInput();
        } else {
            $session->setFlashdata('toast', 'success:Email sudah terkirim !');
            return redirect()->to('member/login');
        }
    }
    
    public function sendEmailOrder()
    {
        helper('text');
        $session = session();
        $this->model = new GeneralModel();

        $token = random_string('alnum', 35);
        $res = array('token_reset' => $token,);

        $input = $this->request->getVar();
        $data = ['email' => $input['email'],];
        $to = $input['email'];

        $cek = $this->authm->where($data)->first();

        // $this->model->ins('temp_reset_pass', ['member_id' => $cek['id'], 'token' => $token]);
        $title = "Order Mitrarenov";
        $message = '<h2>Reset Password</h2><p>Untuk melakukan reset password anda dapat klik link berikut <b><a href="' . base_url('lupa_password') . '/' . $token . '">Link reset</a></b> </p>';

        $email = \Config\Services::email();
        $email->setFrom('notifikasi@mitrarenov.com', 'notifikasi@mitrarenov.com');
        $email->setTo($to);
        $email->setSubject($title);
        $email->setMessage($message);

        if (!$email->send()) {
            $session->setFlashdata('toast', 'error:Email Gagal dikirim !');
            return redirect()->back()->withInput();
        } else {
            $session->setFlashdata('toast', 'success:Email sudah terkirim !');
            return redirect()->to('member/login');
        }
    }

    public function pros_log()
    {
        $request = $this->request->getVar();

        $w = array(
            'email' => $request['email'],
            'password' => md5($request['password']),
            'usergroup_id' => 5,
        );

        $cek = $this->model->getWhere('member', $w)->getRow();
        if (!$cek) {
            session()->setFlashdata('toast', 'error:Email atau password anda salah !');
            return redirect()->to('member/login');
        }

        $cek_detail = $this->model->getWhere('member_detail', ['member_id' => $cek->id])->getRow();

        $ctoken = $this->model->getWhere('token_login', ['member_id' => $cek->id])->getRow();
        $agent = $this->request->getUserAgent();
        if ($agent->isMobile('iphone')) {
            $device = 1;
        } elseif ($agent->isMobile()) {
            $device = 2;
        } else {
            $device = 3;
        }
        $this->model->upd('member', ['id' => $cek->id], ['last_login' => time(), 'device' => $device]);

        $headers = $this->request->headers();
       
       
        // if (!$ctoken) {
        //     $token = random_string('alnum', 30);
        //     $dtoken = array(
        //         'member_id' => $cek[0]['id'],
        //         'token' => $token,
        //         'user_agent' => $device,
        //     );
        //     $this->model->ignore(true)->insert($dtoken);
        // }
        $session = session();
        $ses_data = [
            'user_id'       => $cek->id,
            'user_name'     => $cek_detail->name,
            'user_phone'     => $cek_detail->telephone,
            'user_email'    => $cek->email,
            'logged_in'     => TRUE,
        ];
        $session->set($ses_data);
        $session->setFlashdata('toast', 'success:Welcome to Mitrarenov !');
        return redirect()->to('home');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        session()->setFlashdata('toast', 'success:See you! Have a nice day.');
        return redirect()->to('member/login');
    }
    
}