<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class SimilarityModel extends Model
{
    public function getLowerCtDetails($diary_no, $table_prefix)
    {

        $builder = $this->db->table('lowerct' . $table_prefix . ' as l');

        $builder->select('l.*, r.relied_court, r.relied_case_type, r.relied_case_no, r.relied_case_year, r.relied_state, r.relied_district, t.transfer_court, t.transfer_case_type, t.transfer_case_no, t.transfer_case_year, t.transfer_state, t.transfer_district')
            ->join('relied_details as r', 'l.lower_court_id = r.lowerct_id', 'left')
            ->join('transfer_to_details as t', 'l.lower_court_id = t.lowerct_id', 'left')
            ->where('diary_no', $diary_no);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }


    public function getSimilarityForTransferDetails($court, $state, $district, $case_no, $case_year, $table_prefix)
    {

        $builder = $this->db->table('lowerct' . $table_prefix . ' as b');

        $builder->distinct()
            ->select("b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.diary_no as c_diary, b.ct_code, name, 
        CASE WHEN b.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y') 
        ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = 'f') END AS agency_name, 
        CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype) 
        ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y') END AS type_sname, 
        SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, b.is_order_challenged, b.full_interim_flag, short_description, court_name, 
        aa.transfer_court, aa.transfer_case_type, aa.transfer_case_no, aa.transfer_case_year, aa.transfer_state, aa.transfer_district, c_status")
            ->join('transfer_to_details aa', "b.lower_court_id = aa.lowerct_id AND aa.display = 'Y'", 'left')
            ->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main' . $table_prefix . ' d', 'd.diary_no = b.diary_no', 'left')
            ->join('master.casetype e', "((e.casecode::text) =TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2))))  AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = b.ct_code AND f.display = 'Y'", 'left')
            ->where('ct_code', $court)
            ->where('lct_caseno', $case_no)
            ->where('lct_caseyear', $case_year)
            ->where('l_state', $state)
            ->where('l_dist', $district)
            ->where('lw_display', 'Y')
            ->orderBy('b.diary_no');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                    $dataSet[$key]['transfer_court'] = $row['transfer_court'];
                    $dataSet[$key]['transfer_case_type'] = $row['transfer_case_type'];
                    $dataSet[$key]['transfer_case_no'] = $row['transfer_case_no'];
                    $dataSet[$key]['transfer_case_year'] = $row['transfer_case_year'];
                    $dataSet[$key]['transfer_state'] = $row['transfer_state'];
                    $dataSet[$key]['transfer_district'] = $row['transfer_district'];
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }

    public function getSimilarityForReliedDetails($court, $state, $district, $case_no, $case_year, $table_prefix)
    {

        $builder = $this->db->table('lowerct' . $table_prefix . ' as b');

        $builder->distinct()
            ->select("b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.diary_no as c_diary, b.ct_code, name, 
        CASE WHEN b.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y') 
        ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = 'f') END AS agency_name, 
        CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype) 
        ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y') END AS type_sname, 
        SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, b.is_order_challenged, b.full_interim_flag, short_description, court_name, 
        aa.relied_court, aa.relied_case_type, aa.relied_case_no, aa.relied_case_year, aa.relied_state, aa.relied_district, c_status")
            ->join('relied_details aa', "b.lower_court_id = aa.lowerct_id AND aa.display = 'Y'", 'left')
            ->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main' . $table_prefix . ' d', 'd.diary_no = b.diary_no', 'left')
            ->join('master.casetype e', "((e.casecode::text) = TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2)))) AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = b.ct_code AND f.display = 'Y'", 'left')
            ->where('ct_code', $court)
            ->where('lct_caseno', $case_no)
            ->where('lct_caseyear', $case_year)
            ->where('l_state', $state)
            ->where('l_dist', $district)
            ->where('lw_display', 'Y')
        ->orderBy('b.diary_no');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                    $dataSet[$key]['relied_court'] = $row['relied_court'];
                    $dataSet[$key]['relied_case_type'] = $row['relied_case_type'];
                    $dataSet[$key]['relied_case_no'] = $row['relied_case_no'];
                    $dataSet[$key]['relied_case_year'] = $row['relied_case_year'];
                    $dataSet[$key]['relied_state'] = $row['relied_state'];
                    $dataSet[$key]['relied_district'] = $row['relied_district'];
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }

    public function getStateBenchSimilarity($lowercourt_id, $table_prefix = null)
    {
        $builder = $this->db->table('lowerct' . $table_prefix . ' as a');

        if (strstr($lowercourt_id, ",")) {
            $allids = explode(",", $lowercourt_id);
        } else {
            $allids = array($lowercourt_id);
        }
        $builder->distinct()
            ->select("a.lct_dec_dt, a.l_dist, a.l_state, a.lct_casetype, a.lct_caseno, a.lct_caseyear, a.diary_no AS c_diary, a.ct_code, name, 
            CASE WHEN a.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') 
            ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END AS agency_name, 
            CASE WHEN a.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) 
            ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END AS type_sname, 
            SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, a.is_order_challenged, a.full_interim_flag, short_description, court_name, 
            c_status, case_status_id")
            ->join('master.state c', "a.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main' . $table_prefix . ' d', "d.diary_no = a.diary_no", 'left')
            ->join('master.casetype e', "((e.casecode::text) = TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2)))) AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = a.ct_code AND f.display = 'Y'", 'left')
            ->whereIn('lower_court_id', $allids)
            ->orderBy('a.diary_no');
        $query = $builder->get();
       // $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['case_status_id'] = $row['case_status_id'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }

    public function getReferenceSimilarity($lowercourt_ids, $table_prefix = null)
    {
        $builder = $this->db->table('lowerct' . $table_prefix . ' as a');

        if (strstr($lowercourt_ids, ",")) {
            $allids = explode(",", $lowercourt_ids);
        } else {
            $allids = array($lowercourt_ids);
        }
        $builder->distinct()
            ->select("a.lct_dec_dt, a.l_dist, a.l_state, a.lct_casetype, a.lct_caseno, a.lct_caseyear, a.diary_no as c_diary, a.ct_code, name, 
            CASE WHEN a.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') 
            ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END AS agency_name, 
            CASE WHEN a.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) 
            ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END AS type_sname, 
            SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, a.is_order_challenged, a.full_interim_flag, short_description, 
            court_name, a.ref_court, a.ref_case_type, a.ref_case_no, a.ref_case_year, a.ref_state, a.ref_district, c_status")
            ->join('master.state c', "a.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main d', "d.diary_no = a.diary_no", 'left')
            ->join('master.casetype e', "((e.casecode::text) = TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2))))  AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = a.ct_code AND f.display = 'Y'", 'left')
            ->whereIn('lower_court_id', $allids)
        ->orderBy('a.diary_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();

            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }


    public function getGovtNotificationSimilarity($lowercourt_ids, $table_prefix)
    {
        $builder = $this->db->table('lowerct' . $table_prefix . ' as b');
        if (strstr($lowercourt_ids, ",")) {
            $allids = explode(",", $lowercourt_ids);
        } else {
            $allids = array($lowercourt_ids);
        }

        $builder->distinct()
            ->select("b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.diary_no as c_diary, b.ct_code, c.name as name, 
        CASE WHEN b.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y') 
        ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = 'f') END AS agency_name, 
        CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype) 
        ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y') END AS type_sname, 
        SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, b.is_order_challenged, b.full_interim_flag, short_description, court_name, 
        b.gov_not_state_id, b.gov_not_case_type, b.gov_not_case_no, b.gov_not_case_year, b.gov_not_date, c_status, g.name as govt_state_name")
            ->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('master.state g', "b.gov_not_state_id = g.id_no AND g.display = 'Y'", 'left')
            ->join('main' . $table_prefix . ' d', "d.diary_no = b.diary_no", 'left')
            ->join('master.casetype e', "((e.casecode::text) =TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2))))  AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = b.ct_code AND f.display = 'Y'", 'left')
            ->whereIn('lower_court_id', $allids)
            ->where('b.lw_display', 'Y')
            ->orderBy('b.diary_no');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['govt_state_name'] = $row['govt_state_name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                    $dataSet[$key]['gov_not_state_id'] = $row['gov_not_state_id'];
                    $dataSet[$key]['gov_not_case_type'] = $row['gov_not_case_type'];
                    $dataSet[$key]['gov_not_case_no'] = $row['gov_not_case_no'];
                    $dataSet[$key]['gov_not_case_year'] = $row['gov_not_case_year'];
                    $dataSet[$key]['gov_not_date'] = $row['gov_not_date'];
                }
            }
            return $dataSet;
        } else {
            return false;
        }
    }

    public function getCheckConditionsForCase($diary_no, $table_prefix)
    {
        $builder1 = $this->db->table('conct' . $table_prefix);

        $builder1->select('(conn_key::varchar)');
        $builder1->select('conn_type, (linked_to::varchar)')
            ->where('diary_no', $diary_no);

        $builder1a = $this->db->table('main' . $table_prefix);

        $builder1a->select('conn_key, null as conn_type, null as linked_to')
            ->where('diary_no', $diary_no);

        $unionQuery1 = $builder1->unionAll($builder1a);
        $query = $unionQuery1->get();

        $result = $query->getResultArray();

        if (!empty($result)) {
            $res_connect_case = $result[0]['conn_key'];
            $res_conn_type =  $result[0]['conn_type'];
            $res_linked_to =  $result[0]['linked_to'];
        } else {
            $res_connect_case = $res_conn_type = $res_linked_to = "";
        }

        $builder2 = $this->db->table('conct' . $table_prefix);
        $builder2->select('(linked_to::varchar)')
            ->where('diary_no', $diary_no);
        $builder2a = $this->db->table('main' . $table_prefix);

        $builder2a->select('conn_key as linked_to')
            ->where('diary_no', $diary_no);

        $unionQuery = $builder2->union($builder2a);
        $query2 = $unionQuery->get();

        $result1 = $query2->getResultArray();

       
        if (!empty($result1)) {
            $res_linked =  $result1[0]['linked_to'];
        } else {
            $res_linked = "";
        }


        $builder3 = $this->db->table('heardt' . $table_prefix);

        $builder3->selectCount('diary_no', 'total')
            ->where('diary_no', $diary_no)
            ->groupStart()
            ->where('main_supp_flag', 1)
            ->orWhere('main_supp_flag', 2)
            ->groupEnd()
            ->where('next_dt >=', date('Y-m-d'));
        $query3 = $builder3->get();
        $result2 = $query3->getResultArray();

        if (!empty($result2)) {
            $res_listed1 =  $result2[0]['total'];
        } else {
            $res_listed1 = "";
        }

        $data[$diary_no] = array('cn' => $res_connect_case, "rln" => $res_linked, "rls" => $res_listed1);

        return $data;
    }

    public function getLinkingReason($diary_no, $table_prefix)
    {
        $builder = $this->db->table('conct' . $table_prefix);
        $builder->select('linking_reason')
            ->where('diary_no', $diary_no);

        $query = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            $linking_reason =  $result[0]['linking_reason'];
        } else {
            $linking_reason = "";
        }
        return $linking_reason;
    }

    public function getCheckForMainDairyNumber($diary_no, $table_prefix)
    {

        $builder = $this->db->table('conct' . $table_prefix);
        $builder->distinct()
            ->select('conn_key')
            ->where('diary_no', $diary_no);
        // ->orWhere('linked_to', $diary_no)
        // ->orWhere(')');

        $query = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            $conn_key = $result[0]['conn_key'];
        } else {
            $conn_key = "";
        }
        return $conn_key;
    }


    public function getDiaryHearingDt($diary_no, $table_prefix)
    {
        $builder = $this->db->table('heardt' . $table_prefix);

        $builder->selectCount('diary_no', 'total')
            ->where('diary_no', $diary_no)
            ->groupStart()
            ->where('main_supp_flag', 1)
            ->orWhere('main_supp_flag', 2)
            ->groupEnd()
            ->where('next_dt >=', date('Y-m-d'));
        $query = $builder->get();
        $result = $query->getResultArray();

        if (!empty($result)) {
            $res_listed =  $result[0]['total'];
        } else {
            $res_listed = "";
        }
        return $res_listed;
    }

    public function getPoliceStationSimilarity($lowercourt_ids, $table_prefix)
    {
        if (strstr($lowercourt_ids, ",")) {
            $allids = explode(",", $lowercourt_ids);
        } else {
            $allids = array($lowercourt_ids);
        }

        $builder = $this->db->table('lowerct' . $table_prefix . ' as a');
        $builder->distinct()
            ->select("a.lct_dec_dt, a.l_dist, a.l_state, a.lct_casetype, a.lct_caseno, a.lct_caseyear, a.diary_no as c_diary, a.ct_code, name, 
            CASE WHEN a.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') 
            ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END AS agency_name, 
            CASE WHEN a.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) 
            ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END AS type_sname, 
            SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, a.is_order_challenged, a.full_interim_flag, short_description, 
            a.polstncode, a.crimeno, a.crimeyear, policestndesc, court_name, c_status")
            ->join('master.state c', "a.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main_a d', "d.diary_no = a.diary_no", 'left')
            ->join('master.casetype e', "((e.casecode::text) = TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2))))  AND e.display = 'Y'", 'left')
            ->join('master.police p', "p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist", 'left')
            ->join('master.m_from_court f', "f.id = a.ct_code AND f.display = 'Y'", 'left')
            ->whereIn('lower_court_id', $allids)
            ->where('a.lw_display', 'Y')
        ->orderBy('a.diary_no');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();

            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                    $dataSet[$key]['polstncode'] = $row['polstncode'];
                    $dataSet[$key]['crimeno'] = $row['crimeno'];
                    $dataSet[$key]['crimeyear'] = $row['crimeyear'];
                    $dataSet[$key]['policestndesc'] = $row['policestndesc'];
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }

    public function getVehicleSimilarity($lowercourt_ids, $table_prefix)
    {

        if (strstr($lowercourt_ids, ",")) {
            $allids = explode(",", $lowercourt_ids);
        } else {
            $allids = array($lowercourt_ids);
        }

        $builder = $this->db->table('lowerct' . $table_prefix . ' as b');
        $builder->distinct()
            ->select("b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.diary_no as c_diary, b.ct_code, name, 
            CASE WHEN b.ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y') 
            ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = 'f') END AS agency_name, 
            CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype) 
            ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y') END AS type_sname, 
            SUBSTRING(fil_no FROM 4) AS fil_no, EXTRACT(YEAR FROM fil_dt) AS fil_dt, b.is_order_challenged, b.full_interim_flag, short_description, 
            court_name, b.vehicle_no, b.vehicle_code, g.code, c_status")
            ->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left')
            ->join('main_a d', "d.diary_no = b.diary_no", 'left')
            ->join('master.casetype e', "((e.casecode::text) = TRIM (LEADING '0' FROM(SUBSTRING(d.fil_no FROM 1 FOR 2))))  AND e.display = 'Y'", 'left')
            ->join('master.m_from_court f', "f.id = b.ct_code AND f.display = 'Y'", 'left')
            ->join('master.rto g', "g.id = b.vehicle_code AND g.display = 'Y'", 'left')
            ->whereIn('lower_court_id', $allids)
            ->where('b.lw_display', 'Y')
            ->orderBy('b.diary_no');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();

            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['lct_dec_dt'] = $row['lct_dec_dt'];
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['l_dist'] = $row['l_dist'];
                    $dataSet[$key]['l_state'] = $row['l_state'];
                    $dataSet[$key]['lct_casetype'] = $row['lct_casetype'];
                    $dataSet[$key]['lct_caseno'] = $row['lct_caseno'];
                    $dataSet[$key]['lct_caseyear'] = $row['lct_caseyear'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['ct_code'] = $row['ct_code'];
                    $dataSet[$key]['name'] = $row['name'];
                    $dataSet[$key]['agency_name'] = $row['agency_name'];
                    $dataSet[$key]['type_sname'] = $row['type_sname'];
                    $dataSet[$key]['fil_no'] = $row['fil_no'];
                    $dataSet[$key]['fil_dt'] = $row['fil_dt'];
                    $dataSet[$key]['is_order_challenged'] = $row['is_order_challenged'];
                    $dataSet[$key]['full_interim_flag'] = $row['full_interim_flag'];
                    $dataSet[$key]['short_description'] = $row['short_description'];
                    $dataSet[$key]['court_name'] = $row['court_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['linking_reson'] = $this->getLinkingReason($row['c_diary'], $table_prefix);
                    $dataSet[$key]['vehicle_no'] = $row['vehicle_no'];
                    $dataSet[$key]['vehicle_code'] = $row['vehicle_code'];
                    $dataSet[$key]['code'] = $row['code'];
                }
            }

            return $dataSet;
        } else {
            return false;
        }
    }

    public function getCauseTitleSimilary($diary_no, $pet_name, $res_name, $table_prefix)
    {

        $builder = $this->db->table('main'.$table_prefix.' as m');
        $builder->distinct();
        $builder->select('m.diary_no as c_diary, m.pet_name, m.res_name, m.c_status');
        $builder->where('m.diary_no !=', $diary_no);
        $builder->groupStart();
        $builder->like('m.pet_name',$pet_name);
        $builder->like('m.res_name', $res_name);
        $builder->groupEnd();
        $builder->orGroupStart();
        $builder->like('m.res_name',$pet_name);
        $builder->like('m.pet_name', $res_name);
        $builder->groupEnd();

        $query = $builder->get();
        
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $dataSet = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $data = $this->getCheckConditionsForCase($row['c_diary'], $table_prefix);
                    $dataSet[$key]['res_connect_case'] = $data[$row['c_diary']]['cn'];
                    $dataSet[$key]['c_diary'] = $row['c_diary'];
                    $dataSet[$key]['res_linked'] = $data[$row['c_diary']]['rln'];
                    $dataSet[$key]['res_listed1'] = $data[$row['c_diary']]['rls'];
                    $dataSet[$key]['pet_name'] = $row['pet_name'];
                    $dataSet[$key]['res_name'] = $row['res_name'];
                    $dataSet[$key]['c_status'] = $row['c_status'];
                }
            }
            return $dataSet;
        } else {
            return false;
        }
    }

    public function getStageOfDiary($diary_number)
    {
        $builder = $this->db->table("heardt a");
        $builder->select('stagename description');
        $builder->join('master.subheading b', 'a.subhead=b.stagecode');
        $builder->where('diary_no', $diary_number);
        $builder->where('display', 'Y');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result[0];
        } else {
            // insert keyword
            return 0;
        }
    }

    public function getDiaryConnection($diary_number, $if_main, $linked_diary_no)
    {
        $builder = $this->db->table("conct");
        $builder->select('count(conn_key)');

        if ($if_main == 'Y') {
            $builder->where('conn_key', $linked_diary_no);
            $builder->where('diary_no', $diary_number);
        } else {
            $builder ->where('conn_key', $linked_diary_no);
            $builder->where('diary_no', $diary_number);
        }
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result[0];
        } else {
            // insert keyword
            return 0;
        }
    }


    public function insertConct($conct_data)
    {
        $builder = $this->db->table("conct");
        if ($builder->insert($conct_data)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }

    function updateMainCaseDiaryNumber($hd_link, $dv_mn_case)
    {
        $builder = $this->db->table("main");
        $builder->set('conn_key', $dv_mn_case);

        $builder->set('updated_on', date('Y-m-d H:i:s'));
        $builder->set('updated_by', session()->get('login')['usercode']);
        $builder->set('updated_by_ip', getClientIP());

        $builder->WHERE('diary_no', $hd_link);
        $builder->WHERE("(conn_key is null or conn_key='')");

        //  $builder->update();
        // echo $this->db->getLastQuery();
        //  exit;
        if ($builder->update()) {
            //$query = $builder->get();
            //exit;
            //  $result = $query->getResultArray();
            return true;
        } else {
            return false;
        }
    }

    function updateCaseDiaryNumber($hd_link, $diary_no)
    {
        $builder = $this->db->table("main");
        $builder->set('conn_key', $hd_link);

        $builder->set('updated_on', date('Y-m-d H:i:s'));
        $builder->set('updated_by', session()->get('login')['usercode']);
        $builder->set('updated_by_ip', getClientIP());

        $builder->WHERE("(diary_no = '$diary_no')");
        $builder->WHERE("(conn_key is null or conn_key='')");

        //  $builder->update();
        // echo $this->db->getLastQuery();
        //  exit;
        if ($builder->update()) {
            //$query = $builder->get();
            //exit;
            //  $result = $query->getResultArray();
            return true;
        } else {
            return false;
        }
    }

    public function insertLinkedCase($linked_cases)
    {
        $builder = $this->db->table("linked_cases");
        if ($builder->insert($linked_cases)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }
}
