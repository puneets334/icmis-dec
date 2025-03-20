<div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
   

            <?php if(!empty($case_result)):?>
                <caption><h3 style="text-align: center;"><strong> OR Uploaded for Date <?php echo $on_date?></strong></h3></caption>
                <table id="ReportVec" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th style="width: 5%;" rowspan='1'>SNo.</th>
                                <th style="width: 5%;" rowspan='1'>Ct No.</th>
                                <th style="width: 10%;" rowspan='1'>Item No.</th>
                                <th style="width: 10%;" rowspan='1'>DA Name</th>
                                <th style="width: 10%;" rowspan='1'>Case No.</th>
                                <th style="width: 30%;" rowspan='1'>Titled As</th>
                                <th style="width: 10%;" rowspan='1'>section</th>
                                <th style="width: 10%;" rowspan='1'>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $s_no = 1;                    
                    foreach($case_result as $row): //print_r($row); exit;        
                    ?>
                    <tr>
                    <td><?php echo $s_no;?></td>
                                    <td><?php echo $row['courtno'];?></td>
                                    <td><?php echo $row['brd_prnt'];?></td>
                                    <td><?php echo $row['DA_Name'];?></td>
                                    <td><?php echo $row['reg_no_display'].'@D.No. '.$row['d_no'];?></td>
                                    <td><?php echo $row['cause_title'];?></td>
                                    <td><?php echo $row['user_section'];?></td>
                                    <td><strong><?php echo ($row['web_status'] == 1) ? 'Upload' : 'Not Upload';?></td></strong></td>    
                       
                    </tr>
                    
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
        <script>

            
$(function () {
$("#ReportVec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>