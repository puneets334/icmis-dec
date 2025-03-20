
<?php if(isset($result) && sizeof($result)>0 && is_array($result)){ ?>

    <div id="sar" style="border: 0px solid red"></div>
    <div id="msgsar"></div>

    <?php   if(!empty($listing)){ ?>
<div class="glowtext">CASE IS LISTED YOU CAN NOT DELETE ANY RECORD, Please Contact Server Room</div>
            <?php } ?>
    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <table  id="report" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Document No</th>
                <th>Description & Remark</th>
                <th>No of Copies</th>
                <th>Fee</th>
                <th>Filed By</th>
                <th>Party / For Res</th>
                <th>Advocate</th>
                <th>Filing Date</th>
                <th>Taken By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sno=1; //echo '<pre>';print_r($result);
            foreach ($result as $row) { ?>
                <tr id="row<?php echo $sno;?>"><th><?php echo $sno; ?></th>
                    <td><?php echo $row['docnum'].'/'.$row['docyear']; ?></td>
                    <td><?php
                        if($row['doccode']==8) echo "I.A. - ";
                        echo $row['docdesc']; if($row['other1'] != '') echo ' - '.$row['other1'];
                        echo ",,Remark=".$row['remark'];
                        if(($row['doccode']== 1 || $row['doccode']== 12 || $row['doccode']== 13) && ($row['advocate_id'] != 0)){
                            echo '<br><span class=undertxt>Please remove advocate from case also</span>';
                        }

                        ?>
                    </td>
                    <td><?php echo $row['no_of_copy']; ?></td>
                    <td><?php echo $row['docfee']; ?></td>
                    <td><?php echo $row['filedby']; ?></td>
                    <td><?php echo $row['party'].$row['forresp']; ?></td>
                    <td><?php echo $row['advname']; ?></td>
                    <td><?=!empty($row['ent_dt']) ? date('d-m-Y H:i:s',strtotime($row['ent_dt'])):''; ?></td>
                    <td><?php echo $row['entryuser'];?></td>
                    <td><?php if($row['iastat']=='P') echo 'Pending'; else if($row['iastat']=='D') echo 'Disposed';  ?></td>
                    <td style="width: 10%;"><?php if(empty($listing)){ ?>
                            <input type="button" id="<?php echo $row['docd_id']; ?>" onclick="old_delete_ld(this.id)" value="Delete" disabled>
                        <?php } ?>
                        <input type="button" id="<?php echo $row['docd_id']; ?>" onclick="old_update_ld(this.id)" value="UPDATE"/>
                    </td></tr>
            <?php $sno++; }?>
            </tbody>

        </table>
    </div>

    <script>
        function old_update_ld(docd_id) {
            $.ajax({
                type: "GET",
                data: {docd_id: docd_id},
                url: "<?php echo base_url('ARDRBM/IA/get_ia_entry_date_correction_content'); ?>",
                success: function (data)
                {
                    $('#sar').html(data);
                }
            });
        }
        function calcelFunct(){
            $('#sar').html('');
        }

        function updateFunct(docd_id){
            $("#sp_close").css("display","none");
            var regNum = new RegExp('^[0-9]+$');
            var new_filing_date=$("#new_filing_date").val()
            if($("#new_filing_date").val()=='') {
                alert("Please enter filing date");
                $("#new_filing_date").focus();
                return false;
            }
            $.ajax({
                type: "GET",
                data: {type:'U',docd_id:docd_id, new_filing_date:new_filing_date},
                url: "<?php echo base_url('ARDRBM/IA/update_ia_entry_date_correction_content'); ?>",
                success: function (data)
                {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msgsar').html(resArr[1]);
                        setTimeout(function() {
                            window.location.reload();
                        }, 5000);
                    } else if (resArr[0] == 3) {
                        $('#msgsar').html(resArr[1]);
                        $('.alert-error').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                    }

                }
            });
        }
    </script>
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
