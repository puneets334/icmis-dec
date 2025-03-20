<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;

class Requisition extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = session();
    }

    public function requisition_view()
    {
        $sessionData = $this->session->get();
        if (!isset($sessionData['login']['usercode'])) {
            return redirect()->to('/login');
        }
        if (!isset($sessionData['token'])) {
            $this->session->set('token', bin2hex(random_bytes(32)));
        }
        $usercode = $sessionData['login']['usercode'];
        $requisitions = $this->view_today_RequisitionData($usercode);
        $listRole = $this->db->query("select * from admin_user_roles  where role_id IN('4','5','6','1')")->getResultArray();
        return view('Library/requisition', [
            'requisitions' => $requisitions,
            'listRole' => $listRole
        ]);
    }

    private function view_today_RequisitionData($usercode)
    {
        $todayDate = date("Y-m-d");

        $query = "SELECT * FROM tbl_court_requisition 
                  WHERE itemDate >= ? 
                  AND current_status IN ('pending') 
                  AND court_number = ? 
                  ORDER BY id DESC";

        return $this->db->query($query, [$todayDate, $usercode])->getResultArray();
    }

    public function create_requisition()
    {
        $data = $this->request->getPost();

        // Sanitize inputs
        $courtNumber = htmlspecialchars($data['court_number']);
        // ... sanitize other inputs as needed

        $query = "INSERT INTO tbl_court_requisition (court_number, court_userName, remark1, court_bench, urgent, section, request_file, itemNo, itemDate, alternate_number, user_type, created_by, user_ip, diary_no, advocate_name, appearing_for, party_serial_no) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($query, [
            $courtNumber,
            htmlspecialchars($data['court_userName']),
            htmlspecialchars($data['remark1']),
            htmlspecialchars($data['court_bench']),
            htmlspecialchars($data['urgent']),
            htmlspecialchars($data['section']),
            htmlspecialchars($data['file']),
            htmlspecialchars($data['itemNo']),
            htmlspecialchars($data['itemDate']),
            htmlspecialchars($data['phoneNo']),
            $data['user_type'] ?? 1,
            htmlspecialchars($data['created_by']),
            $_SERVER['REMOTE_ADDR'],
            htmlspecialchars($data['diary_no']),
            htmlspecialchars($data['advocate_name']),
            htmlspecialchars($data['appearing_for']),
            htmlspecialchars($data['party_serial_no']),
        ]);

        return redirect()->to('/success');
    }

    public function frmusrLogin()
    {
        // Check CSRF token
        // if ($this->request->getPost('CSRF_TOKEN') !== $this->session->get('token')) {
        //     return $this->response->setJSON(['status' => 'Error', 'msg' => 'Invalid CSRF token dsafsdf']);
        // }.
        // pr(session()->get('login'));
        $usercode = session()->get('login')['usercode'];
        $sql = "SELECT * FROM admin where icmis_user_id= '" . $usercode . "'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        $data['role_id'] = $result[0]['role_id'];
        $data['court_number'] = $result[0]['court_no'];
        $role_id = $data['role_id'];
        $court_number = $data['court_number'];
        $data = $this->request->getPost();
        session()->set('role_id', $role_id);
        session()->set('court_number', $court_number);
        // Validate role
        if (empty($role_id)) {
            return $this->response->setJSON(['status' => 'Error', 'msg' => 'Please select a role']);
        }
        // Process according to the role
        switch ($role_id) {
            case 4:
                // Validate court number and user name
                // pr($court_number);
                if (empty($court_number)) {
                    return $this->response->setJSON(['status' => 'Error', 'msg' => 'Please enter court number']);
                }
                break;

            case 5:
            case 6:
            case 7:
                // Validate username and password for these roles
                if (empty($data['user_name']) || empty($data['user_password'])) {
                    return $this->response->setJSON(['status' => 'Error', 'msg' => 'Please enter username and password']);
                }
                // Authentication logic should go here
                break;

            default:
                return $this->response->setJSON(['status' => 'Error', 'msg' => 'Invalid role']);
        }

        // If all validations pass
        return $this->response->setJSON(['status' => 'Success', 'msg' => 'Login successful']);
    }
    public function court_dashboard()
    {
        $todayDate = date('Y-m-d');
        $court_userName = session()->get('login')['name'];
        $sql = "SELECT *  FROM  tbl_court_requisition  where DATE(created_on)='$todayDate' AND court_userName='$court_userName' ORDER BY id DESC";
        $query = $this->db->query($sql);
        $data['result'] = $query->getResultArray();
        return view('Library/court_dashboard',$data);
    }
    // public function ajax(){
    //     pr($this->request->getPost());

    // }
}
