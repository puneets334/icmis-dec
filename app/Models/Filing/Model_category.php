<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class Model_category extends Model
{
    public function get_main_category_list()
    {
        $builder = $this->db->table("master.submaster");

        $builder->select('subcode1,sub_name1,category_sc_old');
        $builder->where('display', 'Y');
        $builder->where('match_id!=', '0');
        $builder->where('flag', 's');
        $builder->groupBy('subcode1,sub_name1,category_sc_old');
        $builder->orderBy('subcode1,category_sc_old');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    public function get_mul_category($diary_number)
    {
        $builder = $this->db->table("mul_category as a");
        $builder->select('submaster_id ,category_sc_old,sub_name1, sub_name4 , subcode1');
        $builder->join('master.submaster b', 'a.submaster_id=b.id', 'left');
        $builder->where('diary_no', $diary_number);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->groupStart()
            ->where('b.is_old IS NULL', null, false)
            ->orWhere('b.is_old', 'Y')
            ->groupEnd();
      
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
            //  echo '<pre>';print_r($result);exit();
        } else {
            return false;
        }
    }

    public function get_reason_sensitive_case($diary_number)
    {
        $builder = $this->db->table("sensitive_cases");
        $builder->select('reason');
        $builder->where('diary_no', $diary_number);
        $builder->where('display', 'Y');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
            //  echo '<pre>';print_r($result);exit();
        } else {
            return false;
        }
    }

    public function get_diary_keywords($diary_number)
    {
        $builder = $this->db->table("ec_keyword as a");
        //$builder->select('keyword_id ,keyword_description');
        $builder->select('keyword_id,keyword_description');
        $builder->join('master.ref_keyword b', 'a.keyword_id=b.id', 'left');
        $builder->where('diary_no', $diary_number);
        $builder->where('display', 'Y');
        $builder->where('is_deleted', 'f');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
            //  echo '<pre>';print_r($result);exit();
        } else {
            return false;
        }
    }
    public function updateMulCategory($diary_number, $user_id)
    {
        $builder = $this->db->table("mul_category");
        $builder->set('updated_on', date('Y-m-d H:i:s'));
        $builder->set('updated_by', $user_id);
        $builder->set('display', 'N');
        $builder->set('updated_by_ip', $_SERVER['REMOTE_ADDR']);
        $builder->WHERE('diary_no', $diary_number);
        // $builder->update();
        if ($builder->update()) {
            return true;
        } else {
            return false;
        }
    }

    public function insertMulCategory($insert_nul_categoryarray)
    {
        $builder = $this->db->table("mul_category");
        if ($builder->insert($insert_nul_categoryarray)) {
            return true;
        } else {
            return false;
        }
    }

    public function getMasterCategoryId($subcode)
    {
        $builder = $this->db->table("master.submaster");
        $builder->select('id');
        $builder->where('subcode1', $subcode);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
            //  echo '<pre>';print_r($result);exit();
        } else {
            return false;
        }
    }

    public function updateDiaryBasicDetails($form_data, $diary_number, $user_id)
    {
        $filing_details = session()->get('filing_details');

        $brief_description = $form_data['brief_description'];
        $claim_amt = $form_data['claim_amt'];
        $relief = $form_data['relief'];
        $fixed_for = intval($form_data['fixed_for']) > 0 ? $form_data['fixed_for'] : 0;
        $bench = intval($form_data['bench']) > 0 ? $form_data['bench'] : NULL;
        $actcode = intval($form_data['actcode']) > 0 ? $form_data['actcode'] : NULL;
        $sensitive_case_reason = $form_data['sensitive_case_reason'];

        $builder = $this->db->table("main");
        $builder->set('last_dt', date('Y-m-d H:i:s'));
        $builder->set('fixed', $fixed_for);
        $builder->set('bench', $bench);
        $builder->set('claim_amt', $claim_amt);
        $builder->set('relief', $relief);
        $builder->set('last_usercode', $user_id);
        $builder->set('actcode', $actcode);
        $builder->set('brief_description', $brief_description);
        $builder->set('scr_user', $user_id);
        $builder->set('scr_time',  date('Y-m-d H:i:s'));
        $builder->set('updated_on', date('Y-m-d H:i:s'));
        $builder->set('updated_by', $user_id);
        $builder->set('updated_by_ip', getClientIP());

        if (!empty($form_data['court_fee'])) {
            $builder->set('court_fee', $form_data['court_fee']);
        }
        if (!empty($form_data['total_court_fee'])) {

            $builder->set('total_court_fee', $form_data['total_court_fee']);
        }
        if (!empty($form_data['valuation'])) {
            $builder->set('valuation', $form_data['valuation']);
        }

        /* Pending bailno  Court fee and total fee pending */

        // $builder->set('bailno', 'N');


        $builder->WHERE('diary_no', $diary_number);

        //  $builder->update();
         //echo $builder->getCompiledUpdate();
          //exit;
        if ($builder->update()) {
            $filing_details['fixed'] = $fixed_for;
            $filing_details['bench'] = $bench;
            $filing_details['claim_amt'] = $claim_amt;
            $filing_details['relief'] = $relief;
            $filing_details['actcode'] = $actcode;
            $filing_details['brief_description'] = $brief_description;
            session()->set('filing_details', $filing_details);
            //$query = $builder->get();
            //exit;
            //  $result = $query->getResultArray();
            return true;
        } else {
            return false;
        }
    }

    public function deleteKeywords($diary_number)
    {
        $builder = $this->db->table("ec_keyword");
        $builder->where('diary_no', $diary_number);
        $builder->delete();
    }

    public function checkforSensitiveCase($diary_number)
    {
        $builder = $this->db->table("sensitive_cases");
        $builder->select('count(*)');
        $builder->where('diary_no', $diary_number);
        $builder->where('display', 'Y');
        $query = $builder->get();
        //echo ">>".print_r($query->getResultArray());
        // echo $this->db->getLastQuery();
        // exit;
        if ($query->getNumRows() >= 1) {
            $count = $query->getResultArray();
            return $count;
        } else {
            // insert keyword
            return 0;
        }
    }

    public function updateKeyword($keywordarray)
    {
        $builder = $this->db->table("ec_keyword");
        if ($builder->insert($keywordarray)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSenstiveCase($if_sensitive, $sensitive_case_reason, $diary_number, $user_id, $sensitiveCaseCount)
    {
        $builder = $this->db->table("sensitive_cases");
        $updatedFromSystem = $_SERVER['REMOTE_ADDR'];

        if ($if_sensitive == "1") {
            if ($sensitiveCaseCount >= 1) {
                $builder->set('updated_on', date('Y-m-d H:i:s'));
                $builder->set('display', 'Y');
                $builder->set('updated_by', $user_id);
                $builder->set('updated_by_ip', $updatedFromSystem);
                $builder->set('reason', $sensitive_case_reason);
                $builder->WHERE('diary_no', $diary_number);
                $builder->WHERE('display', 'Y');

                // $builder->update();
                if ($builder->update()) {
                    //$query = $builder->get();
                    //echo $this->db->getLastQuery();
                    //exit;
                    //  $result = $query->getResultArray();

                    return true;
                } else {
                    return false;
                }
            } else {
                $insert_sensitivecasearray = [
                    'reason' => $sensitive_case_reason,
                    'diary_no' => $diary_number,
                    'updated_by' => $user_id,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by_ip' => $updatedFromSystem
                ];
                if ($builder->insert($insert_sensitivecasearray)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            $builder->set('updated_on', date('Y-m-d H:i:s'));
            $builder->set('display', 'N');
            $builder->set('updated_by', $user_id);
            $builder->set('updated_by_ip', $updatedFromSystem);

            $builder->WHERE('diary_no', $diary_number);
            $builder->WHERE('display', 'Y');

            // $builder->update();
            if ($builder->update()) {
                //$query = $builder->get();
                //echo $this->db->getLastQuery();
                //exit;
                //  $result = $query->getResultArray();

                return true;
            } else {
                return false;
            }

            // update 'N';
        }
    }

    public function deleteActs($diary_number)
    {
        $builder = $this->db->table("act_main");
        $builder->where('diary_no', $diary_number);
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteSection($diary_number)
    {
        $builder = $this->db->tdiary_noable("master.act_section a");
        $builder->join('act_main b', 'a.act_id=b.id');
        $builder->where('b.diary_no', $diary_number);
        $builder->delete();
        // echo $this->db->getLastQuery();
        // exit;
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function checkforAct($diary_number, $act_id)
    {
        $builder = $this->db->table("act_main");
        $builder->select('*');
        $builder->where('diary_no', $diary_number);
        $builder->where('act', $act_id);
        $builder->where('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $count = $query->getResultArray();
            return $count;
        } else {
            // insert keyword
            return 0;
        }
    }

    public function insertActMain($act_main)
    {
        $builder = $this->db->table("act_main");
        if ($builder->insert($act_main)) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }


    public function insertActSection($section_array)
    {
        $builder = $this->db->table("master.act_section");
        if ($builder->insert($section_array)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAct($diary_number, $user_id, $act_id)
    {
        $builder = $this->db->table("act_main");
        $builder->set('updated_on', date('Y-m-d H:i:s'));
        $builder->set('updated_by', $user_id);
        $builder->set('display', 'Y');
        $builder->set('updated_by_ip', $_SERVER['REMOTE_ADDR']);
        $builder->WHERE('diary_no', $diary_number);
        $builder->WHERE('act', $act_id);
        // $builder->update();
        if ($builder->update()) {
            return true;
        } else {
            return false;
        }
    }

    public function getActsSections($diary_number)
    {
        $builder = $this->db->table("master.act_section as a");
        $builder->select('act, section,b.id');
        $builder->join('act_main b', 'a.act_id=b.id');
        $builder->where('diary_no', $diary_number);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $query = $builder->get();
        //echo ">>".print_r($query->getResultArray());
        // echo $this->db->getLastQuery();
        // exit;
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
}
