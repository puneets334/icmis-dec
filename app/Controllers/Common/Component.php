<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;

class Component extends BaseController
{


    function __construct()
    {

    }
    function index()
    {
        return view('Common/Component/demo',);
    }
    public function get_component_court_html()
    {

        echo component_court_html($_REQUEST['component_type']);exit();

    }
}
