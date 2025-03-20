<?=view('header'); ?>
<style>
    /* input[type="text"], input[type="date"], input[type="email"], input[type="tel"], input[type="number"], input[type="url"], input[type="password"], input[type="search"], select, textarea 
    {
	    border: 1px solid #e1e1e1 !important;
	    width: 100% !important;
	    height: 38px !important;
	    padding: 5px 10px !important;
	    border-radius: 0 !important;
	} */

	.form-control, .btn {
		font-size: 14px !important;
	}
	* {
        box-sizing: border-box;
    }
    </style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-title">Court >> Court Master (NSH) >> Reports >> Gist Module</h3>
                            </div>
                        </div>
                    </div>
                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                            	<form id="myform" action="#" enctype="multipart/form-data" method="" class="form-inline" >
                                <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                <fieldset style="padding:20px; border:1px solid #95bee7;">
                                    <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Mainhead</b></legend>      
                                    <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked" class="form-control" >Misc&nbsp;
                                    <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular" class="form-control">Regular
                                </fieldset>

                                <fieldset style="padding:20px; border:1px solid #95bee7;">
                                <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Listing Dates</b></legend>
                                <input type="date" name="listing_dts" id="listing_dts" class="form-control"/>
                            </fieldset>


<!-- 
                             <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Action</b></legend>            
                                <input type="button" name="btn1" id="btn1" value="Submit"/>
                            </fieldset> -->


                            <!-- <fieldset>
                                <legend>Action</legend>
                                <input type="button" name="bt1" id="bt1" value="Get Records"/>
                            </fieldset> -->


                            <!-- <fieldset>
                                <legend>Part No.</legend>
                                <select class="ele" name="part_no" id="part_no">
                                    <option value="-1" selected>EMPTY</option>
                                </select>
                            </fieldset>



                            <fieldset>
                            <legend>Part No.</legend>
                            <select class="ele" name="part_no" id="part_no">
                                <?php
                                for($i=1;$i<100;$i++){
                                    ?>
                                    <option value="<?php echo $i ?>" > <?php echo $i ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </fieldset> -->
                        

                        <!-- <fieldset>
                            <legend>Reshuffle</legend>
                            <input type="text" name="resh_from_txt" id="resh_from_txt" value="0" maxlength="4" size="5"/>
                            <span id="resf_span" style="background: #5fa3f9; border: #ffffff; color: #ffffff; height: 12px; padding: 4px;"><b>FROM</b></span>
                            <input type='button' name='re_shuffle' id='re_shuffle' value='Re-Shuffle'/>
                        </fieldset> -->

<!-- 
                        <fieldset>
                            <legend>Cause List Date</legend>
                            <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" readonly/>
                        </fieldset> -->



                        <fieldset style="padding:20px; border:1px solid #95bee7;">
                                <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Board Type</b></legend>
                                <select class="ele" name="board_type" id="board_type" class="form-control">
                                    <option value="0">-ALL-</option>
                                    <option value="J">Court</option>
                                    <option value="S">Single Judge</option>
                                    <option value="C">Chamber</option>
                                    <option value="R">Registrar</option>

                                </select>
                            </fieldset>
                            
                            <fieldset style="padding:20px;  border:1px solid #95bee7;">
                                <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Court No.</b></legend>
                                <select class="ele" name="courtno" id="courtno"  class="form-control">
                                    <option value="0">-ALL-</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="21">1 (Registrar)</option>
                                    <option value="22">2 (Registrar)</option>
                                </select>
                            </fieldset>


                            <fieldset style="padding:20px;  border:1px solid #95bee7;">
                                <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Main/Suppl.</b></legend>
                                <select class="ele" name="main_suppl" id="main_suppl"  class="form-control">
                                    <option value="0">-ALL-</option>
                                    <option value="1">Main</option>
                                    <option value="2">Suppl.</option>
                                </select>
                            </fieldset>


                            <fieldset style="padding:20px;  border:1px solid #95bee7;">
                                <legend style="background-color:#f6fbff; width:100%; text-align:center; border:1px solid #c5d5dd;"><b>Action</b></legend>            
                                <input type="button" name="btn1" id="btn1" value="Submit"/>
                            </fieldset>

                            <?php form_close();?>


                                 </form>



                                 <div id="dv_res1"></div>
                                
                                </div>
                                

								 </div>
								</div>
							</div>


                                                         
								 
                        
                </div>
            </div>
        </div>
    </div>
</section>
<script>

$(document).on("click","#btn1",function(){
            get_cl_1();
        });

        function get_cl_1(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
			var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            var mainhead = get_mainhead();
            var list_dt = $("#listing_dts").val();
            var courtno = $("#courtno").val();
            var board_type = $("#board_type").val();
            var main_suppl = $("#main_suppl").val();
            $.ajax({
               // url: 'summary_report_process.php',
                url:"<?php echo base_url('Court/CourtReportNHS/CourtReportGistModule/summary_report_process');?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead, courtno: courtno, board_type: board_type, main_suppl:main_suppl,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#dv_res1').html(data);

                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }

        function get_mainhead(){
            var mainhead = "";
            $('input[type=radio]').each(function () {
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
            return mainhead;
        }

        //function CallPrint(){
        $(document).on("click","#prnnt1",function(){
            //upd_gist_report();
            var prtContent = $("#prnnt").html();
            var temp_str=prtContent;
            var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            upd_gist_report();
        });


        function upd_gist_report(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
			var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            var mainhead = get_mainhead();
            var list_dt = $("#listing_dts").val();
            var courtno = $("#courtno").val();
            var board_type = $("#board_type").val();
            var main_suppl = $("#main_suppl").val();
            $.ajax({
                url:"<?php echo base_url('Court/CourtReportNHS/CourtReportGistModule/gist_report_update');?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead, courtno: courtno, board_type: board_type, main_suppl:main_suppl,CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    //$('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    //$('#dv_res1').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }

</script>
