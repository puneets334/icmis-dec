<?php

namespace App\Controllers\Listing\Case_drop;
use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Listing\Casetype;


class CaseDropController extends BaseController
{
   
    

    function __construct()
    {

        

     
    }



    public function index()
    {
        

        
        $caseTypeModel = new CaseType();
        $holiday_str = $this->nextHolidays();
      

      
        $caseTypes = $caseTypeModel->where('display', 'Y')
                                   ->where('casecode !=', 9999)
                                   ->orderBy('short_description')
                                   ->findAll();
                                 

       
        $session = session();
       
        $data = [
            'holiday_str' => $holiday_str,
            'caseTypes' => $caseTypes,
            'session_diary_no' => $session->get('session_diary_no'),
            'session_diary_yr' => $session->get('session_diary_yr') ?? date('Y')
        ];

       
        return view('Listing/case_drop/index', $data);
    }

    private function nextHolidays()
    {
        // This function should retrieve the next holidays data
        // Example static string, replace with actual logic
        return '"9-3-2024","14-3-2024","15-3-2024"';
    }





}
