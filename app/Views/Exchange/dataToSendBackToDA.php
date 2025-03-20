<?php
if(count($caseList)>0)
{
    ?>
    <input type="hidden" id="causelistDate" value="<?=$causelistDate?>">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group row">
            <div class="col-md-3">
                <label for="attendant">Select Attendant Name</label>
                <select class="form-control search-box" id="attendant" name="attendant" placeholder="attendant" style="height: 34px !important;">
                    <option value="">Select Attendant Name</option>
                    <?php
                    foreach ($attendants as $at) {
                        echo '<option value="' . $at['usercode'] . '">' . $at['name'] . ' (' . $at['empid'] . ')</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" id="btnDownloadROPTop" name="btnDownloadROP" class="btn btn-success btn-block  generateROP" onclick="return doDispatch();">&nbsp;Dispatch Files
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
        <table id="tblCasesForDispatch" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="5%">S.No.</th>
                    <th width="5%">Court No</th>
                    <th width="5%">Item No</th>
                    <th width="15%">Case Number</th>
                    <th width="30%">Causetitle</th>
                    <th width="20%">DA Name(Section)</th>
                    <th width="10%">
                        <label>
                            <input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All
                        </label>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
            $s_no = 1;
            $checkBocValue = "";
            foreach ($caseList as $case) {
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
                    $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4);
                    ?>

                    <td>

                        <?php echo wordwrap($case['case_no'], 30, "\n", true); ?>
                    </td>


                    <td>
                        <?php
                        echo $case['petitioner_name'] . "<br/><centre>Vs.</centre><br/> " . $case['respondent_name'];
                        ?>
                    </td>
                    <td>
                        <?=$case['daname']?> (<?=$case['section']?>)
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
    echo "<div class='form-group col-md-12'><label class = 'text-danger'>Nothing to Dispatch!!</label></div>";
}
?>
<script>
    $(document).ready(function()
    {
        $('.search-box').select2();
    });

    function selectallMe() 
    {
        var checkBoxList=$('[name="proceeding[]"]');
        if ($('#allCheck').is(':checked'))
        {
            for (var i1 = 0; i1<checkBoxList.length; i1++){
                checkBoxList[i1].checked=true;
            }
        }else{
            for (var i1 = 0; i1<checkBoxList.length; i1++){
                checkBoxList[i1].checked=false;
            }
        }
    }

    function doDispatch()
    {
        updateCSRFToken();
        var attendant=0;
        var selectedCases = [];
        var toExit=0;
        $('#tblCasesForDispatch input:checked').each(function ()
        {
            if ($(this).attr('name') != 'allCheck')
            {
                var ckValue = $(this).attr('value');
                var diary = ckValue.split("#")[0];
                selectedCases.push(ckValue);
            }
        });

        if(toExit==1)
        {
            return false;
        }
        if($("#attendant").val()==0)
        {
            alert("Select Attendant name.");
            $("#attendant").focus();
            return false;
        }

        attendant = $("#attendant").val();
        console.log(attendant);
        if (selectedCases.length <= 0)
        {
            alert("Please Select at least one case for dispatch..");
            return false;
        }

        var causelistDate = $("#causelistDate").val();
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            data:
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                selectedCases:selectedCases,
                attendant:attendant,
                causelistDate:causelistDate
            },
            url: "<?= site_url('Exchange/causeListFileMovement/doSendBackToDA') ?>",
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
                    swal({
                        title: "Success",
                        text: 'Total '+result.data+' Files dispatched to Dealing Assistant Successfully!',
                        icon: "success"
                    }).then(() => {
                        $('#btnGetCases').click();
                    });
                }
                else
                {
                    $("#loader").html('');
                    updateCSRFToken();
                    alert("There is some problem.");
                    return false;
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
</script>