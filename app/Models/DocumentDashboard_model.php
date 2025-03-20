<?php
namespace App\Models;
use CodeIgniter\Model;
class DocumentDashboard_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getStepWiseTotal(){
        $builder = $this->db->table('faster_cases');
        $builder->select("
            COUNT(1) as total,
            COUNT(CASE WHEN last_step_id IN(1) THEN 1 ELSE NULL END) as pending_digital_sign,
            COUNT(CASE WHEN last_step_id IN(2, 3) THEN 1 ELSE NULL END) as pending_institutional_sign,
            COUNT(CASE WHEN last_step_id IN(4, 9) THEN 1 ELSE NULL END) as completed
        ");
        $builder->where('is_deleted', 0);

        $query = $builder->get();
        return $query->getResult();
    }
    public function getTypeWiseData($params= array()){
        $output= false;
        $sql='';
        if(isset($params['type']) && !empty($params['type'])){
            $type = trim($params['type']);
            $stepType = " ";
            switch ($type){
                case 'total':
                    $stepType ="";
                    break;
                case 'pending_digital_sign':
                    $stepType =" and fc.last_step_id IN(1) ";
                    break;
                case 'pending_institutional_sign':
                    $stepType =" and fc.last_step_id IN(2,3) ";
                    break;
                case 'completed':
                    $stepType =" and fc.last_step_id IN(4,9) ";
                    break;
                default:
                    $stepType ="";
            }
            $builder = $this->db->table('faster_cases fc');

            $builder->select("fc.id, 
                            TO_CHAR(fc.created_on, 'DD-MM-YYYY HH24:MI:SS') as created_on, 
                            u.name, 
                            rfs.description as current_stage,
                            CONCAT(m.reg_no_display, ' @ ', 
                                    SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR (LENGTH(CAST(m.diary_no AS TEXT)) - 4)), 
                                    ' / ', 
                                    SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4)) as reg_no,
                            CONCAT(us.section_name, ' (', us.description, ' )') as section_details")
                ->join('master.users u', 'fc.created_by = u.usercode', 'inner')
                ->join('master.ref_faster_steps rfs', 'fc.last_step_id = rfs.id', 'inner')
                ->join('main m', 'fc.diary_no = m.diary_no', 'inner')
                ->join('master.usersection us', 'fc.created_by = us.id AND us.display = \'Y\'', 'inner')
                ->where('fc.is_deleted', 0);
            if (!empty($stepType)) {
                $stepType = trim($stepType);
                if (stripos($stepType, 'AND') === 0) {
                    $stepType = substr($stepType, 4);
                }
                $builder->where($stepType);
            }
            $builder->orderBy('fc.created_on', 'DESC');

            $output = $builder->get()->getResult();
        }
        return $output;
    }

    public function getDocumentTimelineDataById($type,$rowId){
        $output= false;
        $sql="";
        if(isset($type) && !empty($type) && isset($rowId) && !empty($rowId)) {
            $rowId = (int)trim($rowId);
            $type = trim($type);
            $stepType =" ";
            switch ($type) {
                case 'total':
                    $stepType =" ";
                    break;
                case 'pending_digital_sign':
                    $stepType = " and fc.last_step_id IN(1) ";
                    break;
                case 'pending_institutional_sign':
                    $stepType = " and fc.last_step_id IN(2,3) ";
                    break;
                case 'completed':
                    $stepType = " and fc.last_step_id IN(4,9) ";
                    break;
                default:
                    $stepType =" ";
              }
            // $sql="select fc.id,fc.diary_no,fc.created_on,rfs.description as current_stage,j.ref_faster_steps_id,
            //     j.file_path,j.dated,j.transaction_created_on,
            //     u.name,j.document_name,j.fs_deleted,j.ft_is_deleted,j.fs_created_on
            //     from faster_cases fc left join  
            //     (
            //        select ft.faster_cases_id,group_concat(fsdd.file_path) as file_path,ft.ref_faster_steps_id,
            //             group_concat(fsdd.dated) as dated,fsdd.created_by,
            //             group_concat(ft.faster_shared_document_details_id) as faster_shared_document_details_id ,
            //             group_concat(ft.created_on ) as transaction_created_on,
            //             group_concat(tw.name) as document_name,
            //             group_concat(fsdd.is_deleted  ) as fs_deleted,
            //             group_concat(ft.is_deleted ) as ft_is_deleted ,
            //             group_concat(fsdd.created_on) as fs_created_on
            //             from faster_transactions ft  left join faster_shared_document_details fsdd
            //             on  ft.faster_shared_document_details_id = fsdd.id  left join master.tw_notice tw 
            //             on fsdd.tw_notice_id = tw.id and tw.display in ('Y','Z','N')
            //             where ft.faster_cases_id=$rowId
            //             group by ft.ref_faster_steps_id
            //             order by fsdd.id
            //     ) as j
            //     on fc.id =j.faster_cases_id inner join users u
            //     on u.usercode = fc.created_by left join ref_faster_steps rfs 
            //     on rfs.id = j.ref_faster_steps_id
            //     where  fc.id in ($rowId) $stepType
            //     order by  j.transaction_created_on ASC";

            $sql="SELECT 
                fc.id,
                fc.diary_no,
                fc.created_on,
                rfs.description AS current_stage,
                j.ref_faster_steps_id,
                j.file_path,
                j.dated,
                j.transaction_created_on,
                u.name,
                j.document_name,
                j.fs_deleted,
                j.ft_is_deleted,
                j.fs_created_on
            FROM 
                faster_cases fc 
            LEFT JOIN 
                (
                    SELECT 
                        ft.faster_cases_id,
                        string_agg(fsdd.file_path, ', ') AS file_path,
                        ft.ref_faster_steps_id,
                        string_agg(fsdd.dated::text, ', ') AS dated,
                        fsdd.created_by,
                        string_agg(ft.faster_shared_document_details_id::text, ', ') AS faster_shared_document_details_id,
                        string_agg(ft.created_on::text, ', ') AS transaction_created_on,
                        string_agg(tw.name, ', ') AS document_name,
                        string_agg(fsdd.is_deleted::text, ', ') AS fs_deleted,
                        string_agg(ft.is_deleted::text, ', ') AS ft_is_deleted,
                        string_agg(fsdd.created_on::text, ', ') AS fs_created_on
                    FROM 
                        faster_transactions ft  
                    LEFT JOIN 
                        faster_shared_document_details fsdd ON ft.faster_shared_document_details_id = fsdd.id  
                    LEFT JOIN 
                        master.tw_notice tw ON fsdd.tw_notice_id = tw.id AND tw.display IN ('Y', 'Z', 'N')
                    WHERE 
                        ft.faster_cases_id = $rowId
                    GROUP BY 
                        ft.ref_faster_steps_id,ft.faster_cases_id,fsdd.created_by,fsdd.id
                    ORDER BY 
                        fsdd.id
                ) AS j ON fc.id = j.faster_cases_id 
            INNER JOIN 
                master.users u ON u.usercode = fc.created_by 
            LEFT JOIN 
                master.ref_faster_steps rfs ON rfs.id = j.ref_faster_steps_id
            WHERE  
                fc.id IN ($rowId)  
            ORDER BY 
                j.transaction_created_on ASC";
        }
        // pr($sql);
        $output =  $this->db->query($sql)->getResult();
        return $output;
    }

}