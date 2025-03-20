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

        // echo $today.">>".$time;
        // COURT LIST ********************************

        $getDataWithCourtNo = $this->vc_webcast_details->select('*')->findAll();

        // echo "<pre>";
        // print_r($getDataWithCourtNo);
        // die;
        if(!empty($getDataWithCourtNo))
        {

            // echo "<pre>";
            //  print_r($getDataWithCourtNo);
            $data['court'] = $getDataWithCourtNo;


        }else{
            $data['msg']='No record Found';
        }

        // echo "<pre>";
        // print_r($data);
        // die;


        // JOURNALIST LIST ********************************

        $getData = $this->master_mediapersions->select('*')->where('media_name !=','Test Media')->findAll();
//         echo "<pre>";
//         print_r($getData);
//         die;

        if(!empty($getData[0]))
        {

//              echo "<pre>";
//              print_r($getDataWithCourtNo);
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
        // echo "<pre>";
        // print_r($columnInsert);
        // die;


        $result = $this->master_mediapersions->insert($columnInsert);
        if ($result) {
            session()->setFlashdata("infomsg", 'Record Inserted Successfully!!!!');
            return redirect()->to('WebCasting/Home');
//            redirect(base_url('WebCasting/Home'));
        } else{
            session()->setFlashdata("infomsg", 'Already Exit .');
            return redirect()->to('WebCasting/Home');
//            redirect(base_url('WebCasting/Home'));

        }

    }


    public function editMediaPersons()
    {
       $id = $_POST['id'];

        $getJournalist = $this->master_mediapersions->select('*')->where('id',$id)->findAll();

        // echo "<pre>";
        // print_r($getJournalist);
        // die;

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
//        echo "<pre>";
//        print_r($columnUpdate);
//        die;


        $result = $this->master_mediapersions->update($id,$columnUpdate);

        if ($result) {
//            echo "SDGDSF";
//            die;
            session()->setFlashdata("infomsg", 'Record Updated Successfully!!!!!!');
            return redirect()->to('WebCasting/Home');

//            redirect(base_url('WebCasting/Home'));
        } else{

            session()->setFlashdata("infomsg", 'Already Exit');
            return redirect()->to('WebCasting/Home');

//            redirect(base_url('WebCasting/Home'));

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
        echo "DSF";
        echo "<pre>";
        print_r($_POST);
        die;
    }


}

?>