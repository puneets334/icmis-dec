<div class="modal-header">
    <h4 class="modal-title"><?=$heading?></h4>
    <button type="button" class="close" data-dismiss="modal">×</button>
</div>
<!-- Modal body -->
<div class="modal-body">
    <div class="row" id="query_builder_wrapper" >
        (As on <?=date('d-m-Y H:i:s')?>)
        <?php
        if (!empty($all_records)) {
            ?>
            <table class="table table-bordered table-striped" id="ReportFileTrap">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Case No./Diary No.
                    </th>

                    <th>
                        Application No./CRN
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Section Name
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
            <?php
            $sno = 1;
            foreach($all_records as $data){
                ?>
                <tr>
                    <td>
                       <?=$sno++?>
                    </td>
                    <td>
                        <?php 
                        if(!empty($data['diary_no'])){
                            echo $data['reg_no_display']." @ ".substr($data['diary_no'], 0, -4)." - ".substr($data['diary_no'], -4);
                        }else{
                            echo $data['reg_no_display'];
                        }

                        ?>
                    </td>

                    <td>
                        <?php
                        if($_POST['flag'] == 'offline_total_applications' OR $_POST['flag'] == 'offline_pending_applications' OR $_POST['flag'] == 'offline_disposed_applications' OR $_POST['flag'] == 'total_applications' OR $_POST['flag'] == 'pending_applications' OR $_POST['flag'] == 'disposed_applications') {
                            echo $data['application_number_display']."<br>";
                        }
                        if($_POST['flag'] != 'offline_total_applications' AND $_POST['flag'] != 'offline_pending_applications' AND $_POST['flag'] != 'offline_disposed_applications'){
                            echo "CRN : ".$data['crn'];
                        }

                        ?>
                        <p>Received Date : <?=date("d-m-Y H:i:s", strtotime($data['application_receipt'])) ?></p>
                    </td>

                    <td>
                        <?php echo $data['name']." ";
                        if($data['filed_by'] == 1){
                            echo "<span class='text-success'>(AOR)</span>";
                        }
                        else if($data['filed_by'] == 2){
                            echo "<span class='text-success'>(Party/Party-in-person)</span>";
                        }
                        else if($data['filed_by'] == 3){
                            echo "<span class='text-success'>(Appearing Counsel)</span>";
                        }
                        else if($data['filed_by'] == 4){
                            echo "<span class='text-success'>(Third Party)</span>";
                        }
                        else if($data['filed_by'] == 6){
                            echo "<span class='text-success'>(Authenticated By AOR)</span>";
                        }

                        ?>
                    </td>
                    <td><?= "Section"?></td>
                   <td>
                        <?php 
                        if($data['application_status'] == 'F' || $data['application_status'] == 'R' || $data['application_status'] == 'D' || $data['application_status'] == 'C' || $data['application_status'] == 'W'){
                            if($_POST['flag'] == 'total_request' OR $_POST['flag'] == 'pending_request' OR $_POST['flag'] == 'disposed_request') {
                                echo "<span class='text-danger'>Completed</span>";
                            }
                            else{
                                echo "<span class='text-danger'>Disposed</span>";
                            }

                        }
                        else{
                            echo "<span class='text-success'>Pending</span>";
                            if ($data['send_to_section']=='t'){
                                echo "<p><span class='text-success'>(Sent to concern section)</span> </p>";
                            }
                        }

                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
                </tbody>
            </table>
            <?php
        }
        else{
            echo "No Records Found";
        }
        ?>

    </div>
</div>
<!-- Modal footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
<!--start datatable script-->
<script>
    $(function () {
        var title = $('.modal-title').text();
        $("#ReportFileTrap").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },{extend: 'csv', title: title },{extend: 'excel', title: title }
                ],"bProcessing": true,
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>
