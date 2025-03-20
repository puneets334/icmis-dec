<div id="test">
    <?php
   
    $cur_date = date('d-m-Y');

    $new_date = date('d-m-Y', strtotime($cur_date . ' + 60 days'));

    $cat = 0;
    $ref = 0;
    $condition = "and remarks=''";

    $fil_trap_type_row['usertype'] = 108;
    $fil_trap_type_row['type_name'] = 'Filing Dispatch Receive';
    $ref = 2;
    //   echo "value".$_REQUEST['stype'];
    if (!empty($_REQUEST['stype'])) {
        if ($ref == 2) {
            $condition1 = '';
            if ($_REQUEST['dno'] != '') {
                $condition1 = "a.diary_no=" . $_REQUEST['dno'] . $_REQUEST['dyr'] . " and ";
            }
            $select_rs = $model->get_data($condition1);
            // pr($select_rs);
        }


        if (!empty($select_rs) && ($fil_trap_type_row['usertype'] != 106 && $fil_trap_type_row['usertype'] != 105)) {

    ?>
      <?= csrf_field() ?>
            <div class="table-responsive">
                <table id="customers" class="table table-striped custom-table">
                    <thead>
                        <tr style="background-color: lightgrey">
                            <th>SNo.</th>
                            <th>Diary No.</th><?php if ($fil_trap_type_row['usertype'] != 107) { ?>
                                <th>Parties</th><?php } ?>
                            <th>Dispatch By</th>
                            <th>Dispatch On</th>
                            <th>Remarks</th><?php if ($fil_trap_type_row['usertype'] == 107) { ?>
                                <th>Tentative Listing Date[Listed For]</th> <?php } ?>
                            <!-- <th>Receive</th>--><?php if ($fil_trap_type_row['usertype'] != 107) { ?>
                                <th>Dispatch</th> <?php } ?>
                            <th>Filing Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($select_rs as $row) {
                        ?>
                            <tr style="<?php if ($row['remarks'] == 'FDR -> AOR' || $row['remarks'] == 'AOR -> FDR') { ?> background-color: #cccccc <?php } ?>">
                                <td><?php echo $sno; ?></th>
                                <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                                <?php if ($fil_trap_type_row['usertype'] != 107) { ?>
                                    <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name'] ?></td><?php } ?>
                                <td><?php echo $row['d_by_name']; ?></td>
                                <td><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>

                                <td><?php echo $row['remarks']; ?></td>
                                <?php /*if ($fil_trap_type_row['usertype'] == 107) { */ ?><!--
                                            <td>
                                                <?php
                                                /*                                                if (strtotime($row['next_dt']) >= strtotime($cur_date) && strtotime($row['next_dt']) <= strtotime($new_date)) {
                                                    if ($row['main_supp_flag'] == 1 or $row['main_supp_flag'] == 2)
                                                        echo "<font color=red>" . $row['0000-00-00 00:00:00next_dt'] . "</font>" . '    [' . $row['board_type'] . ']';
                                                    else
                                                        echo $row['next_dt'] . '    [' . $row['board_type'] . ']';
                                                }

                                                */ ?>
                                            </td>
                                            <td><?php /*echo $row['filing_type'];*/ ?></td>
                                        --><?php /*}*/ ?> <?php if ($fil_trap_type_row['usertype'] != 108) { ?>
                                    <td>
                                        <div id='d'><?php
                                                                if ($row['rece_dt'] == '') {
                                                                    if (($ref == 0 && $cat == 0) || $ref == 2 || $ref == 3 || $cat == 1) {
                                                    ?>
                                                    <input type="button" id="rece<?php echo $row['uid']; ?>" value="Receive" />
                                            <?php
                                                                    }
                                                                } else {
                                                                    echo "Received On " . date('d-m-Y h:i:s A', strtotime($row['rece_dt']));
                                                                }
                                            ?>
                                    </td>
                                <?php } ?>
                                <td>
                                    <?php echo $row['filing_type']; ?></td>
                                <?php
                                if ($fil_trap_type_row['usertype'] != 107) {
                                ?>
                                    <td><!--<input type="button" id="comp<?php /*echo $row['uid'];*/
                                                                            ?>comp<?php /*echo $row['nature'];*/
                                                                                    ?>" value="<?php /*if($fil_trap_type_row['usertype']==108 && ($row['remarks']=='AOR -> FDR' || $row['remarks']=='FDR -> AOR') ){ echo "Allot To Scruitny User"; } else if($fil_trap_type_row['usertype']==108 && $row['remarks']=='SCR -> FDR') { echo "Return to AOR" ;} else echo "Dispatch"; */
                                                                                                ?>" disabled/>-->
                                        <input type="button" onclick="AllotToScruitnyUser('<?php echo $row['uid']; ?>')" id="comp<?php echo $row['uid']; ?>"
                                            value=" <?php if ($fil_trap_type_row['usertype'] == 108 && ($row['remarks'] == 'AOR -> FDR' || $row['remarks'] == 'FDR -> AOR')) {
                                                        echo "Allot To Scruitny User";
                                                    } else if ($fil_trap_type_row['usertype'] == 108 && $row['remarks'] == 'SCR -> FDR') {
                                                        echo "Return to AOR";
                                                    } else echo "Dispatch"; ?>" <?php /*if($row[rece_dt]=='0000-00-00 00:00:00') echo "disabled";*/
                                                                                ?> />
                                        <?php
                                        if ($cat == 1) {
                                        ?>
                                            <input type="button" id="tag<?php echo $row['uid']; ?>" value="Send to Tagging" />
                                        <?php
                                        }

                                        ?>
                                        <div id="div<?php echo $row['uid']; ?>"></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php
                            $sno++;
                        }
                        ?>
                    <tbody>
                </table>
            </div>
            <?php

        } else {

            if (($fil_trap_type_row['usertype'] == 105) || ($fil_trap_type_row['usertype'] == 106)) {
            } else {
            ?>

                <div class="nofound">SORRY!!!, NO RECORD FOUND</div>
    <?php
            }
        }
    }
    ?>
</div>

<script>
    $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    function get_list(value1) {
        var str = value1;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "get_matters.php?q=" + str, true);
        xmlhttp.send();

    }

    function recieve_file(iss) {
        var idd = iss.split('rece');
        $(this).attr('disabled', true);
        var type = 'R';
        $.ajax({
                type: 'POST',
                url: "./receive.php",
                beforeSend: function(xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                data: {
                    id: idd[1],
                    value: type
                }
            })
            .done(function(msg) {
                //$("#result").html(msg);
                // alert(msg);
                console.log(msg);
                get_list(document.getElementById('type_report').value);
                //document.getElementBYId('d').innerHTML=msg;
                // return;
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    }
    function AllotToScruitnyUser(idd){
        // $("[id^='comp']").click(function() {
            // var idd = $(this).attr('id').split('comp');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(this).attr('disabled', true);
            var type = 'C';
            $.ajax({
                method: 'POST',
                url: "<?php echo base_url('Filing/IncompleteFDR/receiveFDR');?>",
                    //   url:"./return_to_aor.php",
                    beforeSend: function(xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        id: idd,
                        value: type,
                       // nature: idd[2],
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                  
                    // $("#result").html(msg);
                    updateCSRFToken();
                    //console.log(msg);
                    alert(msg);
                    window.location.reload();
                    return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        // });

    } 

    $(document).ready(function() {
        $("[id^='rece']").click(function() {
            var idd = $(this).attr('id').split('rece');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(this).attr('disabled', true);
            var type = 'R';
            $.ajax({
                    method: 'POST',
                    url: "<?php echo base_url('Filing/IncompleteFDR/receiveFDR');?>",
                    beforeSend: function(xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    //$("#result").html(msg);
                    console.log(msg);
                    alert(msg);
                    window.location.reload();
                    return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });

        /*$("[id^='comp']").click(function() {
            var idd = $(this).attr('id').split('comp');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(this).attr('disabled', true);
            var type = 'C';
            $.ajax({
                method: 'POST',
                url: "<?php echo base_url('Filing/IncompleteFDR/receiveFDR');?>",
                    //   url:"./return_to_aor.php",
                    beforeSend: function(xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        nature: idd[2],
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                  
                    // $("#result").html(msg);
                    updateCSRFToken();
                    //console.log(msg);
                    alert(msg);
                    window.location.reload();
                    return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        }); */

        $("[id^='tag']").click(function() {
            var tag = 'Y';
            var idd = $(this).attr('id').split('tag');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(this).attr('disabled', true);
            var type = 'C';
            $.ajax({
                method: 'POST',
                url: "<?php echo base_url('Filing/IncompleteFDR/receiveFDR');?>",
                beforeSend: function(xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        tag: tag,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    //$("#result").html(msg);
                    updateCSRFToken();
                    console.log(msg);
                    alert(msg);
                    window.location.reload();
                    return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });
    });
</script>