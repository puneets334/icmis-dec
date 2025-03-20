<?php
$listorder = '0';
$lastorder = '';
$fhc1 = '';
$t_checked = '';
$benchmain = '';
$mfvar = '';
$mainhead_new1 = '';
$check_for_regular_case = "";
?>
<script src="<?= base_url() ?>/judicial/proposal.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pt-4" style="text-align: center;">
                                <h3>Diary No.- <?php echo $diary_number; ?> - <?php echo $diary_year; ?></h3>
                            </div>
                            <div class="cl_center"><u>
                                    <h3>CASE DETAILS</h3>
                                </u></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td style="width: 15%">Case No.</td>
                                    <td><?php echo $case_no; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">DA Name</td>
                                    <td>
                                        <?php echo $da_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Petitioner</td>
                                    <td>
                                        <?php echo $pet_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Respondant</td>
                                    <td>
                                        <?php echo $res_name; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 15%">Case Category</td>
                                    <td><?php echo $mul_category; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        Act
                                    </td>
                                    <td><?php echo $act_section; ?></td>
                                </tr>
                                <tr>
                                    <td>Provision of Law</td>
                                    <td><?php echo $provision_of_law; ?></td>
                                </tr>
                                <tr>
                                    <td>Amicus Curie(For Court Assistance)</td>
                                    <td>
                                        <?php echo $ac_court; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Petitioner Advocate</td>
                                    <td>
                                        <?php echo $padvname; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Respondant Advocate</td>
                                    <td>
                                        <?php echo $radvname; ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Last Order</td>
                                    <td>
                                        <?php echo $fil_no['lastorder']; ?>
                                    </td>
                                </tr>
                                <?php
                                if ($fil_no['c_status'] == 'P') {
                                    if ($t_rgo != '') { ?>
                                        <tr>
                                            <td>Conditional Dispose</td>
                                            <td style='font-size:12px;font-weight:100;'>
                                                <b>
                                                    <font style='font-size:12px;font-weight:100;'><b><?php echo $t_rgo; ?></b></font>
                                                </b>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            Tentative Date
                                        </td>
                                        <td>
                                            <input type="hidden" name="ttd" id="ttd" value="<?php echo $tentative_date; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Matter Type </td>
                                        <td>
                                            <?php echo $matter_type; ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>

                                    <tr>
                                        <td>Case Status</td>
                                        <td>
                                            <b>
                                                <font color=red style="font-size:14px;">Case is Disposed</font>
                                            </b>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class='cl_center'><u>
                                <h3>LISTINGS / PROPOSALS</h3>
                            </u></div>

                        <?php echo $perposal_listing; ?>

                        <input type='hidden' name='pendingIAs' id='pendingIAs' value=<?php echo $pendingIAs; ?> />
                        <!-- // added on 28.01.2020 -->
                        <input type='hidden' name='last_remarks' id='last_remarks' value=<?php echo $remarks; ?> />
                        <input type='hidden' name='last_cl_date' id='last_cl_date' value=<?php echo $last_cl_date; ?> />

                        <?php if (!empty($ian_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>INTERLOCUTARY APPLICATIONS</h3>
                                </u></div>
                            <?php echo $ian_listing; ?>
                        <?php } ?>

                        <?php if (!empty($doc_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>DOCUMENTS FILED</h3>
                                </u></div>
                            <?php echo $doc_listing; ?>
                        <?php } ?>
                        
                        <?php if (!empty($rmtable) && $fil_no['c_status'] == 'P') { ?>
                            <?php echo $rmtable; ?>
                        <?php } ?>
                        
                        <?php if (!empty($linked_case_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>CONNECTED / LINKED CASES</h3>
                                </u></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td align='center' width='30px'><b>S.N.</b></td>
                                    <td><b>Diary No.</b></td>
                                    <td><b>Case No.</b></td>
                                    <td><b>Proposed for</b></td>
                                    <td><b>Petitioner Vs. Respondant</b></td>
                                    <td><b>Case Category</b></td>
                                    <td align='center'><b>Status</b></td>
                                    <td align='center'><b>Before/Not Before</b></td>
                                    <td align='center'><b>List</b></td>
                                    <td><b>DA</b></td>
                                </tr>
                                <?php echo $linked_case_listing; ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php echo csrf_field(); ?>
</section>
<?= csrf_field() ?>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button> -->
<div id="model-proposal-form" data-bs-backdrop='static' data-bs-keyboard="false" class="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <?php
        ////Proposal Form
        $editable = 1;
        ?>
        <div id="newb">
            <table width="100%" border="1" style="border-collapse: collapse">
                <tr style="background-color: #A9A9A9;">
                    <td align="center">
                        <b>
                            <font color="black" style="font-size:14px;">Proposal in Diary No. <?php echo $diary_number; ?> - <?php echo $diary_year; ?></font>
                        </b>
                    </td>
                </tr>
            </table>
            <div id="newb123" style="overflow:auto; background-color: #FFF;">
                <table class="table_tr_th_w_clr c_vertical_align" border="1" width="100%" style="border-collapse: collapse">
                    <!--                        <tr>
                                                            <td colspan="3">DO YOU WANT TO RECEIVE THE CASE <input type="checkbox" id="da_case_rec_chkbx" /></td>
                                                        </tr>-->

                    <tr>
                        <td align="right">READY to list before :</td>
                        <td colspan="2">
                            <?php
                            $next_dt = $proposal_form['next_dt'];
                            $is_nmd = $proposal_form['is_nmd'];
                            $r_nr = $proposal_form['r_nr'];
                            $lo = $proposal_form['lo'];
                            $bt = $proposal_form['bt'];
                            $sj = $proposal_form['sj'];

                            $reslt_validate_verification = validate_verification($fil_no['diary_no']);
                            if ($reslt_validate_verification > 0) {
                            ?>
                                <font color='red' style='font-size:16px;'>Verification Pending From IB Section</font><br>
                            <?php
                            }

                            // Get the database connection
                            $db = \Config\Database::connect();

                            // Build the query using the query builder
                            $sqlLastProposed = $db->table('last_heardt')
                                ->select('board_type, next_dt, subhead')
                                ->where('diary_no', $fil_no['diary_no'])
                                ->where('next_dt > CURRENT_DATE') // Equivalent to curdate()
                                ->orderBy('next_dt', 'desc')
                                ->limit(1)
                                ->get();

                            // Handle case where no result was found (optional)
                            $lastProposed = "";
                            $lastListedOn = "";
                            $lastSubHead = "";

                            // Check if any row was returned
                            if ($sqlLastProposed->getNumRows() > 0) {
                                // Fetch the first row and assign values to variables
                                $rowLastProposed = $sqlLastProposed->getRow();
                                $lastProposed = $rowLastProposed->board_type;
                                $lastListedOn = $rowLastProposed->next_dt;
                                $lastSubHead = $rowLastProposed->subhead;
                            }
                            ?>


                            <!--<select size="1" name="jrc" id="jrc" onChange="javascript:get_tentative_date(); get_subheading();changeNumJudge();checkInAdvance(); " >
                                            <option value="" <?php /*if($bt=="") echo "selected=selected";*/ ?> > Select </option>
                            --> <?php
                            if ($reslt_validate_verification == 0) {  ?>
                                <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "J") echo "checked"; ?> value="J" onclick="jrc_changed()"> Judge</label>
                                <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "S") echo "checked"; ?> value="S" onclick="jrc_changed()"> Single Judge</label>
                            <?php }  ?>
                            <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "C") echo "checked"; ?> value="C" onclick="jrc_changed()"> Chamber</label>
                            <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "R") echo "checked"; ?> value="R" onclick="jrc_changed()"> Registrar</label>

                            <!-- No. of Sitting Judges :-->
                            <select name="sj" id="sj" hidden>
                                <option value="1" <?php if ($sj == 1) echo "selected=selected"; ?>>1</option>
                                <option value="2" <?php if ($sj == 2) echo "selected=selected"; ?>>2</option>
                                <option value="3" <?php if ($sj == 3) echo "selected=selected"; ?>>3</option>
                                <option value="4" <?php if ($sj == 4) echo "selected=selected"; ?>>4</option>
                                <option value="5" <?php if ($sj == 5) echo "selected=selected"; ?>>5</option>
                                <option value="6" <?php if ($sj == 6) echo "selected=selected"; ?>>6</option>
                                <option value="7" <?php if ($sj == 7) echo "selected=selected"; ?>>7</option>
                                <option value="8" <?php if ($sj == 8) echo "selected=selected"; ?>>8</option>
                                <option value="9" <?php if ($sj == 9) echo "selected=selected"; ?>>9</option>
                                <option value="10" <?php if ($sj == 10) echo "selected=selected"; ?>>10</option>
                                <option value="11" <?php if ($sj == 11) echo "selected=selected"; ?>>11</option>
                                <option value="12" <?php if ($sj == 12) echo "selected=selected"; ?>>12</option>
                                <option value="13" <?php if ($sj == 13) echo "selected=selected"; ?>>13</option>
                                <option value="14" <?php if ($sj == 14) echo "selected=selected"; ?>>14</option>
                                <option value="15" <?php if ($sj == 15) echo "selected=selected"; ?>>15</option>
                            </select>
                        </td>
                        <td><input type="hidden" value="<?php echo $lastProposed; ?>" name="lastProposed" id="lastProposed"></td>
                        <td><input type="hidden" value="<?php echo $lastListedOn; ?>" name="lastListedOn" id="lastListedOn"></td>
                        <td><input type="hidden" value="<?php echo $lastSubHead; ?>" name="lastSubHead" id="lastSubHead"></td>
                        <td><input type="hidden" name="usercode" id="usercode" value="<?= $ucode ?>"></td>
                    </tr>
                    <tr valign="top">
                        <td align="right">Purpose of Listing : </td>
                        <td align="left" colspan="2">
                            <select size="1" name="listorder" id="listorder" onChange="javascript:get_tentative_date(); chg_def1();"> <?php // print $t_ed; 
                                                                                                                                        ?>
                                <option value="">Select</option>
                                <?php
                                    // $diary_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
                                    //                         $sql_list = "select next_dt from heardt where diary_no=$diary_no and board_type='J' and clno!=0 and clno is not null and brd_slno is not null and brd_slno!=0 and roster_id!=0 and roster_id is not null
                                    // union
                                    // select next_dt from last_heardt where diary_no=$diary_no and board_type='J' and clno!=0 and clno is not null and brd_slno is not null and brd_slno!=0 and roster_id!=0 and roster_id is not null and (bench_flag is null or trim(bench_flag)='')";
                                    //                         $result_list = mysql_query($sql_list);
                                    //                         $row_list = mysql_num_rows($result_list);

                                    // Get the database connection
                                    // $db = \Config\Database::connect();

                                    // First SELECT query for the `heardt` table
                                    $query1 = $db->table('heardt')
                                        ->select('next_dt')
                                        ->where('diary_no', $diary_no)
                                        ->where('board_type', 'J')
                                        ->where('clno !=', 0)
                                        ->where('clno IS NOT NULL')
                                        ->where('brd_slno IS NOT NULL')
                                        ->where('brd_slno !=', 0)
                                        ->where('roster_id !=', 0)
                                        ->where('roster_id IS NOT NULL');

                                    // Second SELECT query for the `last_heardt` table
                                    $query2 = $db->table('last_heardt')
                                        ->select('next_dt')
                                        ->where('diary_no', $diary_no)
                                        ->where('board_type', 'J')
                                        ->where('clno !=', 0)
                                        ->where('clno IS NOT NULL')
                                        ->where('brd_slno IS NOT NULL')
                                        ->where('brd_slno !=', 0)
                                        ->where('roster_id !=', 0)
                                        ->where('roster_id IS NOT NULL')
                                        ->groupStart()  // Start a grouped condition
                                            ->where('bench_flag IS NULL')
                                            ->orWhere('TRIM(bench_flag)', '')
                                        ->groupEnd();  // End the grouped condition

                                    // Combine the two queries using UNION
                                    $query_list = $query1->union($query2);

                                    // Execute the query and get the result
                                    $results = $query_list->get();

                                    // Get the number of rows returned
                                    $row_list = $results->getNumRows();


                                    // $sql_lp1 = "SELECT code, CONCAT(code,'. ',purpose) AS lp FROM listing_purpose WHERE code!=22 and purpose!='NULL' AND display='Y' ORDER BY code";
                                    // $results_lp1 = mysql_query($sql_lp1);

                                    // Build the query using CodeIgniter 4's query builder
                                    $sql_lp1 = $db->table('master.listing_purpose')
                                        ->select("code, CONCAT(code, '. ', purpose) AS lp")
                                        ->where('code !=', 22)
                                        ->where('purpose IS NOT NULL')
                                        ->where('display', 'Y')
                                        ->orderBy('code')
                                        ->get();

                                    // Check if any rows are returned
                                    if ($sql_lp1->getNumRows() > 0) {
                                        foreach ($sql_lp1->getResultArray() as $row_lp1) {
                                        if ($row_list > 0 and $row_lp1["code"] == 32 and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))))
                                            $temp_check = " disabled=disabled ";

                                        else if (($row_lp1["code"] == 24 or $row_lp1["code"] == 2  or $row_lp1["code"] == 48) and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) //fresh or $row_lp1["code"]==32
                                            $temp_check = " disabled=disabled ";
                                        else if (($row_lp1["code"] == 49 or $row_lp1["code"] == 5  or ($mainhead_kk == 'F' and ($row_lp1["code"] == 4))) and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))))
                                            $temp_check = " disabled=disabled "; //OR ($link['c_type'] != 'M' AND ($row_lp1["code"]==4 OR $row_lp1["code"]==7 OR $row_lp1["code"]==8) modified on 11.02.2019)
                                        else
                                            $temp_check = " ";
                                        if ($lo == $row_lp1["code"])
                                            echo '<option value="' . $row_lp1["code"] . '" selected="selected" ' . $temp_check . '>' . $row_lp1["lp"] . '</option>';
                                        else
                                            echo '<option value="' . $row_lp1["code"] . '"' . $temp_check . '>' . $row_lp1["lp"] . '</option>';
                                    }
                                }
                                if ($listorder == 22)
                                    echo '<option value="22" selected="selected">REGISTRAR AUTHENTICATED</option>';
                                ?>
                            </select>&nbsp;
                            (Regular hearing Court orders : list on/next week/after week etc. may update by previous court remark module)
                            <?php
                            if ($listorder == 22)
                                echo "<br><font color='red'>REGISTRAR AUTHENTICATED</font>&nbsp;&nbsp;";
                            if ($lastorder != "" and $fhc1 == "")
                                echo "<br>Last Order: <font color='red'>" . $lastorder . "</font>&nbsp;&nbsp;";
                            if ($fhc1 != "")
                                echo "<br>" . $fhc1;
                            ?>
                        </td>
                    </tr>
                    <?php
                    $future_dates = "";
                    $q_next_dt = date("Y-m-d", strtotime($next_dt));
                    // $result_future_dates = mysql_query("select group_concat(distinct next_dt) as dates from cl_printed where display='Y' and next_dt>date(now())");
                    // if (mysql_num_rows($result_future_dates) > 0)
                    //     $future_dates = mysql_result($result_future_dates, 0, "dates");
                    // // echo $future_dates;

                    // Build the query using the Query Builder
                    $query = $db->table('cl_printed')
                        ->select("string_agg(DISTINCT next_dt::text, ',') AS dates")  // string_agg in PostgreSQL
                        ->where('display', 'Y')
                        ->where('next_dt >', date('Y-m-d'))  // `next_dt > current_date` equivalent
                        ->get();

                    // Check if any results were returned
                    if ($query->getNumRows() > 0) {
                        // Retrieve the result (the first row)
                        $future_dates = $query->getRow()->dates;
                    }


                    $nextmonday = "";
        //             mysql_query("SET @sr:=0;");
        //             if ($q_next_dt > date("Y-m-d")) {
        //                 $result_nm = mysql_query("SELECT date_format(working_date,'%d-%m-%Y') newdate FROM
        // sc_working_days WHERE display = 'Y' and is_holiday = 0 and is_nmd = 0 and
        // working_date >= '$q_next_dt' order by working_date asc LIMIT 1;");
        //             } else {
        //                 $result_nm = mysql_query("SELECT date_format(working_date,'%d-%m-%Y') newdate FROM
        // sc_working_days WHERE display = 'Y' and is_holiday = 0 and is_nmd = 0 and
        // working_date > date_add(curdate(), interval 28 day)  order by working_date asc LIMIT 1;");
        //             }
        //             if (mysql_num_rows($result_nm) > 0) {
        //                 $nextmonday = mysql_result($result_nm, 0, "newdate");
        //             }

                    // Check if $q_next_dt is greater than today's date
                    if ($q_next_dt > date("Y-m-d")) {
                        // Build the query to fetch the next working date
                        $result_nm = $db->table('master.sc_working_days')
                            ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                            ->where('display', 'Y')
                            ->where('is_holiday', 0)
                            ->where('is_nmd', 0)
                            ->where('working_date >=', $q_next_dt)
                            ->orderBy('working_date', 'asc')
                            ->limit(1)
                            ->get();
                    } else {
                        // Build the query for 28 days after the current date
                        $result_nm = $db->table('master.sc_working_days')
                            ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                            ->where('display', 'Y')
                            ->where('is_holiday', 0)
                            ->where('is_nmd', 0)
                            ->where("working_date > (CURRENT_DATE + 28)")
                            ->orderBy('working_date', 'asc')
                            ->limit(1)
                            ->get();
                    }

                    // Check if any rows were returned
                    if ($result_nm->getNumRows() > 0) {
                        // Get the next working date
                        $nextmonday = $result_nm->getRow()->newdate;
                    }

                    /*                      else {
                                                        if($q_next_dt > date('Y-m-d')){
                                                            $res_nm = mysql_query("SELECT DATE_ADD('$q_next_dt', INTERVAL (9 - DAYOFWEEK('$q_next_dt')) DAY) as nm;");
                                                        }
                                                        else {
                                                            $res_nm = mysql_query("SELECT DATE_ADD(CURDATE(), INTERVAL (9 - DAYOFWEEK(CURDATE())) DAY) as nm;");
                                                        }
                                                        $nextmonday = mysql_result($res_nm, 0, "nm");
                                                    }*/
                    $nexttuesday = "";
                    // mysql_query("SET @sr:=0;");

                    /*
                                                                    if($q_next_dt > date("Y-m-d")){
                                                                        mysql_query("SET @newdate:=DATE_ADD('$q_next_dt', INTERVAL (10 - DAYOFWEEK('$q_next_dt')) DAY);");
                                                                    }
                                                                    else{
                                                                        mysql_query("SET @newdate:=DATE_ADD(CURDATE(), INTERVAL (10 - DAYOFWEEK(CURDATE())) DAY);");
                                                                    }*/


        //             if ($q_next_dt > date("Y-m-d")) {
        //                 $result_nm = mysql_query("SELECT date_format(working_date,'%d-%m-%Y') newdate FROM
        // sc_working_days WHERE display = 'Y' and is_holiday = 0 and DAYOFWEEK(working_date)=3 and
        // working_date >= '$q_next_dt' order by working_date asc LIMIT 1;");   //removed is_nmd=1 and added DAYOFWEEK(working_date)=3 by preeti on 30.4.2024
        //             } else {
        //                 $result_nm = mysql_query("SELECT date_format(working_date,'%d-%m-%Y') newdate FROM
        // sc_working_days WHERE display = 'Y' and is_holiday = 0 and DAYOFWEEK(working_date)=3 and
        // working_date > date_add(curdate(), interval 28 day)  order by working_date asc LIMIT 1;");  //removed is_nmd=1 and added DAYOFWEEK(working_date)=3 by preeti on 30.4.2024
        //             }
        //             if (mysql_num_rows($result_nm) > 0) {
        //                 $nexttuesday = mysql_result($result_nm, 0, "newdate");
        //             }

                    // Check if $q_next_dt is greater than today's date
                    if ($q_next_dt > date("Y-m-d")) {
                        // Build the query to fetch the next Wednesday
                        $result_nm = $db->table('master.sc_working_days')
                            ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                            ->where('display', 'Y')
                            ->where('is_holiday', 0)
                            ->where('EXTRACT(DOW FROM working_date)', 3)  // Find Wednesday (DOW = 3)
                            ->where('working_date >=', $q_next_dt)
                            ->orderBy('working_date', 'asc')
                            ->limit(1)
                            ->get();
                    } else {
                        // Build the query for 28 days after the current date for the next Wednesday
                        $result_nm = $db->table('master.sc_working_days')
                            ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                            ->where('display', 'Y')
                            ->where('is_holiday', 0)
                            ->where('EXTRACT(DOW FROM working_date)', 3)  // Find Wednesday (DOW = 3)
                            ->where('working_date > (CURRENT_DATE + 28)')
                            ->orderBy('working_date', 'asc')
                            ->limit(1)
                            ->get();
                    }

                    // Check if any rows were returned
                    if ($result_nm->getNumRows() > 0) {
                        // Get the next Wednesday date
                        $nexttuesday = $result_nm->getRow()->newdate;
                    }

                    /*else {
                                    if($q_next_dt > date('Y-m-d')){
                                        $res_nm = mysql_query("SELECT DATE_ADD('$q_next_dt', INTERVAL (10 - DAYOFWEEK('$q_next_dt')) DAY) as nt;");
                                    }
                                    else {
                                        $res_nm = mysql_query("SELECT DATE_ADD(CURDATE(), INTERVAL (10 - DAYOFWEEK(CURDATE())) DAY) as nt;");
                                    }
                                    $nexttuesday = mysql_result($res_nm, 0, "nt");
                                }*/
                    ?>

                    <tr>
                        <td align="right">Proposed Listing Date : </td>
                        <td>
                            <?php
                            $pdate = '';
                            // echo $next_dt."-".$tentative_date;
                            if ($next_dt != "" and $next_dt != "0000-00-00")
                                $pdate = date('d-m-Y', strtotime($next_dt));
                            if ($tentative_date != "" and $tentative_date != "0000-00-00")
                                if (strtotime($tentative_date) > strtotime($next_dt))
                                    $pdate = date('d-m-Y', strtotime($tentative_date));
                            //echo $pdate;

                            if (date("Y", strtotime($tentative_date)) == 2077) {
                                $tomorrow = strtotime('+1 day');
                                $pdate = date('d-m-Y', $tomorrow);
                            }

                            /*                                if(($lo==16 or $lo==2) and $mainhead_kk == "M" and (strtotime($tentative_date) < strtotime(date("d-m-Y"))))
                                                                            $t_pdate=$nextmonday;
                                                                        else if(($lo==16 or $lo==2) and $mainhead_kk == "F" and (strtotime($tentative_date) < strtotime(date("d-m-Y"))))
                                                                            $t_pdate=$nexttuesday;
                                                                        else
                                                                            $t_pdate=$pdate;
                                                                        if($mainhead_kk == "M" and (strtotime($tentative_date) < strtotime(date("d-m-Y"))))
                                                                            $t_pdate=$nextmonday;
                                                                        else if($mainhead_kk == "F" and (strtotime($tentative_date) < strtotime(date("d-m-Y"))))
                                                                            $t_pdate=$nexttuesday;
                                                                        else  */
                            $t_pdate = $pdate;
                            if ($listorder == 4 or $listorder == 5 or $listorder == 7)
                                $editable = 0;

                            if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) {
                                $allow = 1;
                                if ($editable == 0) {
                            ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>" size="15" onchange="checkFutureDate();checkInAdvance();" onload="">&nbsp;(dd-mm-yyyy)
                                    <!--<br>[<font color="green">Tentative Date : <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                <?php
                                } else if ($t_pdate == '' or $t_pdate == '00-00-0000' or $tentative_date == '0000-00-00' or trim($tentative_date) == '') {

                                ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>"
                                        onchange="checkFutureDate();checkInAdvance();" size="15">&nbsp;(dd-mm-yyyy)&nbsp;
                                    <!--[<font color="green">Tentative Date: <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                <?php
                                } else {

                                ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>"
                                        onchange="checkFutureDate();checkInAdvance();" size="15" disabled="disabled">&nbsp;(dd-mm-yyyy)&nbsp;
                                    <!--[<font color="green">Tentative Date: <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                    <input class="dtp" type="hidden" name="prev_thdate" id="prev_thdate" value="<?php echo $t_pdate; ?>" size="15">
                                <?php
                                }
                            } else {
                                $allow = 0;
                                ?> <input class="dtp" type="text" name="thdate" id="thdate" value="" size="15" onchange="checkFutureDate();checkInAdvance();">
                                <input class="dtp" type="hidden" name="prev_thdate" id="prev_thdate" value="<?php echo $t_pdate; ?>" size="15">
                            <?php

                            }
                            ?>

                            <input type="hidden" name="thdate_h" id="thdate_h" value="<?php echo $pdate; ?>">
                            <input type="hidden" name="thdate_nm" id="thdate_nm" value="<?php echo $nextmonday; ?>">

                        </td>
                        <td>
                            <?php if (($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))) || (($ucode == 1504 || $ucode == 94) and (($row_sensitive != null and $row_sensitive != '') or ($row_PIP != null and $row_PIP != '')))) { ?>
                                <select name="r_nr" id="r_nr">
                                    <option value="R" <?php if ($r_nr != 3) echo "selected=selected"; ?>>READY</option>

                                    <option value="NR" <?php if ($r_nr == 3)  echo "selected=selected"; ?>>NOT READY</option>
                                </select>
                            <?php } else { ?>
                                <select name="r_nr" id="r_nr" hidden>
                                    <option value="R" <?php if ($r_nr != 3) echo "selected=selected"; ?>>READY</option>
                                </select>

                            <?php   } ?>

                        </td>
                        <td><input type="hidden" name="future_date" id="future_date" value=<?php echo $future_dates; ?>></td>
                    </tr>
                    <tr>
                        <td align="right">Hearing Head :</td>
                        <?php
                        $t11 = "";
                        // //HABEAS
                        // //if ($category == 139 and $subcat == 0 and $subcat1 == 0 and $mfvar == "M") {
                        // //    $sql_po = mysql_query("select 810 as mul_sub_hd, 'M' as mainhead");
                        // //} else {
                        // $sql_po = mysql_query("select mainhead from heardt where diary_no=" . $fil_no['diary_no']) or die(mysql_error());
                        // //}
                        // $row_h = mysql_fetch_array($sql_po);
                        // //$_res_sql_po = $row_h["mul_sub_hd"];
                        // $t11 = $row_h["mainhead"];

                        // Query to fetch the mainhead based on the diary_no
                        $query = $db->table('heardt')
                            ->select('mainhead')
                            ->where('diary_no', $fil_no['diary_no'])
                            ->get();

                        // Check if any row was returned
                        if ($query->getNumRows() > 0) {
                            // Fetch the row and get the mainhead value
                            $row_h = $query->getRow();
                            $t11 = $row_h->mainhead;  // Accessing the mainhead field
                        }
                        ?>
                        <td align="left">
                            <!--                    <select size="1" name="mf_select" id="mf_select" onChange="javascript:get_subheading();  get_max_fin_m(this.value);" <?php //if($_res_sql_po!='') {   
                                                                                                                                                                            ?>disabled="true" <?php //}   
                                                                                                                                                                                                            ?>>-->
                            <select size="1" name="mf_select" id="mf_select" onChange="subheading_change()">
                                <option value="M" <?php if ($t11 == "M") echo "selected"; ?>>Miscellaneous Hearing</option>
                                <option value="F" <?php if ($t11 == "F") echo "selected"; ?>>Regular Hearing</option>
                            </select>&nbsp;&nbsp;
                        </td>
                        <td>
                            <?php
                            if ($main_fh_fil_no == '') {
                            ?>
                                <div class="fh_error" style="display:none;">
                                    <font color="red">Check whether Direct Appeal or Not. If Not inform Computer Cell</font>
                                </div>
                            <?php
                            }



                            //                            if ($judge1 == "527")
                            //                                $t_checked = " checked=checked";
                            // <div class="fh_error" style="display:none;"><font color="red">Case is not registered in Regular Hearing (If Registration not required then ignore)</font></div>
                            // $t_check='<div class="fh_error" style="display:none;"><font color="red">Check whether Direct Appeal or Not. If Not inform Computer Cell</font></div>';

                            //                            else
                            //                                $t_checked = "";
                            ?>
                            <!--<label><input type="checkbox" name="legalaid" id="legalaid" value="LAID" <?php print $t_checked; ?>/>LEGAL AID</label>-->
                        </td>

                    </tr>
                    <?php
                    //                        if($check_for_regular_case==''){
                    ?>
                    <!--<tr id="case_for_final_div" style="display:none;">
                                    <td align="right">Select Case Type :</td>
                                    <td colspan="2">
        <select size="1" name="case_for_final" id="case_for_final" >
        <option value="">Select</option><?php

                            // Build the query using the query builder
                            $query = $db->table('master.casetype')
                                ->select('casecode, skey, casename, short_description')
                                ->where('display', 'Y')
                                ->where('casecode !=', 9999)
                                ->orderBy('short_description')
                                ->get();

                            // Loop through the results
                            foreach ($query->getResultArray() as $ct_rw) {


                                        // $ct_q = "SELECT casecode, skey, casename,short_description FROM casetype WHERE display = 'Y' AND casecode!=9999 ORDER BY short_description";
                                        // $ct_rs = mysql_query($ct_q) or die(mysql_error());
                                        // while ($ct_rw = mysql_fetch_array($ct_rs)) {
                                        ?>
                                    <option value="<?php echo $ct_rw['casecode'] ?>"><?php echo $ct_rw['short_description']; ?></option>
                            <?php } ?>
                            </select>&nbsp;(for new case no. in Regular Hearing)
                                    </td>
        </tr>-->
                    <?php
                    //}
                    ?>
                    <tr valign="top">
                        <td align="right">Case Category :</td>
                        <td align="left" colspan="2"><?php echo $mul_category; ?></td>
                    </tr>
                    <?php
                    $bf = "";
                    $nbf = "";
                    if ($bf != "")
                        $bf = "<tr><td width='110px'><b><u>LIST BEFORE</u></b> : </td><td><font color='green'><b>" . $bf . "</b></font></td></tr>";
                    if ($nbf != "")
                        $nbf = "<tr><td width='110px'><b><u>NOT LIST BEFORE</u></b> : </td><td><font color='red'><b>" . $nbf . "</b></font></td></tr>";
                    if ($bf != "" or $nbf != "")
                        $pr_bf = "<table>" . $bf . $nbf . "</table>";
                    if ($benchmain == "S") {
                        if ($judge1 > 0)
                            $t_jud1 = $judge1;
                        else
                            $t_jud1 = "250";
                    }
                    if ($benchmain == "D") {
                        if ($judge1 > 0)
                            $t_jud1 = $judge1;
                        else
                            $t_jud1 = "200";
                        if ($judge2 > 0)
                            $t_jud2 = $judge2;
                        else
                            $t_jud2 = "999";
                    }
                    ?>

                    <?php

                    if ($mfvar != $mainhead_new1 and $mainhead_new1 != '') {
                    ?>

                        <tr valign="top">
                            <td align="right">Pre. Heading :</td>
                            <td align="left"><b>
                                    <font color='blue'><?php echo $mainhead_new1 . " [" . $subhead_new1 . "]"; ?></font>
                                </b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr valign="top">
                        <td align="right">Sub Heading :</td>
                        <td align="left" colspan="2">
                            <select size="1" style="width:100%;" name="subhead_select" id="subhead_select">
                            <option value="">SELECT</option>
                            </select>
                        </td>
                    </tr>
                    <!--purpose//-->
                    <tr valign="top">
                        <td align="right">
                            Statutory Information :
                        </td>
                        <td align="left"><b>IAs to be list :</b><span style='color:green; font-weight: bold;' id="ianp_jshow"><?php echo $listed_ia; ?></span><?php echo $ian_p; ?>&nbsp;</td>
                        <td align="left">(Info. regarding IA not to be inserted in the statutary box it will come automatically in the proposal.)
                            <?php
                            // $br = get_brd_remarks($fil_no['diary_no']);
                            echo '<font color=green><b>' . $brdremh . '</b></font>'; ?>
                            <input type="hidden" name="brdremh" id="brdremh" value="<?php echo $brdremh; ?>">
                            <textarea cols="50" name="brdrem" id="brdrem" rows="5" style='width:95%;min-height:75%;'><?php echo $brdremh; ?></textarea>
                        </td>

                    </tr>
                    <?php
                    //echo $conncases."cxzcxzc";
                    if (count($conncases) > 0 and $check_for_conn != 'N') {
                        $lconn = "Y";
                    ?>
                        <tr valign="top">
                            <th align="right">
                                Connected Case :
                            </th>
                            <td align="left" colspan="2">
                                <?php
                                /*
                                //if ($lconn != "Y")
                                //    $lconn = "N";
                                //if ($conncases != "") {
                                //$lconn = "Y";
                                //    }
                                // else {
                                // $lconn = "N";
                                //}
                                ?>
                                <!--                    <select name="conncs" id="conncs" onChange="chk_conncase();" <?php if ($conncntr == 1) echo "disabled=disabled"; ?>>
                            <option value="Y" <?php if ($lconn == "Y") echo "selected"; ?>>Y</option>
                            <option value="N" <?php if ($lconn == "N") echo "selected"; ?>>N</option>
                            </select>-->
                            <?php */ ?>
                                <br>
                                <div id="conncasediv" <?php
                                                        if ($lconn == "Y")
                                                            echo "style='display:block;'";
                                                        else
                                                            echo "style='display:none;'";
                                                        ?>>
                                    <?php echo $connchks; ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    } else {
                        $lconn = "N";
                    }
                    ?>
                    <tr bgcolor="#FAFAFE" valign="top">
                        <td height="100%" style="bottom:0">&nbsp;
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>

                </table>
            </div>
            <div id="newb1" align="center">
                <input type="hidden" name="diaryno" id="diaryno" value="<?php echo $fil_no['diary_no']; ?>">
                <table border="0" width="100%">
                    <tr>
                        <td align="center" width="250px">
                            <input type='button' name='insert1' id='insert1' value="Save" onClick="return check_details();">&nbsp;
                            <input type="button" name="close1" id="close1" value="Cancel" onClick="return close_w()">
                            <input type="hidden" name="tmp_casenop" id="tmp_casenop" value="" />
                        </td>
                    </tr>
                </table>
            </div>

        </div>

</div>
  </div>
</div>


<?php
////Proposal Form end
?>
<div id="newcs" style="display:none;">
    <table width="100%" border="0" style="border-collapse: collapse">
        <tr style="background-color: #A9A9A9;">
            <td align="center">
                <b>
                    <font color="black" style="font-size:14px;">Case Status</font>
                </b>
            </td>
            <td>
                <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_wcs();" />
            </td>

        </tr>
    </table>
    <div id="newcs123" style="overflow:auto; background-color: #FFF;">
    </div>
    <div id="newcs1" align="center">
        <table border="0" width="100%">
            <tr>
                <td align="center" width="250px">
                </td>
            </tr>
        </table>
    </div>
</div>

<input type="hidden" name="sh" id="sh" value="<?php if ($lastSubHead == '') print $subhead;
                                                else print $lastSubHead; ?>" />
<input type="hidden" name="da_hidden" id="da_hidden" value="<?php echo ''; ?>" />
<input type="hidden" name="ucode" id="ucode" value="<?php echo $ucode; ?>" />
<input type="hidden" name="check_for_regular_case" id="check_for_regular_case" value="<?php echo $check_for_regular_case; ?>" />

<script>
var excluded_dates=<?php echo json_encode($holiday_dates) ?>;
$(function() {
    var date = new Date();
    date.setDate('<?php echo $t_pdate; ?>');
    $('.dtp').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        // startDate: date,
        todayHighlight: true,
        changeMonth : true, 
        changeYear : true,
        minDate:'+1',
        yearRange : '-0:+1',
        datesDisabled: excluded_dates,
        isInvalidDate: function(date) {
            return (date.day() == 0 || date.day() == 6);
        },
    });
});
   /*var excluded_dates=<?php //echo json_encode($holiday_dates) ?>;
    $(document).on("focus",".dtp",function() 
    {

        console.log(excluded_dates);

        $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,minDate:'+1', yearRange: '-0:+1',beforeShowDay: function(date) 
            {
                console.log(date);

                date = $.datepicker.formatDate('yy-mm-dd', date);
                
                console.log(date);

                var excluded = $.inArray(date, excluded_dates) > -1;
                return [!excluded, ''];
            }
        });
    });*/
