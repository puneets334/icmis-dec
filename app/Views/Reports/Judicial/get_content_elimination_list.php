<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <div id="prnnt" style="font-size: 12px;">
                <div align="center" style="font-size: 12px;">
                    <span style="font-size: 12px;" align="center">
                        <b>
                            <img src="<?php echo base_url('/images/scilogo.png'); ?>" width="50px" height="80px" /><br />

                            SUPREME COURT OF INDIA

                            <br />
                        </b>
                    </span>
                </div>
                <b>

                    <br />
                    <p align="left" style="font-size: 12px;">
                        <b>
                            NEW DELHI<br />
                            <?php date_default_timezone_set('Asia/Kolkata');
                            echo date('d-m-Y H:i:s');
                            ?>
                        </b>
                        &nbsp; &nbsp;
                    </p>
                    <br />
                    <p align="right" style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
                    <div style="width: 100%; padding-bottom: 1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display: block;">
                        <input name="prnnt1" type="button" id="prnnt1" value="Print" />
                    </div>
                    <center></center>
                    <table border="0" width="100%" style="font-size: 12px; text-align: left; background: #ffffff;" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align: center;">
                                    ELIMINATION LIST<br />
                                    <br />
                                    (Eliminated due to excess matters/not availability of bench etc. for listing)<br />
                                    DATE OF LISTING : <?php echo  date('d-m-Y', strtotime($post_data['listing_dts'])) ?>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </b>
            


            <?php if (!empty($Elimination_list)): ?>
                <table id="ReportCaveat" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Sr.No.</th>
                            <th width="" style="text-align: left;">Diary No</th>
                            <th>List Order</th>
                            <th width="">CauseList</th>
                            <th width="">Name</th>
                            <th width="">Section Name</th>
                            <th> Main Key</th>
                            <th width="">Short description </th>
                            <th>Reg No </th>
                            <th width="">Date</th>
                            <!--<th width="10%">State/Lower Court Information</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($Elimination_list as $row): //print_r($row); exit;
                        ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= '<b>' . $row->diary_no . '</b>' ?></td>
                                <td><?= $row->listorder_new ?></td>

                                <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                                <td><?= $row->name ?></td>
                                <td><?= $row->section_name ?></td>
                                <td><?= $row->main_key ?> </td>
                                <td><?= $row->short_description ?></td>
                                <!-- <td><?= $row->active_fil_no ?></td> -->
                                <!-- <td><?= $row->active_reg_year ?></td> -->
                                <td><?= $row->reg_no_display ?></td>
                                <td><?= date("d-m-Y", @strtotime(@$row->date)); ?></td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>
            </div>
        </div>
        <script>
            $(function() {
                $("#ReportCaveat").DataTable({
                    "responsive": true,
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
                    "lengthMenu" : [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All']
                    ],
                    "bProcessing": true,
                    "extend": 'colvis',
                    "text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

            //function CallPrint(){
            $(document).on("click", "#prnnt1", function() {
                var prtContent = $("#prnnt").html();
                var temp_str = prtContent;
                var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
                WinPrint.document.write(temp_str);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
            });
        </script>