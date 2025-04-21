<style>
    td {
        overflow: hidden;
    }

    th {
        overflow: hidden;
    }

    td {
        font-size: 14px
    }

    th {
        font-size: 14px
    }

    tr.bck1 {
        background-color: #C4C4C4;
    }

    tr.bck2 {
        background-color: #FFF8EB;
    }

    tr.bck3 {
        background-color: #D7CCD9;
    }

    tr.bck4 {
        background-color: #E7E2FE;
    }

    tr.bck5 {
        background-color: #F1EEF2;
    }
</style>

<?php
if (count($table) > 0) {
?><table align=center border="1" cellpadding=2 cellspacing=0 class="table table-bordered table-striped table-hover">
        <tr>
            <th colspan=26><?php $til_date2 = explode("-", $til_dt);
                            $til_dt2 = $til_date2[2] . "-" . $til_date2[1] . "-" . $til_date2[0];
                            echo 'Total records = ' . count($table) . ' ' . @$subhead_name . ' pending cases as on ' . $til_dt2 . ' where year=' . $_GET['year'] . ' and nature=' . $_GET['skey']; ?></th>
        </tr>
        <tr>
            <th>SNo.</th>
            <th>Diary No</th>
            <th>Case Details</th>
            <th>Fil dt.</th>
            <th>Pet.</th>
            <th>Res.</th>
            <th>Pet.Adv.</th>
            <th>Res.Adv.</th>
            <th>Status</th>
            <th>DA</th>
            <th>mainhead</th>
            <th>Jud1</th>
            <th>next/last dt</th>
            <th>last Order dt</th>
            <th>rj_dt</th>
            <th>disp month/year</th>
            <th>disp_dt</th>
            <th>rest. month /year</th>
            <th>last disp month/year</th>
            <th>rest. dt</th>
            <th>last disp_dt</th>   
            <th>last subhead</th>
            <th>last max ent dt</th>
            <th>cuurent ent dt</th>
            <th>cuurent subhead</th>
            <th>category</th>

        </tr>
        <?php
        $i = 1;
        $temp_da = '';
        $temp_dt = '';
        $j = 1;
        $sr = 1;
        foreach ($table as $row)           
        {
            if(empty($row['tentative_cl_dt']))
            {
               $tentative_date = '';
            }  else{ 
            $tc = explode('-', $row['tentative_cl_dt']);
            $tentative_date = $tc[2] . '-' . $tc[1] . '-' . $tc[0];
            }
            if(empty($row['tentative_cl_dt']))
            {
               $next_date = '';
            }  else{ 

            $nd = explode('-', $row['next_dt']);
            $next_date = $nd[2] . '-' . $nd[1] . '-' . $nd[0];
            }
            // if ($year_wise_tot == 'y' || $year_wise_tot == 'all')
            //     $casename = $this->casetype2(substr($row['fil_no'], 2, 3));
            // else
                $casename = $skey;
            if(empty($row['lastorder']))
            $lastorder = '';
            elseif (substr($row['lastorder'], -3, 1) == ":")
                $lastorder = substr($row['lastorder'], -19, 10);
            else
                $lastorder = substr($row['lastorder'], -10);
;
            if (isset($row['pet_adv2']) && trim($row['pet_adv2']) == '') 
                $pet_adv_other = '';
            else
                $pet_adv_other = $row['pet_adv2'] ;

            if (isset($row['res_adv2']) && trim($row['res_adv2']) == '') 
                $res_adv_other = '';
            else
                $res_adv_other = $row['res_adv2'] ;


            if ($mainhead_name == 'sw') {
                $mainhead_display = $row['mainhead_n'];
            } else {
                $mainhead_display = $row['mainhead'];
            }
        ?>

            <tr>
                <td><?php echo $j; ?></td>
                <td><?php echo $row['diary_no']; ?></td>
                <td><?php echo $casename . " / " . substr($row['active_fil_no'], 4) . " / " . $row['active_reg_year']; ?></td>
                <td><?php echo substr($row['diary_no_rec_date'], 0, 10); ?></td>
                <td><?php echo $row['pet_name']; ?></td>
                <td><?php echo $row['res_name']; ?></td>
                <td><?php echo @$row['pet_adv'] . ' ' . @$pet_adv_other; ?></td>
                <td><?php echo @$row['res_adv'] . ' ' . @$res_adv_other; ?></td>
                <td><?php echo $row['c_status']; ?></td>
                <td><?php echo @$row['username']; ?></td>
                <td><?php echo $mainhead_display; ?></td>
                <td><?php echo $row['judges']; ?></td>
                <td><?php echo $row['next_dt']; ?></td>
                <td><?php echo $lastorder; ?></td>
                <td><?php echo $row['rj_dt']; ?></td>
                <td><?php echo $row['month'] . '/' . $row['year']; ?></td>
                <td><?php echo $row['disp_dt']; ?></td>
                <td><?php echo @$row['res_month'] . '/' . @$row['res_year']; ?></td>
                <td><?php echo $row['disp_month'] . '/' . $row['disp_year']; ?></td>
                <td><?php echo $row['conn_next_dt']; ?></td>
                <td><?php echo $row['disp_dt_res']; ?></td>
                <td><?php echo @$row['last_subhead']; ?></td>
                <td><?php echo @$row['med']; ?></td>
                <td><?php echo $row['ent_dt']; ?></td>
                <td><?php echo @$row['subhead']; ?></td>
                <td><?php echo @$row['category'] . "|" . @$row['subcat'] . "|" . @$row['subcat1']; ?></td>
            </tr>
        <?php $temp_dt = $row['tentative_cl_dt'];
            $j++;
            //}// if($_GET['da']==$row['dacode']) end

            $sr++;
        } // while end
        ?>
    </table>
<?php
} // if(mysql_affected_rows()>0)
else
    echo "<center><h1>Record Not Found</h1></center>";
?>