<?=view('header') ?>
<?php

$cl_result = $model->isPrintedCaseSingleJudge($from_dt, $to_dt);

if($cl_result == 1)
{
    echo "<br/><center><span style='color:red;'>YOU CAN NOT ALLOT CASE FOR DATED " .date('d-m-Y', strtotime($from_dt))." TO ".date('d-m-Y', strtotime($to_dt)) . " BECAUSE ADVANCE LIST FINALIZED</span></center>";
}
else if(empty($diary_number))
{
    echo "<br/><center><span style='color:red;'>Please enter Diary No.</center>";
}
else
{
   
    $res_list_no = $model->getWeekNoSingleJudge($from_dt, $to_dt);
    if(!empty($res_list_no))
    {
        $row_list_no = $res_list_no;
        $advance_weekly_no=$row_list_no['weekly_no'];
        $advance_weekly_year=$row_list_no['weekly_year'];
        $afros=$model->insertAdvanceSingleJudgeAllocated($from_dt,$to_dt,$advance_weekly_no,$advance_weekly_year,$q_usercode);
        if($afros > 0)
        {
            echo "Success";
        }
        else
        {
            $sql2=$model->getAdvanceSingleJudgeAllocated($diary_number,$from_dt,$to_dt);
            if(!empty($sql2))
            {
                    $sql3=$model->updateSingleJudgeAllocated($diary_number,$from_dt,$to_dt);
                    echo "success";
            }
            else
            {
                echo "Case Not Fit for Allocation";
            }

        }
    }
    else
    {
        echo "<br/><center><span style='color:red;'>FIRST ALLOCATE CASES USING SINGLE JUDGE ADVANCE WEEKLY MODULE</span></center>";
    }

}

?>