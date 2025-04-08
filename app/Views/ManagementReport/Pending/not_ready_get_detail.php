<?php
 
    if(count($result_array)>0) {

            $sno = 1;
            ?>
        <style>div, table, tr, td{
                font-size:12px;
                vertical-align: top;
            }</style>
            <div id="prnnt" style="font-size:12px;">
            <center><h3>CASES AVAILABLE IN POOL <br><?php echo $headnote2.$headnote1; ?></h3></center>
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


        foreach($result_array as $row) {

            ?>

            <tr>
                <td><?php echo $sno++; ?></td>
                <td>
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
                        echo "<br/>Reg dt " . date("d-m-y", strtotime($row['fil_dt']));
                    }

                    ?>
                </td>

                <td><?php echo date("d-m-Y", strtotime($row['next_dt'])) . " " . $row['mainhead']; ?></td>
                <td <?php if ($row['mainhead'] == 'M') {
                    if ($row['board_type'] == "R" AND $row['subhead'] != '848' AND $row['subhead'] != '849' AND $row['subhead'] != '850') {
                        echo 'style="background-color:#ff1e2c;"';
                    }
                    if ($row['board_type'] == "J" AND ($row['subhead'] == 0 OR $row['subhead'] == '848' OR $row['subhead'] == '849' OR $row['subhead'] == '850' OR $row['subhead'] == '818' OR $row['subhead'] == '819')) {
                        echo 'style="background-color:#ff1e2c;"';
                    }

                }
                ?> > <?php if ($row['mainhead']  != 'F') {
                        f_get_subhead_basis($row['subhead']);
                    }
                    ?>
                </td>
                <td <?php if (empty($row['cat1']) OR $row['cat1'] == 331 OR $row['cat1'] == 911 OR $row['cat1'] == 912 OR $row['cat1'] == 240 OR $row['cat1'] == 241 OR $row['cat1'] == 242 OR $row['cat1'] == 243) { ?> style="background-color: #ff1e2c;" <?php } ?> > <?php if ($row['cat1']) {
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
                        echo "<br/><span style='color:red;'>Not to list till dispose of $rgo_default</span>";
                    }
                    ?></td>
                <td><?php f_get_section_name_fdno($row['diary_no']);
                    f_get_user_name_fdno($row['diary_no']);
                    echo date("d-m-Y H:i:s", strtotime($row['ent_dt'])); ?></td>
            </tr>

            <?php
        }
        ?>
        <table>
        <?php
    }
    else{
        echo "No Records Found";
    }
    ?>