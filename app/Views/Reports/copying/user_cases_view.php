<?php
$uri = current_url(true);
?>
<?= view('header') ?>

    <div class="card">
        <div class="card-body" >
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <?php
                 if(isset($user_cases_result) && sizeof($user_cases_result)>0 ):
                     switch($category){
                         case 1: { $category_display = "Urgent Certified"; break;}
                         case 2: { $category_display = "Ordinary Urgent"; break;}
                         case 3: { $category_display = "Ordinary Certified"; break;}
                         case 4: { $category_display = "Ordinary"; break;}
                         default: { $category_display = " "; break;}
                     }
                     if($from_date==$to_date)
                         $heading=" On ".date('d-m-Y',strtotime($from_date));
                     else
                         $heading=" from ".date('d-m-Y',strtotime($from_date))." to ".
                             date('d-m-Y',strtotime($to_date));

                     foreach ($user_cases_result as $result)
                     {
                         $name=$result['user'];
                         $empid=$result['empid'];
                     }
                    ?>
                    <table  id="ReportFileTrap" class="table table-bordered table-striped">
                        <thead>
                        <h3 style="text-align: center;"><?php echo $category_display;?> Applications received by <?php echo $name."(".$empid.") ".$heading; ?></h3>
                        <tr>
                            <th>#</th>
                            <th>Application<br/>Number</th>
                            <th>Diary<br/>Number</th>
                            <th>Applied By</th>
                            <th>Applied On</th>
                            <th>No. of <br/> Documents<br/> Applied</th>
                            <th>Application <br/> Status</th>
                            <th>Court Fees</th>
                        </tr></thead><tbody>
                        <?php  $i = 0;
                        $total = 0;
                        foreach ($user_cases_result as $result) {
                            $i++;?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><a target="_blank" href="trap?id=<?=$result['id']?>&num=<?=$result['application_number_display']?>"><?php echo $result['application_number_display'];?></a></td>
                                <td><?php echo $result['diary'];?></td>
                                <td><?php echo $result['name'];?></td>
                                <td><?php echo date('d-m-Y',strtotime($result['received_on']));?></td>
                                <td><a target="_blank" href="documents?id=<?php echo $result['id'];?>&num=<?php echo $result['application_number_display'];?>"><?php echo $result['documents'];?></td>
                                <td><?php echo $result['status'];?></td>
                                <td><?php echo $result['court_fee'];?></td>
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
            var title = '<?php echo $category_display;?> Applications received by <?php echo $name."(".$empid.") ".$heading; ?>';
            $("#ReportFileTrap").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
<?=view('sci_main_footer') ?>