 <style>
     .cl_center {
         text-align: center;
     }
 </style>
 <div style="margin-top: 20px" id="dv_print">
     <div class="cl_center" style="font-size: 17px;">SUPREME COURT OF INDIA</div>

     <div class="cl_center" style="font-size: 17px;margin-top: 5px">SECTION I-B</div>
     <div class="cl_center" style="margin-top: 5px;text-align: center">
         CASE SCRUTINY:LIST OF DEFECTS

     </div>
     <div class="cl_center" style="float: right;margin-right: 10px;">Total Defects <?php echo $tot_defects; ?></div>
     <div class="cl_center" style="margin-top: 5px;clear: both;font-size: 17px;font: bolder">Diary No. <b style="font-size: 17px"><?php echo $d_no ?>-<?php echo $d_yr; ?></b></div>
     <div class="cl_center" style="margin-top: 10px">

         <div style="text-align: center;">
             <table width="100%">
                 <tr>
                     <td style="text-align: left">
                         <b><?php echo $row['pet_name'];
                            if ($row['pno'] > 1) {
                                echo " and ors.";
                            } ?></b>

                         Vs

                         <b><?php echo $row['res_name'];
                            if ($row['rno'] > 1) {
                                echo " and ors.";
                            } ?></b>
                     </td>
                     <td style="text-align: right">
                         <div style="text-align: right">
                             Advocate <?php
                                        /*  $advocate="Select name from advocate a join bar b on a.advocate_id=b.bar_id where a.display='Y' and diary_no='$dairy_no' 
                                  and pet_res='P' and adv_type='M' and pet_res_no=1";
                          $advocate=mysql_query($advocate) or die("Error: ".__LINE__.mysql_error());
                          echo mysql_result($advocate,0); */
                                        echo $advocate['name'] ?? '';
                                        ?>
                         </div>
                     </td>
                 </tr>
             </table>


         </div>
     </div>
     <div class="row table-responsive">
         <table id="defect_report" class="table table-striped custom-table" style="width:98%">
             <thead>
                 <tr>
                     <th>
                         S.No.
                     </th>
                     <th>
                         Defects
                     </th>
                     <th>
                         Defects notified on
                     </th>
                     <th>
                         Defects notified by
                     </th>
                     <th>
                         Defects Removed on
                     </th>
                     <th>
                         Defects Removed by
                     </th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                    if (!empty($objection)) {
                        $o_sn = 1;
                        foreach ($objection as $row2) {
                    ?>
                         <tr>
                             <td>
                                 <?php echo $o_sn; ?>
                             </td>
                             <td>
                                 <?php echo $row2['objdesc'] ?? ''; ?>
                                 <?php if ($row2['remark'] != '') {
                                        echo '(<b>' . $row2['remark'] . '</b>)';
                                    } ?>
                                 <?php if ($row2['mul_ent'] != '') {
                                        echo '-' . $row2['mul_ent'];
                                    } ?>
                             </td>
                             <td>
                                 <?php echo date('d-m-Y H:i:s', strtotime($row2['save_dt'])); ?>
                             </td>
                             <td><?php echo $row2['ent_by']; ?></td>
                             <td>
                                 <?php
                                    if ($row2['rm_dt'] == '')
                                        echo " <b><span style='color: red;' >To be cured</span></b>";
                                    else
                                        echo (!empty($row2['rm_dt'])) ? date('d-m-Y H:i:s', strtotime($row2['rm_dt'])) : ''; ?>
                             </td>
                             <td>
                                 <?php echo $row2['rem_by']; ?>
                             </td>
                         </tr>
                     <?php
                            $o_sn++;
                        }
                        ?>
                 <?php
                    }
                    ?>
             </tbody>
         </table>
     </div>

 </div>
 <div cl class="cl_center">
     <input type="button" name="btn_pnt" id="btn_pnt" value="Print" />
 </div>

<script>
    $(function() {
        var table = $("#defect_report").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "buttons": [
                {
                    extend: 'excel',
                    title: 'CASE SCRUTINY : LIST OF DEFECTS <?php echo date("d-m-Y h:i:sa");?>',
                    filename: 'CASE-SCRUTINY:LIST-OF-DEFECTS-<?php echo date("d-m-Y h:i:sa");?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'Orientation',
                    pageSize: 'LEGAL',
                    title: 'CASE SCRUTINY : LIST OF DEFECTS <?php echo date("d-m-Y h:i:sa");?>',
                    filename: 'CASE-SCRUTINY:LIST-OF-DEFECTS-<?php echo date("d-m-Y h:i:sa");?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#defect_report_wrapper .col-md-6:eq(0)');
    });
</script>