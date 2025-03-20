<?php

namespace App\Models\BulkDismissal;

use CodeIgniter\Model;

class MonitoringTeam_Model extends Model
{

    public function getJudges()
    {
        $builder = $this->db->table('master.judge');
        return $builder->where(['display' => 'Y', 'is_retired' => 'N'])->get()->getResultArray();
    }

    public function insertData($table, $data)
    {
        $builder = $this->db->table($table);
        $result = $builder->insert($data);
    }

    public function getDisposals()
    {
        $builder = $this->db->table('master.disposal');
        return $builder->where('display', 'Y')->orderBy('dispcode')->get()->getResultArray();
    }

    public function checkAlreadyDisposed($diary_no)
    {
        if (!is_array($diary_no)) {
            $diary_no = explode(',', $diary_no);
        }
        $builder = $this->db->table('dispose');
        $builder->select("STRING_AGG(diary_no::text, ',') as existing, COUNT(*) as ttl")
            ->whereIn('diary_no', $diary_no);
        $query = $builder->get();

        return $query->getResultArray();
    }


    public function checkFutureDates($diary_no)
{
    if (!is_array($diary_no)) {
        $diary_no = explode(',', $diary_no);
    }

    $currentDate = date('Y-m-d');

    $builder = $this->db->table('heardt');
    $builder->select("STRING_AGG(diary_no::text, ',') as future_date, COUNT(*) as future_date_count")
            ->whereIn('diary_no', $diary_no)
            ->where('judges !=', '')
            ->where('judges !=', '0')
            ->where('clno >', 0)
            ->where('brd_slno >', 0)
            ->where('roster_id >', 0)
            ->groupStart()
                ->where('next_dt IS NOT NULL')
                ->where('next_dt !=', '0001-01-01')
                ->where('next_dt >', $currentDate)
            ->groupEnd();

    $result = $builder->get()->getResultArray();
    return $result;
}

    

    public function getCaseRemarksMultiple($dno)
    {
        $builder = $this->db->table('case_remarks_multiple');
        $result_remarks = $builder->select('r_head, cl_date, head_content')
            ->where('diary_no', $dno)
            ->orderBy('cl_date', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
        return $result_remarks;
    }

    public function updateDocdetails($data, $diaryNumbers)
    {
        if (!is_array($diaryNumbers)) {
            $diaryNumbers = explode(',', $diaryNumbers);
        }
        $builder = $this->db->table('docdetails');
        $result = $builder->set($data)
            ->whereIn('diary_no', $diaryNumbers)
            ->where('iastat', 'P')
            ->where('doccode', 8)
            ->where('display', 'Y')
            ->update();
        return $result;
    }

    public function updateMain($data, $diaryNumbers)
    {
        if (!is_array($diaryNumbers)) {
            $diaryNumbers = explode(',', $diaryNumbers);
        }
        $builder = $this->db->table('main');
        $result = $builder->set($data)
            ->whereIn('diary_no', $diaryNumbers)
            ->update();
        return $result;
    }

    public function updateRgoDefault($data, $diary_no)
    {
        if (!is_array($diary_no)) {
            $diary_no = explode(',', $diary_no);
        }
        $builder = $this->db->table('rgo_default');
        $result = $builder->set($data)
            ->whereIn('fil_no2', $diary_no)
            ->update();
        return $result;
    }

    public function insertCaseRemarksMultiple($diary_no, $dismissal_date, $disp_type, $jcode, $ucode)
    {
        if (!is_array($diary_no)) {
            $diary_no = explode(',', $diary_no);
        }
        $subQueryBuilder = $this->db->table('main');
        $subQueryBuilder->select('diary_no')
            ->whereIn('diary_no', $diary_no);

        $results = $subQueryBuilder->get()->getResultArray();

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'diary_no'    => $row['diary_no'],
                'cl_date'     => $dismissal_date,
                'r_head'      => $disp_type,
                'e_date'      => date('Y-m-d H:i:s'),
                'jcodes'      => $jcode,
                'head_content' => '',
                'mainhead'    => 'M',
                'clno'        => 21,
                'uid'         => $ucode,
                'status'      => 'D',
                'remove'      => 0,
            ];
        }

        if (!empty($data)) {
            $builder = $this->db->table('case_remarks_multiple');
            $result = $builder->insertBatch($data);
            return $result;
        }
    }

    public function insertDispose($diary_no, $mon, $year, $jcode, $h_date, $dismissal_date, $disp_type, $ucode, $rj_date)
    {
        if (!is_array($diary_no)) {
            $diary_no = explode(',', $diary_no);
        }
        $subQueryBuilder = $this->db->table('main');
        $subQueryBuilder->select('diary_no, bench')
            ->whereIn('diary_no', $diary_no);

        $results = $subQueryBuilder->get()->getResultArray();
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'diary_no'       => $row['diary_no'],
                'month'          => $mon,
                'year'           => $year,
                'dispjud'        => $jcode,
                'ord_dt'         => $h_date,
                'disp_dt'        => $dismissal_date,
                'disp_type'      => $disp_type,
                'bench'          => $row['bench'],
                'jud_id'         => $jcode,
                'ent_dt'         => date('Y-m-d H:i:s'),
                'camnt'          => 0,
                'usercode'       => $ucode,
                'crtstat'        => 'R',
                'jorder'         => '',
                'rj_dt'          => $rj_date,
                'disp_type_all'  => $disp_type,
            ];
        }
        if (!empty($data)) {
            $builder = $this->db->table('dispose');
            $result = $builder->insertBatch($data);
            return $result;
        }
    }
}
