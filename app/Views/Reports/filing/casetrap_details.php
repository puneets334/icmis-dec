<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($results)) : ?>
                <?php
                if ($from_date <> '' || $to_date <> '') {
                    echo "<center><h4><font color='blue'>Case Trap - Details of Cases filed between " . date('d-m-Y', strtotime($from_date)) . " and " . date('d-m-Y', strtotime($to_date)) . "</h4></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Generated on: " . date('d/m/Y H:i:s') . "</center>";
                }
                if ($diary_no <> '') {
                    echo "<center><h4><font color = blue>Case Trap -Diary No - " . substr_replace($diary_no, ' / ', -4, 0) . "</h4></font></center>";
                }

                ?>
				<div class="table-responsive">
                <table id="ReportFileTrap" class="table table-striped custom-table">
                    <thead>
                        <tr>
                            <th bgcolor=silver>SNo</th>
                            <th width=4% bgcolor=silver>Diary No</th>
                            <th>Reference ID</th>
                            <th bgcolor=silver>Case Type:</th>
                            <th bgcolor=silver>CauseTitle</th>
                            <th bgcolor=silver>Diarized on </th>
                            <th bgcolor=silver>Diarized By</th>
                            <th bgcolor=silver>Lower Court Entered On</th>
                            <th bgcolor=silver>Lower Court Entered By</th>
                            <th bgcolor=silver>Defects notified on</th>
                            <th bgcolor=silver>Defects Notified By</th>
                            <th bgcolor=silver>Refiled On</th>
                            <th bgcolor=silver>Refiled By</th>
                            <th bgcolor=silver>Registration No.</th>
                            <th bgcolor=silver>Registered On</th>
                            <th bgcolor=silver>Registered By</th>
                            <th bgcolor=silver>Verified On</th>
                            <th bgcolor=silver>Verified By</th>
                            <th bgcolor=silver>Proposed to be Listed on:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
						
                        foreach ($results as $row) { 
						
						?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <td><?php echo $row->diary_no ?? ''; ?><br>
                                    <font color="red" size="3"><?php echo $row->isefiled ?? ''; ?></font>
                                </td>
                                <td><?php echo $row->ref_id ?? ''; ?></td>
                                <td><?php echo $row->short_description ?? ''; ?></td>
                                <td><?php echo $row->causetitle ?? ''; ?></td>
                                <td><?php echo $row->diarized_on ?? ''; ?></td>
                                <td><?php echo $row->diarized_by ?? ''; ?></td>
                                <td><?php echo $row->lowercourt_entered_on ?? ''; ?></td>
                                <td><?php echo $row->lowercourt_entered_by ?? ''; ?></td>
                                <td><?php echo $row->defect_raised_on ?? ''; ?></td>
                                <td><?php echo $row->defects_notified_by ?? ''; ?></td>
                                <td><?php echo $row->defects_removed_on ?? ''; ?></td>
                                <td><?php echo $row->defects_removed_by ?? ''; ?></td>
                                <td><?php echo $row->registration_no ?? ''; ?></td>
                                <td><?php echo $row->registered_on ?? ''; ?></td>
                                <td><?php echo $row->registered_by ?? ''; ?></td>
                                <td><?php echo $row->verified_on ?? ''; ?></td>
                                <td><?php echo $row->verified_by ?? ''; ?></td>
                                <td><?php echo $row->proposed_to_be_listed_on ?? ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
				</div>
            <?php else : {
                    echo "Record Not Found";
                }
            endif; ?>
        </div>
        <script>
            $(function() {
                $("#ReportFileTrap").DataTable({
                    "responsive": false,
                    "lengthChange": false,
                    "autoWidth": true,
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