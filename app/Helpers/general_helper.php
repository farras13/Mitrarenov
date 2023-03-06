<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

// CodeIgniter Array Helpers

if(!function_exists('findTukang')) {
    function findTukang($area_id)
    {      
        $db = db_connect();
        $query = $db->query("SELECT a.id_tukang, b.email as email_tukang, c.name as nama_tukang, c.telephone from area_tukang a inner join member b on a.id_tukang = b.id left join member_detail c on b.id = c.member_id WHERE a.id_area = $area_id ");
        $json['data_tukang'] = $query->getRow();
		
		return json_encode($json);
    }
}

if(!function_exists('urlbase')) {
    function urlbase($string = null)
    {      
		return "https://www.mitrarenov.com/".$string;
    }
}

if(!function_exists('urlasset')) {
    function urlasset($string = null)
    {      
		return "https://www.mitrarenov.com/public/".$string;
    }
}

?>