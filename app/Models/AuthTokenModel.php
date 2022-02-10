<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthTokenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'token_login';
    protected $primaryKey       = 'id_token';
    protected $useAutoIncrement = true;
    protected $insertID         = 1;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['member_id', 'token', 'user_agent'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created';
    protected $updatedField  = 'updated';

}
