<?php

namespace App\Controllers\Copying;

use App\Controllers\BaseController;
use App\Models\Copying\Model_copying;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_post_bar_code_mapping;
use App\Models\Entities\Model_CopyingOrderIssuingApplicationNew;
use App\Models\Entities\Model_CopyingRequestVerify;
use App\Models\Entities\Model_CopyingApplicationDocuments;
use App\Models\Entities\Model_CopyingApplicationDocumentsA;
use App\Models\Entities\Model_CopyingRequestVerifyDocuments;
use App\Models\Entities\Model_CopyingReasonsForRejection;
use App\Models\Entities\Model_CopyingRole;
use App\Models\Entities\Model_users;
use App\Models\Entities\Model_CopyingTrap;
use App\Models\Entities\Model_CopyingApplicationDefects;
use App\Models\LoginModel;


class Copying extends BaseController
{

    public $Dropdown_list_model;
    public $Copying_model;
    public $PostBarCodeMapping;
    public $Model_CopyingOrderIssuingApplicationNew;
    public $Model_CopyingRequestVerify;
    public $Model_CopyingRequestVerifyDocuments;
    public $Model_CopyingReasonsForRejection;
    public $Model_CopyingRole;
    public $Model_CopyingApplicationDocuments;
    public $Model_CopyingApplicationDocumentsA;
    public $Model_users;
    public $Model_CopyingTrap;
    public $Model_CopyingApplicationDefects;
    public $LoginModel;

    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Copying_model = new Model_copying();
        $this->PostBarCodeMapping = new Model_post_bar_code_mapping();
        $this->Model_CopyingOrderIssuingApplicationNew = new Model_CopyingOrderIssuingApplicationNew();
        $this->Model_CopyingRequestVerify = new Model_CopyingRequestVerify();
        $this->Model_CopyingRequestVerifyDocuments = new Model_CopyingRequestVerifyDocuments();
        $this->Model_CopyingReasonsForRejection = new Model_CopyingReasonsForRejection();
        $this->Model_CopyingRole = new Model_CopyingRole();
        $this->Model_CopyingApplicationDocuments = new Model_CopyingApplicationDocuments();
        $this->Model_CopyingApplicationDocumentsA = new Model_CopyingApplicationDocumentsA();
        $this->Model_users = new Model_users();
        $this->Model_CopyingTrap = new Model_CopyingTrap();
        $this->Model_CopyingApplicationDefects = new Model_CopyingApplicationDefects();

