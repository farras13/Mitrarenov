<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\DboModel;
use App\Models\AuthDetailModel;
use App\Models\AuthModel;
use App\Models\GeneralModel;
use App\Models\PortoModel;

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
        $data['alur'] = $this->model->getAll('rules')->getResult();
        $data['keunggulan'] = $this->model->getAll('keunggulan')->getResult();
        $data['artikel'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by', 'left')->where('is_publish', '0')->orderBy('created', 'DESC')->get()->getResult();
        $data['testimoni'] = $this->model->getAll('testimoni')->getResult();
        $data['promo'] = $this->model->getWhere('promomobile', ['is_publish' => 0], null, 'posisi', 'asc')->getResult();
        $data['galery'] = $this->model->getAll('gallery_pekerjaan')->getResult();
        $data['merawat'] = $this->model->getAll('merawat', 5)->getResult();
        $data['design_rumah'] = $this->model->getAll('design_rumah')->getResult();
        $data['liputan'] = $this->model->getAll('liputan')->getResult();
        $data['partner'] = $this->model->getOrderBy('partner', 'position', 'asc')->getResult();
        $data['lokasi'] = $this->model->getAll('location')->getResult();
        $data['kategori'] = $this->model->getWhere('category', ['id !=' => 3])->getResult();
        $data['membangun'] = $this->model->getWhere('product', ['category_id' => 1])->getResult();
        $data['renovasi'] = $this->model->getWhere('product', ['category_id' => 2])->getResult();

        // var_dump($temp_lokasi);
        return view('index', $data);
    }

    public function portofolio()
    {
        $model = new PortoModel();

        $data['porto'] = $model->select('merawat.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = merawat.created_by')->paginate(12, 'berita');
        $data['pager'] = $model->pager;

        return view('portofolio', $data);
    }

    public function detail_porto($id)
    {
        $data['porto'] = $this->model->getWhere('merawat', ['id' => $id])->getRow();
        $id = $data['porto']->created_by;
        $data['penulis'] = $this->model->getWhere('member_detail', ['member_id' => $id])->getRow();
        $data['lain'] = $this->model->getWhere('merawat', ['id !=' => $id], 4)->getResult();
        $data['gambar'] = $this->model->getWhere('gambar_portofolio', ['porto_id' => $data['porto']->id])->getResult();
        return view('porto_detail', $data);
    }

    public function artikel()
    {
        $model = new ArtikelModel();
        $key = $this->request->getVar();

        if ($key['cari'] != null) {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
        } else {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->orderBy('created', 'DESC')->paginate(5, 'berita');
        }
        $data['kategori'] = $this->model->getAll('news_category')->getResult();
        $data['hot'] = $model->orderBy('created', 'ASC')->hot();
        $data['pager'] = $model->pager;
        $data['key'] = $key['cari'];

        return view("artikel", $data);
    }

    public function kategori($name)
    {
        $model = new ArtikelModel();
        $key = $this->request->getVar();

        $kat = str_replace('-', ' ', $name);

        $kategori = $this->model->getWhere('news_category', ['category' => $kat])->getRow();

        if ($key['cari'] != null) {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
        } else {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('news_category', $kategori->id_news_category)->where('is_publish', '0')->orderBy('created', 'DESC')->paginate(5, 'berita');
            if ($data['terbaru'] == null) {
                $data['artikel'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->where('is_publish', '0')->orderBy('created', 'DESC')->paginate(5, 'berita');
            }
        }
        $data['kategori'] =  $this->model->getAll('news_category')->getResult();
        $data['judul'] = $kat;
        $data['hot'] = $model->orderBy('created', 'ASC')->hot();
        $data['pager'] = $model->pager;
        $data['key'] = $key['cari'];

        return view("artikel-kategori", $data);
    }

    public function d_artikel($id)
    {
        $model = new ArtikelModel();
        $key = $this->request->getVar();
        $data['kategori'] = $model->kategori();
        $tagline = $model->where('id', $id)->get()->getRow();

        $data['terkait'] = $model->terkait($tagline->tagline);
        if ($key['cari'] != null) {
            $data['terbaru'] = $model->select('news.*, member_detail.name as penulis')->join('member_detail', 'member_detail.member_id = news.created_by')->like('title', $key['cari'])->orderBy('created', 'DESC')->paginate(5, 'berita');
            $data['hot'] = $model->orderBy('created', 'ASC')->hot();
            $data['pager'] = $model->pager;
            $data['key'] = $key['cari'];
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

    public function chat()
    {
        echo view("percakapan");
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
                'member_id' => $last->id,
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
    public function sendEmailOrder()
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

        // $mdl->ins('temp_reset_pass', ['member_id' => $cek['id'], 'token' => $token]);
        $title = "Order Mitrarenov";
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
            'user_phone'     => $cek_detail->telephone,
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
        $data['nama'] = $sess->get('user_name');
        $data['phone'] = $sess->get('user_phone');
        $data['email'] = $sess->get('user_email');
        $data['tipe_rumah'] = $this->model->getAll('tipe_rumah')->getResult();

        $cat = $this->model->getWhere('category', ['category_name' => $jenis])->getRow();
        if ($cat == null) {
            $produk = $this->model->getWhere('product', ['paket_name' => $jenis])->getRow();
        } else {
            $produk = $this->model->getWhere('product', ['category_id' => $cat->id])->getRow();
        }

        $spek = $this->model->getWhere('product_price', ['product_id' => $produk->id])->getResult();
        if ($spek == null) {
            $spek = $this->model->getWhere('product_price', ['product_id' => $produk->category_id])->getResult();
            if ($spek == null) {
                $spek = $this->model->getWhere('product', ['paket_name' => $jenis])->getResult();
                foreach ($spek as $key => $value) {
                    $value->type_price = $value->paket_name;
                    $value->product_price = $value->start_from_text;
                    $value->spesifikasi = $value->spesifikasi_renov;
                }
            }
        }

        $data['spek'] = $spek;

        return view('order', $data);
    }

    public function order_desain()
    {
        $auth = new AuthModel();
        $sess = session();
        $login = $sess->get('logged_in');
        $id = 0;
        $input = $this->request->getVar();

        if ($sess->get('user_id') != null) {
            $id = $sess->get('user_id');
            $status_cust = 1;
        } else {
            $status_cust = 0;
            $cek_member = $this->model->getWhere('member', ['email' => $input['email']])->getRow();
            if (empty($cek_member)) {
                $insert_h['usergroup_id'] = 5;
                $insert_h['email'] = $input['email'];

                $insert_h['password'] = md5('123456');
                $insert_h['created'] = time();
                $insert_h['last_login'] = time();
                $insert_h['created_by'] = 2;
                $query_h = $this->model->insB('member', $insert_h);
                $id = $this->model->lastId('member', 1)->getRow()->id;

                $singkatan = str_replace(' ', '', $input['name']);
                $fourname = substr($singkatan, 0, 4);
                $last = $auth->hitung();

                $referal = '' . $fourname . '' . $last;

                $insert_d['name'] = $input['nama_lengkap'];
                $insert_d['telephone'] = $input['telepon'];
                $insert_d['handphone'] = $input['telepon'];
                $insert_d['member_id'] = $id;
                $insert_d['referal'] = $referal;
                $insert_d['created'] = time();
                $insert_d['city_id'] = 0;
                $insert_d['address'] = $input['alamat'];
                $insert_d['created_by'] = 2;
                $query_d = $this->model->ins('member_detail', $insert_d);
            } else {
                $id = $cek_member->id;
                $this->model->upd('member', ['id' => $id], ['last_login' => time()]);
            }
        }
        $tdate = date('Y-m-d');
        $date = strtotime($tdate);


        $jenis_order = $input['jenis_order'];
        // var_dump($jenis_order);die;
        $type_order = $input['tipe_order'];
        $tipe_rumah = $input['customRadioInline'];

        $temp = 0;
        $cek_area = $this->model->getAll('area')->getResult();
        $area = "";
        foreach ($cek_area as $c) {
            if (strpos(strtolower($input['city']), strtolower('bks')) > -1) {
                $input['city'] = "Bekasi";
            }
            if (strpos(strtolower($input['city']), strtolower($c->nama_area)) > -1) {
                $temp += 1;
                $area = $c->id_area;
            }
        }

        // var_dump($cek_area);die;
        if ($temp < 1) {
            session()->setFlashdata('toast', 'error:Maaf area anda belum kami jangkau!.');
            return redirect()->back()->withInput();
        }

        $file_rumah = $this->request->getFile('gambar_rumah');
        // $file_denah = $this->request->getFile('denah');

        // $path = "./public/images/desain_rumah_user";
        // $uploadImgdenah = $this->uploadImage($file_denah, $path);
        $path_uploadImg = "./public/images/projek";
        $uploadImg = $this->uploadImage($file_rumah, $path_uploadImg);

        if ($uploadImg != null) {
            $img_rumah = $uploadImg['data']['file_name'];
        }

        // if ($uploadImgdenah != null) {
        //     $denah = $uploadImgdenah['data']['file_name'];
        // }

        $temp_produk = $this->model->getWhere('product', ['paket_name' => $jenis_order])->getRow();
        $total = $input['totalHarga'];

        $db = db_connect();

        $noprojek = $db->query("SELECT COUNT(id) as hitung FROM projects WHERE DATE_FORMAT(FROM_UNIXTIME(created), '%Y%m') = EXTRACT(YEAR_MONTH FROM CURRENT_DATE()) ")->getRow();
        $temp_nopro = (int)$noprojek->hitung + 1;
        $coun = strlen($noprojek->hitung);
        if ($coun == 1) {
            $ht = '0000' . '' . $temp_nopro;
        } elseif ($coun == 2) {
            $ht = '000' . '' . $temp_nopro;
        } elseif ($coun == 3) {
            $ht = '00' . '' . $temp_nopro;
        } elseif ($coun == 4) {
            $ht = '0' . '' . $temp_nopro;
        } else {
            $ht = $temp_nopro;
        }

        $insert = [
            'project_number' => date("dmY", time()) . "" . $ht,
            'type' => $jenis_order,
            'status_project' => 'quotation',
            'luas' => $input['luas'],
            'alamat_pengerjaan' => $input['alamat'],
            'description' => $input['deskripsi'],
            'image_upload' => $img_rumah,
            'total' => $total,
            'total_real' => $total,
            'marketing_name' => $input['marketing'],
            'metode_payment' => $input['metodpay'],
            'created' => time(),
            'latitude' => $input['lat'],
            'longitude' => $input['long'],
            'catatan_alamat' => $input['catatan_alamat'],
            'kode_referal' => $input['promo'],
            'kode_promo' => $input['referal'],
            'id_area' => $area,
            'device' => 3
        ];
        $temp_id_projek = $this->model->lastId('projects', 1)->getRow()->id;
        $id_projek = $temp_id_projek + 1;
        $insert_data = [
            'project_id' => $id_projek,
            'name' => $input['nama_lengkap'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'phone' => $input['telepon'],
            'created' => time(),
            'member_id' => $id,
            'device' => 3
        ];

        $idd = $this->model->lastId('projects_desain')->getRow()->id;

        if ($temp_produk == null) {
            $query_cat = $this->model->getWhere('category', ['category_name' => $jenis_order])->getRow();
            $temp_produk->id = $query_cat->id;
        }

        if ($temp_produk->price != 0) {
            $produkprice = 0;
        } else {
            $produkprice =  $input['spek'];
        }

        $insert_detail = [
            'project_id' => $id_projek,
            'product_id' => $temp_produk->id,
            'product_price_id' => $produkprice,
            'desain_id' => (int)$idd + 1
        ];

        $insert_design = [
            'tipe_rumah' => $tipe_rumah,
            'created' => date('Y-m-d H:i:s')
        ];

        // var_dump($insert);die;

        $this->model->ins('projects', $insert);
        $this->model->ins('project_data_customer', $insert_data);
        $this->model->ins('projects_desain', $insert_design);
        $this->model->ins('projects_detail', $insert_detail);

        if ($status_cust == 1) {
            $emailData = array(
                'from' => 'noreply@mitrarenov.com',
                'name' => 'noreply@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Permintaan Jasa di Mitrarenov.com"
                // 'template' => $this->load->view('email_submit_job', $data, true)              
            );
        } else {
            $emailData = array(
                'from' => 'noreply@mitrarenov.com',
                'name' => 'noreply@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Informasi Akun & Permintaan Jasa di Mitrarenov.com"
                // 'template' => $this->load->view('email_submit_job', $data, true)              
            );
        }

        $this->sendEmailAktifasi($emailData, $status_cust);

        $data2 = array(
            'template' => $temp,
            'nama' => $input['nama_lengkap'],
            'no_telp' => $input['telepon'],
            'project_number' => $insert['project_number'],
            'nama_tukang' => '',
            'hp_tukang' => '',
            'subject' => "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'],
            'ins_project' => $id_projek,
            'product_id' => $insert_detail['product_id'],
            'provinceVal' => $insert['longitude'] . "," . $insert['latitude'],
            'catatan_alamat' => $insert['catatan_alamat'],
            'marketing_name' => $insert['marketing_name']
        );

        $decode_tukang = $this->model->findAllTukang($area);

        $subject_tukang = "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'];
        foreach ($decode_tukang as $key => $d2) {
            if ($d2->email_tukang !== null or $d2->email_tukang !== '') {

                $email = \Config\Services::email();
                $email->setFrom('noreply@mitrarenov.com', 'noreply@mitrarenov.com');
                $email->setTo($d2->email_tukang);
                $email->setSubject($subject_tukang);
                $email->setMessage($this->contenttukang($data2, $d2));

                if (!$email->send()) {
                    // Generate error
                    //echo "Email is not sent!!";
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'gagal');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                } else {
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'terkirim');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                }
            }
        }

        session()->setFlashdata('toast', 'success:Selamat pengajuan Order berhasil dikirim !.');
        return redirect()->to('order/sukses');
    }

    public function order_non()
    {
        $sess = session();
        $auth = new AuthModel();
        $tdate = date('Y-m-d');
        $input = $this->request->getVar();
        $date = strtotime($tdate);
        $login = $sess->get('logged_in');
        $id = 0;
        if ($sess->get('user_id') != null) {
            $status_cust = 1;
            $id += $sess->get('user_id');
        } else {
            $status_cust = 0;
            $cek_member = $this->model->getWhere('member', ['email' => $input['email']])->getRow();
            if (empty($cek_member)) {
                $insert_h['usergroup_id'] = 5;
                $insert_h['email'] = $input['email'];

                $insert_h['password'] = md5('123456');
                $insert_h['created'] = time();
                $insert_h['last_login'] = time();
                $insert_h['created_by'] = 2;
                $query_h = $this->model->insB('member', $insert_h);
                $id = $this->model->lastId('member', 1)->getRow()->id;

                $singkatan = str_replace(' ', '', $input['name']);
                $fourname = substr($singkatan, 0, 4);
                $last = $auth->hitung();

                $referal = '' . $fourname . '' . $last;

                $insert_d['name'] = $input['nama_lengkap'];
                $insert_d['telephone'] = $input['telepon'];
                $insert_d['handphone'] = $input['telepon'];
                $insert_d['member_id'] = $id;
                $insert_d['referal'] = $referal;
                $insert_d['created'] = time();
                $insert_d['city_id'] = 0;
                $insert_d['address'] = $input['alamat'];
                $insert_d['created_by'] = 2;
                $query_d = $this->model->ins('member_detail', $insert_d);
            } else {
                $id = $cek_member->id;
                $this->model->upd('member', ['id' => $id], ['last_login' => time()]);
            }
        }

        // if ($login != TRUE) {
        //     redirect('login');
        // }

        $jenis_order = $input['jenis_order'];
        $type_order = $input['tipe_order'];
        $spek = $input['spek'];

        $temp = 0;
        $cek_area = $this->model->getAll('area')->getResult();
        $area = "";
        foreach ($cek_area as $c) {
            if (strpos(strtolower($input['city']), strtolower('bks')) > -1) {
                $input['city'] = "Bekasi";
            }
            if (strpos(strtolower($input['city']), strtolower($c->nama_area)) > -1) {
                $temp += 1;
                $area = $c->id_area;
            }
        }

        // var_dump($cek_area);die;
        if ($temp < 1) {
            session()->setFlashdata('toast', 'error:Maaf area anda belum kami jangkau!.');
            return redirect()->back()->withInput();
        }
        // $temp = str_replace('Kota', '', $input['city']);
        // $kota = str_replace(' ', '', $temp);
        // $cek_area = $this->model->getWhere('area', ['nama_area' => $kota])->getRow();
        // var_dump($cek_area);die;
        // if($cek_area == null){
        //     session()->setFlashdata('toast', 'error:Maaf area anda belum kami jangkau!.');
        //     return redirect()->to('order/sukses');
        // }
        $file_rumah = $this->request->getFile('gambar_rumah');

        $temp_produk = $this->model->getWhere('product', ['id' => $spek])->getRow();
        $total = $input['totalHarga'];
        // if ($temp_produk == null) {
        //     $total = $input['totalHarga'];
        // } else {
        //     $price = $temp_produk->price;
        //     $total = $price * $input['luas'];
        // }
        $path_uploadImg = "./public/images/projek";

        $uploadImg = $this->uploadImage($file_rumah, $path_uploadImg);

        if ($uploadImg != null) {
            $path_image = $path_uploadImg;
            $json_text = $uploadImg['message'];
            $img_rumah = $uploadImg['data']['file_name'];
        }

        $db = db_connect();
        $noprojek = $db->query("SELECT COUNT(id) as hitung FROM projects WHERE DATE_FORMAT(FROM_UNIXTIME(created), '%Y%m') = EXTRACT(YEAR_MONTH FROM CURRENT_DATE()) ")->getRow();
        $temp_nopro = (int)$noprojek->hitung + 1;
        $coun = strlen($noprojek->hitung);
        if ($coun == 1) {
            $ht = '0000' . '' . $temp_nopro;
        } elseif ($coun == 2) {
            $ht = '000' . '' . $temp_nopro;
        } elseif ($coun == 3) {
            $ht = '00' . '' . $temp_nopro;
        } elseif ($coun == 4) {
            $ht = '0' . '' . $temp_nopro;
        } else {
            $ht = $temp_nopro;
        }

        $insert = [
            'project_number' => date("dmY", time()) . "" . $ht,
            'type' => $jenis_order,
            'status_project' => 'quotation',
            'luas' => $input['luas'],
            'alamat_pengerjaan' => $input['alamat'],
            'description' => $input['deskripsi'],
            'image_upload' => $img_rumah,
            'total' => $total,
            'total_real' => $total,
            'marketing_name' => $input['marketing'],
            'metode_payment' => $input['metodpay'],
            'created' => time(),
            'latitude' => $input['lat'],
            'longitude' => $input['long'],
            'catatan_alamat' => $input['catatan_alamat'],
            'kode_referal' => $input['promo'],
            'kode_promo' => $input['referal'],
            'id_area' => $area,
            'device' => 3
        ];

        $temp_id_projek = $this->model->lastId('projects', 1)->getRow()->id;
        $id_projek = $temp_id_projek + 1;
        $insert_data = [
            'project_id' => $id_projek,
            'name' => $input['nama_lengkap'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'phone' => $input['telepon'],
            'created' => time(),
            'member_id' => $id,
            'device' => 3
        ];

        $idd = $this->model->lastId('projects_desain')->getRow()->id;

        $insert_detail = [
            'project_id' => $id_projek,
            'product_id' => $input['type'],
            'product_price_id' => $input['spek'],
            'desain_id' => (int)$idd + 1
        ];
        // var_dump($insert_detail);die;

        $this->model->ins('projects', $insert);
        $this->model->ins('project_data_customer', $insert_data);
        $this->model->ins('projects_detail', $insert_detail);

        if ($status_cust == 1) {
            $emailData = array(
                'from' => 'noreply@mitrarenov.com',
                'name' => 'noreply@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Permintaan Jasa di Mitrarenov.com"
                // 'template' => $this->load->view('email_submit_job', $data, true)              
            );
        } else {
            $emailData = array(
                'from' => 'noreply@mitrarenov.com',
                'name' => 'noreply@mitrarenov.com',
                'to' => $input['email'],
                'bcc' => "",
                'subject' => "Informasi Akun & Permintaan Jasa di Mitrarenov.com"
                // 'template' => $this->load->view('email_submit_job', $data, true)              
            );
        }

        $this->sendEmailAktifasi($emailData, $status_cust);

        $data2 = array(
            'template' => $temp,
            'nama' => $input['nama_lengkap'],
            'no_telp' => $input['telepon'],
            'project_number' => $insert['project_number'],
            'nama_tukang' => '',
            'hp_tukang' => '',
            'subject' => "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'],
            'ins_project' => $id_projek,
            'product_id' => $insert_detail['product_id'],
            'provinceVal' => $insert['longitude'] . "," . $insert['latitude'],
            'catatan_alamat' => $insert['catatan_alamat'],
            'marketing_name' => $insert['marketing_name']
        );

        $decode_tukang = $this->model->findAllTukang($area);

        $subject_tukang = "Permintaan Jasa di Mitrarenov atas nama " . $input['nama_lengkap'];
        foreach ($decode_tukang as $key => $d2) {
            if ($d2->email_tukang !== null or $d2->email_tukang !== '') {

                $email = \Config\Services::email();
                $email->setFrom('noreply@mitrarenov.com', 'noreply@mitrarenov.com');
                $email->setTo($d2->email_tukang);
                $email->setSubject($subject_tukang);
                $email->setMessage($this->contenttukang($data2, $d2));

                if (!$email->send()) {
                    // Generate error
                    //echo "Email is not sent!!";
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'gagal');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                } else {
                    $this->tambah_log_email_db('permintaan jasa', $d2->email_tukang, 'tukang', 'terkirim');
                    $this->tambah_log_email_db('permintaan jasa', 'info@mitrarenov.com', 'admin', 'terkirim');
                }
            }
        }

        session()->setFlashdata('toast', 'success:Selamat pengajuan Order berhasil dikirim !.');
        return redirect()->to('order/sukses');
    }

    public function desain()
    {
        $sess = session();
        $id = $sess->get('id');

        if (empty($id)) {
            redirect('Login');
        }
    }

    public function order_sukses()
    {
        $sess = session();
        $data['nama'] = $sess->get('user_name');
        $data['phone'] = $sess->get('user_phone');
        $data['email'] = $sess->get('user_email');
        return view('order-sukses', $data);
    }

    public function sendEmailAktifasi($emailData = array(), $tipe)
    {
        $temp = $this->model->getWhere('email_ebook', array('id' => '1'))->getResult();
        $email = \Config\Services::email();
        $email->setFrom('noreply@mitrarenov.com', 'noreply@mitrarenov.com');
        $email->setTo($emailData['to']);
        $email->setSubject($emailData['subject']);
        if ($tipe == 0) {
            $msg = $this->content3($temp, $emailData['to']);

            $email->setMessage($msg);
        } else {
            $msg = $this->content2($temp, $emailData['to']);
            $email->setMessage($msg);
        }

        if (!$email->send()) {
            $this->tambah_log_email_db('permintaan jasa', $emailData['to'], 'customer', 'gagal');
        } else {
            $this->tambah_log_email_db('permintaan jasa', $emailData['to'], 'customer', 'terkirim');
        }
    }

    private function content3($temp, $email)
    {
        return '
			<!DOCTYPE html>

			<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
			<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				
				<style type="text/css">
					body {
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						margin: 0 !important;
						width: 100% !important;
						-webkit-text-size-adjust: 100% !important;
						-ms-text-size-adjust: 100% !important;
						-webkit-font-smoothing: antialiased !important;
					}

					.tableContent img {
						border: 0 !important;
						display: block !important;
						outline: none !important;
					}

					a {
						color: #382F2E;
					}

					p, h1 {
						color: #382F2E;
						margin: 0;
					}

					p {
						text-align: left;
						color: #999999;
						font-size: 14px;
						font-weight: normal;
						line-height: 19px;
					}

					a.link1 {
						color: #382F2E;
					}

					a.link2 {
						font-size: 16px;
						text-decoration: none;
						color: #ffffff;
					}

					h2 {
						text-align: center;
						color: #222222;
						font-size: 19px;
						font-weight: normal;
					}

					div, p, ul, h1 {
						margin: 0;
					}

					.bgBody {
						background: #ffffff;
					}

					.bgItem {
						background: #ffffff;
					}

					.btn {
						background: darkorange;
						background-image: -webkit-linear-gradient(top, darkorange, darkorange);
						background-image: -moz-linear-gradient(top, darkorange, darkorange);
						background-image: -ms-linear-gradient(top, darkorange, darkorange);
						background-image: -o-linear-gradient(top, darkorange, darkorange);
						background-image: linear-gradient(to bottom, darkorange, darkorange);
						-webkit-border-radius: 28;
						-moz-border-radius: 28;
						border-radius: 28px;
						font-family: Arial;
						color: #ffffff;
						font-size: 20px;
						padding: 10px 20px 10px 20px;
						text-decoration: none;
					}

						.btn:hover {
							background: #3cb0fd;
							background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
							background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
							text-decoration: none;
						}
				</style>
				<script type="colorScheme" class="swatch active">
					{
					"name":"Default",
					"bgBody":"ffffff",
					"link":"382F2E",
					"color":"999999",
					"bgItem":"ffffff",
					"title":"222222"
					}
				</script>

			</head>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">

					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">

											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->

											<tr>
												<td class="movableContentContainer" valign="top">

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">SELAMAT</h2>
																		</div>
																		<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa Anda Telah Kami Terima</strong></label>
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															

															<tr><td height="15"> </td></tr>

															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu, <br /> <br />
																				Permintaan jasa Anda telah kami terima, silahkan login dengan akun dibawah ini :<br />
                                                                                <b>Email : ' . $email . ' <br />
                                                                                Password : 123456 <br /><br />
                                                                                Silahkan Download dan Login pada aplikasi android mitrarenov dengan mengakses link dibawah ini : <br /><br />
                                                                                
      <a style="color:#337ab7" href="https://play.google.com/store/apps/details?id=hugaf.mitrarenov.com"><b><u>DOWNLOAD APLIKASI ANDROID MITRARENOV</u></b></a>	
																			
																				<br>
					  
																				
																				<br /><br />
																				Jika memerlukan informasi lebih lanjut dapat mengirimkan ke email: <a href="mailto:info@mitrarenov.com">info@mitrarenov.com </a> atau telepon di 0822-9000-9990

																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>

															<tr>
																<td height="20">
																	<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																		<a href="' . $temp[0]->link_iklan . '" ><img src="http://mitrarenov.com/cdn/images/email_setting/' . $temp[0]->banner_iklan . '" style="width:100%;" /> </a>
																	</p>


																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>

																					</div>
																				</div>
																			</td>


																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />

												</td>
											</tr>



											<!-- =============================== footer ====================================== -->



										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr><td height="88"></td></tr>


				</table>

			</body>
			</html>
		';
    }

    private function content2($temp, $email)
    {
        return '
			<!DOCTYPE html>

			<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
			<head>
				
				<style type="text/css">
					body {
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						padding-top: 0 !important;
						padding-bottom: 0 !important;
						margin: 0 !important;
						width: 100% !important;
						-webkit-text-size-adjust: 100% !important;
						-ms-text-size-adjust: 100% !important;
						-webkit-font-smoothing: antialiased !important;
					}

					.tableContent img {
						border: 0 !important;
						display: block !important;
						outline: none !important;
					}

					a {
						color: #382F2E;
					}

					p, h1 {
						color: #382F2E;
						margin: 0;
					}

					p {
						text-align: left;
						color: #999999;
						font-size: 14px;
						font-weight: normal;
						line-height: 19px;
					}

					a.link1 {
						color: #382F2E;
					}

					a.link2 {
						font-size: 16px;
						text-decoration: none;
						color: #ffffff;
					}

					h2 {
						text-align: center;
						color: #222222;
						font-size: 19px;
						font-weight: normal;
					}

					div, p, ul, h1 {
						margin: 0;
					}

					.bgBody {
						background: #ffffff;
					}

					.bgItem {
						background: #ffffff;
					}

					.btn {
						background: darkorange;
						background-image: -webkit-linear-gradient(top, darkorange, darkorange);
						background-image: -moz-linear-gradient(top, darkorange, darkorange);
						background-image: -ms-linear-gradient(top, darkorange, darkorange);
						background-image: -o-linear-gradient(top, darkorange, darkorange);
						background-image: linear-gradient(to bottom, darkorange, darkorange);
						-webkit-border-radius: 28;
						-moz-border-radius: 28;
						border-radius: 28px;
						font-family: Arial;
						color: #ffffff;
						font-size: 20px;
						padding: 10px 20px 10px 20px;
						text-decoration: none;
					}

						.btn:hover {
							background: #3cb0fd;
							background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
							background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
							background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
							text-decoration: none;
						}
				</style>
				<script type="colorScheme" class="swatch active">
					{
					"name":"Default",
					"bgBody":"ffffff",
					"link":"382F2E",
					"color":"999999",
					"bgItem":"ffffff",
					"title":"222222"
					}
				</script>

			</head>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">

					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">

											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->

											<tr>
												<td class="movableContentContainer" valign="top">

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">SELAMAT</h2>
																		</div>
																		<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa Anda Telah Kami Terima</strong></label>
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>

													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															

															<tr><td height="15"> </td></tr>

															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu, <br /> <br />
																				Permintaan jasa Anda telah kami terima.<br /><br />
																				Silahkan Download dan Login pada aplikasi android mitrarenov dengan mengakses link dibawah ini : <br /><br />
                                                                                
      <a style="color:#337ab7" href="https://play.google.com/store/apps/details?id=hugaf.mitrarenov.com"><b><u>DOWNLOAD APLIKASI ANDROID MITRARENOV</u></b></a>	
																			
																				<br>

																			
																				<br>
																				<!--Silahkan klik button "DOWNLOAD" untuk mendapatkan free ebook
																				<br /><br /> 
																				<a href="' . $temp[0]->link_ebook . '" style="background: darkorange;
																					background-image: -webkit-linear-gradient(top, darkorange, darkorange);
																					background-image: -moz-linear-gradient(top, darkorange, darkorange);
																					background-image: -ms-linear-gradient(top, darkorange, darkorange);
																					background-image: -o-linear-gradient(top, darkorange, darkorange);
																					background-image: linear-gradient(to bottom, darkorange, darkorange);
																					-webkit-border-radius: 28;
																					-moz-border-radius: 28;
																					border-radius: 28px;
																					font-family: Arial;
																					color: #ffffff;
																					font-size: 20px;
																					padding: 10px 20px 10px 20px;
																					text-decoration: none;">DOWNLOAD</a>
																				-->
																				<br /><br />
																				Jika memerlukan informasi lebih lanjut dapat mengirimkan ke email: <a href="mailto:info@mitrarenov.com">info@mitrarenov.com </a> atau telepon di 0822-9000-9990

																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>

															<tr>
																<td height="20">
																	<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																		<a href="' . $temp[0]->link_iklan . '" ><img src="http://mitrarenov.com/cdn/images/email_setting/' . $temp[0]->banner_iklan . '" style="width:100%;" /> </a>
																	</p>


																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>

																					</div>
																				</div>
																			</td>


																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>

																			<td width="16"></td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />

												</td>
											</tr>



											<!-- =============================== footer ====================================== -->



										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr><td height="88"></td></tr>


				</table>

			</body>
			</html>
		';
    }

    private function contenttukang($data2, $d2)
    {
        return '
			    <style type="text/css">
				body {
					padding-top: 0 !important;
					padding-bottom: 0 !important;
					padding-top: 0 !important;
					padding-bottom: 0 !important;
					margin: 0 !important;
					width: 100% !important;
					-webkit-text-size-adjust: 100% !important;
					-ms-text-size-adjust: 100% !important;
					-webkit-font-smoothing: antialiased !important;
				}

				.tableContent img {
					border: 0 !important;
					display: block !important;
					outline: none !important;
				}

				a {
					color: #382F2E;
				}

				p, h1 {
					color: #382F2E;
					margin: 0;
				}

				p {
					text-align: left;
					color: #999999;
					font-size: 14px;
					font-weight: normal;
					line-height: 19px;
				}

				a.link1 {
					color: #382F2E;
				}

				a.link2 {
					font-size: 16px;
					text-decoration: none;
					color: #ffffff;
				}

				h2 {
					text-align: center;
					color: #222222;
					font-size: 19px;
					font-weight: normal;
				}

				div, p, ul, h1 {
					margin: 0;
				}

				.bgBody {
					background: #ffffff;
				}

				.bgItem {
					background: #ffffff;
				}

				.btn {
					background: darkorange;
					background-image: -webkit-linear-gradient(top, darkorange, darkorange);
					background-image: -moz-linear-gradient(top, darkorange, darkorange);
					background-image: -ms-linear-gradient(top, darkorange, darkorange);
					background-image: -o-linear-gradient(top, darkorange, darkorange);
					background-image: linear-gradient(to bottom, darkorange, darkorange);
					-webkit-border-radius: 28;
					-moz-border-radius: 28;
					border-radius: 28px;
					font-family: Arial;
					color: #ffffff;
					font-size: 20px;
					padding: 10px 20px 10px 20px;
					text-decoration: none;
				}

					.btn:hover {
						background: #3cb0fd;
						background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
						background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
						background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
						text-decoration: none;
					}
			</style>
			<script type="colorScheme" class="swatch active">
				{
				"name":"Default",
				"bgBody":"ffffff",
				"link":"382F2E",
				"color":"999999",
				"bgItem":"ffffff",
				"title":"222222"
				}
			</script>
			<body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center" style="font-family:Helvetica, Arial,serif;">
					<tr>
						<td>
							<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="bgItem">
								<tr>
									<td width="40"></td>
									<td width="520">
										<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
											<!-- =============================== Header ====================================== -->
											<!-- =============================== Body ====================================== -->
											<tr>
												<td class="movableContentContainer" valign="top">
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable">
																			<!--<p style="text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;">Newsletter </p>-->
																			<br>
																			<img src="http://mitrarenov.com/cdn/frontend/img/logo.png" style="width:60%;" />
																			<hr style="margin-top:30px; border: 2px solid darkorange;" />
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td valign="top" align="center">
																	<div class="contentEditableContainer contentImageEditable">
																		<div class="contentEditable" style="margin-top:20px;">
																			<h2 style="color:dodgerblue;">PEMBERITAHUAN</h2>
																		</div>
																		<!--<div class="contentEditable" style="margin-top:0px;">
																			<label style="margin-top:1500px;"><strong>Permintaan Jasa dari <strong>' . $data2["nama"] . '</label>
																		</div>
																		-->
																	</div>
																</td>
															</tr>
														</table>
													</div>
													<div class="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<!--<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<h2>' . $data2["title"] . '</h2>
																		</div>
																	</div>
																</td>
															</tr>-->
															<tr><td height="15"> </td></tr>
															<tr>
																<td align="left">
																	<div class="contentEditableContainer contentTextEditable">
																		<div class="contentEditable" align="center">
																			<!--<p style="text-align:center;color:#999999;font-size:14px;font-weight:normal;line-height:19px;">
																				<img src="http://mitrarenov.com/cdn/images/photo_newsletter/<?=$image?>" style="width:50%;" />
																			</p>-->
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				<!--<?=$description?>-->
																				Dear Bapak/Ibu - ' . $d2->nama_tukang . ', anda terpilih untuk mengerjakan project dengan detail sebagai berikut: <br /> <br />
																				Nama : ' . $data2["nama"] . ' <br>
																				Telp : ' . $data2["no_telp"] . ' <br>
																				
																			</p>
																			<hr style="margin-top:10px; margin-bottom:10px; border: 1px solid blue;" />
																			<p style="text-align:left;color:#222222;font-size:14px;font-weight:normal;line-height:19px;">
																				Project Number : ' . $data2["project_number"] . ' <br>
																				Jasa : ' . $this->jasaType($data2["product_id"]) . ' <br>
																				Kebutuhan : ' . $data2["ins_project"]["priority"] . ' <br>
																				Luas : ' . $data2["ins_project"]["luas"] . ' <br>
																				Longitude & Latitude : ' . $data2["provinceVal"] . ' <br>
																				Lokasi : ' . $data2["ins_project"]["alamat_pengerjaan"] . ' <br>
																				Catatan Alamat : ' . $data2["ins_project"]["catatan_alamat"] . ' <br>
																				Description : ' . $data2["ins_project"]["description"] . ' <br>
																				Metode Payment : ' . $data2["ins_project"]["metode_payment"] . ' <br>
																				Total : ' . $data2["ins_project"]["total"] . ' <br>
																				Marketing Name : ' . $data2["marketing_name"] . ' <br>
																				
																				<br>
																				<div class="details">
																					Untuk informasi lengkap silahkan login pada akun anda di aplikasi
																				</div>
																				<!--Silahkan klik button "DOWNLOAD" untuk mendapatkan free ebook
																				<br /><br /> -->
																				
																				<br>
																				<br>
																				Terima kasih,
																				<br>
																				<span style="color:#222222;">Admin Mitrarenov</span>
																			</p>
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td height="20">

																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:30px; margin-bottom:20px; border: 2px solid darkorange;" />
													<div lass="movableContent">
														<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
															<tr>
																<td>
																	<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
																		<tr>
																			<td valign="top" align="left" width="370">
																				<div class="contentEditableContainer contentTextEditable">
																					<div class="contentEditable" align="center">
																						<p style="text-align:left;color:#000000;font-size:14px;font-weight:normal;line-height:20px;">
																							<span style="font-weight:bold; ">Contact us :</span>
																							<h3 style="text-align:left; margin-top:-1px; color:#3cb0fd;">0822-9000-9990</h3>
																						</p>
																					</div>
																				</div>
																			</td>

																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentFacebookEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://www.facebook.com/Mitrarenov-1763460670555727/"><img src="http://mitrarenov.com/cdn/frontend/img/Facebook.png" alt="facebook icon" style="width:60px;" data-default="placeholder" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/twitter.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/instagram.png" alt="twitter icon" data-default="placeholder" style="width:60px;" data-max-width="52" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																			<td width="16"></td>
																			<td valign="top" width="52">
																				<div class="contentEditableContainer contentTwitterEditable">
																					<div class="contentEditable">
																						<a target="_blank" href="https://twitter.com/mitrarenov"><img src="http://mitrarenov.com/cdn/frontend/img/gplus.png" alt="twitter icon" data-default="placeholder" style="width:70px;" data-customIcon="true"></a>
																					</div>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</div>
													<hr style="margin-top:10px; margin-bottom:20px; border: 1px solid darkorange;" />
												</td>
											</tr>

											<!-- =============================== footer ====================================== -->

										</table>
									</td>
									<td width="40"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td height="88"></td></tr>

				</table>
			</body>
		';
    }

    public function tambah_log_email_db($tipe, $email, $role, $status)
    {
        $inserts['tipe'] = $tipe;
        $inserts['email'] = $email;
        $inserts['role'] = $role;
        $inserts['status'] = $status;
        $query = $this->model->insB('log_email', $inserts);
    }

    private function jasaType($product_id = "")
    {
        $jasa = "";
        if ($product_id == 1) {
            $jasa = "Membangun Rumah";
        } else if ($product_id == 2) {
            $jasa = "Membuat Dak";
        } else if ($product_id == 3) {
            $jasa = "Menambah Ruangan";
        } else if ($product_id == 4) {
            $jasa = "Meningkat Rumah";
        } else if ($product_id == 5) {
            $jasa = "Perbaikan Genteng";
        } else if ($product_id == 6) {
            $jasa = "Pengecatan";
        } else if ($product_id == 7) {
            $jasa = "Membuat Carport";
        } else if ($product_id == 8) {
            $jasa = "Membangun Rumah Kost";
        } else if ($product_id == 9) {
            $jasa = "Membangun Ruko";
        } else if ($product_id == 10) {
            $jasa = "Pembuatan Kolam Renang";
        } else if ($product_id == 11) {
            $jasa = "Pekerjaan Interior";
        } else {
            $jasa = "Pekerjaan Lain";
        }

        return $jasa;
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
        $data['snk'] = $model->getSnk();
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
        return redirect()->to('kontak');
    }

    // Akun or setting
    public function akun()
    {
        $sess = session();
        $mdl = new DboModel();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        $data['projekBerjalan'] = $mdl->getProjectUser($sess->get('user_id'), null, 'project');
        // echo "<pre>"; print_r($data['projekBerjalan']); echo"</pre>";
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
        $mdl = new DboModel();
        if ($sess->get('logged_in') == FALSE) {
            return redirect()->to('/');
        }
        $data['akun'] = $this->model->getWhere('member_detail', ['member_id' => $sess->get('user_id')])->getRow();
        $data['projek'] = $mdl->getProjectUserS($sess->get('user_id'), 'done');
        // echo "<pre>"; print_r($data['projekBerjalan']); echo"</pre>";
        return view('riwayatProjek', $data);
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
