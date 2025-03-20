<?php
  $uri = current_url(true);
  ?>
<?= view('header') ?>
 
<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php
            if(!empty($document_detail_result)):
                foreach ($document_detail_result as $result) {
                    $name = $result['dak_name'];
                    $empid = $result['dak_empid'];
                }
                if (!empty($date))
                    $heading = "Documents received in DAK Counter on " . date('d-m-Y', strtotime($date));
                else {
                    if ($first_date == $to_date)
                        $heading = "Documents received by " . $name . "[" . $empid . "] on " . date('d-m-Y', strtotime($first_date));
                    else if ($first_date != $to_date)
                        $heading = "Documents received by " . $name . "[" . $empid . "] from " . date('d-m-Y', strtotime($first_date)) . " to " . date('d-m-Y', strtotime($to_date));
                }
                ?>
                <table  id="ReportFileTrap" class="table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;"><?php echo $heading;?></h3>
                    <tr>
                        <th>Sr.No.</th>
                        <th>Diary Number</th>
                        <th>Cause Title</th>
                        <th>Section</th>
                        <th>Document Number</th>
                        <th>Description</th>
                        <th>Filed By</th>
                        <th>Filed On</th>
                        <th>DAK DA</th>
                        <th>Case DA</th>
                        <th>Next Listing Date</th>
                        </tr></thead><tbody>
                    <?php  $i = 0;
                                    $total = 0;
                                    foreach ($document_detail_result as $result) {
                                        $i++;?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo  $result['diary_no']; ?></td>
                            <td><?php echo  $result['causetitle']; ?></td>
                            <td><?php echo  $result['da_section']; ?></td>
                            <td><?php echo  $result['document']; ?></td>
                            <td><?php echo  $result['docdesc']; ?></td>
                            <td><?php echo  $result['filedby']; ?></td>
                            <td><?php echo  date('d-m-Y H:i:s', strtotime($result['ent_dt'])); ?></td>
                            <td><?php echo  $result['dak_name'] . "(" . $result['dak_empid'] . ")"; ?></td>
                            <td><?php echo $result['da_name'] . "(" . $result['da_empid'] . ")"; ?></td>
                            <td><?php if ($result['next_date'] != null && $result['next_date'] != '0000-00-00' && $result['diff'] > 0 && $result['diff'] <= 7)
                                    echo "<font color='red'>" . date('d-m-Y', strtotime($result['next_date'])) . "</font>";
                                else if ($result['next_date'] != null && $result['next_date'] != '0000-00-00' && $result['diff'] > 7)
                                    echo "<font color='green'>" . date('d-m-Y', strtotime($result['next_date'])) . "</font>";
                                ?></td>
                        </tr>
                        <?php  } ?>
                    </tbody>
                </table>
            <?php  endif; ?>
            <!-- end of fileTrap -->
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#ReportFileTrap").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>
 <?=view('sci_main_footer') ?>