</script>
<script type="text/javascript">
    function check_details() {
        var ucode = '<?php echo $ucode ?>';
        var r_nr = document.getElementById('r_nr').value;
        if (r_nr == 'R' && (ucode == '1504' || ucode == '94')) {
            if (confirm("Matter will be Proposed for Listing.Do you want to propose the matter?")) {
                return check_proposal();
            }
        } else
            return check_proposal();
    }

    function checkInAdvance() {
        var inAdvance = '<?php echo $reslt_validate_caseInAdvanceList ?>';
        var inAdvanceSingle = '<?php echo $reslt_validate_caseInAdvanceListSingleJudge ?>';
        var infinal = '<?php echo $result_caseInFinalList ?>';
        var infinalSingle = '<?php echo $result_caseInFinalListSingleJudge ?>';
        var allowed = '<?php echo $allowed ?>';
        var noticeissued = '<?php echo $noticeissued ?>';
        if (inAdvance == true && noticeissued == 0) {
            alert("Case Listed in Advance List.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (infinal == true && noticeissued == 1) {
            alert("Case Listed in Final List.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (inAdvanceSingle == true && noticeissued == 0) {
            alert("Case Listed in Advance List before Single Judge.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (infinalSingle == true && noticeissued == 1) {
            alert("Case Listed in Final List before Single Judge.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        }
    }


    function checkFutureDate() {
        var date1 = document.getElementById('thdate').value;
        /* added on 30.11.2018 */
        if (date1 == '')
            date1 = '<?php echo $t_pdate; ?>';
        /* end */
        var future_date = document.getElementById('future_date').value;
        var usercode = '<?php echo $ucode; ?>';
        var user_updation = '<?php echo $user_case_updation['always_allowed_users']; ?>';
        user_updation = user_updation.split(",");
        date1 = date1.split('-')[2] + "-" + date1.split('-')[1] + "-" + date1.split('-')[0];
        future_date = future_date.split(",");
        /*
         for(var j=0;j<=user_updation.length;j++){
            if(usercode!=user_updation[j] ) {

        */
        if (!user_updation.includes(usercode)) {
            for (var i = 0; i <= future_date.length; i++) {
                if (date1 == future_date[i]) {
                    alert("Cause List has been published for the date. Please select other future date!");
                    document.getElementById('thdate').value = "";
                    break;
                }

            }
        }
    }

    function hearingHeadChange() {

        var listorder = document.getElementById('listorder').value;
        var hearingHead = document.getElementById('mf_select').value;
        var category = '<?php echo $category_id; ?>';
        var is_nmd = '<?php echo $is_nmd; ?>';

        //  var short_category_id = "343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222"; //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        //   short_category_id=short_category_id.split(","); //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        if (hearingHead == 'F' && listorder != 4 && listorder != 5)
            document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
        else if (hearingHead == 'M' && listorder != 4 && listorder != 5) {
            /*   for (var i = 0; i <= short_category_id.length; i++) {  //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
                if(category==short_category_id[i].trim())
                {
                    document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
                    break;
                }
                else */
            if (is_nmd == 'Y') {
                document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
                //break;
            } else {
                document.getElementById('thdate').value = '<?php echo $nextmonday; ?>';
            }
            //  }
        }
    }

    function changeNumJudge() {
        var option = $("input[name='jrc']:checked").val();
        var allow = '<?php echo $allow; ?>';
        if (option == 'J') {
            document.getElementById('sj').value = 2;
            if (allow == 0)
                document.getElementById('thdate').hidden = true;
        } else
            document.getElementById('sj').value = 1;

        var category = '<?php echo $mul_category; ?>';
        if ((option == 'J' || option == 'S') && category == '') { //Condition modified by Preeti Agrawal on 17062022. Added condition for Single Judge also
            alert("Subject Category not updated. Hence, case cannot be proposed for listing in Hon'ble Court");
            document.getElementById('insert1').hidden = true;
        } else
            document.getElementById('insert1').hidden = false;
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function subheading_change() {
        await get_tentative_date();
        await get_subheading();
        checkInAdvance();
    }

    async function jrc_changed() {
        $("input[name='jrc']").prop('disabled', true);
        await get_tentative_date();
        await sleep(500);
        await get_subheading();
        changeNumJudge();
        checkInAdvance();
        $("input[name='jrc']").prop('disabled', false);
    }

    $(document).ready(function() {

        var option = $("input[name='jrc']:checked").val();
        if (option == 'J')
            document.getElementById('sj').value = 2;
        else
            document.getElementById('sj').value = 1;

        var category = '<?php echo $mul_category; ?>';
        if ((option == 'J' || option == 'S') && category == '') { //Condition modified by Preeti Agrawal on 17062022. Added condition for Single Judge also
            // alert("Subject Category not updated. Hence, case cannot be proposed for listing in Hon'ble Court");
            document.getElementById('insert1').hidden = true;
        } else {
            document.getElementById('insert1').hidden = false;
        }

        // Make default click
        // jrc_changed();
    });
</script>