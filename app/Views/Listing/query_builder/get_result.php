<?php
$db = \Config\Database::connect();
 extract($_POST);
 $status_query_merge = '';
 $judge_where= '';
 $judge_orwhere = '';
 $category_exclude = '';
 $category_not = '';
 $case_type_exclude = '';
 $case_type_not = '';
 $section_exclude = '';
 $section_not = '';
 $da_exclude = '';
 $da_not= '';
 $subhead_exclude = '';
 $subhead_not= '';
 $lp_exclude='';
 $lp_not = '';
 $list_after_vacation= '';
 $list_after_vacation_string3 = '';
 $sensitive_join = '';
 $sensitive_where = '';
 $conditional_matter_join = '';
 $diary_date_where = '';
 $status_where = $category_where = $section_where = $da_where = $subhead_where = $lp_where = $coram_by_cji_where = 
 $conditional_matter_where = $sensitive_where = $cav_or_list_after_vacation_where = $part_heard_where = $judge_where;
 $connected_where = $tentatvie_list_date_where = $case_type_where = $mainhead_where = $board_type_where = '';
 $select_columns = $table_column_name = $seniority_number_column = $category2_join = $inner_left_join = '';
 
if ($flag == "report") {
    //Search by Diary date
    if (!empty($from_list_date) && !empty($to_list_date)) {
        $tentatvie_list_date_where = " and date(h.next_dt) between '" . date("Y-m-d", strtotime($from_list_date)) . "' and '" . date("Y-m-d", strtotime($to_list_date)) . "'";
    }


    if (!empty($from_diary_date) && !empty($to_diary_date)) {
        $diary_date_where = " and date(m.diary_no_rec_date) between '" . date("Y-m-d", strtotime($from_diary_date)) . "' and '" . date("Y-m-d", strtotime($to_diary_date)) . "'";
    }
    if (!empty($mainhead)) {
        if ($mainhead == 'M') {
            $mainhead_where = " and h.mainhead = 'M'";
        }
        if ($mainhead == 'F') {
            $mainhead_where = " and h.mainhead = 'F'";
        }
    }
    if (!empty($board_type)) {
        if ($board_type == 'J') {
            $board_type_where = " and h.board_type = 'J'";
        }
        if ($board_type == 'S') {
            $board_type_where = " and h.board_type = 'S'";
        }
        if ($board_type == 'C') {
            $board_type_where = " and h.board_type = 'C'";
        }
        if ($board_type == 'R') {
            $board_type_where = " and h.board_type = 'R'";
        }
    }
    if (!empty($connected)) {
        if (in_array(1, $connected)) {
            $connected_where = " AND (m.conn_key = m.diary_no::text OR m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0')";
        }
    }


    if (!empty($status)) {
        $status_where = "and (";
        if (in_array(1, $status)) { //UPDATE CASES
            $status_query_merge .= "h.main_supp_flag = 0";
        }
        if (in_array(2, $status)) { //UPDATION AWAITED CASES
            if (!empty($status_query_merge)) {
                $status_query_merge .= " OR ";
            }
            $status_query_merge .= "(h.main_supp_flag IN (1,2) AND h.next_dt < CURRENT_DATE)";
        }
        if (in_array(3, $status)) { //NOT READY CASES
            if (!empty($status_query_merge)) {
                $status_query_merge .= " OR ";
            }
            $status_query_merge .= "h.main_supp_flag = 3";
        }
        if (in_array(4, $status)) { //LISTED IN FUTURE DATES
            if (!empty($status_query_merge)) {
                $status_query_merge .= " OR ";
            }
            $status_query_merge .= "(h.main_supp_flag IN (1,2) and h.next_dt >= CURRENT_DATE)";
        }
        $status_where .= $status_query_merge . ")";
    }

    if (!empty($judge)) {
        if ($judge_exclude == 'y') {
            $judge_not = "not";
        }


        $judge_where .= "and (";
        foreach ($judge as $row => $jcode) {
            if ($row > 0) {
                $judge_orwhere = " OR ";
            }
            if ($only_presiding == 'y') {
                $judge_where .= $judge_orwhere . " $judge_not SPLIT_PART(h.coram, ',', 1) = '$jcode'::text ";
            } else {
                $judge_where .= $judge_orwhere . " $judge_not find_in_set($jcode, h.coram) > 0 ";
            }
        }
        $judge_where .= ")";
    }
    if (!empty($category)) {
        if ($category_exclude == 'y') {
            $category_not = "not";
        }
        $category_where = "and mc.submaster_id $category_not in (" . implode(', ', $category) . ") and mc.display = 'Y'";
    }
    if (!empty($case_type)) {
        if ($case_type_exclude == 'y') {
            $case_type_not = "not";
        }
        $case_type_where = "and m.active_casetype_id $case_type_not in (" . implode(', ', $case_type) . ") ";
    }

    if (!empty($section)) {
        if ($section_exclude == 'y') {
            $section_not = "not";
        }
        $section_where = "and m.section_id $section_not in (" . implode(', ', $section) . ") ";
    }

    if (!empty($da)) {
        if ($da_exclude == 'y') {
            $da_not = "not";
        }
        $da_where = "and m.dacode $da_not in (" . implode(', ', $da) . ") ";
    }


    if (!empty($subhead)) {
        if ($subhead_exclude == 'y') {
            $subhead_not = "not";
        }
        $subhead_where = "and h.subhead $subhead_not in (" . implode(', ', $subhead) . ") ";
    }
    if (!empty($lp)) {
        if ($lp_exclude == 'y') {
            $lp_not = "not";
        }
        $lp_where = "and h.listorder $lp_not in (" . implode(', ', $lp) . ") ";
    }
    if (!empty($coram_by_cji)) {
        if ($coram_by_cji == 'n') {
            $coram_by_cji_where = " and h.list_before_remark != 11 ";
        }
        if ($coram_by_cji == 'y') {
            $coram_by_cji_where = "and h.list_before_remark = 11";
        }
    }

    if (!empty($conditional_matter)) {
        $conditional_matter_join = " LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' ";
        if ($conditional_matter == 'n') {
            $conditional_matter_where = " and rd.fil_no is null ";
        }
        if ($conditional_matter == 'y') {
            $conditional_matter_where = " and rd.fil_no is not null";
        }
    }

    if (!empty($sensitive)) {
        $sensitive_join = " LEFT JOIN sensitive_cases sc on sc.diary_no = m.diary_no and sc.display = 'Y'";
        if ($sensitive == 'n') {
            $sensitive_where = " and sc.diary_no is null ";
        }
        if ($sensitive == 'y') {
            $sensitive_where = " and sc.diary_no is not null";
        }
    }


    if (!empty($cav_matter) or !empty($list_after_vacation)) {
        $cav_or_list_after_vacation_where = " and (";
        if ($list_after_vacation == 'n' or $cav_matter == 'n') {
            $cav_or_list_after_vacation_string1 = " (m.lastorder = '' OR m.lastorder is null OR m.lastorder != '' OR m.lastorder is not null)";
            if ($list_after_vacation == 'n') {
                $cav_or_list_after_vacation_string2 .= " m.lastorder not like '%after vacation%' ";
            }
            if ($list_after_vacation == 'n' and $cav_matter == 'n') {
                $cav_or_list_after_vacation_string2 .= " OR ";
            }
            if ($cav_matter == 'n') {
                $cav_or_list_after_vacation_string2 .= " m.lastorder not like '%Heard & Reserved%' ";
            }
            $cav_or_list_after_vacation_where .= "( $cav_or_list_after_vacation_string2 OR $cav_or_list_after_vacation_string1 ) ";
            if ($list_after_vacation == 'y' or $cav_matter == 'y') {
                $cav_or_list_after_vacation_where .= " OR ";
            }
        }
        if ($list_after_vacation == 'y' or $cav_matter == 'y') {
            if ($list_after_vacation == 'y') {
                $list_after_vacation_string3 .= " m.lastorder like '%after vacation%'";
            }
            if ($cav_matter == 'y' and $list_after_vacation == 'y') {
                $list_after_vacation_string3 .= " OR ";
            }
            if ($cav_matter == 'y') {
                $list_after_vacation_string3 .= " m.lastorder like '%Heard & Reserved%'";
            }
            $cav_or_list_after_vacation_where .= "( $list_after_vacation_string3 )";
        }
        $cav_or_list_after_vacation_where .= " )";
    }

    if (!empty($part_heard)) {
        if ($part_heard == 'n') {
            $part_heard_where = " AND (CASE WHEN h.mainhead = 'M' THEN h.subhead != 824 ELSE mc.submaster_id != 913 END)";
        }
        if ($part_heard == 'y') {
			$part_heard_where = " AND (CASE WHEN h.mainhead = 'M' THEN h.subhead = 824 ELSE mc.submaster_id = 913 END)";
           // $part_heard_where = " and if(h.mainhead = 'M',h.subhead = 824,mc.submaster_id = 913) ";
        }
    }

    //and (h.coram = '' or h.coram is null)
    $sql1 = "select count(distinct m.diary_no) total_cases, STRING_AGG(DISTINCT m.diary_no::text, ',') AS dnos 
    from main m
    inner join heardt h on m.diary_no = h.diary_no
    inner join mul_category mc on mc.diary_no = m.diary_no  
    $conditional_matter_join       
    $sensitive_join
    where c_status = 'P' AND (m.fil_dt IS NOT NULL or m.unreg_fil_dt IS NOT NULL)
    $diary_date_where
    $connected_where
    $tentatvie_list_date_where
    $case_type_where
    $mainhead_where
    $board_type_where    
    $status_where
    $category_where
    $section_where
    $da_where
    $subhead_where
    $lp_where
    $coram_by_cji_where
    $conditional_matter_where
    $sensitive_where
    $cav_or_list_after_vacation_where
    $part_heard_where
    $judge_where
    ";
	
    $query = $db->query($sql1);
    $result1 = $query->getRowArray();
 
    if (!empty($result1)) {
        $row = $result1;		 
    ?>

        <div class="card col-12 p-0 mt-2">

            <div class="card-header bg-info text-white font-weight-bolder"><u>Total Records Found : <?= $row['total_cases']   ?></u>
            </div>
            <div class="card-body">

                <form name="child_form" id="child_form" action="">
				<?= csrf_field() ?>
                    <!--<label for="input_title">Title :</label>-->
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Title</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Title to display in report" id="input_title" name="input_title" autocomplete="on">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Columns</span>
                        </div>
                        <select class="form-control" multiple="multiple" id="add_columns" name="add_columns[]">
                            <option value="case_no_with_dno" selected>Case No. with Diary No.</option>
                            <option value="diary_no">Diary No.</option>
                            <option value="reg_no_display">Registration No. </option>
                            <option value="cause_title" selected>Cause Title</option>
                            <option value="connected_count">No. of Connected</option>
                            <!--<option value="main_connected">Main/Connected</option>-->
                            <option value="section">Section name</option>
                            <option value="da">Dealing Assistant</option>
                            <option value="category">Subject Category</option>
                            <!--<option value="updation_status">Updation Status</option>-->
                            <option value="tentative_date">Tentative Date</option>
                            <option value="coram">Coram</option>
                            <option value="lastorder">Last Order</option>
                            <!-- As per email date 08-02-2024 -->
                            <option value="advocate_name">Advocate Name</option>
                            <option value="notice_date">Notice Date</option>
                            <option value="admitted_on">Admitted On</option>
                        </select>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Sort By</span>
                                </div>
                                <select class="form-control" multiple="multiple" id="sort_by" name="sort_by[]">

                                    <!--<option value="diary_no_rec_date" >Filing Date</option>-->
                                    <!--<option value="reg_no_display">Registration No.</option>-->
                                    <!--<option value="fil_dt">Registration Date</option>-->
                                    <option value="section">Section name</option>
                                    <option value="da">Dealing Assistant</option>
                                    <option value="category">Subject Category</option>
                                    <!--<option value="updation_status">Updation Status</option>-->
                                    <option value="coram">Coram</option>
                                    <!--<option value="case_type">Case Type</option>-->
                                    <option value="tentative_date">Tentative Date</option>
                                </select>






                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" name="button_to_add" id="button_to_add" class="btn btn-gray border-primary py-0">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            <br><br>
                            <button type="button" name="button_to_remove" id="button_to_remove" class="btn btn-gray border-primary py-0">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                        <div class="col-4">
                            <select class="form-control" multiple="multiple" id="sort_by2" name="sort_by2[]">
                                <option value="diary_no" selected="selected">Diary No.</option>
                            </select>
                        </div>
                    </div>





                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">No. of Records</span>
                        </div>
                        <input type="number" class="form-control" name="number_of_rows" id="number_of_rows" placeholder="No. of Rows" value="<?= $row['total_cases'] ?>" min="1" max="1000000" />
                    </div>

                    <div class="row">
                        <div class="col-6 text-left">
                            <button type="button" class="diary_nos btn btn-success" data-dnos="<?= $row['dnos'] ?>">Detail Report</button>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" id="footerButton" class="diary_nos1  btn btn-success" data-dnos="<?= $row['dnos'] ?>">Generate in Causelist format</button>
                        </div>
                    </div>


            </div>



        </div>










        </form>
		
		
		<form id="frmCl" action="<?php echo base_url()?>/Listing/AdvanceListReport/cl" method="get" target="_BLANK">
			 
			<input type="hidden" name="diaryNos[]" value="<?= $row['dnos'] ?>" />
			<input type="hidden" id="cl_input_title" name="input_title" value="" />
			<button type="submit">Submit</button>
		</form>

        </div>


        </div>

    <?php
    } else {
        echo "No Records Found";
    }
    ?>

    <div class="line"></div>
    <?php
}
if ($flag == "report_detail") {


    if ($number_of_rows > 0) {
        $limit = "limit " . $number_of_rows;
    }

    if (!empty($add_columns)) {

        if (in_array('case_no_with_dno', $add_columns)) {
            $select_columns .= "m.diary_no, concat(m.reg_no_display, ' @ ', m.diary_no) case_no_with_dno, ";
            $table_column_name .= "<th>Case No.</th>";
        }
        if (in_array('diary_no', $add_columns)) {
            $select_columns .= "m.diary_no, ";
            $table_column_name .= "<th>Diary No.</th>";
        }
        if (in_array('reg_no_display', $add_columns)) {
            $select_columns .= "m.reg_no_display, ";
            $table_column_name .= "<th>Case No.</th>";
        }
        if (in_array('cause_title', $add_columns)) {
            $select_columns .= "concat(m.pet_name,' Vs. ',m.res_name) as causetitle, ";
            $table_column_name .= "<th>Cause Title</th>";
        }


        if (in_array('coram', $add_columns)) {
            $select_columns .= "ifnull((select group_concat(abbreviation order by judge_seniority separator ', ') from judge where is_retired = 'N' and display = 'Y' and find_in_set(jcode,h.coram)),'') as Coram, ";
            $seniority_number_column = "ifnull((select group_concat(judge_seniority order by judge_seniority separator ', ') from judge where is_retired = 'N' and display = 'Y' and find_in_set(jcode,h.coram)),'99999') as seniority_code, ";
            $table_column_name .= "<th>Coram</th>";
        }

        if (in_array('category', $add_columns)) {
            $category2_join = "inner JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                          inner JOIN submaster s ON mc.submaster_id = s.id AND s.display = 'Y'";
            $select_columns .= "case when (s.category_sc_old is not null and s.category_sc_old!='' and s.category_sc_old!=0) then concat('(',s.category_sc_old,')',s.sub_name1,'-',s.sub_name4) else concat('(',concat(s.subcode1,'',s.subcode2),')',s.sub_name1,'-',s.sub_name4) end as CATEGORY, ";
            $table_column_name .= "<th>Category</th>";
        }
        if (in_array('connected_count', $add_columns)) {
            $select_columns .= "ifnull(cc.total_connected,'0') AS connected_count, ";
            $inner_left_join .= " left join (select n.conn_key,count(*) as total_connected from main m
inner join heardt h on m.diary_no=h.diary_no
inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
group by n.conn_key
) cc on m.diary_no=cc.conn_key";
            $table_column_name .= "<th>No. of Connected</th>";
        }

        if (in_array('tentative_date', $add_columns)) {
            $select_columns .= "date_format(h.next_dt,'%d-%m-%Y') Next_Listing_Dt, ";
            $table_column_name .= "<th>Tentative List Date</th>";
        }

        if (in_array('lastorder', $add_columns)) {
            $select_columns .= "m.lastorder, ";
            $table_column_name .= "<th>Last Order</th>";
        }
        if (in_array('section', $add_columns)) {
            $select_columns .= "tentative_section(m.diary_no) SECTION, ";
            $table_column_name .= "<th>Section</th>";
        }
        if (in_array('da', $add_columns)) {
            $select_columns .= "tentative_da(m.diary_no) DA, ";
            $table_column_name .= "<th>DA</th>";
        }

        if (in_array('advocate_name', $add_columns)) {
            $select_columns .= "group_concat(bar.name) as Advocate_Name, ";
            $inner_left_join .= " left join advocate on advocate.diary_no = m.diary_no and advocate.display = 'Y'
        left join bar on bar.bar_id = advocate.advocate_id and bar.if_aor = 'Y' and bar.isdead = 'N' and bar.if_sen ='N'
";


            $table_column_name .= "<th>Advocate Name</th>";
        }
        if (in_array('notice_date', $add_columns)) {
            $select_columns .= "date_format(crm.cl_date,'%d-%m-%Y') Notice_Date, ";
            $inner_left_join .= " left join case_remarks_multiple crm on crm.diary_no = m.diary_no and crm.r_head in (3,62,181,182,183,184,203) ";
            $table_column_name .= "<th>Notice Date</th>";
        }
        if (in_array('admitted_on', $add_columns)) {
            $select_columns .= "date_format(m.fil_dt_fh,'%d-%m-%Y') Admitted_On, ";
            $table_column_name .= "<th>Admitted On</th>";
        }




        if (empty($select_columns)) {
            echo "Please select atleast one column";
            exit();
        }

        $select_columns = rtrim($select_columns, ', ');
        //$add_columns_fields = "and mc.submaster_id $category_not in (".implode(', ', $category).") and mc.display = 'Y'";
    } else {
        echo "Please select Columns field";
        exit();
    }
    //echo $sort_by;
    if (!empty($sort_by2)) {
        $sort_by_query = " order by ";

        foreach ($sort_by2 as $sort_by_value) {
            if ('diary_no' == $sort_by_value) {
                $sort_by_query .= "CAST(SUBSTR(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 3, 4) AS BIGINT) ASC, CAST(SUBSTR(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS BIGINT) ASC, ";

            }
            if ('section' == $sort_by_value) {
                //if(in_array('section', $sort_by)){
                $sort_by_query .= "SECTION, ";
            }
            if ('da' == $sort_by_value) {
                //if(in_array('da', $sort_by)){
                $sort_by_query .= "DA, ";
            }
            if ('category' == $sort_by_value) {
                //if(in_array('category', $sort_by)){
                $sort_by_query .= "CATEGORY, ";
            }
            if ('coram' == $sort_by_value) {
                //if(in_array('coram', $sort_by)){
                $sort_by_query .= " seniority_code, "; //"Coram, ";
            }
            if('tentative_date' == $sort_by_value){            
                $sort_by_query .= " h.next_dt, "; 
            }
        }



        $sort_by_query = rtrim($sort_by_query, ', ');
    } else {
        echo "Please select Sort by field";
        exit();
    }

    $sql1 = "select $seniority_number_column $select_columns from main m
