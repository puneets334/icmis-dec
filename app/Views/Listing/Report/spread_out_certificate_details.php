<style>div, table, tr, td{
                font-size:12px;
                vertical-align: top;
            }</style>
 <div id="prnnt" style="font-size:12px;">
            <center><h3>CASES AVAILABLE IN POOL <br><?php echo $headnote2.$headnote1; ?></h3></center>
            <?php if (isset($spread_out_result) && count($spread_out_result)) { ?>
            <table border="1" width="100%" id="example" class="display" cellspacing="0" width="100%">
            <thead>
            <th>sno.</th>
            <th>Diary / Reg. / Tag</th>
            <th>Proposed Date / Head</th>
            <th>Sub Head</th>
            <th>Sub. Category</th>
            <th>Purpose of Listing</th>
            <th>Before/ Not Before Judge</th>
            <th>DA/Last Updated</th>
            </thead>
            <tbody>
            <?php   
             $sno = 1;     
            foreach($spread_out_result as $row){ ?>

<tr>
                <td rowspan="2"><?php echo $sno++; ?></td>
                <td rowspan="2">
                    <?php
                    $coram = $row['coram'];


                    if ($row['reg_no_display'] == "") {
                        $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $comlete_fil_no_prt = $row['reg_no_display']." @ "."Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                    }
                    //   }
                    echo $comlete_fil_no_prt;
                    // echo f_get_connected($row['diary_no']);

                    echo "<br/>Diarydt " . date("d-m-y", strtotime($row['diary_no_rec_date']));
                    if ($row['reg_no_display'] != "") {
                        if (isset($row['fil_dt']) && !empty($row['fil_dt'])) {
                            echo "<br/>Reg dt " . date("d-m-y", strtotime($row['fil_dt']));
                        } else {
                            // Handle the case where $row['fil_dt'] is null or empty
                            echo "<br/>Reg dt: Date not available"; // or handle it in another way
                        }
                    }

                    ?>
                </td>

                <td><?php echo date("d-m-Y", strtotime($row['next_dt'])) . " " . $row['mainhead']; ?></td>
                <td <?php if ($mainhead == 'M') {
                    if ($bench == "R" AND $row['subhead'] != '849' AND $row['subhead'] != '850') {
                        echo 'style="background-color:#ff1e2c;"';
                    }
                    if ($bench == "J" AND ($row['subhead'] == '849' OR $row['subhead'] == '850')) {
                        echo 'style="background-color:#ff1e2c;"';
                    }
                }
                ?> > <?php if ($mainhead != 'F') {
                        f_get_subhead_basis($row['subhead']);
                    }
                    ?>
                </td>
                <td <?php if (empty($row['cat1']) OR $row['cat1'] == 331) { ?> style="background-color: #ff1e2c;" <?php } ?> > <?php if ($row['cat1']) {
                        f_get_cat_diary_basis($row['cat1']);
                    } ?></td>

                <td><?php echo $row['purpose']; ?></td>

                <td><?php if ($coram != 0) {
                        echo "CORAM : " . f_get_judge_names_inshort($coram);
                    }
                    f_get_ntl_judge($row['diary_no']);
                    f_get_ndept_judge($row['diary_no']);
                    f_get_category_judge($row['diary_no']);
                    f_get_not_before($row['diary_no']);
                    $rgo_default = f_cl_rgo_default($row['diary_no']);
                    if ($rgo_default != 0) {
                        echo "<br/>Not to list till dispose of $rgo_default";
                    }
                    ?></td>
                <td><?php f_get_section_name_fdno($row['diary_no']);
                    f_get_user_name_fdno($row['diary_no']);
                    echo date("d-m-Y H:i:s", strtotime($row['ent_dt'])); ?></td>
            </tr>
            <tr>
                <td colspan="6">
                    <?php
                    $diary_no = $row['diary_no']?? 0;
                    try{
                    $sqll = getSpreadOutCertificateDetail($diary_no);
                }
                catch (UnexpectedValueException $e) {
                    printf($e);
                }
                     //pr($sqll);

                   if (count($sqll) > 0) {
                    foreach ($sqll as $ro_rop) {
                        $orderDate = $ro_rop['orderdate'];
                        if (strtotime($orderDate) !== false) { // Check if valid date
                            $formattedDate = date("d-m-Y", strtotime($orderDate));
                            $rjm = explode("/", $ro_rop['pdfname']);
                            if ($rjm[0] == 'supremecourt') {
                                echo 'ROP Dated : <a href="../../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">' . $formattedDate . '</a><br>';
                            } else {
                                echo 'ROP Dated : <a href="../../judgment/' . $ro_rop['pdfname'] . '" target="_blank">' . $formattedDate . '</a><br>';
                            }
                        } else {
                            echo "Invalid date: " . $orderDate . "<br>"; // Handle invalid dates
                        }
                    }
                }
                    ?>
                    <?php echo $row['lastorder']; ?>
                    <?php f_get_brdrem($row['diary_no']); ?>
                    <?php f_get_kword($row['diary_no']); ?>
                    <?php f_get_docdetail($row['diary_no']); ?>
                    <?php f_get_act_main($row['diary_no']); ?>
                </td>
            </tr>

            <?php }  ?>

                </tbody>
                </table>
        <?php
                }
                else{
                    echo "No Records Found";
                }
    ?>
