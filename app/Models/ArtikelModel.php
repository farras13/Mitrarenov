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

    function kategori()
    {
        $cek =  $this->db->table('news')
        ->select('news.id, news.news_category as title, count(kategori) as banyak')
        ->groupBy('news.kategori')
        ->orderBy('banyak', 'desc')->get(8);
        if($cek){
            return $cek->getResult();;
        }else{
            return $this->tagline();
        }
    }

    // function kategori()
    // {
    //     return $this->db->table('news_category')
    //     ->select('id_news_category, news_category as title')
    //     ->orderBy('id_news_category', 'desc')
    //     ->get()->getResult();
        
    // }

    function tagline()
    {
        return  $this->db->table('news')
        ->select('news.id, news.tagline as title, count(tagline) as banyak')
        ->groupBy('news.tagline')
        ->orderBy('banyak', 'desc')->get(8)->getResult();
    }

    function terkait($id)
    {
        return  $this->db->table('news')
        ->select('news.*')
        ->where('tagline', $id)
        ->orderBy('id', 'desc')->get(4)->getResult();
    }

    function search_hot($s)
    {
         return $this->db->table('news')
        ->select('news.*, member_detail.name as penulis')
        ->join('member', 'member.id = news.created_by')
        ->join('member_detail', 'member_detail.member_id = member.id')
        ->like('title', $s)
        ->orderBy('news.analyticsviews', 'desc')->get(8)->getResult(); 
    }
}
