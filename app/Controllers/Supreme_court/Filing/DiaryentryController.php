<?php

namespace App\Controllers\Supreme_court\Filing;
use CodeIgniter\Controller;
use App\Models\Filing\DiaryentryModel;

class DiaryentryController extends Controller
{
    protected $session;

    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $this->session->start();
        // $this->LoginModel = new LoginModel();
        helper(['url', 'form']);
        helper("functions");
        date_default_timezone_set('Asia/Calcutta');
        if (!isset($_SESSION['dcmis_user_idd'])) {
            header('Location:'.base_url('Signout'));exit();
        }
    }

    public function index()
    {
        $dcmis_user_idd = session()->get('dcmis_user_idd');


        $data['court_type'] = $this->get_court_type();
        $data['state_list'] = $this->get_state_list();

        $data['bench_list'] = $this->get_bench_list('358033','3');
        $data['district_list'] = $this->get_district_list('1');
        $role = $this->get_role();

        $data['role']="filing";
        if($role<=0 and $dcmis_user_idd!=1)
        {
           $data['role']="";
        }
        /*echo '<pre>'; print_r($_SESSION);//exit();
        echo '<pre>'; print_r($_SESSION);exit();*/
        return view('Filing/Diaryentry',$data);
    }

    public function get_court_type(){
        $Model_query = new DiaryentryModel();
        $court_data = $Model_query->get_court_type();
        return $court_data; die;
    }

    public function get_state_list(){
        $Model_query = new DiaryentryModel();
        $state_data = $Model_query->get_state_list();
        return $state_data; die;
    }

    public function get_role(){
        $dcmis_user_idd = session()->get('dcmis_user_idd');

        $Model_query = new DiaryentryModel();
        $role = $Model_query->get_role($dcmis_user_idd);
        return $role; die;
    }

    public function get_bench_list($state_id, $court_type){
        $Model_query = new DiaryentryModel();
        $bench_data = $Model_query->get_bench_list($state_id, $court_type);
        return $bench_data; die;
    }

    public function get_district_list($state_id){
        $Model_query = new DiaryentryModel();
        $dist_data = $Model_query->get_district_list($state_id);
        return $dist_data; die;
    }
}
