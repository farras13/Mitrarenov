<?php

namespace App\Controllers\Api;

use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\AuthTokenModel;
use App\Models\DboModel;
use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\Images\Image;
use CodeIgniter\RESTful\ResourceController;
use PHPUnit\Framework\Constraint\Count;

class LoginController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;
    protected $helpers = ['text'];

    public function login_create()
    {
        // inisiet model
        $auth = new AuthModel();
        $mtoken = new AuthTokenModel();
        $model = new GeneralModel();
        // init request json
        $headers = $this->request->headers();
        $request = $this->request->getVar();
        
        $device = $headers['User-Agent']->getValue();

        if(strpos("Okhttp/3.12.12", $device) != ""){
            $cekdev = 2;
        }else{
            $cekdev = 1; 
        }

        if($request['email'] == null || $request['password'] == null){
            $res = [
                "status" => 500,
                "message" => "Email atau pass anda kosong!",
                "data" => null
            ];
            return $this->respond($res, 500);
        }
        
        // get login request
        $data = array(
            'email' => $request['email'],
            'password' => md5($request['password']),
        );

        // cek data login
        $cek = $auth->MemberLogin($data);
        if (!$cek) {
            $res = [
                "status" => 401,
                "message" => "data tidak ditemukan !",
                "data" => null
            ];
            return $this->respond($res, 401);
        }
        
        // var_dump($agent);die;
        $cek_token = $model->getWhere('token_login', ['member_id' => $cek[0]['id']], null)->getRow();
        $model->upd('member', ['id' => $cek[0]['id']], ['last_login' => time(), 'device' => $cekdev ]);
        if ($cek_token != null) {
            $token = $cek_token->token;
            $fcm_id = $request['fcm_id'];
            $model->upd('token_login',  ['token' => $token], ['fcm_id' => $fcm_id]);
        } else {
            $token = random_string('alnum', 30);
            
            $fcm_id = $request['fcm_id'];
          
            // ins log
            $hdata = array(
                'token' => $token,
                'fcm_id' => $fcm_id,
                'device' => $device,
            );
            $cek[0]['key'] = $hdata;
            // ins token auth
            $dtoken = array(
                'member_id' => $cek[0]['id'],
                'token' => $token,
                'user_agent' => $device,
            );
            $mtoken->ignore(true)->insert($dtoken);
        }
        $hdata = array(
            'token' => $token,
            'fcm_id' => $fcm_id,
            'device' => $device,
        );
        $cek[0]['key'] = $hdata;
        $res =  [
            'id' => $cek[0]['id'],
            'key' => $hdata,
            'data' => $cek,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function updateFcm()
    {
        $request = $this->request->getVar();
        $headers = $this->request->headers();

        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();

        $cek_token = $model->getWhere('token_login', ['token' => $token])->getRow();

        $update_fcm = $model->upd('token_login',  ['token' => $token], ['fcm_id' => $request['fcm_id']]);

        $auth = $model->getWhere('token_login', ['token' => $token], null)->getRow();

        if ($update_fcm) {
            $res =  [
                'message' => 'Berhasil melakukan update fcm',
                'data' => $auth
            ];
        } else {
            $res =  [
                'message' => 'Gagal melakukan update fcm',
                'data' => $auth
            ];
        }
        return $this->respond($res, 200);
    }

    public function signout()
    {
        $mtoken = new AuthTokenModel();

        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $res = [
            "status" => 200,
            "messages" => "Berhasil Logout",
            "data" => []
        ];
        if ($mtoken->where('token', $token)->delete()) {
            return $this->respond($res, 200);
        }
        return $this->failResourceGone('Data tidak ditemukan');
    }

    private function sendEmail($to, $title, $message)
    {
        $email = \Config\Services::email();
        $email->setFrom('info@mitrarenov.com', 'info@mitrarenov.com');
        $email->setTo($to);
        $email->setSubject($title);
        $email->setMessage($message);

        if (! $email->send()) {
            return false;
        } else {
            // var_dump($email->printDebugger());die;
            return true;
        }
    }

    public function page_link()
	{
        $input = $this->request->getVar();
        $token = $input['token'];
		$link = "https://mitrarenov.page.link/resetpassword?token=$token";
        // echo $link;
		return redirect()->to($link);
	}

    public function send_email_reset()
    {
        // create token
        $token = random_string('alnum', 35);
        $res = array(
            'status' => 200,
            'token_reset' => $token,
        );

        $input = $this->request->getVar();
        // $data = ['telephone' => $input['email']];
        $dt = ['email' => $input['email'],];
        $mail = $input['email'];

        $model = new AuthModel();
        $mdl = new GeneralModel();
        $cek = $model->where($dt)->first();

        if (!$cek) {
            return $this->failNotFound('email tidak ditemukan dan belum terdaftar');
        }
        $mdl->ins('temp_reset_pass', ['member_id' => $cek['id'], 'token' => $token]);
        $message = '<h2>Reset Password</h2><p>Untuk melakukan reset password anda dapat klik link berikut <b><a href="https://mitrarenov.soldig.co.id/member/page_link?token=' . $token . '">Link reset</a></b> </p>';
        // $message = '<h2>Reset Password</h2><p>Untuk melakukan reset password anda dapat klik link berikut <b><a href="https://mitrarenov.soldig.co.id/resetpassword/' . $token . '">Link reset</a></b> </p>';
        $kirim = $this->sendEmail($mail, 'reset password', $message);

        if ($kirim) {
            return $this->respond($res, 200);
        } else {
            $res = [
                "status" => 500,
                "message" => "Ada kesalahan pada server , mohon coba lagi nanti",
                "data" => null
            ];
            return $this->respond($res, 500);
        }
    }

    public function profile()
    {
        $model = new AuthModel();
        $mdl = new GeneralModel();

        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $user = $mdl->getWhere('member_detail', ['member_id' => $id])->getRow();
        $user_temp = $mdl->getWhere('member', ['id' => $id])->getRow();
        if (!$user and !$user_temp) {
            return $this->respond('user tidak ditemukan', 500);
        }
        $url = base_url();
        $user->email = $user_temp->email;
        $user->path_image = $url . '' . '/public/images/pp/' . $user->photo;
        $res = [
            'data' => $user,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function resetPass()
    {
        $model = new AuthModel();
        $mdl = new GeneralModel();

        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mdl->getWhere('token_login', ['token' => $token])->getRow();
        $id = $cekUser->member_id;
        $input = $this->request->getVar();
        $oldcek = $model->Where('password', md5($input['old_password']))->first();
        
        if ($oldcek != null) {
            if ($input['password'] != $input['temp_pass']) {
                return $this->fail('Password tidak match , pastikan sama antar kedua nya');
            }
            
        } else {
            $res = [
                "status" => 500,
                "message" => "Pastikan anda memasukkan password anda yang masih berlaku dengan benar !",
                "data" => null
            ];
            return $this->respond($res, 500);
            // return  $this->fail('Pastikan anda memasukkan password anda yang masih berlaku dengan benar !');
        }
        if($input['password'] != null && $input['password'] != ''){
            $data = ['password' => md5($input['password'])];
            $exc = $mdl->upd('member', ['id' => $id], $data);
            if (!$exc) {
                // return  $this->fail('Reset Password gagal !');
                $res = [
                    "status" => 500,
                    "message" => "Reset Password gagal !",
                    "data" => null
                ];
                return $this->respond($res, 500);
            }
        }else{
            $res = [
                "status" => 500,
                "message" => "Password tidak boleh kosong !",
                "data" => null
            ];
            return $this->respond($res, 500);
        }

        return $this->respond($data, 200);
    }

    public function resetPass_luar()
    {
        $model = new AuthModel();
        $mdl = new GeneralModel();

        $headers = $this->request->headers();
        $token = $headers["Auth-Token-Reset"]->getValue();

        $cekUser = $mdl->getWhere('temp_reset_pass', ['token' => $token])->getRow();
        $id = $cekUser->member_id;

        $input = $this->request->getVar();
        if ($input['password'] != $input['temp_pass']) {
            return $this->fail('Password tidak match , pastikan sama antar kedua nya');
        }
        $data = ['password' => md5($input['password'])];
        $w = array('id' => $id);

        $exc = $mdl->upd('member', $w, $data);

        if (!$exc) {
            return  $this->fail('Reset Password gagal !');
        }

        $msg = array(
            'status' => 200,
            'data' => $data
        );

        return $this->respondUpdated($msg);
    }

    public function updReferal()
    {
        $auth = new AuthModel();
        $authdt = new AuthDetailModel();
        $model = new GeneralModel();
        $cek = $authdt->where('referal', '')->get()->getResult();
        foreach ($cek as $key => $value) {
            $singkatan = str_replace(' ', '', $value->name);
            $fourname = substr($singkatan, 0, 4);
            $last = $auth->hitung();
            $referal = '' . $fourname . '' . $last;
            $model->upd('member_detail', ['id' => $value->id], ['referal' => $referal]);
        }

        $msg = array(
            'status' => 200,
        );

        return $this->respondUpdated($msg);
    }

    public function register()
    {
        helper(['form', 'url']);

        $auth = new AuthModel();
        $dtl = new AuthDetailModel();
        $request = $this->request->getVar();
        // var_dump($request);die;
        try {
            if (!$this->validate([
                'name' => 'required',
                'telephone' => 'required',
                'email'    => 'required||valid_email',
                'password' => 'required||min_length[8]',
            ])) {
                return $this->fail('pastikan semuanya benar');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $cek = $auth->where('email', $request['email'])->first();

        $cekp = $dtl->where('telephone', $request['phone'])->first();

        if ($cek) {
            $res = [
                "status" => 500,
                "message" => "Email sudah terdaftar",
                "data" => null
            ];
            return $this->respond($res, 500);
        }
        
        if ($cekp) {
            $res = [
                "status" => 500,
                "message" => "Nomor telpon sudah terdaftar",
                "data" => null
            ];
            return $this->respond($res, 500);
        }

        $singkatan = str_replace(' ', '', $request['name']);
        $fourname = substr($singkatan, 0, 4);
        $last = $auth->hitung();

        $referal = '' . $fourname . '' . $last;

        $data = array(
            'usergroup_id' => 5,
            'email' => $request['email'],
            'password' => md5($request['password']),
            'created_by' => 1,
            'modified_by' => 1,
        );

        if ($auth->ins('member', $data)) {
            $cekk = $auth->where('email', $request['email'])->first();
            $datak = array(
                'member_id' => $cekk['id'],
                'name' => $request['name'],
                'referal' => $referal,
                'photo' => null,
                'telephone' => $request['phone'],
                'handphone' => $request['phone'],
                'created_by' => $cekk['id'],
                'modified_by' => 1,
            );
            $all = array(
                "status" => 200,
                "messages" => "sukses",
                'data' => [
                    'akun' => $data,
                    'profile' => $datak
                ],
                'akun' => $data,
                'profile' => $datak
            );
        }
        $q = $auth->ins('member_detail', $datak);
        $dmsg = [
            'email' => $request['email'],
            'nama' => $request['name'],
            'stat' => 'baru'
        ];
        if ($q) {
            $this->sendEmail($request['email'], 'Akun APLIKASI MITRARENOV telah dibuat', view('v_email_register', $dmsg));
            $res = [
                "status" => 200,
                "message" => "Register Gagal !",
                "data" => $all
            ];
            return $this->respond($res, 200);
            // return $this->respondCreated($all);
        }
        $res = [
            "status" => 500,
            "message" => "Register Gagal !",
            "data" => null
        ];
        return $this->respond($res, 500);
    }

    public function uploadImage()
    {
        helper(['form']);
        $file = $this->request->getFile('image');

        $mtoken = new GeneralModel();

        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $cekUser = $mtoken->getWhere('token_login', ['token' => $token])->getRow();
        $id = (int)$cekUser->member_id;

        $input = $this->request->getVar();
        if (empty($file)) {
            // var_dump('atas2_masuk');
            $data = [
                'name' => $input['name'],
                'telephone' => $input['telephone'],
                'handphone' => $input['telephone'],
            ];
            // var_dump($model->updateData('member_detail', ['member_id' => $id], $data));die;
            $dt = ['email' => $input['email']];
            
            $model = new DboModel();
            //   var_dump($model->updateData('member_detail', ['member_id' => $id], $data));die;

            $model->updateData('member', ['id' => $id], $dt);
            if ($model->updateData('member_detail', ['member_id' => $id], $data)) {

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Profile updated without image success!',
                    'data' => []
                ];
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => 'Failed to update profile',
                    'data' => []
                ];
            }
        } else {
            // Renaming file before upload
            $profile_image = $file->getName();
            $temp = explode(".", $profile_image);
            $newfilename = round(microtime(true)) . '.' . end($temp);

            if ($file->move('./public/images/pp', $newfilename)) {

                $data = [
                    'name' => $input['name'],
                    'telephone' => $input['telephone'],
                    'handphone' => $input['telephone'],
                    'photo' => $newfilename,
                ];
                // var_dump($model->updateData('member_detail', ['member_id' => $id], $data));die;
                $dt = ['email' => $input['email']];

                $model = new DboModel();
                //   var_dump($model->updateData('member_detail', ['member_id' => $id], $data));die;

                $model->updateData('member', ['id' => $id], $dt);
                if ($model->updateData('member_detail', ['member_id' => $id], $data)) {

                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => 'File uploaded successfully',
                        'data' => ["file_path" => "./public/images/pp/" . $newfilename]
                    ];
                } else {

                    $response = [
                        'status' => 500,
                        'error' => true,
                        'message' => 'Failed to save image',
                        'data' => []
                    ];
                }
            } else {

                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => 'Failed to upload image',
                    'data' => []
                ];
            }
        }
        return $this->respond($response, 200);
    }
}
