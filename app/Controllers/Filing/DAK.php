<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Entities\Model_main;
use App\Models\Entities\Model_main_a;
use App\Models\Entities\Model_sensitive_cases;

class DAK extends BaseController
{
   public $Model_sensitive_cases;
   public $Model_main;
   public $Model_main_a;
    function __construct()
    {
        $this->Model_sensitive_cases = new Model_sensitive_cases();
        $this->Model_main = new Model_main();
        $this->Model_main_a = new Model_main_a();
        ini_set('memory_limit','1024M');
    }

    public function index()
    {
       return view('Filing/DAK/search_doc');
    }
    
    public function get_search_doc()
    {
        if ($this->request->getMethod() === 'post') {
           $diary_number=$this->request->getPost('diary_number');
            $diary_year=$this->request->getPost('diary_year');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');
                    $data = [
                        'diary_number'=>$diary_number,
                        'diary_year'=>$diary_year,
                    ];

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();exit();
            }
            $data['search_result']=$data;
            $diary_no=$diary_number.$diary_year;
            
            $final_result1=$this->get_doc_details($diary_number,$diary_year);
            // print_r('Hi');die;
            $final_result2=$this->get_doc_details($diary_number,$diary_year,'_a');
            $final_result_merge=array_merge($final_result1,$final_result2);

            $data['result'] = $final_result_merge;
            //echo '<pre>';print_r($final_result);exit();
            $resul_view= view('Filing/DAK/search_doc_get_content',$data);
            echo '1@@@'.$resul_view;exit();

        }
        return view('Filing/DAK/search_doc');
        // exit();
    }


    public function get_doc_details($diary_number,$diary_year,$is_archival_table='')
    {
        // print_r('docdetails'.$is_archival_table);die;
        $query = $this->db->table("docdetails$is_archival_table a");
            $query->select('a.diary_no, a.doccode, a.doccode1, docnum, docyear, a.remark, other1, filedby, iastat, a.ent_dt, advocate_id, verified, docdesc, c.name AS advname, u.name AS entryuser, active_fil_no, active_reg_year, short_description');
            $query->join('master.docmaster b', "a.doccode = b.doccode AND a.doccode1 = b.doccode1 AND (b.display = 'Y' OR b.display = 'E')", 'left');
            $query->join('master.bar c', 'advocate_id = bar_id', 'left');
            $query->join('master.users u', 'a.usercode = u.usercode', 'left');
            $query->join("main$is_archival_table m", "CAST(a.diary_no AS bigint) = CAST(m.diary_no AS bigint)", 'left');

            // $query->join("main$is_archival_table m", 'a.diary_no = m.diary_no', 'left');
            $query->join('master.casetype ct', 'casecode = active_casetype_id', 'left');
            $query->where('CAST(docnum AS BIGINT)', $diary_number);
            $query->where('docyear', $diary_year);
            $query->whereIn('a.display', ['Y', 'E']);
            $query = $query->get();
       // $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
            return $result;

    }

}
