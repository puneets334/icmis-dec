<?php

namespace App\Controllers\Reports\FilingMonitoring;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;
use App\Models\Reports\DefaultReports\DefaultReportsModel;
use App\Models\Reports\DepartmentWise\DepartmentWiseModel;
use App\Models\Reports\FilingMonitoring\FilingMonitoringModel;

class FilingMonitoringController extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $MovementOfDocumentModel;
    public $DefaultReportsModel;
    public $DepartmentWiseModel;
    public $FilingMonitoringModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->MovementOfDocumentModel = new MovementOfDocumentModel();
        $this->DefaultReportsModel = new DefaultReportsModel();
        $this->DepartmentWiseModel = new DepartmentWiseModel();
        $this->FilingMonitoringModel = new FilingMonitoringModel();
    }


    public function index()
    {
        if(!empty($_REQUEST['sdate']) && !empty($_REQUEST['edate']))
        {
            $result = $this->FilingMonitoringModel->weekly_filing_stats();
            echo $result;

            if(!empty($result))
            {
                $from_email_id = 'sci@nic.in';
                $to_email_ids = 'ppavan.sc@nic.in,ca.pnbartwal@sci.nic.in,pavansid@gmail.com';

                $subject = 'Filing Section Statistical Information as on '.date("d/m/y h:i:s");
                
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

                // To send HTML mail, the Content-type header must be set

                $headers  = "From: sci@nic.in" . "\r\n";
                $headers .= "MIME-Version: 1.0". "\r\n";
                $headers .= "Content-type: text/html; charset=ISO-8859-1"."\r\n";

                $message = $result;

                // $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
                // $headers .= "From: sci@nic.in"."\r\n";
                // $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" ."Content-Transfer-Encoding: 7bit\n\n" . $output . "\n\n";

                /*if(mail($to_email_ids, $subject, $message, $headers))
                {
                    echo 'Email has been sent successfully.';
                }
                else
                {
                    echo 'Email sending failed.';
                }*/
            }
        }
        else
        {
            return view('Reports/filingMonitoring/filingStatistics');
        }
    }
}