<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\AllocationTp;
use App\Models\Listing\VacationRegistrar;

class RegistrarVacation extends BaseController
{
    public $AllocationTp;
    public $VacationRegistrar;
    function __construct()
    {
        $this->AllocationTp= new AllocationTp();
        $this->VacationRegistrar = new VacationRegistrar();
        
    }

    public function registrar_vacation()
    {
        $ucode = session()->get('login')['usercode'];
        $user_ip = session()->get('login')['ipadd'];
        $totalInPool =  $this->AllocationTp->getTotalInPool();
        $registrars =  $this->AllocationTp->getRegistrars();
    
        $nextCourtWorkDay = date('d-m-Y', strtotime('+1 day'));
        $data = [
            'totalInPool' => $totalInPool,
            'nextCourtWorkDay' => $nextCourtWorkDay,
            'registrars' => $registrars,
        ];

        return view('Listing/RegistrarVacation/registrar_vacation', $data);
    }


    public function registrar_vacation_allocate()
    {
        $mainhead = $this->request->getPost('mainhead');
        $ucode = session()->get('login')['usercode'];
      
        $listDt = date("Y-m-d", strtotime($this->request->getPost('list_dt')));
        $noc = $this->request->getPost('noc');
        $judgeCodes = explode(",", $this->request->getPost('chked_jud_sel'));
       
        
        $affectedRows = $this->VacationRegistrar->insertNotReadyCases($listDt, $ucode, $judgeCodes, $noc);
        if ($affectedRows > 0) {
            echo "Listed $affectedRows cases.";
        } else {
            echo "Not Listed";
        }

       
    }


    
    

    
    

}
