<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">

            <?php if (!empty($Vac_list)): ?>
                <table id="ReportVec" class="query_builder_report table table-striped custom-table">
                    <thead>
                        <tr>
                            <th style="width:5%;">SNo.</th>
                            <th style="width:20%;">Case No.</th>
                            <th style="width:35%;">Petitioner / Respondent</th>
                            <th style="width:40%;">Petitioner/Respondent Advocate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        //Array ( [diary_no] => 634261984 [section_name] => PIL-W [active_fil_no] => 05-013381-013381 [active_reg_year] => 1984 [casetype_id] => 5 [active_casetype_id] => 5 [ref_agency_state_id] => 490506 [reg_no_display] => W.P.(C) No. 13381/1984 [fil_year] => 1984 [fil_no] => 05-013381-013381 [main_key] => 634261984 [fil_dt] => 1984-07-27 00:00:00+05:30 [fil_no_fh] => [fil_year_f] => 0 [mf_active] => M [pet_name] => M.C. MEHTA [res_name] => UNION OF INDIA [pno] => 1 [rno] => 9 [diary_no_rec_date] => 1984-07-24 00:00:00+05:30 [last_digits] => 1984 [first_digits] => 63426 )

                        foreach ($Vac_list as $row): //print_r($row); exit; 
                            $diary_no = $row['diary_no'];

                        ?>
                            <tr>
                                <td><?php echo $sno;
                                    $sno++; ?></td>
                                <td><?php echo $row['casetype_id'] . '/ ' . $row['active_fil_no'] ?></td>
                                <!--<td align="left" style='vertical-align: top;'><?php /*echo date('d-m-Y', strtotime($ro['tentative_cl_dt']));  */ ?></td>-->
                                <td><?php echo $row['pet_name'] . "<br/>Vs<br/>" . $row['res_name']; ?></td>
                                <td><?php echo $row['pet_name'] . "<br/>Vs<br/>" . $row['res_name']; ?></td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
    </div>
</div>
<script>
    $(function() {
        $("#ReportVec").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>