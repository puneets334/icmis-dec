<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Work_done)):?>
                <table id="ReportWeekly" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th rowspan="2">SNo.</th><th rowspan="2">Section</th><th rowspan="2">Name</th><th rowspan="2">Designation</th>
                    <th colspan="2">Document</th>
                    <th colspan="2">Cases Updated for Listing</th>
                    <th rowspan="2">No. of Office<br>Report Prepared</th>
                    <th colspan="3">Notice</th>
                    <th rowspan="2">Red Category Cases</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [ent_dt] => 2014-09-09 10:28:09+05:30 [dno] => XIV-A [courtno] => 2 [name] => BHARATI SHARMA 
                    //[section_name] => [purpose] => Next Week / Week Commencing / C.O.Week [short_description] => C.A. No.
                    // [fyr] => 2015 [active_reg_year] => 2015 [active_fil_dt] => 2015-11-02 00:00:00+05:30 
                    //[conn_key] => 331572013 [active_fil_no] => 03-013333-013333 [pet_name] => M/S. HARVEL AGUA INDIA PRIVATE LIMITED 
                    //[res_name] => THE STATE OF HIMACHAL PRADESH [pno] => 1 [rno] => 4 [casetype_id] => 3 [ref_agency_state_id] => 571779 
                    //[diary_no_rec_date] => 2013-10-17 00:00:00+05:30 [remark] => [diary_no] => 331572013 [next_dt] => 2022-11-02 
                    //[subhead] => 82 [judges] => 219,281 [coram] => 219,273,288 [brd_slno] => 46 [clno] => 1 [listorder] => 7 
                    //[reg_no_display] => C.A. No. 13333/2015 )
                    foreach($Work_done as $row): //print_r($ro); exit; ?>
                        <th><?php echo $sno;?></th><td><?php echo $row['section_name']; ?></td>
                        <td><?php echo "<span id='name_$row[usercode]'>".$row['name'].'/'.$row['empid']."</span>";?></td>
                        <td><?php echo $row['type_name'];?></td>
                        <td><?php echo "<span style='cursor:pointer' id='doc_$row[usercode]'>".$row['totdoc']."</span>"; ?></td>
                        <td style="background:#F08080;">
                            <?php echo "<span style='cursor:pointer' id='notvdoc_$row[usercode]'>".$row['totdoc_not']."</span>"; ?>
                        </td>
                        <td><?php echo "<span style='cursor:pointer' id='totup_$row[usercode]'>".$row['totup']."</span>"; ?></td>
                        <td>
                            <?php echo "<span style='cursor:pointer' id='supuser_$row[usercode]'>".$row['supuser']."</span>"; ?>
                        </td>
                        <td><?php echo $row['totoff']; ?></td>
                        <td><?php echo $row['totnot']; ?></td>
                        <td style="background:#F08080;"><?php echo $row['p_notice_not_made']; ?></td>
                        <td style="background:#F08080;"><?php echo $row['d_notice_not_made']; ?></td>
                        <td style="background:#F08080;"><?php echo $row['red']; ?></td>
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
$("#ReportWeekly").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>

           