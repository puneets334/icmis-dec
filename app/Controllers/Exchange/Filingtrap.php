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
        return view('Exchange/completeView');
    }

    public function getTrap()
    {
        $request = \Config\Services::request();
        $diaryNumber = $request->getPost('dno').$request->getPost('dyr');
        $data['trapData'] = $this->FilingTrapModel->get_trap($diaryNumber);
        return view('Exchange/filingTrapResult', $data);
    }
}
