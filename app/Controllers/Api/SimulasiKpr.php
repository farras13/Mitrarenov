<?php

namespace App\Controllers\Api;

use App\Models\DboModel;
use App\Models\GeneralModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class SimulasiKpr extends ResourceController
{
    use RequestTrait;
    use ResponseTrait;

    public function index_area()
    {
        $model = new DboModel();
        $key = $this->request->getGet();
        if(array_key_exists("prov_id", $key) ){
            $id  = (int) $key['prov_id'];
            $area = $model->getArea($id);
            if(!$area){
                $res = [
                    "status" => True,
                    "messages" => "data kosong",
                    "data" => null
                ];
                return $this->respond($res, 200);
            }
            $res = [
                "status" => True,
                "messages" => "Sukses",
                "data" => $area
            ];
            return $this->respond($res, 200);
        }
        $res = [
            "status" => False,
            "messages" => "Isi parameter prov id ",
            "data" => null
        ];
        return $this->respond($res, 500);
    }

    public function province()
    {
        $model = new DboModel();
        $prov = $model->getProv();

        if ($prov == null) {
            $res = [
                "status" => True,
                "messages" => "data masih kosong",
                "data" => null
            ];
            return $this->respond($res, 200);
        }

        $res = [
            "status" => True,
            "messages" => "data masih kosong",
            'data' => $prov,
        ];

        return $this->respond($res, 200);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function perhitungan_kpr()
    {
        $input = $this->request->getVar();
        $harga = $input['harga'];
        $dp = $input['dp'];
        $bunga = $input['bunga'];
        $jw = $input['jangka_waktu'];


        if($input == null){
            $this->respond('inputkan kosong semua, mohon di isi terlebih dahulu', 500);
        }
        if(empty($harga) ){
            $this->respond('inputan harga masih kosong, mohon di isi terlebih dahulu!', 500);

        }else if(empty($dp)){
            $this->respond('inputkan DP masih kosong, mohon di isi terlebih dahulu!', 500);
        }
        else if(empty($bunga)){
            $this->respond('inputkan bunga kosong, mohon di isi terlebih dahulu', 500);
        }
        else if(empty($jw)){
            $this->respond('inputkan jangka waktu kosong, mohon di isi terlebih dahulu', 500);
        }

        $bulan = $jw * 12;
        $dp_persen = $dp / 100;
        $bungaEfektif = $bunga / 100;
        $temp_dp = $harga * $dp_persen;
        $pokok = $harga - $temp_dp;

        $_bungaPerBulan = $bungaEfektif / 12;

        $_bungaPerBulan1 = 1 + ($bungaEfektif/12);

        $_bungaPerBulan1Exp = pow($_bungaPerBulan1, $bulan);

        $_bungaPerBulan2Exp = pow($_bungaPerBulan1, $bulan) - 1;

        $_angsuranPerBulan1 = $pokok * $_bungaPerBulan * $_bungaPerBulan1Exp;

        $angsuranPerBulan = round($_angsuranPerBulan1 / $_bungaPerBulan2Exp); 
        $gajiMinumun = round($angsuranPerBulan / 0.4);              
        $data =[
            'form' => $input,
            'jumlah_uang_muka' => $temp_dp,
            'pokok_pembayaran' => $pokok,
            'angsuran' => $angsuranPerBulan,
            'gaji' => $gajiMinumun,
        ];

        $res = [
            'data' => $data,
            'error' => 0
        ];

        return $this->respond($res, 200);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $model = new DboModel();
        $input = $this->request->getVar();

        $json_tukang =  $model->findTukang($input['area']);
        

        $id_tukang = $json_tukang->id_tukang;
        $email_tukang = $json_tukang->email_tukang;
        $nama_tukang = $json_tukang->nama_tukang;
        $hp_tukang = $json_tukang->telephone;
       
        $date_jadwal = date_create($input['jadwal_survey']);
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
            'created' => date("Y-m-d H:i:s"), 
            'tukang_id' => $id_tukang, 
            'jadwal_survey' => date_format($date_jadwal, "Y-m-d"), 
            'kode_referal' => $input['kode_referal'], 
        ];
        // var_dump($ins_project);die;
        // $provinceVal = $input('provinceVal');

        $insert1 = $model->insA('pengajuan_kpr', $ins_project);

        if (!$insert1) {
            return $this->fail("Gagal melakukan pengajuan KPR");
        }

        $res = [
            'data' => $ins_project,
            'error' => null
        ];

        return $this->respond($res, 200);
    }


    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    // public function merawat()
    // {
    //     $model = new GeneralModel();
    //     $merawat = $model->getAll('merawat')->getResult();
    //     foreach ($merawat as $key => $value) {
    //         $model->ins('gambar_portofolio', ['porto_id' => $value->id,'image' => $value->image]);
    //     }
    //     $res = $model->getAll('gambar_portofolio')->getResult();
    //     return $this->respond($res, 200);
    // }

    
}
