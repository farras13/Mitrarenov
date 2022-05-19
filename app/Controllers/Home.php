<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\DboModel;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;

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

        $data['artikel'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->orderBy('created', 'DESC')->get()->getResult();
        $data['testimoni'] = $this->model->getAll('testimoni')->getResult();
        $data['promo'] = $this->model->getWhere('promomobile', ['is_publish' => 0])->getResult();
        $data['galery'] = $this->model->getAll('gallery_pekerjaan')->getResult();
        $data['merawat'] = $this->model->getAll('merawat')->getResult();
        $data['design_rumah'] = $this->model->getAll('design_rumah')->getResult();
        $data['partner'] = $this->model->getOrderBy('partner', 'position', 'asc')->getResult();
        $data['lokasi'] = $this->model->getAll('location')->getResult();
        $data['kategori'] = $this->model->getWhere('category', ['id !=' => 3])->getResult();
        $data['membangun'] = $this->model->getWhere('product', ['category_id' => 1])->getResult();
        $data['renovasi'] = $this->model->getWhere('product', ['category_id' => 2])->getResult();

        // var_dump($temp_lokasi);
        return view('index', $data);
    }

    public function artikel()
    {
        $model = new ArtikelModel();
        $key = $this->request->getVar();

        if ($key != null) {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
        } else {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->orderBy('created', 'DESC')->paginate(5, 'berita');
        }
        $data['kategori'] = $model->kategori();
        $data['hot'] = $model->orderBy('created', 'ASC')->hot();
        $data['pager'] = $model->pager;
        $data['key'] = $key;

        return view("artikel", $data);
    }

    public function d_artikel($id)
    {
        $model = new ArtikelModel();
        $key = $this->request->getVar();
        $data['kategori'] = $model->kategori();
        $tagline = $model->where('id', $id)->get()->getRow();

        $data['terkait'] = $model->terkait($tagline->tagline);
        if ($key != null) {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
            $data['hot'] = $model->orderBy('created', 'ASC')->hot();
            $data['pager'] = $model->pager;
            $data['key'] = $key;
            return view("artikel", $data);
        } else {
            $data['berita'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->find($id);
            $data['hot'] = $model->orderBy('created', 'ASC')->hot();
            return view("artikel-detail", $data);
        }
    }

    public function d_promo($id)
    {
        $model = new GeneralModel();
        $w = array('id' => (int)$id, 'is_publish' => 0);
        $data['promo'] = $model->getWhere('promomobile', $w)->getRow();

        return view("promo-detail", $data);
    }

    public function gallery()
    {
        $data['galery'] = $this->model->getAll('gallery_pekerjaan')->getResult();
        return view("promo-detail", $data);
    }

    // Login Register
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
            $model = new AuthModel();
            $models = new AuthDetailModel();

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
            $model->save($data);

            $last = $this->model->lastId('member', 1)->getRow();
            $datas = [
                'member_id'    => $last->id,
                'name'    => $this->request->getVar('name'),
                'telephone' => $this->request->getVar('phone')
            ];
            $models->save($datas);
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
        $model = new GeneralModel();
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
        $cek = $model->getWhere('temp_reset_pass', $where)->getRow();
        $w = array('id' => $cek->member_id);
        $data = array('password' => md5($pass));
        $update = $model->upd('member', $w, $data);

        if (!$update) {
            $session->setFlashdata('toast', 'error:Update Password Gagal !');
            return redirect()->back()->withInput();
        }

        $model->del('temp_reset_pass', ['token' => $token]);
        $session->setFlashdata('toast', 'success:Update Password berhasil !');
        return redirect()->to('member/login');
    }

    public function sendEmail()
    {
        helper('text');
        $session = session();
        $model = new AuthModel();
        $mdl = new GeneralModel();

        $token = random_string('alnum', 35);
        $res = array('token_reset' => $token,);

        $input = $this->request->getVar();
        $data = ['email' => $input['email'],];
        $to = $input['email'];

        $cek = $model->where($data)->first();

        if (!$cek) {
            $session->setFlashdata('toast', 'error:Email belum terdaftar !');
            return redirect()->back()->withInput();
        }
        $mdl->ins('temp_reset_pass', ['member_id' => $cek['id'], 'token' => $token]);
        $title = "Reset Password";
        $message = '<h2>Reset Password</h2><p>Untuk melakukan reset password anda dapat klik link berikut <b><a href="' . base_url('lupa_password') . '/' . $token . '">Link reset</a></b> </p>';

        $email = \Config\Services::email();
        $email->setFrom('noreply@mitrarenov.com', 'noreply@mitrarenov.com');
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
        $model = new GeneralModel();
        $request = $this->request->getVar();

        $w = array(
            'email' => $request['email'],
            'password' => md5($request['password']),
        );

        $cek = $model->getWhere('member', $w)->getRow();
        if (!$cek) {
            session()->setFlashdata('toast', 'error:Email atau password anda salah !');
            return redirect()->to('member/login');
        }

        $cek_detail = $model->getWhere('member_detail', ['member_id' => $cek->id])->getRow();

        $ctoken = $model->getWhere('token_login', ['member_id' => $cek->id])->getRow();
        $model->upd('member', ['id' => $cek->id], ['last_login' => time()]);

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

    // ORDER PROJECT
    public function order()
    {
        $sess = session();
        $req = $this->request->getVar();
        $type = $req['type'];
        $jenis = $req['jenis'];
        $data['type'] = $type;
        $data['jenis'] = $jenis;
        $data['tipe_rumah'] = $this->model->getAll('tipe_rumah')->getResult();
        if ($type == 2) {
            $spek = $this->model->getWhere('product', ['paket_name' => $jenis])->getResult();
            foreach ($spek as $key => $value) {
                $value->type_price = $value->paket_name;
                $value->product_price = $value->start_from_text;
                $value->spesifikasi = $value->spesifikasi_renov;
            }
        } else {
            $spek = $this->model->getAll('product_price')->getResult();
        }
        $data['spek'] = $spek;

        return view('order', $data);
    }
    public function order_desain()
    {
        $sess = session();
        $input = $this->request->getVar();
        $tdate = date('Y-m-d');
        $date = strtotime($tdate);
        $id = $sess->get('user_id');

        if (empty($id)) {
            redirect('login');
        }

        $rules = [
            "customRadioInline" => "required",
            "ruang_keluarga" => "required",
            "kamar_tidur" => "required",
            "kamar_mandi" => "required",
            "dapur" => "required",
            "luas" => "required",
            "deskripsi" => "required",
            "alamat" => "required",
            "jangka_waktu" => "required",
            "spek" => "required",
            "nama_lengkap" => "required",
            "telepon" => "required",
            "email" => "required",
            "latitude" => "required",
            "longitude" => "required",
            "metodpay" => "required",
        ];
        $msg = [
            'gambar_rumah' => [
                'uploaded' => 'Harus Ada File yang diupload',
                'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
                'max_size' => 'Ukuran File Maksimal 2 MB'
            ],
            'denah' => [
                'uploaded' => 'Harus Ada File yang diupload',
                'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
                'max_size' => 'Ukuran File Maksimal 2 MB'
            ]
        ];

        if (!$this->validate($rules, $msg)) {
            session()->setFlashdata('toast', 'error:' . $this->validator->getErrors());
            return redirect()->back()->withInput();
        } else {
            $jenis_order = $input['jenis_order'];
            $type_order = $input['tipe_order'];
            $tipe_rumah = $input['customRadioInline'];

            $file_rumah = $this->request->getFile('gambar_rumah');
            $file_denah = $this->request->getFile('denah');

            $path = "./public/images/desain_rumah_user";
            $uploadImgdenah = $this->uploadImage($file_denah, $path);
            $path_uploadImg = "./public/images/projek";
            $uploadImg = $this->uploadImage($file_rumah, $path_uploadImg);

            if ($uploadImg != null) {

                $img_rumah = $uploadImg['data']['file_name'];
            }

            if ($uploadImgdenah != null) {

                $denah = $uploadImgdenah['data']['file_name'];
            }

            $temp_produk = $this->model->getData('product', ['id' => $type_order])->getRow();
            if ($temp_produk == null) {
                $total = $input['totalHarga'];
            } else {
                $price = $temp_produk->price;
                $total = $price * $input['luas'];
            }

            $insert = [
                'project_number' => date("dmY", time()) . "" . rand(10, 100),
                'type' => $jenis_order,
                'total' => $total,
                'alamat_pengerjaan' => $input['alamat'],
                'catatan_alamat' => $input['alamat'],
                'luas' => $input['luas'],
                'description' => $input['deskripsi'],
                'latitude' => $input['lat'],
                'longitude' => $input['long'],
                'metode_payment' => $input['metodpay'],
                'status_project' => 'quotation',
                'image_upload' => $img_rumah,
                'marketing_name' => $input['marketing'],
                'kode_referal' => $input['promo'],
                'kode_promo' => $input['referal'],
                'created' => time()
            ];

            $insert_data = [
                'name' => $input['nama_lengkap'],
                'address' => $input['alamat'],
                'email' => $input['email'],
                'phone' => $input['telepon'],
                'created' => time(),
                'member_id' => $id
            ];

            $idd = $this->m->lastId('projects_desain')->getRow()->id;

            $insert_detail = [
                'product_id' => $input['type'],
                'product_price_id' => $input['spek'],
                'desain_id' => (int)$idd + 1
            ];

            $insert_design = [
                'tipe_rumah' => $tipe_rumah,
                'ruang_keluarga' => $input['ruang_keluarga'],
                'kamar_tidur' => $input['kamar_tidur'],
                'kamar_mandi' => $input['kamar_mandi'],
                'dapur' => $input['dapur'],
                'denah' => $denah,
                'created' => time()
            ];

            $this->m->insert('projects', $insert);
            $this->m->insert('project_data_customer', $insert_data);
            $this->m->insert('projects_desain', $insert_design);
            $this->m->ins('projects_detail', $insert_detail);

            session()->setFlashdata('toast', 'success:Selamat pengajuan Order berhasil dikirim !.');
            return redirect()->to('home');
        }
    }
    public function order_non()
    {
        $sess = session();
        $tdate = date('Y-m-d');
        $input = $this->request->getVar();
        $date = strtotime($tdate);
        $id = $sess->get('user_id');

        if (empty($id)) {
            redirect('login');
        }

        $jenis_order = $input['jenis_order'];
        $type_order = $input['tipe_order'];
        $spek = $input['spek'];

        $file_rumah = $this->request->getFile('gambar_rumah');

        $temp_produk = $this->model->getWhere('product', ['id' => $spek])->getRow();
        if ($temp_produk == null) {
            $total = $input['totalHarga'];
        } else {
            $price = $temp_produk->price;
            $total = $price * $input['luas'];
        }

        $path_uploadImg = "./public/images/projek";
       
        $uploadImg = $this->uploadImage($file_rumah, $path_uploadImg);
        
        if ($uploadImg != null) {
            $path_image = $path_uploadImg;
            $json_text = $uploadImg['message'];
            $img_rumah = $uploadImg['data']['file_name'];
        }

        $insert = [
            'project_number' => date("dmY", time()) . "" . rand(10, 100),
            'type' => $jenis_order,
            'total' => $total,
            'alamat_pengerjaan' => $input['alamat'],
            'catatan_alamat' => $input['alamat'],
            'luas' => $input['luas'],
            'description' => $input['deskripsi'],
            'latitude' => $input['lat'],
            'longitude' => $input['long'],
            'metode_payment' => $input['metodpay'],
            'status_project' => 'quotation',
            'image_upload' => $img_rumah,
            'marketing_name' => $input['marketing'],
            'kode_referal' => $input['promo'],
            'kode_promo' => $input['referal'],
            'created' => time()
        ];

        $insert_data = [
            'name' => $input['nama_lengkap'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'phone' => $input['telepon'],
            'created' => time(),
            'member_id' => $id
        ];


        $insert_detail = [
            'product_id' => $input['type'],
            'product_price_id' => $input['spek'],
            'desain_id' => 0
        ];

        $this->model->ins('projects', $insert);
        $this->model->ins('project_data_customer', $insert_data);
        $this->model->ins('projects_detail', $insert_detail);

        session()->setFlashdata('toast', 'success:Selamat pengajuan Order berhasil dikirim !.');
        return redirect()->to('home');
    }

    public function desain()
    {
        $sess = session();
        $id = $sess->get('id');

        if (empty($id)) {
            redirect('Login');
        }
    }
    // TRANSAKSI


    // Simulasi-kpr
    public function kpr()
    {
    }

    public function simulasi()
    {
        $model = new DboModel();
        $data['prov'] = $model->getProv();
        // var_dump($data);die;
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
        echo view("tentang-kami");
    }

    // Hubungi Kami
    public function Hubungi()
    {
        $model = new DboModel();
        $data['lokasi'] = $this->model->getAll('location')->getResult();
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
            'created' => date("Y-m-d H:i:s"),
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
        return redirect()->to('kontak');
    }

    // Akun or setting
    public function akun()
    {
        $sess = session();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view("projek_berlangsung", $data);
    }

    public function edit_profile()
    {
        $sess = session();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
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
        $nameNew = $date . substr($profile_image, 0, 5) . rand(0, 20);
        // var_dump($newfilename);die;
        if (!$file->isValid()) {
            return $response = $file->getErrorString();
        }

        if ($file->move($path, $nameNew)) {
            $response = [
                'data' => [
                    "file_name" => $profile_image,
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
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
    }

    public function changePass()
    {
        $sess = session();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
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
            $session->setFlashdata('toast', 'errorr:Password lama anda salah!');
            return redirect()->back()->withInput();
        }

        if ($newK != $new) {
            $session->setFlashdata('toast', 'errorr:Password dan password konfrimasi tidak match!');
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
        $data['tentang'] = $model->getWhere('footer', ['id' => 1])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('tentang-mitra', $data);
    }

    public function qa()
    {
        $sess = session();
        $model = new GeneralModel();
        $data['tentang'] = $model->getWhere('footer', ['id' => 7])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('qa', $data);
    }

    public function snk()
    {
        $sess = session();
        $model = new GeneralModel();
        $data['tentang'] = $model->getWhere('footer', ['id' => 4])->getRow();
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        echo view('snk', $data);
    }
}
