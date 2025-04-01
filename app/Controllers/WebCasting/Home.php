<?php
namespace App\Controllers\WebCasting;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Entities\Model_vcWebCastDetails;
use App\Models\Entities\Model_mediapersions;




class Home extends BaseController
{

    public $vc_webcast_details;
    public $master_mediapersions;


    function __construct()
    {

        $this->vc_webcast_details =  new Model_vcWebCastDetails();
        $this->master_mediapersions = new Model_mediapersions();


    }
    public function index()
    {
        date_default_timezone_set('Asia/Kolkata');
        $today = date("Y-m-d");
        $time = date("h:i:s");
        $data=[];
 
        $getDataWithCourtNo = $this->vc_webcast_details->select('*')->orderBy('id','ASC')->findAll(); 
        if(!empty($getDataWithCourtNo))
        {
 
            $data['court'] = $getDataWithCourtNo;

        }else{
            $data['msg']='No record Found';
        }
 

        // JOURNALIST LIST ********************************

        $getData = $this->master_mediapersions->select('*')->where('media_name !=','Test Media')->findAll(); 

        if(!empty($getData[0]))
        {

            $data['fuel'] = $getData;


        }else{
            $data['msg']='No record Found';
        }

        return view('WebCasting/web_cast_management',$data);
    }

    public function ModelUpdate()
    {

        $id = $_POST['id'];
        $getDataWithCourtNo = $this->vc_webcast_details->select('*')->where('id',$id)->findAll();

        // echo "<pre>";
        // print_r($getDataWithCourtNo);
        // die;

        if(!empty($getDataWithCourtNo[0]))
        {
            echo json_encode($getDataWithCourtNo);
        }else{
            echo 'No Record Found';
        }


    }

    public function AddMediaPersons()
    {

        date_default_timezone_set('Asia/Kolkata');
        $today = date("Y-m-d");
        $time = date("h:i:s");
        $userCode = $_SESSION['login']['usercode'];
        $client_ip = getClientIP();

        $name = $_POST['name'];
        $media_name = $_POST['media_name'];
        $mobile = $_POST['mobile'];
        $display = $_POST['display'];


        $columnInsert = array(
            'name'=>$name,
            'media_name'=>$media_name,
            'mobile'=>$mobile,
            'display'=>$display,
            'create_on'=>date("Y-m-d H:i:s"),
            'last_login'=>date("Y-m-d H:i:s"),
            'updated_by'=>$userCode,
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by_ip'=>$client_ip
        );
        
        $datacheck_num = is_data_from_table('master.media_persions'," mobile = '$mobile' ", '*', 'N');
        
        if($datacheck_num == 0)
        {
            $result = $this->master_mediapersions->insert($columnInsert);
            if ($result) {
                session()->setFlashdata("infomsg", 'Record Inserted Successfully!!!!');
                return redirect()->to('WebCasting/Home');
               
            } else{
                session()->setFlashdata("infomsg", 'Already Exists.');
                return redirect()->to('WebCasting/Home');   

            }
        }else{
            session()->setFlashdata("infomsg", 'Mobile number already Exists.');
            return redirect()->to('WebCasting/Home');
        }

    }


    public function editMediaPersons()
    {
       $id = $_POST['id'];

        $getJournalist = $this->master_mediapersions->select('*')->where('id',$id)->findAll();
 

        if(!empty($getJournalist[0]))
        {
            echo json_encode($getJournalist);
        }else{
            echo 'No Record Found';
        }

    }

    public function UpdateMediaPerson()
    {

        $userCode = $_SESSION['login']['usercode'];
        $client_ip = getClientIP();

        $name = $_POST['name_editj'];
        $media_name = $_POST['media_name_editj'];
        $mobile = $_POST['mobile_editj'];
        $display = $_POST['display'];
        $id = $_POST['id_editj'];

        $columnUpdate = array(
            'name'=>$name,
            'media_name'=>$media_name,
            'mobile'=>$mobile,
            'display'=>$display,
            'updated_by'=>$userCode,
            'updated_on'=>'NOW()',
            'updated_by_ip'=>$client_ip,
        );
 
        $datacheck_num = is_data_from_table('master.media_persions'," mobile = '$mobile' ", '*', 'N');
        
        if($datacheck_num == 0)
        {
            $result = $this->master_mediapersions->update($id,$columnUpdate);

            if ($result) {
    
                session()->setFlashdata("infomsg", 'Record Updated Successfully!!!!!!');
                return redirect()->to('WebCasting/Home');
    
            } else{

                session()->setFlashdata("infomsg", 'Mobile number already Exists.');
                return redirect()->to('WebCasting/Home');    

            }
        }else{
                session()->setFlashdata("infomsg", 'Mobile number already Exists.');
                return redirect()->to('WebCasting/Home');
        }

    }

