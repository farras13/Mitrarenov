<?php
namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\ArtikelModel;
use App\Models\GeneralModel;
use DOMDocument;

class Sitemap extends BaseController
{
    function __construct()
    {
        $this->model = new GeneralModel();
        $this->artikel = new ArtikelModel();        
    }
    
    public function index()
    {
        $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';
        
        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $data = array(
            array('loc'=>base_url()),
            array('loc'=>base_url('sitemap-portofolio.xml')),
            array('loc'=>base_url('sitemap-gallery.xml')),
            array('loc'=>base_url('sitemap-desainrumah.xml')),
            array('loc'=>base_url('sitemap-berita.xml')),
            array('loc'=>base_url('sitemap-halaman.xml')),
        );
        
        foreach($data as $item) {
            // create <url> element
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $item['loc']);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
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
        $data = $this->artikel->getPages();
        foreach ($data as $key => $value) {
            $value->slug = base_url('berita').'/'.$value->slug;
        }
        $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';

        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $url = $doc->createElement('url');
        // create <loc> element
        $loc = $doc->createElement('loc', "https://www.mitrarenov.com/berita");
        $url->appendChild($loc);
        // create <lastmod> element
        $lastmod = $doc->createElement('lastmod', $datetime);
        $url->appendChild($lastmod);
        // append <url> to the <urlset> element
        $urlset->appendChild($url);
        foreach ($data as $r) {
            $link = strtolower($r->slug);
            $datetime = date("Y-m-d", $r->date).'T'.date("H:i:s", $r->date).'+00:00';
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $link);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap-berita.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
    }
    
    public function sitemap_portofolio(){
       $data = $this->model->getOrderBy('merawat', 'created', 'desc')->getResult();
       $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';

        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $url = $doc->createElement('url');
        // create <loc> element
        $loc = $doc->createElement('loc', "https://www.mitrarenov.com/portofolio");
        $url->appendChild($loc);
        // create <lastmod> element
        $lastmod = $doc->createElement('lastmod', $datetime);
        $url->appendChild($lastmod);
        // append <url> to the <urlset> element
        $urlset->appendChild($url);
        foreach ($data as $r) {
            $link = base_url('portofolio/' . $r->slug);
            $datetime = date('Y-m-d', $r->created).'T'.date('H:i:s', $r->created).'+00:00';
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $link);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap-portofolio.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
    }

    public function sitemap_gallery(){
        $data = $this->model->getOrderBy('gallery_pekerjaan', 'id', 'desc')->getResult();
        $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';

        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $url = $doc->createElement('url');
        // create <loc> element
        $loc = $doc->createElement('loc', "https://www.mitrarenov.com/gallery");
        $url->appendChild($loc);
        // create <lastmod> element
        $lastmod = $doc->createElement('lastmod', $datetime);
        $url->appendChild($lastmod);
        // append <url> to the <urlset> element
        $urlset->appendChild($url);
        foreach ($data as $r) {
            $link = 'https://office.mitrarenov.com/assets/main/images/photo_promo_paket/' . $r->image;
            $time = strtotime($r->created);
            $datetime = date('Y-m-d',$time).'T'.date('H:i:s', $time).'+00:00';
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $link);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap-gallery.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
    }
    
    public function sitemap_designrumah(){
        $data = $this->model->getOrderBy('design_rumah', 'created', 'DESC')->getResult();
        $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';

        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $url = $doc->createElement('url');
        // create <loc> element
        $loc = $doc->createElement('loc', "https://www.mitrarenov.com/desain-rumah/");
        $url->appendChild($loc);
        // create <lastmod> element
        $lastmod = $doc->createElement('lastmod', $datetime);
        $url->appendChild($lastmod);
        // append <url> to the <urlset> element
        $urlset->appendChild($url);
        foreach ($data as $r) {
            $link = base_url('desain-rumah/' . $r->slug);
            $datetime = date('Y-m-d', $r->created).'T'.date('H:i:s', $r->created).'+00:00';
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $link);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap-desainrumah.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
    }
    public function sitemap_halaman(){
        $data = $this->model->getOrderBy('page_website', 'id', 'DESC')->getResult();
        $datetime = date('Y-m-d').'T'.date('H:i:s').'+00:00';

        $doc = new DOMDocument();
        $doc->version = '1.0';
        $doc->encoding = 'UTF-8';
        
        $pi = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="public/sitemap.xsl"');
        $doc->appendChild($pi);
        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setattribute('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setattribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');
        
        $doc->appendChild($urlset);
        
        $url = $doc->createElement('url');
        // create <loc> element
        $loc = $doc->createElement('loc', "https://www.mitrarenov.com/");
        $url->appendChild($loc);
        // create <lastmod> element
        $lastmod = $doc->createElement('lastmod', $datetime);
        $url->appendChild($lastmod);
        // append <url> to the <urlset> element
        $urlset->appendChild($url);
        foreach ($data as $r) {
            $time = strtotime($r->created);
            $datetime = date('Y-m-d',$time).'T'.date('H:i:s', $time).'+00:00';
            $link = base_url('halaman/'.$r->url_page);
            $url = $doc->createElement('url');
            // create <loc> element
            $loc = $doc->createElement('loc', $link);
            $url->appendChild($loc);
            // create <lastmod> element
            $lastmod = $doc->createElement('lastmod', $datetime);
            $url->appendChild($lastmod);
            // append <url> to the <urlset> element
            $urlset->appendChild($url);
        }
        
        $doc->save('sitemap-halaman.xml');
        $this->response->setContentType('application/xml');
        $this->response->setBody($doc->saveXML());
        return $this->response;
    }
    
}

/* End of file Sitemap.php */





