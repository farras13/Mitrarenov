<?php

namespace App\Models;

use CodeIgniter\Model;

class DboModel extends Model
{
    public function getProject($id)
    {
        $db = db_connect();
        return $data = $db->query("SELECT * FROM `dbo.mst_project` WHERE member_id = $id")->getResult();
    }

    public function getProjectProgres($id)
    {
        $db = db_connect();
        return $data = $db->query("SELECT * FROM projects WHERE member_id = $id and status_project = 'project'")->getResult();
    }

    public function getSnk()
    {
        $db = db_connect();
        return $data = $db->query("SELECT * FROM snk_kpr")->getResult();
    }

    function findTukang($area_id)
    {
        $db = db_connect();
        $query = $db->query("SELECT a.id_tukang, b.email as email_tukang, c.name as nama_tukang, c.telephone from area_tukang a inner join member b on a.id_tukang = b.id left join member_detail c on b.id = c.member_id WHERE a.id_area = $area_id ");
        $json = $query->getRow();

        return $json;
    }

    public function updateData($t, $id, $data)
    {
        return $this->db->table($t)->where($id)->update($data);
    }

    public function getProjectUser($id, $l = null, $w = null)
    {
        $db = db_connect();
        // var_dump($id);
        // $data = $db->query("SELECT b.* FROM `projects` as b join project_data_customer as  a on a.project_id = b.id  WHERE member_id = $id")->getResult();
        if ($l != null && $w != null) {
            $data = $db->query("SELECT b.id, a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak, e.rab,e.dokumen,e.dokumen_rab, b.alamat_pengerjaan,f.name as subkon,f.telephone as phone_subkon, b.status_project, DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id  
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak			
            join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.tukang_id  
            WHERE a.member_id = $id AND b.status_project = '$w' ORDER BY b.id DESC LIMIT $l")->getResult();
        } else if ($l == null && $w != null) {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as subkon,f.telephone as phone_subkon, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.tukang_id  
            WHERE a.member_id = $id AND b.status_project = '$w' ORDER BY b.id DESC")->getResult();
        } else if ($l != null && $w == null) {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as subkon,f.telephone as phone_subkon, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.tukang_id  
            WHERE a.member_id = $id ORDER BY b.id DESC LIMIT $l")->getResult();
        } else {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as subkon,f.telephone as phone_subkon, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.tukang_id  
            WHERE a.member_id = $id ORDER BY b.id DESC")->getResult();
        }
        foreach ($data as $key => $value) {

            $value->tambah = $db->query("SELECT sum(biaya) as total, keterangan as ket_enum, tipe FROM `projects_addendum`  
                WHERE STATUS = 'disetujui' AND project_id = $value->id and tipe = 0
                ORDER BY `projects_addendum`.`id`  DESC")->getResult();
            $value->kurang = $db->query("SELECT sum(biaya) as total, keterangan as ket_enum, tipe FROM `projects_addendum`  
                WHERE STATUS = 'disetujui' AND project_id = $value->id and tipe = 1
                ORDER BY `projects_addendum`.`id`  DESC")->getResult();
        }


        // var_dump($data);
        return $data;
    }

    public function getProjectUserD($id)
    {
        $db = db_connect();
        // var_dump($id);
        // $data = $db->query("SELECT b.* FROM `projects` as b join project_data_customer as  a on a.project_id = b.id  WHERE member_id = $id")->getResult();
        $progres = $db->query("SELECT id, image, description FROM projects_update WHERE project_id = $id")->getResult();

        $sk = $db->query("SELECT projects.id, projects.nomor_kontrak, member_detail.name, product.category_id, DATE_FORMAT(FROM_UNIXTIME(projects.created), '%m') AS 'bulan',DATE_FORMAT(FROM_UNIXTIME(projects.created), '%Y') AS 'tahun' 
            FROM projects 
            JOIN project_data_customer ON project_data_customer.project_id = projects.id 
            JOIN member_detail ON member_detail.member_id = project_data_customer.member_id 
            JOIN projects_detail ON projects_detail.project_id = projects.id 
            JOIN product ON product.id = projects_detail.product_id 
           
            WHERE projects.id = $id
            ORDER BY id DESC")->getRow();

        $addenum_tambah = $db->query("SELECT DISTINCT b.status as status_bayar, a.biaya, a.tipe, a.status,  DATE_FORMAT(FROM_UNIXTIME(a.tanggal_selesai), '%d/%m/%Y') AS 'tanggal_selesai', a.keterangan, b.jenis, a.berkas FROM `projects_addendum` as a
                JOIN projects_pembayaran as b on a.project_id = b.project_id
                WHERE a.status = 'disetujui' AND a.project_id = $id AND b.jenis = 'tambahan' AND b.tipe = 1
                -- GROUP BY a.tipe
                ORDER BY a.`id` DESC")->getResult();
        $addenum_kurang = $db->query("SELECT DISTINCT b.status as status_bayar, a.biaya, a.tipe, a.status,  DATE_FORMAT(FROM_UNIXTIME(a.tanggal_selesai), '%d/%m/%Y') AS 'tanggal_selesai', a.keterangan, b.jenis, a.berkas FROM `projects_addendum` as a
                JOIN projects_pembayaran as b on a.project_id = b.project_id
                WHERE a.status = 'disetujui' AND a.project_id = $id AND b.jenis = 'tambahan' AND b.tipe = 2
                -- GROUP BY a.tipe
                ORDER BY a.`id` DESC")->getResult();

        $customer = $db->query("SELECT project_data_customer.name, project_data_customer.phone, t.name as 'pic', t.telephone AS 'pic_telp' ,projects.luas, projects.metode_payment
            FROM project_data_customer 
            JOIN projects on projects.id = project_data_customer.project_id
            JOIN member_detail on member_detail.member_id = project_data_customer.member_id
            JOIN member_detail as t on t.member_id = projects.tukang_id
            WHERE project_id = $id limit 1")->getResult();

        $projek = $db->query("SELECT projects.id, projects.nomor_kontrak as no_sk,projects_persetujuan.rab, projects.project_number, projects.presentase_progress, product.paket_name, projects_update.tanggal,projects_persetujuan.dokumen, projects_persetujuan.dokumen_rab  FROM projects
            JOIN projects_detail on projects_detail.project_id = projects.id
            JOIN product on product.id = projects_detail.product_id
            LEFT JOIN projects_update on projects_update.project_id = projects.id
            LEFT JOIN projects_persetujuan on projects_persetujuan.nomor_kontrak = projects.nomor_kontrak
            WHERE projects.id = $id
            ORDER BY projects_update.id DESC
            LIMIT 1")->getRow();

        $rab = str_replace('.','',$projek->rab);
        $projek->rab = $rab;

        $termin_unpaid = $db->query("SELECT id, tipe, project_id, nomor_invoice, biaya, keterangan, DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%e %b %Y') AS tanggal_terbit, DATE_FORMAT(due_date, '%e %b %Y') as jatuh_tempo, status
            FROM projects_pembayaran
            WHERE project_id = $id AND keterangan NOT LIKE '%RAP%' AND due_date != 0000-00-00 AND status = 'belum dibayar'")->getResult();

        $termin_paid = $db->query("SELECT id, tipe, project_id, nomor_invoice, biaya, keterangan, DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%e %b %Y') AS tanggal_terbit, DATE_FORMAT(due_date, '%e %b %Y') as jatuh_tempo, status
             FROM projects_pembayaran
             WHERE project_id = $id AND keterangan NOT LIKE '%RAP%' AND due_date != 0000-00-00 AND status != 'belum dibayar'")->getResult();

        //get
        $nm = substr($sk->name, 0, 3);
        $array_bln = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII");
        $bln = $array_bln[$sk->bulan];
        $nsk = $sk->id . '/' . $nm . '/' . $bln . '/' . $sk->tahun;

        if ($termin_paid == null) {
            $termin_paid = [null];
        } else {
            $a=1;
            foreach ($termin_paid as $key => $tp) {
                $akhir = substr($termin_paid[$key+1]->nomor_invoice,5, 4);
                $awal = substr($tp->nomor_invoice,5, 4);
                $selisih = (int)$akhir - (int)$awal;
                $tp->biaya = str_replace('.', '', $tp->biaya);
                $tp->biaya_tambahan = '-';
                $tp->title = "Pembayaran Termin " . $tp->keterangan;
                // if($selisih != 1){
                //     unset($termin_paid[$key+1]);
                // }
                // if($a > 7){
                //     unset($termin_paid[$key]);
                // }
                // $a++;
            }
        }

        if ($termin_unpaid == null) {
            $termin_unpaid = [null];
        } else {
            $a=1;
            foreach ($termin_unpaid as $kt => $t) {
                $akhir = substr($termin_unpaid[$kt+1]->nomor_invoice,5, 4);
                $awal = substr($t->nomor_invoice,5, 4);
                $selisih = (int)$akhir - (int)$awal;
                $t->biaya = str_replace('.', '', $t->biaya);
                $t->biaya_tambahan = '-';
                $t->title = "Pembayaran Termin " . $t->keterangan;
                // if($selisih != 1){
                //     unset($termin_unpaid[$kt+1]);
                // }
                // if($a > 7){
                //     unset($termin_unpaid[$kt]);
                // }
                // $a++;
            }
        }

        $termin = ['unpaid' => $termin_unpaid, 'paid' => $termin_paid ];
        
        if ($addenum_tambah == null) {
            $addenum_tambah = null;
        } else {
            foreach ($addenum_tambah as $a) {               
                $a->tipe = 'tambah';
            }
        }

        if ($addenum_kurang == null) {
            $addenum_kurang = null;
        } else {
            foreach ($addenum_kurang as $a) {               
                $a->tipe = 'kurang';
            }
        }
        $exten = ['tambah' => $addenum_tambah, 'kurang' => $addenum_kurang ];

        $url = base_url();
        $path = "https://admin.mitrarenov.soldig.co.id/assets/main/images/project_update/";
        if ($progres != null) {
            foreach ($progres as $p) {
                if ($p->image != null) {
                    $p->image = $path . $p->image;
                    
                } else {
                    $p->image = $url . '/public/images/no-picture/no_logo.png';
                }
            }
        } else {
            $progres = [[
                'image' => $url . '/public/images/no-picture/no_logo.png',
                'keterangan' => 'Belum ada report proses'
            ]];
        }

        $data = [
            'image_progress' => $progres,
            'projek' => $projek,
            'exten' => $exten,
            'data_customer' => $customer,
            'termin' => $termin,

        ];
        // var_dump($data);            

        return $data;
    }

    public function addenum($tipe, $id)
    {
        $db = db_connect();
        if($tipe == "tambah"){
            $addenum = $db->query("SELECT DISTINCT b.status as status_bayar, a.biaya, a.tipe, a.status,  DATE_FORMAT(FROM_UNIXTIME(a.tanggal_selesai), '%d/%m/%Y') AS 'tanggal_selesai', a.keterangan, b.jenis, a.berkas FROM `projects_addendum` as a
            JOIN projects_pembayaran as b on a.project_id = b.project_id
            WHERE a.status = 'disetujui' AND a.project_id = $id AND b.jenis = 'tambahan' AND b.tipe = 1
            ORDER BY a.`id` DESC")->getResult();
    
        }else{
            $addenum = $db->query("SELECT DISTINCT b.status as status_bayar, a.biaya, a.tipe, a.status,  DATE_FORMAT(FROM_UNIXTIME(a.tanggal_selesai), '%d/%m/%Y') AS 'tanggal_selesai', a.keterangan, b.jenis, a.berkas FROM `projects_addendum` as a
            JOIN projects_pembayaran as b on a.project_id = b.project_id
            WHERE a.status = 'disetujui' AND a.project_id = $id AND b.jenis = 'tambahan' AND b.tipe = 2           
            ORDER BY a.`id` DESC")->getResult();
        }
        return $addenum;
    }

    public function getProjectUserS($id, $s, $l = null)
    {
        $db = db_connect();
        if ($l != null) {
            $data = $db->query("SELECT a.member_id, a.name, a.dob, b.* FROM `project_data_customer` as a join projects as  b on b.id = a.project_id  WHERE member_id = $id AND b.status_project = '$s' ORDER BY b.id DESC LIMIT $l ")->getResult();
        } else {
            $data = $db->query("SELECT a.member_id, a.name, a.dob, b.* FROM `project_data_customer` as a join projects as  b on b.id = a.project_id  WHERE member_id = $id AND b.status_project = '$s'")->getResult();
        }
        return $data;
    }

    public function detailInvoice($invoice)
    {
        $termin_unpaid = $db->query("SELECT id, tipe, project_id, nomor_invoice, biaya, keterangan, DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%e %b %Y') AS tanggal_terbit, DATE_FORMAT(due_date, '%e %b %Y') as jatuh_tempo, status
            FROM projects_pembayaran
            WHERE project_id = $id AND nomor_invoice = $invoice AND keterangan NOT LIKE '%RAP%' AND due_date != 0000-00-00 AND status = 'belum dibayar'")->getResult();

        $termin_paid = $db->query("SELECT id, tipe, project_id, nomor_invoice, biaya, keterangan, DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%e %b %Y') AS tanggal_terbit, DATE_FORMAT(due_date, '%e %b %Y') as jatuh_tempo, status
             FROM projects_pembayaran
             WHERE project_id = $id AND nomor_invoice = $invoice AND keterangan NOT LIKE '%RAP%' AND due_date != 0000-00-00 AND status != 'belum dibayar'")->getResult();

        if ($termin_paid == null) {
            $termin_paid = [null];
        } else {
            $a=1;
            foreach ($termin_paid as $key => $tp) {
                $akhir = substr($termin_paid[$key+1]->nomor_invoice,5, 4);
                $awal = substr($tp->nomor_invoice,5, 4);
                $selisih = (int)$akhir - (int)$awal;
                $tp->biaya_tambahan = '-';
                $tp->title = "Pembayaran Termin " . $tp->keterangan;      
            }
        }

        if ($termin_unpaid == null) {
            $termin_unpaid = [null];
        } else {
            $a=1;
            foreach ($termin_unpaid as $kt => $t) {
                $akhir = substr($termin_unpaid[$kt+1]->nomor_invoice,5, 4);
                $awal = substr($t->nomor_invoice,5, 4);
                $selisih = (int)$akhir - (int)$awal;
                $t->biaya_tambahan = '-';
                $t->title = "Pembayaran Termin " . $t->keterangan;
            }
        }

        if($termin_paid != null){
            $termin = $termin_paid;
        }else{
            $termin = $termin_unpaid;
        }

        return $termin;
    }

    public function ins($t, $object)
    {
        return $this->db->table($t)->ignore(true)->insert($object);
    }

    public function insA($t, $object)
    {
        return $this->db->table($t)->insert($object);
    }

    public function getProv()
    {
        $db = db_connect();
        $data = $db->query("SELECT DISTINCT(province_id), name from area LEFT JOIN province on area.province_id = province.id Where province_id != 0 order by name asc")->getResult();
        return $data;
    }

    public function getArea($id)
    {
        $db = db_connect();
        return $db->query("SELECT id_area, nama_area, province_id from area WHERE province_id = $id order by province_id asc")->getResult();
    }

    public function getListChat($role, $id)
    {
        $db = db_connect();
        if ($role == 'tukang') {
            return $db->query("SELECT projects.id, DATE_FORMAT(FROM_UNIXTIME(projects.created), '%e %b %Y') AS 'tanggal', product.paket_name, chat.message, chat.user FROM projects 
            JOIN projects_detail ON projects_detail.project_id = projects.id
            JOIN product on product.id = projects_detail.product_id
            LEFT JOIN chat ON chat.project_id = projects.id
            WHERE chat.date IN (select MAX(date) FROM chat GROUP BY project_id) AND projects.tukang_id = $id
            GROUP BY chat.project_id
            ORDER BY chat.id DESC")->getResult();
        } else {
            return $db->query("SELECT chat.id, chat.project_id, DATE_FORMAT(FROM_UNIXTIME(chat.date), '%e %b %Y') AS 'tanggal' , product.paket_name, chat.message, chat.user, member_detail.name as admin FROM chat
            JOIN project_data_customer ON chat.project_id = project_data_customer.project_id
            JOIN projects ON chat.project_id = projects.id
            JOIN projects_detail ON projects_detail.project_id = chat.project_id
            JOIN product on product.id = projects_detail.product_id
            JOIN member_detail ON member_detail.member_id = projects.id_project_manager 
            WHERE chat.date IN (select MAX(date) FROM chat GROUP BY project_id) AND project_data_customer.member_id = $id
            GROUP BY chat.project_id DESC ORDER BY chat.date desc")->getResult();
        }
    }

    public function dtrans($w)
    {
        $db = db_connect();
        return $db->query("SELECT product.paket_name, product.price, product_price.product_price, projects_desain.*  FROM projects JOIN projects_detail on projects_detail.project_id = projects.id JOIN product ON projects_detail.product_id = product.id JOIN product_price on product.id = product_price.product_id JOIN projects_desain on projects_detail.desain_id = projects_desain.id WHERE projects.id = $w");
    }

    public function chat_customer($id)
    {
        $db = db_connect();
        return $db->query("SELECT DISTINCT projects.type, projects.id FROM project_data_customer
                        JOIN projects on project_data_customer.project_id = projects.id 
                        WHERE projects.id = $id ");
    }

    public function chat_tukang($id)
    {
        $db = db_connect();
        return $db->query("SELECT DISTINCT projects.type, projects.id FROM projects WHERE projects.tukang_id = $id");
    }

    public function dchat($id)
    {
        $db = db_connect();
        return $db->query("SELECT DISTINCT chat.*, member_detail.name as tukang, project_data_customer.name as customer, c.name as admin 
                                    FROM chat 
                                    JOIN projects on chat.project_id = projects.id 
                                    LEFT JOIN member_detail ON projects.tukang_id = member_detail.member_id 
                                    LEFT JOIN project_data_customer on chat.project_id = project_data_customer.project_id 
                                    LEFT JOIN member_detail as c ON projects.id_project_manager = c.member_id 
                                    WHERE chat.project_id = $id
                                ")->getResult();
    }
}
