<?php
namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\GeneralModel;

class Sitemap extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
        $this->artikel = new ArtikelModel();        
    }
    
    public function index()
    {
        echo view('sitemap/index');
    }

    public function sitemap_home()
    {
        $artikel = $this->artikel->select('slug, date')->where('is_publish', '0')->orderBy('date', 'DESC')->get(9)->getResult();
        $temp_artikel = [];
        foreach ($artikel as $key => $value) {
            $value->slug = base_url('berita').'/'.$value->slug;
            if($value->date <= time()){
                $temp_artikel[] = $value;
            }
        }
        $data['artikel'] = $temp_artikel; 
        $data['merawat'] = $this->model->getOrderBy('merawat', 'created', 'desc', 8)->getResult();
        $data['galery'] = $this->model->getOrderBy('gallery_pekerjaan', 'id', 'desc', 8)->getResult();
        echo view('sitemap/home', $data);
    }

    public function sitemap_berita(){
        $data['post'] = $this->artikel->getPages();
        foreach ($data['post'] as $key => $value) {
            $value->slug = base_url('berita').'/'.$value->slug;
        }
        echo view('sitemap/berita', $data);
    }
    
    public function sitemap_portofolio(){
       $data['data'] = $this->model->getOrderBy('merawat', 'created', 'desc')->getResult();
       echo view('sitemap/portofolio', $data);
    }

    public function sitemap_gallery(){
        $data['data'] = $this->model->getOrderBy('gallery_pekerjaan', 'id', 'desc')->getResult();
        echo view('sitemap/gallery', $data);
    }
    
    public function sitemap_designrumah(){
        $data['data'] = $this->model->getOrderBy('design_rumah', 'created', 'DESC')->getResult();
        echo view('sitemap/designrumah', $data);
    }
}

/* End of file Sitemap.php */





