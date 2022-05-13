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

    public function getOrderBy($t, $row, $order)
    {
        return $this->db->table($t)->orderBy($row, $order)->get();
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
    function getWhere($t, $w, $l = null)
    {
        if ($l != null) {
            return $this->db->table($t)->where($w)->get($l);
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
}
