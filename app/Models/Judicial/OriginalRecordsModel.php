<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 14/8/18
 * Time: 12:22 PM
 */
namespace App\Models\Judicial;
use CodeIgniter\Model;

class OriginalRecordsModel extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function checkIfFileExist($diaryNo)
    {
        $sql="select * from original_records_file where diary_no=".$diaryNo." and display='Y'";
        $query = $this->db->query($sql);
        $res = $query->result();
        if(!$res)
        {
            return 0;
        }
        return $query->result_array();
    }

    function insertOriginalRecords($data)
    {
        $result=0;
        $selectString="select * from original_records_file where diary_no=".$data['diary_no']." and display='Y'";
        $query = $this->db->query($selectString);
        $res = $query->result();
        if(!$res){
            $queryString="insert into original_records_file(diary_no,file_name,usercode,updated_on,display)
                    values(".$data['diary_no'].",'".$data['file_name']."',".$data['usercode'].",now(),'Y')";
            //echo "<br/>".$queryString;
            $this->db->query($queryString);
            $result=1;
        }
        return $result;
    }

    function updateOriginalRecords($data)
    {
        $result=0;
        $queryString="update original_records_file set file_name='".$data['file_name']."',display='N' where id=".$data['id'];
        $this->db->query($queryString);
        $result=1;
        return $result;
        //$this->db->query($queryString);
    }

    public function getCaseDownloadList($fromDate,$toDate,$usercode)
    {
        // $queryString = " select concat(m.reg_no_display,' @ ',concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4))) as case_no, concat(m.pet_name,' Vs. ',m.res_name) as causetitle,orf.file_name,u.name as upladed_by,orf.updated_on from original_records_file orf inner join main m on orf.diary_no=m.diary_no inner join users u on u.usercode=orf.usercode where orf.display='Y' and date(updated_on) between '".$fromDate."' and '".$toDate."'";

        // //and orf.usercode=".$usercode
        // //echo $queryString;
        // $query = $this->db->query($queryString);
        // return $query->result_array();

        // echo $usercode."<br>";
        // echo $fromDate."<br>";
        // echo $toDate."<br>";
        // echo 'cvghncvgn'."<br>";
        // die;

        $builder = $this->db->table('original_records_file orf');

        // Select the required columns
        $builder->select("
            CONCAT(m.reg_no_display, ' @ ', 
                CONCAT(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                ' / ', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3))) AS case_no,
            CONCAT(m.pet_name, ' Vs. ', m.res_name) AS causetitle,
            orf.file_name,
            u.name AS uploaded_by,
            orf.updated_on
        ", false);

        // Join with 'main' and 'users' tables
        $builder->join('main m', 'orf.diary_no = m.diary_no');
        $builder->join('master.users u', 'u.usercode = orf.usercode');

        // Add WHERE conditions
        $builder->where('orf.display', 'Y');
        $builder->where("DATE(orf.updated_on) BETWEEN '$fromDate' AND '$toDate'", null, false);

        // Execute the query and fetch the result
        $query = $builder->get();
        $result = $query->getResultArray(); // Fetch as an associative array
        // echo "Query = ".$this->db->getLastQuery()->getQuery()."<br>";
        // pr($result);
        return $result;
    }

}
