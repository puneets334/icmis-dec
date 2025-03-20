<?php
namespace App\Models\Filing;

use CodeIgniter\Model;

class DefectModel extends Model
{


    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

//public function get_defect_list($dataFind)
//{
//
//     $builder = $this->db->table("main");
//     $builder->select("pet_name,res_name,to_char(diary_no_rec_date,'dd-mm-yyyy') dt, case_grp,fil_no,c_status,casetype_id,dacode");
//     $builder->where('diary_no',$dataFind);
//     $query = $builder->get();
//     $result = $query->getResultArray();
//     return $result;
//}

    public function get_all_defect($old_defect)
    {
        //  print_r($old_defect);die;

        $eliminate_other_from_old_defect_array = array_diff($old_defect, array(302));
//        echo "<pre>";
//        print_r($eliminate_other_from_old_defect_array);
//        die;
        $builder = $this->db->table("master.objection");
        $builder->select("objcode org_id,objdesc obj_name,sideflg ci_cri");
        $builder->where('display', 'Y');
        if (!empty($eliminate_other_from_old_defect_array)) {
            $builder->whereNotIn('objcode', $eliminate_other_from_old_defect_array);
        }            // $builder->whereNotIn('objcode', $old_defect);
        $builder->orderBy('defect_code_main', 'defect_code_sub', 'objdesc');
        $query = $builder->get();
//           $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        return $result;


    }
    /*THIS METHOD IS NOT IN USE NOW  STARTING FROM LINE 45-58 */
//    public function get_existing_otherdefect($diary_no)
//    {
//
//        $builder = $this->db->table("obj_save a");
//        $builder->select("diary_no,a.org_id,objdesc obj_name, rm_dt,remark, ARRAY_TO_STRING(ARRAY_AGG(mul_ent), ',') mul_ent");
//        $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner', false);
//        $builder->where('diary_no', $diary_no)->where('a.org_id', '302')->where('a.display', 'Y');
//        $builder->groupBy('diary_no,a.org_id, a.remark,b.objdesc,rm_dt,a.id');
//        $builder->orderBy('id');
//        $query = $builder->get();
//        $result = $query->getResultArray();
//        return $result;
//
//    }


public function get_all_existing_defect($diary_no)
{
    $builder = $this->db->table("obj_save a");
    $builder->select("a.diary_no, a.id, a.org_id, b.objdesc AS obj_name, a.rm_dt, a.remark, ARRAY_TO_STRING(ARRAY_AGG(a.mul_ent), ',') AS mul_ent");
    $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner', false);
    $builder->where('a.diary_no', $diary_no);
    $builder->where('a.display', 'Y');
    $builder->groupBy('a.diary_no, a.org_id, a.remark, b.objdesc, a.rm_dt, a.id');
    $builder->orderBy('a.id');
    $query = $builder->get();

    $result = $query->getResultArray();
    return $result;
}


    public function navigate_diary($diary_no)
    {

        $builder = $this->db->table('main m', false);
        $builder->select("m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, m.active_fil_dt, m.lastorder, m.c_status", false);
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left', false);
        $builder->where('m.diary_no', $diary_no, false);
        $query = $builder->get();
        //  $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        return $result;


    }

    public function check_section($user)
    {

        $builder = $this->db->table('master.users u', false);
        $builder->select('*', false);
        $builder->join('master.usersection us', 'u.section=us.id',  false);
        $builder->where('u.usercode', "'$user'", false)->where('us.isda', '\'Y\'', false)->where('u.display', '\'Y\'', false);
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
//    $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getNumRows();
        return $result;

    }

    public function get_da($diary_no)
    {

        $da = 0;
        $builder = $this->db->table("main");
        $builder->select("dacode");
        $builder->where('diary_no', $diary_no);
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        $result = $query->getResultArray();
        if (count($result) > 0) {
            $da = $result[0]['dacode'];
            return $da;
        }else{
            return 0;
        }

    }

