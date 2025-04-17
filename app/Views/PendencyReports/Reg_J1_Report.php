<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Section wise J1 Report </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-1 mt-4">
                                <label for=""><b>Select Section</b></label>
                            </div>
						    <div class="col-md-2" id="section">
                                  <select class='form-control' name='section' id="section">
								      <option value="0">Select Section</option>
                                       <?php foreach($result_array as $vals){?>
									    <option value="<?=$vals['id'].'^'.$vals['section_name']?>"><?=$vals['section_name']?></option>
									   <?php }?>
								   </select>  
								   <input type='hidden' id='mysection' value=''>
                            </div>
							<div class="col-md-2 mt-4">
                                <label for=""><b>Select Main Subject Category</b></label>
                            </div>
							<div class="col-md-2" id="mainsubjectCategory">
                                  <select class='form-control' name='McategoryCode' id="McategoryCode" onchange= "get_sub_sub_cat()">
								      <option value="0">Select Main Subject Category</option>
                                       <?php foreach($getMainSubjectCategory as $vals){?>
									    <option value="<?=$vals['subcode1']?>"><?=$vals['subcode1'].' # '.$vals['sub_name1']?></option>
									   <?php }?>
								   </select>  
							</div>
							<div class="col-md-2 mt-4">
                                <label for=""><b>Select Sub Subject Category</b></label>
                            </div>
							<div class="col-md-2" id="mainsubjectCategory">
                                  <select class='form-control' name='categoryCode' id="categoryCode">
								      <option value="">All</option>
                                   </select>  
							</div>
						    <div class="col-md-1">
                                <input type="button" id="btnGetDiaryList" class="btn btn-block_ btn-primary" value="Submit" />
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
    
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
	
	
	function get_sub_sub_cat() { 
        var Mcat = $("#McategoryCode option:selected").val();
		var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?=base_url('/PendencyReports/Physical_verify/get_Sub_Subject_Category')?>',
            type: "POST",
            data: {Mcat:Mcat,CSRF_TOKEN: csrf},
            cache: false,
            dataType:"json",
			success: function(data){
				updateCSRFToken();
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].id + '">' + data[i].dsc + '</option>';

                }
                $("#categoryCode").html(options);

				},
            error: function () {
				updateCSRFToken();
                alert('ERRO');
            }
        });

    }
	
	
    $("#btnGetDiaryList").click(function() {
		var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var section = $("#section option:selected").val();
        var McategoryCode = $("#McategoryCode option:selected").val();
        var categoryCode = $("#categoryCode option:selected").val(); 
		if(section==''){
			alert('Pleas Select Section');
			return false;
		}else if(McategoryCode==''){
			alert('Pleas Select Main Subject Category');
			return false;
		}else if(categoryCode==''){
			alert('Pleas Select Sub Subject Category');
			return false;
		}
		$("#dv_res1").html('');
        $.ajax({
            url: '<?php echo base_url('/PendencyReports/Physical_verify/Reg_J1_Report_get') ?>',
            type: "POST",
            cache: false,
            async: true,
			beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                CSRF_TOKEN: csrf,
                section:section,
				McategoryCode:McategoryCode,
				categoryCode:categoryCode
            },
            success: function(r) {
				updateCSRFToken();
                $("#dv_res1").html(r);
            },
            error: function() {
                updateCSRFToken();
                alert('ERRO');
            }
        });
        updateCSRFToken();
    });
	
	
	$(document).ready(function() {
        disableAllElements();
        enableElements($('#subjectCategory').children());
        $('#subjectCategory').show();
        enableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').show();

        
        if($("input[name='reportType']:checked").val()==0){
            disableAllElements();
            enableElements($('#subjectCategory').children());
            $('#subjectCategory').show();
            enableElements($('#mainsubjectCategory').children());
            $('#mainsubjectCategory').show();
        }
        else if($("input[name='reportType']:checked").val()==1){
            disableAllElements();

            enableElements($('#courtType').children());
            $('#courtType').show();
        }
        else if($("input[name='reportType']:checked").val()==2){
            disableAllElements();

            enableElements($('#listDateType').children());
            $('#listDateType').show();
        }
        else if($("input[name='reportType']:checked").val()==4){
            disableAllElements();

            enableElements($('#fromToDatePicker').children());
            $('#fromToDatePicker').show();
        }

        $("input[name$='reportType']").click(function() {

            var searchValue = $(this).val();
            //alert('hello '+ searchValue);
            if(searchValue==0)
            {
                disableAllElements();
                enableElements($('#subjectCategory').children());
                $('#subjectCategory').show();
                enableElements($('#mainsubjectCategory').children());
                $('#mainsubjectCategory').show();

            }
            else if(searchValue==1)
            {
                disableAllElements();

                enableElements($('#courtType').children());
                $('#courtType').show();
            }
            else if(searchValue==2)
            {
                disableAllElements();

                enableElements($('#listDateType').children());
                $('#listDateType').show();
            }
            else if(searchValue==3)
            {
                disableAllElements();
            }
            else if(searchValue==4)
            {
                disableAllElements();

                enableElements($('#fromToDatePicker').children());
                $('#fromToDatePicker').show();
            }
            else
            {
                disableAllElements();
            }


        });


        $(function() {

            $('#section').change(function() {
                $('#mysection').val =$("#section option:selected").text();
            });
        });

        $(function () {
            $(".select2").select2();
        });

        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            });
        });


    function disableAllElements()
    {
        
        disableElements($('#subjectCategory').children());
        $('#subjectCategory').hide();

        disableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').hide();

        disableElements($('#fromToDatePicker').children());
        $('#fromToDatePicker').hide();

        disableElements($('#courtType').children());
        $('#courtType').hide();

        disableElements($('#listDateType').children());
        $('#listDateType').hide();

    }
    function enableAllElements()
    {
        enableElements($('#subjectCategory').children());
        $('#subjectCategory').show();

        enableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').show();

        enableElements($('#fromToDatePicker').children());
        $('#fromToDatePicker').show();

        enableElements($('#courtType').children());
        $('#courtType').show();

        enableElements($('#listDateType').children());
        $('#listDateType').show();

    }

    function disableElements(el) {
        for (var i = 0; i < el.length; i++) {
            el[i].disabled = true;
            disableElements(el[i].children);
        }
        
    }
	
    function enableElements(el) {
        for (var i = 0; i < el.length; i++) {
            el[i].disabled = false;
            enableElements(el[i].children);
        }
    }

});

   
</script>