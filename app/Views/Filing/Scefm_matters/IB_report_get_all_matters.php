<?php
if(isset($all_matters) && sizeof($all_matters)>0 ) {
    ?>
    <center> <h3>SC-eFM Filed Matters Pending for Transfer</h3></center>
    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
        <table  id="datatable_report" class="table table-bordered table-striped datatable_report">
        <thead>
        <tr>
            <th>#</th>
            <th>Efiling Number</th>
            <th>Diary Number</th>
            <th>Case Type</th>
            <th>Diary Date</th>
            <th>Cause Title</th>
            <th>Diary User</th>
            <th>Diary Modified</th>
            <th>Party Modified</th>
            <th>Transfer</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        $crc=array(11, 12, 19, 25, 26, 9, 10, 39);
        foreach ($all_matters as $result)
        {$i++;
            ?>
            <tr>
                <td><?php echo $i;?></td>
                <td onclick="efiling_number('<?=$result['efiling_no']?>')""><u><b><?php echo $result['efiling_no'];?></u></b></td>
                <td><?php echo $result['diary_number'];?>
                    <?php  if($result['ref_special_category_filing_id']!=null and $result['ref_special_category_filing_id']!='' and $result['ref_special_category_filing_id']!=0 )
                    {?>
                        <span id="blink_text">urgent</span>
                        <?php
                        echo "<br><font color='purple'>".$result['category_name']."</font> ";
                    } ?>
                </td>
                <td><?php echo $result['short_description'];?></td>
                <td><?php echo $result['diary_date'];?></td>
                <td><?php echo $result['cause_title'];?></td>
                <td><?php echo $result['diary_user'];?></td>
                <td><?php echo $result['diary_modified'];?></td>
                <td><?php echo $result['party_modified'];?></td>
                <td><?php if($result['diary_modified']=='Yes' && $result['party_modified']=='Yes'){
                        if(in_array($result['casetype_id'], $crc)) {?>
                            <button class="btn ui-button-text-icon-primary"  id="transfer_<?php echo $result['diary_no'];?>" style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="update_case(<?php echo $result['casetype_id'] ;?>,<?php echo $result['diary_no']?>);">Transfer to Judicial Section</button>
                        <?php } else {?>
                            <button class="btn ui-button-text-icon-primary" id="transfer_<?php echo $result['diary_no'];?>" style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="update_case(<?php echo $result['casetype_id']; ?>,<?php echo $result['diary_no']?>);">Dispatch to High Court</button>
                        <?php }} ?></td>
            </tr>
            <?php
        }

        ?>
        </tbody>
    </table>
    </div>


    <script>
        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>

    <?php }else{
       echo "No Record Found!!";}
       ?>


