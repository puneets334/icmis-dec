<?php  $uri = current_url(true); ?>
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
                                    <h3 class="card-title">Judicial / Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                <?php if(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= session()->getFlashdata('error')?>
                                    </div>
                                <?php } else if(session("message_error")){ ?>
                                    <div class="alert alert-danger text-danger" style="color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?=session("message_error")?>
                                    </div>
                                <?php }else{?>

                                <?php }?>
                            </div>
                            <div class="col-md-12">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a onclick="get_search_view(1)" class="nav-link <?php if($active_status =="Elimination_list"){ echo 'active';}?>" href="#Elimination_list" id="Elimination List" data-toggle="tab">Elimination List</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(3)" class="nav-link <?php if($active_status == 'Section_Wise_list'){ echo 'active';}?>" href="#DDR" id="daily_disposal_search_click" data-toggle="tab">Section-Wise</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(4)" class="nav-link <?php if($active_status == 'Weekly_Wise_list'){ echo 'active';}?>" href="#Gist Module" id="gist_module_search_click" data-toggle="tab">Weekly Section List</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(5)" class="nav-link <?php if($active_status == 'Sec_list'){ echo 'active';}?>" href="#Matters_Disposed_through_Mentioning" id="matters_disposed_through_mentioning_click" data-toggle="tab">Sec List</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(6)" class="nav-link <?php if($active_status == 'Vac_list'){ echo 'active';}?>" href="#Final_Disposal_Matters" id="final_disposal_matters_search_click" data-toggle="tab">Vacation Registrar List</a></li>
                                        <!-- <li class="nav-item"><a onclick="get_search_view(7)" class="nav-link <?php if(($uri->getSegment(4))=='fixed_date_matters_search'){ echo 'active';}?>" href="#Fixed_Date_Matters" id="fixed_date_matters_search_click" data-toggle="tab">Daily Remarks</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(8)" class="nav-link <?php if(($uri->getSegment(4))=='cause_list_with_OR_search'){ echo 'active';}?>" href="#Cause_List_With_OR" id="cause_list_with_OR_click" data-toggle="tab">Pending Copying Requests</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(9)" class="nav-link <?php if(($uri->getSegment(4))=='appearance_search'){ echo 'active';}?>" href="#Appearance" id="appearance_search_click" data-toggle="tab">Loose Document</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(10)" class="nav-link <?php if(($uri->getSegment(4))=='upload_search'){ echo 'active';}?>" href="#Upload" id="upload_search_click" data-toggle="tab">Work Done</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(11)" class="nav-link <?php if(($uri->getSegment(4))=='vernacular_judgments_report'){ echo 'active';}?>" href="#vernacular_judgments_report" id="vernacular_judgments_report_search_click" data-toggle="tab">ROGY DAWise</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(12)" class="nav-link <?php if(($uri->getSegment(4))=='final_disposal_matters_search'){ echo 'active';}?>" href="#Final_Disposal_Matters" id="final_disposal_matters_search_click" data-toggle="tab">ROGY Complete</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(13)" class="nav-link <?php if(($uri->getSegment(4))=='fixed_date_matters_search'){ echo 'active';}?>" href="#Fixed_Date_Matters" id="fixed_date_matters_search_click" data-toggle="tab">AOR wise matters</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(14)" class="nav-link <?php if(($uri->getSegment(4))=='cause_list_with_OR_search'){ echo 'active';}?>" href="#Cause_List_With_OR" id="cause_list_with_OR_click" data-toggle="tab">OR_Uploaded</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(15)" class="nav-link <?php if(($uri->getSegment(4))=='appearance_search'){ echo 'active';}?>" href="#Appearance" id="appearance_search_click" data-toggle="tab">Advance List</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(16)" class="nav-link <?php if(($uri->getSegment(4))=='upload_search'){ echo 'active';}?>" href="#Upload" id="upload_search_click" data-toggle="tab">ROP Verification</a></li> -->

                                    </ul>
                                </div>
        <div class="card-body">
        <div class="tab-content">
            <div id="load_search_view"> </div>

   </div>
    <!-- /.row -->
  </div>
    <!-- /.container-fluid -->
  </section>
    <!-- /.content -->

    <script>
		
		<?php if($active_status == 'Elimination_list'){?>
			get_search_view(1);		
		<?php }elseif($active_status == 'Section_Wise_list'){?>
			get_search_view(3);
		<?php }elseif($active_status == 'Weekly_Wise_list'){?>
			get_search_view(4);
		<?php }elseif($active_status == 'Sec_list'){?>	
			get_search_view(5);
		<?php }elseif($active_status == 'Vac_list'){?>	
			get_search_view(6);
		<?php }?>

        function get_search_view(type) {
            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "GET",
                data: { type: type},
                url: "<?php echo base_url('Reports/Judicial/Report/get_search_view'); ?>",
                success: function (data)
                {
                    $('#load_search_view').html(data);
                  //  updateCSRFToken();
                }
                // error: function () {
                //     updateCSRFToken();
                // }
            });

        }



    </script>