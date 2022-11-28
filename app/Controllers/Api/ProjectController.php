<?php

namespace App\Controllers\Api;

use App\Models\AuthDetailModel;
use App\Models\DboModel;
use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class ProjectController extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;

    public function tipe_rumah()
    {
        $mdl = new GeneralModel();
        $data = $mdl->getAll('tipe_rumah')->getResult();

        if (!$data) {
            return $this->fail('Gagal mendapatkan tipe rumah!');
        }

        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "data" => $data
        ];

        return $this->respond($res, 200);
    }

    public function index()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        $url = base_url();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id = (int)$cekUser->member_id;
        
        $key = $this->request->getGet();
      
        if (array_key_exists("limit", $key) && array_key_exists("status", $key)) {
            $limit  = (int) $key['limit'];
            $status  = $key['status'];
            $data = $models->getProjectUser($id, $limit, $status);
        }else if(array_key_exists("status", $key)){
            $status  = $key['status'];
            $data = $models->getProjectUser($id, null, $status);
        }else if(array_key_exists("limit", $key)){
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUser($id, $limit, null);
        }
        else{
            $data = $models->getProjectUser($id);
        }

        if (!$data) {
            return $this->failNotFound('data tidak ditemukan! Belum pernah melakukan transaksi.');
        }
        $path = "/public/images/projek/";   
         
        foreach ($data as $p) {
           if($p->image_upload == null){
                $p->image_upload = $url.'/public/images/no-picture/no_logo.png';
           }else{
                $p->image_upload = $url.$path.$p->image_upload;
           }          
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function projekBerjalan()
    {  
    }

    public function listProgresImage($id)
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();
        $id_user = (int)$cekUser->member_id;

        $path_dokumen = "https://admin.mitrarenov.soldig.co.id/assets/main/images/project_update/";
        $data = $model->getWhere('projects_update', ['project_id' => $id])->getResult();
        foreach ($data as $key => $value) {
            $value->image = $path_dokumen.$value->image;
            $value->progres = (int) str_replace('%', '', $value->progres);
        }
        if (!$data) {
            return $this->failNotFound('data tidak ditemukan!');         
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function detail($id)
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        $url = base_url();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id_user = (int)$cekUser->member_id;
          
        $data = $models->getProjectUserD($id, true);
        $berkas = $models->getProjectUser($id_user, null, 'project');
        $path_dokumen = "https://admin.mitrarenov.soldig.co.id/assets/main/berkas/";
        $data['berkas'] = $path_dokumen.$berkas[0]->dokumen;
        $data['berkas_rab'] = $path_dokumen.$berkas[0]->dokumen_rab;
        $data['image_default'] = $url."public/main/images/gambar-blum-update.png";
        
        if (!$data) {
            return $this->failNotFound('data tidak ditemukan! Belum pernah melakukan transaksi.');         
        }
      
        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }

    public function generatePDF($id){
        $mdl = new GeneralModel();
        $data['bayar'] = $mdl->getWhere('projects_pembayaran', ['id' => $id, 'due_date !=' => 0000-00-00])->getRow(); //data dari tabel iklan
        $data['project'] = $mdl->getWhere('projects', ['id' => $data['bayar']->project_id])->getRow(); //data dari tabel iklan
        $data['detail'] = $mdl->getWhere('project_data_customer', ['project_id' => $data['bayar']->project_id])->getRow(); //data dari tabel iklan
        $data['pembayaran_done'] = $mdl->getWhere('projects_pembayaran', ['project_id' => $data['bayar']->project_id, 'due_date !=' => 0000-00-00, 'status' => 'sudah dibayar'])->getResult(); //data dari tabel iklan
        $data['pembayaran'] = $mdl->getWhere('projects_pembayaran', ['project_id' => $data['bayar']->project_id, 'due_date !=' => 0000-00-00, 'status' => 'belum dibayar'])->getResult(); //data dari tabel iklan
        $data['persetujuan'] = $mdl->getWhere('projects_persetujuan', ['nomor_kontrak' => $data['project']->nomor_kontrak])->getRow(); //data dari tabel iklan
        $tagihan = 0;
        foreach ($data['pembayaran'] as $key => $value) {
            $tagihan += str_replace('.','', $value->biaya);
        }
        $data['terbilang'] = $this->numberToWordsID($tagihan);
        
        $nama = "Invoice-". $data['bayar']->nomor_invoice;
        $res = [
            "status" => 200,
            "messages" => "Sukses",
            "error" => NULL
        ];
        try {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml(view('printinvoice', $data));
            $dompdf->setPaper('A4', 'portrait'); //ukuran kertas dan orientasi
            $dompdf->render();
            // $dompdf->stream($nama); //nama file pdf  
            $file_path = FCPATH. "public/main/pdf/". $nama.".pdf";
            
            if (file_exists($file_path)) {
                unlink($file_path);    
            }
            file_put_contents($file_path, $dompdf->output());  
            $res['data'] = ['file' => base_url("public/main/pdf/". $nama.".pdf")];
            return $this->respond($res, 200);
             //arahkan ke list-iklan setelah laporan di unduh
        } catch (\Throwable $th) {
            return $this->respond($th, 200);; //arahkan ke list-iklan setelah laporan di unduh
        }
    }

    public function numberToWordsID($number) {
        $max_size = pow(10,18);
        if (!$number) return "nol";
        $suffix = ""; //add suffix to fix scope problem

        if (is_int($number) && $number < abs($max_size)) 
        {            
            switch ($number) 
            {
                case $number < 0:
                    $prefix = "negatif";
                    $suffix = $this->numberToWordsID(-1*$number);
                    $string = $prefix . " " . $suffix;
                    break;
                case 1:
                    $string = "satu";
                    break;
                case 2:
                    $string = "dua";
                    break;
                case 3:
                    $string = "tiga";
                    break;
                case 4: 
                    $string = "empat";
                    break;
                case 5:
                    $string = "lima";
                    break;
                case 6:
                    $string = "enam";
                    break;
                case 7:
                    $string = "tujuh";
                    break;
                case 8:
                    $string = "delapan";
                    break;
                case 9:
                    $string = "sembilan";
                    break;                
                case 10:
                    $string = "sepuluh";
                    break;            
                case 11:
                    $string = "sebelas";
                    break;            
                case 12:
                    $string = "dua belas";
                    break;            
                case 13:
                    $string = "tiga belas";
                    break;            
                case 15:
                    $string = "lima belas";
                    break;            
                case $number < 20:
                    $string = $this->numberToWordsID($number%10);
                    if ($number == 18)
                    {
                    $suffix = " belas";
                    } else 
                    {
                    $suffix = " belas";
                    }
                    $string .= $suffix;
                    break;            
                case 20:
                    $string = "dua puluh";
                    break;            
                case 30:
                    $string = "tiga puluh";
                    break;            
                case 40:
                    $string = "empat puluh";
                    break;            
                case 50:
                    $string = "lima puluh";
                    break;            
                case 60:
                    $string = "enam puluh";
                    break;            
                case 70:
                    $string = "tujuh puluh";
                    break;            
                case 80:
                    $string = "delapan puluh";
                    break;            
                case 90:
                    $string = "sembilan puluh";
                    break;                
                case $number < 100:
                    $prefix = $this->numberToWordsID($number-$number%10);
                    $suffix = $this->numberToWordsID($number%10);
                    $string = $prefix . "-" . $suffix;
                    break;
                case $number < pow(10,3):                
                    $getNumber = $this->numberToWordsID(intval(floor($number/pow(10,2))));
                    $getNumber = $getNumber == "satu" ? "se" : $getNumber . " ";
                    $prefix = $getNumber . "ratus";
                    if ($number%pow(10,2)) $suffix = " " . $this->numberToWordsID($number%pow(10,2));
                    $string = $prefix . $suffix;
                    break;
                case $number < pow(10,6):
                    $prefix = $this->numberToWordsID(intval(floor($number/pow(10,3)))) . " ribu";
                    if ($number%pow(10,3)) $suffix = $this->numberToWordsID($number%pow(10,3));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10,9):
                    $prefix = $this->numberToWordsID(intval(floor($number/pow(10,6)))) . " juta";
                    if ($number%pow(10,6)) $suffix = $this->numberToWordsID($number%pow(10,6));
                    $string = $prefix . " " . $suffix;
                    break;                    
                case $number < pow(10,12):
                    $prefix = $this->numberToWordsID(intval(floor($number/pow(10,9)))) . " milliar";
                    if ($number%pow(10,9)) $suffix = $this->numberToWordsID($number%pow(10,9));
                    $string = $prefix . " " . $suffix;    
                    break;
                case $number < pow(10,15):
                    $prefix = $this->numberToWordsID(intval(floor($number/pow(10,12)))) . " triliun";
                    if ($number%pow(10,12)) $suffix = $this->numberToWordsID($number%pow(10,12));
                    $string = $prefix . " " . $suffix;    
                    break;        
                case $number < pow(10,18):
                    $prefix = $this->numberToWordsID(intval(floor($number/pow(10,15)))) . " kuadraliun";
                    if ($number%pow(10,15)) $suffix = $this->numberToWordsID($number%pow(10,15));
                    $string = $prefix . " " . $suffix;    
                    break;                    
            }
        }else{
            return "ERROR - $number<br/> Number must be an integer between -" . number_format($max_size, 0, ".", ",") . " and " . number_format($max_size, 0, ".", ",");
        }

        return $string;    
    }

    public function spek()
    {
        $models = new GeneralModel();
        
        $key = $this->request->getGet();
       
        $produk =  $models->getWhere('product', ['id' => $key['id']])->getRow();
        if($key != null){
                // $spek = $models->getWhere('product_price', ['product_id' => $produk->id])->getResult();
                // if($spek == null){
                //     $spek = $models->getWhere('product_price', ['product_id' => $produk->category_id])->getResult();
                //     if ($spek == null) {
                //         $spek = $models->getWhere('product', ['paket_name' => $key['name']])->getResult();
                //         foreach ($spek as $key => $value) {
                //             $value->type_price = $value->paket_name;
                //             $value->product_price = $value->start_from_text;
                //             $value->spesifikasi = $value->spesifikasi_renov;
                //         }
                //     }
                // }
                $spek =  $models->getWhere('product_price', ['product_id' => $produk->id])->getResult();
                if($spek == null){
                    $spek =  $models->getWhere('product_price', ['product_id' => $produk->category_id])->getResult();
                    if ($spek == null) {
                        $spek =  $models->getWhere('product', ['paket_name' => $key['name']])->getResult();
                        foreach ($spek as $key => $value) {
                            $value->type_price = $value->paket_name;
                            $value->product_price = $value->start_from_text;
                            $value->spesifikasi = $value->spesifikasi_renov;
                        }
                    }
                }
            // $data = $models->getWhere('product_price', ['id' => $key['id']])->getRow();
            $data = $spek;
        }else{
            $data = $models->getAll('product_price')->getResult();
        }

        $res = [
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function detailSpek()
    {
        $models = new GeneralModel();        
        $key = $this->request->getGet();       
        $spek =  $models->getWhere('product_price', ['id' => $key['id']])->getRow();
        $res = [
            'data' => $spek,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function type_order()
    {
        $models = new GeneralModel();

        $data = $models->getAll('type_order')->getResult();

        $res = [
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function detailInvoice()
    {
        $headers = $this->request->headers();
        $models = new DboModel();
        $key = $this->request->getVar();

        $data = $models->detailInvoice($key->no_invoice);

        $res = [
            'message' => 'Sukses',
            'data' => $data,
            'error' => null
        ];

        return $this->respond($res, 200);
    }

    public function projectDone()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();
        
        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();
        
        $id = (int)$cekUser->member_id;

        $data = $models->getProjectUserS($id, 'done');
        $user = $model->getwhere('project_data_customer', ['member_id' => $id])->getResult();

        $key = $this->request->getGet();

        if (array_key_exists("limit", $key)) {
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUserD($id, 'done', $limit);
        }

        $res = [
            'X-Auth-Token' => $token,
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }    

    public function projectProgres()
    {
        $headers = $this->request->headers();
        $token = $headers['X-Auth-Token']->getValue();
        $model = new GeneralModel();
        $models = new DboModel();

        $cekUser = $model->getWhere('token_login', ['token' => $token])->getRow();

        $id = (int)$cekUser->member_id;
        $path = "./public/images/desain_rumah_user";
        $path1 = "./public/images/projek";
        $data = $models->getProjectUserS($id, 'project');
        // var_dump($data);die;

        $user = $model->getwhere('project_data_customer', ['member_id' => $id])->getResult();

        $key = $this->request->getGet();

        if (array_key_exists("limit", $key)) {
            $limit  = (int) $key['limit'];
            $data = $models->getProjectUserS($id, 'project', $limit);
        }

        $res = [
            'X-Auth-Token' => $token,
            'data' => $data,
            'error' => null
        ];
        return $this->respond($res, 200);
    }
}
