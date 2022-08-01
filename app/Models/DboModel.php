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
            $data = $db->query("SELECT b.id, a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak, e.rab,e.dokumen,e.dokumen_rab, b.alamat_pengerjaan,f.name as pic,f.telephone as phone_pic, b.status_project, DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id  
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak			
            join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.id_admin_project  
            WHERE a.member_id = $id AND b.status_project = '$w' ORDER BY b.id DESC LIMIT $l")->getResult();
        } else if ($l == null && $w != null) {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as pic,f.telephone as phone_pic, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.id_admin_project  
            WHERE a.member_id = $id AND b.status_project = '$w' ORDER BY b.id DESC")->getResult();
        } else if ($l != null && $w == null) {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as pic,f.telephone as phone_pic, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as  b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.id_admin_project  
            WHERE a.member_id = $id ORDER BY b.id DESC LIMIT $l")->getResult();
        } else {
            $data = $db->query("SELECT b.id,a.member_id, a.name,a.phone,b.luas,b.metode_payment, b.project_number,b.nomor_kontrak,e.rab,e.dokumen,e.dokumen_rab,b.alamat_pengerjaan,f.name as pic,f.telephone as phone_pic, b.status_project,DATE_FORMAT(FROM_UNIXTIME(b.created), '%e %b %Y') AS 'created', b.presentase_progress, d.paket_name, b.image_upload 
			FROM `project_data_customer` as a 
            join projects as b on b.id = a.project_id  
            join projects_detail as c on c.project_id = b.id 
            left join projects_persetujuan as e on e.nomor_kontrak = b.nomor_kontrak
			join product as d on d.id = c.product_id
            join member_detail as f on f.member_id = b.id_admin_project  
            WHERE a.member_id = $id ORDER BY b.id DESC")->getResult();
        }
        foreach ($data as $key => $value) {

            $value->addenum = $db->query("SELECT sum(biaya) as total, keterangan as ket_enum FROM `projects_addendum`  
                WHERE STATUS = 'disetujui' AND project_id = $value->id
                GROUP BY tipe
                ORDER BY `projects_addendum`.`tipe`  DESC")->getResult();
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

        $addenum = $db->query("SELECT * FROM `projects_addendum`  
            WHERE STATUS = 'disetujui' AND project_id = $id
            GROUP BY tipe
            ORDER BY `projects_addendum`.`tipe`  DESC")->getResult();

        $customer = $db->query("SELECT member_detail.name, project_data_customer.phone, t.name as 'pic', t.telephone AS 'pic_telp' ,projects.luas, projects.metode_payment
            FROM project_data_customer 
            JOIN projects on projects.id = project_data_customer.project_id
            JOIN member_detail on member_detail.member_id = project_data_customer.member_id
            JOIN member_detail as t on t.member_id = projects.tukang_id
            WHERE project_id = $id limit 1")->getResult();

        $projek = $db->query("SELECT projects.id, projects.nomor_kontrak as no_sk, projects.project_number, projects.presentase_progress, product.paket_name, projects_update.tanggal,projects_persetujuan.dokumen, projects_persetujuan.dokumen_rab  FROM projects
            JOIN projects_detail on projects_detail.project_id = projects.id
            JOIN product on product.id = projects_detail.product_id
            LEFT JOIN projects_update on projects_update.project_id = projects.id
            LEFT JOIN projects_persetujuan on projects_persetujuan.nomor_kontrak = projects.nomor_kontrak
            WHERE projects.id = $id
            ORDER BY projects_update.id DESC
            LIMIT 1")->getRow();

        $termin = $db->query("SELECT id, tipe, project_id, nomor_invoice, biaya, keterangan, DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%e %b %Y') AS tanggal_terbit, DATE_FORMAT(ADDDATE(DATE_FORMAT(FROM_UNIXTIME(tanggal_dibuat), '%Y-%m-%d'), INTERVAL 7 DAY), '%e %b %Y') as jatuh_tempo, status
            FROM projects_pembayaran
            WHERE project_id = $id")->getResult();


        //get
        $nm = substr($sk->name, 0, 3);
        $array_bln = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII");
        // // if($sk->bulan == null){ $sk->bulan = 1;}
        // var_dump($sk->bulan);
        $bln = $array_bln[$sk->bulan];
        $nsk = $sk->id . '/' . $nm . '/' . $bln . '/' . $sk->tahun;
        // $projek->no_sk = $nsk;

        if ($termin == null) {
            $termin = [["title" => 'Hubungi Admin untuk pembuatan invoice']];
        } else {
            $a = 1;
            foreach ($termin as $t) {
                $t->biaya_tambahan = '-';
                $t->title = "Pembayaran Termin " . $a++;
            }
        }

        if ($addenum == null) {
            $addenum = null;
        } else {
            foreach ($addenum as $a) {
                if ($a->tipe == 1) {
                    $a->tipe = 'kurang';
                } else {
                    $a->tipe = 'tambah';
                }
            }
        }

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
            'exten' => $addenum,
            'data_customer' => $customer,
            'termin' => $termin,

        ];
        // var_dump($data);            

        return $data;
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
            WHERE projects.tukang_id = $id")->getResult();
        } else {
            return $db->query("SELECT project_data_customer.project_id, DATE_FORMAT(FROM_UNIXTIME(project_data_customer.created), '%e %b %Y') AS 'tanggal' , product.paket_name, chat.message, chat.user FROM project_data_customer 
            JOIN projects_detail ON projects_detail.project_id = project_data_customer.project_id
            JOIN product on product.id = projects_detail.product_id
            JOIN chat ON chat.project_id = project_data_customer.project_id
            WHERE project_data_customer.member_id = $id
            GROUP BY chat.project_id
            ORDER BY chat.id DESC")->getResult();
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
                        WHERE projects.id ");
    }

    public function chat_tukang($id)
    {
        $db = db_connect();
        return $db->query("SELECT DISTINCT projects.type, projects.id FROM projects WHERE projects.tukang_id = $id");
    }

    public function dchat($id)
    {
        $db = db_connect();
        return $db->query("SELECT DISTINCT chat.*, member_detail.name as tukang, project_data_customer.name as customer 
                                    FROM chat 
                                    JOIN projects on chat.project_id = projects.id 
                                    LEFT JOIN member_detail ON projects.tukang_id = member_detail.member_id 
                                    LEFT JOIN project_data_customer on chat.project_id = project_data_customer.project_id 
                                    WHERE chat.project_id = $id
                                ")->getResult();
    }
}
