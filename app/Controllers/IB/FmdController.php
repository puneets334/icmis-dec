<?php

namespace App\Controllers\IB;

use App\Controllers\BaseController;
use App\Models\IB\FmdModel;

class FmdController extends BaseController
{
    public $FmdModel;

    function __construct()
    {
        $this->FmdModel = new FmdModel();
    }

    public function set_dispose()
    {
        return view('IB/set_dispose');
    }

    public function set_dispose_process()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        $data['ct'] = $this->request->getGet('ct');
        $data['cn'] = $this->request->getGet('cn');
        $data['cy'] = $this->request->getGet('cy');
        $data['d_no'] = $this->request->getGet('d_no');
        $data['d_yr'] = $this->request->getGet('d_yr');

        $data['model'] = $this->FmdModel;
        
        return view('IB/set_dispose_process', $data);
    }
}