    public function check_if_chamber_listed($diary_no)
    {
        // QUERRY TO BE CONVERT IN ACTIVE RECORD LATER ******************************


        /* $subquery = $this->db->table('defective_chamber_listing')->select('listing_date')->where('display','Y')->getCompiledSelect();
         $query1 = $this->db->table('heardt')->select('diary_no,next_dt')->whereIn('main_supp_flag',[1,2])->where('diary_no',$diary_no);
         $query2 = $this->db->table('last_heardt')->select('diary_no,next_dt')->whereIn('main_supp_flag',[1,2])->where('diary_no',$diary_no)->groupStart()->where('bench_flag is null')->orwhere('bench_flag','')->groupEnd();

         $builder = $query1->union($query2)->get();
         echo $this->db->getLastQuery(); exit();

         $this->db->groupStart()->table($builder)->groupEnd()->select('next_dt')->where('next_dt',$subquery)->get();

         echo $this->db->getLastQuery(); exit();


         $builder = $this->db->table('heardt')->select('diary_no,next_dt')->whereIn('main_supp_flag',[1,2])->where('diary_no',$diary_no);
         $query1 = $builder->getCompiledSelect();
         $builder = $this->db->table('last_heardt')->select('diary_no,next_dt')->whereIn('main_supp_flag',[1,2])->where('diary_no',$diary_no)->groupStart()->where('bench_flag is null')->orwhere('bench_flag','')->groupEnd();
         $query2=$builder->getCompiledSelect();
         $result=$this->db->query($query1.' UNION '.$query2)->getCompiledSelect();


         $subquery = $this->db->table('defective_chamber_listing')->select('listing_date')->where('display','Y')->get();

         $this->db->select('next_dt')->from($query1.' UNION '.$query2)->whereIn('next_dt',$subquery);
         $query    = $builder->get();
         echo $this->db->getLastQuery(); exit();


         $builder  = $this->db->newQuery()->fromSubquery($subquery, 't');



         $this->db->select("concat('https://main.sci.gov.in/jonew/bosir/orderpdfold/',pno,'.pdf') as file_path,concat(nc.pdf_name,'.pdf') as file_name", false);
         $this->db->from('nc_njdg nc',false);
         $this->db->join("rop_text_web.ordertext ot", "nc.diary_no=ot.dn and nc.dispose_order_date=date_format(ot.orderdate,'%Y-%m-%d') and ot.order_type='judgment'","left",false);
         $this->db->where('nc.diary_no', $diary_no)->where('nc.dispose_order_date',$dispose_date);
         $query6 = $this->db->get_compiled_select();

         $result = $this->db->query($query1 . ' UNION ' . $query2. ' UNION ' . $query3. ' UNION ' . $query4. ' UNION ' . $query5. ' UNION ' . $query6)->result_array();*/


        $sql = "select next_dt from( select diary_no,next_dt from heardt where main_supp_flag in (1,2) and diary_no='$diary_no'
        union select diary_no,next_dt from last_heardt where main_supp_flag in(1,2) and (bench_flag is null or bench_flag='')
        and diary_no='$diary_no')aa where next_dt in(select listing_date from defective_chamber_listing where display='Y')";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
//        echo $query->getNumRows();
//        echo "ttt";
//        echo $this->db->getLastQuery(); exit();
        if($query->getNumRows() > 0)
        {
            return 1;
        }else{
            return 0;
        }
}

    public function check_if_listed($diary_no)
    {
            $sql = "select min(next_dt) as next_dt
                            from( select diary_no,next_dt from heardt where main_supp_flag in (1,2) and diary_no='$diary_no'
                            union select diary_no,next_dt from last_heardt where main_supp_flag in(1,2) and (bench_flag is null or bench_flag='')
                            and  diary_no='$diary_no') as aa where next_dt >= CURRENT_DATE ";

            $query1 = $this->db->query($sql);
        $result = $query1->getResultArray();
//           echo $this->db->getLastQuery(); exit();
//        echo "<pre>";
//        print_r($result);
//        die;
        if($result)
        {
            return $result[0];
        }else{
            return 0;
        }
    }

    public function check_if_verified($diary_no)
    {

        $builder = $this->db->table("defects_verification");
        $builder->select("*");
        $builder->where('diary_no', $diary_no)->where('verification_status', '0');
        $query = $builder->get();
        $result = $query->getResultArray();
//        echo $this->db->getLastQuery(); exit();
//        echo $query->getNumRows();die;
        if ($query->getNumRows() > 0)
        {
           return 1;
        }else{
            return 0;
        }
    }


