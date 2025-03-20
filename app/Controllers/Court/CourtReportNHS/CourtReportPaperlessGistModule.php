<?php

namespace App\Controllers\Court\CourtReportNHS;
use CodeIgniter\Model;
use App\Controllers\BaseController;
use App\Models\Casetype;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class CourtReportPaperlessGistModule extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;

    function __construct()
    {   
        $this->model = new CourtMasterModel();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();

        //   if(empty(session()->get('filing_details')['diary_no'])){
        //     header('Location:'.base_url('Filing/Diary/search'));exit();
        // }else{
        //     $this->diary_no = session()->get('filing_details')['diary_no'];
        // }
    
    }
    public function index()
    {
        return view('Court/CourtReportNHS/gistmodule');
    }

}