        $this->LoginModel = new LoginModel();
    }
    public function orders()
    {
        return view('Copying/order_search_view');
    }

    public function download_order()
    {
        $dataArray = array(
            'diary' => $this->request->getPost('diary_no'),
            'diary_year' => $this->request->getPost('diary_year'),
            'order_date' => date('Y-m-d', strtotime($this->request->getPost('order_date')))
        );
        $file_path_row = $this->Copying_model->get_rop_path($dataArray);
        if (!empty($file_path_row)) {
            $file_path = $file_path_row[0]['file_path'];
            $data = file_get_contents(getBasePath().'/supreme_court/jud_ord_html_pdf/' . $file_path);
            force_download($file_path_row[0]['c_no'] . '.pdf', $data);

            
        } else {
            session()->setFlashdata("error", 'ROP not found!');
            $this->response->redirect(site_url('/Copying/Copying/orders'));
        }
    }

    public function barcodeconsume()
    {
        return view('Copying/barcode_consume_view');
    }

    public function getbarcodeconsume()
    {
        $data['barcode_consume'] = $this->Copying_model->get_consume_barcode();
  
        return view('Copying/barcode_consume_get_view', $data);
    }

    public function barcodesave()
    {
        $response = "";
        if (strlen(trim($this->request->getPost('barcode'))) >= 12) {
            $dataArray = array(
                'copying_application_id' => $this->request->getPost('app_id'),
                'barcode' => $this->request->getPost('barcode'),
                'envelope_weight' => $this->request->getPost('envelope_weight'),
                'module_flag' => 'ecopying',
                'is_consumed' => '1',
                'consumed_by' => session()->get('login')['usercode'],
                'consumed_on' => date("Y-m-d H:i:s"),
                'create_modify' => date("Y-m-d H:i:s"),
                'ent_time' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $this->PostBarCodeMapping->insert($dataArray);
            $this->db->transComplete();
            $response = 'Y';
            session()->setFlashdata("success_msg", 'Barcode Consumed Data saved Successfully');
        } else {
            $response = 'N';
            session()->setFlashdata("error", 'Proper Barcode Entry Required');
        }
        echo $response;
    }

    public function track()
    {
        return view('Copying/track');
    }

    public function getConsignmentDetails()
    {
        $response = $message = "";
        $barcode = $this->request->getPost('cn');
        if (strlen(trim($barcode)) >= 10) {
            $barcode_details = $this->Copying_model->getConsignmentDetails($barcode);
            if (empty($barcode_details)) {
                $data['response'] = 'N';
                $data['message'] = 'Consignment Number not found';
                $data['barcode_details'] = "";
                $data['cn_no'] = "";
            } else {
                $data['response']  = 'Y';
                $data['barcode_details'] = $barcode_details;
                $data['cn_no'] = $barcode;
                $data['message'] = "";
            }
        } else {
            $data['response']  = 'N';
            $data['message'] = 'Proper Consignment Number Required';
            $data['barcode_details'] = "";
            $data['cn_no'] = "";
        }
        return view('Copying/consignment_details', $data);
        exit;
    }

    public function copy_search()
    {

        $data['copy_category'] = $this->Dropdown_list_model->get_copy_category();
        // $cause_title=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date,
        // (current_date - diary_no_rec_date::date) no_of_days,casetype_id")->where(['diary_no'=>$caveat_no])->findAll(1);

        return view('Copying/copy_search', $data);
    }

    public function get_copy_search()
    { 
        error_reporting(0);
        $track_horizonal_timeline = array();

        $application_type = $this->request->getPost('application_type');
        $application_no = $this->request->getPost('application_no');
        $application_year = $this->request->getPost('application_year');
        $crn = $this->request->getPost('crn');
        $flag = $this->request->getPost('flag');

        $data['row_barcode'] = $data['postage_response'] = "";

        //$this->Model_CopyingOrderIssuingApplicationNew = new Model_CopyingOrderIssuingApplicationNew();
        $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('master.ref_copying_source as r ', 'r.id = copying_order_issuing_application_new.source', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('master.ref_copying_status as s ', 's.status_code = copying_order_issuing_application_new.application_status', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->select('(case when m.reg_no_display is not null then m.reg_no_display else 
        ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
        ma.c_status end) as case_status, copying_order_issuing_application_new.id, copying_order_issuing_application_new.application_number_display,
        copying_order_issuing_application_new.diary, copying_order_issuing_application_new.crn, copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.updated_on,
        copying_order_issuing_application_new.name, copying_order_issuing_application_new.mobile, copying_order_issuing_application_new.email, copying_order_issuing_application_new.allowed_request, 
        copying_order_issuing_application_new.dispatch_delivery_date, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.filed_by, 
        copying_order_issuing_application_new.court_fee, copying_order_issuing_application_new.postal_fee, copying_order_issuing_application_new.delivery_mode, r.description, s.status_description,
        copying_order_issuing_application_new.address');
        if ($flag == 'ano') {
            $this->Model_CopyingOrderIssuingApplicationNew->where(['application_reg_number' => $application_no, 'application_reg_year' => $application_year, 'copy_category' => $application_type]);
            $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
            $query = $this->db->getLastQuery();
//pr($query);
            $data['application_request'] = 'application';
        } else {
            $this->Model_CopyingOrderIssuingApplicationNew->where(['crn' => $crn]);
            $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
//
            $data['application_request'] = 'application';

            if (empty($data['result'])) {
                $this->Model_CopyingRequestVerify->join('main m', 'm.diary_no = copying_request_verify.diary', 'left');
                $this->Model_CopyingRequestVerify->join('main_a ma', 'ma.diary_no = copying_request_verify.diary', 'left');
                $this->Model_CopyingRequestVerify->join('master.ref_copying_source as r ', 'r.id = copying_request_verify.source', 'left');
                $this->Model_CopyingRequestVerify->join('master.ref_copying_status as s ', 's.status_code = copying_request_verify.application_status', 'left');
                $this->Model_CopyingRequestVerify->select('(case when m.reg_no_display is not null then m.reg_no_display else 
                                    ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
                                    ma.c_status end) as case_status,  copying_request_verify.id, 
                                    copying_request_verify.application_number_display, copying_request_verify.diary, copying_request_verify.crn, copying_request_verify.application_receipt, copying_request_verify.updated_on,
                                );             copying_request_verify.name,copying_request_verify.mobile, copying_request_verify.email, copying_request_verify.allowed_request, copying_request_verify.dispatch_delivery_date,
                                    copying_request_verify.application_status, copying_request_verify.filed_by,
                                    copying_request_verify.court_fee, copying_request_verify.postal_fee, copying_request_verify.delivery_mode, r.description, s.status_description, copying_request_verify.address ');
                $data['result'] = $this->Model_CopyingRequestVerify->where(['crn' => $crn])->findAll(1);
                $data['application_request'] = 'request';
            }
        }

        if (is_array($data['result'])) {
            foreach ($data['result'] as $row) {
                $this->PostBarCodeMapping->select('string_agg(barcode::text,\',\') as barcode,copying_application_id');
                $data['row_barcode'] = $this->PostBarCodeMapping->where(['copying_application_id' => $row['id']])->groupBy(['copying_application_id', 'barcode'])->findAll();
                if (!empty($data['row_barcode'])) {
                    $row_barocode = $data['row_barcode'][0];
                    $explode_barcode = explode(",", $row_barocode['barcode']);
                    for ($k = 0; $k < count($explode_barcode); $k++) {
                        $data['postage_response'] = $this->Copying_model->getConsignmentDetails($explode_barcode[$k]);
                    }
                }



                if ($data['application_request'] == 'application') {
                }
                if ($data['application_request'] == 'request') {
                    if ($row['allowed_request'] != 'request_to_available') {
                    }
                    $this->Model_CopyingRequestVerifyDocuments->join('master.ref_order_type r', 'copying_request_verify_documents.order_type = r.id', 'left');
                    $data['row1'] = $this->Model_CopyingRequestVerifyDocuments->where(['copying_order_issuing_application_id' => $row['id']])->findAll();
                }
            }
        }
        return view('Copying/copy_search_get', $data);


       // print_r($data['row_barcode']);
    }

    public function reason_rejection()
    {

        $this->Model_CopyingReasonsForRejection->join('master.users u', 'copying_reasons_for_rejection.user_id = u.usercode', 'left');
        $this->Model_CopyingReasonsForRejection->select('copying_reasons_for_rejection.*,u.name as user_name');

        $data['rejection_reasons'] = $this->Model_CopyingReasonsForRejection->where(['is_active' => 'T'])->findAll();
        return view('Copying/reason_rejection_list_view', $data);
    }

    public function reason_rejection_add()
    {
        return view('Copying/reason_rejection_add_view');
    }

    public function reason_reject_save()
    {
        $reasons_deactive_id = $this->request->getPost('reasons_deactive_id');
        $md5_reason = urldecode($reasons_deactive_id);
        $dataArray = array(
            'is_active' => 'F',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );
        $supdated = $this->Model_CopyingReasonsForRejection->update($md5_reason, $dataArray);
        if ($supdated) {
            session()->setFlashdata("success_msg", 'Reasons Rejection Deleted Successfully!');
        } else {
            session()->setFlashdata("error", 'Reasons Rejection Not Deleted Successfully!');
        }
        echo $supdated;
    }

    public function reason_reject_insert()
    {
        $response = "";
        $reject_reasons = $this->request->getPost('reasons');

        $user_id = session()->get('login')['usercode'];

        if (intval($user_id) > 0) {
            if ($reject_reasons != '') {

                $this->Model_CopyingReasonsForRejection->select('id');

                $data['reason_already_existed'] = $this->Model_CopyingReasonsForRejection->where(['reasons' => $reject_reasons])->findAll(1);
               //  $query=$this->db->getLastQuery();
               //  echo (string) $query;exit();

                if (empty($data['reason_already_existed'])) {
                    $dataArray = array(
                        'is_active' => 'T',
                        'reasons' => $reject_reasons,
                        'user_id' => $user_id,
                        'entry_time' => date("Y-m-d H:i:s"),
                        'ip_address' => getClientIP(),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    );

                    $this->db = \Config\Database::connect();
                    $this->db->transStart();
                    $this->Model_CopyingReasonsForRejection->insert($dataArray);
                    $this->db->transComplete();
                    $response = '1';
                    session()->setFlashdata("success_msg", 'Reasons for Rejection inserted Successfully.');
                } else {
                    $response = "2";
                    session()->setFlashdata("error", 'Reasons Rejection Already Inserted');
                }
            } else {
                $response = "3";
                session()->setFlashdata("error", 'Reasons Mandatory*');
            }
        } else {
            $response = "4";
            session()->setFlashdata("error", 'User Not Found !');
        }
        echo $response;
    }

    public function user_role()
    {
        $this->Model_CopyingRole->join('master.users u', 'copying_role.role_assign_by = u.usercode', 'left');
        $this->Model_CopyingRole->select('copying_role.*,u.name as role_assined_by');

        $data['user_roles'] = $this->Model_CopyingRole->where(['status' => 'T'])->findAll();

        $arr_result = array();
        if (!empty($data['user_roles'])) {
            foreach ($data['user_roles'] as $row) {

                $id = $row['role_assign_to'];
                $application_type = $row['application_type_id'];
                $role_assign_to = $row['role_assign_to'];

                if (strstr($application_type, ",")) {
                    $all_application_types = explode(",", $application_type);
                } else {
                    $all_application_types = array($application_type);
                }


                $applicant_type_id = $row['applicant_type_id'];

                $pplicantTypeIds = explode(",", $applicant_type_id);

                $role_assign_by = $row['role_assined_by'];
                $from_date = $row['from_date'];
                $date = strtotime($from_date);
                $fromdate = date('d-m-Y H:i:s', $date);

                $users_data = is_data_from_table('master.users', ['usercode' => $role_assign_to], 'name', 'R');
                //  print_r($users_data);
                $application_data = '';
                // $row_data = is_data_from_table_whereIn('master.copy_category',"'id',$all_application_types_www",'description','R');
                $application_row_data = is_data_from_table_whereIn('master.copy_category', "id", $all_application_types, 'description');
                if (!empty($application_row_data)) {
                    foreach ($application_row_data as $row_data) {
                        $application_type_name = $row_data['description'] . ',';
                        if ($application_data == '') {
                            $application_data = $row_data['description'];
                        } else {
                            $application_data = $application_data . ',' . $row_data['description'];
                        }
                    }
                }


                $applicant_data = '';
                foreach ($pplicantTypeIds as $val) {
                    // echo $val.'<br>';
                    switch ($val) {
                        case 1:
                            $applicant_type_name = 'Advocate on Record';
                            break;
                        case 2:
                            $applicant_type_name = 'Party/Party-in-person';
                            break;
                        case 3:
                            $applicant_type_name = 'Appearing Counsel';
                            break;
                        case 4:
                            $applicant_type_name = 'Third Party';
                            break;
                        case 6:
                            $applicant_type_name = 'Authenticated By AOR';
                            break;
                    } //end of switch case..

                    if ($applicant_data == '') {
                        $applicant_data = $applicant_type_name;
                    } else {
                        $applicant_data = $applicant_data . ',' . $applicant_type_name;
                    }
                }
                array_push($arr_result, array(
                    "name" => $users_data['name'], "application_type" => $application_data, "applicant_type" => $applicant_data,
                    "role_assign_by" => $role_assign_by, "id" => $id, "from_date" => $fromdate
                ));
            }
        }
        $data['arr_result'] = $arr_result;
        return view('Copying/user_role_list_view', $data);
    }

    public function user_role_delete()
    {
        $usercode_deactive_id = $this->request->getPost('usercode_deactive');
        $md5_reason = urldecode($usercode_deactive_id);
        $dataArray = array(
            'status' => 'F',
            'to_date' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );

        $supdated = update('master.copying_role', $dataArray, ['role_assign_to' => $usercode_deactive_id]);

        if ($supdated) {
            session()->setFlashdata("success_msg", 'Role Assigned Deleted Successfully!');
        } else {
            session()->setFlashdata("error", 'Role Assign Not Deleted!');
        }
        echo $supdated;
    }

    public function user_role_add()
    {
        $data['copy_category'] = get_from_table_json('copy_category');

        $user_type_section = session()->get('login')['section'];
        $user_id = session()->get('login')['usercode'];

        //echo $user_type_section;
        //print_r(session()->get('login'));

        if ($user_id == 1) {
            $data['users_data'] = is_data_from_table('master.users', ['display' => 'Y'], 'usercode,name');
        } else {
            $data['users_data'] = $this->Dropdown_list_model->get_copying_users($user_type_section);
        }
        //print_r($users_data);

        // print_r($data['copy_category']);
        return view('Copying/user_role_add_view', $data);
    }

    public function role_assign_add()
    {
        $response = "";
        $message = "";
        $application_type = $this->request->getPost('application_type');
        $applicant_type = $this->request->getPost('applicant_type');
        $role_assign = $this->request->getPost('role_assign');
        $applicantType = implode(",", $applicant_type);
        $applicationType = implode(",", $application_type);
        $user_id = session()->get('login')['usercode'];

        if (isset($user_id) != '') {
            if ($applicantType != '' && $role_assign != '') {
                $this->Model_CopyingRole->select('id');
                $data['role_already_assigned'] = $this->Model_CopyingRole->where(['role_assign_to' => $role_assign])->where(['status' => 'T'])->findAll();
                if (empty($data['role_already_assigned'])) {

                    $dataArray = array(
                        'status' => 'T',
                        'applicant_type_id' => $applicantType,
                        'role_assign_by' => $user_id,
                        'application_type_id' => $applicationType,
                        'role_assign_to' => $role_assign,
                        'from_date' => date("Y-m-d H:i:s"),
                        'ip_address' => getClientIP(),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    );
                    $this->db = \Config\Database::connect();
                    $this->db->transStart();
                    $this->Model_CopyingRole->insert($dataArray);
                    $this->db->transComplete();
                    $response = '1';
                    $message = "Role Assign Successfully!";

                    session()->setFlashdata("success_msg", 'Role Assign Successfully!');
                } else {
                    $response = "2";
                    $message = "User Role Already Assigned !";
                    session()->setFlashdata("error", 'User Role Already Assigned !');
                }
            } else {
                $response = "3";
                $message = "All fields are Mandatory";
                session()->setFlashdata("error", 'All fields are Mandatory*');
            }
        } else {
            $response = "4";
            $message = "User Not Found !";
            session()->setFlashdata("error", 'User Not Found !');
        }
        echo $response;
    }
    public function application_search()
    {
        $usertype = session()->get('login')['usertype'];

        if (empty(session()->get('login'))) {
            exit;
        }
        if ($usertype == 50 || $usertype == 51 || $usertype == 17) {
        }

        $data['copy_category'] = get_from_table_json('copy_category');

        return view('Copying/application_search_view', $data);
    }

    public function get_application_search()
    {

        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $application_type = $this->request->getPost('application_type');
        $applicant_type = $this->request->getPost('applicant_type');
      


        $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');

        $this->Model_CopyingOrderIssuingApplicationNew->select('(case when m.reg_no_display is not null then m.reg_no_display else 
        ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
        ma.c_status end) as case_status, copying_order_issuing_application_new.*');

        $this->Model_CopyingOrderIssuingApplicationNew->where(['source' => 6]);

        if (!empty($application_type)) {
            $this->Model_CopyingOrderIssuingApplicationNew->whereIn('copy_category', $application_type);
        }

        if (!empty($applicant_type)) {
            $this->Model_CopyingOrderIssuingApplicationNew->whereIn('filed_by', $applicant_type);
        }
       // $this->Model_CopyingOrderIssuingApplicationNew->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

        $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.application_receipt) BETWEEN '$from_date' and '$to_date'");
        $data['all_application_search'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
        $user_verification_details = [];

       // print_r($this->Model_CopyingOrderIssuingApplicationNew->getLastQuery()); 
      //  pr( $data['all_application_search'] );

        $main_array = array();
        if (is_array($data['all_application_search'])) {

           
            foreach ($data['all_application_search'] as $row) {
              
                $this->Model_CopyingApplicationDocuments->join('master.ref_order_type r', 'copying_application_documents.order_type = r.id', 'left');
                $this->Model_CopyingApplicationDocuments->select('r.order_type order_name, copying_application_documents.*');
                $this->Model_CopyingApplicationDocuments->where(['copying_order_issuing_application_id' => $row['id'], 'sent_to_applicant_on' => null]);

                $all_documents = $this->Model_CopyingApplicationDocuments->findAll();


                
                if (!empty($all_documents)) {
                    foreach ($all_documents as $docs) {
                        $main_array[$row['id']][] = $docs;
                    }
                }
                $all_data = array();
                if ($row['filed_by'] == 2 || $row['filed_by'] == 3 || $row['filed_by'] == 4) {
                    $all_data = $this->Copying_model->getUserVerficationDetails($row['mobile'], $row['email'], $row['diary']);
                }
                if (is_array($all_data)) {
                    foreach ($all_data as $all_user_ver_row) {
                        $user_verification_details[$row['id']][] = $all_user_ver_row;
                    }
                }
            }
        }

       // pr( $main_array);
        $data['all_docs_array'] = $main_array;
        //   print_r($user_verification_details);
        $data['user_verification_details'] = $user_verification_details;


        // $query=$this->db->getLastQuery();
        // echo (string) $query;exit();

        return view('Copying/application_search_get_view', $data);
    }
    public function bulk_status()
    {
        $this->Model_users->join('copying_order_issuing_application_new coian', 'users.usercode = coian.adm_updated_by');
        $this->Model_users->distinct();
        $this->Model_users->select('usercode,users.name,empid');
        $this->Model_users->whereNotIn('empid', [1, 3]);
        $data['all_copying_users'] = $this->Model_users->findAll();
        return view('Copying/bulkStatus_update_view', $data);
    }

    public function bulk_status_get_data()
    {
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $usercode = $this->request->getPost('userName');
        if (!empty($from_date) && !empty($to_date)) {

            if ($usercode != null && $from_date != '1970-01-01' && $to_date != '1970-01-01') {
                $this->Model_CopyingOrderIssuingApplicationNew->join('master.users u', 'u.usercode=copying_order_issuing_application_new.adm_updated_by', 'left');
                $this->Model_CopyingOrderIssuingApplicationNew->select("copy_category,application_reg_number,copying_order_issuing_application_new.id,application_number_display,diary,
                concat(copying_order_issuing_application_new.name,
                  case when filed_by=1 then ' (Adv)' else
                  case when filed_by=2 then ' (Party)' else case when filed_by=3 then ' (AC)' else 
                  case when filed_by=4 then ' (Other)' end end end end) as name,
                  date(application_receipt) as received_on,u.name as user,empid");

                $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.updated_on) BETWEEN '$from_date' and '$to_date'");
                $this->Model_CopyingOrderIssuingApplicationNew->where(['application_status' => 'P', 'source' => 1]);
                if (intval($usercode) > 0) {
                    $this->Model_CopyingOrderIssuingApplicationNew->where(['usercode' => $usercode]);
                    $data['user_detail'] = is_data_from_table('master.users', ['usercode' => $usercode], 'name, empid', 'R');
                }
                $data['from_date'] =  date('d-m-Y', strtotime($from_date));
                $data['to_date'] = date('d-m-Y', strtotime($to_date));

                $data['fromDate'] = date('Y-m-d', strtotime($from_date));
                $data['toDate'] = date('Y-m-d', strtotime($to_date));
                $this->Model_CopyingOrderIssuingApplicationNew->orderBy('date(application_receipt),copy_category,application_reg_number');

                $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();

            }
            return view('Copying/get_bulk_status_view', $data);
        }
    }


    public function bulk_status_index()
    {
        $this->Model_users->join('copying_order_issuing_application_new coian', 'users.usercode = coian.adm_updated_by');
        $this->Model_users->distinct();
        $this->Model_users->select('usercode,users.name,empid');
        $this->Model_users->whereNotIn('empid', [1, 3]);

        if (isset($_POST['view'])) {
            $data['user_detail']  = "";
            $from_date = $this->request->getPost('from_date');
            $to_date = $this->request->getPost('to_date');
            $usercode = $this->request->getPost('userName');
            if ($usercode != null && $from_date != '1970-01-01' && $to_date != '1970-01-01') {
                $this->Model_CopyingOrderIssuingApplicationNew->join('master.users u', 'u.usercode=copying_order_issuing_application_new.adm_updated_by', 'left');
                $this->Model_CopyingOrderIssuingApplicationNew->select("copy_category,application_reg_number,copying_order_issuing_application_new.id,application_number_display,diary,
                concat(copying_order_issuing_application_new.name,
                  case when filed_by=1 then ' (Adv)' else
                  case when filed_by=2 then ' (Party)' else case when filed_by=3 then ' (AC)' else 
                  case when filed_by=4 then ' (Other)' end end end end) as name,
                  date(application_receipt) as received_on,u.name as user,empid");

                $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.updated_on) BETWEEN '$from_date' and '$to_date'");
                $this->Model_CopyingOrderIssuingApplicationNew->where(['application_status' => 'P', 'source' => 1]);
                if (intval($usercode) > 0) {
                    $this->Model_CopyingOrderIssuingApplicationNew->where(['usercode' => $usercode]);
                    $data['user_detail'] = is_data_from_table('master.users', ['usercode' => $usercode], 'name, empid', 'R');
                }
                $data['from_date'] =  date('d-m-Y', strtotime($from_date));
                $data['to_date'] = date('d-m-Y', strtotime($to_date));

                $data['fromDate'] = date('Y-m-d', strtotime($from_date));
                $data['toDate'] = date('Y-m-d', strtotime($to_date));
                $this->Model_CopyingOrderIssuingApplicationNew->orderBy('date(application_receipt),copy_category,application_reg_number');


                $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();

                //   print_r($data['user_detail']);
                //   exit;

            }
        }

        $data['all_copying_users'] = $this->Model_users->findAll();
        // $query=$this->db->getLastQuery();
        // echo (string) $query;exit();
        return view('Copying/bulkStatus_update_view', $data);
        // print_r($all_copying_users);
    }

    function bulkStatusUpdate()
    {
        $idSelected = $this->request->getPost('idSelected');
        $all_ids = explode(",", $idSelected);
        $usercode = session()->get('login')['usercode'];
        $dataArray = array(
            'application_status' => 'R',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'adm_updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );
        $this->db = \Config\Database::connect();
        $this->db->transStart();
        $supdated = updateIn('copying_order_issuing_application_new', $dataArray, 'id', $all_ids);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            session()->setFlashdata("error", 'Data not Updated!');
            return 'no';
        } else {
            $text = $this->bulk_status_get_data();
            return $text;
            session()->setFlashdata("success_msg", 'Data Updated Successfully');
        }
    }

    function application_status()
    {
        $data['copy_status'] = is_data_from_table('master.ref_copying_status', null, '*');
        if (!empty($this->request->getPost('category')))
            $app_no = trim($this->request->getPost('category')) . '-' . $this->request->getPost('app_no') . '/' . $this->request->getPost('year');
        $data['app_no']  = $data['category_view'] = $data['year'] = "";
        if (isset($app_no) &&  $this->request->getPost('app_no') != '') {
            $data['app_no'] = $this->request->getPost('app_no');
            $data['category_view'] = $this->request->getPost('category');
            $data['year'] = $this->request->getPost('year');

            $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('master.users as u ', 'u.usercode=m.dacode', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('master.usersection as us ', 'us.id=u.section', 'left');
            // $this->Model_CopyingOrderIssuingApplicationNew->join('copying_application_documents as b', 'copying_order_issuing_application_new.id = b.copying_order_issuing_application_id', 'left');
            // $this->Model_CopyingOrderIssuingApplicationNew->join('copying_application_documents_a as ba ', 'copying_order_issuing_application_new.id = ba.copying_order_issuing_application_id', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->select("copying_order_issuing_application_new.*,
            ,(case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as reg_no_display,
            concat((case when m.reg_no_display is not null then m.pet_name else ma.pet_name end),' Vs ',
                (case when m.reg_no_display is not null then m.res_name else ma.res_name end)) as title , (case when tentative_section(case when m.diary_no is null then ma.diary_no else m.diary_no end) is null then us.section_name else
 tentative_section(case when m.diary_no is null then ma.diary_no else m.diary_no end)  end) as section_name");
            $this->Model_CopyingOrderIssuingApplicationNew->where(['application_number_display' => $app_no]);
            $data['application_details'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();


            if (!empty($data['application_details'])) {
                $application_details_id =  $data['application_details'][0]['id'];

                $data['app_documents'] = is_data_from_table('copying_application_documents', ['copying_order_issuing_application_id' => $application_details_id], '*');
                if (empty($data['app_documents'])) {
                    $data['app_documents'] = is_data_from_table('copying_application_documents_a', ['copying_order_issuing_application_id' => $application_details_id], '*');
                }


                $data['copying_order_issuing_application_id'] = $application_details_id;
                if (!empty($data['app_documents'])) {
                    foreach ($data['app_documents'] as $app_Docs) {
                        $order_type = $app_Docs['order_type'];
                        $data_order_type = is_data_from_table('master.ref_order_type', ['id' => $order_type], 'order_type', 'R');
                        $data_orderType[$order_type] = $data_order_type['order_type'];
                    }
                }


                $data['order_type_display'] = $data_orderType;
                $this->Model_CopyingTrap->join('master.users as u ', 'u.usercode=copying_trap.updated_by', 'left');
                $this->Model_CopyingTrap->join('master.ref_copying_status as prev ', 'prev.status_code = copying_trap.previous_value', 'left');

                $this->Model_CopyingTrap->join('master.ref_copying_status as new ', 'new.status_code=copying_trap.new_value', 'left');
                $this->Model_CopyingTrap->select('prev.status_description as prev,new.status_description as new,
                name,empid,copying_trap.updated_on');
                $this->Model_CopyingTrap->where(['copying_application_id' => $application_details_id]);

                $data['trap_list'] = $this->Model_CopyingTrap->findAll();
                //  $query=$this->db->getLastQuery();
                //  echo (string) $query;

                $query = $this->Model_CopyingApplicationDefects->select('ref_order_defect_id,remark')
                    ->where('defect_cure_date', null)
                    ->where('copying_order_issuing_application_id', "(SELECT id FROM copying_order_issuing_application_new WHERE application_number_display='$app_no')", false)
                    ->get();
                $data['show_defects'] = $query->getResultArray();
            }
            //print_r($data['application_details']);
        }


        $data['app_name'] = 'Application Status Update';
        $data['defects'] = is_data_from_table('master.ref_order_defect', null, '*');
        $data['copy_category'] = is_data_from_table('master.copy_category', null, '*');
        return view('Copying/application_status', $data);
    }

    public function application_status_update()
    {

        if ($this->request->getPost('or_defects')) {
            $defect = $this->request->getPost('or_defects');
            $fee_defecit = $this->request->getPost('fee_defecit');
            $remark = $this->request->getPost('remark');
            $feepay = $this->request->getPost('feePay');
        } else {
            $defect = array(0);
            $feepay = "";

        }

        $app_id = $this->request->getPost('app_id');
        if (!empty($app_id)) {
            $application_status = $this->request->getPost('application_status');

            if ($application_status == 'R') {
                $dataArray = array(
                    'application_status' => $application_status,
                    'ready_date' => date("Y-m-d H:i:s"),
                    'ready_remarks' => intval($feepay) ? $feepay : "NULL",
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'D') {
                $dataArray = array(
                    'application_status' => $application_status,
                    'dispatch_delivery_date' => date("Y-m-d H:i:s"),
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'C') {

                $dataArray = array(
                    'application_status' => $application_status,
                    'is_deleted' => 'f',
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'F') {
                $defects_ids = implode(", ", $defect);
                $dataDefectsArray = array(
                    'defect_cure_date' =>  date("Y-m-d H:i:s"),
                    'defect_cured_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );

                $this->Model_CopyingApplicationDefects->set($dataDefectsArray)
                    ->where('copying_order_issuing_application_id', $app_id)
                    ->whereNotIn('ref_order_defect_id', explode(",", $defects_ids))
                    ->update();



                foreach ($defect as $all_defect) {

                    $query_def = $this->db->table('copying_application_defects')
                        ->select('ref_order_defect_id')
                        ->where('copying_order_issuing_application_id', $app_id)
                        ->where('ref_order_defect_id', $all_defect)
                        ->where('defect_cure_date IS NULL')
                        ->get();

                    //$ref_order_defect_id = is_data_from_table('copying_application_defects', ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect, 'defect_cure_date is' => null], 'ref_order_defect_id', 'R');
                    if ($query_def->getNumRows() >= 1) {
                        if ($all_defect == 1) {
                            $updateRemark = [
                                'remark' => !empty($fee_defecit) ? $fee_defecit : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $dupdated = update('copying_application_defects', $updateRemark, ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect]);
                        } else if ($all_defect == 12) {
                            $updateRemark = [
                                'remark' => !empty($remark) ? $remark : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $dupdated = update('copying_application_defects', $updateRemark, ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect]);
                        }
                    } else {
                        if ($all_defect == 1) {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'remark' => !empty($fee_defecit) ? $fee_defecit : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                        } else if ($all_defect == 12) {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'remark' => !empty($remark) ? $remark : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                        } else {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'remark' => "",
                            ];
                        }
                        insert('copying_application_defects', $insertDefectArray);
                    }
                }
                $dataArray = array(
                    'application_status' => $application_status,
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else {
                $dataArray = array(
                    'application_status' => $application_status,
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            }
            if ($application_status != 'F') {
                $dataDefectsArray = array(
                    'defect_cure_date' =>  date("Y-m-d H:i:s"),
                    'defect_cured_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                update('copying_application_defects', $dataDefectsArray, ['copying_order_issuing_application_id' => $app_id]);
            }


            $app_data = is_data_from_table('copying_order_issuing_application_new', ['id' => $app_id], '*', '');


//            if($this->request->getPost('application_status')=='F'){
//                $defect_data = is_data_from_table('copying_application_defects', ['copying_order_issuing_application_id' => $app_id], '*', '');
//                sendSMS($app_data['mobile'], "Following Defect(s) found in application No. ".$app_data['application_number_display']."- ".$defect_data['remark']." #Copying Branch. - Supreme Court of India", '1107161243443625778');
//             }
//             if($this->request->getPost('application_status')=='R'){
//                 sendSMS($app_data['mobile'], "Your application No. ".$app_data['application_number_display']." received in Copying Branch is ready for Dispatch. - Supreme Court of India", '1107161243451962063');
//             }
//             if($this->request->getPost('application_status')=='D'){
//                 sendSMS($app_data['mobile'], "Your application No. ".$app_data['application_number_display']." received in Copying Branch is Delivered. - Supreme Court of India", '1107161243456951452');
//             }

            if ($supdated) {
                session()->setFlashdata("success_msg", 'Data Updated Sucessfully');
            } else {
                session()->setFlashdata("error", 'Data not updated');
            }
            $this->response->redirect(site_url('/Copying/Copying/application_status'));
        } else {
            session()->setFlashdata("error", 'Data not updated');
            $this->response->redirect(site_url('/Copying/Copying/application_status'));
        }
    }

    function send_sms($mobile, $message, $templateID)
    {
        $otp_url = "http://10.25.78.5/eAdminSCI/a-push-sms-gw?mobileNos=$mobile&message=" . rawurlencode($message) . "&typeId=29&myUserId=NIC001001&myAccessId=root&templateId=$templateID";
        $otp_res = (array)json_decode(file_get_contents($otp_url));
    }


    function specimen_signature()
    {
        $data['app_name'] = "Specimen Signature";
       $aor_code = $this->request->getPost('aor_code');
        $data['aor_code'] = "";
        if (!empty($aor_code) && $aor_code != '')
            $data['aor_code'] = $aor_code;
        return view('Copying/specimen_signature', $data);
    }

    public function dashboard()
    {
        return view('Copying/dashboard_view');
    }
    public function dashboard_count()
    {
        $from_date = date("Y-m-d", strtotime($this->request->getPost('from_date')));
        $to_date = date("Y-m-d", strtotime($this->request->getPost('to_date')));
        $data['offline_copy_pending']= $data['offline_copy_disposed']= $data['offline_total_filed']=$data['e_copy_pending']=  $data['e_copy_disposed'] =  $data['total_filed'] =0;
        $data['e_copy_request_pending'] = $data['e_copy_request_disposed'] = $data['total_request'] = $data['e_copy_verify_pending']= $data['e_copy_verify_disposed']=0;
        $data['total_verify']=$data['e_copy_request_pending_at_copying']=$data['e_copy_request_pending_at_judicial']=0;

        $query = $this->Model_CopyingOrderIssuingApplicationNew;
        $query->select([
            'SUM(CASE WHEN application_status NOT IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_pending',
            'SUM(CASE WHEN application_status IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_disposed',
        ]);
        $query->where('source !=', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result = $query->get();
        // echo $this->db->getLastQuery();

        if ($result->getNumRows() >= 1) {
            $resultSet1 = $result->getResultArray();
            $data['offline_copy_pending'] = $resultSet1[0]['e_copy_pending'];
            $data['offline_copy_disposed'] = $resultSet1[0]['e_copy_disposed'];
            $data['offline_total_filed'] = $data['offline_copy_pending'] + $data['offline_copy_disposed'];
        }


        $query_pending = $this->Model_CopyingOrderIssuingApplicationNew;
        $query_pending->select([
            'SUM(CASE WHEN application_status NOT IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_pending',
            'SUM(CASE WHEN application_status IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_disposed',
        ]);
        $query_pending->where('source', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result_pending = $query_pending->get();

        if ($result_pending->getNumRows() >= 1) {
            $resultSet2 = $result_pending->getResultArray();
            $data['e_copy_pending'] = $resultSet2[0]['e_copy_pending'];
            $data['e_copy_disposed'] = $resultSet2[0]['e_copy_disposed'];
            $data['total_filed'] = $data['e_copy_pending'] + $data['e_copy_disposed'];
        }

        $query3 = $this->Model_CopyingRequestVerify;
        $query3->select([
            "sum(case when allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending",

            "sum(case when send_to_section = 'f' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending_at_copying",
            "sum(case when send_to_section = 't' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending_at_judicial",

            "sum(case when allowed_request = 'request_to_available' and application_status in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_disposed",

            "sum(case when allowed_request != 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_verify_pending",
            "sum(case when allowed_request != 'request_to_available' and application_status in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_verify_disposed"
        ]);
        $query3->where('source', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result3 = $query3->get();
        // echo $this->db->getLastQuery();
        // exit;
        if ($result3->getNumRows() >= 1) {
            $resultSet3 = $result3->getResultArray();
            $data['e_copy_request_pending'] = $resultSet3[0]['e_copy_request_pending'];
            $data['e_copy_request_disposed'] = $resultSet3[0]['e_copy_request_disposed'];
            $data['total_request'] = $data['e_copy_request_pending'] + $data['e_copy_request_disposed'];

            $data['e_copy_verify_pending'] =  $resultSet3[0]['e_copy_verify_pending'];
            $data['e_copy_verify_disposed'] =  $resultSet3[0]['e_copy_verify_disposed'];
            $data['total_verify'] = $data['e_copy_verify_pending'] + $data['e_copy_verify_disposed'];

            $data['e_copy_request_pending_at_copying'] =  $resultSet3[0]['e_copy_request_pending_at_copying'];
            $data['e_copy_request_pending_at_judicial'] =  $resultSet3[0]['e_copy_request_pending_at_judicial'];

        }

        return view('Copying/dashboard_count_view',$data    );
    }

    public function dashboard_details(){
        $from_date = date("Y-m-d", strtotime($this->request->getPost('from_date')));
        $to_date = date("Y-m-d", strtotime($this->request->getPost('to_date')));
        $flag=$this->request->getPost('flag');
        if($flag == 'offline_total_applications' OR $flag == 'offline_pending_applications' OR $flag == 'offline_disposed_applications') {
            $table = $this->Model_CopyingOrderIssuingApplicationNew;

            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_order_issuing_application_new.crn, copying_order_issuing_application_new.filed_by, copying_order_issuing_application_new.name, copying_order_issuing_application_new.application_number_display,
            copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.adm_updated_by, 
            copying_order_issuing_application_new.updated_on, copying_order_issuing_application_new.send_to_section");

            $table->join('main m', 'm.diary_no=copying_order_issuing_application_new.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_order_issuing_application_new.diary', 'left');


            if($flag == 'offline_pending_applications'){
                $table->where('source !=', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Offline Mode and Pending";
            }
            else if($flag == 'offline_disposed_applications'){
                $table->where('source !=', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Offline Mode and Disposed";
            }
            else{
                $table->where('source !=', 6);
                $heading = "Applications Received Through Offline Mode";
            }

        }else if($flag == 'total_applications' OR $flag == 'pending_applications' OR $flag == 'disposed_applications') {
            $table = $this->Model_CopyingOrderIssuingApplicationNew;
            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_order_issuing_application_new.crn, copying_order_issuing_application_new.filed_by, copying_order_issuing_application_new.name, copying_order_issuing_application_new.application_number_display,
            copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.adm_updated_by, 
            copying_order_issuing_application_new.updated_on, copying_order_issuing_application_new.send_to_section");

            $table->join('main m', 'm.diary_no=copying_order_issuing_application_new.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_order_issuing_application_new.diary', 'left');

            if($flag == 'pending_applications'){
                $table->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Online Mode and Pending";
            }
            else if($flag == 'disposed_applications'){
                $table->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Online Mode and Disposed";
            }
            else{
                $table->where('source', 6);
                $heading = "Applications Received Through Online Mode";
            }
        }else if($flag == 'total_request' OR $flag == 'pending_request' OR $flag == 'disposed_request' OR $flag == 'request_pending_copying' OR $flag == 'request_pending_judicial' OR $flag == 'request_pending_record_room') {

            $table = $this->Model_CopyingRequestVerify;

            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_request_verify.crn, copying_request_verify.filed_by, copying_request_verify.name, copying_request_verify.application_number_display,
            copying_request_verify.application_receipt, copying_request_verify.application_status copying_request_verify.send_to_section");

            $table->join('main m', 'm.diary_no=copying_request_verify.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_request_verify.diary', 'left');

            if($flag == 'request_pending_copying'){
                $table->where('send_to_section', 'f')
                    ->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending in Copying Section";
            }
            else if($flag == 'request_pending_judicial'){
                $table->where('send_to_section', 't')
                    ->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending in Judicial Sections";
            }
            /*else if($flag == 'request_pending_record_room'){
                $sub_query = "and send_to_section = 'r' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W')  and a.source = 6";
                $heading = "Document Request Received Through Online Mode And Pending in Record Room";
            }*/
            else if($flag == 'pending_request'){
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending";
            }
            else if($flag == 'disposed_request'){
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Completed";
            }
            else{
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6);

                $heading = "Document Request Received Through Online Mode";
            }
        }else if($flag == 'total_verify' OR $flag == 'pending_verify' OR $flag == 'disposed_verify') {
            $table = $this->Model_CopyingRequestVerify;
            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_request_verify.crn, copying_request_verify.filed_by, copying_request_verify.name, copying_request_verify.application_number_display,
            copying_request_verify.application_receipt, copying_request_verify.application_status, copying_request_verify.adm_updated_by, 
            copying_request_verify.updated_on, copying_request_verify.send_to_section");

            $table->join('main m', 'm.diary_no=copying_request_verify.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_request_verify.diary', 'left');

            if($flag == 'pending_verify'){

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Verification Request Received Through Online Mode And Pending";
            }
            else if($flag == 'disposed_verify'){

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Verification Request Received Through Online Mode And Completed";
            }
            else{

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6);
                $heading = "Verification Request Received Through Online Mode";
            }
        }

        $table->where('is_deleted','0');
        $table->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result = $table->get();
        // echo $this->db->getLastQuery();
        // exit;

        if ($result->getNumRows() >= 1) {
            $data['all_records'] = $result->getResultArray();
        }

        $heading .= " (Date ".date("d-m-Y", strtotime($this->request->getPost('from_date')))." to ".date("d-m-Y", strtotime($this->request->getPost('to_date'))).")";

        $data['heading'] = $heading;
        return view('Copying/dashboard_details_view',$data);
    }

    public function application(){
        $data['app_name'] = 'Add Application';
        $data['copy_category'] = is_data_from_table('master.copy_category', null, '*');
        $data['order_type'] = is_data_from_table('master.ref_order_type', null, '*');

        $query = $this->db->table('master.casetype');
        $query->select('*');
        $query->where('is_deleted', 'f');
        $query->where('casecode != 9999');
        $query->orderBy('casecode');
        $result = $query->get();
        if ($result->getNumRows() >= 1) {
            $data['case_types'] = $result->getResultArray();
        }
       // $data['case_types'] =  is_data_from_table('master.casetype', ['is_deleted'=>'f','casecode!='=>'9999'], '*');
        $data['case_status']= is_data_from_table('master.ref_copying_status', null, '*');
        $data['case_source']= is_data_from_table('master.ref_copying_source', null, '*');
        //var_dump($data['case_source']);
        return view('Copying/add_application_view',$data);
    }

    public function get_diary(){

        $case_type= $this->request->getPost('case_type');
        $case_number= $this->request->getPost('case_number');
        $case_year= $this->request->getPost('case_year');

        $diary_details = get_diary_case_type($case_type,$case_number,$case_year);
        $diary_no = "";
        if(!empty($diary_details)){
            $diary_no = $diary_details;
        }
        echo $diary_no;
    }

    public function previous_applies(){
        $diary_number= $this->request->getPost('diary_number');
        $diary_year= $this->request->getPost('diary_year');

        $d_no = $diary_number.$diary_year;

        $query = $this->db->table('copying_order_issuing_application_new coian');
        $query->select('coian.id, application_number_display, coian.court_fee, CONCAT(coian.name, CASE WHEN filed_by=1 THEN \' (Adv)\' WHEN filed_by=2 THEN \' (Party)\' WHEN filed_by=3 THEN \' (AC)\' WHEN filed_by=4 THEN \' (Other)\' END) AS name, rcs.status_description AS status, application_receipt AS received_on');
        $query->join('master.ref_copying_status rcs', 'coian.application_status = rcs.status_code', 'LEFT OUTER');
        $query->where('diary', $d_no);
        $query->whereNotIn('application_status', ['D', 'C', 'W']);
        $result = $query->get();
        if ($result->getNumRows() >= 1) {
            $previous_applies = $result->getResultArray();
            echo json_encode($previous_applies,JSON_UNESCAPED_SLASHES);
        }else{
            echo "0";
        }

    }

    public function contact_detail(){
        $selected_val= $this->request->getPost('selected_val');
        $applied_by= $this->request->getPost('applied_by');
        $diary_no= $this->request->getPost('diary_no');
        if($applied_by == 2){
            $selected_val = substr($selected_val,0,-3);

            $diary_details = is_data_from_table('main',['diary_no'=>$diary_no],'*','R');
            $table_alias = '';
            if(empty($diary_details)){
                $table_alias = '_a';
            }

            $query = $this->db->table('party'.$table_alias.' party')
                ->select('partyname as name, contact as mobile, email, CONCAT(addr1, \',\', addr2, \', \', TRIM(city.name), \', \', TRIM(state.name), \',\', pin) as caddress')
                ->join('master.state state', '(state.id_no = party.state::bigint)', 'left')
                ->join('master.state city', '(city.id_no = party.city::bigint)', 'left')
                ->where('diary_no', $diary_no)
                ->like('partyname', $selected_val )
                ->get();
            $result = $query->getResultArray();
            return $result[0]['name'].'|'.$result[0]['mobile'].'|'.$result[0]['caddress'];
        }

        if($applied_by == 1){
            $contact_details = is_data_from_table('master.bar',['aor_code'=>$selected_val],'*','R');
            return $contact_details['name'].'|'.$contact_details['mobile'].'|'.$contact_details['caddress'];
        }

    }


    public function advocate_or_party_details(){
        $diary_number= trim($this->request->getPost('diary_number'));
        $filed = trim($this->request->getPost('filed'));
        $diary_year = trim($this->request->getPost('diary_year'));
        $diary_no = trim($diary_number.$diary_year);

        $diary_details = is_data_from_table('main',['diary_no'=>$diary_no],'*','R');
        $table_alias = '';
        if(empty($diary_details)){
            $table_alias = '_a';
        }

        $union = $this->db->table('advocate'.$table_alias.' adv')->select("(bar.aor_code::text) as code, concat(bar.name,'(',adv.pet_res,')') as name ,1 as type")->join('master.bar bar','adv.advocate_id=bar.bar_id')->where('diary_no', $diary_no);
        $builder = $this->db->table('party'.$table_alias.' party')->select("concat(partyname,'(',pet_res,')') as code, concat(partyname,'(',pet_res,')') as name,2 as type")->where('diary_no', $diary_no)->union($union);
        $finalQuery = $this->db->newQuery()->select("*,(select CASE WHEN tentative_section(m.diary_no) IS NULL THEN us.section_name ELSE tentative_section(m.diary_no) END from main".$table_alias." m join master.users u on m.dacode=u.usercode join master.usersection us on us.id=u.section where diary_no=$diary_no) as sec")->fromSubquery($builder, 'a')->where('a.type',$filed)->get();

//        echo $finalQuery->getCompiledSelect();
//            exit;

        //
        $dropdownOptions = $section="";
        if ($finalQuery->getNumRows() >= 1) {
            $app_result = $finalQuery->getResultArray();
            $dropdownOptions = '<option value="">SELECT</option>';
            if(is_array($app_result)){
                foreach ($app_result as $data) {
                    $section = $data['sec'];
                    $dropdownOptions .= '<option value="' . sanitize($data['code']) . '">' . sanitize(strtoupper($data['name'])) . '</option>';
                }

            }

        }
        $dropdownOptions .= '<option value="0">OTHER</option>';
        echo $dropdownOptions.'|'.$section;
    }

    public function add_new_application(){
        $category = $this->request->getPost('category');
        if(!session()->get('login')['usercode']){
            $response = '0';
            echo $response;
            session()->setFlashdata("error_msg", 'Please login');
            $this->response->redirect(site_url('/login'));
        }

        $diary_number = $this->request->getPost('diary_number');
        $diary_year = $this->request->getPost('diary_year');
        $deliver_mode = $this->request->getPost('deliver_mode');
        $advocate_or_party = $this->request->getPost('advocate_or_party');
        $court_fee = $this->request->getPost('court_fee');
        $order_type = $this->request->getPost('order_type');
        $mobile =$this->request->getPost('mobile');
        if(isset($diary_number, $diary_year, $category, $deliver_mode, $advocate_or_party, $court_fee, $order_type)){



            $builder = $this->db->table('copying_order_issuing_application_new')->select("max(application_reg_number )+1 as app_no,(select code from master.copy_category where id='$category') as code")
                ->where('copy_category', $category)
                ->where('application_reg_year', 'EXTRACT(YEAR FROM CURRENT_DATE)', false);

            $finalQuery = $this->db->newQuery()->select("concat(code,'-',(case when app_no is null then 1 else app_no end),'/',EXTRACT(YEAR FROM CURRENT_DATE)) as application_no_display, (case when app_no is null then 1 else app_no end) as app_no")->fromSubquery($builder, 'a')->get();

            if ($finalQuery->getNumRows() >= 1) {
                $app_result = $finalQuery->getResultArray();
            }

            $application_reg_number = $app_result[0]['app_no'];
            $application_number_display = $app_result[0]['application_no_display'];
            $application_reg_year = date('Y');

            $dataArray = array(
                'diary' => $this->request->getPost('diary_number').''.$this->request->getPost('diary_year'),
                'copy_category' =>$this->request->getPost('category'),
                'application_receipt' => date('Y-m-d H:i:s'),
                'advocate_or_party' => $this->request->getPost('advocate_or_party'),
                'court_fee' => $this->request->getPost('court_fee'),
                'delivery_mode' => $this->request->getPost('deliver_mode'),
                'adm_updated_by' => session()->get('login')['usercode'],
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
                'updated_on' => date('Y-m-d H:i:s'),
                'application_reg_number' => $app_result[0]['app_no']==NULL? '1': $app_result[0]['app_no'],
                // 'application_reg_number' => $app_result[0]['app_no'],
                'application_reg_year'=>date('Y'),
                'filed_by'  =>$this->request->getPost('filed'),
                'name' => !empty($this->request->getPost('name'))?$this->request->getPost('name'):'',
                'mobile'=> !empty($this->request->getPost('mobile'))?$this->request->getPost('mobile'):'',
                'address'=> !empty($this->request->getPost('address'))?$this->request->getPost('address'):'',
                'remarks'=> !empty($this->request->getPost('remarks'))?$this->request->getPost('remarks'):'',
                'source'  => $this->request->getPost('case_source'),
                'send_to_section'=> !empty($this->request->getPost('send_section'))?$this->request->getPost('send_section'):'f',
                'application_status'=>$this->request->getPost('send_section')=='t'?'A':'P',
                'application_number_display'=>$app_result[0]['application_no_display']
            );

            $this->db = \Config\Database::connect();
            $this->db->transStart();

            $isInserted = insert('copying_order_issuing_application_new',$dataArray);
            if($isInserted){

                $query = $this->db->table('copying_order_issuing_application_new');
                $query->select('id');
                $query->where('copy_category', $category);
                $query->where('application_reg_number', $application_reg_number);
                $query->where('application_reg_year', $application_reg_year);
                $query->orderBy('id DESC');
                $query->limit('1');

                $result = $query->get();
                if ($result->getNumRows() >= 1) {
                    $dataId = $result->getResultArray();
                    $inserted_application_id = $dataId[0]['id'];
                }


                $orderDate = $this->request->getPost('orderDate');
                $copies = $this->request->getPost('copies');

                for($i=0; $i < sizeof($order_type); $i++){
                    $tempArray = array(
                        'order_type'    =>  $order_type[$i],
                        'order_date'     => !empty($orderDate[$i])? date('Y-m-d', strtotime($orderDate[$i])) : NULL,
                        'number_of_copies'=> $copies[$i],
                        'copying_order_issuing_application_id'=>$inserted_application_id,
                        'number_of_pages_in_pdf' => 0,
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                        'updated_on' => date('Y-m-d H:i:s'),
                    );
                    $this->Model_CopyingApplicationDocuments->insert($tempArray);
                }

            }

            $this->db->transComplete();
            sendSMS($mobile, "Your application received in Copying Branch has been registered with application number ".$application_number_display.". - Supreme Court of India", '1107161243437551558');

            $response = '1';
            echo $response;
            session()->setFlashdata("success_msg", 'Application Inserted Sucessfully with application number - '.$application_number_display);

            return redirect()->to('/Copying/Copying/application');

            // $this->response->redirect(site_url('/Copying/Copying/application'));

        }else{
            $response = '0';
            echo $response;
            session()->setFlashdata("error_msg", 'Please fill all the fields ');
            return $this->response->redirect(site_url('/Copying/Copying/application'));
        }

    }

    public function request_search()
    {

        //$data['dcmis_multi_section_id'] = session()->get('login')['section'];
        $data['dcmis_user_idd'] = session()->get('login')['usercode'];
        $data['icmic_empid'] = session()->get('login')['empid'];
        $data['dcmis_usertype'] = session()->get('login')['usertype'];
        $data['dcmis_section'] = session()->get('login')['section'];
       $lognDetail = $this->LoginModel->get_multi_section( $data['icmic_empid']);
       if (!session()->has('dcmis_multi_section_id')) {
        session()->set('dcmis_multi_section_id', []);
    }
    
    if (!session()->has('dcmis_multi_section_name')) {
        session()->set('dcmis_multi_section_name', []);
    }
    foreach ($lognDetail as $row_multi_sec) {
        // Get the session arrays (which are already initialized)
        $section_ids = session()->get('dcmis_multi_section_id');
        $section_names = session()->get('dcmis_multi_section_name');
    
        // Add new items to the session arrays
        $section_ids[] = $row_multi_sec['id'];
        $section_names[] = $row_multi_sec['section_name'];
    
        // Save the updated arrays back to the session
        session()->set('dcmis_multi_section_id', $section_ids);
        session()->set('dcmis_multi_section_name', $section_names);
    }
    $data['dcmis_multi_section_id'] = session()->get('login')['section'];


        $sql = "SELECT us.section_name, us.isda
        FROM master.user_sec_map usm
        INNER JOIN master.usersection us ON us.id = usm.usec
        WHERE usm.empid = ? 
        AND usm.display = 'Y'
        AND us.isda = 'Y'";

        // Execute the query with the prepared statement
        $query = $this->db->query($sql, [$data['icmic_empid']]);

        // Check if rows exist
        if ($query->getNumRows() > 0) {
            // Fetch the row as an associative array
            $row_sn = $query->getRowArray();
            $data['isda'] = $row_sn['isda'];
        } else {
            $data['isda'] = 'N';
        }

        //if(in_array_any( [61], $_SESSION['dcmis_multi_section_id']) OR $isda == 'Y'){
            if(!in_array_any([10], $data['dcmis_multi_section_id']) && $data['dcmis_user_idd'] != 1){
                $data['ignore_applicant_type'] = 'Y';
            }
            else{
                $data['ignore_applicant_type'] = 'N';
            }


        if(in_array_any( [10], $data['dcmis_multi_section_id']) || $data['dcmis_user_idd'] == 1) {
            if($data['dcmis_usertype'] == 50 OR $data['dcmis_usertype'] == 51 OR $data['dcmis_usertype'] == 17){
               // Prepare the raw SQL query using a parameter for binding
                $sql = "SELECT applicant_type_id, application_type_id 
                FROM copying_role 
                WHERE status = 'T' 
                AND role_assign_to = ? 
                AND to_date = '0000-00-00 00:00:00'";

                // Execute the query with binding the user ID
                $query = $this->db->query($sql, [$data['dcmis_user_idd']]);

                // Check if there are any results
                if ($query->getNumRows() > 0) {
                // Fetch the result as an associative array
                $data_role = $query->getRowArray();

                // Split the result fields into arrays
                $applicant_type_array = explode(",", $data_role['applicant_type_id']);
                $application_type_array = explode(",", $data_role['application_type_id']);
                } else {
                // If no role found, output a message and stop execution
                echo "You don't have permission";
                exit();
                }
            }
        }



        if (!empty( $data['dcmis_section']) && ( $data['dcmis_section'] == 10 ||  $data['dcmis_section'] == 1)) {
            // First query if section is 10 or 1
            $sql_section = "SELECT id, section_name, display, isda 
                            FROM master.usersection 
                            WHERE display = 'Y' 
                            ORDER BY CASE WHEN id IN (10, 61) THEN 1 ELSE 999 END ASC, 
                                     CASE WHEN isda = 'Y' THEN 2 ELSE 999 END ASC, 
                                     section_name ASC";
        } else {
            // Otherwise, restrict by the specific section id
            $sql_section = "SELECT id, section_name, display, isda 
                            FROM master.usersection 
                            WHERE display = 'Y' 
                            AND id = ?";
        }
        
        // Execute the query, binding $dcmis_section only if needed
        $query = ( $data['dcmis_section'] == 10 ||  $data['dcmis_section']== 1) 
                 ? $this->db->query($sql_section) 
                 : $this->db->query($sql_section, [ $data['dcmis_section']]);
        
        // Get the number of results
        $section_count = $query->getNumRows();
        
        if ($section_count > 0) {
            // Fetch results as an array of objects
            $section_list = $query->getResultObject();
        } else {
            $section_list = []; // No results found
        }
        
        // If necessary, handle the case where no sections are returned
        $d_none_class = empty($section_list) ? "d-none" : "";

        //var_dump($_SESSION);
        return view('Copying/request/request_search',$data);
    }

}