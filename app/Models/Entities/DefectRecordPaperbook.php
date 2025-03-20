<?php

namespace App\Models\Entities;

use CodeIgniter\Model;

class DefectRecordPaperbook extends Model
{
    protected $table = 'master.defect_record_paperbook';
    protected $primaryKey = 'id';
    protected $allowedFields = ['diary_no', 'section_id', 'court_fees', 'defect_notify_date', 'rack_no', 'shelf_no', 'display', 'ent_dt', 'ent_userid', 'upd_dt', 'upd_userid'];

    public function getMainRecord($diaryno, $module)
    {
        if ($module == 'add') {
            $check_diaryno = "select * from main where diary_no='$diaryno'";
            $check_diaryno = $this->db->query($check_diaryno);
            $check_diaryno_result = $check_diaryno->getRowArray();

            if (!empty($check_diaryno_result)) {

                $sql = "select * from  master.defect_record_paperbook where diary_no=$diaryno and display='Y'";
                $query = $this->db->query($sql);
                $result = $query->getRowArray();
                if (!empty($result)) {
                    echo '1';
                    exit();
                }

                $sql1 = "select tentative_section($diaryno)";
                $sql2 = "SELECT m.court_fee,TO_CHAR(MIN(o.save_dt), 'DD-MM-YYYY') FROM main m LEFT JOIN  obj_save o ON  m.diary_no = o.diary_no WHERE m.diary_no = '$diaryno' AND o.display = 'Y' group by m.court_fee;";
                $sql1_query = $this->db->query($sql1);
                $result1 = $sql1_query->getRowArray();

                if (!empty($result1)) {
                    $row1 = $result1;
                    $section = $row1['tentative_section'];
                    $result2 = $this->db->query($sql2);
                    $row2 = $result2->getRowArray();
                    if (!empty($result2)) {
                        $courtfees = $row2['court_fee'] ?? '';
                        $dateofnotification = $row2['to_char'] ?? '';
                        echo $section . '~' . $courtfees . '~' . $dateofnotification;
                        die();
                    } else
                        echo '0';
                } else
                    echo '0';
            } else {
                echo '0';
            }
        }
    }

    public function getExistingRecord($diaryno)
    {
        return $this->where(['diary_no' => $diaryno, 'display' => 'Y'])
            ->get()
            ->getRowArray();
    }

    public function getTentativeSection($diaryno)
    {
        $query = $this->db->query("SELECT tentative_section('$diaryno')");
        $result = $query->getRowArray();
        return $result ? $result['tentative_section'] : null;
    }

    public function getCourtFeeAndDate($diaryno)
    {
        $query = $this->db->query("
            SELECT m.court_fee, TO_CHAR(MIN(o.save_dt), 'DD-MM-YYYY') AS date
            FROM main m
            LEFT JOIN obj_save o ON m.diary_no = o.diary_no
            WHERE m.diary_no = '$diaryno' AND o.display = 'Y'
            GROUP BY m.court_fee
        ");
        return $query->getRowArray();
    }

    public function getUpdateRecord($diaryno)
    {
        return $this->where(['diary_no' => $diaryno, 'display' => 'Y'])
            ->select('section_id, court_fees, TO_CHAR(defect_notify_date, \'DD-MM-YYYY\') AS defect_notify_date, rack_no, shelf_no, id')
            ->get()
            ->getRowArray();
    }

    public function getSectionName($sectionId)
    {
        $query = $this->db->table('master.usersection')
            ->select('section_name')
            ->where('id', $sectionId)
            ->get()
            ->getRowArray();

        return $query ? $query['section_name'] : null;
    }

    public function insertRecord($data)
    {
        $this->insert($data);
        return $this->db->affectedRows();
    }

    public function updateRecord($data, $id)
    {
        $this->update($id, $data);
        return $this->db->affectedRows();
    }

    public function deleteRecord($id)
    {
        return $this->update($id, ['display' => 'N']);
    }

    public function bulkUpdate($rackno, $shelfno, $idRange)
    {
        $builder = $this->builder();
        $builder->set('rack_no', $rackno);
        $builder->set('shelf_no', $shelfno);
        $builder->set('upd_dt', 'NOW()', false);
        $builder->set('upd_userid', $_SESSION['login']['usercode']);
        $builder->whereIn('id', $idRange);
        $builder->where('display', 'Y');
        return $builder->update();
    }

    public function getSectionId($section_name)
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('id');
        $builder->where('section_name', $section_name);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getDefectiveRecords($dtd1, $dtd2)
    {
        $builder = $this->builder('master.defect_record_paperbook d');
        $builder->select('d.id, diary_no, section_name, court_fees, TO_CHAR(defect_notify_date, \'DD-MM-YYYY\') as defect_notify_date, rack_no, shelf_no, ent_dt, name');
        $builder->join('master.users u', 'd.ent_userid = u.usercode');
        $builder->join('master.usersection us', 'd.section_id = us.id');
        $builder->where("CAST(ent_dt as DATE) BETWEEN '$dtd1' AND '$dtd2'");
        $builder->where('d.display', 'Y');
        $builder->orderBy('d.id, d.ent_dt, CAST(d.rack_no AS INTEGER), CAST(d.shelf_no AS INTEGER)');
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        return $query->getResultArray();
    }
}
