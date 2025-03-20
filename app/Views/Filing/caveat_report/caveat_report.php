 <div id="dv_print">
     <?php
        $condition;
        $msg = '';
        $condition = " and 1=1 ";
        if (!empty($caseTypeId)) {
            $condition = $condition . " and m.casetype_id=" . $caseTypeId;
            $msg = "";
            $row = is_data_from_table('master.casetype', " display='Y' and casecode=$caseTypeId ", 'casename', '');
            $msg = $msg . "Case Type:" . $row['casename'];
        }
        $srno = 1;
        $result = $CaveatModel->getCaveatReportData($dateFrom, $dateTo, $condition);
        ?>

     <h1 style="color: blue;font-size: 1.2em;text-align: center">Total Caveat:<?php echo (!empty($result) && count($result) > 0) ? count($result) : '0' ?></h1>

     <h2 style="text-align: center;text-transform: capitalize;color: blue;"> Caveat Registration between <?php echo date('d-m-Y',strtotime($dateFrom)) ?> and <?php echo date('d-m-Y',strtotime($dateTo)) ?><br><?= $msg ?>
     </h2>
     <!-- <div class="cl_center">
         <input type="button" name="btn_pnt" id="btn_pnt" value="Print" />
     </div> -->
     <div class="table-responsive">
         <table class="table table-striped custom-table" id="diaryReport">
             <thead>
                 <tr>
                     <th style="text-align: center;">Sr.No.</th>
                     <th width="13%" style="text-align: left;">Caveat No-Year#Caveat Date</th>
                     <th>Lower Court Details</th>
                     <th width="8%">Diary No./Date</th>
                     <th width="30%">Cause Title</th>
                     <th width="10%">Caveator Advocate </th>
                     <th width="10%">Petitioner Advocate </th>
                     <th>Court Fee#Total Court Fee</th>
                     <th width="7%">Diary User</th>
                     <!--<th width="10%">State/Lower Court Information</th>-->
                 </tr>
             </thead>
             <tbody>
                 <?php
                    //  print_r($result);
                    //         die(47);
                    if (!empty($result)) {
                        if (count($result) > 0) {
                            //  print_r($result);
                            //     die(47);
                            foreach ($result as $row) {
                    ?>
                             <tr>
                                 <td style="text-align: center;"><?php echo $srno ?></td>
                                 <td><?php echo $row['caveat_no1']; ?>-<?php echo $row['caveat_year']; ?>#<?php echo date('d-m-Y', strtotime($row['caveat_date'])); ?>
                                     <?php
                                        if ($row['no_of_days'] > 90) {
                                        ?>

                                         <font> STATUS:</font><span style="color:red"><?php echo "Expired"; ?></span> <?php
                                                                                                                                                                                                                } else {?>
                                            <font> STATUS:</font><span style="color:green"><?php echo "Active"; ?></span> <?php }?>
                                 </td>
                                 <th>
                                     <?php


                                        $r_lowerct = $CaveatModel->getCaveatLowerct($row['c_no']);
                                        // pr($r_lowerct);
                                        echo (!empty($r_lowerct['tot_data'])) ? nl2br($r_lowerct['tot_data']) : '';

                                        ?>
                                 </th>
                                 <td>
                                     <?php
                                        $caveat_no = $row['caveat_no1'] . $row['caveat_year'];
                                        $diary_tot = '';
                                        $diary_caveat_match = is_data_from_table('caveat_diary_matching', " display='Y' and caveat_no=$caveat_no ", 'diary_no', 'A');
                                        if (!empty($diary_caveat_match) && count($diary_caveat_match) > 0) {
                                            $total_diary = '';

                                            foreach ($diary_caveat_match as $row1) {
                                                if ($total_diary == '') {
                                                    // pr( $diary_caveat_match);
                                                    $total_diary = substr($row1['diary_no'], 0, strlen($row1['diary_no']) - 4) . '-' . substr($row1['diary_no'], -4);
                                                    $diary_tot = $row1['diary_no'];
                                                } else {
                                                    $total_diary = $total_diary . '<br/><br/>' . substr($row1['diary_no'], 0, strlen($row1['diary_no']) - 4) . '-' . substr($row1['diary_no'], -4);
                                                    $diary_tot = $diary_tot . ',' . $row1['diary_no'];
                                                }
                                            }
                                        } else {
                                            $total_diary = "-";
                                        }
                                        echo $total_diary; ?>
                                     <!-- pr($row); -->
                                 </td>
                                 <td><?php echo $row['pet_name'] ?> <strong>Vs.</strong> <?php echo $row['res_name'] ?></td>
                                 <td><?php echo $row['pet_adv_id'] ?></td>
                                 <td>
                                     <?php
                                        /* $diary_advocate="Select name from advocate a join bar b on 
                                        a.advocate_id=b.bar_id where diary_no 
                                     in ('$diary_tot') and display='Y' and pet_res='P' and pet_res_no='1'";
                                   $diary_advocate=  mysql_query($diary_advocate) or die("Error: ".__LINE__.mysql_error()); 
                                    */
                                        $adv_name = '';
                                        if (!empty($diary_tot)) {
                                            $diary_advocate = $CaveatModel->getDiaryAdvocate($diary_tot);
                                            if (!empty($diary_advocate) && count($diary_advocate) > 0) {

                                                foreach ($diary_advocate as $row2) {
                                                    if ($adv_name == '')
                                                        $adv_name = $row2['name'];
                                                    else
                                                        $adv_name = $adv_name . '<br/><br/>' . $row2['name'];
                                                }
                                            } else {
                                                $adv_name = "-";
                                            }
                                        }
                                        echo $adv_name; ?>

                                 </td>
                                 <td><?php echo $row['court_fee'] ?> # <?php echo $row['total_court_fee'] ?></td>
                                 <td><?php echo $row['diary_user_id'] ?></td>
                                 <!--<td><?php //echo $row['ref_agency_state_id'] 
                                            ?> # <?php //echo $row['ref_agency_code_id'] 
                                                    ?></td>-->
                             </tr>
                 <?php $srno++;
                            }
                        }
                    }
                    ?>
             </tbody>
         </table>
     </div>
 </div>
 <script>
    $("#diaryReport").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
            {
                extend: 'excel',
                text: 'Excel'
            },
            {
                extend: 'pdf',
                text: 'PDF'
            },
            {
                extend: 'print',
                text: 'Print',
                customize: function (win) {
                  
                    $(win.document.body)
                        .css('font-size', '10pt') 
                        .prepend(
                            '<h5 style="text-align: center;">Diary Report</h5>' 
                        );

                    $(win.document.body).find('div').remove(); 

                    $(win.document.body).find('table')
                        .addClass('display')
                        .css({
                            'font-size': '10pt',
                            'width': '100%'
                        });
                },
                exportOptions: {
                    columns: ':visible', 
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });
</script>

