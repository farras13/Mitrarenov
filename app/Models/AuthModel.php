<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'member';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 1;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['usergroup_id', 'email', 'password',  'created_by', 'modified_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created';
    protected $updatedField  = 'modified';

    function MemberLogin($w)
    {
        return $this->db->table('member a')
        ->where($w)
        ->get()->getResultArray();  
    }

    public function hitung()
    {
        $a =  $this->db->table('member a')->get()->getResultArray();
        return count($a);
    }

    public function ins($t,$object)
    {
       return $this->db->table($t)->ignore(true)->insert($object);
    }

    public function upd($t, $object, $w)
    {
        return $this->db->update($t, $object, $w);
    }

}
