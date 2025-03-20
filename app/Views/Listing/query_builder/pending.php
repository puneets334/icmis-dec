 <?=view('header') ?>
 
 <style>
 #sidebar {
    width: 60%;
    position: fixed;
    top: 0;
    left: -60%;
    height: 100vh;
    z-index: 999;
    background: #ebe4e4;
    color: #191a1d;
    transition: all 0.3s;
    overflow-y: scroll;
    box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
}
#sidebar.active {
    left: 0;
}
#dismiss {
    width: 35px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    /* background: #7386D5; */
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
#dismiss:hover {
    background: #fff;
    color: #7386D5;
}
.bg-info {
    background-color: #17a2b8 !important;
}
.text-white {
    color: #fff !important;
}
#sidebar .sidebar-header {
    padding: 10px;
    background: #7f7c7c;
}
.form-check-input {
    position: absolute;
    margin-top: .3rem !important;
    margin-left: -1.25rem !important;
}
.input-group {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 100%;
}
 </style>
 
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title"> Pending Cases - Query Builder (For Listing Section Only)</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						
    
    <nav id="sidebar">
        <div id="dismiss">
            <i class="fas fa-arrow-left"></i>
        </div>

        <div class="sidebar-header bg-info text-white">
            <h4 class="m-0">Pending Cases Filter</h4>
        </div>



       
		<form name="parent_form" id="parent_form"  method="post" action="<?= site_url(uri_string()) ?>">
		<?= csrf_field() ?>


            <div class="form-group row p-1">
                <div for="app_date_range" class="col-sm-2">Filing Date</div>
                <div class="col-sm-10 input-group input-daterange" id="app_date_range">
                    <input type="date" class="form-control bg-white col-md-4" id="from_diary_date" name="from_diary_date"
                           placeholder="From Date..." />
                    <span class="px-2 col-md-1">to</span>
                    <input type="date" class="form-control bg-white col-md-4" id="to_diary_date" name="to_diary_date"
                           placeholder="To Date..." />
                </div>
            </div>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Connected</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="checkbox" name="connected[]" id="connected_exclude" value="1">
                            <label class="form-check-label" for="connected_exclude">
                                Exclude
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>


            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Stage</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="radio" name="mainhead" id="stage_misc" value="M">
                            <label class="form-check-label" for="stage_prenotice">
                                Misc.
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="radio" name="mainhead" id="stage_regular"
                                   value="F">
                            <label class="form-check-label" for="stage_afternotice">
                                Regular
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Board Type</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_court"
                                   value="J">
                            <label class="form-check-label" for="board_type_court">
                                Court
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_single_judge"
                                   value="S">
                            <label class="form-check-label" for="board_type_single_judge">
                                Single Judge
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_chamber"
                                   value="C">
                            <label class="form-check-label" for="board_type_chamber">
                                Chamber
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_registrar"
                                   value="R">
                            <label class="form-check-label" for="board_type_reg">
                                Registrar
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>


            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_updated" value="1">
                            <label class="form-check-label" for="status_updated">
                                Updated
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_updation_awaited"
                                   value="2">
                            <label class="form-check-label" for="status_updation_awaited">
                                Updation Awaited
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_not_ready"
                                   value="3">
                            <label class="form-check-label" for="status_not_ready">
                                Not Ready
                            </label>
                        </div>
                        <div class="form-check pr-5">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_listed" value="4">
                            <label class="form-check-label" for="status_listed">
                                Listed
                            </label>
                        </div>

                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Hon'ble Judge</legend>
                    <div class="col-sm-10 input-group">

                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="only_presiding"
                                   id="only_presiding" value="y">
                            <label class="form-check-label" for="only_presiding">
                                Only Presiding
                            </label>
                        </div>

                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="judge_exclude"
                                   id="case_type_exclude" value="y">
                            <label class="form-check-label" for="judge_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="judge" name="judge[]">
                            <?php echo judge(); ?>
                        </select>

                    </div>
                </div>
            </fieldset>






            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Subhead</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="subhead_exclude" id="subhead_exclude" value="y">
                            <label class="form-check-label" for="subhead_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="subhead" name="subhead[]">
                            <?php echo subheading(); ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Listing Purpose</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="lp_exclude" id="lp_exclude" value="y">
                            <label class="form-check-label" for="lp_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="lp" name="lp[]">
                            <?php echo listing_purpose(); ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="form-group row p-1">
                <div for="list_date_range" class="col-sm-2">Tentative Date</div>
                <div class="col-sm-10 input-group input-daterange" id="list_date_range">
                    <input type="date" class="form-control bg-white col-md-4" id="from_list_date" name="from_list_date"
                           placeholder="From Date..." />
                    <span class="px-2 col-md-1">to</span>
                    <input type="date" class="form-control bg-white col-md-4" id="to_list_date" name="to_list_date"
                           placeholder="To Date..." />
                </div>
            </div>





            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Category</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="category_exclude"
                                   id="category_exclude" value="y">
                            <label class="form-check-label" for="category_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="category" name="category[]">
                            <?php echo category(); ?>
                        </select>


                    </div>

                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Case Type</legend>
                    <div class="col-sm-10 input-group">

                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="case_type_exclude"
                                   id="case_type_exclude" value="y">
                            <label class="form-check-label" for="case_type_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="case_type" name="case_type[]">
                            <?php echo casetype(); ?>
                        </select>


                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Section</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="section_exclude"
                                   id="section_exclude" value="y">
                            <label class="form-check-label" for="section_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-control select2" multiple="multiple" id="section" name="section[]">
                            <?php echo judicial_section(); ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Dealing Assistant</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="da_exclude"
                                   id="da_exclude" value="y">
                            <label class="form-check-label" for="da_exclude">
                                Exclude
                            </label>
                        </div>
                        <select class="form-select select2" aria-label="Default select example" multiple="multiple" id="da" name="da[]">
                            <?php echo da(); ?>
                        </select>
                    </div>
                </div>
            </fieldset>



            <div class="row py-1">
                <div class="col-sm-2 pl-4">Auxiliary</div>


                <div class=" col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="coram_by_cji"
                               id="coram_by_cji_exclude" value="n">
                        <label class="form-check-label" for="coram_by_cji_exclude">
                            Exclude
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="coram_by_cji" id="coram_by_cji_include" value="y">
                        <label class="form-check-label" for="coram_by_cji_include">
                            Coram is given by Hon’ble the CJI
                        </label>
                    </div>
                </div>

            </div>


            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="conditional_matter"
                               id="conditional_matter_exclude" value="n">
                        <label class="form-check-label" for="conditional_matter_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="conditional_matter"
                               id="conditional_matter_include" value="y" >
                        <label class="form-check-label" for="conditional_matter_include">
                            Conditional matters
                        </label>
                    </div>
                </div>
            </div>

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="cav_matter"
                               id="cav_matter_exclude" value="n">
                        <label class="form-check-label" for="cav_matter_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cav_matter"
                               id="cav_matter_include" value="y" >
                        <label class="form-check-label" for="cav_matter_include">
                            CAV Matters
                        </label>
                    </div>
                </div>
            </div>

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="part_heard"
                               id="part_heard_exclude" value="n">
                        <label class="form-check-label" for="part_heard_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="part_heard"
                               id="part_heard_include" value="y" >
                        <label class="form-check-label" for="part_heard_include">
                            Part Heard
                        </label>
                    </div>
                </div>
            </div>

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="list_after_vacation"
                               id="list_after_vacation_exclude" value="n">
                        <label class="form-check-label" for="list_after_vacation_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="list_after_vacation"
                               id="list_after_vacation_include" value="y" >
                        <label class="form-check-label" for="list_after_vacation_include">
                           List After Vacation
                        </label>
                    </div>
                </div>
            </div>
 

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">

                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="sensitive" id="sensitive_exclude"
                               value="n">
                        <label class="form-check-label" for="sensitive_exclude">
                            Exclude
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sensitive" id="sensitive_include" value="y">
                        <label class="form-check-label" for="sensitive_include">
                            Sensitive
                        </label>
                    </div>

                </div>
            </div>


            <div class="form-group row p-1">
                <div class="col-sm-10">
                    <button type="button" id="dismiss2" class="btn btn-success get_pendency">Click to Get Pendency</button>
                </div>
            </div>
        </form>

        


    </nav>

    
     
			<div class="row col-12">

                    <div class="card col-12 p-0">
						<div class="card-header bg-info text-white font-weight-bolder">Pending Cases - Query Builder (For Listing Section Only) </div>
						<div class="card-body">

							<div class="row">
								<div class="col-6 text-left">
								<button type="button" id="sidebarCollapse" class="btn btn-info d-inline">
									<i class="fas fa-align-left"></i>
									<span>Filter</span>
								</button>
								</div>
								<div class="col-6 text-right">
								<button type="button" class="btn btn-success get_pendency d-inline">
									Click to Get Pendency
								</button>
								</div>
							</div>

						</div>
					</div>

			</div>
			<div class="row col-12 pendency_result"></div>
			<div class="row col-12 pendency_result_detail"></div>
         
	
	
