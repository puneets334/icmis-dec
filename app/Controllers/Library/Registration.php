<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;

class Registration extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function registration_view()
    {
        $sessionData = $this->session->get();
        $usercode = $sessionData['login']['usercode'];

        $employees = $this->db->table('master.users')
            ->select('empid, usercode, name')
            ->where('display', 'Y')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $courtArr = $this->db->table('tbl_requisition_department')
            ->select('*')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        return view('Library/registration', [
            'employees' => $employees,
            'courtArr' => $courtArr
        ]);
    }

    public function getEmployees_()
    {
        $search = $this->request->getVar('val');
        $builder = $this->db->table('master.users');
        $builder->select('empid, usercode, name');

        // Group display + search conditions
        $builder->groupStart()
            ->where('display', 'Y')
            ->groupStart()
                ->orLike('name', $search)
                ->orLike('empid', $search)
            ->groupEnd()
        ->groupEnd();
        if (is_numeric($search)) {
            $builder->Like('empid', $search);
        }else{
            $builder->Like('empid', $search);
        }


        $builder->orderBy('name', 'ASC');
        // echo $builder->getCompiledSelect();
        $query = $builder->get();

        return $this->response->setJSON($query->getResultArray());
    }

    public function getEmployees()
    {
        $search = $this->request->getVar('val');
        $builder = $this->db->table('master.users');
        $builder->select('empid, usercode, name')->where('display', 'Y');        
        if (is_numeric($search)) {
            $builder->like('CAST(empid AS text)', $search . '%', 'after');
        }else{
            $builder->Like('lower(name)', $search);
        }
        $builder->orderBy('name', 'ASC');         
        $query = $builder->get();
        return $this->response->setJSON($query->getResultArray());
    }



    public function searchData()
    {
        $searchId = $this->request->getVar('selectedValue');
        $query = $this->db->table('admin')
            ->select('fullname, adminemail, username, user_type, phone_number, alternative_phone_no, status, court_no')
            ->where('icmis_user_id', $searchId)
            // $sql = $query->getCompiledSelect();
            // pr($sql);
            ->get();
    
            // dd($query->getNumRows());
        if ($query->getNumRows() > 0) {
            return $this->response->setJSON($query->getRowArray());
        } else {
            $query2 = $this->db->table('"master.users"')
                ->select('name as fullname, mobile_no as phone_number, email_id as adminemail')
                ->where(['display' => 'Y', 'usercode' => $searchId])
                ->get();
           // pr($query2->getRowArray());
            if ($query2->getNumRows() > 0) {
                return $this->response->setJSON($query2->getRowArray());
            }else{
                return $this->response->setJSON([]);
            }
        }
    
        return $this->response->setJSON([]);
    }

    public function insertUpdate()
    {
        if (!$this->request->getMethod() === 'post' || !$this->validate(['CSRF_TOKEN' => 'required'])) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $data = $this->request->getPost(); 
        $searchId = $data['empid'];
        $query = $this->db->table('admin')
            ->where('icmis_user_id', $searchId)
            ->get();

            // $sql = $query->getCompiledSelect();
            // pr( $sql);

        if ($query->getNumRows() > 0) {
            
            $dataResult = $query->getRowArray();
            $userRoleID = $this->getUserRoleID($data['usertype']);

            $this->db->table('public.admin')->update([
                'fullname' => $data['fullname'] ?: $dataResult['fullname'],
                'adminemail' => $data['email'] ?: $dataResult['adminemail'],
                'username' => $data['username'] ?: $dataResult['username'],
                'updationdate' => date('Y-m-d H:i:s'),
                'user_type' => $data['usertype'] ?: $dataResult['user_type'],
                'role_id' => $userRoleID ?: $dataResult['role_id'],
                'phone_number' => $data['phone1'] ?: $dataResult['phone_number'],
                'alternative_phone_no' => $data['alterphone'] ?: $dataResult['alternative_phone_no'],
                'court_no' => $data['court_no'],
                
            ],
            ['icmis_user_id' => $searchId]);

            return $this->response->setJSON('2');  // Update success
        } else {
            // Insert new record
            $userRoleID = $this->getUserRoleID($data['usertype']);

            $this->db->table('admin')->insert([
                'fullname' => $data['fullname'],
                'adminemail' => $data['email'],
                'username' => $data['username'],
                'password' => '',  
                'updationdate' => date('Y-m-d H:i:s'),
                'user_type' => $data['usertype'],
                'role_id' => $userRoleID,
                'phone_number' => $data['phone1'],
                'alternative_phone_no' => $data['alterphone'],
                'created_on' => date('Y-m-d H:i:s'),
                'status' => '1',
                'icmis_user_id' => $data['empid'],
                'court_no' => $data['court_no']
            ]);

            return $this->response->setJSON('1'); // Insert success
        }
    }


    public function changeStatus()
    {
        $status = $this->request->getVar('status');
        $empid = $this->request->getVar('empid');
        
        $this->db->table('admin')->update([
            'status' => $status,
            'updationdate' => date('Y-m-d H:i:s')
        ], ['icmis_user_id' => $empid]);
        return $this->response->setJSON($status === '1' ? '1' : '2');
    }

    protected function getUserRoleID($roleName)
    {
        $query = $this->db->table('admin_user_roles')
            ->select('role_id')
            ->where('role_name', $roleName)
            ->get();

        return $query->getNumRows() > 0 ? $query->getRow()->role_id : null;
    }
}