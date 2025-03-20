<?php

namespace App\Controllers\Supreme_court;
use App\Controllers\BaseController;
use App\Controllers\Filing\Diary;
use App\Controllers\Filing\Similarity;
use CodeIgniter\Controller;
class Content extends BaseController
{

    function __construct()
    {

    }
    public function index()
    {
        //echo 'welcome to Dashboard '; //exit();
        //echo '<pre>'; print_r($_SESSION);exit();
        $unique_id_processed_active = base64_decode($_REQUEST['id']);
        $content_url = base64_decode($_REQUEST['url']);
        $_SESSION['redirect_url_to'] = $content_url;
        $pageTitle = base64_decode($_REQUEST['title']);
        $old_smenu = $_REQUEST['old_smenu'];
        $content_url= str_replace(".php","",$content_url);
        $content_url=ucwords($content_url);
        $content_url= base_url($content_url);
        $data['pageTitle']=$pageTitle;
        $data['unique_id_processed_active']=$unique_id_processed_active;
        $data['content_url']=$content_url;
        $data['old_smenu']=$old_smenu;
        $data['left_side_menu_call']='Y';
        //return redirect()->to($content_url);
        //$result_view= redirect()->to($content_url);
        // echo $result_view;
        //echo $content_url;
        //$result_view= 'dsjgnknskgtrg ierjngiergrehbg jhebfiereri iregierhgire ihierhieyrb uhertiue';
        $result_view= view('sci_main_iframe',$data);
        // return view('templates/content',$data);
        echo $result_view;
        exit();



    }
    public function default_view()
    {
        echo view('sci_main_content');
    }
    public function get_sub_menu_list(){
        $menu_id = $_REQUEST['id'];
        $usercode=session()->get('login')['usercode'];
        /*$sqrs=get_sub_menus($usercode,$menu_id);
        $sub_menus = $sqrs;*/
        $data=array();
        $data['menu_id'] = $menu_id;
        $get_result= view('templates/left_side_bar_sub_menu',$data);
        echo $get_result;exit();
    }
    public function index_16mar24()
    {
        //echo 'welcome to Dashboard '; //exit();
        //echo '<pre>'; print_r($_SESSION);exit();
        $unique_id_processed_active = base64_decode($_REQUEST['id']);
        $content_url = base64_decode($_REQUEST['url']);
        //$pageTitle = base64_decode($_REQUEST['title']);
        $old_smenu = $_REQUEST['old_smenu'];
        // $content_url= str_replace(".php","",$content_url);
        $content_url=ucwords($content_url);
        // $content_url='Filing/Diary';
        //return redirect()->to('Filing/Diary');
        // return redirect()->to($content_url);
        //  header('Location:'.base_url($content_url));exit();
        /* $Url_Diary = new Diary();
        echo $Url_Diary->index();*/

        echo base_url($content_url);
        //echo $content_url;
        exit();

        /*echo 'unique_id_processed_active='.$unique_id_processed_active.' content_url='.$content_url.' pageTitle='.$pageTitle.' old_smenu='.$old_smenu;
        exit();*/

        /*
         $data['pageTitle']=$pageTitle;
         $data['unique_id_processed_active']=$unique_id_processed_active;
         $data['content_url']=$content_url;
         $data['old_smenu']=$old_smenu;
         echo view('templates/content',$data);*/





    }
    public function contentNavbar(){
        $menu_id = $_REQUEST['id'];
        $usercode=session()->get('login')['usercode'];
        $sqrs=get_sub_menus($usercode,$menu_id);
        $sub_menus = $sqrs;
        $data=array();
        $data['submenus'] = $sub_menus;
        return view('templates/top_menu',$data);
    }
    public function index_ok()
    {
        //echo 'welcome to Dashboard '; //exit();
        //echo '<pre>'; print_r($_SESSION);exit();
        $unique_id_processed_active = base64_decode($_REQUEST['id']);
        $content_url = base64_decode($_REQUEST['url']);
        $pageTitle = base64_decode($_REQUEST['title']);
        $old_smenu = $_REQUEST['old_smenu'];
       // $content_url= str_replace(".php","",$content_url);
       $content_url=ucwords($content_url);
       // $content_url='Filing/Diary';
        //return redirect()->to('Filing/Diary');
       // return redirect()->to($content_url);
      //  header('Location:'.base_url($content_url));exit();
       /* $Url_Diary = new Diary();
       echo $Url_Diary->index();*/
        $Url_Diary = new Similarity();
        echo $Url_Diary->index();
       echo base_url($content_url);
        //echo $content_url;
exit();

        /*echo 'unique_id_processed_active='.$unique_id_processed_active.' content_url='.$content_url.' pageTitle='.$pageTitle.' old_smenu='.$old_smenu;
        exit();*/

       /*
        $data['pageTitle']=$pageTitle;
        $data['unique_id_processed_active']=$unique_id_processed_active;
        $data['content_url']=$content_url;
        $data['old_smenu']=$old_smenu;
        echo view('templates/content',$data);*/





    }
    public function index_18mar24ok()
    {
        //echo 'welcome to Dashboard '; //exit();
        //echo '<pre>'; print_r($_SESSION);exit();
        $unique_id_processed_active = base64_decode($_REQUEST['id']);
        $content_url = base64_decode($_REQUEST['url']);
        $pageTitle = base64_decode($_REQUEST['title']);
        $old_smenu = $_REQUEST['old_smenu'];
        $content_url= str_replace(".php","",$content_url);
        $content_url=ucwords($content_url);
        $content_url= base_url($content_url);
         $data['pageTitle']=$pageTitle;
         $data['unique_id_processed_active']=$unique_id_processed_active;
         $data['content_url']=$content_url;
         $data['old_smenu']=$old_smenu;
        echo base_url($content_url);
       //return redirect()->to($content_url);
        //$result_view= redirect()->to($content_url);
       // echo $result_view;
        //echo $content_url;
        //$result_view= 'dsjgnknskgtrg ierjngiergrehbg jhebfiereri iregierhgire ihierhieyrb uhertiue';
        // $result_view= view('templates/content',$data);
       // return view('templates/content',$data);
        //echo $result_view;
        exit();



    }
}
