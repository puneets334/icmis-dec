<?= view('header') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Question of Law >> Insert</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                            <div id="error-message" class="alert alert-danger" style="position: fixed; z-index: 10000;"></div>
                            <div id="success-message" class="alert alert-success" style="position: fixed; z-index: 10000;"></div>
                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
        <form role="form" id="case-form">
            <?= csrf_field() ?>
            <div class="col-md-12">
                <div class="well66">
                    <br/>
                    <div class="row  align-items-center">
                        
                        <div class="col-md-2">
                            <div class="form-group2 clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                                    <label for="search_type_d">
                                        Diary
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline2 mt-3">
                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                                    <label for="search_type_c">
                                        Case Type
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3  diary_section">
                            <div class="form-group row">
                                <label for="inputEmail3" class="ml-2 col-form-label">Diary No</label>
                                <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No">
                            </div>
                        </div>
                        <div class="col-md-3  diary_section">
                            <div class="form-group row">
                                <label for="inputEmail3" class="ml-2 col-form-label">Diary Year</label>
                                <?php $year = 1950;
                                $current_year = date('Y');
                                ?>
                                <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                        <option><?php echo $x; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-7 casetype_section" style="display: none;">
                            <div class="row">
                                   
                                    <div class="col-md-4 casetype_section" style="display: none;">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-form-label mb-2">Case type</label>
                                                <select name="case_type" id="case_type" class="custom-select rounded-0 select2" style="width: 100%;">
                                                    <option value="">Select case type</option>
                                                    <?php
                                                    foreach ($case_type as $row) {
                                                        echo '<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                    </div>
                                    <div class="col-md-4 casetype_section" style="display: none;">

                                            <div class="form-group row ">
                                                <label for="inputEmail3" class="col-form-label">Case No</label>
                                                <input type="number" class="form-control" id="case_number" name="case_number" placeholder="Enter Case No">
                                            </div>
                                    </div>
                                    <div class="col-md-4 casetype_section" style="display: none;">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class=" col-form-label">Case Year</label>
                                            <?php $year = 1950;
                                            $current_year = date('Y');
                                            ?>
                                            <select name="case_year" id="case_year" class="custom-select rounded-0">
                                                <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                    <option><?php echo $x; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>


                            </div>
                        </div>
                        
                        
                        

                        <div class="col-md-3 mt-3">
                            <button type="btton" class="btn btn-primary" id="submit" onclick="get_detail(event)">Search Case</button>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <br/>
                    <hr style="background-color: #808080;height: 1px;">
                    <div style="text-align: center;" class="row" id="consignOn"></div>
                    <div style="text-align: center;" class="row" id="verify"></div>
                    <div class="row">
                        <input type="hidden" name="usercode" id="usercode" value="<?=$usercode;?>" >
                        <label class="d-none" id="case_diaryno" name="case_diaryno"></label>
                        <div class="col-md-5"><div class=""><span class="">Diary Number</span><label class="form-control" id="case_diary" name="case_diary"></label></div></div>
                        <div class="col-md-7"><div class=""><span class="">Cause Title</span><label class="form-control" id="case_title" name="case_title"></label></div></div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-3"><div class=""><span class="">Section:</span><label class="form-control" id="section" name="section"></label></div></div>
                        <div class="col-md-4"><div class=""><span class="">Case No.:</span><label class="form-control" id="caseNo" name="caseNo" ></label></div></div>
                    </div>

                    <hr style="background-color: #808080;height: 1px;">
                    <div id="conAdditionalEntry">
                        <div class="row form-group">
                            <div class="col-md-12 col-sm-12">
                                <label for="Status" class="col-sm-3 ">Question of Law:</label>
                                <div class="input-group input-group-sm col-sm-9">
                                    <!--<span class="input-group-addon">Remarks:</span>-->
                                    <textarea name="lawPoint" id="lawPoint" required rows="5" cols="80"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-sm-12 row" id="keywords">
                            <label for="category" class="col-sm-3">Select Keyword:</label>
                            <div class="col-sm-9">
                                <select class="form-control select2 col-sm-9" id="keyword" name="keyword" placeholder="Select Multiple Keyword" multiple="multiple" required="required">
                                    <option value="0">All</option
                                    <?php
                                    foreach($keywords as $keyword){
                                        echo '<option value="' . $keyword['id'] . '">'. $keyword['keyword_code'] .'&nbsp;:&nbsp;' .$keyword['keyword_description']. '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-12 row" id="acts">
                            <label for="category" class="col-sm-3">Select Act:</label>
                            <div class="col-sm-9">
                                <select class="form-control select2 col-sm-9" id="act" name="act" placeholder="Select Multiple Act" multiple="multiple"  required="required">
                                    <option value="0">All</option
                                    <?php
                                    foreach($acts as $act){
                                        echo '<option value="' . $act['id'] . '">'.$act['act_name']. '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 row" id="acts">
                            <label for="category" class="col-sm-3">Enter Catchwords:</label>
                            <div class="input-group col-sm-9">
                                <div class="col-sm-9">
                                    <?php  if($param[0]['usertype']==6) { ?>
                                        <p><input  class="form-group col-sm-12" type="text" id="catchwords" name="catchwords" ></p>
                                    <?php } else { ?>
                                        <p><input  class="form-group col-sm-12" type="text" id="catchwords" name="catchwords" disabled="disabled"></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 row">
                        <input type="hidden" name="usertype" id="usertype" value="<?=$param[0]['usertype'];?>" >
                    </div>

                    <br/>
                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-6 col-md-offset-3"><button type="button" id="btn-update" class="btn bg-olive btn-flat pull-right" onclick="update_case();" tabindex="4"><i class="fa fa-save"></i>
                                <?php  if($param[0]['usertype']==6) { ?>
                                    Verify Case
                                <?php }  else { ?>
                                    Update Case
                                <?php } ?>
                            </button></div>
                    </div>
                    
                </div>

            </div>
        </form>
        <!-- Page Content End -->
        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main content End -->
<script>
    function isEmpty(obj) {
        if (obj == null) return true;
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (hasOwnProperty.call(obj, key)) return false;
        }

        return true;
    }

    $(document).ready()
    {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $(function(){

            $("#case_type").change(function(){
                var status = this.value;
                //alert(status);
                $("#case_year")[0].selectedIndex = 0;
                $('#case_number').val("");
            });

        });

        $('#consignment_date').prop('disabled',false);
        $('#remarks').prop('disabled',false);
        $('#conAdditionalEntry').show();

        // $('#diary_number').prop('disabled',true);
        // $('#diary_year').prop('disabled',true);

        $( function() {
            $( "#consignment_date" ).datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth : true,
                changeYear  : true,
                yearRange: "1950:2017",
                autoSize: true,
                setDate: new Date()
            }).datepicker("setDate", new Date());
        } );

        $('#error-message').hide();
        $('#success-message').hide();
    }

    function display_success(message, focus_element='')
    {
        $('#success-message').text(message).show().delay(3000).fadeOut(500);
        if(focus_element != '') {
            $('#'+focus_element).trigger('focus');
        }
    }

    function display_error(message, focus_element='')
    {
        $('#error-message').text(message).show().delay(3000).fadeOut(500);
        if(focus_element != '') {
            $('#'+focus_element).trigger('focus');
        }
    }

    function form_reset() {
        $('#case_diary').text('');
        $('#case_diaryno').text('');
        $('#case_title').text('');
        $('#caseNo').text('');
        $('#lawPoint').text('');
        $('#section').text('');
        $('#remarks').val('');
        $("#keyword").val([]).trigger('change');
        $("#act").val([]).trigger('change');
    }

    async function get_detail(e) {
        
        e.preventDefault();

        await updateCSRFTokenSync();
        
        form_reset();
        $("#consignOn").hide();
        $("#verify").hide();

        var option=$('input:radio[name=search_type]:checked').val();
        var caseNumber=$('#case_number').val();
        var caseType=$('#case_type').val();
        var case_year=$('#case_year').val();
        var diaryNo=$('#diary_number').val();
        var diary_year=$('#diary_year').val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if(option == 'C' && !isEmpty(caseNumber) && !isEmpty(caseType) && !isEmpty(case_year) && case_year!=0)
        {
            $.post("<?=base_url();?>/Judicial/QuestionofLaw/get_details", {search_type : option, case_number:caseNumber, case_type:caseType, case_year:case_year,CSRF_TOKEN:CSRF_TOKEN_VALUE}, function (obj) {
                
                if(obj.success != undefined && obj.success == 0)
                {
                    display_error("The searched case is not found");
                    form_reset();
                }
                else
                {
                    var case_status=obj.case_detail[0]['c_status'];
                    if(case_status=='D')
                    {
                        display_error("The searched case is Disposed!");
                        form_reset();
                    }
                    else {
                        var diary_details = obj.case_detail[0]['diary_no'] + '/' + obj.case_detail[0]['diary_year'] + ' filed on:' + obj.case_detail[0]['diary_date'];
                        $('#case_diary').text(diary_details);
                        $('#case_diaryno').text(obj.case_detail[0]['case_diary']);
                        $('#case_title').text(obj.case_detail[0]['case_title']);
                        $('#section').text(obj.case_detail[0]['user_section']).css({
                            "olor": "Green",
                            "font-size": "15px",
                            "font-weight": "bold"
                        });
                        $('#caseNo').text(obj.case_detail[0]['reg_no_display']);
                        var lawPointEnteredOn = obj.case_detail[0]['updated_date'];
                        var lawPointEnteredBy = obj.case_detail[0]['lawPointEnteredBy'];
                        if (obj.case_detail[0]['question_of_law'] != null) {
                            $("#consignOn").show();
                            $('#lawPoint').text(obj.case_detail[0]['question_of_law']);
                            $("#consignOn").html('<p style="color: Red;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Entered On : ' + lawPointEnteredOn + ' [Entered By : ' + lawPointEnteredBy + ']</p>');
                        }
                        else
                            $('#lawPoint').text('');

                        var act = (obj.case_detail[0]['acts']);
                        var keyword = (obj.case_detail[0]['keyword']);
                        var catchword = (obj.case_detail[0]['Catchwords']);
                        $('#catchwords').val(catchword);

                        // bind keyword values start

                        if(keyword!="" && keyword!=null) {
                            var keywords = new Array();
                            keywords = keyword.split(",");
                            var Values1 = new Array();
                            if (keywords.length > 0) {
                                $.each(keywords, function (index, value) {
                                    Values1.push(parseInt(value));
                                });
                            }
                            else {
                                Values1 = [];
                            }
                            $("#keyword").val(Values1).trigger('change');
                        }

                        // bind keyword values end

                        // bind acts values start

                        if(act!="" && act!=null) {

                            var acts = new Array();
                            acts = act.split(",");
                            var Values = new Array();
                            if (acts.length >= 0) {
                                $.each(acts, function (index, value) {
                                    Values.push(parseInt(value));
                                });
                            }
                            else {
                                Values = [];
                            }
                            $("#act").val(Values).trigger('change');
                        }
                        // bind acts values end

                        var lawPointStatus = obj.case_detail[0]['question_of_law'];
                        var lawPointEntryBy = obj.case_detail[0]['lawPointEnteredBy'];

                        if (lawPointStatus != null && lawPointEntryBy != null) {
                            $("#consignOn").show();
                            $("#verify").show();
                            // $('#consignment_date').datepicker('setDate', obj.case_detail[0]['updated_date']);

                            var isVerified = parseInt(obj.case_detail[0]['is_verified']);
                            var userType = parseInt($('#usertype').val());
                            var lawPointVerifiedOn = obj.case_detail[0]['verified_on'];
                            var lawPointVerifiedBy = obj.case_detail[0]['verified_by'];
                            //alert(lawPointVerifiedOn+'#'+lawPointVerifiedBy);
                            $("#consignOn").html('<p style="color: Red;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Entered On : ' + lawPointEnteredOn + ' [Entered By : ' + lawPointEnteredBy + ']</p>');
                            if (isVerified == 1 && lawPointVerifiedOn != null && lawPointVerifiedBy != null) {
                                $("#verify").html('<p style="color: blue;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Verified On : ' + lawPointVerifiedOn + ' [Verified By : ' + lawPointVerifiedBy + ']</p>');
                            }
                            // 6 usertype of Deputy Registrar
                            if (isVerified == 1 && userType != 6) {
                                $("#btn-update").attr("disabled", true);
                                $("#btn-update").hide();
                            }
                            else {
                                $("#btn-update").removeAttr("disabled");
                                $("#btn-update").show();
                            }

                        }
                        else {
                            $("#consignOn").hide();
                            $("#verify").hide();
                            $(function () {
                                $("#consignment_date").datepicker({
                                    dateFormat: "dd-mm-yy",
                                    changeMonth: true,
                                    changeYear: true,
                                    yearRange: "1950:2017",
                                    autoSize: true,
                                    setDate: new Date()
                                }).datepicker("setDate", new Date());
                            });
                        }
                        if (obj.case_detail[0]['question_of_law'] != null) {
                            if (obj.case_detail[0]['question_of_law'] == 'null') {
                                $('#remarks').val("");
                            }
                            else {
                                $('#remarks').text(obj.case_detail[0]['question_of_law']);
                            }
                        }
                    }

                }
            });
        }
        else if(option == 'D' && !isEmpty(diaryNo) && !isEmpty(diary_year) && diary_year!=0)
        {

            $.post("<?=base_url();?>/Judicial/QuestionofLaw/get_details", {search_type : option, diary_number:diaryNo, diary_year:diary_year,CSRF_TOKEN:CSRF_TOKEN_VALUE}, function (obj)
            {
                if(obj.success != undefined && obj.success == 0)
                {
                    display_error("The searched case is not found");
                    form_reset();
                }
                else
                {
                    var case_status=obj.case_detail[0]['c_status'];
                    if(case_status=='D')
                    {
                        display_error("The searched case is Disposed!");
                        form_reset();
                    }
                    else {
                        var diary_details = obj.case_detail[0]['diary_no'] + '/' + obj.case_detail[0]['diary_year'] + ' filed on:' + obj.case_detail[0]['diary_date'];
                        $('#case_diary').text(diary_details);
                        $('#case_diaryno').text(obj.case_detail[0]['case_diary']);
                        $('#case_title').text(obj.case_detail[0]['case_title']);
                        $('#section').text(obj.case_detail[0]['user_section']).css({
                            "olor": "Green",
                            "font-size": "15px",
                            "font-weight": "bold"
                        });
                        $('#caseNo').text(obj.case_detail[0]['reg_no_display']);
                        var lawPointEnteredOn = obj.case_detail[0]['updated_date'];
                        var lawPointEnteredBy = obj.case_detail[0]['lawPointEnteredBy'];
                        if (obj.case_detail[0]['question_of_law'] != null) {
                            $("#consignOn").show();
                            $('#lawPoint').text(obj.case_detail[0]['question_of_law']);
                            $("#consignOn").html('<p style="color: Red;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Entered On : ' + lawPointEnteredOn + ' [Entered By : ' + lawPointEnteredBy + ']</p>');
                        }
                        else
                            $('#lawPoint').text('');

                        var act = (obj.case_detail[0]['acts']);
                        var keyword = (obj.case_detail[0]['keyword']);
                        var catchword = (obj.case_detail[0]['catchwords']);
                        $('#catchwords').val(catchword);

                        // bind keyword values start

                        if(keyword!="" && keyword!=null) {
                            var keywords = new Array();
                            keywords = keyword.split(",");
                            var Values1 = new Array();
                            if (keywords.length > 0) {
                                $.each(keywords, function (index, value) {
                                    Values1.push(parseInt(value));
                                });
                            }
                            else {
                                Values1 = [];
                            }
                            $("#keyword").val(Values1).trigger('change');
                        }

                        // bind keyword values end

                        // bind acts values start

                        if(act!="" && act!=null) {

                            var acts = new Array();
                            acts = act.split(",");
                            var Values = new Array();
                            if (acts.length >= 0) {
                                $.each(acts, function (index, value) {
                                    Values.push(parseInt(value));
                                });
                            }
                            else {
                                Values = [];
                            }
                            $("#act").val(Values).trigger('change');
                        }
                        // bind acts values end

                        var lawPointStatus = obj.case_detail[0]['question_of_law'];
                        var lawPointEntryBy = obj.case_detail[0]['lawPointEnteredBy'];

                        if (lawPointStatus != null && lawPointEntryBy != null) {
                            $("#consignOn").show();
                            $("#verify").show();
                            // $('#consignment_date').datepicker('setDate', obj.case_detail[0]['updated_date']);

                            var isVerified = parseInt(obj.case_detail[0]['is_verified']);
                            var userType = parseInt($('#usertype').val());
                            var lawPointVerifiedOn = obj.case_detail[0]['verified_on'];
                            var lawPointVerifiedBy = obj.case_detail[0]['verified_by'];
                            //alert(lawPointVerifiedOn+'#'+lawPointVerifiedBy);
                            $("#consignOn").html('<p style="color: Red;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Entered On : ' + lawPointEnteredOn + ' [Entered By : ' + lawPointEnteredBy + ']</p>');
                            if (isVerified == 1 && lawPointVerifiedOn != null && lawPointVerifiedBy != null) {
                                $("#verify").html('<p style="color: blue;font-size:20px;font-weight: bold;text-align=center;">Question of Law  Verified On : ' + lawPointVerifiedOn + ' [Verified By : ' + lawPointVerifiedBy + ']</p>');
                            }
                            // 6 usertype of Deputy Registrar
                            if (isVerified == 1 && userType != 6) {
                                $("#btn-update").attr("disabled", true);
                                $("#btn-update").hide();
                            }
                            else {
                                $("#btn-update").removeAttr("disabled");
                                $("#btn-update").show();
                            }

                        }
                        else {
                            $("#consignOn").hide();
                            $("#verify").hide();
                            $(function () {
                                $("#consignment_date").datepicker({
                                    dateFormat: "dd-mm-yy",
                                    changeMonth: true,
                                    changeYear: true,
                                    yearRange: "1950:2017",
                                    autoSize: true,
                                    setDate: new Date()
                                }).datepicker("setDate", new Date());
                            });
                        }
                        if (obj.case_detail[0]['question_of_law'] != null) {
                            if (obj.case_detail[0]['question_of_law'] == 'null') {
                                $('#remarks').val("");
                            }
                            else {
                                $('#remarks').text(obj.case_detail[0]['question_of_law']);
                            }
                        }
                    }

                }
            });
        } else {
            display_error("Enter Case Details");
            return false;
        }
    }

    async function update_case()
    {
        await updateCSRFTokenSync();

        var case_diary=$('#case_diaryno').text();
        var usercode=$('#usercode').val();
        var usertype=$('#usertype').val();
        //var usercode=1;
        var law_point=$('#lawPoint').val();
        /* if(!isEmpty(case_diary)  && !isEmpty(usercode)) {*/
        var acts=$('#act').val();
        var keywords=$('#keyword').val();
        var catchwords=$('#catchwords').val();
    
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        //alert(catchwords);
        if(!isEmpty(case_diary) )
        {
            law_point=$.trim(law_point);
            if(law_point==null || law_point=='')
            {
                display_error("Question of Law Cannot be Blank");
                return false;
            }
            $.post("<?=base_url();?>/Judicial/QuestionofLaw/update_case", {
                case_diary: case_diary,
                usercode: usercode,
                law_point:law_point,
                acts:acts,
                keywords:keywords,
                catchwords:catchwords,
                usertype:usertype,
                CSRF_TOKEN:CSRF_TOKEN_VALUE
            }, function (result) {

                display_success(result);
                form_reset();
            });
        }
        else {
            display_error("Enter Case Details");
            return false;
        }
    }
</script>