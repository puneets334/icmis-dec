<?php

namespace App\Controllers\Scanning;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Scanning\ScaningModel;

class IndexBenchController extends BaseController
{
    protected $ScaningModel;
    protected $db;
    public function __construct()
    {
        $this->ScaningModel =  new ScaningModel();
        $this->db = \Config\Database::connect();
    }

    public function fetchAgencies()
    {

        $diary_no = '';
        $diary_no = $this->request->getVar('diary_number') . $this->request->getVar('diary_year');
        $ddl_court = $this->request->getVar('ddl_court');
        $ddl_st_agncy = $this->request->getVar('ddl_st_agncy');
        $db = \Config\Database::connect();
        if ($ddl_court == 3) {

            $builder = $db->table('master.state a')
                        ->distinct()
                        ->select('a.id_no AS id, a.name AS agency_name')->join('lowerct b', 'a.id_no = b.l_dist')
                        ->where('display', 'Y')->where('a.sub_dist_code', 0)->where('a.village_code', 0)->where('a.district_code !=', 0)
                        ->where('b.diary_no', $diary_no)->where('b.lw_display', 'Y')->where('b.ct_code', $ddl_court)
                        ->where("CAST(b.l_state AS integer)", $ddl_st_agncy) 
                        ->orderBy('a.name');                
            $query = $builder->get();
            $agencies = $query->getResultArray();
        } else {
            if ($ddl_court == '5') {
                $ddl_court = 2;
            }
            $builder = $db->table('master.ref_agency_code a')->distinct()
                            ->select('a.id, a.agency_name, a.short_agency_name')
                            ->join('lowerct b', 'b.ct_code = CAST(a.agency_or_court AS INTEGER) 
                                                AND a.cmis_state_id = b.l_state 
                                                AND a.id = b.l_dist')
                            ->where('a.is_deleted', 'f')
                            ->where('a.agency_or_court', $ddl_court)->where('a.cmis_state_id', $ddl_st_agncy)->where('b.diary_no', $diary_no)
                            ->where('b.lw_display', 'Y');

            $query = $builder->get();
            $agencies = $query->getResultArray();
                            
        }
        echo $builder->getCompiledSelect();
        // print_r($params);exit();
        $options = '<option value="">Select</option>';
        foreach ($agencies as $row) {
            if ($ddl_court == 3) {
                $options .= '<option value="' . $row['id'] . '">' . $row['agency_name'] . '</option>';
            } else {
                $options .= '<option value="' . $row['id'] . '">' . $row['agency_name'] . '::' . $row['short_agency_name'] . '</option>';
            }
        }
        return $this->response->setBody($options);
    }
    public function getTotalCases()
    {
        $db = \Config\Database::connect();
        $diary_number = $this->request->getPost('diary_number');
        $diary_year = $this->request->getPost('diary_year');
        $ddl_court = $this->request->getPost('ddl_court');
        $ddl_st_agncy = $this->request->getPost('ddl_st_agncy');
        $ddl_bench = $this->request->getPost('ddl_bench');
        $dairy_no = $diary_number . $diary_year;
        $sql = "SELECT lct_casetype, lct_caseno, lct_caseyear, lower_court_id, ct_code FROM public.lowerct 
        WHERE ct_code = ? AND l_state = ? AND l_dist = ?  AND diary_no = ? AND lw_display = 'Y'";
        $query = $db->query($sql, [$ddl_court, $ddl_st_agncy, $ddl_bench, $dairy_no]);
        $totalCaseArray = $query->getResultArray();
        if (count($totalCaseArray) > 0) {
            echo '<option value="">Select</option>';
            foreach ($totalCaseArray as $row) {
                if ($row['ct_code'] == 4) {
                    $casetypeQuery = $this->db->query("SELECT skey FROM master.casetype WHERE display = 'Y' AND casecode = ?", [$row['lct_casetype']]);
                } else {
                    $casetypeQuery = $this->db->query("SELECT type_sname FROM master.lc_hc_casetype WHERE display = 'Y' AND lccasecode = ?", [$row['lct_casetype']]);
                }
                $casetype = $casetypeQuery->getRow();
                $sel_casetype = $casetype ? ($row['ct_code'] == 4 ? $casetype->skey : $casetype->type_sname) : '';
                echo '<option value="' . esc($row['lower_court_id']) . '">' . esc($sel_casetype) . '-' . intval($row['lct_caseno']) . '-' . esc($row['lct_caseyear']) . '</option>';
            }
        } else {
            echo '<option value="">No records found</option>';
        }
    }
}
