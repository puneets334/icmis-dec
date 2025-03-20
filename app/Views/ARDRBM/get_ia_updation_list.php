<?php if(!empty($casetype)) { ?>
    <div style="color:blue;text-align: center;font-weight: bold;">
        <?php
        echo "Case No.-";

        if ($casetype['fil_no'] != '' || $casetype['fil_no'] != NULL) {
            echo '[M]' . $casetype['short_description'] . SUBSTR($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
        }

        if ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL) {
            echo ',[R]' . $casetype['short_description'] . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
        }
        echo ", Diary No: " . substr($diary_no, 0, -4) . '/' . substr($diary_no, -4);
        echo "<br>" . $casetype['pet_name'];
        if ($casetype['pno'] == 2) {
            echo " <span style='color:#72bcd4'>AND ANR</span>";
        }else if ($casetype['pno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";{
            echo " <font style=color:black>&nbsp; Versus &nbsp;</font> ";
            echo $casetype['res_name'];
        }
        if ($casetype['rno'] == 2) {
            echo " <span style='color:#72bcd4'>AND ANR</span>";
        }else if ($casetype['rno'] > 2) {
            echo " <span style='color:#72bcd4'>AND ORS</span>";
          }
        if ($casetype['c_status'] == 'P') {
            echo "<br/>The Case is  <span class='text-success'> Pending</span>";
        }
        ?>

    </div><br/>
    <?php }?>
<?php  if ($casetype['c_status'] == 'D') { ?>
    <font style="color:red;font-size: larger"><br>!!!The Case is Disposed!!!</font>
    <?php  }else{ ?>
<?php
if($is_allowed!=1 and $section_officer!=1 and $session_user!=1){ ?>
        <div class="sorry text-danger">Only IB section user/Additional Registrar or Concerned Section Dealing Assistant/OFFICER can update Loose Documents !!!</div>
        <?php }else{ ?>

<?php if(isset($result) && sizeof($result)>0 && is_array($result)){ ?>

    <div id="sar" style="border: 0px solid red"></div>
    <div id="msgsar"></div>

    <?php $is_listed = 0;
    if(!empty($listing)){
        $is_listed = 1;
        if ($section_officer == 1 || $IB_officer == 1) { ?>
            <div class="glowtext text-danger">CASE IS LISTED. Please Contact Listing Branch before making any updation!!</div>
            <?php  } else { ?>
            <div class="glowtext text-danger">CASE IS LISTED YOU CAN NOT DELETE/UPDATE ANY RECORD, Please Contact Server Room</div>
            <?php  } ?>

            <?php } ?>
    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <table  id="report" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Document No</th>
                <th>Document Type</th>
                <th>Description</th>
                <th>Remark</th>
                <th>No of Copies</th>
                <th>Fee</th>
                <th>Filed By[Advocate]</th>
                <th>Filing Date</th>
                <th>Received By</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sno=1;  $from='';  $ia_listed=0; //echo '<pre>';print_r($result);
            foreach ($result as $row) {  $from='';  $ia_listed=0; ?>
                <tr id="row<?php echo $sno;?>"><th><?php echo $sno; ?></th>
                    <td><?php echo $row['docnum'].'/'.$row['docyear']; if($row['is_efiled']=='Y'){ echo "<br> <font color='blue' class='text-primary'> E-Filed </font><br>"; } ?></td>
                    <td><?php
                        if($row['doccode']==8){ echo "I.A. - "; }
                        echo $row['docdesc'];
                        if(($row['doccode']== 1 || $row['doccode']== 12 || $row['doccode']== 13) && ($row['advocate_id'] != 0)){
                            echo '<br><span class="undertxt text-danger ">Please remove advocate from case also</span>';
                        }

                        if($row['doccode'] == 8) {

                        }

                        ?>
                    </td>
                    <td><?php echo $row['other1'];  ?></td>
                    <td><?php echo $row['remark']; ?></td>
                    <td><?php echo $row['no_of_copy']; ?></td>
                    <td><?php echo $row['docfee']; ?></td>
                    <td><?php echo $row['filedby']."[".$row['advocate_id']."]"; ?></td>
                    <td><?=!empty($row['ent_dt']) ? date('d-m-Y h:i:s A',strtotime($row['ent_dt'])):''; ?></td>

                    <td><?php echo $row['entryuser'];?></td>
                    <td style="width: 10%;">
                        <input type="button" id="<?php echo $diary_no.'~'.$row['doccode'].'~'.$row['doccode1'].'~'.$row['docnum'].'~'.$row['docyear'].'~'.$row['advocate_id'].'~'.$from.'~'.$row['docd_id']; ?>" onclick="delete_ld(this.id)" value="Delete" <?php if(($is_listed==1||$ia_listed==1)&&$section_officer!=1 && $IB_officer != 1&& $session_user!=1) echo "disabled";?>/>
                        <input type="button" id="<?php echo $diary_no.'~'.$row['doccode'].'~'.$row['doccode1'].'~'.$row['docnum'].'~'.$row['docyear'].'~'.$row['advocate_id'].'~'.$from.'~'.$row['docd_id'].'~'.$row['is_efiled'];?>" onclick="update_ld(this.id)" value="UPDATE" <?php if(($is_listed==1|| $from=='H'|| $from=='L'||$ia_listed==1)&&$section_officer!=1 && $IB_officer != 1 && $session_user!=1) echo "disabled";?> />
                    </td>
                </tr>
            <?php $sno++; }?>
            </tbody>

        </table>
    </div>


<?php }else { ?>
    <div class="text-center align-items-center"><i class="fas fa-info"> </i> SORRY, NO RECORD FOUND !!!</div>
<?php } ?>

<script>
    $(function () {
        $("#report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });


</script>

<?php } ?>

<?php  } ?>
