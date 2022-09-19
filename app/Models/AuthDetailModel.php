<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthDetailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'member_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 1;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['member_id', 'name', 'photo', 'telephone', 'created_by', 'modified_by', 'referal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created';
    protected $updatedField  = 'modified';
}
