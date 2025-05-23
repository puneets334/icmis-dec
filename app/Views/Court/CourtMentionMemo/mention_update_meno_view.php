<style>
    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="url"],
    input[type="password"],
    input[type="search"],
    select,
    textarea {
        border: 1px solid #e1e1e1 !important;
        width: 100% !important;
        height: 38px !important;
        padding: 5px 10px !important;
        border-radius: 0 !important;
    }

    .form-control,
    .btn {
        font-size: 14px !important;
    }

    * {
        box-sizing: border-box;
    }

    .my_B {
        font-size: 16px;
        color: Black;
        font-weight: bold;
    }

    .dl-horizontal dd {
        margin: 0px 0px 0px 180px;
       /* line-height: 0.2em; */
    }

    dl {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .dl-horizontal dt {
        float: left;
        width: 160px;
        overflow: hidden;
        clear: left;
        text-align: right;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: normal;
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
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <span class="alert-danger align-center"><?= \Config\Services::validation()->listErrors() ?>
                                    <?php if (session()->getFlashdata('error-msg')) :
                                        echo session()->getFlashdata('error-msg');
                                    endif; ?>
                                </span>
                                <div class="row">
                                    <?php if (session()->getFlashdata('msg')) : ?>
                                        <div class="alert alert-success alert-dismissible align-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('msg') ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (session()->getFlashdata('error')) : ?>
                                        <div class="alert alert-danger alert-dismissible align-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('error') ?></strong>
                                        </div>
                                    <?php endif; ?>
									 <?php if (!empty($msg_combined)) : ?>
                                        <div class="alert alert-danger alert-dismissible align-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= $msg_combined; ?></strong>
                                        </div>
                                    <?php endif; ?>
								</div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($caseInfo)){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                               <form class="form-horizontal" method="POST" name="headerForm" action="" id="Uform">
							   <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <h3> <i class="fa fa-list-alt" aria-hidden="true"></i> &nbsp; Case Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <dl class="dl-horizontal">
                                            <?php foreach ($caseInfo as $row) { ?>
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
                                               <?php } ?>                                            
										</dl>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <dl class="dl-horizontal">
                                            <dt class="my_B"> Dealing Assistant</dt>
                                            <dd ><span class="my_G"><?=$row['alloted_to_da']?></span >[<SPAN class="my_Bl">[<?=$row['user_section']?></SPAN>]</dd>                                            
                                            <dt class="my_B">Category</dt>
                                            <dd class="my_B" ><?=$row['sub_name1']?></dd>
                                            <dd class="my_B"><?=$row['sub_name4']?>(<?=$row['category_sc_old']?>)</dd>
                                            <?php
                                                 $result = $Mentioning_Model->get_display_status_with_date_differnces($row['tentative_cl_dt']);
                                                 if($result=='T') {
                                                    ?>
                                                    <dt >Tentative CL Date</dt>
                                                    <dd class="my_bl" style="font-size: 18px;color:#0000ff;font-weight: bold;"><?=date('d-m-Y', strtotime($row['tentative_cl_dt']))?> <?/*//=$row['next_dt']*/?></dd>
                                                <?php } ?>
                                                <input type="hidden" name="ten_cl_date" id="ten_cl_date" value="<?php echo (!empty($row['tentative_cl_dt'])) ? date('d-m-Y', strtotime($row['tentative_cl_dt'])) : ''?>" >

                                        </dl>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
					<div id="forMentioningList">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="from_date" class="col-sm-4 col-form-label">Date of Mention Memo Received <span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="mmReceivedDate" value="<?php echo $mmData['date_of_received'] ?>" id="mmReceivedDate" placeholder="Date of Mention Memo Received">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="to_date" class="col-sm-4 col-form-label">Date on which Mention Memo Presented before Hon'ble Court.<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control dtp" name="mmPresentedDate" id="mmPresentedDate" placeholder="dd-mm-yyyy" required autocomplete="off" value="<?php echo $mmData['date_on_decided'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="from_date" class="col-sm-4 col-form-label">Date on Which Matter was <br>Directed to be Listed</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control dtp" name="mmDecidedDate" id="mmDecidedDate" placeholder="dd-mm-yyyy" required="required" autocomplete="off" value="<?php echo $mmData['date_for_decided'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                             <div class="form-group">
                                                <label for="remark" class="col-sm-3 control-label">Reason to Update/Delete</label>
                                                <div class="col-sm-6">
                                                   <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Remarks......."><?php echo $mmData['spl_remark'] ?></textarea>
                                                </div>
                                             </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php if ($formData['forListType'] == 1) { ?>
					   <div id="forOralMentioning">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="pJudge" class="col-sm-4 col-form-label">Presiding Judge<span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" id="pJudge" name="pJudge" placeholder="pJudge" required>
                                                        <option value="">Select Presiding Judge</option>
                                                        <?php
														 if (!empty($mmData['jname'])) {
                                                                echo '<option selected value="' . $mmData['jcode'] . '" >' . $mmData['jcode'] . ' - ' . $mmData['jname'] . '</option>';
                                                          }
                                                        foreach ($judge as $j1) {
                                                            echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="causelistType" class="col-sm-4 col-form-label">Causelist Type<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="causelistType" tabindex="1" id="causelistType" onchange="getBenches();">
                                                   <option value="">Select Causelist</option>
                                                   <option <?php if ($mmData['mainhead'] == "F") {
                                                      echo "selected=selected";
                                                      } ?> value="1">Regular List</option>
                                                   <option <?php if ($mmData['mainhead'] == "M" && $mmData['board_type'] == 'J') {
                                                      echo "selected=selected";
                                                      } ?> value="3">Misc. List</option>
                                                   <option <?php if ($mmData['mainhead'] == "M" && $mmData['board_type'] == 'C') {
                                                      echo "selected=selected";
                                                      } ?> value="5">Chamber List</option>
                                                   <option <?php if ($mmData['mainhead'] == "M" && $mmData['board_type'] == 'R') {
                                                      echo "selected=selected";
                                                      } ?> value="7">Registrar List</option>
                                                   <option <?php if ($mmData['mainhead'] == "F" && $mmData['board_type'] == 'J') {
                                                      echo "selected=selected";
                                                      } ?> value="9">Review/Curative List</option>
                                                </select>
												<input type="hidden" name="roster_id" id="roster_id" value="<?= $mmData['roster_id'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
											<div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="pJudge" class="col-sm-4 col-form-label">Bench<span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
													<select class="form-control" name="bench" tabindex="1" id="bench">
													   <option value="<?php if ($roster_id) echo $roster_id; ?>"><?php if ($bench_desc) echo $bench_desc; ?></option>
													</select>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="causelistType" class="col-sm-4 col-form-label">Item No<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" id="itemNo" name="itemNo" placeholder="Item Number" type="number" maxlength="20" value="<?= $mmData['m_brd_slno'] ?>" required="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                            </div>
                        </div>
                    </div>
					<?php }?>
					             <input type="hidden" name="id" value="<?= $mmData['id'] ?>">
								 <input type="hidden" name="sessionurl" value="<?= $session_id_url ?>">
								 <input type="hidden" name="diary_no" value="<?= $mmData['diary_no'] ?>">
								 
								  <input type='hidden' class="" name="session_id_url" value="<?= $formData['session_id_url']; ?>">
					                 <div class="row">
										<div class="col-md-12">
											<div class="card-body">
												<button id="updateButton" onclick="return confirm('Do you really want to Update The Matter.....?');" type="submit" value="submit" value="Save" style="width:15%;float:right" class="btn btn-block btn-primary">Update</button>
												<button id="deleteButton" onclick="return confirm('Do you really want to Delete The Matter.....?');" type="submit" value="submit" value="Save" style="width:15%;float:rightmargin-top: auto;" class="btn btn-block btn-primary">Delete</button>
											</div>
										</div>
									</div>
					     </form>
					<?php } else {?>
							<div style="text-align:center; "><b>No case information available.</b></div> 
					<?php }?>		
				</div>
            </div>
        </div>
    </div>
</section>	 
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
		 
       
         function make_party_div_popup() {
         document.getElementById("newparty1").style.display = 'block';
         
         }
         
       
         
	function getBenches() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var causelistDate = $('#mmPresentedDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
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
            $.get("<?php echo base_url('Court/CourtMasterController/getBench'); ?>", {
                causelistDate: causelistDate,
                pJudge: pJudge,
                causelistType: causelistType,
                CSRF_TOKEN: csrf
            }, function(result) {
                $("#divCasesForGeneration").html("");
                $("#bench").empty();
                $("#bench").append(result);
            });
        }
    }
		 
	
         $('#updateButton').click(function() {
			
         //var userId = $("#user_id").val();
         $("#Uform").attr("action", "<?php echo base_url('Court/CourtMentionMemoController/updateMm'); ?>");
		
         });
		 
         $('#deleteButton').click(function() {
            $("#Uform").attr("action", "<?php echo base_url('Court/CourtMentionMemoController/deleteMm'); ?>");
         });
		 
		 $('#Uform').submit(function(e) {
				console.log("Form action:", $(this).attr("action")); // Debug line
			});
      </script>
					
					
					