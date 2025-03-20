<?php

namespace App\Controllers\Supreme_court;
use CodeIgniter\Controller;
use App\Models\MasterModel;
class MasterController extends Controller
{
    protected $session;

    function __construct(){   
        $session = session();
        ini_set('memory_limit', '-1');
        $this->session = \Config\Services::session();
        $this->session->start();
       // $this->LoginModel = new LoginModel();
        helper(['url', 'form']);
        helper("functions");
        // if (!isset($_SESSION['dcmis_user_idd'])) {
        //     header('Location:'.base_url('Signout'));exit();
        // }
    }

    public function index(){

        $Model_query = new MasterModel();
        $tbl_name = 'random_user';
        $tblData = $Model_query->get_table_data($tbl_name);
        if(!empty($tblData)){
            $this->generateJson($tblData, $tbl_name);
        }else{
            $this->generateJson([], $tbl_name);
        }
        // echo "<pre>";
        // print_r($tblData); die;
        
    }


    public function generateJson($dataArr, $tbl_name){

        $baseDir = dirname(__DIR__, 3);
        $basePath = $baseDir.'/MasterJSON';
        $dir = $basePath; 
        // echo $dir; die;
        if ( !is_dir( $dir ) ) {
            mkdir( $dir, 0777, true);
            $fp = fopen($dir.'/'.$tbl_name.'.json', 'w');
            fwrite($fp, json_encode($dataArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            fclose($fp);            
        }else{
            $fp = fopen($dir.'/'.$tbl_name.'.json', 'w');
            fwrite($fp, json_encode($dataArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            fclose($fp); 
        }

    }


}
