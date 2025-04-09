<?= view('header') ?>
<style>
    #div1 {
        /* margin-top: 4%; */
    }

    #d,
    #cat {
        /* margin-left: 25px; */
        /* font-size: 15px;
        vertical-align: top;
        font-weight: bold; */
        /*margin-top: 20px;*/
    }

    #judge {
        /*margin-top:8px;*/
        /* margin-left: 10px;
        padding-left: 3px; */
    }

    #datef,
    #fdate,
    #tdate,
    #ddatef {
        /* vertical-align: top;
        margin-left: 50px;
        font-size: 15px; */
    }

    #datef,
    #ddatef {
        /* font-weight: bold; */
    }

    #fdate,
    #tdate {
        /* vertical-align: top;
        margin-left: 3px; */
    }

    #sp {
        /* vertical-align: top;
        margin-left: 5px;
        font-weight: bold; */
    }

    #div2 {
        /*margin-top: 10px;*/
    }

    #category {
        /*border: 20px;*/
        /* margin-left: 10px; */
        /* overflow: scroll; */
        /* width: 15%; */
        /* vertical-align: top; */
        /* margin-right: 10px; */
    }

    #subcat {
        /* margin-left: 20px; */
        /* vertical-align: top; */
        /* font-weight: bold; */
        /*display:none;*/
        /* font-size: 15px; */
    }

    #subcategory {
        /* width: 25%; */
        /* top: auto; */
        /*overflow:-moz-scrollbars-horizontal;*/
        /* overflow-x: scroll; */
        /* margin-left: 7px; */
        /* height: 80%; */
        /* padding-left: 3px; */
        /*display:none;*/
    }

    #btn {
        /*vertical-align: top;*/
        /* margin-left: 40%; */
        /* margin-top: 5px; */

    }

    #judgebtn {
        /* margin-left: 30%; */
        /* margin-top: 2%; */
        display: none;
    }

    #div3 {
        /* margin-top: 3%; */
    }

    #selsub {
        /* vertical-align: top; */
        /* margin-left: 18px; */
        /* font-weight: bold; */
        /* font-size: 15px; */
        /*margin-top: 5%;*/
    }

    #selsubcat {
        /* width: 25%; */
        /*margin-top:2%;*/
        /* overflow-x: scroll;
        margin-left: 3px; */

    }

    #btn_rem {
        /*vertical-align: top;*/
        margin-top: 5px;
        /* margin-left: 28%; */
    }

    #btn_submit {
        /* margin-left: 25%; */
        /* margin-top: 2%; */
    }

    #image {
        /* margin-left: 25%; */
        /* margin-top: 2%; */
    }

    #errmsg1,
    #errmsg2,
    #errmsg3,
    #errmsg4 {
        display: none;
        color: red;
        margin-left: 3%;
        font-size: 15px;
        margin-top: 0px;
        width: 50%;
    }

    #div3 {
        /*display:none;*/
        /* margin-top: 2%; */
    }

    #p {
        /* margin-left: 2%; */
        /* vertical-align: top; */
        /* font-weight: bold; */
        /* font-size: 15px; */
    }

    #mr {
        /* vertical-align: top; */
        /* margin-left: 3px; */
    }

    #tabl {
        /* width: 96%; */
        /* margin-left: 2%; */
        /* margin-right: 2%; */
    }

    .subcategory-buttons .quick-btn {
    font-size: 13px;
    padding: 8px 15px;
}

