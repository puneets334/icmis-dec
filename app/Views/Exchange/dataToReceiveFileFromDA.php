<?php
if(count($caseList)>0)
{
    ?>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group row">
            <div class="col-md-3">
                <label for="from" class="text-right">&nbsp;</label>
                <select class="form-control" id="action" name="action" style="height: 34px !important;">
                    <option value="">Select</option>
                    <option value="2">Receive</option>
                    <option value="3">Return</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" id="btnDownloadROPTop" name="btnDownloadROP" class="btn btn-success btn-block" style="width: 100%" onclick="return doReceive();">&nbsp;Receive Files
                </button>
            </div>
        </div>
    </div>

    <!--<input type="hidden" name="courtNo" id="courtNo" value="<?/*=$courtno*/
    ?>"/>
<input type="hidden" name="courtNo" id="courtNo" value="<?/*=$courtno*/
    ?>"/>-->
    <center><span id="loader"></span></center>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <table id="tblCasesForReceive" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="5%"><b>S.No.</b></th>
                    <th width="5%"><b>Court No</b></th>
                    <th width="5%"><b>Item No</b></th>
                    <th width="20%"><b>Case Number</b></th>
                    <th width="30%"><b>Causetitle</b></th>
                    <th width="20%"><b>Sent by (Sent On)</b></th>
                    <th><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                $s_no = 1;
                $checkBocValue = "";
                foreach ($caseList as $case)
                {
                    $checkBocValue = $case['causelist_file_movement_id'];
                    ?>
                    <tr>
                        <td>
                            <?php echo $s_no; ?>
                        </td>
                        <td>
                            <?= $case['court_number']; ?>
                        </td>
                        <td>
                            <?php echo $case['item_number']; ?>
                        </td>
                        <?php
                        $diarynumber = $case['diary_no'];
                        $diarynumber = "DIary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4);
                        ?>
                        <td>
                            <!--<?php echo $diarynumber . "<br/>" . $case['registration_number_desc']; ?>-->
                            <?php echo $diarynumber . "<br/>" . wordwrap($case['registration_number_desc'], 30, "\n", true); ?>
                        </td>
                        <td>
                            <?php
                            echo $case['petitioner_name'] . "<br/><centre>Vs.</centre><br/> " . $case['respondent_name'];
                            ?>
                        </td>
                        <td>
                            <?=$case['updated_by']?><br/> At: <?=date('d-m-Y h:i:s A', strtotime($case['updated_on']))?>
                        </td>
                        <td>
                            <input type="checkbox" id="proceeding" name="proceeding[]" value="<?= $checkBocValue ?>">
                        </td>
                    </tr>
                    <?php
                    $s_no++;
                }
            ?>
            </tbody>
        </table>
    </div>
    <hr/>
    <br/>
    <br/>
    <?php
}
else
{
    echo "<div class='col-sm-12'><h4 class='text-danger'>Nothing to Dispatch!!</h4></div>";
}
?>
<script>
    function doReceive()
    {
        updateCSRFToken();
        var selectedCases = [];
        $('#tblCasesForReceive input:checked').each(function ()
        {
            if ($(this).attr('name') != 'allCheck')
            {
                var ckValue=$(this).attr('value');
                selectedCases.push(ckValue);
            }
        });
        action=$("#action").val();
        if (selectedCases.length <= 0)
        {
            swal({
                title: "Warning",
                text: 'Please select at least one case for Receive/Return.',
                icon: "warning"
            }).then(() => {
                // window.location = "<?php echo base_url('Exchange/causeListFileMovement/receiveFromDA'); ?>";
                return false;
            });
        }
        else if(action == '' || action == null)
        {
            swal({
                title: "Warning",
                text: 'Please select action type either Receive or Return',
                icon: "warning"
            }).then(() => {
                return false;
            });
            $("#action").focus();
        }
        else
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                data:
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    selectedCases:selectedCases,
                    action:action
                },
                url: "<?= site_url('Exchange/causeListFileMovement/doReceiveFromDA') ?>",
                beforeSend: function ()
                {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result)
                {
                    if (result.data != '0')
                    {
                        updateCSRFToken();
                        $("#loader").html('');
                        alert("Total "+result.data+" Files Received Successfully!");
                        $('#btnGetCases').click();
                    }
                    else
                    {
                        $("#loader").html('');
                        alert("There is some problem.");
                        updateCSRFToken();
                    }
                    
                },
                error: function(xhr, status, error)
                {
                    $("#loader").html('');
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        }
    }

    function selectallMe()
    {
        var checkBoxList=$('[name="proceeding[]"]');

        if ($('#allCheck').is(':checked'))
        {
            for (var i1 = 0; i1<checkBoxList.length; i1++)
            {
                checkBoxList[i1].checked=true;
            }

        }
        else
        {
            for (var i1 = 0; i1<checkBoxList.length; i1++)
            {
                checkBoxList[i1].checked=false;
            }
        }
    }
</script>