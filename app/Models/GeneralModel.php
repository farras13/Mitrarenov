<?php

namespace App\Models;

use CodeIgniter\Model;

class GeneralModel extends Model
{
    function getAll($t, $l = null)
    {
        if ($l != null) {
            return $this->db->table($t)->get($l);
        } else {
            return $this->db->table($t)->get();
        }
    }

    public function getOrderBy($t, $row, $order, $l=null)
    {
        return $this->db->table($t)->orderBy($row, $order)->get($l);
    }

    public function getQuery($query)
    {
        return $this->db->query($query);
    }

    public function lastId($t, $l = null)
    {
        if ($l != null) {
            return $this->db->table($t)->orderBy('id', 'desc')->get($l);
        } else {
            return $this->db->table($t)->orderBy('id', 'desc')->get();
        }
    }
    function getWhere($t, $w, $l = null, $row = null, $order = null)
    {
        if ($l != null && $row != null && $order != null) {
            return $this->db->table($t)->where($w)->orderBy($row, $order)->get($l);
        } else if ($l != null && $row == null && $order == null) {
            return $this->db->table($t)->where($w)->get($l);
        } else if ($l == null && $row != null && $order != null) {
            return $this->db->table($t)->where($w)->orderBy($row, $order)->get();
        } else {
            return $this->db->table($t)->where($w)->get();
        }
    }

    public function ins($t, $object)
    {
        return $this->db->table($t)->ignore(true)->insert($object);
    }

    public function insB($t, $object)
    {
        return $this->db->table($t)->insert($object);
    }

    public function insId($t, $data)
    {
        $db = db_connect('default');
        $builder = $db->table($t);
        $builder->insert($data);
        return $db->insertID();
    }

    public function upd($t, $w, $object)
    {
        return $this->db->table($t)->where($w)->update($object);
    }

    public function del($t, $w = null)
    {
        if ($w != null) {
            return $this->db->table($t)->where($w)->delete();
        } else {
            return $this->db->table($t)->delete();
        }
    }

    public function findAllTukang($area_id)
    {
        $db = db_connect();
        $query = $db->query("SELECT a.id_tukang, b.email as email_tukang, c.name as nama_tukang, c.telephone, c.handphone from area_tukang a inner join member b on a.id_tukang = b.id left join member_detail c on b.id = c.member_id WHERE a.id_area = $area_id");
        $json = $query->getResult();

        return $json;
    }
}
