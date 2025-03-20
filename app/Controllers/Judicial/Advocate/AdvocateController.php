<?php

namespace App\Controllers\Judicial\Advocate;

use App\Controllers\BaseController;
use App\Models\Judicial\Advocate\AdvocateModel;

class AdvocateController extends BaseController
{
    public $AdvocateModel;

    function __construct()
    {
        $this->AdvocateModel = new AdvocateModel();
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }


    public function searchName()
    {
        return view('Judicial/Advocate/searchbyName');
    }

    public function requestNameSearch()
    {
        //$usercode = session()->get('login')['usercode'];

        $advName = $this->request->getVar('term');
        if (empty($advName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Name parameter is required']);
        }

        try {
            $result = $this->AdvocateModel->getAdvocate($advName);

            if (!empty($result) && count($result) > 0) {
                return $this->response->setJSON($result);
            } else {
                return $this->response->setJSON([]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching advocate data: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred while fetching data']);
        }
    }
}
