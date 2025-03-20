<?php

namespace App\Controllers\Exchange;

use App\Controllers\BaseController;
use App\Models\Exchange\FilingTrapModel;

class Filingtrap extends BaseController
{
    public $FilingTrapModel;

    function __construct()
    {
        $this->FilingTrapModel = new FilingTrapModel();
    }

    public function completeView()
    {
        $usercode = session()->get('login')['usercode'];
        return view('Exchange/completeView');
    }

    public function getTrap()
    {
        $dno = $_REQUEST['dno'].$_REQUEST['dyr'];
        $data['trapData'] = $this->FilingTrapModel->get_trap($dno);
        return view('Exchange/filingTrapResult', $data);
    }
}
