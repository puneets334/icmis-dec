 <?php

       if(!empty($list_stats) ){
                    if(!empty($from_date) && !empty($to_date)){
                        if($from_date!= $to_date)
                            $heading="Statistics Of Gist Uploaded Between ". date('d-m-Y',strtotime($from_date)). " and ". date('d-m-Y',strtotime($to_date));
                        else if($from_date==$to_date)
                            $heading="Statistics Of Gist Uploaded On ". date('d-m-Y',strtotime($from_date));
                    }


                    ?>


               <div><button type="button" id="print" name="print" style="width:10%" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>
              <div id="printable" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                 <br> <div style="text-align:center; font-weight:bold; font-size:24px;">  <?php echo $heading; ?> </div><br>
                     <table id="datewisereport" class="table table-bordered table-striped datatable_report">
                            <thead>
                            <th>S.No.</th>
                            <th>Date</th>
                            <th>Total Uploaded</th>
                            <th>Total Verified</th>
                            </tr>
                            </thead>
                          <tbody>
                            <?php
                            $i=0;
                            $total_uploaded=0;
                            $total_verified=0;
                            foreach ($list_stats as $result)
                            {
                                $i++;
                                ?>
                                <tr>
                                    <td ><?php echo $i;?></td>
                                    <td><a target="_blank" href="<?php echo base_url() ?>/Editorial/ESCR/report?fromDate=<?php echo $result['uploaded_on'];?>&toDate=<?php echo $result['uploaded_on'];?>"><?php echo date('d-m-Y',strtotime($result['uploaded_on']));?></td>
                                    <td><?php echo $result['updated'];?></td>
                                    <td><?php echo $result['verified'];?></td>
                                </tr>

                                <?php
                                $total_uploaded+=$result['updated'];
                                $total_verified+=$result['verified'];
                            }
                            ?>
                            <tr style="font-weight:bold;"><td colspan="2"><a target="_blank" href="<?php echo base_url() ?>/Editorial/ESCR/report?fromDate=<?php echo $from_date;?>&toDate=<?php echo $to_date;?>">Total</td><td><?=$total_uploaded;?></td><td><?=$total_verified;?></td>
                            </tr>
                          </tbody>
                        </table>

                    </div>
                <?php
                }else
                 {
                     echo "<center><span style='color:red;font-size:24px;'>Data not available for given dates</span></center>";
                 }

       ?>


 <script>
     $(function () {
         $(".datatable_report").DataTable({
             "responsive": true, "lengthChange": false, "autoWidth": false,
             "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                 { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
         }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

     });

     function printDiv(printable) {
         // alert(printable);return false;
         var printContents = document.getElementById('printable').innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
     }
 </script>