    public function Update_Courtno()
    {

        $userCode = $_SESSION['login']['usercode'];
        $client_ip = getClientIP();

        $fnNo = $_POST['fn'];
        $vcMeet = $_POST['vc'];
        $courtNo = $_POST['courtno'];
        $display = $_POST['display'];
        $id = $_POST['id'];
        $columnUpdate = array(

            'courtno'=>$courtNo,
            'is_nofn'=>$fnNo,
            'is_vcmeet'=>$vcMeet,
            'display'=>$display,
            'updated_by'=>$userCode,
            'updated_on'=>'NOW()',
            'updated_by_ip'=>$client_ip,

        );

        $result = $this->vc_webcast_details->update($id,$columnUpdate);

        if ($result) {

            session()->setFlashdata("infomsg", 'Record Updated Successfully!!!!!!!');
            return redirect()->to('WebCasting/Home');

//           return redirect(base_url('WebCasting/Home'));
        } else{

            session()->setFlashdata("infomsg", 'Already Exit');
            return redirect()->to('WebCasting/Home');
//            return redirect(base_url('WebCasting/Home'));

        }
    }

    public function DeleteJournalist()
    {
        $idToDelete = $_POST['id'];
        $query = $this->master_mediapersions->delete($idToDelete);
        if($query)
        {
            echo "Record Deleted Successfully";
        }
        else{
            echo "Record Not Deleted Successfully";
        }

    }

    public function DeleteCourtNo()
    {
        $idToDelete = $_POST['id'];
        $query = $this->vc_webcast_details->delete($idToDelete);
        if($query)
        {
            echo "Record Deleted Successfully";
        }
        else{
            echo "Record Not Deleted Successfully";
        }


    }

    // VC LINK METHODS*************************************

    public function insert_data()
    {

        $message = "";
        $inserted = 0;
        $notinserted = 0;
        if (isset($_FILES['userfile']['name'])) { //check if form was submitted
            $uploaddir = '/var/www/html/';
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $myfile = fopen("$uploadfile", "r") or die("Unable to open file!");

                $contents = fread($myfile, filesize($uploadfile));
                fclose($myfile);
                $variables = explode("\n", $contents);

                foreach ($variables as $variable) {
                    $var = explode("##", $variable);

                    $court = $var[0];
                    $issb = $var[1];
                    $btime = $var[2];
                    $webex = $var[3];
                    $remarks = $var[4];
                    $bench_date = $var[5];
                    if ($issb == 'N') {
                        $result_array = $this->Model_vcWebCastDetails->insert_vc($court, $issb, $btime, $webex, $remarks, $bench_date);
                    } else {
                        $result_array = $this->Model_vcWebCastDetails->insert_sb($court, $issb, $btime, $webex, $remarks, $bench_date);
                    }
                    if ($result_array == true) {
                        //$this->data['rev_result'] = "inserted";
                        $inserted = $inserted + 1;
                    } else {
                        $notinserted = $notinserted + 1;
                        //$this->data['rev_result'] = "not inserted";
                    }
                }
            }
        }
        if (isset($_POST['sorb']) && $_POST['sorb'] == 's') {

            if (isset($_POST['webex']) && $_POST['webex'] != null) {
                $result_array = $this->Model_vcWebCastDetails->insert_vc($_POST['virtual_court_number'], 'N', $_POST['bench_timing'], $_POST['webex'], $_POST['remarks'], $_POST['bench_date']);
                if ($result_array == true) {
                    $inserted = $inserted + 1;
                } else {
                    $notinserted = $notinserted + 1;
                }
            }
            if (isset($_POST['speclink']) && $_POST['speclink'] != null) {
                $result_array = $this->Model_vcWebCastDetails->insert_sb($_POST['virtual_court_number'], 'Y', $_POST['bench_timing'], $_POST['speclink'], $_POST['remarks'], $_POST['bench_date']);
                if ($result_array == true) {
                    $inserted = $inserted + 1;
                } else {
                    $notinserted = $notinserted + 1;
                }
            }
        }
        echo $inserted;
        echo $notinserted;
    }


}

?>