/*
            if ($resultCheckIfListed[0]['next_dt']!=NULL &&  $resultCheckIfListed[0]['next_dt']!='')
            {
                $check_if_listed = $resultCheckIfListed[0];
//                print_r($check_if_listed);
//                die;
                if (($check_if_listed != null) && ($check_if_listed != '')) {
                    $check = array(
                        '1' => 'Case Is Listed. Defects cannot be added sfsfsff!!!!');
//                    print_r($check);
//                    exit;
                    return $check;
//                    echo "<div style='text-align:center;color: red'><h3>Case Is Listed. Defects cannot be added!!!!</h3></div>";
//                    exit(0);
                } else {
                    $builder = $this->db->table("defects_verification");
                    $builder->select("*");
                    $builder->where('diary_no', $diary_no)->where('verification_status', '0');
                    $query3 = $builder->get();
                    $result = $query3->getResultArray();

                    if ($query3->getNumRows() > 0) {
                        $check = array(
                            '2' => 'Case Is Verified. Defects cannot be added!!!!');
//                        print_r($check);
//                        exit;
                        return $check;
//                        echo "<div style='text-align:center;color: red'><h3>Case Is Verified. Defects cannot be added!!!!</h3></div>";
//                        exit(0);
                    }
                }
            } else {

                $builder = $this->db->table("defects_verification");
                $builder->select("*");
                $builder->where('diary_no', $diary_no)->where('verification_status', '0');
                $query4 = $builder->get();
                $result = $query4->getResultArray();
//echo $query4->getNumRows();die;
                if ($query4->getNumRows() > 0) {
                    $check = array(
                        '2' => 'Case Is Verified. Defects cannot be added!!!!');
//                    print_r($check);
//                    exit;
                    return $check;
//                    echo "<div style='text-align:center;color: red'><h3>Case Is Verified. Defects cannot be added!!!!</h3></div>";
//                    exit(0);
                }
            }
        } else {
            $check = array(
                '3' => 'set ifChamberListed to 1');

            return $check;
        }

*/
//    }


    public function get_soft_copy_user($uCode)
    {
        $builder = $this->db->table("master.specific_role");
        $builder->distinct("usercode");
        $builder->where('display', 'Y');
        $builder->where('flag', 'S');
        $builder->where('usercode', $uCode);
        $query4 = $builder->get();
        //   $result = $query4->getResultArray();
//        echo $this->db->getLastQuery();exit;

        if ($query4->getNumRows() > 0)
        {
            return 1;

        }else{
            return 0;
        }
    }

    public function check_if_counterfiled($diary_no)
    {
        $query1 = $this->db->table('efiled_cases')->select('diary_no')->where('diary_no', $diary_no)->where('efiled_type', 'new_case');
        $query2 = $this->db->table('main')->select('diary_no')->where('diary_no', $diary_no)->where('ack_id!=', '0');

        $builder = $query1->union($query2)->get();
//          echo $this->db->getLastQuery(); exit();
        //  echo $builder->getNumRows();die;
        if ($builder->getNumRows() > 0)
        {
            return 1;
        }else{
            return 0;
        }


    }

    public function check_if_registered($diary_no)
    {
        $builder = $this->db->table("main");
        $builder->select("fil_no");
        $builder->where('diary_no', $diary_no)->where('active_fil_no is not null')->where('active_fil_no!=', '');
        $query = $builder->get();
        $result = $query->getResultArray();
//      echo $this->db->getLastQuery();exit;
//        echo "<pre>";
//        print_r($result);
//        die;

        if ($query->getNumRows() > 0)
        {
            return $result[0];
        }else{
            return 0;
        }
    }


    public function check_old_defect($diary_no)
    {
        $builder = $this->db->table("obj_save");
        $builder->select("rm_dt,status");
        $builder->where('diary_no', $diary_no)->where('display', 'Y');
        $query4 = $builder->get();

//       echo $this->db->getLastQuery();exit;

        if ($query4->getNumRows() > 0)
        {
            $result = $query4->getResultArray();
            return $result;
        }else{
            return [];
        }
    }

    public function check_fil_trap($diary_no)
    {
        $builder = $this->db->table("fil_trap f ");
        $builder->select("remarks, d_to_empid, usercode,name");
        $builder->join('master.users u',' f.d_to_empid=u.empid');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $result = $query->getResultArray();
//        echo $this->db->getLastQuery();exit;
        if($result)
        {
            return $result;
        }else{
            return [];
        }


    }

    public function insert_function($dataArr)
    {

        $diaryNo = $dataArr['diary_no'];
        $sessionUCode = $dataArr['ucode'];
        $orgId = $dataArr['defid'];
        $remark = $dataArr['remark'];

//        echo count($orgId);
//        echo "<pre>";
//        print_r($orgId);

        for($i=0; $i<count($orgId);$i++) {

            //  $org_id =$orgId[$i];
            //  $remark_str = $remark[$i];

            //    echo $org_id.">>>>".$remark_str;

//                $sql = " Insert Into obj_save (org_id,save_dt,usercode,remark,mul_ent,diary_no)
//                         values ($orgId[$i],'NOW()',$sessionUCode,'$remark[$i]','',$diaryNo) ";
//             //   echo $sql."<br>";
//            $query = $this->db->query($sql);

            // }
            //die;
            $columnsData = array(
                'org_id' => $orgId[$i],
                'save_dt' => 'NOW()',
                'usercode' => $sessionUCode,
                'remark' => $remark[$i],
                'mul_ent' => '',
                'diary_no' => $diaryNo,
                'display' =>'Y',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            );

//            echo "<pre>";
//            print_r($columnsData);
//            die;

            $builder = $this->db->table('obj_save');

            $query = $builder->insert($columnsData);
        }
//echo $query;die;
        if($query) {
            return 1;
        }else
        {
            return 0;
        }



    }

    public function update_function($data)
    {
//        $defectName = $data['def_name'];
        $remarkUpdate = $data['new_remark'];
        $id = $data['defid'];
        $diaryNo = $data['diaryno'];
        $usercode = $data['ucode'];


        $columnsUpdate = array(
            'remark' =>$remarkUpdate,
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('obj_save');
        $builder->where('id', $id)->where('diary_no',$diaryNo)->where('usercode', $usercode);
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }

    }
	
	
	public function getMainTableData($diary_no)
	{

		$builder = $this->db->table('main');
		$builder->select("pet_name, res_name, TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') AS dt, case_grp, fil_no, c_status,casetype_id");
		$builder->where('diary_no', $diary_no);
		//$query = $builder->get();

        $builder2 = $this->db->table('main_a');
		$builder2->select("pet_name, res_name, TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') AS dt, case_grp, fil_no, c_status,casetype_id");
		$builder2->where('diary_no', $diary_no);
		//$query = $builder->get();
        //pr($builder->union($builder2)->getCompiledSelect());
        $query = $builder->union($builder2)->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
		} else {
			log_message('error', 'No records found');
			return false; 
		}
	}	

	public function checkifListed($diary_no)
{
    // Build the first subquery
    $subquery1 = $this->db->table('heardt')
        ->select('diary_no, next_dt')
        ->whereIn('main_supp_flag', [1, 2])
        ->where('diary_no', $diary_no)
        ->getCompiledSelect();

    // Build the second subquery
    $subquery2 = $this->db->table('last_heardt')
        ->select('diary_no, next_dt')
        ->whereIn('main_supp_flag', [1, 2])
        ->groupStart()
            ->where('bench_flag IS NULL', null, false)
            ->orWhere('bench_flag', '')
        ->groupEnd()
        ->where('diary_no', $diary_no)
        ->getCompiledSelect();

    // Combine subqueries with UNION
    $unionSubquery = "($subquery1 UNION $subquery2) AS aa";

    // Build the main query using the combined subquery
    $builder = $this->db->table($unionSubquery, false)
        ->select('MIN(next_dt) AS next_dt');

      //  pr($builder->getCompiledSelect());
    // Execute the query
    $query = $builder->get();

    // Fetch the result
    if ($query->getNumRows() > 0) {
        $result = $query->getRowArray();
        return $result['next_dt']; // Return the minimum next_dt
    } else {
        log_message('error', 'No records found');
        return false; // Or handle the error accordingly
    }
}

	
	
	public function defectsVerification($diary_no)
	{
		// Build the query
		$builder = $this->db->table('defects_verification');
		$builder->select('*');
		$builder->where('diary_no', $diary_no);
		$builder->where('verification_status', '0');
        //pr($builder->getCompiledSelect());
		// Execute the query
		$query = $builder->get();

		// Fetch the result
		if ($query->getNumRows() > 0) {
			return $query->getResultArray(); // Return the results as an array
		} else {
			log_message('error', 'No records found');
			return false; // Or handle the error accordingly
		}

	}
	
	
	public function checkIfRegd($diary_no)
	{
		// Build the query
		$builder = $this->db->table('main');
		$builder->select('*');
		$builder->where('diary_no', $diary_no);
		$builder->groupStart();
		$builder->where('fil_no IS NOT NULL', null, false);
		$builder->orWhere("fil_no !=''");
		$builder->groupEnd();

		// Execute the query
		$query = $builder->get();
		return $query->getNumRows();		 

	}
	
	
	public function getObjSaveData($diary_no)
	{
		$builder = $this->db->table('obj_save');
		$builder->select("DATE(rm_dt) AS rm_dt, status");
		$builder->where('diary_no', $diary_no);
		$builder->where('display', 'Y');
		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $query->getResultArray();
		} else {
			log_message('error', 'No records found');
			return false; 
		}
	}

	public function getObjectionDetails($diary_no)
{
    // Build the query
    $builder = $this->db->table('obj_save a');
    $builder->select('a.org_id, b.objdesc AS obj_name, a.rm_dt, a.remark, STRING_AGG(b.mul_ent, \',\') AS mul_ent');
    $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner');
    $builder->where('a.diary_no', $diary_no);
    $builder->where('a.display', 'Y');
    $builder->where('a.status', 0);
    $builder->where('a.rm_dt IS NOT NULL'); // Filter out NULL dates
    $builder->groupBy(['a.diary_no', 'a.org_id', 'a.remark', 'b.objdesc','a.rm_dt','a.id']);
    $builder->orderBy('a.id');

    // Execute the query
    $query = $builder->get();

    // Check for results and handle accordingly
    if ($query->getNumRows() > 0) {
        return $query->getResultArray(); // Return the fetched data
    } else {
        log_message('error', 'No records found');
        return false; // Or handle the error accordingly
    }
}

	
	public function checkIfBO($ucode)
	{
		$builder = $this->db->table('master.users');
		$builder->select('*');
		$builder->where('usercode', $ucode);
		$builder->where('section', '19');
		$builder->where('usertype', '14');

		$query = $builder->get();
		return $query->getNumRows();
		// Fetch the result
		 
	}
	
	
	public function def_notify($diary_no)
	{
		$builder = $this->db->table('obj_save');
		$builder->select('MIN(DATE(save_dt)) AS save_dt, MIN(DATE(rm_dt)) AS rm_dt');
		$builder->where('diary_no', $diary_no);
		$builder->where('display', 'Y');
		$builder->groupBy('diary_no');

		$query = $builder->get();
		// Check for results and handle accordingly
		if ($query->getNumRows() > 0) {
			return $query->getResultArray(); // Return the fetched data
		} else {
			log_message('error', 'No records found');
			return false; // Or handle the error accordingly
		}
		 
	}
	
	
	
	public function gatObjSave($diary_no)
{
    // Start building the query
    $builder = $this->db->table('obj_save a');

    // Join the related tables
    $builder->join('master.objection b', 'a.org_id = b.objcode');
    $builder->join('master.users u', 'a.usercode = u.usercode');
    $builder->join('master.users u1', 'a.rm_user_id = u1.usercode', 'left');

    // Select the required columns
    $builder->select('b.objdesc, a.save_dt, a.rm_dt, a.remark, u.name as ent_by, u1.name as rem_by, a.mul_ent');

    // Apply the conditions
    $builder->where('a.display', 'Y');
    $builder->groupStart()
            ->where('b.display', 'Y')
            ->orGroupStart()
                ->where('b.display', 'N')
                ->where('b.objcode <', 10075)
            ->groupEnd()
        ->groupEnd();

    // Filter by the given diary number
    $builder->where('a.diary_no', $diary_no);

    // Order the results by ID
    $builder->orderBy('a.id');

    // Execute the query
    $query = $builder->get();

    // Check if any results were found
    if ($query->getNumRows() > 0) {
        return $query->getResultArray(); // Return the fetched data as an array
    } else {
        log_message('error', 'No records found for diary number: ' . $diary_no);
        return false; // Handle the case where no records are found
    }
}

	
	
	public function getTotalDefects($diary_no)
	{
		// Build the query
		$builder = $this->db->table('obj_save');

		// Select the count of IDs
		$builder->selectCount('id');

		// Apply the conditions
		$builder->where('display', 'Y');
		$builder->where('diary_no', $diary_no);

		// Group by the diary number
		$builder->groupBy('diary_no');

		// Execute the query
		$query = $builder->get();

		// Fetch the result (the total count of defects)
		$tot_defects = $query->getRowArray(); 

		// If there is a result, get the count, otherwise set to 0
		return $total_count = isset($tot_defects['id']) ? $tot_defects['id'] : 0;
	}
	
	
	public function getAdvocateName($diary_no)
	{
		// Build the query
		$builder = $this->db->table('advocate a');

		// Join the 'bar' table
		$builder->join('master.bar b', 'a.advocate_id = b.bar_id');

		// Select the advocate's name
		$builder->select('b.name');

		// Apply the conditions
		$builder->where('a.display', 'Y');
		$builder->where('diary_no', $diary_no);
		$builder->where('pet_res', 'P');
		$builder->where('adv_type', 'M');
		$builder->where('pet_res_no', 1);

		// Execute the query
		$query = $builder->get();
		// Check if any results were found
		if ($query->getNumRows() > 0) {
			return $query->getResultArray(); // Return the fetched data as an array
		} else {
			log_message('error', 'No records found for diary number: ' . $diary_no);
			return false; // Handle the case where no records are found
		}
	}




}

?>
