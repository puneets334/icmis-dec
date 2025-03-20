<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial > Amend Causetitle</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url() ?>/Judicial/AmendCausetitle"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                    </div>
                    <div class="card-footer">
                        <div class="p-5">
                            <?php if (isset($casedesc['section'])) {
                                $dis_remark = '';
                            ?>

                                <div style="margin-left: 50%;">
                                    <button id="prnnt1" class="btn btn-primary ">Print <i class="fas fa-print" aria-hidden="true"></i></button>
                                </div>

                                <div id="prnnt" contenteditable="true">
                                    <div align="right">
                                        <b style="font-size: 20px">Section: <?= $casedesc['section'] ?></b>
                                    </div>
                                    <div align="center">
                                        <div style="text-align: center;font-size:25px;margin-top: 30px">
                                            AMENDED CAUSE TITLE
                                        </div>
                                        <div style="text-align: center;font-size:20px;margin-top: 30px">
                                            IN THE SUPREME COURT OF INDIA
                                        </div>
                                        <div style="text-align: center;margin-bottom: 15px;font-size: 20px">
                                            <b>(<?= $casedesc['c_r'] ?> Appellate Jurisdiction)</b>
                                        </div>
                                        <b>
                                            <u>
                                                <div style="font-size: 20px;margin-top: 20px;margin-top: 50px">
                                                    <?= $casedesc['casename'] ?>
                                                </div>
                                            </u>
                                        </b>
                                        <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
                                            ( <?= $casedesc['headTtitle'] ?> )

                                        </p>
                                        <div align="center" style="width: 100%;clear: both;margin-top: 70px">
                                            <table cellpadding="10" cellspacing="10" style="width: 100%">
                                                <tbody>
                                                    <?php
                                                    $petitioner_data = $casedesc['petitioner_data'];
                                                    if (!empty($petitioner_data)) {
                                                        foreach ($petitioner_data as $row_p) {
                                                            $tmp_name = '';
                                                            $tmp_addr = '';
                                                            $dstName = '';

                                                    ?>
                                                            <tr>
                                                                <td style="font-size: 13pt" face="Times New Roman" WIDTH="4%" align="left" valign="top">
                                                                    <?php
                                                                    echo "$row_p[sr_no_show]";
                                                                    if ($row_p["pflag"] == 'O' or $row_p["pflag"] == 'D') {
                                                                        echo "*";
                                                                        $dis_remark = $dis_remark . $row_p['remark_del'] . "<br>";
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <td style="font-size: 13pt" face="Times New Roman" align="left" WIDTH="45%">
                                                                    <?php
                                                                    $tmp_name =  trim($row_p["partyname"], ' ');
                                                                    if ($row_p["prfhname"] != "") {
                                                                        $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
                                                                    }
                                                                    if ($row_p["addr1"] != "") {
                                                                        $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";
                                                                    }
                                                                    if ($row_p['ind_dep'] != 'I' && $row_p['deptname'] != '' && trim(str_replace($row_p['deptname'], '', $row_p['partysuff']) != '')) {
                                                                        $tmp_addr = $tmp_addr . " " . trim(str_replace($row_p['deptname'], '', $row_p['partysuff'])) . ", ";
                                                                    }
                                                                    if ($row_p["addr2"] != "") {
                                                                        $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";
                                                                    }
                                                                    if ($row_p["city"] != "") {
                                                                        $dstName = '';
                                                                        if ($row_p["dstname"] != "") {
                                                                            $dstName .= " , DISTRICT: " . $row_p["dstname"];
                                                                        }
                                                                        $tmp_addr = $tmp_addr . $dstName . " ," . $row_p["city"] . " ";
                                                                    }
                                                                    if ($row_p["state"] != "") {
                                                                        $tmp_addr = $tmp_addr . ", " . $row_p["state"] . " ";
                                                                    }
                                                                    if ($tmp_addr != "") {
                                                                        $tmp_name = $tmp_name . "<br>" . $tmp_addr . "";
                                                                    }

                                                                    if ($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] != NULL) {
                                                                        $tmp_name = $tmp_name . "<br><b>" . " [" . $row_p["remark_lrs"] . "]</b>";
                                                                    }

                                                                    if ($row_p["pflag"] == 'O' || $row_p["pflag"] == 'D') {
                                                                        $tmp_name = $tmp_name . "<br><b>" . " [" . $row_p["remark_del"] . "]</b>";
                                                                    }
                                                                    echo strtoupper($tmp_name);
                                                                    ?>
                                                                </td>

                                                                <td style="font-size: 13pt;text-align: right" WIDTH="50%">
                                                                    <?php
                                                                    if ($casedesc['c_code'] == '3' || $casedesc['c_code'] == '4') {
                                                                        echo "...APPELLANT NO. $row_p[sr_no_show]";
                                                                    } else {
                                                                        echo "...PETITIONER NO. $row_p[sr_no_show]";
                                                                    }
                                                                    ?>
                                                                </td>

                                                            </tr>
                                                    <?php
                                                        }
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <table cellpadding="10" cellspacing="10" style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle;font-size: 13pt;text-align: center">
                                                            VERSUS
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table cellpadding="10" cellspacing="10" style="width: 100%">
                                                <tbody>
                                                    <?php
                                                    $respondant_data = $casedesc['respondant_data'];
                                                    if (!empty($respondant_data)) {
                                                        // echo "<pre>"; print_r($respondant_data[1]); 
                                                        foreach ($respondant_data as $row_p) {
                                                            $tmp_name = '';
                                                            $tmp_addr = '';
                                                            $dstName = '';
                                                    ?>
                                                            <tr>
                                                                <td style="font-size: 13pt" face="Times New Roman" WIDTH="4%" align="left" valign="top">
                                                                    <?php
                                                                    echo "$row_p[sr_no_show]";
                                                                    if ($row_p["pflag"] == 'O' or $row_p["pflag"] == 'D') {
                                                                        echo "*";
                                                                        $dis_remark = $dis_remark . $row_p['remark_del'] . "<br>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="font-size: 13pt" face="Times New Roman" align="left" WIDTH="45%">
                                                                    <?php
                                                                    $tmp_name = $row_p["partyname"];
                                                                    if ($row_p["prfhname"] != "") {
                                                                        $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
                                                                    }

                                                                    if ($row_p["addr1"] != "") {
                                                                        $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";
                                                                    }
                                                                    if ($row_p['ind_dep'] != 'I' && $row_p['deptname'] != '' && trim(str_replace($row_p['deptname'], '', $row_p['partysuff']) != '')) {
                                                                        $tmp_addr = $tmp_addr . " " . trim(str_replace($row_p['deptname'], '', $row_p['partysuff'])) . ", ";
                                                                    } else {
                                                                        $tmp_addr = $tmp_addr . " " . trim($row_p['partysuff']) . ", ";
                                                                    }
                                                                    if ($row_p["addr2"] != "") {
                                                                        $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";
                                                                    }

                                                                    if ($row_p["city"] != "") {
                                                                        $dstName = '';
                                                                        if ($row_p["dstname"] != "") {
                                                                            $dstName .= " , DISTRICT: " . $row_p["dstname"];
                                                                        }
                                                                        $tmp_addr = $tmp_addr . $dstName . " ," . $row_p["city"] . " ";
                                                                    } else {
                                                                        $dstName = '';
                                                                        if ($row_p["dstname"] != "") {
                                                                            $dstName .= " , DISTRICT: " . $row_p["dstname"];
                                                                        }
                                                                        $tmp_addr = $tmp_addr . $dstName . " ," . $row_p["city"] . " ";
                                                                    }
                                                                    if ($row_p["state"] != "") {
                                                                        $tmp_addr = $tmp_addr . ", " . $row_p["state"] . " ";
                                                                    }
                                                                    if ($tmp_addr != "") {
                                                                        $tmp_name = $tmp_name . "<br>" . $tmp_addr . "";
                                                                    }

                                                                    if ($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] != NULL) {
                                                                        $tmp_name = $tmp_name . "<br><b>" . " [" . $row_p["remark_lrs"] . "]</b>";
                                                                    }

                                                                    if ($row_p["pflag"] == 'O' || $row_p["pflag"] == 'D') {
                                                                        $tmp_name = $tmp_name . "<br><b>" . " [" . $row_p["remark_del"] . "]</b>";
                                                                    }
                                                                    echo strtoupper($tmp_name);
                                                                    ?>
                                                                </td>
                                                                <td style="font-size: 13pt;text-align: right" WIDTH="50%">
                                                                    <?php
                                                                    echo "...RESPONDENT NO. $row_p[sr_no_show]";
                                                                    ?>

                                                                </td>

                                                            </tr>
                                                    <?php
                                                        }
                                                    }

                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div style="text-align: left;font-size:13pt;margin-top: 30px" width="100%">
                                        Remarks: <?= $dis_remark; ?>
                                    </div>
                                    <div style="text-align: right;font-size:13pt;margin-top: 30px" width="80%">
                                        <b><br><br>ASSISTANT REGISTRAR</b>
                                    </div>
                                </div>

                            <?php } else if (isset($casedesc['casedet'])) { ?>
                                <p style="text-align: center;color: red;font-size: 20px;"><?= $casedesc['casedet'] ?>
                                <p>
                                <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- /.content -->

<script>
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var mywindow = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
        var is_chrome = Boolean(mywindow.chrome);
        mywindow.document.write(prtContent);
        if (is_chrome) {
            setTimeout(function() { // wait until all resources loaded
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                mywindow.print(); // change window to winPrint
            }, 20);
        } else {
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
        }
    });
</script>