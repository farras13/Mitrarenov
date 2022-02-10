<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'news';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 1;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'slug', 'description', 'image', 'date', 'meta_title', 'meta_description', 'meta_keyword', 'tagline'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'timestamp';
    protected $createdField  = 'created';
    protected $updatedField  = 'modified';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    function hot(){
        return $this->db->table('news')
        ->select('news.*, member_detail.name as penulis')
        ->join('member', 'member.id = news.created_by')
        ->join('member_detail', 'member_detail.member_id = member.id')
        ->orderBy('news.analyticsviews', 'desc')->get(8)->getResult(); 
    }
}
