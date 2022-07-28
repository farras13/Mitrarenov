<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    public function getSubKategori($id = null)
    {
        if ($id != null) {
            return $this->db->query('SELECT product.id, paket_name, slug, image_icon, category_id, category.type FROM product
                                        JOIN category on category.id = product.category_id  
                                        WHERE product.category_id = $id')->get();
        }else{
            return $this->db->query('SELECT product.id, paket_name, slug, image_icon, category_id, category.type FROM product
                                        JOIN category on category.id = product.category_id')->get();
        }
    }


}