.subcategory-buttons {
    margin: 5px 0;
}
.row.mx-0{margin-left: 0!important;margin-right: 0!important;}
</style>
<section class="content">
   <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Category Wise Judge Matters</h3>
                        </div>
                    </div>
                </div>
				<div class="card-body">
                <?php
                        echo form_open();
                        csrf_token();
                        ?>

                    <div id="dv_content1">


                        <div id="errmsg3"></div>
                        <div id="errmsg4"></div>
                        <div id="errmsg2"></div>
                        <div id="errmsg1"></div>

                        <div id="div2">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <label for="category" id="cat" class="form-label"><b>Category</b></label>

                                    <?php
                                    $uniqueCategories = [];
                                    foreach ($categories as $category) {
                                        if (isset($category['subcode1']) && isset($category['category'])) {
                                        $uniqueCategories[$category['subcode1']] = $category;
                                        }
                                    }$categories = array_values($uniqueCategories);
                                    ?>

                                    <select id="category" name="category" class="form-select cus-form-ctrl">
                                        <option value="a"><b>Select Category</b></option>

                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= esc($category['subcode1']) ?>"><?= esc($category['category']) ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No categories found</option>
                                        <?php endif; ?>
                                    </select>  
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <label for="subcategory" id="subcat"  class="form-label"><b>Subcategory</b></label>
                                    <select class="form-select cus-form-multiselect" id="subcategory" name="subcategory[]" size="6" multiple>
                                        </select>
                                    <div class="subcategory-buttons">
                                        <button type="button" class="quick-btn" id="btn" onclick="data_select();">Select Subcategory</button>
                                        <button type="button" class="quick-btn gray-btn" id="btn_rem" onclick="remove_data()">Remove Selected Subcategory</button>
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                <label for="selsubcat" id="selsub"  class="form-label"><b>Selected subcategory</b></label>
                                    <select class="form-select cus-form-multiselect" id="selsubcat" name="selsubcat[]" size="6" multiple>
                                    </select>
                                </div>     
                                <div class="mb-3 col-sm-12 col-md-12 col-lg-12">
                                   
                                </div>
                            </div>

                            <div id="div3">
                                <div class="row">
                                    <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                        <label for='mr' id="p"  class="form-label"><b>Mainhead</b></label>
                                        <select id="mr" name="mr"  class="form-select cus-form-ctrl">
                                            <option value="l">Select</option>
                                            <option value="a">All</option>
                                            <option value="M">Miscellaneous</option>
                                            <option value="F">Regular</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row mx-0">
                                            <div class="col-12 col-md-6">
                                                <label for="fdate" id="datef" class="form-label"><b>Tentative Date</b></label>
												<input type="text" name="fdate" id="fdate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y') ?>"  autocomplete="on" size="9" readonly>
												<input type="hidden" name="hd_from_dt1" id="hd_from_dt1" value="1" />
                                                <!--<input type="date" id="fdate" name="fdate" class="form-control cus-form-ctrl">-->
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label id="sp" class="form-label"><b>to</b></label>
												<input type="text" name="tdate" id="tdate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y') ?>"  autocomplete="on" size="9" readonly>
												<input type="hidden" name="hd_to_dt1" id="hd_to_dt1" value="1" />
                                                <!--<input type="date" id="tdate" name="tdate" class="form-control cus-form-ctrl">-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row mx-0">
                                            <div class="col-12 col-md-6">
                                                <label for="dfdate" id="ddatef" class="form-label"><b>Diary Date</b></label>
												<input type="text" name="dfdate" id="dfdate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y') ?>"  autocomplete="on" size="9" readonly>
												<input type="hidden" name="hdd_from_dt1" id="hdd_from_dt1" value="1" />
                                                <!--<input type="date" id="dfdate" name="dfdate" class="form-control cus-form-ctrl">-->
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label id="sp" class="form-label"><b>to</b></label>
												<input type="text" name="dtdate" id="dtdate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y') ?>"  autocomplete="on" size="9" readonly>
												<input type="hidden" name="hdd_to_dt1" id="hdd_to_dt1" value="1" />
                                                <!--<input type="date" id="dtdate" name="dtdate" class="form-control cus-form-ctrl">-->
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                                


                                
                                <!-- <span id="sp">to</span>
                                <input type="date" id="dtdate" name="dtdate"> -->
                                <!--    <button type="button" id="btn_rem" onclick="remove_data()">Remove Selected Subcategory</button>-->
                                <div>


                                    <div id="div1">
                                        <div class="row">
                                            <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                                <label for="judge" id="judgeLabel"><b>Hon'ble Judge Name</b></label>
                                                <select id="judge" name="judge[]" multiple class="form-select cus-form-multiselect">
                                                    <option value="0" onclick="return selectAll('judge', true)">Select All</option>
                                                    <option value="0" onclick="return selectAll('judge', false)">Deselect All</option>
                                                    <option value="b">Blank</option>
                                                    <?php if (!empty($judges)): ?>
                                                        <?php foreach ($judges as $judge): ?>
                                                            <option value="<?= esc($judge['jcode']) ?>"><?= esc($judge['jname']) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No judges found</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-sm-12 col-md-12 col-lg-12">
                                                <button type="button" id="judgebtn" onclick="select_judge()" class="quick-btn">Select Judge</button>
                                            </div>
                                        </div>
									</div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="center-buttons">
                                                <button type="button" id="btn_submit" onclick="fetch_data()" class="quick-btn">Submit</button>
                                            </div>

                                        </div>
                                    </div> 
								</div>
							</div>	
						</div>
                    </div>						
						<?php echo form_close(); ?>
                        <br>
                        <div id="dv_content1">
                            <div id="dv_res1" style="align-content: center"></div>
                            <div id="ank"></div>
                        </div>									
				</div>
         </div>
    </div>
