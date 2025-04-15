<style>
    th{
        background-color: #0d48be;
        text-align: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }
</style>
<?php
    if ($tot_row > 0) {
        
        ?>
        <div id="prnTable" align="center">
            <table class="table table-bordered table-striped" cellpadding=1 cellspacing=0 border=1>
                <tr>
                    <th colspan=<?php echo ($civil_colspan + $cr_colspan + 3); ?> align="center">
                        Year and Nature wise 
                        <?php 
                        $til_date2 = explode("-", $til_dt);
                        $til_dt2 = $til_date2[2] . "-" . $til_date2[1] . "-" . $til_date2[0];	 
                        echo $head_subhead . ' pending cases as on ' . $til_dt2; ?>
                        
                    </th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th colspan=<?php echo $civil_colspan; ?> align="center">CIVIL CASES</th>
                    <th colspan=<?php echo $cr_colspan; ?> align="center">CRIMINAL CASES</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
                $i = 1;
                $total = 0;
                echo '</tr><tr><th>Sno</th><th>Year</th>';

                foreach ($results as $row) {
                    if ($rpt_type == 'bench') {
                        $bench_or_year = $row['bench'];
                    } else {
                        $bench_or_year = @$row['pend_year'];
                    }

                    $builder = $db->table('master.casetype');
                    $builder->select('casecode,LOWER(skey) as skey,nature');
                    $builder->where('display', 'Y');
                    $builder->orderBy('nature', 'ASC')->orderBy('skey', 'ASC');
                    $query = $builder->get();

                    // $sql_case = "SELECT skey, nature FROM casetype WHERE display='Y' ORDER BY nature, skey";
                    // $query_case = $db->query($sql_case);
                    //  = $query_case->getResultArray();

                    if ($i == 1) {
                        foreach ($query->getResultArray() as $row_case) {
                           echo "<th>" . strtoupper($row_case['skey']) . "</th>";
                        }
                        echo "<th>Total</th></tr>";
                    }

                    if ($i == $tot_row) {
                        $year_wise_tot_str = 'all';
                        echo "<tr><th colspan=2>Total</th>";
                    } else {
                        echo "<tr><td>" . $i . "</td><td>" . $row['year'] . "</td>";
                        $year_wise_tot_str = 'y';
                    }

                    // $sql_case1 = "SELECT skey, casecode FROM casetype WHERE display='Y' ORDER BY nature, skey";
                    // $query_case1 = $db->query($sql_case1);
                    // $case_results1 = $query_case1->getResultArray();
                    $year_wise_tot = 0;
                    foreach ($query->getResultArray() as $row_case1) {
                       
                        $count = $row[$row_case1['skey']] == "0" ? "-" : $row[$row_case1['skey']];
                        $year_wise_tot += $row[$row_case1['skey']] == "0" ? 0 : $row[$row_case1['skey']];

                        if ($row[$row_case1['skey']] == "0") {
                            echo "<td align=right>-</td>";
                        } else {
                            if ($tot_row == $i) {
                                echo "<td align=right><span style='cursor: pointer;'>" . $count . "</span></td>";
                            } else {
                                ?>
 
 <td><span style="cursor: pointer;" id="<?php echo $row['year'] . "_" . $row_case1['casecode']; ?>" 
                                onclick="open_tab(
                               '<?php echo @$_GET['nature_wise_tot']?>','<?php echo $_GET['subject']?>','<?php echo $_GET['subject_length'];?>',
                               '<?php echo $_GET['cat'];?>','<?php echo $_GET['cat_length'];?>','<?php echo $_GET['subcat'];?>',
                               '<?php echo $_GET['subcat_length'];?>','<?php echo $row['year'];?>','<?php echo $row_case1['skey'];?>',
                               '<?php echo $_GET['subhead'];?>','<?php echo $_GET['mf'];?>','<?php echo $_GET['til_date'];?>',
                               '<?php echo $_GET['from_year'];?>','<?php echo $_GET['to_year'];?>','<?php echo $_GET['rpt_type'];?>',
                               '<?php echo $_GET['pet_res'];?>','<?php echo $_GET['party_name'];?>','<?php echo $_GET['act_msc'];?>',
                               '<?php echo $_GET['lst_month'];?>','<?php echo $_GET['lst_year'];?>','<?php echo $_GET['ason_type'];?>',
                               '<?php echo $_GET['from_fil_dt'];?>','<?php echo $_GET['upto_fil_dt'];?>','<?php echo $_GET['rpt_purpose'];?>',
                               '<?php echo @$_GET['spl_case'];?>','<?php echo $_GET['concept'];?>','<?php echo $_GET['main_connected'];?>',
                               '<?php echo $_GET['act'];?>','<?php echo $_GET['order_by'];?>','<?php echo $_GET['adv_opt'];?>',
                               '<?php echo $_GET['case_status_id']?>','<?php echo $_GET['subcat2'];?>',
                               '<?php echo $_GET['subcat2_length'];?>'); "class="ank"><?php echo $count; ?></span>
                               </td>
                                <?php
                            }
                        }
                    }
                    echo "<td align=right>" . $year_wise_tot . "</td></tr>";
                    $i++;
                }
                ?>
            </table>
        </div>
        <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
            &nbsp;
        </div>
        <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;left:0;width:100%;height:100%;z-index: 105;">
            <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()">
                <b><img src="<?php echo base_url()?>/images/close_btn.png" style="width:30px;height:30px"/></b>
            </div>
            <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return nb(event)" onmouseup="checkStat()">
            </div>
        </div>
        <br><br>
        <div align="center">
            <input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('prnTable');" value="PRINT">
        </div>
        <?php
    } else {
        echo "<center><h2>Record Not Found</h2></center>";
        echo "<span id='s' align='left'>Date : " . date('d-m-Y H:i:s') . "</span>";
    }


