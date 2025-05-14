<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_category;

class Category extends BaseController
{
    public $LoginModel;
    public $Dropdown_list_model;
    public $Model_category;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_category = new Model_category();
        error_reporting(2);
    }

    public function index($category = '')
    {
        // $dummyParameter = $this->request->getGet('dummyParameter');

        // print_r($category);

        if (empty($_SESSION['filing_details']['diary_no'])) {
            //session()->setFlashdata("message_error", 'Please enter diary number or case number');
            $uri = current_url(true);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        }
        $result_casetype =  $_SESSION['filing_details']['casetype_id'];
        $ucode = $_SESSION['login']['usercode'];
        $user_section = $_SESSION['login']['section'];
        $logged_in_user_type = $_SESSION['login']['usertype'];
        if ($ucode != "1") {
            if (($user_section != 19 && $ucode != 146 && $ucode != 559 && $ucode != 742 && $ucode != 1363 && $ucode != 586 && $ucode != 1224 && $ucode != 148 && $ucode != 2431 && $ucode != 9980 && $ucode != 723 && $ucode != 724 && $ucode != 109 && $ucode != 2977)) {
                if ($logged_in_user_type == 14) {
                    $casetypeArray = array('9', '10', '19', '20', '25', '26', '39');
                    if (!in_array($result_casetype, $casetypeArray)) {
                        session()->setFlashdata("message_error", 'Category can be done in RP/CUR.P/CONT.P./MA');
                        $uri = current_url(true);
                        $getUrl = str_replace('/', '-', $uri->getPath());
                        header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
                        exit();
                    }
                } else {
                    session()->setFlashdata("message_error", 'Only BO is authorized for Verification');

                    $uri = current_url(true);
                    $getUrl = str_replace('/', '-', $uri->getPath());
                    header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
                    exit();
 
                }
            }
        }

        $data['main_categories'] = $this->Model_category->get_main_category_list();
        $data['fixed_for'] = $this->Dropdown_list_model->get_fixed_for_list();
        $data['keywords'] = get_from_table_json('ref_keyword', 'f', 'is_deleted');
        $data['acts'] = get_from_table_json('act_master', 'Y', 'display');
        $case_group = session()->get('filing_details')['case_grp'];
        $data['provision_of_law'] = $this->Dropdown_list_model->get_provision_of_law_list($case_group);
        $data['listed_before'] =  $this->Dropdown_list_model->get_listed_before('master_bench', 'id', 1, 6);
        $diary_number = session()->get('filing_details')['diary_no'];
        $data['mul_category'] = $this->Model_category->get_mul_category($diary_number);
        //pr($data['mul_category'] );
        $data['diary_details'] = session()->get('filing_details');

        $data['acts_section'] = $this->Model_category->getActsSections($diary_number);

        /* Added Keywords */

        $data['diary_keywords'] = $this->Model_category->get_diary_keywords($diary_number);
        $data['sensitive_case_reason'] = $this->Model_category->get_reason_sensitive_case($diary_number);
        $data['category'] =  $category;
        $data['selected_keywords'] = !empty($data['diary_keywords']) ? array_column($data['diary_keywords'], 'keyword_id') : null;
        return view('Filing/category_view', $data);
    }

    public function updateCategory($category = '')
    {
       // pr($_REQUEST);
        // if ($this->request->getMethod() === 'post' && $this->validate([
        //     'main_category' => ['label' => 'Main Category', 'rules' => 'required'],
        // ])) 
        $sub_category = "";

        if (!empty($this->request->getPost('sub_category'))) {


            $sub_category = $this->request->getPost('sub_category');
            if ($sub_category == "") {
                $sub_cdoe = $this->request->getPost('main_category');
                $submaster_id = $this->Model_category->getMasterCategoryId($sub_cdoe);
                $sub_category = $submaster_id[0]['id'];
            }

            $userid = session()->get('login')['empid'];
            $diary_number = session()->get('filing_details')['diary_no'];


            $updated = $this->Model_category->updateMulCategory($diary_number, $userid);

            $insert_nul_categoryarray = [
                'od_cat' => 1,
                'diary_no' => $diary_number,
                'submaster_id' => $sub_category,
                'mul_cat_user_code' => $userid,
                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'e_date' =>  date('Y-m-d H:i:s'),
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $userid
            ];

            $inserteddata = $this->Model_category->insertMulCategory($insert_nul_categoryarray);
        }
        $subCategoryPost = $this->request->getPost('sub_category');
        if ($subCategoryPost !== null && empty($subCategoryPost)) {
            session()->setFlashdata("error", 'Kindly Select Sub-Category.');
        }




        $data['main_categories'] = $this->Model_category->get_main_category_list();
        $data['fixed_for'] = $this->Dropdown_list_model->get_fixed_for_list();
        $data['keywords'] = get_from_table_json('ref_keyword', 'f', 'is_deleted');
        $data['acts'] = get_from_table_json('act_master', 'Y', 'display');
        $case_group = session()->get('filing_details')['case_grp'];
        $data['provision_of_law'] = $this->Dropdown_list_model->get_provision_of_law_list($case_group);
        $data['listed_before'] =  $this->Dropdown_list_model->get_listed_before('master_bench', 'id', 1, 6);
        $diary_number = session()->get('filing_details')['diary_no'];
        $data['mul_category'] = $this->Model_category->get_mul_category($diary_number);
        $data['diary_details'] = session()->get('filing_details');

        $data['acts_section'] = $this->Model_category->getActsSections($diary_number);

        /* Added Keywords */

        $data['diary_keywords'] = $this->Model_category->get_diary_keywords($diary_number);
        $data['sensitive_case_reason'] = $this->Model_category->get_reason_sensitive_case($diary_number);
        if (!empty($this->request->getPost('sub_category'))) {
            session()->setFlashdata("success_msg", 'Category Saved Successfully.');
        }
        $data['category'] =  $category;

        return view('Filing/category_view', $data);




        //return redirect()->to('Filing/Category');
        // $this->response->redirect(site_url('/Filing/Category'));
    }
    public function updateBasicDetails()
    {
        
        if (empty($_SESSION['filing_details']['diary_no'])) {

            //session()->setFlashdata("message_error", 'Please enter diary no');
            $uri = current_url(true);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        }

        $userid = session()->get('login')['empid'];
        $diary_number = session()->get('filing_details')['diary_no'];
        // $form_data = parse_str($this->request->getPost('post_data'), $form_data);
        $updated = $this->Model_category->updateDiaryBasicDetails($_POST, $diary_number, $userid);

        $checkforSensitiveCase = $this->Model_category->checkforSensitiveCase($diary_number);
        $sensitiveCaseCount = $checkforSensitiveCase[0]['count'];


        /* Add for sensitive case */
        $if_sensitive = "";
        $sensitive_case_reason = $_POST['sensitive_case_reason'];

        if (isset($_POST['if_sensitive']) && ($_POST['sensitive_case_reason'] != "")) {
            $if_sensitive = $_POST['if_sensitive'];
            $updated = $this->Model_category->updateSenstiveCase($if_sensitive, $sensitive_case_reason, $diary_number, $userid, $sensitiveCaseCount);
        } else if (!isset($_POST['if_sensitive']) && ($sensitiveCaseCount > 0)) {
            $updated = $this->Model_category->updateSenstiveCase($if_sensitive, $sensitive_case_reason, $diary_number, $userid, $sensitiveCaseCount);
        }

        if ($updated == 1) {
            $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_number);
            //$this->session->set(array('filing_details' => $get_main_table));
            session()->setFlashdata("success_msg", 'Details saved successfully.');
            $this->response->redirect(site_url('/Filing/Category'));
        } else {
            session()->setFlashdata("error", 'Detail not saved.');
            $this->response->redirect(site_url('/Filing/Category'));
        }
    }

    public function updateKeywords()
    {
        $userid = session()->get('login')['empid'];
        if (empty($_SESSION['filing_details']['diary_no'])) {

            $uri = current_url(true);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        }
        $diary_number = session()->get('filing_details')['diary_no'];
        if (isset($_POST['diary_keyword'])) {
            $keywords = $_POST['diary_keyword'];
            /* Add code for delete */

            $this->Model_category->deleteKeywords($diary_number);

            if (is_array($keywords)) {
                foreach ($keywords as $keyword) {
                    $keywords_array =  [
                        'diary_no' => $diary_number,
                        'keyword_id' => $keyword,
                        'display' => 'Y',
                        'updated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $userid,
                        'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                    ];
                  //  pr($keywords_array);
                    $this->Model_category->updateKeyword($keywords_array);
                }
            }
        }
        session()->setFlashdata("success_msg", 'Keywords saved successfully.');
        $this->response->redirect(site_url('/Filing/Category'));
    }

    public function updateActs()
    {
        
        $userid = session()->get('login')['empid'];
        if (empty($_SESSION['filing_details']['diary_no'])) {

            $uri = current_url(true);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        }
        $diary_number = session()->get('filing_details')['diary_no'];


        if (!empty($this->request->getPost('act'))) {

            /* Delete Queries for acts and section according to diary number - End*/
            $this->Model_category->deleteActs($diary_number);
            //$this->Model_category->deleteSection($diary_number);
            /* Delete Queries for acts and section according to diary number - End*/


            $acts = $this->request->getPost('act');
            $section_1 = $this->request->getPost('section_1');
            $section_2 = $this->request->getPost('section_2');
            $section_3 = $this->request->getPost('section_3');
            $section_4 = $this->request->getPost('section_4');
            $actcount = 0;

            if (is_array($acts)) {
                foreach ($acts as $act_key => $act) {
                    if ($act > 0) {

                        // check first act added or not according to diary no then insert act or update

                        $checkforAct = $this->Model_category->checkforAct($diary_number, $act);


                        if (!empty($checkforAct)) {

                            $this->Model_category->updateAct($diary_number, $userid, $act);
                            $section_data = $section_1[$act_key] . '(' . $section_2[$act_key] . ')(' . $section_3[$act_key] . ')(' . $section_4[$act_key] . ')';
                            $sectionArray = [
                                'act_id' => $checkforAct[0]['id'],
                                'entdt' => date('Y-m-d H:i:s'),
                                'display' => 'Y',
                                'user' => $userid,
                                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                                'section' => $section_data
                            ];
                            $section_inserted = $this->Model_category->insertActSection($sectionArray);

                            // insert only section and act updated by 
                        } else {
                            $actsArray = [
                                'diary_no' => $diary_number,
                                'entdt' => date('Y-m-d H:i:s'),
                                'display' => 'Y',
                                'user' => $userid,
                                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                                //'updated_from_ip'=>getClientIP(),
                                'act' => $act
                            ];

                            $act_inserted = $this->Model_category->insertActMain($actsArray);
                            $section_data = $section_1[$act_key] . '(' . $section_2[$act_key] . ')(' . $section_3[$act_key] . ')(' . $section_4[$act_key] . ')';
                            $sectionArray = [
                                'act_id' => $act_inserted,
                                'entdt' => date('Y-m-d H:i:s'),
                                'display' => 'Y',
                                'user' => $userid,
                                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                                'section' => $section_data
                            ];
                            $section_inserted = $this->Model_category->insertActSection($sectionArray);
                            //insert act first and then section
                        }
                    }
                }
            }

            session()->setFlashdata("success_msg", 'Acts and Section saved successfully.');
        }
        $this->response->redirect(site_url('/Filing/Category'));
    }
}
