<?=view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Mention Memo</h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div class="card-body ">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url("court/CourtMentionMemoController/updateMentionMemo"), $attribute);
                                    ?>
                                    <?php echo component_html_mention_meno();?>
									 

                                     <center> 
									 <input type='hidden' class="" name="session_id_url" value="<?= $session_id_url; ?>">
									 <button type="submit" id="view" name="view" class="btn btn-primary">View</button>
									 <button type="submit" class="btn btn-primary" id="submit">Submit</button></center>
                                    <?php form_close();?>

                                    <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!DOCTYPE html>


<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mention Memo Update</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/jsAlert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/jsAlert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.css">
    <script src="<?= base_url() ?>assets/css/select2.css"></script>
    <style>
        .my_B {
            font-size: 16px;
            color: Black;
            font-weight: bold;
        }

        .my_G {
            font-size: 16px;
            color: #008000;
            font-weight: bold;
        }

        .my_Bl {
            font-size: 16px;
            color: #0000ff;
            font-weight: bold;
        }

        table {
            font-size: 15px;
            color: #001f3f;
        }
    </style>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <?php
        //include('../Copying/template/top_navigation.html');
        // $this->load->view('Copying/template/top_navigation.html');
        ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container" style="margin:0px !important;width:100% !important;">
                <!-- Main content -->
                <section class="content ">
                    



                    <!-------------Result Section ------------>
                    <?php if (is_array($caseInfo)) {

                        //echo $mmData->pdfname.$mmData->upload_date;
                         {

                    ?>

                            <div class="">
                                <div class="box-body">
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" method="POST" name="headerForm" action="" id="Uform">
                                            <!-- <input type="text" class="form-control" name="dno" id="dno" value="<?= $row['diary_no'] ?>"  >
                              <input type="text" class="form-control" name="dyear" id="dyear" value="<?= $row['diaryYear'] ?>"  > -->
                                            <!---------------- Next Section ---------------->
                                            <div class="box box-primary">
                                                <div class="box-body">
                                                    <!-- <div class="form-group">
                                            <span class="col-sm-3"></span>
                                            <div class="col-sm-9">
                                                <div class="input-group" >
                                                    <label class="radio-inline control-label"><input type="radio" name="forListType" value="1" checked><b>Oral Mentioning</b></label>
                                                    &nbsp;&nbsp;
                                                    <label class="radio-inline control-label"><input type="radio" name="forListType" value="2"><b>For Mentioning List</b></label>
                                                </div>
                                            </div>
                                        </div> -->
                                                    <hr>
                                                    <div class="box-body">
                                                        <div class="col-md-6">
                                                            <div class="box box-solid">
                                                                <div class="box-header with-border">
                                                                    <i class="glyphicon glyphicon-list-alt"></i>
                                                                    <h3 class="box-title">Case Details</h3>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body">
                                                                    <?php
                                                                    foreach ($caseInfo as $row) { ?>
                                                                        <dl class="dl-horizontal">
                                                                            <dt>Case No.</dt>
                                                                            <dd class="my_B"> <?= $row['reg_no_display'] ?>&nbsp;(D No.<?= $row['diary_no'] ?>-<?= $row['diary_year'] ?>)</dd>
                                                                            <dt>Petitioner</dt>
                                                                            <dd class="my_B"><?= $row['pet_name'] ?></dd>
                                                                            <dt>Respondant</dt>
                                                                            <dd class="my_B"><?= $row['res_name'] ?></dd>
                                                                            <dt>Status</dt>
                                                                            <dd class="my_B">
                                                                                <?php if ($row['mainhead'] == 'M')
                                                                                    echo "Misc";
                                                                                elseif ($row['mainhead'] == 'F')
                                                                                    echo "Regular";
                                                                                if ($row['c_status'] == 'P')
                                                                                    echo '(Pending)';
                                                                                elseif ($row['c_status'] == 'D')
                                                                                    echo '(Disposed)';
                                                                                ?>
                                                                            </dd>
                                                                            <dt>Petitioner Advocate</dt>
                                                                            <dd class="my_B"><?= $row['pet_adv_name'] ?>-<?= $row['pet_aor_code'] ?></dd>
                                                                            <dt>Respondant Advocate</dt>
                                                                            <dd class="my_B"><?= $row['res_adv_name'] ?>-<?= $row['res_aor_code'] ?></dd>


                                                                        </dl>
                                                                    <?php } ?>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-md-6">
                                                            <div class="box box-solid">
                                                                <div class="box-header with-border">
                                                                    <i class="glyphicon glyphicon-tags"></i>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body">
                                                                    <dl class="dl-horizontal">
                                                                        <dt> Dealing Assistant</dt>
                                                                        <dd><span class="my_G"><?= $row['alloted_to_da'] ?></span>[<SPAN class="my_Bl"><?= $row['user_section'] ?>]</SPAN></dd>
                                                                        <dt>Category</dt>
                                                                        <dd class="my_B"><?= $row['sub_name1'] ?></dd>
                                                                        <dd class="my_B"><?= $row['sub_name4'] ?>(<?= $row['category_sc_old'] ?>)</dd>
                                                                        <?php
                                                                        $CI     = &get_instance();
                                                                        $this->load->model('Mentioning_Model');
                                                                        $result = $CI->Mentioning_Model->get_display_status_with_date_differnces($row['tentative_cl_dt']);
                                                                        //echo $result.'#'.$row['tentative_cl_dt'];
                                                                        if ($result == 'T') {
                                                                        ?>
                                                                            <dt>Tentative CL Date</dt>
                                                                            <dd class="my_bl" style="font-size: 18px;color:#0000ff;font-weight: bold;"><?= date('d-m-Y', strtotime($row['tentative_cl_dt'])) ?> <?/*//=$row['next_dt']*/ ?></dd>
                                                                        <?php } ?>
                                                                        <input type="hidden" name="ten_cl_date" id="ten_cl_date" value="<?= date('d-m-Y', strtotime($row['tentative_cl_dt'])) ?>">

                                                                    </dl>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->
                                                        </div>
                                                        <hr>


                                                      
                                                        <?php 


if ($mmData->pdfname == NULL || $mmData->upload_date == NULL)

//  print_r($user_id);exit;
                                                    if( $session_id_url === $user_id ){
                                                    ?>

                                                    </div>
                                                    <div id = "hide_div">    
                                                        <div class="form-group">
                                                            <label for="lodgementDate" class="col-sm-3 control-label">Date of Mention Memo Received</label>
                                                            <div class="col-sm-3">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input type="date" class="form-control" name="mmReceivedDate" value="<?php echo $mmData->date_of_received ?>" id="mmReceivedDate" placeholder="Date of Mention Memo Received">
                                                                </div>
                                                            </div>

                                                            <label for="lodgementDate" class="col-sm-3 control-label">Date on which Mention Memo <br>Presented before Hon'ble Court</label>
                                                            <div class="col-sm-3">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input type="date" class="form-control" name="mmPresentedDate" id="mmPresentedDate" placeholder="dd-mm-yyyy" value="<?php echo $mmData->date_on_decided ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="forMentioningList">
                                                            <div class="form-group">
                                                                <label for="lodgementDate" class="col-sm-3 control-label">Date on Which Matter was <br>Directed to be Listed</label>
                                                                <div class="col-sm-3">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                        <input type="date" class="form-control" name="mmDecidedDate" id="mmDecidedDate" placeholder="dd-mm-yyyy" value="<?php echo $mmData->date_for_decided ?>">
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="col-sm-2">
                                                    <input type="hidden" name="order" id="o2" value="R" > Rejected-
                                                    <input type="radio" name="order" id="o3" value="N" >&nbsp;&nbsp;As Per Schedule
                                                </div> -->
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="remark" class="col-sm-3 control-label">Reason to Update/Delete</label>
                                                                    <div class="col-sm-6">
                                                                        <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Remarks......."><?php echo $mmData->spl_remark ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if ($formData['forListType'] == 1) { ?>

                                                            <div id="forOralMentioning">

                                                                <hr>
                                                                <div class="form-group">
                                                                    <div class="col-sm-6 ">
                                                                        <label for="pJudge" class="control-label">Presiding Judge</label>
                                                                        <div>
                                                                            <div class="input-group">
                                                                                <select class="form-control" id="pJudge" name="pJudge" placeholder="pJudge" required>
                                                                                    <option value="">Select Presiding Judge</option>
                                                                                    <?php
                                                                                    if (!empty($mmData->jname)) {
                                                                                        echo '<option selected value="' . $mmData->jcode . '" >' . $mmData->jcode . ' - ' . $mmData->jname . '</option>';
                                                                                    }
                                                                                    foreach ($judge as $j1) {
                                                                                        echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                                                    }

                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php  //print_r($mmData->roster_id) 
                                                                    ?>

                                                                    <div class="form-group col-sm-6 ">
                                                                        <label for="causelistType">Causelist Type</label>
                                                                        <select class="form-control" name="causelistType" tabindex="1" id="causelistType" onchange="getBenches();">

                                                                            <option value="">Select Causelist</option>
                                                                            <option <?php if ($mmData->mainhead == "F") {
                                                                                        echo "selected=selected";
                                                                                    } ?> value="1">Regular List</option>
                                                                            <option <?php if ($mmData->mainhead == "M" && $mmData->board_type == 'J') {
                                                                                        echo "selected=selected";
                                                                                    } ?> value="3">Misc. List</option>
                                                                            <option <?php if ($mmData->mainhead == "M" && $mmData->board_type == 'C') {
                                                                                        echo "selected=selected";
                                                                                    } ?> value="5">Chamber List</option>
                                                                            <option <?php if ($mmData->mainhead == "M" && $mmData->board_type == 'R') {
                                                                                        echo "selected=selected";
                                                                                    } ?> value="7">Registrar List</option>
                                                                            <option <?php if ($mmData->mainhead == "F" && $mmData->board_type == 'J') {
                                                                                        echo "selected=selected";
                                                                                    } ?> value="9">Review/Curative List</option>




                                                                        </select>
                                                                        <input type="hidden" name="roster_id" id="roster_id" value="<?= $mmData->roster_id ?>">
                                                                    </div>

                                                                    <div class="form-group col-sm-6" style="margin-left: 10px;">
                                                                        <label for="bench">Bench</label>
                                                                        <select class="form-control" name="bench" tabindex="1" id="bench">
                                                                            <option value="<?php if ($roster_id) echo $roster_id; ?>"><?php if ($bench_desc) echo $bench_desc; ?></option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-sm-2"></div>

                                                                    <div class="form-group col-sm-4">
                                                                        <label for="itemNo" class=" control-label">Item No</label>
                                                                        <div>
                                                                            <input class="form-control" id="itemNo" name="itemNo" placeholder="Item Number" type="number" value="<?= $mmData->m_brd_slno ?>" maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>

                                                                </div>

                                                            </div>
<br>
                                                            <?php 
                                                    } else {
                                                        '';                                                    }
                                                    ?>
                                                                <!--
                                            <div class="col-sm-9">
                                                <input class='pdbutton' data-toggle="modal" data-target="#myModal" type='button' id="pendingRemarks" name='pendingRemarks'  value='Pending Remarks'>
                                            </div>
                                            -->
                                                            </div>

                                                            <!-- pending remarks div opening-->
                                                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                            <h4 class="modal-title">Modal title</h4>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="te"></div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <button type="button" class="btn btn-primary">Save changes</button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>

                                                            <!--pending remark div ending -->
                                                    </div>
                                                    <?php //print_r($formData);
                                                    ?>



                                                    <input type="hidden" name="id" value="<?= $mmData->id ?>">
                                                    <input type="hidden" name="sessionurl" value="<?= $session_id_url ?>">
                                                    <input type="hidden" name="diary_no" value="<?= $mmData->diary_no ?>">
                                                    <input type="hidden" name="user_id" value="<?= $mmData->user_id ?>">

                                                    <input type='hidden' class="" name="session_id_url" value="<?= $formData['session_id_url']; ?>">

                                                    <?php 
                                                    if ($mmData->pdfname == NULL || $mmData->upload_date == NULL)
                                                    if( $session_id_url === $user_id ){
                                                    ?>

                                                    <div class="box-footer">                                                                                               
                                                    <button id="updateButton" onclick="return confirm('Do you really want to Update The Matter.....?');" type="submit" value="submit" value="Save" style="width:15%;float:right" class="btn btn-block btn-primary">Update</button>
                                                    <button id="deleteButton" onclick="return confirm('Do you really want to Delete The Matter.....?');" type="submit" value="submit" value="Save" style="width:15%;float:rightmargin-top: auto;" class="btn btn-block btn-primary">Delete</button>
                                                    </div>


                                                    <?php 
                                                    } else {
                                                        '';
                                                    }
                                                    ?>

                                                    
                                                </div>
                                            </div>

                                    </div>
                                    </form>
                                </div>

                            </div>
                    <?php }
                    } else {
                    }
                    ?>


            </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->
    </div>

    </div>
    <!-- ./wrapper -->
    <!-- SlimScroll -->
    <script src="<?= base_url() ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/select2.full.min.js"></script>


    <script src="<?= base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/fastclick/fastclick.js"></script>
    <script src="<?= base_url() ?>assets/js/app.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pil.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>

    <!--<script src="<?= base_url() ?>assets/js/reader_cl.js"></script>-->

    <script>
            function confirmBeforeAdd() {
                var choice = confirm('Do you really want to List The Matter.....?');
                if (choice === true) {
                    return true;
                }
                return false;
            }

            function myfunc() {
                // var start= $("#mmPresentedDate").datepicker("getDate");
                // var end= $("#mmDecidedDate").datepicker("getDate");
                // days = (end- start) / (1000 * 60 * 60 * 24);
                // alert(Math.round(days));
            }
            $('#pendingRemarks').click(function() {
                var mf = "";
                var jcodes = document.getElementById("jcodes" + cn).innerHTML;
                make_paps_div(jcodes, cln, cn, e, cnt);
                if (document.frm.mf[0].checked)
                    mf = document.frm.mf[0].value;
                if (document.frm.mf[1].checked)
                    mf = document.frm.mf[1].value;
                if (document.frm.mf[2].checked)
                    mf = document.frm.mf[2].value;
                if (document.frm.mf[3].checked)
                    mf = document.frm.mf[3].value;
                if (mf == "1" || mf == "5" || mf == "7")
                    document.getElementById("hdate").value = document.getElementById("dtd").value;
                if (cnt == 1) {
                    var div1 = "chkp";
                    var div2 = "hdremp";
                    $('#tmp_casenop').val(cn);
                    $("#partybutton").attr('disabled', true);
                    $("#partybutton1").attr('disabled', true);
                    $('#psn').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
                    $('#pend_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
                    $('#pend_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
                    $('#hdremp22_div').html('');
                    $('#hdremp26_div').html('');
                    $('#hdremp95_div').html('');
                    $('#hdremp142_div').html('');
                    if (mf == "2") {
                        var rfinal = $('#rfinal' + cn).val();
                        if (rfinal == "" || rfinal == '151') {
                            $('#chkp150').attr('disabled', false);
                            $('#chkp151').attr('disabled', true);
                            $('#hdremp150').attr('disabled', false);
                            $('#hdremp151').attr('disabled', true);
                        } else {
                            $('#chkp150').attr('disabled', true);
                            $('#chkp151').attr('disabled', false);
                            $('#hdremp150').attr('disabled', true);
                            $('#hdremp151').attr('disabled', false);
                        }
                    }
                    if ($('#ian' + cn).val() != "") {
                        var t_var = $('#ian' + cn).val().split(",");
                        for (i = 0; i < (t_var.length - 1); i++) {
                            if (t_var[i] != "") {
                                addCheckbox('hdremp22_div', t_var[i]);
                                addCheckbox('hdremp26_div', t_var[i]);
                                addCheckbox('hdremp95_div', t_var[i]);
                                addCheckbox('hdremp142_div', t_var[i]);
                            }
                        }
                        $('#chkp22').attr('disabled', false);
                        $('#chkp22').attr('checked', false);
                        $('#chkp26').attr('disabled', false);
                        $('#chkp26').attr('checked', false);
                        $('#chkp95').attr('disabled', false);
                        $('#chkp95').attr('checked', false);
                        $('#chkp142').attr('disabled', false);
                        $('#chkp142').attr('checked', false);
                    } else {
                        $('#chkp22').attr('disabled', true);
                        $('#chkp22').attr('checked', false);
                        $('#chkp26').attr('disabled', true);
                        $('#chkp26').attr('checked', false);
                        $('#chkp95').attr('disabled', true);
                        $('#chkp95').attr('checked', false);
                        $('#chkp142').attr('disabled', true);
                        $('#chkp142').attr('checked', false);
                    }
                } else {
                    var div1 = "chkd";
                    var div2 = "hdremd";
                    $('#tmp_casenod').val(cn);
                    $('#tmp_casenosub').val(subh);
                    $('#psn1').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
                    $('#disp_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
                    $('#disp_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
                }
                var csval = document.getElementById("caseval" + cn).value;
                var csvalspl = csval.split("^^");
                var t_val;
                var chk_val;
                $("input[type='checkbox'][name^='" + div1 + "']").each(function() { //alert("af");
                    chk_val = $(this).val().split("|");
                    int_chk = 0;
                    for (i = 0; i < (csvalspl.length - 1); i++) {
                        t_val = csvalspl[i].split("|");
                        if (t_val[0] == chk_val[0]) {
                            document.getElementById(div1 + chk_val[0]).checked = true;
                            if (t_val[0] == 190) {
                                var t_var = t_val[1].replace('D:', '');
                                t_var = t_var.replace('W:', '');
                                t_var = t_var.replace('M:', '');
                                var new_var = t_var.split(',');
                                $("#" + div2 + chk_val[0] + "_1").val(new_var[0]);
                                $("#" + div2 + chk_val[0] + "_1").attr('readonly', false);
                                $("#" + div2 + chk_val[0] + "_1").css('background-color', '#FFF');
                                $("#" + div2 + chk_val[0] + "_1").css('border', '1px solid #ccc');

                                $("#" + div2 + chk_val[0] + "_2").val(new_var[1]);
                                $("#" + div2 + chk_val[0] + "_2").attr('readonly', false);
                                $("#" + div2 + chk_val[0] + "_2").css('background-color', '#FFF');
                                $("#" + div2 + chk_val[0] + "_2").css('border', '1px solid #ccc');

                                $("#" + div2 + chk_val[0] + "_3").val(new_var[2]);
                                $("#" + div2 + chk_val[0] + "_3").attr('readonly', false);
                                $("#" + div2 + chk_val[0] + "_3").css('background-color', '#FFF');
                                $("#" + div2 + chk_val[0] + "_3").css('border', '1px solid #ccc');
                                int_chk = 1;
                            } else {
                                $("#" + div2 + chk_val[0]).val(t_val[1]);
                                $("#" + div2 + chk_val[0]).attr('readonly', false);
                                $("#" + div2 + chk_val[0]).css('background-color', '#FFF');
                                $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
                            }
                            if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
                                $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function() {
                                    $(this).attr('disabled', false);
                                    if (parseInt(t_val[1].indexOf($(this).val())) >= 0)
                                        $(this).attr('checked', true);
                                });
                            }
                            if (chk_val[0] == 91) {
                                $("#partybutton").attr('disabled', false);
                            }
                            if (chk_val[0] == 149) {
                                $("#partybutton1").attr('disabled', false);
                                make_party_div1();
                            }
                            int_chk = 1;
                        }
                    }
                    if (int_chk == 0) {
                        document.getElementById(div1 + chk_val[0]).checked = false;
                        $("#" + div2 + chk_val[0]).val('');
                        $("#" + div2 + chk_val[0]).attr('readonly', true);
                        $("#" + div2 + chk_val[0]).css('background-color', '#F5F5F5');
                        $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
                        if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
                            $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function() {
                                $(this).attr('disabled', true);
                                $(this).attr('checked', false);
                            });
                        }
                    }
                });
                //call_f1(cnt);
            });

        });

        function make_party_div_popup() {
            document.getElementById("newparty1").style.display = 'block';

        }

        function make_party_div() {

            $('#newb').width($(window).width() - 150);
            $('#newb').height($(window).height() - 150);
            $('#newparty').height($('#newb').height() - 100);

            var filno = $('#tmp_casenop').val();
            var cldt = document.getElementById("dtd").value;
            var dt1 = cldt.split("-");
            var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
            var xhr2 = getXMLHTTP();
            var str = "get_parties.php?filno=" + filno + "&cldt=" + dt_new;
            // alert(str);
            xhr2.open("GET", str, true);
            xhr2.onreadystatechange = function() {
                if (xhr2.readyState == 4 && xhr2.status == 200) {
                    var data = xhr2.responseText;
                    // document.getElementById('paps').value=data;
                    //alert(data);
                    // var paps = document.getElementById('paps').value;
                    var div_output = "";
                    var p = data;
                    var p1 = "";
                    var p2 = "";
                    var snoo = 0;
                    var bgc = "";
                    if (p != "") {
                        div_output += '<table cellspacing=0 cellpadding=0 width="100%" border="1" style="border-collapse: collapse;padding:0;">';
                        div_output += '<tr><td colspan="4" align=center><b>SELECT PARTY(S) TO BE APPEARED BEFORE REGISTRY</b></td></tr>';
                        p1 = p.split("#");
                        //var cntr = parseInt(paps1.length / 2);
                        var cntr = 2;
                        for (var i = 0; i < (p1.length - 1); i++) {
                            snoo++;
                            p2 = p1[i].split("|");
                            bgc = "#F8F9FC";
                            if ((snoo % cntr) != 0) {
                                div_output += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                            }
                            if (p2[3] == "F") {
                                div_output += '<td style="padding:0;" width="5%" align=center><b>' + p2[1] + p2[2] + '</b></td><td style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '" checked="checked"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                            } else {
                                div_output += '<td style="padding:0;" width="5%" align=center><b>' + p2[1] + p2[2] + '</b></td><td style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                            }
                            if ((snoo % cntr) == 0)
                                div_output += '</tr>';
                        }
                        div_output += '</table><div id="buttonbottom" style="width: 100%; position:absolute;bottom:0; text-align:center;"><input name="sparty" type="button" value="SAVE" onclick="save_parties();"/>&nbsp;<input name="cparty" type="button" value="CLOSE" onclick="close_party();"/></div>';
                    }
                    document.getElementById("newparty").innerHTML = div_output;
                    document.getElementById("newparty").style.display = 'block';
                }
            } // inner function end
            xhr2.send(null);
        }

        function getBenches() {
            var causelistDate = $('#mmPresentedDate').val();
            var pJudge = $('#pJudge').val();
            var causelistType = $('#causelistType').val();
            var roster_id = $('#roster_id').val();
            if (causelistDate == "") {
                alert("Please fill Causelist Date..");
                $('#mmPresentedDate').focus();
                return false;
            }
            if (pJudge == "") {
                alert("Please Select Presiding Judge..");
                return false;
            }
            if (causelistType == "") {
                alert("Please Select Type of Causelist..");
                return false;
            }
            if (causelistDate != "" && pJudge != "" && causelistType != "") {
                //  $bench = $data['mmData']=$this->Mentioning_Model->get_mmData($receivedDate,$dy,$dn,$forListType);  // get data from table to view in table

                $.get("<?= base_url() ?>index.php/CourtMasterController/getBenchMM", {
                        causelistDate: causelistDate,
                        pJudge: pJudge,
                        causelistType: causelistType
                    },
                    function(result) {

                        //console.log(result);
                        $("#divCasesForGeneration").html("");
                        $("#bench").empty();
                        $("#bench").append(result);
                    });
            }
        }

        $('#updateButton').click(function() {
            //var userId = $("#user_id").val();
            $("#Uform").attr("action", "<?php echo base_url(); ?>index.php/Mentioning/updateMm");
        })
        $('#deleteButton').click(function() {
            $("#Uform").attr("action", "<?php echo base_url(); ?>index.php/Mentioning/deleteMm");
        })
    </script>

</body>

</html>
