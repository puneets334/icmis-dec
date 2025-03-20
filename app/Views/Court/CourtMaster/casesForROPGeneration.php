<div class="row">
    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 1</label>
        <select class="form-control" id="judge1" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j1):
                if (!empty($judgeinitial[0]) == $j1['jcode']) {
                    echo '<option value="' . $j1['jcode'] . '" selected="selected">' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>

    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 2</label>
        <select class="form-control" id="judge2" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j2):
                if (!empty($judgeinitial[1]) == $j2['jcode']) {
                    echo '<option value="' . $j2['jcode'] . '" selected="selected">' . $j2['jcode'] . ' - ' . $j2['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j2['jcode'] . '" >' . $j2['jcode'] . ' - ' . $j2['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 3</label>
        <select class="form-control" id="judge3" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j3):
                if (!empty($judgeinitial[2]) == $j3['jcode']) {
                    echo '<option value="' . $j3['jcode'] . '" selected="selected">' . $j3['jcode'] . ' - ' . $j3['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j3['jcode'] . '">' . $j3['jcode'] . ' - ' . $j3['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>

    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 4</label>
        <select class="form-control" id="judge4" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j4):
                if (!empty($judgeinitial[3]) == $j4['jcode']) {
                    echo '<option value="' . $j4['jcode'] . '" selected="selected">' . $j4['jcode'] . ' - ' . $j4['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j4['jcode'] . '">' . $j4['jcode'] . ' - ' . $j4['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 5</label>
        <select class="form-control" id="judge5" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j5):
                if (!empty($judgeinitial[4]) == $j5['jcode']) {
                    echo '<option value="' . $j5['jcode'] . '" selected="selected">' . $j5['jcode'] . ' - ' . $j5['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j5['jcode'] . '">' . $j5['jcode'] . ' - ' . $j5['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 6</label>
        <select class="form-control" id="judge6" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j6):
                if (!empty($judgeinitial[5]) == $j6['jcode']) {
                    echo '<option value="' . $j6['jcode'] . '" selected="selected">' . $j6['jcode'] . ' - ' . $j6['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j6['jcode'] . '">' . $j6['jcode'] . ' - ' . $j6['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 7</label>
        <select class="form-control" id="judge7" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j7):
                if (!empty($judgeinitial[6]) == $j7['jcode']) {
                    echo '<option value="' . $j7['jcode'] . '" selected="selected">' . $j7['jcode'] . ' - ' . $j7['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j7['jcode'] . '">' . $j7['jcode'] . ' - ' . $j7['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 8</label>
        <select class="form-control" id="judge8" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j8):
                if (!empty($judgeinitial[7]) == $j8['jcode']) {
                    echo '<option value="' . $j8['jcode'] . '" selected="selected">' . $j8['jcode'] . ' - ' . $j8['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j8['jcode'] . '">' . $j8['jcode'] . ' - ' . $j8['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 9</label>
        <select class="form-control" id="judge9" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j9):
                if (!empty($judgeinitial[8]) == $j9['jcode']) {
                    echo '<option value="' . $j9['jcode'] . '" selected="selected">' . $j9['jcode'] . ' - ' . $j9['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j9['jcode'] . '">' . $j9['jcode'] . ' - ' . $j9['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>


    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 10</label>
        <select class="form-control" id="judge10" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j10):
                if (!empty($judgeinitial[9]) == $j10['jcode']) {
                    echo '<option value="' . $j10['jcode'] . '" selected="selected">' . $j10['jcode'] . ' - ' . $j10['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j10['jcode'] . '">' . $j10['jcode'] . ' - ' . $j10['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>



    <div class="col-sm-12 col-md-3 mb-3">
        <label class="">Judge 11</label>

        <select class="form-control" id="judge11" name="judge[]">
            <option value="0">Select Judge</option>
            <?php
            foreach ($judge as $key => $j11):
                if (!empty($judgeinitial[10]) == $j11['jcode']) {
                    echo '<option value="' . $j11['jcode'] . '" selected="selected">' . $j11['jcode'] . ' - ' . $j11['jname'] . '</option>';
                } else {
                    echo '<option value="' . $j11['jcode'] . '">' . $j11['jcode'] . ' - ' . $j11['jname'] . '</option>';
                }
            endforeach;
            ?>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td><label>Court No</label> </td>
                <td>
                    <font class="text-danger Bold"><?= $courtNoDisplay ?></font>
                </td>
                <td>
                    <label>Signing Authority 1</label>
                </td>
                <td>
                    <?= $username; ?>
                </td>

                <td><label>Signing Authority 2 </label></td>
                <td>
                    <select class="form-control" id="user2" name="user2" placeholder="user2">
                        <option value="">Select Signing Authority</option>
                        <?php
                        foreach ($cmnsh as $cm) {
                            echo '<option value="' . $cm['usercode'] . '">' . $cm['name'] . ' (' . $cm['empid'] . ')</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-sm-12 col-md-3 mb-3">
        <label for="judge4">Select By Item No</label>
        <input type="text" class="form-control" name="snoselect" id="snoselect" onblur="selectItem();">
    </div>
    <div class="col-sm-12 col-md-3 mb-3">
        <label>&nbsp;</label>
        <button type="submit" id="btnDownloadROPTop" name="btnDownloadROP" class="quick-btn mt-26 generateROP" onclick="return generateAndDownloadROP();"><i class="fa fa-fw fa-download"></i>&nbsp;Generate ROP</button>
    </div>
</div>


<input type="hidden" name="courtNo" id="courtNo" value="<?= $courtno ?>" />
<input type="hidden" name="courtNo" id="courtNo" value="<?= $courtno ?>" />

<div class="table-responsive">
    <table id="tblCasesForGeneration" class="table table-striped custom-table">
        <thead>
            <tr>
                <th width="5%">S.No.</th>
                <th width="5%">Item No</th>
                <th width="25%">Case Number</th>
                <th width="35%">Causetitle</th>
                <th width="14%">Reportable/<br /> Non-Reportable</th>
                <th><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $s_no = 1;
            $checkBocValue = "";
            foreach ($caseList as $case) {
                $checkBocValue = $case['diary_no'] . '#' . $case['roster_id'] . '#' . $case['court_number'] . '#' . $case['item_number']
            ?>
                <tr>
                    <td>
                        <?php echo $s_no; ?>
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
                        <select class="form-control pull-left" id="<?= $checkBocValue ?>" name="<?= $checkBocValue ?>">
                            <option value="0" selected>Non-Reportable</option>
                            <option value="1">Reportable</option>
                        </select>
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

<div class="row">
    <div class="col-sm-12 col-md-12 mb-3 text-center">
        <button type="submit" id="btnDownloadROPBottom" name="btnDownloadROP" class="quick-btn mt-26 generateROP" onclick="return generateAndDownloadROP();">
            <i class="fa fa-fw fa-download"></i>&nbsp;Generate ROP
        </button>
    </div>
</div>



<script type="text/javascript">
    $("#tblCasesForGeneration").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    function selectallMe() {
        var checkBoxList = $('[name="proceeding[]"]');

        if ($('#allCheck').is(':checked')) {

            for (var i1 = 0; i1 < checkBoxList.length; i1++) {
                checkBoxList[i1].checked = true;
            }

        } else {
            for (var i1 = 0; i1 < checkBoxList.length; i1++) {
                checkBoxList[i1].checked = false;
            }
        }
    }

    function generateAndDownloadROP() {
        var causelistDate = $('#causelistDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        var bench = $('#bench').val();
        var usercode = $('#usercode').val();
        var user2 = $('#user2').val();
        if (causelistDate == "") {
            alert("Please fill Causelist Date..");
            $('#causelistDate').focus();
            return false;
        }
        if (pJudge == "") {
            alert("Please Select Presiding Judge..");
            $('#pJudge').focus();
            return false;
        }
        if (causelistType == "") {
            alert("Please Select Type of Causelist..");
            $('#causelistType').focus();
            return false;
        }
        if (bench == "") {
            alert("Please Select Bench..");
            return false;
        }
        /* var checkBoxList=$('[name="proceeding"]');
         alert(checkBoxList.length);*/
        var selectedCases = [];
        $('#tblCasesForGeneration input:checked').each(function() {
            if ($(this).attr('name') != 'allCheck')
                selectedCases.push($(this).attr('value'));
        });
        if (selectedCases.length <= 0) {
            alert("Please Select at least one case for generation..");
            return false;
        }
        if (user2 == "" || user2 == "0") {
            alert("Please Select name of Signatory Authority 2.");
            return false;
        }

    }

    function selectItem() {
        var checkBoxList = document.getElementsByName("proceeding[]");
        for (var i1 = 0; i1 < checkBoxList.length; i1++) {
            checkBoxList[i1].checked = false;
        }
        var l = document.getElementById("snoselect").value;
        temp = l.split(",");
        var t;
        var p;
        var s = new Array();
        var j = 0;
        for (a in temp) {
            t = temp[a].split("-");
            var k = parseInt(t.length);
            if (k == 2) {
                var f = parseInt(t[0]);
                var f1 = parseInt(t[1]);
                for (var h = f; h <= f1; h++) {
                    s[j] = h;
                    j = j + 1;
                }
            } else {
                s[j] = temp[a];
                j = j + 1;
            }
        }
        for (l = 0; l < s.length; l++) {
            var checkitemno = "";
            var i = 0;
            var selectedCases = [];
            var tab = document.getElementById("tblCasesForGeneration"); // table with id tbl1
            var elems = tab.getElementsByTagName("input");
            var len = elems.length;

            for (var ai = 0; ai < len; ai++) {
                if (elems[ai].type == "checkbox") {
                    selectedCases.push(elems[ai].value);
                }
            }
            if (selectedCases.length > 0) {
                for (var eleentno = 0; eleentno < selectedCases.length; eleentno++) {
                    filename = selectedCases[eleentno];
                    checkitemno = filename.substring(filename.lastIndexOf('#') + 1);
                    checkitemno = parseInt(checkitemno);
                    if (checkitemno == parseInt(s[l])) {
                        checkBoxList[i - 1].checked = true;
                    }
                    i = i + 1;
                }
            }
        }
    }
</script>