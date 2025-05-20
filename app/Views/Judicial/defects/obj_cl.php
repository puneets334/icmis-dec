<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/css/menu_css.css">
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/dp/jquery-ui.css" type="text/css"/>
<style>
    section.content .card .card{box-shadow: none;}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> Defects</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal', 'name' => '', 'autocomplete' => 'off');
                                echo form_open(base_url('#'), $attribute);
                                ?>
		                            <div id="dv_content1">
                                       <br />
                                        <div style="text-align: center">
                                            <div class="row">
                                                <div class="col-md-3">&nbsp;</div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-4 text-right">
                                                             <span class="mt-2">       
                                                                <b>Search</b> &nbsp;&nbsp;  <b>Diary No. </b>
                                                            </span>   
                                                        </div> 
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" id="t_h_cno" name="t_h_cno" size="5" value="" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <?php
                                                                    $currently_selected = date("Y");
                                                                    $earliest_year = 1950;
                                                                    $latest_year = date("Y");
                                                                    print '<select id="t_h_cyt">';
                                                                    foreach (range($latest_year, $earliest_year) as $i) {
                                                                        print '<option value="' . $i . '"';
                                                                        if ($i == date("Y")) {
                                                                                print 'selected="selected"';
                                                                        }
                                                                        print ">" . $i . "</option>";
                                                                    }
                                                                    print "</select>";
                                                                    ?>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="get_def_rec();" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    
                                                </div>
                                            </div>

                                        </div>  

                                        <br />
                                        <div id="dv_dup">
                                            <?php $w_wo_dn = "";?>
                                        </div>
                                        <input type="hidden" name="hd_hd_spl" id="hd_hd_spl" />
                                        <input type="hidden" name="hd_hd_sh_fno" id="hd_hd_sh_fno" />
                                        <input type="hidden" name="hd_f_no" id="hd_f_no" />
                                        <div id="sData"></div>
                                        <div id="sData1"></div>
                                        <div id="dv_ia"></div>


                                    </div>									  
                                     <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
                                        &nbsp;
                                    </div>
                                    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;left:0;width:100%;
                                    height:100%;z-index: 105;">
                                        <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="../images/close_btn.png" style="width:30px;height:30px"/></b></div>
                                                    <div  style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
                                                </div>
                                        </div>
                                    </div>                
                                   
                                <?php form_close(); ?>
                            </div>
                        </div>
                    </div>

					<div class="row mb-3 mb-4">
						<div class="col-md-12">							
								 <div id="div_result"></div>
            					 <div id="div_show"></div>						
						</div>
					</div>								

                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url(); ?>/da_defect/js/menu_js.js"></script>
<script src="<?= base_url(); ?>/da_defect/jquery/jquery-1.9.1.js"></script>
<script src="<?= base_url(); ?>/da_defect/calendar/datetimepicker_css.js"></script>

<script src="<?= base_url('/judicial/obj_cl.js') ?>"></script>
<script src="<?= base_url(); ?>/da_defect/d_navigation/d_jq.js"></script>
<script src="<?= base_url(); ?>/da_defect/dp/jquery-ui.js"></script>
<script>
    $(document).on("focus", ".dtp", function() {

        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

            function checkDetails() {
    var diary_no = "<?php //echo $_SESSION["session_diary_no"] . $_SESSION["session_diary_yr"]; ?>"; 
    var button = document.getElementById('checkDetailsBtn');

    var confirmation = confirm("Are you sure you want to receive matter from advocate?");
    if (!confirmation) {
        return;
    }

    button.disabled = true; 

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_case_details.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send("diary_no=" + diary_no);

    xhr.onload = function() {
        if (xhr.status == 200) {
            alert("Matter received successfully!");
            document.getElementById("btnSubmit").click();
            setTimeout(() => {
                // alert($('.caseDetails1').css('display'))
                if($('.caseDetails1').css('display') == 'none'){
                    $('.caseDetails1').css('display', 'block')
                    $('.styled-button').css('display', 'none')

                }
            }, 1500);

             document.getElementById("dv_ia").style.display = "block";

        } else {
            alert("An error occurred while saving case details.");
            button.disabled = false; 
        }
    };
}

</script>