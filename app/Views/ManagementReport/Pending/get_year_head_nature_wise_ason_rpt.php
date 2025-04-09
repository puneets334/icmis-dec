<?php
    echo $sql;
    $query = $db->query($sql);
    $results = $query->getResultArray();
    $tot_row = count($results);

    if ($tot_row > 0) {
        $civil_colspan = tot_case_in_nature('C');
        $cr_colspan = tot_case_in_nature('R');
        ?>
        <div id="prnTable" align="center">
            <table cellpadding=1 cellspacing=0 border=1>
                <tr>
                    <td colspan=<?php echo ($civil_colspan + $cr_colspan + 3); ?> align="center">
                        <font color=blue size=+1>Year and Nature wise 
                        <?php 
                        $til_date2 = explode("-", $til_dt);
                        $til_dt2 = $til_date2[2] . "-" . $til_date2[1] . "-" . $til_date2[0];	 
                        echo $head_subhead . ' pending cases as on ' . $til_dt2; ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <th colspan=<?php echo $civil_colspan; ?> align="center"><font color=blue>CIVIL CASES</font></th>
                    <th colspan=<?php echo $cr_colspan; ?> align="center"><font color=blue>CRIMINAL CASES</font></th>
                    <th>&nbsp;</th>
                </tr>
                <?php
                $i = 1;
                $total = 0;
                echo '</tr><tr><th>Sno</th><th>Year</th>';

                foreach ($results as $row) {
                    if ($this->request->getGet('rpt_type') == 'bench') {
                        $bench_or_year = $row['bench'];
                    } else {
                        $bench_or_year = $row['pend_year'];
                    }

                    $sql_case = "SELECT skey, nature FROM casetype WHERE display='Y' ORDER BY nature, skey";
                    $query_case = $db->query($sql_case);
                    $case_results = $query_case->getResultArray();

                    if ($i == 1) {
                        foreach ($case_results as $row_case) {
                            echo "<th>" . $row_case['skey'] . "</th>";
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

                    $sql_case1 = "SELECT skey, casecode FROM casetype WHERE display='Y' ORDER BY nature, skey";
                    $query_case1 = $db->query($sql_case1);
                    $case_results1 = $query_case1->getResultArray();
                    $year_wise_tot = 0;

                    foreach ($case_results1 as $row_case1) {
                        $count = $row[$row_case1['skey']] == "0" ? "-" : $row[$row_case1['skey']];
                        $year_wise_tot += $row[$row_case1['skey']] == "0" ? 0 : $row[$row_case1['skey']];

                        if ($row[$row_case1['skey']] == "0") {
                            echo "<td align=right>-</td>";
                        } else {
                            if ($tot_row == $i) {
                                echo "<td align=right><span style='cursor: pointer;'>" . $count . "</span></td>";
                            } else {
                                echo "<td align=right><span style='cursor: pointer;' id='" . $row['year'] . "_" . $row_case1['casecode'] . "' 
                                onclick=\"open_tab(
                                    '" . $this->request->getGet('nature_wise_tot') . "','" . $this->request->getGet('subject') . "','" . $this->request->getGet('subject_length') . "',
                                    '" . $this->request->getGet('cat') . "','" . $this->request->getGet('cat_length') . "','" . $this->request->getGet('subcat') . "',
                                    '" . $this->request->getGet('subcat_length') . "','" . $row['year'] . "','" . $row_case1['skey'] . "',
                                    '" . $this->request->getGet('subhead') . "','" . $this->request->getGet('mf') . "','" . $this->request->getGet('til_date') . "',
                                    '" . $this->request->getGet('from_year') . "','" . $this->request->getGet('to_year') . "','" . $this->request->getGet('rpt_type') . "',
                                    '" . $this->request->getGet('pet_res') . "','" . $this->request->getGet('party_name') . "','" . $this->request->getGet('act_msc') . "',
                                    '" . $this->request->getGet('lst_month') . "','" . $this->request->getGet('lst_year') . "','" . $this->request->getGet('ason_type') . "',
                                    '" . $this->request->getGet('from_fil_dt') . "','" . $this->request->getGet('upto_fil_dt') . "','" . $this->request->getGet('rpt_purpose') . "',
                                    '" . $this->request->getGet('spl_case') . "','" . $this->request->getGet('concept') . "','" . $this->request->getGet('main_connected') . "',
                                    '" . $this->request->getGet('act') . "','" . $this->request->getGet('order_by') . "','" . $this->request->getGet('adv_opt') . "',
                                    '" . $this->request->getGet('case_status_id') . "','" . $this->request->getGet('subcat2') . "',
                                    '" . $this->request->getGet('subcat2_length') . "');\" class='ank'>" . $count . "</span></td>";
                            }
                        }
                    }
                    echo "<th align=right>" . $year_wise_tot . "</th></tr>";
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
                <b><img src="../images/close_btn.png" style="width:30px;height:30px"/></b>
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
    }

    echo "<span id='s' align='left'>Date : " . date('d-m-Y H:i:s') . "</span>";
}
