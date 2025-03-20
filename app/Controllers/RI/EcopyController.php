<?php
namespace App\Controllers\RI;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\RI\RIModel;

class EcopyController extends BaseController
{
    public $RIModel;
    public function __construct()
    {
        $this->RIModel = new RIModel();

    }
    public function index()
    {
        return view('RI/Ecopy/envelope_movement');
    }

    public function get_envelope_movement()
    {
        $envelopeData = $this->RIModel->get_envelope_data();
//        echo "<pre>";
//        print_r($envelopeData);
//        die;
        $data=[];
        if($envelopeData)
        {
            $data['envelopeData'] = $envelopeData;
        }else{
            $data['envelopeData']='';
        }
        echo  view('RI/Ecopy/envelope_movement_get',$data);

    }

    public function envelope_movement_save()
    {
//        var_dump($_POST);
//        die;
        if(!empty($_POST)){
            $barcode = $_POST['barcode'];
        }
//        echo "<pre>";
//        print_r($_SESSION);
//        die;
        $usercode = session()->get('login')['usercode'];
        $section = session()->get('login')['section'];
        $envelopeData = $this->RIModel->envelope_receive_data($barcode,$usercode,$section);

        echo json_encode($envelopeData);
        exit;

    }

    public function report()
    {
        return view('RI/Ecopy/envelope_movement_report');
    }

    public function envelope_movement_report_get()
    {

        $data=[];
        if(!empty($_POST))
        {

           $from_date =  date('Y-m-d',strtotime($_POST['from_date']));
           $to_date =  date('Y-m-d',strtotime($_POST['to_date']));
            $data['envelopeReport'] = $this->RIModel->envelope_report_data($from_date,$to_date);
            $data['title'] = "eCopying Reports : Envelopes Received by R & I Section from Copying Section: Dated ".date('d-m-Y',strtotime($_POST['from_date']))." to ".date('d-m-Y',strtotime($_POST['to_date']));
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;

            echo view('RI/Ecopy/envelope_movement_report_getdata',$data);
            exit;

        }else{
            echo "No record Found";
            exit;
        }




    }


}








?>