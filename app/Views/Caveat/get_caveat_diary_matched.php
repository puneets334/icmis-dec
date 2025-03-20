 <?php if(!empty($caveat_diary_matched_list)){ ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="query_builder_wrapper_dataTable">
                                        <table class="table table-bordered table-striped table-responsive datatablereport">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th>Caveat No.</th>
                                                <th>Diary No.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $s_no=1;
                                            foreach ($caveat_diary_matched_list as $row ){ ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php echo substr( $row['caveat_no'], 0, strlen( $row['caveat_no'] ) -4 ) ; ?>-<?php echo substr( $row['caveat_no'] , -4 );?></td>
                                                    <td><?php echo substr( $row['diary_no'], 0, strlen( $row['diary_no'] ) -4 ) ; ?>-<?php echo substr( $row['diary_no'] , -4 );?></td>
                                                    </tr>
                                            <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-1 end-->

                        <?php }else{
                            echo '<center><span class="text-danger">No Record Found !!</span></center>';
                        } ?>


 <script>
     $(function () {
         $(".datatablereport").DataTable({
             "responsive": true, "lengthChange": false, "autoWidth": false,
             "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                 { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
         }).buttons().container().appendTo('.query_builder_wrapper_dataTable .col-md-6:eq(0)');

     });
 </script>