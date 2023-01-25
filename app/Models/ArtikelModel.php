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
        ->join('member', 'member.id = news.created_by', 'left')
        ->join('member_detail', 'member_detail.member_id = member.id', 'left')
        ->where('news.is_publish', 0)
        ->where('date <=', time())
        ->orderBy('news.hits', 'desc')->get(8)->getResult(); 
    }
    
    public function getPages()
    {
        // Select kolom url dan last_modified dari tabel pages
        return $this->db->table('news')
            ->select('slug, date')
            ->where('news.is_publish', 0)
            ->where('date <=', time())
            ->get()->getResult();
    }

    function kategori()
    {
        $cek =  $this->db->table('news')
        ->select('news.id, news.news_category as title, count(kategori) as banyak')
        ->where('news.is_publish', 0)
        ->where('date <=', time())
        ->groupBy('news.kategori')
        ->orderBy('banyak', 'desc')->get(8);
        if($cek){
            return $cek->getResult();;
        }else{
            return $this->tagline();
        }
    }
    
    function newest(){
        return $this->db->table('news')
        ->select('news.*, member_detail.name as penulis')
        ->join('member', 'member.id = news.created_by', 'left')
        ->join('member_detail', 'member_detail.member_id = member.id', 'left')
        ->where('news.is_publish', 0)
        ->where('date <=', time())
        ->orderBy('news.date', 'desc')->get(8)->getResult(); 
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
        ->where('news.is_publish', 0)
        ->where('date <=', time())
        ->groupBy('news.tagline')
        ->orderBy('banyak', 'desc')->get(8)->getResult();
    }

    function terkait($id)
    {
        return  $this->db->table('news')
        ->select('news.*')
        ->where('tagline', $id)
        ->where('date <=', time())
        ->where('news.is_publish', 0)
        ->orderBy('rand()')->get(4)->getResult();
    }
    
    function bycategory($id){
        return  $this->db->table('news')
            ->select('news.*')
            ->where('news_category', $id)
            ->where('news.is_publish', 0)
            ->where('news.date <=', time())
            ->orderBy('rand()')->get(4)->getResult();
    }

    function search_hot($s)
    {
         return $this->db->table('news')
        ->select('news.*, member_detail.name as penulis')
        ->join('member', 'member.id = news.created_by', 'left')
        ->join('member_detail', 'member_detail.member_id = member.id', 'left')
        ->where('news.is_publish', 0)
        ->where('date <=', time())
        ->like('title', $s)
        ->orderBy('news.date', 'desc')
        ->orderBy('news.hits', 'desc')->get(8)->getResult(); 
    }
}