</div>	
</div>	
</section>



<script type="text/javascript">
    var subcate = [];

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });


    function fetch_data() {

        $('#errmsg1').hide();
        $('#errmsg2').hide();
        $('#errmsg3').hide();
        $('#errmsg4').hide();
        $('#tabl').hide();
        var judge = getSelectedValue('judge');
        var selsubcat = getAllValue('selsubcat');
        var tdate = document.getElementById('tdate').value;
        var fdate = document.getElementById('fdate').value;
        var mainhead = document.getElementById('mr').value;
        var jud_num = '<?php echo $judge_count; ?>';

        var dtdate = document.getElementById('dtdate').value;
        var dfdate = document.getElementById('dfdate').value;

       



        var dtt = tdate.split('-');
        var dt_chkt = dtt[0] + dtt[1] + dtt[2];
        var dtf = fdate.split('-');
        var dt_chkf = dtf[0] + dtf[1] + dtf[2];
       // alert()

        var dtt = dtdate.split('-');
        var dt_dchkt = dtt[0] + dtt[1] + dtt[2];
        var dtf = dfdate.split('-');
        var dt_dchkf = dtf[0] + dtf[1] + dtf[2];


        //debugger;
        if (judge == '' || tdate == '' || fdate == '' || selsubcat == '' || mainhead == 'l' || (dt_chkf > dt_chkt) || (dt_dchkf > dt_dchkt)) {
           // debugger;
            if (judge == '') {
                document.getElementById('errmsg1').innerHTML = 'Error: Please select Judge';
                $('#errmsg1').show();

            }

            if (tdate == '' || fdate == '') {
                document.getElementById('errmsg2').innerHTML = 'Error: Please Enter Tentative date';
                $('#errmsg2').show();

            } else if (dt_chkf > dt_chkt) {
                document.getElementById('errmsg2').innerHTML = 'Error: Tentative From date should be smaller or equal to Todate';
                $('#errmsg2').show();
            }


            if (dt_dchkf > dt_dchkt) {
                document.getElementById('errmsg2').innerHTML = 'Error: Diary From date should be smaller or equal to Todate';
                $('#errmsg2').show();
            }

            if (selsubcat == '') {
                document.getElementById('errmsg3').innerHTML = 'Error: Please select subcategory';
                $('#errmsg3').show();

            }

            if (mainhead == 'l') {
                document.getElementById('errmsg4').innerHTML = 'Error: Please select Mainhead';
                $('#errmsg4').show();
            }

        } else {
            //debugger;
            if (dfdate != '') {
                if (dtdate == '') {
                    document.getElementById('errmsg2').innerHTML = 'Error: Enter Diary To date';
                    $('#errmsg2').show();
                    return;
                }
            } else if (dtdate != '') {
                if (dfdate == '') {
                    document.getElementById('errmsg2').innerHTML = 'Error: Enter Diary From date';
                    $('#errmsg2').show();
                    return;
                }
            }
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

        

            $.ajax({
                type: "POST",
                url: '<?php echo base_url('ManagementReports/Report/category_data_fetch'); ?>',
                data: {
                    selsubcat: selsubcat,
                    mainhead: mainhead,
                    tdate: tdate,
                    fdate: fdate,
                    dtdate: dtdate,
                    dfdate: dfdate,
                    judge: judge,
                    jud_num: jud_num,
                    CSRF_TOKEN: csrf,
                },
				
				beforeSend: function() {
                  $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
				
                success: function(data) {
                   updateCSRFToken();
					$('#dv_res1').html(data);
                },
                error: function() {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
          });

 }
}

function remove_data(){
        var temp = getSelectedValue('selsubcat');
        for (var p = temp.length - 1; p >= 0; p--) {
            for (var t = 0; t < subcate.length; t++) {
                if (temp[p] == subcate[t]) {
                    subcate.splice(t, 1);
                }
            }

        }

        var options = '';
        $("#selsubcat").html(options);
        for (var i = 0; i < subcate.length; i++) {
            options += '<option value="' + subcate[i] + '">' + subcate[i] + '</option>';


        }
        $("#selsubcat").html(options);
}



function data_select() {
      var options = '';
        var temp = getSelectedValue('subcategory');
        for (var p = 0; p < temp.length; p++) {
            if ($.inArray(temp[p], subcate) == -1) {
                subcate.push(temp[p]);
            }
        }
        for (var i = 0; i < subcate.length; i++) {
            options += '<option value="' + subcate[i] + '">' + subcate[i] + '</option>';


        }
        $("#selsubcat").html(options);

    }


    function select_judge() {
        var judge = getSelectedValue('judge');
    }




    function getSelectedValue(id) {
        var result = [];
        var options = document.getElementById(id);
        var opt;
        for (var i = 0, iLen = options.length; i < iLen; i++) {
            opt = options[i];

            if (opt.selected) {
                result.push(opt.value);
            }
        }
        return result;
    }


    function getAllValue(id) {
        var result = [];
        var options = document.getElementById(id);
        var opt;
        for (var i = 0, iLen = options.length; i < iLen; i++) {
            opt = options[i];

            result.push(opt.value);
        }
        return result;
    }



    $(function() {
       $('#category').change(function() {
            var options = '';
            $("#subcategory").html(options);
            fetchsubcat();
        });

    });



    function fetchsubcat() {

        
        var cat = document.getElementById('category').value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            type: "post",
            url: '<?php echo base_url('ManagementReports/Report/getSubcategories'); ?>',
            data: {
                cat: cat,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            dataType: "json",

            beforeSend: function() {},

            complete: function() {},

            success: function(data) {
                updateCSRFToken();
                console.log(data);
                console.log(data.length);
                var options = '';
                if (data.length != 1) {
                    options += '<option value="0" onclick="return selectAll(\'subcategory\', true)">Select All</option>';
                    options += '<option value="0" onclick="return selectAll(\'subcategory\', false)">Deselect All</option>';
                }
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].id + '">' + data[i].sub_name4 + '</option>';

                }
                $("#subcategory").html(options);
                $('#subcat').show();
                $('#subcategory').show();



            },
            Error: function() {
                updateCSRFToken();
                alert('Error');
            }
        });
    }



    function selectAll(id, isSelected) {
        var selectObj = document.getElementById(id);
        var options = selectObj.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value == 0)
                options[i].selected = false;
            else
                options[i].selected = isSelected;

        }
    }
</script>