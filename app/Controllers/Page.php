<?php

namespace App\Controllers;

use App\Controllers\Api\BaseController;
use App\Models\DboModel;
use App\Models\GeneralModel;


class Page extends BaseController
{
    function __construct()
    {

    }
    
    public function index()
    {
        $url = current_url();
    }
}