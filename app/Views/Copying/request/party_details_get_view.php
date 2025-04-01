<?php

$diary_no = $_POST['diary_no'];
?>


 <?php
$resultData=$copyRequestModel->getPartyDetailsByDiaryNo($diary_no);
 
    $sn_pet=1;
   $sn_rep=1;
   $sk_tot='';
   if($resultData['ctnt_tot']>0){
       ?>
<table class="table table-striped mt-3" >
    <tr><td colspan="2" class="bg-success text-white font-weight-bolder">PARTY DETAILS:</td></tr>
       <?php
 foreach($resultData['results'] as $row_tot_par)
 {
 $nm_nm = $row_tot_par['pet_res'];
 $s_sno = $row_tot_par['sr_no'];
 if ($row_tot_par['pet_res'] == 'P') {
     $sk_tot = $sn_pet++;
 } else if ($row_tot_par['pet_res'] == 'R') {
     $sk_tot = $sn_rep++;
 }

 if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
     ?>

     <?php
     $res_ct_pet=$copyRequestModel->getPartyCount($diary_no,'P');
     
     ?>
     <tr>
         <td rowspan="<?php echo $res_ct_pet + 1; ?>" class="font-weight-bolder text-success">

             Petitioner
         </td>


     </tr>
     <?php
 } else if ($row_tot_par['pet_res'] == 'R' && $row_tot_par['sr_no'] == '1') {
     ?>
     <tr>
         <?php
         $sq_ct_res = $copyRequestModel->getPartyCount($diary_no,'R');
         ?>
         <td rowspan="<?php echo $res_ct_res + 1; ?>" class="font-weight-bolder text-success">

             Respondent
         </td>
     </tr>
     <?php
 }
 ?>


    <tr>
        <td style="text-align: left;" colspan="2">


            <b>
                <?php
                if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
                    $mn_party = $row_tot_par['partyname'];
                }
                echo '(' . $sk_tot . ') <span class="badge badge-secondary">' . $row_tot_par['partyname'] . '</span>';
                if ($row_tot_par['sonof'] != '') {
                    if ($row_tot_par['sonof'] == 'S') {
                        if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
                            $mn_party .= " S/o ";
                        }
                        echo " S/o ";

                    } else if ($row_tot_par['sonof'] == 'D') {
                        if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
                            $mn_party .= " D/o ";
                        }

                        echo " D/o ";

                    } else if ($row_tot_par['sonof'] == 'W') {
                        if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
                            $mn_party .= " W/o ";
                        }

                        echo " W/o ";
                    }
                    if ($row_tot_par['pet_res'] == 'P' && $row_tot_par['sr_no'] == '1') {
                        $mn_party .= $row_tot_par['prfhname'];
                    }
                    echo $row_tot_par['prfhname'];
                }


                if ($row_tot_par['age']) {
                    echo $age = ", </b>Aged about<b> " . $row_tot_par['age'] . " years</b>";
                    $age = "";
                }


                if (!preg_match('/[0-9]/', $row_tot_par['state'])) {
                    $rs_non_bail_dis_all = $row_tot_par['city'];
                    $rs_non_bail_sta_all = $row_tot_par['state'];
                } else {
                    $rs_non_bail_dis_all = $copyRequestModel->get_state($row_tot_par['city']);
                    $rs_non_bail_sta_all = $copyRequestModel->get_state($row_tot_par['state']);
                }
                if ($row_tot_par['addr1']) {
                    echo ", <b>" . ucwords($row_tot_par['addr1']);
                }
                ?>
            </b>, R/o <b><?php echo trim(ucwords($row_tot_par['addr2'])); ?></b>, District-
            <b>
                <?php echo strtoupper($rs_non_bail_dis_all); ?>
                (
                <?php if ($rs_non_bail_sta_all == 'MADHYA PRADESH') {
                    echo "M.P.";
                } else {
                    echo $rs_non_bail_sta_all;
                } ?>
                )</b>
            <?php }
            }
            else{
                echo '<table class="table table-striped mt-3" >
                    <tr><td colspan="2" class="bg-success text-white font-weight-bolder">No Records Found</td></tr></table>';
            }?>
        </td>
   </tr>
<?php  
?>
