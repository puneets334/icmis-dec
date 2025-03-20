<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header heading">
                <h3 class="card-title"><?php //echo  $title; ?></h3>
            </div>
            <div class="card-body">
                <?php //if (count($result_array) >  0) {
                ?>
                <div style="align:center;">
                    <h2><?= $title ?></h2>
                    <table id="tab">
                        <thead>
                            <tr style="background-color:darkgrey;">
                                <th style="width: 5%;">Item No.</th>
                                <th style="width:10%;">Case No.</th>
                                <th style="width: 15%;">Cause Title</th>
                                <th style="width:10%;">List Date</th>
                                <th style="width: 15%;">Coram</th>
                                <th style="width: 15%;">Listed Before</th>
                                <th style="width: 20%;">Sensitive Reason</th>
                                <th style="width: 10%;">List Status</th>
                        </thead>
                        <tbody>

                            <?php

                            foreach ($result_array as $row) {
                                if (empty($row['reg_no_display'])) {
                                    $case_no = 'Diary No. ' . substr_replace($row['diary_no'], ' of ', -4, 0);
                                } else {
                                    $case_no = $row['reg_no_display'];
                                }
                            ?>
                                <tr>
                                    <td><?= $row['brd_slno']; ?></td>
                                    <td><?= $case_no ?></td>
                                    <td><?= $row['pet_name'] . ' Vs. ' . $row['res_name']; ?></td>
                                    <td><?= date("d-m-Y", strtotime($row['next_dt'])); ?></td>
                                    <td><?= $row['coram'] ?></td>
                                    <td><?= $row['judge_name'] ?></td>
                                    <td><?= $row['reason'] ?></td>
                                    <td><?= $row['is_published'] == null ? ' ' : '<span class="text-success">Published</span>' ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php   //} else {
                // echo 'No Records Found';
                //} 
                ?>
            </div>
        </div>
    </div>
    <script>
        var filename = '<?= $title ?>';
        var title = '<?= $title ?>';
        $(document).ready(function() {
            $('#tab').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        filename: filename,
                        title: title,
                        text: 'Export to Excel',
                        autoFilter: true,
                        sheetName: 'Sheet1'

                    },

                    {
                        extend: 'pdf',
                        className: 'btn btn-primary glyphicon glyphicon-file',
                        filename: filename,
                        title: title,
                        pageSize: 'A4',
                        orientation: 'landscape',
                        text: 'Save as Pdf',
                        customize: function(doc) {
                            doc.styles.title = {

                                fontSize: '18',
                                alignment: 'left'

                            }
                            // doc.content[1].table.widths = [25,88,230,130]; Width of Column in PDF
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-primary glyphicon glyphicon-print',
                        // filename: filename,
                        title: title,
                        pageSize: 'A4',
                        orientation: 'portrait',
                        text: 'Print',
                        autoWidth: false,
                        columnDefs: [{
                            "width": "20px",
                            "targets": [0]
                        }],

                        customize: function(win) {
                            $(win.document.body).find('h1').css('font-size', '20px');
                            $(win.document.body).find('h1').css('text-align', 'left');
                            $(win.document.body).find('tab').css('width', 'auto');

                        }

                    }
                ],

                paging: false,
                ordering: false,
                info: false,
                // columnDefs: [{"width": "20px", "targets": [0]},
                //                 {"width": "40px", "targets": [1]},
                //                 {"width": "250px", "targets": [2]}],
                searching: false,


            });
        });
    </script>