</div>  



                    
                </div>
              
            </div>
           
        </div>
        
    </section>
	 

 
 


<script type="text/javascript">
    $(function () {

      /*   $('#app_date_range').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            container: '#app_date_range'
        });

        $('#list_date_range').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            container: '#list_date_range'
        }); */

        $('#case_type,  #subhead, #lp, #section, #da, #judge').multiselect({
            enableFiltering : true,
            enableCaseInsensitiveFiltering : true,
            maxHeight:400
        });

       $('#category').multiselect({
            enableFiltering : true,
            enableCaseInsensitiveFiltering : true,
            enableClickableOptGroups: true,
            //enableCollapsibleOptGroups: true,
            //collapseOptGroupsByDefault: true,
            maxHeight:400
        });


    });

    $(document).ready(function () {
//            $("#sidebar").mCustomScrollbar({
//                theme: "minimal"
//            });



        $('#dismiss, #dismiss2, .overlay').on('click', function () {
            $('#sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });

        $('#sidebarCollapse').on('click', function () {		 
            $('#sidebar').addClass('active');
            $('.overlay').addClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

 

        $(".get_pendency").on("click", function(){
            //e.defaultPrevented;
            var formValues= $("#parent_form").serialize();
            console.log(formValues);
			var CSRF_TOKEN = 'CSRF_TOKEN';
			var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $('.pendency_result_detail').html('');
            $.ajax({
                url: base_url+"/Listing/AdvanceListReport/get_result",
                type:"POST",
                cache:false,
                beforeSend:function(){
                    $('.pendency_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');

                },
                data:formValues+ '&flag=report',
                success:function(data){
					updateCSRFToken();
                    $(".pendency_result").html(data);
                } 				
            }).fail(function() {
				updateCSRFToken();
				$('.pendency_result').html('<p>Error occurred. Please try again later.</p>'); 
			});

        });



    });



/*    $(document).on("change","#sort_by",function() {
        console.log($(this).val());
        $("#get_sort_by").append($(this).val());
    });*/

    $(document).on("click",".diary_nos",function() {
       // e.defaultPrevented;

            $('#sort_by2 option').attr('selected','selected');

        var formValues= $("#child_form").serialize();
        var dnos = $(this).data('dnos');
        var number_of_rows = $("#number_of_rows").val();



        function abc(){
            $.ajax({
                type: "POST",
                url: base_url+"/Listing/AdvanceListReport/get_result",
                beforeSend:function(){
                    $('.pendency_result_detail').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                data:formValues+ '&dnos='+dnos+'&flag=report_detail',
                //data:{dnos:dnos,number_of_rows:number_of_rows,flag:'report_detail'},
                cache: false,
                success: function (data) {
					updateCSRFToken();
                    $(".pendency_result_detail").html(data);
                } 
            }).fail(function() {
				updateCSRFToken();
				$('.pendency_result').html('<p>Error occurred. Please try again later.</p>'); 
			});
        }
       // alert(number_of_rows);
        console.log(formValues);
        if(number_of_rows > 1000) {
            swal({
                title: "Are you sure?",
                text: number_of_rows + " records will take time to fetch. If not required, click on 'No, cancel it!' button",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {

                    abc();


                } else {
                    swal("Cancelled", "Please try again :)", "error");
                }
            })
        }
        else{
            abc();
        }


    });

$('.select2').select2();

</script>
 