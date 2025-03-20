<?php if (count($data) > 0) {
?>
    <div class="table-responsive">
        <table class="table table-striped custom-table" id="report">
            <thead>
                <tr>
                    <th width="5%">SrNo.</th>
                    <th width="20%">Reg No. / Diary No</th>
                    <th width="10%">Tentative Date</th>
                    <th width="8%">Tentative Board</th>
                    <th width="27%">Petitioner / Respondent</th>
                    <th width="27%">Advocate</th>
                    <th width="13%">Section</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;

                foreach ($data as $ro) {
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $conn_no = $ro['conn_key'];
                    if ($ro['board_type'] == "J") {
                        $board_type1 = "Court";
                    }
                    if ($ro['board_type'] == "C") {
                        $board_type1 = "Chamber";
                    }
                    if ($ro['board_type'] == "R") {
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-", $ro['active_fil_no']);

                    if (empty($filno_array[0])) {
                        $fil_no_print = "Unregistred";
                    } else {
                        $fil_no_print = $ro['short_description'] . "/" . ltrim($filno_array[1], '0');
                        if (!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                            $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                        $fil_no_print .= "/" . $ro['active_reg_year'];
                    }
                    if ($sno1 == '1') { ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                        <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if ($ro['pno'] == 2) {
                        $pet_name = $ro['pet_name'] . " AND ANR.";
                    } else if ($ro['pno'] > 2) {
                        $pet_name = $ro['pet_name'] . " AND ORS.";
                    } else {
                        $pet_name = $ro['pet_name'];
                    }
                    if ($ro['rno'] == 2) {
                        $res_name = $ro['res_name'] . " AND ANR.";
                    } else if ($ro['rno'] > 2) {
                        $res_name = $ro['res_name'] . " AND ORS.";
                    } else {
                        $res_name = $ro['res_name'];
                    }
                    $padvname = "";
                    $radvname = "";
                    $advsql = $model->advocatesql($ro["diary_no"]);

                    if (!empty($resultsadv)) {
                        $rowadv = $resultsadv;
                        $radvname =  $rowadv["r_n"];
                        $padvname =  $rowadv["p_n"];
                    }


                    if (($ro['section_name'] == null or $ro['section_name'] == '') and $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {
                        if ($ro['active_reg_year'] != 0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));

                        if ($ro['active_casetype_id'] != 0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if ($ro['casetype_id'] != 0)
                            $casetype_displ = $ro['casetype_id'];


                        if (!empty($result)) {
                            $section_ten_row = $result;
                            $ro['section_name'] = $section_ten_row["section_name"];
                        }
                    }
                        ?> <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $fil_no_print . "<br>Dno " . substr_replace($ro['diary_no'], '-', -4, 0); ?></td>
                        <td align="left" style='vertical-align: top;'>

                            <?php
                            if (get_display_status_with_date_differnces($ro['tentative_cl_dt']) == 'T') {
                                echo date('d-m-Y', strtotime($ro['tentative_cl_dt']));
                            }
                            ?>
                        </td>
                        <td align="left" style='vertical-align: top;'><?php echo $board_type1; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")); ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['section_name'] . "<br/>" . $ro['name']; ?></td>

                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "No Recrods Found";
}
?>

<script> 
    $("#report").DataTable({
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