INNER JOIN heardt h ON m.diary_no = h.diary_no 
$category2_join
$inner_left_join
where m.c_status = 'P' and m.diary_no in ($dnos) 
group by m.diary_no $sort_by_query $limit";
	
	$query = $db->query($sql1);
   // $result1 = $dbo_icmis_read->prepare($sql1);
    $result1 = $query->getResultArray();
    if (!empty($result1)) {
        $srno = 1;
    ?>
        
        <div id="print_area" class="col-12 m-0 p-0">
            <div class="box box-primary" id="tachelist">
                <div class="box-header ptbnull">
                    <h3 class="box-title titlefix"><?= $input_title; ?> (As on <?= date("d-m-Y H:i:s"); ?>)</h3>

                </div>
                <div class="box-body">
                    <div class="table-responsive mailbox-messages">
                        <div class="download_label d-none"><?= $input_title; ?> (As on <?= date("d-m-Y H:i:s"); ?>)</div>
                        <table class="table table-striped table-bordered table-hover example custom-table" id="reportTable2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <?= $table_column_name ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result1 as $row) {
                                    //while ($row = mysql_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $srno++; ?></td>
                                        <?php
                                        if (in_array('case_no_with_dno', $add_columns)) {
                                        ?><td><?= $row['case_no_with_dno']; ?></td>
                                        <?php
                                        }

                                        if (in_array('diary_no', $add_columns)) {
                                        ?><td><?= $row['diary_no']; ?></td>
                                        <?php
                                        }

                                        if (in_array('reg_no_display', $add_columns)) {
                                        ?><td><?= $row['reg_no_display']; ?></td>
                                        <?php
                                        }
                                        if (in_array('cause_title', $add_columns)) {
                                        ?><td><?= $row['causetitle']; ?></td>
                                        <?php
                                        }
                                        if (in_array('coram', $add_columns)) {
                                        ?><td><?= $row['Coram']; ?></td>
                                        <?php
                                        }
                                        if (in_array('category', $add_columns)) {
                                        ?><td><?= $row['CATEGORY']; ?></td>
                                        <?php
                                        }
                                        if (in_array('connected_count', $add_columns)) {
                                        ?><td><?= $row['connected_count']; ?></td>
                                        <?php
                                        }
                                        if (in_array('tentative_date', $add_columns)) {
                                        ?><td><?= $row['Next_Listing_Dt']; ?></td>
                                        <?php
                                        }

                                        if (in_array('lastorder', $add_columns)) {
                                        ?><td><?= $row['lastorder']; ?></td>
                                        <?php
                                        }



                                        if (in_array('section', $add_columns)) {
                                        ?><td><?= $row['SECTION']; ?></td>
                                        <?php
                                        }
                                        if (in_array('da', $add_columns)) {
                                        ?><td><?= $row['DA']; ?></td>
                                        <?php
                                        }

                                        if (in_array('advocate_name', $add_columns)) {
                                        ?><td><?= $row['Advocate_Name']; ?></td>
                                        <?php
                                        }
                                        if (in_array('notice_date', $add_columns)) {
                                        ?><td><?= $row['Notice_Date']; ?></td>
                                        <?php
                                        }
                                        if (in_array('admitted_on', $add_columns)) {
                                        ?><td><?= $row['Admitted_On']; ?></td>
                                        <?php
                                        }
                                        ?>





                                        <!--<td><?/*= $row['diary_no']; */ ?></td>
                                            <td><?/*= $row['reg_no_display']; */ ?></td>
                                            <td><?/*= $row['causetitle']; */ ?></td>-->
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    var title = function() {
                        return $('.download_label').text();
                    };
                    $('#reportTable2').DataTable({

                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'csv',
                                title: title,
                                exportOptions: {
                                    // columns: [0,1,2,3,4,5,6],
                                    stripHtml: true
                                }

                            },
                            {
                                extend: 'excel',
                                title: title,
                                exportOptions: {
                                    //  columns: [0,1,2,3,4,5,6],
                                    stripHtml: true
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                title: title,
                                exportOptions: {
                                    //columns: [0,1,2,3,4,5,6],
                                    stripHtml: true
                                }

                            },
                            {
                                extend: 'print',
                                title: title,
                                exportOptions: {
                                    //  columns: [0,1,2,3,4,5,6],
                                    stripHtml: true
                                }
                            }
                        ]
                    });
                });
            </script>



        <?php
    } else {
        echo '<div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>';
    }

        ?>
        </div>
    <?php
}
    ?>
    <script>
        $(function() {

            //e.defaultPrevented;
            $('#add_columns').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 400
            });




        });

        $("#button_to_add").on("click", function() {
            $('#sort_by :selected').each(function() {
                $('#sort_by2').append($('<option>', {                    
                    value: $(this).val(),
                    text: $(this).text()
                }).prop("selected", true));
                $(this).remove();
            });
        });

        $("#button_to_remove").on("click", function() {
            $('#sort_by2 :selected').each(function() {
                $('#sort_by').append($('<option>', {                         
                    value: $(this).val(),
                    text: $(this).text()                    
                }));
                $(this).remove();
            });
        });

        $('#footerButton').on('click', function() {
            var inputTitle = $('#input_title').val();
			var dnos = $(this).data('dnos');
            /* var dnos = $(this).data('dnos');
            var form = $('<form>', {
                'method': 'POST',
                'action': 'http://localhost/SC-ICMIS/public/Listing/AdvanceListReport/cl',
                'target': '_blank'
            });
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'diaryNos[]',
                'value': dnos
            }));

            form.append($('<input>', {
                'type': 'hidden',
                'name': 'input_title',
                'value': inputTitle
            })); */
			  if(inputTitle == '')
			{
				alert('Please input Title');
				$('#input_title').focus();
				return false
			}else{
				$('#cl_input_title').val(inputTitle);
				$('#frmCl').submit();
			}  
			
			 
        });
    </script>