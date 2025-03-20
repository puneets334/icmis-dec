<?= view('header'); ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Filing Trap >> Incomplete Matters </h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                            <!--<div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pencil" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <?//= view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <h4 class="basic_heading"> File Trap Details </h4>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <?php
                                        $attribute = array('class' => 'form-horizontal diary_fil_trap_form', 'name' => 'diary_fil_trap_form', 'id' => 'diary_fil_trap_form', 'autocomplete' => 'off');
                                        echo form_open('#', $attribute);
                                        ?>
                                        <div id="dv_content1">

                                            <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Incomplete Matters for <span style="color: #d73d5a"><?php echo session()->get('login')['name'];?></span>
                                                        <?php
                                                        $ucode = session()->get('login')['usercode'];
                                                        $icmic_empid = session()->get('login')['empid'];
                                                        $defects_cured = 0;
                                                        $cur_date = date('d-m-Y');
                                                        $new_date = date('d-m-Y', strtotime($cur_date . ' + 60 days'));
                                                        $cat = 0;
                                                        $ref = 0;
                                                        $condition = "and remarks=''";
                                                        if (!empty($fil_trap_type_row)) {
                                                            if ($fil_trap_type_row['usertype'] == 104)
                                                                $ref = 1;
                                                            if ($fil_trap_type_row['usertype'] == 108) {
                                                                $ref = 2;
                                                            }
                                                            if ($fil_trap_type_row['usertype'] == 105 || $fil_trap_type_row['usertype'] == 106)  // for category and tagging user
                                                            {
                                                                $cat = 1;
                                                                if ($fil_trap_type_row['usertype'] == 105) {
                                                                    $text = "Category";
                                                                } else {
                                                                    $text = "Tagging";
                                                                }
                                                        ?>

                                                                <select id="type_report" onChange="get_list(this.value)">
                                                                    <option value="">Select</option>
                                                                    <option value=<?php echo $_SESSION['login']['usercode']; ?>> <?php echo $_SESSION['login']['name']; ?></option>
                                                                    <option value="<?php echo $fil_trap_type_row['usertype']; ?>">Pending Matters of <?php echo $text; ?></option>
                                                                </select>


                                                            <?php
                                                            }
                                                            ?>

                                                            <span style="color: #737add">[<?php echo $fil_trap_type_row['type_name']; ?>]</span>
                                                            <div id='txtHint'></div>
                                                        <?php
                                                            if ($fil_trap_type_row['usertype'] == 102)
                                                                $condition = "and remarks='FIL -> DE'";
                                                            if ($fil_trap_type_row['usertype'] == 103)
                                                                $condition = "and remarks in('DE -> SCR','FDR -> SCR')";
                                                            if ($fil_trap_type_row['usertype'] == 107)
                                                                $condition = "and remarks in('CAT -> IB-Ex','TAG -> IB-Ex','SCN -> IB-Ex') ";
                                                        } else {
                                                            echo "<br>No record found!!!!";
                                                            exit();
                                                        }
                                                        ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <hr>
                                                    </th>
                                                </tr>
                                                    </thead>
                                            </table>
                                            <div id="result">
                                                <div id="dv_content1">
                                                    <div id="result">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php
                                                                if (!empty($fil_trap_list) && ($fil_trap_type_row['usertype'] != 106  &&  $fil_trap_type_row['usertype'] != 105)) { ?>
                                                                    <table id="example1" class="table table-bordered table-striped">
                                                                        <thead>
                                                                            <!--<tr>
                                                                                <th>SNo.</th>
                                                                                <th>Diary No.</th>
                                                                                <th>Parties</th>
                                                                                <th>Dispatch By</th>
                                                                                <th>Dispatch On</th>
                                                                                <th>Remarks</th>
                                                                                <th>Receive</th>
                                                                                <th>Dispatch</th>
                                                                                <th>eFiling View</th>
                                                                            </tr>-->
                                                                            <tr style="background-color: lightgrey">
                                                                                <th>SNo.</th>
                                                                                <th>Diary No.</th><?php if ($fil_trap_type_row['usertype'] != 107) { ?><th>Parties</th><?php } ?><th>Dispatch By</th>
                                                                                <th>Dispatch On</th>
                                                                                <th>Remarks</th><?php if ($fil_trap_type_row['usertype'] == 107) { ?><th>Tentative Listing Date[Listed For]</th> <?php } ?>
                                                                                <?php if ($fil_trap_type_row['usertype'] != 108) { ?> <th>Receive</th> <?php } ?><?php if ($fil_trap_type_row['usertype'] == 103) { ?> <th>Type</th> <?php } ?><?php if ($fil_trap_type_row['usertype'] != 107) { ?><th>Dispatch</th> <?php } ?> <th>eFiling View</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $sno = 1;
                                                                            foreach ($fil_trap_list as $row) {
                                                                                if ($fil_trap_type_row['usertype'] == 103 and $row['remarks'] == "FDR -> SCR") {
                                                                                    $check_if_def_rs = is_data_from_table('obj_save', ['diary_no' => $row['diary_no'], 'display' => 'Y', 'rm_dt is not' => null]);
                                                                                    /*$check_if_def_rm="SELECT distinct diary_no from obj_save where diary_no='$row[diary_no]' and date(rm_dt)!='0000-00-00' and display='Y'  ";
                                                                                    $check_if_def_rs = mysql_query($check_if_def_rm) or die(__LINE__.'->'.mysql_error());*/
                                                                                    if (!empty($check_if_def_rs)) {
                                                                                        $defects_cured = 1;
                                                                                    }
                                                                                }
                                                                            ?>
                                                                                <tr style="<?php if ($row['remarks'] == 'FDR -> AOR' || $row['remarks'] == 'AOR -> FDR') { ?> background-color: #cccccc <?php } ?>">
                                                                                    <th><?php echo $sno; ?></th>
                                                                                    <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?>
                                                                                        <?php if ($row['ref_special_category_filing_id'] != null and $row['ref_special_category_filing_id'] != '' and $row['ref_special_category_filing_id'] != '0') { ?>
                                                                                            <span id="blink_text">urgent</span>
                                                                                        <?php
                                                                                            echo "<br><font color='purple' >" . $row['category_name'] . "</font> ";
                                                                                        } ?>
                                                                                    </td>
                                                                                    <?php if ($fil_trap_type_row['usertype'] != 107) { ?> <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name'] ?></td><?php } ?>
                                                                                    <td><?php echo $row['d_by_name']; ?></td>
                                                                                    <td><?= (!empty($row['disp_dt'] && $row['disp_dt'] != null)) ? date('d-m-Y h:i:s A', strtotime($row['disp_dt'])) : ''; ?></td>

                                                                                    <td><?php echo $row['remarks']; ?></td>

                                                                                    <?php if ($fil_trap_type_row['usertype'] == 107) { ?>
                                                                                        <td>
                                                                                            <?php
                                                                                            if (strtotime($row['next_dt']) >= strtotime($cur_date) && strtotime($row['next_dt']) <= strtotime($new_date)) {
                                                                                                if ($row['main_supp_flag'] == 1 or $row['main_supp_flag'] == 2)
                                                                                                    echo "<font color=red>" . $row['next_dt'] . "</font>" . '    [' . $row['board_type'] . ']';
                                                                                                else
                                                                                                    echo  $row['next_dt'] . '    [' . $row['board_type'] . ']';
                                                                                            }

                                                                                            ?>
                                                                                        </td>
                                                                                    <?php } ?> <?php if ($fil_trap_type_row['usertype'] != 108) { ?>
                                                                                        <td>
                                                                                            <div id='d'><?php
                                                                                                        if ($row['rece_dt'] == '') {
                                                                                                            if (($ref == 0 && $cat == 0) || $ref == 2 || $ref == 3 || $cat == 1) {
                                                                                                        ?>
                                                                                                        <input type="button" id="rece<?php echo $row['uid']; ?>" value="Receive" class="btn btn-primary quick-btn" />
                                                                                                <?php
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo "Received On " . (!empty(trim($row['rece_dt']))) ? date('d-m-Y h:i:s A', strtotime($row['rece_dt'])) : '';
                                                                                                        }
                                                                                                ?>
                                                                                        </td>
                                                                                        <?php if ($fil_trap_type_row['usertype'] == 103) { ?>
                                                                                            <td>
                                                                                                <?php if ($row['remarks'] == "DE -> SCR") echo "<font color='green'>Fresh Matter</font>";
                                                                                                        else if ($row['remarks'] == "FDR -> SCR" and $defects_cured == 0) echo "<font color='blue'>Refiling</font>";
                                                                                                        else if ($row['remarks'] == "FDR -> SCR" and $defects_cured == 1) echo "<font color='blue'>Refiling</font><font color='orange'><br>(Defects Cured)</font>";
                                                                                                ?>
                                                                                            </td>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                    <?php
                                                                                    if ($fil_trap_type_row['usertype'] != 107) {
                                                                                        $nature = !empty($row['nature']) ? 'comp' . $row['nature'] : 'comp9999';
                                                                                    ?>
                                                                                        <td><!--<input type="button" id="comp<?php /*echo $row['uid'];*/ ?>comp<?php /*echo $row['nature'];*/ ?>" value="<?php /*if($fil_trap_type_row['usertype']==108 && ($row['remarks']=='AOR -> FDR' || $row['remarks']=='FDR -> AOR') ){ echo "Allot To Scruitny User"; } else if($fil_trap_type_row['usertype']==108 && $row['remarks']=='SCR -> FDR') { echo "Return to AOR" ;} else echo "Dispatch"; */ ?>" disabled/>-->
                                                                                            <input class="btn btn-primary quick-btn" type="button" id="comp<?php echo $row['uid']; ?><?php echo $nature; ?>" value="<?php if ($fil_trap_type_row['usertype'] == 108 && ($row['remarks'] == 'AOR -> FDR' || $row['remarks'] == 'FDR -> AOR')) { echo "Allot To Scruitny User";} else if ($fil_trap_type_row['usertype'] == 108 && $row['remarks'] == 'SCR -> FDR') { echo "Return to AOR";} else echo "Dispatch"; ?>" <?php //if($row[rece_dt]=='0000-00-00 00:00:00') echo "disabled";?> />
                                                                                            <?php
                                                                                            if ($cat == 1) {
                                                                                            ?>
                                                                                                <input type="button" id="tag<?php echo $row['uid']; ?>" value="Send to Tagging" class="btn btn-primary quick-btn" />
                                                                                            <?php
                                                                                            }

                                                                                            ?>
                                                                                        </td>
                                                                                    <?php } ?>
                                                                                    <td>
                                                                                        <?php if ($row['efiling_no'] != '') { ?>
                                                                                            <div class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="efiling_number('<?= $row['efiling_no'] ?>')">View</div>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                                $sno++;
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php  } else {
                                                                    if (($fil_trap_type_row['usertype'] == 105) || ($fil_trap_type_row['usertype'] == 106)) {
                                                                    } else {
                                                                    ?>

                                                                        <div class="nofound">SORRY!!!, NO RECORD FOUND</div>
                                                                <?php   }
                                                                } ?>

                                                            </div>
                                                        </div>


                                                    </div>


                                                </div>
                                            </div>
                                            <div id="newresult"> </div>
                                            <div id="result1"> </div>
                                        </div>
                                    </div>
                                    <?= form_close(); ?>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    function efiling_number(efiling_number) {
        var url = "http://10.25.78.48:81/efiling_search/DefaultController/?efiling_number=" + efiling_number;
        window.open(url, '_blank');
    }
</script>
<script type="text/javascript">
    // function myFunction() {
    //     document.getElementById("demo").innerHTML = "Hello World";
    // }

    function get_list(value1) {
        var str = value1;

        $.ajax({
            url: "<?php echo base_url('Filing/FileTrap/getMatters'); ?>",
            type: "GET",
            data: {
                q: str
            },
            success: function(response) {
                $("#txtHint").html(response);
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + status + " - " + error);
            }
        });
    }

    // function get_list(value1) {

    //     var str = value1;
    //     var xmlhttp = new XMLHttpRequest();
    //     xmlhttp.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //             document.getElementById("txtHint").innerHTML = this.responseText;
    //         }
    //     };
    //     xmlhttp.open("GET", "get_matters.php?q=" + str, true);
    //     xmlhttp.send();

    // }

    function recieve_file(iss) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var idd = iss.split('rece');
        $(this).attr('disabled', true);
        var type = 'R';
        $.ajax({
                type: 'POST',
                url: "<?= base_url('Filing/FileTrap/receive') ?>",
                beforeSend: function(xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
                data: {
                    id: idd[1],
                    value: type,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#result1").html('');
                updateCSRFToken();
                //$("#result").html(msg);
                // alert(msg);
                get_list(document.getElementById('type_report').value);
                //document.getElementBYId('d').innerHTML=msg;
                // return;
            })
            .fail(function() {
                $("#result1").html('');
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }
</script>
<script>
    $(document).ready(function() {

        $("[id^='rece']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var idd = $(this).attr('id').split('rece');
            //alert('received');
            //alert(idd);return;
            // $(this).attr('disabled',true);
            var type = 'R';
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Filing/FileTrap/receive') ?>",
                    beforeSend: function(xhr) {
                        $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    $("#result1").html('');
                    updateCSRFToken();
                    //$("#result").html(msg);
                    alert(msg);

                    window.location.reload();
                    return;
                })
                .fail(function() {
                    $("#result1").html('');
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });

        $("[id^='comp']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var c = confirm("Are You Sure You Want to Dispatch");
            if (c == true) {
                var idd = $(this).attr('id').split('comp');
                $(this).attr('disabled', true);
                var type = 'C';
                var nature = idd[2];
                //alert('id='+idd[1] + ' type='+type +' nature='+nature);// return false;
                $.ajax({
                        type: 'POST',
                        url: "<?= base_url('Filing/FileTrap/receive') ?>",
                        beforeSend: function(xhr) {
                            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                        },
                        data: {
                            id: idd[1],
                            value: type,
                            nature: nature,
                            CSRF_TOKEN: CSRF_TOKEN_VALUE
                        }
                    })
                    .done(function(msg) {
                        $("#result1").html('');
                        updateCSRFToken();
                        //$("#result").html(msg);
                        alert(msg);
                        window.location.reload();
                        return;
                    })
                    .fail(function() {
                        $("#result1").html('');
                        updateCSRFToken();
                        alert("ERROR, Please Contact Server Room");
                    });
            }
        });

        $("[id^='tag']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var tag = 'Y';
            var idd = $(this).attr('id').split('tag');
            $(this).attr('disabled', true);
            var type = 'C';
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Filing/FileTrap/receive') ?>",
                    beforeSend: function(xhr) {
                        $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        tag: tag,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    //$("#result").html(msg);
                    alert(msg);
                    // window.location.reload();
                    //return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });
    });

    $(document).on("click", "#print1", function() {
        var prtContent = $("#dv_content1").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=10,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>