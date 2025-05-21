<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/css/menu_css.css">
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/dp/jquery-ui.css" type="text/css"/>
<style>
        .mybr {
            border-collapse: collapse;
            border-style: dashed;
        }

        .mybr th {
            border-collapse: collapse;
            border-style: dashed;
        }

        .nofound {
            text-align: center;
            color: red;
            font-size: 17px;
        }

        .centerview {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
        }

        tr:nth-child(odd) {
            background-color: #F0F0F0;
        }
    </style>
<script src="<?= base_url(); ?>/da_defect/js/menu_js.js"></script>
<script src="<?= base_url(); ?>/da_defect/jquery/jquery-1.9.1.js"></script>
<script src="<?= base_url(); ?>/da_defect/dp/jquery-ui.js"></script>
<script src="<?= base_url(); ?>/filing/incomplete.js"></script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> Defects >> Incomplete E-filled  Refiling matters</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                echo form_open(base_url('#'), $attribute);
                                ?>
                                    <div id="dv_content1">
                                        <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%" class="text-center">
                                            <tr>
                                                <th><b>Incomplete E-filed refiling matters for <span style="color: #d73d5a"><?php echo @$emp_name_login; ?></span></b>
                                                    <?php
                                                    $cur_date = date('d-m-Y');
                                                    $new_date = date('d-m-Y', strtotime($cur_date . ' + 60 days'));
                                                    ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <hr>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>   


		                        <?php form_close(); ?>
                            </div>
                        </div>
                    </div>

					<div class="row mb-3 mb-4">
						<div class="col-md-12">							
								 <div id="div_result">
                                    <?php                                    
                                    if(!empty($select_rs) && count($select_rs) > 0) {
                                    ?>  
                                    <table class="centerview" border="1" cellspacing="4" cellpadding="5">
                                        <tr style="background-color: lightgrey">
                                            <th>SNo.</th>
                                            <th>Diary No.</th>
                                            <th>Parties</th>
                                            <th>Dispatch By</th>
                                            <th>Dispatch On</th>
                                            <th>Remarks</th>
                                            <th>Receive</th>
                                            <th>eFiling View</th>
                                        </tr>
                                        <?php
                                        $sno = 1;
                                        $hasRows = false;
                                        foreach ($select_rs as $row) {
                                            if ($row['efiling_no'] != '') {
                                                $hasRows = true;
                                        ?>
                                        <tr>
                                            <td><?php echo $sno; ?></td>
                                            <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                                            <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name'] ?></td>
                                            <td><?php echo $row['d_by_name']; ?></td>
                                            <td><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
                                            <td><?php echo $row['remarks']; ?></td>
                                            <td><?php echo "Received On " . date('d-m-Y h:i:s A', strtotime($row['rece_dt'])); ?></td>
                                            <td>
                                                <button class="btn ui-button-text-icon-primary" style="background-color: #555555; color: #fff; cursor: pointer; font-size: large;" onclick="efiling_number(event,'<?php echo $row['efiling_no']; ?>')">View</button>
                                            </td>
                                        </tr>
                                        
                                        <?php
                                                $sno++;
                                            } 

                                        }
                                        ?>


                                        <?php
                                            if (!$hasRows) {
                                                echo '<tr><td colspan="8" style="text-align: center;">No record found</td></tr>';
                                            }
                                         ?>
                                    
                                    </table>


                                   <?php     
                                    }   
                                     ?>
                                 </div>
            					 					
						</div>
					</div>								

                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    // function myFunction() {
    //     document.getElementById("demo").innerHTML = "Hello World";
    // }

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

    // function recieve_file(iss) {
    //     var idd = iss.split('rece');
    //     $(this).attr('disabled', true);
    //     var type = 'R';
    //     $.ajax({
    //             type: 'POST',
    //             url: "./receive.php",
    //             beforeSend: function(xhr) {
    //                 $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
    //             },
    //             data: {
    //                 id: idd[1],
    //                 value: type
    //             }
    //         })
    //         .done(function(msg) {

    //             get_list(document.getElementById('type_report').value);

    //         })
    //         .fail(function() {
    //             alert("ERROR, Please Contact Server Room");
    //         });
    // }
</script>

<script>
    function efiling_number(event,efiling_number) {
        event.preventDefault();
        var link = document.createElement("a")
        link.href = "http://10.192.105.105:91/efiling_search/DefaultController/?efiling_number=" + efiling_number
        link.target = "_blank"
        link.click()
    }
</script>
