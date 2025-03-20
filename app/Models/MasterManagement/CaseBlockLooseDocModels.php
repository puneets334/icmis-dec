<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class CaseBlockLooseDocModels extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    function getdetail()
    {
        $sql = "SELECT a.id,a.diary_no,
                         reason_blk, 
                         section_name,
                         pet_name,
                         res_name,
                         a.ent_dt,
                         b.name username 
                    FROM loose_block a 
                    LEFT JOIN master.users b ON a.usercode=b.usercode 
                    LEFT JOIN master.usersection c ON b.section=c.id 
                    LEFT JOIN main m ON a.diary_no=m.diary_no 
                    WHERE a.display='Y' 
                    ORDER BY a.ent_dt";
                    
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }



    public function checkRecordExists($dno)
    {

        $builder = $this->db->table('loose_block');
        $builder->where('diary_no', $dno);
        $builder->where('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return true;  
        } else {
            return false;  
        }
    }
    


          public function getLooseBlockDetails()
          {
            $sql =  "SELECT a.id,a.diary_no, reason_blk, section_name,pet_name,res_name,a.ent_dt 
                     FROM loose_block a 
                     LEFT JOIN master.users b ON a.usercode=b.usercode 
                     LEFT JOIN master.usersection c ON b.section=c.id 
                     LEFT JOIN main m ON a.diary_no=m.diary_no 
                     WHERE a.display='Y' 
                     ORDER BY a.ent_dt;";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
                     
          }




          function Judges_Report_Mod($judges = null, $is_retired = null)
          {

              $builder = $this->db->table('master.judge');
              $builder->select("*");
              if ($is_retired === 'Y' || $is_retired === 'N') {
                  $builder->where('is_retired', $is_retired);
              }
          
              if ($judges === 'R' || $judges === 'J') {
                  $builder->where('jtype', $judges);
              }
              $query = $builder->get();
              if ($query->getNumRows() >= 1) {
                  return $query->getResult(); 
              } else {
                  return false;
              }
          }
          

          function select_user()
          {
              $sql="select usercode, empid, name from master.users";
              $query=$this->db->query($sql);
            if ($query->getNumRows() > 0) {
                return $query->getResultArray(); 
            }else {
                return false;
            }
          }


          function Menu_Details($user_code=null)
          {
               $sql="select u.name,u.empid,u.*, mn.id as main_menu_id, mn.menu_nm, smp.sub_me_per,ssm.su_su_menu_id, sm.sub_mn_nm, ssm.sub_sub_mn_nm
                FROM master.mn_me_per mp
                inner join master.menu mn ON mn.id = mp.mn_me_per and mp.display = 'Y'
                inner join master.sub_me_per smp on mp.mn_me_per=smp.mn_me_per and mp.us_code=smp.sub_us_code and (mp.display='Y' and smp.display='Y')
                inner join master.sub_sub_me_per ssmp on smp.id=ssmp.sub_me_per_id and smp.sub_us_code=ssmp.sub_sub_us_code and (mp.display='Y' and smp.display='Y' and ssmp.display='Y')
                inner join master.submenu sm on smp.sub_me_per=sm.su_menu_id
                inner join master.sub_sub_menu ssm on ssmp.sub_sub_menu=ssm.su_su_menu_id
                left join master.users u on us_code=u.usercode
                WHERE
                us_code = $user_code and mn.display = 'Y'and sm.display='Y' and ssm.display='Y'
                order by mn.priority";

               
                
              $query=$this->db->query($sql);
              if ($query->getNumRows() > 0) {
                return $query->getResultArray(); 
            }else {
                return false;
            }
          }



    function Menu_remove($mn_me_per=null, $emp_rem=null)
       {
        $sql="UPDATE master.mn_me_per
                SET display='N'
                WHERE mn_me_per=$mn_me_per and us_code=$emp_rem";

        $query=$this->db->query($sql);
        
        $sql=" UPDATE master.sub_me_per
                SET display='N'
                WHERE sub_us_code=$emp_rem and mn_me_per in 
                (select mn_me_per from master.mn_me_per where mn_me_per=$mn_me_per)";

        $query=$this->db->query($sql);

        $sql="  UPDATE master.sub_sub_me_per
				SET display='N'
                WHERE sub_sub_us_code=$emp_rem and sub_me_per_id in 
                (Select id from master.sub_me_per where mn_me_per in 
                (select mn_me_per from master.mn_me_per where mn_me_per=$mn_me_per))";

        $query=$this->db->query($sql);

        $this->menu_log($mn_me_per,null,null,$emp_rem,1);
    }

    function Sub_menu_remove($mn_me_per=null,$sub=null, $emp_rem=null)
    {
        $sql="UPDATE master.sub_sub_me_per SET display='N'
      WHERE sub_sub_us_code=$emp_rem and sub_me_per_id in 
      (Select id from master.sub_me_per where sub_me_per=$sub)";
        $query=$this->db->query($sql);

        $sql="UPDATE master.sub_me_per SET display='N'
        WHERE sub_me_per=$sub and sub_us_code=$emp_rem";
        $query=$this->db->query($sql);

        $this->menu_log($mn_me_per,$sub,null,$emp_rem,1);
    }

    function Sub_sub_menu_remove($mn_me_per=null,$sub=null,$sub_sub_menu=null, $emp_rem=null)
    {
        $sql="UPDATE master.sub_sub_me_per
                  SET display='N'
                  WHERE sub_sub_menu=$sub_sub_menu and sub_sub_us_code=$emp_rem;";

        $query=$this->db->query($sql);

        $this->menu_log($mn_me_per,$sub,$sub_sub_menu,$emp_rem,1);
    }

        


    function menu_log($mn_me_per=null,$sub=null,$sub_sub_menu=null,$emp_rem=null,$updateflag=null)
    {
        

        $updatedBy=1;
        $operationText='';
        $updatedFromSystem=$_SERVER['REMOTE_ADDR'];
        if($sub==null && $sub_sub_menu==null)
        {
            $operationText='TableName:mn_me_per ';
        }
        else if($mn_me_per==null && $sub_sub_menu==null)
        {
            $operationText='TableName:sub_me_per ';
        }
        else
        {
            $operationText='TableName:sub_sub_me_per ';
        }

        if($updateflag==1)
        {
            $operationText.='operation:REMOVE';
        }
        else
        {
            $operationText.='operation:ADD';
        }

        if($mn_me_per !=null && $sub==null && $sub_sub_menu==null) {
            // for deleting main menu
            $sql = "insert into menu_log(usercode, menu_id, sub_menu_id, sub_sub_menu_id, operation, updated_on, updated_by, updated_by_ip) values('$emp_rem','$mn_me_per','$sub','$sub_sub_menu','$operationText',NOW(), $updatedBy, '$updatedFromSystem')";
        } else if($mn_me_per ==null && $sub!=null && $sub_sub_menu==null)
        {
            // for deleting sub_menu
            $sql = "insert into menu_log(usercode, menu_id, sub_menu_id, sub_sub_menu_id, operation, updated_on, updated_by, updated_by_ip) values('$emp_rem',(select id from submenu where su_menu_id=$sub),'$sub','$sub_sub_menu','$operationText',NOW(), $updatedBy, '$updatedFromSystem')";

        }
        else {
            //for deleting sub_sub_menu
            $sql = "insert into menu_log(usercode, menu_id, sub_menu_id, sub_sub_menu_id, operation, updated_on, updated_by, updated_by_ip) values('$emp_rem',(select id from submenu where su_menu_id in (select su_menu_id from sub_sub_menu where su_su_menu_id=$sub_sub_menu)),(select su_menu_id from sub_sub_menu where su_su_menu_id=$sub_sub_menu),'$sub_sub_menu','$operationText',NOW(), $updatedBy, '$updatedFromSystem')";
        }

        $query = $this->db->query($sql);

    }

  }
  
