<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header {
    padding: .75rem 0;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Receive files from Court Master (NSH)</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php
                            if(count($caseList)>0)
                            {
                                ?>
                                <div class="form-group col-sm-3 pull-right">
                                    <label>&nbsp;</label>
                                    <button type="submit" id="btnDownloadROPTop" name="btnDownloadROP" class="btn btn-success btn-block pull-right" onclick="doReceive();">&nbsp;Receive Files </button>
                                </div>

                                <table id="tblCasesForReceive" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No.</th>
                                            <th width="15%">Attendant Name</th>
                                            <th width="15%">Case Number</th>
                                            <th width="25%">Causetitle</th>
                                            <th width="20%">Sent by (Sent On)</th>
                                            <th width="10%">
                                                <label>
                                                    <input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label>
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
                                                <?= $s_no; ?>
                                            </td>
                                            <td>
                                                <?= $case['attendant']; ?>
                                            </td>
                                            <td>
                                                <?php echo  wordwrap($case['reg_no_display'], 30, "\n", true); ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $case['petitioner_name'] . "<br/><centre>Vs.</centre><br/> " . $case['respondent_name'];
                                                ?>
                                            </td>
                                            <td><?=$case['updated_by']?><br/> At: <?=date('d-m-Y h:i:s A', strtotime($case['updated_on']))?></td>
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
                                <?php
                            }
                            else{
                                echo "<div class='col-sm-12'><h4 class='text-danger'>Nothing to Receive!!</h4></div>";
                            }
                            ?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    function doReceive()
    {
        var selectedCases = [];
        $('#tblCasesForReceive input:checked').each(function ()
        {
            if ($(this).attr('name') != 'allCheck')
            {
                var ckValue = $(this).attr('value');
                selectedCases.push(ckValue);
            }
        });
        if (selectedCases.length <= 0)
        {
            swal({
                title: "Warning",
                text: 'Please select at least one case',
                icon: "warning"
            }).then(() => {
                return false;
            });
        }
        else
        {
            $.ajax({
                type: 'GET',
                data:
                {
                    selectedCases:selectedCases
                },
                url: "<?= site_url('Exchange/causeListFileMovement/doReceiveFromCM') ?>",
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
                            text: 'Selected Files Received Successfully!',
                            icon: "success"
                        }).then(() => {
                            window.location = "<?php echo base_url('Exchange/causeListFileMovement/receiveFromCM'); ?>";
                            return false;
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