<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\Model_fil_trap;



class RefilingReport extends BaseController
{
  public function index()
  {
    return view('Filing/refiling_report');
  }

  public function GetRefilingReport()
  {
    $data['dateFrom'] = $this->request->getPost('dateFrom');
    $data['model'] = new Model_fil_trap();
    $data['result'] = $data['model']->getRefilingReport( $data['dateFrom']);
    return view('Filing/refiling_report_data',$data);
  }
}
