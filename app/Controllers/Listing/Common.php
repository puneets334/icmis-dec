<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;

use App\Models\Listing\AllocationTp;
use App\Models\Listing\CaseType;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Subheading;
use App\Models\Listing\Roster;
use App\Models\Listing\Heardt;
use App\Models\Common\MasterModel;



class Common extends BaseController
{
    protected $MasterModel;
    public function __construct()
  {
    $this->MasterModel =  new MasterModel();
  }
    public function get_cl_print_mainhead()
    {
        $data['mainhead'] = $this->request->getPost('mainhead');
        $data['board_type'] = $this->request->getPost('board_type');

       
        $data['result_array'] = $this->MasterModel->get_cl_print_mainhead($data['mainhead'],$data['board_type']);
        return view('');
    }
}
