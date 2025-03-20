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
                    <th width="25%">Case Number</th>
                    <th width="25%">Causetitle</th>
                    <th width="20%">Court Master(NSH)</th>
                    <th width="15%">
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
                $checkBocValue = $case['diary_no'] . '#' . $case['roster_id'];
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
                    <td><input type="hidden" id="da_<?= $case['diary_no'] ?>" value="<?=$case['dacode']?>">
                        <select class="form-control search-box" id="cmnsh_<?= $case['diary_no'] ?>">
                            <option value="0">Select</option>
                            <?php
                            foreach ($cmnsh as $cm)
                            {
                                echo '<option value="' . $cm['usercode'] . '">' . $cm['name'] . ' (' . $cm['empid'] . ')</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" id="proceeding" name="proceeding[]" value="<?= $checkBocValue ?>">
                    </td>
                </tr>
                <?php
                $s_no++;
            }   //for each
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
    $(document).ready(function()
    {
        $('.search-box').select2();
    });

    function doDispatch()
    {
        updateCSRFToken();
        var attendant=0;
        var selectedCases = [];
        var cmnshusercodes= [];
        var dacodes=[];
        var toExit=0;
        $('#tblCasesForDispatch input:checked').each(function ()
        {
            if ($(this).attr('name') != 'allCheck'){
                var ckValue=$(this).attr('value');
                var diary=ckValue.split("#")[0];
                selectedCases.push(ckValue);
                if($('#cmnsh_'+diary).val()==0){
                    alert("Select Court Master(NSH) name.");
                    $("#cmnsh_"+diary).focus();
                    toExit=1;
                    return false;
                }
                cmnshusercodes.push($('#cmnsh_'+diary).val());
                dacodes.push($('#da_'+diary).val());
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

        attendant=$("#attendant").val();
        if (selectedCases.length <= 0)
        {
            alert("Please Select at least one case for dispatch..");
            return false;
        }

        var causelistDate=$("#causelistDate").val();
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            data:
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                selectedCases:selectedCases,
                cmnshusercodes:cmnshusercodes,
                attendant:attendant,
                causelistDate:causelistDate,
                dacodes:dacodes
            },
            url: "<?= site_url('Exchange/causeListFileMovement/dispatchFileToCM') ?>",
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
                        text: 'Total '+result.data+' files dispatched to CM(NSH) Successfully!',
                        icon: "success"
                    }).then(() => {
                        window.location = "<?php echo base_url('Exchange/causeListFileMovement/sendFileToCM'); ?>";
                    });
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
</script>