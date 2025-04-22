<?php

namespace App\Models\MasterManagement;

use CodeIgniter\Model;

class UserManagementModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function getUserByCode($usercode)
    {
        $builder = $this->db->table('master.users');
        $builder->select('usertype, section, udept, usercode');
        $builder->where('usercode', $usercode);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getDepartments($usertype, $usercode, $hd_ud)
    {

        if (in_array($usertype, [1, 57, 4, 6, 14])) {
            if (in_array($usertype, [1, 57, 6])) {
                return $this->db->table('master.users a')
                    ->DISTINCT()
                    ->select('udept, dept_name')
                    ->join('master.userdept b', 'udept = b.id', 'left')
                    ->orderBy('udept')
                    ->get()
                    ->getResultArray();
            } elseif (in_array($usertype, [4, 14])) {
                return $this->db->table('master.users a')
                    ->select('dept_name, udept')
                    ->join('master.userdept b', 'a.udept = b.id', 'left')
                    ->where('usercode', $usercode)
                    ->get()
                    ->getResultArray();
            } elseif ($usertype == 20) {
                return $this->db->table('master.users')
                    ->select('deo AS udept')
                    ->where('master.usertype', '20')
                    ->where('usercode', $hd_ud)
                    ->get()
                    ->getResultArray();
            }
        }

        return [];
    }

    public function getRetiredJudges()
    {
        return $this->db->table('master.judge')
            ->select('jcode, jname')
            ->where('is_retired', 'Y')
            ->orderBy('jcode')
            ->get()
            ->getResultArray();
    }

    public function getUserData_obj($id)
    {
        $builder = $this->db->table('master.users a');
        $builder->select('a.usercode, a.name, a.userpass, b.dept_name, d.type_name, c.section_name, a.empid');
        $builder->join('master.userdept b', 'a.udept = b.id', 'left');
        $builder->join('master.usersection c', 'a.section = c.id', 'left');
        $builder->join('master.usertype d', 'a.usertype = d.id', 'left');
        $builder->where('a.empid', $id);        
        $query = $builder->get();
        $user = $query->getRowArray();
        // pr($user);
        if ($user) {
            return $user['empid'] . '~' . $user['name'] . '~' . $user['userpass'] . '~' . $user['dept_name']. '~' . $user['type_name']. '~' . $user['section_name'];
        }

        return null;
    }

    public function getUserDataDept_obj($id)
    {
        $builder = $this->db->table('master.userdept a');
        $builder->select('a.*');        
        $builder->where('a.id', $id);        
        $query = $builder->get();
        $user = $query->getRowArray();
        // pr($user);
        if ($user) {
            return $user['id'] . '~' . $user['dept_name'] . '~' . $user['uside_flag'];
        }

        return null;
    }

    public function getUserData_obj_edit($id){
        //$get_q = "SELECT usercode,name,usertype,udept,section,empid,service FROM users WHERE usercode = $id";
        //$get_rs = mysql_query($get_q) or die(__CLASS__.'->'.__LINE__.'->'.mysql_error());
        //$get_row = mysql_fetch_array($get_rs);

        $builder = $this->db->table('master.users a');
        $builder->select('usercode,name,usertype,udept,section,empid,service,userpass');
        $builder->where('a.usercode', $id);
        $query = $builder->get();
        $get_row = $query->getRowArray();

        return $get_row['usercode'].'@'.$get_row['name'].'@'.$get_row['userpass'].'@'.$get_row['usertype'].'@'.$get_row['udept'].'@'.$get_row['section']
                .'@'.$get_row['empid'].'@'.$get_row['service'];
    }

    public function resetPasswordUser_UM($id, $user)
    {
        $pass =  hash('sha256', $id);
        $data = [
            'userpass' => $pass, 
            'upuser' => $user,   
            'updt' => date('Y-m-d H:i:s') 
        ];
        
        $builder = $this->db->table('master.users');
        $builder->set($data)
                ->where('empid', $id)
                ->update();
        return '1';
    }


    public function getPeonNdriver()
    {
        $builder = $this->db->table('master.user_l_map a');

        $builder->select('a.id, a.ucode, a.utype, c.type_name AS utype, d.name AS perticular_user, 
                      uh.name AS part_user_name, uh.emp_id AS part_user_empid, f.name AS faname');
        $builder->join('master.usertype c', 'a.utype = c.id', 'left');
        $builder->join('master.users d', 'a.ucode = d.usercode', 'left');
        $builder->join('users_history uh', 'a.ucode = uh.userid AND uh.display = \'Y\' AND uh.to_date IS NULL', 'left');
        $builder->join('master.users f', 'a.f_auth = f.usercode', 'left');

        $builder->where('a.display', 'Y');
        // $builder->where('d.udept', 'PROTOCOL');
        $builder->whereIn('d.usertype', [55, 56]);

        $builder->groupBy('a.id, a.ucode, a.utype, c.type_name, d.name, d.udept, uh.name, uh.emp_id, f.name, a.f_auth');
        $builder->orderBy('d.udept');
        $builder->orderBy('a.utype');
        $builder->orderBy('a.ucode');
        $builder->orderBy('a.f_auth');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getAllUserSection_full()
    {
        $query = $this->db->query("SELECT id, section_name, description, isda 
                                     FROM master.usersection 
                                     WHERE display = 'Y' 
                                     ORDER BY id");
        return $query->getResultArray();
    }

    public function get_Open_id()
    {
        // Define the SQL query using a subquery
        $last_id_q = "
            SELECT usercode,name as username, 
                   ROW_NUMBER() OVER (ORDER BY usercode) AS numb
            FROM master.users
            WHERE usercode > 10
        ";

        // Execute the query
        $subquery = $this->db->query($last_id_q);
        $results = $subquery->getResultArray();

        // Calculate the new user code based on the results
        $newUserCode = null;
        $previousUserCode = null;

        foreach ($results as $row) {
            if ($previousUserCode !== null) {
                $diff = $row['usercode'] - $previousUserCode - 1;
                if ($diff > 0) {
                    $newUserCode = $row['usercode'] - $diff;
                    break;
                }
            }
            $previousUserCode = $row['usercode'];
        }

        return $newUserCode;
    }

    public function getAllUserDept_full()
    {
        $select_type_q = "
        SELECT z.id, z.dept_name, z.uside_flag, 
               STRING_AGG(COALESCE(y.section_name, ''), ',' ORDER BY y.id) AS type_name, 
               STRING_AGG(COALESCE(z.utype::text, ''), ',') AS utype_top  
        FROM
        ( 
            SELECT a.id, a.dept_name, a.uside_flag, b.utype 
            FROM master.userdept a 
            LEFT JOIN master.user_d_t_map b ON a.id = b.udept AND a.display = 'Y' AND b.display = 'Y' 
            WHERE a.display = 'Y' 
            ORDER BY a.id, b.utype 
        ) z 
        LEFT JOIN master.usersection y ON z.utype = y.id 
        GROUP BY z.id, z.dept_name, z.uside_flag";

        $query = $this->db->query($select_type_q);
        return $query->getResultArray();
    }

    public function add_userdept($name, $flag, $butype, $user)
    {
        if ($this->c_if_already_dept($name) == 0) {
            $data = [
                'dept_name' => strtoupper($name),
                'uside_flag' => strtoupper($flag),
                'entuser' => $user,
                'updt' => date('Y-m-d H:i:s'),
                'entdt' => date('Y-m-d H:i:s'),
                'upuser' => 0
            ];                        
            $this->db->table('master.userdept')->insert($data);
            $sel_id = $this->db->insertID();

            $t_butype = explode(',', ltrim($butype, ','));
            foreach ($t_butype as $value) {
                $this->db->table('master.user_d_t_map')->insert([
                    'udept' => $sel_id,
                    'utype' => $value,
                    'entuser' => $user,
                    'updt' => date('Y-m-d H:i:s'),
                    'entdt' => date('Y-m-d H:i:s'),
                    'upuser' => 0
                ]);
            }
            return "1";
        } else {
            return "ERROR, USER DEPARTMENT ALREADY EXISTS";
        }
    }

    private function c_if_already_dept($name, $id = null)
    {
        $builder = $this->db->table('master.userdept');
        $builder->where('dept_name', $name);
        $builder->where('display', 'Y');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $check_row = $query->getRowArray();
            if ($id === null) {
                return 1;
            } else {
                return $check_row['id'] == $id ? 0 : 1;
            }
        } else {
            return 0;
        }
    }

    public function remove_userdept($id, $user)
    {
        $this->db->transStart();

        $this->db->table('master.userdept')
            ->set(['display' => 'N', 'updt' => date('Y-m-d H:i:s'), 'upuser' => $user])
            ->where('id', $id)
            ->where('display', 'Y')
            ->update();

        $this->db->table('master.user_d_t_map')
            ->set(['display' => 'N', 'updt' => date('Y-m-d H:i:s'), 'upuser' => $user])
            ->where('udept', $id)
            ->where('display', 'Y')
            ->update();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return "0";
        }

        return "1";
    }

    public function edit_userdept($name, $flag, $id, $butype, $user)
    {
        $checker = $this->c_if_already_dept($name, $id);

        if ($checker == 0) {
            $this->db->transStart();

            $data = [
                'dept_name' => strtoupper($name),
                'uside_flag' => strtoupper($flag),
                'upuser' => $user,
                'updt' => date('Y-m-d H:i:s')
            ];
            $this->db->table('master.userdept')->update($data, ['id' => $id]);

            $this->db->table('master.user_d_t_map')->update(
                ['display' => 'N', 'upuser' => $user, 'updt' => date('Y-m-d H:i:s')],
                ['udept' => $id]
            );

            $t_butype = explode(',', ltrim($butype, ','));

            foreach ($t_butype as $value) {
                $exists = $this->db->table('master.user_d_t_map')
                    ->where(['udept' => $id, 'utype' => $value, 'display' => 'N'])
                    ->countAllResults();

                if ($exists > 0) {
                    $this->db->table('master.user_d_t_map')->update(
                        ['display' => 'Y', 'upuser' => $user, 'updt' => date('Y-m-d H:i:s')],
                        ['udept' => $id, 'utype' => $value]
                    );
                } else {
                    $insertData = [
                        'udept' => $id,
                        'utype' => $value,
                        'entuser' => $user,
                        'entdt' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('master.user_d_t_map')->insert($insertData);
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return "0";
            }

            return "1";
        } else {
            return "ERROR, USER DEPARTMENT IS ALREADY EXISTS";
        }
    }
    public function select_type_row()
    {
        $sql = "SELECT id,type_name,disp_flag,mgmt_flag FROM master.usertype WHERE display = 'Y' ORDER BY id";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getAllUserRange_full()
    {
        $sql = "SELECT a.id,utype,low,up,type_name FROM master.user_range a LEFT JOIN master.usertype b ON utype = b.id AND b.display = 'Y' WHERE a.display = 'Y' ORDER BY low";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function add_range($utype, $low, $up, $usercode)
    {
        $builder = $this->db->table('master.user_range');
        $data = [
            'utype'   => $utype,
            'low'     => $low,
            'up'      => $up,
            'entuser' => $usercode,
            'entdt'   => date('Y-m-d H:i:s'),
            'upuser'  => 0,
            'updt'    => date('Y-m-d H:i:s'),
        ];
        $builder->insert($data);
        return true;
    }
    public function getUser_range_Data($id)
    {
        $builder = $this->db->table('master.user_range');
        $query = $builder->getWhere(['id' => $id]);
        $user = $query->getRowArray();
        if ($user) {
            return $user['id'] . '~' . $user['utype'] . '~' . $user['low'] . '~' . $user['up'];
        }
        return true;
    }
    public function edit_userrange($utype, $low, $up, $id, $usercode)
    {
        $builder = $this->db->table('master.user_range');
        $data = [
            'utype'   => $utype,
            'low'     => $low,
            'up'      => $up,
            'entuser' => $usercode,
            'entdt'   => date('Y-m-d H:i:s'),
            'upuser'  => 0,
            'updt'    => date('Y-m-d H:i:s'),
        ];
        $builder->where('id', $id);
        $builder->update($data);
        return true;
    }
    public function remove_userrange($id, $usercode)
    {
        $builder = $this->db->table('master.user_range');
        $data = [
            'display' => 'N',
            'upuser'  => $usercode,
            'updt'    => date('Y-m-d H:i:s'),
        ];
        $builder->where('id', $id);
        $builder->update($data);

        return true;
    }

    //User Section
    public function user_section_Open_id($from = NULL)
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('MAX(id) as id');
        $builder->where('id !=', 9999);

        $query = $builder->get();
        $result = $query->getRow();
        $last_id = ($result->id ?? 0) + 1;
        return $last_id;
    }


    public function getUserSectionById($id)
    {
        $result = $this->db->table('master.usersection')
            ->where('id', $id)
            ->get()
            ->getRowArray();
        if ($result) {
            return $result['id'] . '~' . $result['section_name'] . '~' . $result['description'] . '~' . $result['isda'];
        }

        return null;
    }

    public function add_usersection($name, $des, $user, $isda)
    {
        $checker = $this->c_if_already_sec(trim($name));
        $lastid = $this->user_section_Open_id(1);

        if ($checker == 0) {
            $data = [
                'id' => $lastid,
                'section_name' => ucwords(trim($name)),
                'description' => strtoupper($des),
                'entuser' => $user,
                'entdt' => date('Y-m-d H:i:s'),
                'updt' => date('Y-m-d H:i:s'),
                'isda' => $isda,
                'old_id' => 0,
                'upuser' => 0
            ];

            $this->db->table('master.usersection')->insert($data);
            return "1"; // Return success
        } else {
            return "2~ USER SECTION IS ALREADY EXISTS"; // Return error
        }
    }

    private function c_if_already_sec($name, $id = NULL)
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('*');
        $builder->like('section_name', $name);
        $builder->where('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            if ($id === null) {
                return 1;
            } else {
                $check_row = $query->getRow();
                if ($check_row->id == $id) {
                    return 0;
                } else {
                    return 1;
                }
            }
        } else {
            return 0; // No section exists
        }
    }

    public function remove_usersection($id, $user)
    {
        $data = [
            'display' => 'N',
            'upuser' => $user,
            'updt' => date('Y-m-d H:i:s')
        ];
        $this->db->table('master.usersection')->where('id', $id)->update($data);
        return "1";
    }

    public function edit_usersection($name, $des, $id, $user, $isda)
    {
        $checker = $this->c_if_already_sec($name, $id);
        if ($checker == 0) {
            $data = [
                'section_name' => ucwords(trim($name)),
                'description' => strtoupper($des),
                'isda' => $isda,
                'upuser' => $user,
                'updt' => date('Y-m-d H:i:s')
            ];

            // Update the user section
            $this->db->table('master.usersection')->where('id', $id)->update($data);

            return "1";
        } else {
            return "ERROR, USER SECTION IS ALREADY EXISTS";
        }
    }

    //User Type
    public function getAllUserType_full()
    {
        $return = [];
        $builder = $this->db->table('master.usertype');
        $builder->select('id, type_name, disp_flag, mgmt_flag');
        $builder->where('display', 'Y');
        $builder->orderBy('id');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $return = $query->getResultArray();
        }
        return $return;
    }

    public function user_type_Open_id()
    {
        $query = $this->db->query("SELECT last_value FROM master.usertype_id_seq");
        $result = $query->getRow();

        if ($result) {
            return $result->last_value + 1;
        } else {
            return null;
        }
    }

    public function getUserTypeById($id)
    {
        $builder = $this->db->table('master.usertype');
        $query = $builder->where('id', $id)->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->id . '~' . $row->type_name . '~' . $row->disp_flag . '~' . $row->mgmt_flag;
        }

        return null;
    }

    public function add_usertype($name, $flag, $mflag, $user)
    {
        $checker = $this->c_if_already_flag($flag);
        $checker2 = $this->c_if_already_mflag($mflag);
        if ($checker == 0 && $checker2 == 0) {
            $data = [
                'type_name' => ucwords(trim($name)),
                'disp_flag' => strtoupper($flag),
                'mgmt_flag' => strtoupper($mflag),
                'entuser' => $user,
                'updt' => date('Y-m-d H:i:s'),
                'upuser' => 0,
                'entdt' => date('Y-m-d H:i:s'),
            ];

            $builder = $this->db->table('master.usertype');
            $builder->insert($data);

            return "1";
        } else {
            if ($checker != 0) {
                return "2~ THE DISPATCH FLAG IS ALREADY USED";
            } else if ($checker2 != 0) {
                return "2~ THE MANAGEMENT FLAG IS ALREADY USED";
            } else {
                return "2~ ANY FLAG IS ALREADY USED";
            }
        }
    }

    private function c_if_already_flag($flag, $id = NULL)
    {
        $builder = $this->db->table('master.usertype');
        $builder->like('disp_flag', $flag);
        $builder->where('display', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            if ($id === null) {
                return 1;
            } else {
                $check_row = $query->getRowArray();
                if ($check_row['id'] == $id) {
                    return 0;
                } else {
                    return 1;
                }
            }
        } else {
            return 0;
        }
    }

    private function c_if_already_mflag($mflag, $id = NULL)
    {
        $builder = $this->db->table('master.usertype');
        $builder->like('mgmt_flag', $mflag);
        $builder->where('display', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            if ($id === null) {
                return 1;
            } else {
                $check_row = $query->getRowArray();
                if ($check_row['id'] == $id) {
                    return 0;
                } else {
                    return 1;
                }
            }
        } else {
            return 0;
        }
    }

    public function remove_usertype($id, $user)
    {
        $data = [
            'display' => 'N',
            'upuser' => $user,
            'updt' => date('Y-m-d H:i:s')
        ];

        $builder = $this->db->table('master.usertype');
        $builder->where('id', $id);
        $builder->update($data);

        if ($this->db->affectedRows() > 0) {
            return "1"; // Update successful
        }
    }

    public function edit_usertype($name, $flag, $mflag, $id, $user)
    {
        $checker = $this->c_if_already_flag($flag, $id);
        $checker2 = $this->c_if_already_mflag($mflag, $id);

        if ($checker == 0 && $checker2 == 0) {
            $data = [
                'type_name' => ucwords(trim($name)),
                'disp_flag' => strtoupper($flag),
                'mgmt_flag' => strtoupper($mflag),
                'upuser' => $user,
                'updt' => date('Y-m-d H:i:s')
            ];

            $builder = $this->db->table('master.usertype');
            $builder->where('id', $id);
            $builder->update($data);

            return "1"; // Update successful
        } else {
            if ($checker != 0) {
                return "ERROR, THE DISPATCH FLAG IS ALREADY USED";
            } else if ($checker2 != 0) {
                return "ERROR, THE MANAGEMENT FLAG IS ALREADY USED";
            } else {
                return "ERROR, ANY FLAG IS ALREADY USED";
            }
        }
    }


    public function getDept($name)
    {
        if ($name[0] == 2 && $name[1] == 'PROTOCOL') {
            return $this->db->table('master.userdept')
                ->select('id, dept_name')
                ->where('id', 41)
                ->get()
                ->getResultArray();
        } else {
            return $this->db->table('master.userdept')
                ->select('id, dept_name')
                ->orderBy('id')
                ->get()
                ->getResultArray();
        }
    }

    public function getAllUserLeave_full()
    {
        $selectQuery = "
        SELECT a.*, 
               b.dept_name AS dept, 
               c.type_name AS utype, 
               d.name AS perticular_user, 
               (SELECT name FROM users_history WHERE UserID = a.ucode AND display = 'Y' AND (to_date IS NULL OR to_date IS NOT NULL)) AS part_user_name, 
               (SELECT Emp_id FROM users_history WHERE UserID = a.ucode AND display = 'Y' AND (to_date IS NULL OR to_date IS NOT NULL)) AS part_user_empid, 
               e.name AS ltype, 
               f.name AS faname, 
               g.name AS aaname 
        FROM master.user_l_map a 
        LEFT JOIN master.userdept b ON a.udept = b.id
        LEFT JOIN master.usertype c ON a.utype = c.id
        LEFT JOIN master.users d ON a.ucode = d.usercode
        LEFT JOIN master.user_l_type e ON a.l_type = e.id
        LEFT JOIN master.users f ON a.f_auth = f.usercode
        LEFT JOIN master.users g ON a.a_auth = g.usercode
        WHERE a.display = 'Y'
        ORDER BY a.udept, a.utype, a.ucode, a.l_type, a.f_auth, a.a_auth";
    
        $query = $this->db->query($selectQuery);
        $result = $query->getResultArray();
        return !empty($result) ? $result : 0;
    }
      
    public function new_user()
    {
        $builder = $this->db->table('master.users a');
        $builder->select('a.usercode, a.name, b.type_name, a.empid');
        $builder->join('master.usertype b', 'b.id = a.usertype', 'left');
        $builder->where('a.display', 'Y');
        $builder->orderBy('a.name');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function result_dept()
    {
        $sql = "SELECT 
                z.id, 
                z.dept_name, 
                z.uside_flag, 
                STRING_AGG(COALESCE(y.section_name, ''), ',' ORDER BY y.id) AS type_name, 
                STRING_AGG(COALESCE(z.utype::text, ''), ',') AS utype_top
            FROM (
                SELECT 
                    a.id, 
                    a.dept_name, 
                    a.uside_flag, 
                    COALESCE(b.utype, 0) AS utype  -- Prevent NULLs in join condition
                FROM 
                    master.userdept a
                LEFT JOIN 
                    master.user_d_t_map b 
                    ON a.id = b.udept 
                    AND b.display = 'Y'
                WHERE 
                    a.display = 'Y'
            ) z
            LEFT JOIN 
                master.usersection y 
                ON z.utype = y.id
            GROUP BY 
                z.id, z.dept_name, z.uside_flag";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getUserData_code_obj($id)
    {
        $builder = $this->db->table('master.users');
        $builder->select('usercode,name,usertype,udept,section,empid,service');
        $builder->where('usercode', $id);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getUserTypeByUDept($utype)
    {
        $builder = $this->db->table('master.user_d_t_map a');
        $builder->select('utype, section_name');
        $builder->join('master.usersection b', 'utype = b.id', 'left');
        $builder->where('udept', $utype);
        $builder->where('a.display', 'Y');
        $builder->orderBy('utype');

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getAllUserTypefull_fromNewUser()
    {
        $builder = $this->db->table('master.usertype');
        $builder->select('id,type_name,disp_flag,mgmt_flag');
        $builder->where('display', 'Y');
        $builder->orderBy('id');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function edit_user($udept, $usec, $utype, $empid, $tempid, $name, $service, $ucode, $user)
    {
        $employee = 0;
        if ($empid != 0) {
            $builder = $this->db->table('master.users');
            $builder->where('empid', $empid);
            $builder->where('display', 'Y');
            $query = $builder->get();
            $checker = $query->getResultArray();
            $employee = $empid;
        } else if ($tempid != 0) {
            $builder = $this->db->table('master.users');
            $builder->where('empid', $tempid);
            $builder->where('display', 'Y');
            $query = $builder->get();
            $checker = $query->getResultArray();

            $employee = $tempid;
        } else {
            $checker = 0;
            $checker2 = 0;
        }
        if ($checker == 0 && $checker2 == 0) {
            if ($service == 'J') 
                $name = 'Justice ' . $name;
                $data = [
                    'name' => $name,
                    'usertype' => $utype,
                    'udept' => $udept,
                    'section' => $usec,
                    'empid' => $employee,
                    'service' => $service,
                    'upuser' => $user,
                    'updt' => date('Y-m-d H:i:s')
                ];

                $builder = $this->db->table('master.users');
                $builder->where('usercode', $ucode);
                $builder->update($data);
                return true;
        } else {
            if ($checker != 0)
                return "ERROR, EMPLOYEE ID ALREADY EXISTS";
            if ($checker2 != 0)
                return "ERROR, ONLY ONE SO IS PERMITTED FOR EVERY DEPARTMENT";
        }
    }


    public function add_user($udept,$usec,$utype,$empid,$tempid,$name,$service,$user){
        $employee=0;
        if($empid != 0){
            $checker = $this->check_EMP_ID($empid);
            $employee = $empid;
        }
        else if($tempid != 0){
            $checker = $this->check_EMP_ID($tempid);
            $employee = $tempid;
        }else{        
            $checker = 0;
        }
        
            $checker2 = 0;
       
        if($checker == 0 && $checker2 == 0){           
             
            if($service == 'J')
                $name = 'Justice '.$name;

          
              $pass = md5($employee.$employee);
               $insert = "INSERT INTO master.users(userpass,name,empid,service,usertype,section,udept,entdt,entuser,upuser,updt) 
                VALUES('$pass','".$name."','$employee','$service',$utype,$usec,$udept,NOW(),$user,0,NOW())";              
                $this->db->query($insert);
     
            return "1";
        }
        else{
            if($checker != 0)
                return "ERROR, EMPLOYEE ID ALREADY EXISTS";
            if($checker2 != 0)
                return "ERROR, ONLY ONE SO IS PERMITTED FOR EVERY DEPARTMENT";
        }
    }

    public function check_EMP_ID($emp_id, $ucode = NULL){
        $check = "SELECT * FROM master.users WHERE empid = '$emp_id' AND display = 'Y'";
        $check = $this->db->query($check);
        $check_row = $check->getRowArray();
        if(!empty($check_row)){
            if($ucode == NULL)
                return 1;
            else{
                $check_row = $check->getRowArray();
                if($check_row['usercode'] == $ucode)
                    return 0;
                else
                    return 1;
            }
        }
        else
            return 0;
    }



    public function getVcRoomStatus($userId, $ipAddress)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('master.case_status_flag');
        $builder->where('flag_name', 'vc_room');
        $builder->where('to_date IS NULL',);  // Consider handling this as NULL in PostgreSQL
        $builder->where('CAST(display_flag as BIGINT)', 0);

        // Use `string_to_array()` to handle FIND_IN_SET equivalent
        $builder->where("position('".$userId."' in always_allowed_users) > 0", null, false);
        //$builder->where("position('".$ipAddress."' in ip) > 0", null, false);
       // pr($builder->getCompiledSelect());
        $query = $builder->get();
        return $result = $query->getRowArray(); 
    }

    public function getUpcomingDates()
    {
        $db = \Config\Database::connect(); // Connect to DB
        $builder = $db->table('cl_printed p');
        $builder->distinct();
        $builder->select('p.next_dt');
        $builder->join('master.roster r', 'r.id = p.roster_id');
        $builder->where('r.display', 'Y');
        $builder->where('p.display', 'Y');
        $builder->where('r.courtno >=', 1);
        $builder->where('r.courtno <=', 70);
        //$builder->where('p.next_dt >=', date('Y-m-d'));  
        $builder->where('p.next_dt >=', '2024-02-14');  // For testing
        //pr($builder->getCompiledSelect());
        return $builder->get()->getResultArray(); // Fetch as an array
    }

    public function getCourtDetails($nextDt)
{
    $db = \Config\Database::connect();
    $builder = $db->table('cl_printed p');

    // Select fields
    $builder->select("
        (CASE 
            WHEN r.courtno BETWEEN 31 AND 60 THEN (r.courtno - 30)
            WHEN r.courtno BETWEEN 61 AND 70 THEN (r.courtno - 40)
            ELSE r.courtno 
        END) AS court_sorting,
        r.frm_time, 
        u.name AS username, 
        v.created_on, 
        v.vc_url, 
        p.roster_id, 
        p.next_dt, 
        p.main_supp, 
        p.m_f, 
        r.courtno, 
        mb.board_type_mb,
        STRING_AGG(j.jcode::TEXT, ', ' ORDER BY j.judge_seniority) AS judge_code,  -- FIXED ORDER BY
        STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS judge_name,  -- FIXED ORDER BY
        STRING_AGG(
            CASE 
                WHEN from_brd_no = to_brd_no THEN to_brd_no::TEXT 
                ELSE CONCAT(from_brd_no, '-', to_brd_no) 
            END, ', ') AS item_numbers
    ");

    // Joins
    $builder->join('master.roster r', 'r.id = p.roster_id');
    $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
    $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
    $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
    $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
    $builder->join('vc_room_details v', 'v.roster_id = p.roster_id AND v.next_dt = p.next_dt AND v.display = \'Y\'', 'left');
    $builder->join('master.users u', 'u.usercode = v.created_by', 'left');

    // Conditions
    $builder->where('r.display', 'Y');
    $builder->where('rb.display', 'Y');
    $builder->where('mb.display', 'Y');
    $builder->where('j.is_retired !=', 'Y');
    $builder->where('j.display', 'Y');
    $builder->where('rj.display', 'Y');
    $builder->where('r.courtno >=', 1);
    $builder->where('r.courtno <=', 70);
    $builder->where('p.display', 'Y');
    $builder->where('p.next_dt', $nextDt);
    $builder->whereIn('p.main_supp', [1, 2]);
    $builder->where('mb.board_type_mb !=', 'CC');

    // GROUP BY all non-aggregated fields
    $builder->groupBy([
        'p.roster_id', 
        'r.frm_time', 
        'u.name', 
        'v.created_on', 
        'v.vc_url', 
        'p.next_dt', 
        'p.main_supp', 
        'p.m_f', 
        'r.courtno', 
        'mb.board_type_mb',
        'court_sorting'
    ]);

    // ORDER BY
    $builder->orderBy('court_sorting', 'ASC');
    $builder->orderBy('p.main_supp', 'ASC');

    return $builder->get()->getResultArray();
}




public function checkCoramChange($nextDt, $rosterId)
{
    $db = \Config\Database::connect();
    $builder = $db->table('vc_room_details v');

    // Subquery for `old_coram`
    $subquery = "
        SELECT 
            STRING_AGG(j.abbreviation, ', ' ORDER BY j.judge_seniority) AS old_coram,
            v.next_dt, 
            v.roster_id, 
            v.vc_url AS old_url, 
            v.created_on, 
            r.courtno, 
            r.m_f 
        FROM vc_room_details v
        LEFT JOIN master.roster r ON r.id = v.roster_id AND r.display != 'Y'
        LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id
        LEFT JOIN master.judge j ON j.jcode = rj.judge_id
        WHERE v.next_dt = '$nextDt'
        AND r.id IS NOT NULL
        GROUP BY v.next_dt, v.roster_id, v.vc_url, v.created_on, r.courtno, r.m_f, r.id
    ";

    // Main Query
    $builder->select('a.*, r.id')
            ->from("($subquery) AS a", true)
            ->join('master.roster r', 'r.courtno = a.courtno AND r.m_f = a.m_f')
            ->where('r.display', 'Y')
            ->where('r.from_date', $nextDt)
            ->where('r.id', $rosterId)
            ->limit(1);

    return $builder->get()->getRowArray(); // Return single row
}


    public function getVCConsentCount($nextDt, $rosterId)
    {
        $db = \Config\Database::connect(); // Connect to DB
        $builder = $db->table('consent_through_email c');

        $builder->select('COUNT(c.id) AS total')
                ->join('cl_printed cp', 'cp.next_dt = c.next_dt AND cp.roster_id = c.roster_id AND cp.part = c.part AND cp.display = \'Y\'', 'left')
                ->where('cp.next_dt IS NOT NULL')
                ->where('c.is_deleted IS NULL')
                ->where('c.next_dt', $nextDt)
                ->where('c.roster_id', $rosterId);

        return $builder->get()->getRowArray()['total']; // Return count
    }


    public function checkVcRoomDetails($nextDt, $rosterId, $vcUrl, $vcItemsCsv, $vcItem)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vc_room_details');

        $builder->select('next_dt')
                ->where('next_dt', $nextDt)
                ->where('roster_id', $rosterId)
                ->where('vc_url', $vcUrl)
                ->where('item_numbers_csv', $vcItemsCsv)
                ->where('item_numbers', $vcItem);

        return $builder->get()->getRowArray(); // Fetch single row
    }


    public function insertEmailHcCl($nextDt, $rosterId, $vcItemsCsv, $vcQryFrom)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            INSERT INTO email_hc_cl (title, name, email, diary_no, next_dt, mainhead, court, judges, roster_id, board_type, brd_slno, ent_time, cno, jnames, pname, rname, qry_from)
            SELECT j.* FROM (
                SELECT title, name, email, diary_no, next_dt, mainhead, court, judges, roster_id, board_type, brd_slno, NOW(),
                    CASE 
                        WHEN reg_no_display = '' THEN CONCAT('Diary No. ', diary_no) 
                        ELSE CONCAT('Case No. ', reg_no_display) 
                    END AS cno,
                    (SELECT STRING_AGG(jname, ',' ORDER BY judge_seniority) 
                    FROM master.roster_judge r 
                    INNER JOIN master.judge j ON j.jcode = r.judge_id 
                    WHERE r.roster_id = a.roster_id
                    GROUP BY r.roster_id) AS jnm,
                    pname, rname, ? AS qry_from
                FROM (
                    SELECT * FROM (
                        SELECT p.id, b.title, b.name, b.email, m.active_fil_no, m.reg_no_display, m.diary_no, a.advocate_id, 
                            h.next_dt, h.mainhead, h.judges, h.roster_id, h.board_type, h.clno, h.brd_slno,
                            CASE 
                                WHEN h.clno = 50 OR h.clno = 51 THEN 'By Circulation' 
                                WHEN r.courtno BETWEEN 31 AND 60 THEN CONCAT('Court No. ', r.courtno - 30)
                                WHEN r.courtno BETWEEN 61 AND 70 THEN CONCAT('Registrar Court No. ', r.courtno - 60)
                                WHEN r.courtno = 21 THEN 'Court No. R 1' 
                                WHEN r.courtno = 22 THEN 'Court No. R 2' 
                                ELSE CONCAT('Court No. ', r.courtno) 
                            END AS court,
                            CASE 
                                WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') 
                                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') 
                                ELSE m.pet_name 
                            END AS pname,
                            CASE 
                                WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') 
                                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') 
                                ELSE m.res_name 
                            END AS rname
                        FROM heardt h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.display = 'Y' AND a.advocate_id != 0
                        LEFT JOIN bar b ON b.bar_id = a.advocate_id AND b.isdead != 'Y' AND b.email ~ '^[^@]+@[^@]+\.[^@]{2,}$' AND b.email IS NOT NULL
                        LEFT JOIN roster r ON r.id = h.roster_id
                        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                        WHERE a.diary_no IS NOT NULL 
                            AND b.bar_id IS NOT NULL 
                            AND r.id IS NOT NULL 
                            AND h.brd_slno > 0 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                            AND h.next_dt = ? 
                            AND h.roster_id = ? 
                            AND h.brd_slno IN ($vcItemsCsv)
                            
                        UNION

                        SELECT p.id, '' AS title, pt.partyname AS name, pt.email, m.active_fil_no, m.reg_no_display, m.diary_no, a.advocate_id, 
                            h.next_dt, h.mainhead, h.judges, h.roster_id, h.board_type, h.clno, h.brd_slno,
                            CASE 
                                WHEN h.clno = 50 OR h.clno = 51 THEN 'By Circulation' 
                                WHEN r.courtno BETWEEN 31 AND 60 THEN CONCAT('Court No. ', r.courtno - 30)
                                WHEN r.courtno BETWEEN 61 AND 70 THEN CONCAT('Registrar Court No. ', r.courtno - 60)
                                WHEN r.courtno = 21 THEN 'Court No. R 1' 
                                WHEN r.courtno = 22 THEN 'Court No. R 2' 
                                ELSE CONCAT('Court No. ', r.courtno) 
                            END AS court,
                            CASE 
                                WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') 
                                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') 
                                ELSE m.pet_name 
                            END AS pname,
                            CASE 
                                WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') 
                                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') 
                                ELSE m.res_name 
                            END AS rname
                        FROM heardt h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.display = 'Y' AND a.advocate_id IN (584, 585, 610, 616, 666, 940)
                        LEFT JOIN party pt ON pt.diary_no = m.diary_no AND pt.email ~ '^[^@]+@[^@]+\.[^@]{2,}$' AND pt.email IS NOT NULL
                        LEFT JOIN roster r ON r.id = h.roster_id
                        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                        WHERE a.diary_no IS NOT NULL 
                            AND pt.diary_no IS NOT NULL 
                            AND r.id IS NOT NULL 
                            AND h.brd_slno > 0 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                            AND h.next_dt = ? 
                            AND h.roster_id = ? 
                            AND h.brd_slno IN ($vcItemsCsv)
                    ) f WHERE f.id IS NOT NULL
                    GROUP BY email, diary_no, next_dt
                ) a
            ) j
            LEFT JOIN email_hc_cl l ON j.diary_no = l.diary_no 
                                    AND j.email = l.email 
                                    AND l.next_dt = j.next_dt 
                                    AND l.mainhead = j.mainhead 
                                    AND l.roster_id = j.roster_id 
                                    AND l.brd_slno = j.brd_slno 
                                    AND l.qry_from = ?
            WHERE l.diary_no IS NULL;
        ";

        return $db->query($sql, [$vcQryFrom, $nextDt, $rosterId, $nextDt, $rosterId, $vcQryFrom]);
    }



    public function getEmailDetails($vcQryFrom)
    {
        $db = \Config\Database::connect();

        $sql = "
            SELECT 
                email, 
                title, 
                name, 
                CASE 
                    WHEN (r.frm_time IS NULL OR r.frm_time = '') THEN '10:30 AM' 
                    ELSE r.frm_time 
                END AS start_time,
                vrd.vc_url,
                e.next_dt,
                e.court
            FROM email_hc_cl e
            INNER JOIN vc_room_details vrd ON e.next_dt = vrd.next_dt
                AND e.roster_id = vrd.roster_id
            INNER JOIN cl_printed p ON p.next_dt = vrd.next_dt
                AND p.roster_id = vrd.roster_id
                AND p.display = 'Y'
            INNER JOIN roster r ON p.roster_id = r.id
            WHERE r.display = 'Y' 
                AND sent_to_smspool = 'N' 
                AND qry_from = ?
            GROUP BY email, title, name, start_time, vrd.vc_url, e.next_dt, e.court
        ";

        return $db->query($sql, [$vcQryFrom])->getResultArray();
    }


    public function insertIntoSmsHcCl($nextDt, $rosterId, $vcItemsCsv, $vcQryFrom)
    {
        $db = \Config\Database::connect();

        $sql = "
            INSERT INTO sms_hc_cl (mobile, diary_no, next_dt, mainhead, court, roster_id, brd_slno, ent_time, cno, qry_from, pet_name, res_name)
            SELECT j.* FROM (
                SELECT 
                    mobile, 
                    diary_no, 
                    next_dt, 
                    mainhead, 
                    court, 
                    roster_id, 
                    brd_slno, 
                    NOW(),
                    CASE 
                        WHEN reg_no_display = '' THEN CONCAT('Diary No. ', diary_no) 
                        ELSE CONCAT('Case No. ', reg_no_display) 
                    END AS cno,
                    ? AS qry_from,
                    pname, 
                    rname
                FROM (
                    SELECT * FROM (
                        SELECT 
                            p.id,
                            b.mobile, 
                            m.active_fil_no, 
                            m.reg_no_display,
                            m.diary_no, 
                            a.advocate_id, 
                            h.next_dt, 
                            h.mainhead, 
                            h.judges, 
                            h.roster_id, 
                            h.clno, 
                            h.brd_slno,
                            CASE 
                                WHEN h.clno IN (50, 51) THEN 'By Circulation' 
                                WHEN r.courtno BETWEEN 31 AND 60 THEN CONCAT('Court No. ', r.courtno - 30)
                                WHEN r.courtno BETWEEN 61 AND 70 THEN CONCAT('Registrar Court No. ', r.courtno - 60)
                                WHEN r.courtno = 21 THEN 'Court No. R 1' 
                                WHEN r.courtno = 22 THEN 'Court No. R 2' 
                                ELSE CONCAT('Court No. ', r.courtno) 
                            END AS court,
                            CASE 
                                WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') 
                                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') 
                                ELSE m.pet_name 
                            END AS pname,
                            CASE 
                                WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') 
                                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') 
                                ELSE m.res_name 
                            END AS rname
                        FROM heardt h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                            AND a.display = 'Y' 
                            AND a.advocate_id NOT IN (0, 939, 1277)
                        LEFT JOIN bar b ON b.bar_id = a.advocate_id 
                            AND b.isdead != 'Y' 
                            AND CHAR_LENGTH(b.mobile) = 10 
                            AND LEFT(b.mobile, 1) NOT BETWEEN '0' AND '6'
                            AND b.name NOT IN ('ATTORNEY GENERAL FOR INDIA', 'SOLICITOR GENERAL OF INDIA')
                            AND b.mobile IS NOT NULL
                        LEFT JOIN roster r ON r.id = h.roster_id
                        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt 
                            AND p.m_f = h.mainhead 
                            AND p.part = h.clno 
                            AND p.roster_id = h.roster_id 
                            AND p.display = 'Y'
                        WHERE a.diary_no IS NOT NULL 
                            AND b.bar_id IS NOT NULL 
                            AND r.id IS NOT NULL
                            AND h.brd_slno > 0 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                            AND h.next_dt = ?
                            AND h.roster_id = ?
                            AND h.brd_slno IN (?)
                        
                        UNION
                        
                        SELECT 
                            p.id,
                            pt.contact AS mobile, 
                            m.active_fil_no, 
                            m.reg_no_display,
                            m.diary_no, 
                            a.advocate_id, 
                            h.next_dt, 
                            h.mainhead, 
                            h.judges, 
                            h.roster_id, 
                            h.clno, 
                            h.brd_slno,
                            CASE 
                                WHEN h.clno IN (50, 51) THEN 'By Circulation' 
                                WHEN r.courtno BETWEEN 31 AND 60 THEN CONCAT('Court No. ', r.courtno - 30)
                                WHEN r.courtno BETWEEN 61 AND 70 THEN CONCAT('Registrar Court No. ', r.courtno - 60)
                                WHEN r.courtno = 21 THEN 'Court No. R 1' 
                                WHEN r.courtno = 22 THEN 'Court No. R 2' 
                                ELSE CONCAT('Court No. ', r.courtno) 
                            END AS court,
                            CASE 
                                WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') 
                                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') 
                                ELSE m.pet_name 
                            END AS pname,
                            CASE 
                                WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') 
                                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') 
                                ELSE m.res_name 
                            END AS rname
                        FROM heardt h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                            AND a.display = 'Y' 
                            AND a.advocate_id IN (584, 585, 610, 616, 666, 940)
                        LEFT JOIN party pt ON pt.diary_no = m.diary_no 
                            AND CHAR_LENGTH(pt.contact) = 10 
                            AND LEFT(pt.contact, 1) NOT BETWEEN '0' AND '6'
                        LEFT JOIN roster r ON r.id = h.roster_id
                        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt 
                            AND p.m_f = h.mainhead 
                            AND p.part = h.clno 
                            AND p.roster_id = h.roster_id 
                            AND p.display = 'Y'
                        WHERE a.diary_no IS NOT NULL 
                            AND pt.diary_no IS NOT NULL 
                            AND r.id IS NOT NULL
                            AND h.brd_slno > 0 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                            AND h.next_dt = ?
                            AND h.roster_id = ?
                            AND h.brd_slno IN (?)
                    ) f 
                    WHERE id IS NOT NULL 
                    GROUP BY mobile, diary_no, next_dt
                ) a
            ) j
            LEFT JOIN sms_hc_cl l ON j.diary_no = l.diary_no 
                AND j.mobile = l.mobile 
                AND l.next_dt = j.next_dt
                AND l.mainhead = j.mainhead 
                AND l.roster_id = j.roster_id 
                AND l.brd_slno = j.brd_slno 
                AND l.qry_from = ?
            WHERE l.diary_no IS NULL
        ";

        return $db->query($sql, [$vcQryFrom, $nextDt, $rosterId, $vcItemsCsv, $nextDt, $rosterId, $vcItemsCsv, $vcQryFrom]);
    }


    public function insertIntoSmsPool($vcQryFrom, $templateId)
    {
        $db = \Config\Database::connect();

        $sql = "
            INSERT INTO sms_pool (mobile, msg, table_name, ent_time, template_id)
            SELECT 
                shc.mobile,
                CONCAT(
                    'Video Conferencing link for ', shc.court, ' on ', 
                    TO_CHAR(shc.next_dt, 'DD-MM-YYYY'), ' at ', 
                    CASE 
                        WHEN (r.frm_time IS NULL OR r.frm_time = '') THEN '10:30 AM' 
                        ELSE r.frm_time 
                    END,
                    ' is ', vrd.vc_url, '. - SUPREME COURT OF INDIA'
                ) AS cno1,
                ?, 
                NOW(), 
                ?
            FROM sms_hc_cl shc
            INNER JOIN vc_room_details vrd ON shc.next_dt = vrd.next_dt 
                AND shc.roster_id = vrd.roster_id
            INNER JOIN cl_printed p ON p.next_dt = vrd.next_dt 
                AND p.roster_id = vrd.roster_id 
                AND p.display = 'Y'
            INNER JOIN roster r ON p.roster_id = r.id
            WHERE r.display = 'Y' 
                AND sent_to_smspool = 'N' 
                AND qry_from = ?
            GROUP BY shc.mobile, shc.court, shc.next_dt, r.frm_time, vrd.vc_url
        ";

        return $db->query($sql, [$vcQryFrom, $templateId, $vcQryFrom]);
    }



    public function getUser_mgmt_range($val){
        $query = "SELECT ut.id, ut.type_name, mgmt_flag, low, up FROM master.usertype ut LEFT JOIN master.user_range ur ON ut.id = utype AND ur.display = 'Y' WHERE ut.id = $val";
        $result = $this->db->query($query);
        $row = $result->getRowArray();
        
        $low = $row['low'];
        if($low == '')
            $low = 1;
        
         
        
        $newusercode = $this->getNewUserCode($low);
        
        if($low == 1){
            $range = "SELECT * FROM user_range WHERE display = 'Y'";
            $range_rs = $this->db->query($range);
            $range_rs = $range_rs->getResultArray();
            if(!empty($range_rs)){
                foreach($range_rs as $row_range){
                    
                    if(($newusercode >= $row_range['low']) && ($newusercode <= $row_range['up'])){
                        $newusercode = $this->getNewUserCode($row_range['up']);
                    }   
                }
            }
        }
        return $row['id'].'~'.$row['type_name'].'~'.$row['mgmt_flag'].'~'.$row['low'].'~'.$row['up'].'~'.$newusercode;
    }


    public function getNewUserCode($low)
    {
        $sql = "
            SELECT t.*, cur_num - last_num - 1 AS new_usercode FROM (
                SELECT 
                    ROW_NUMBER() OVER (ORDER BY usercode) AS numb,
                    usercode,
                    name,
                    usercode AS cur_num,
                    LAG(usercode) OVER (ORDER BY usercode) AS last_num
                FROM master.users
                WHERE usercode > ?
            ) t
            WHERE (cur_num - last_num - 1) > 0 AND usercode > 10
            LIMIT 1
        ";
    
        $query = $this->db->query($sql, [$low]);
        $result = $query->getRowArray();
    
        return $result['new_usercode'] ?? null;
    }






}
