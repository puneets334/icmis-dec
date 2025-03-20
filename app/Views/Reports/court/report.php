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
                                    <h3 class="card-title">Court / Report</h3>
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
                                        <li class="nav-item"><a onclick="get_search_view(1)" class="nav-link active" href="#Paperless" data-toggle="tab">Paperless Court</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(2)" class="nav-link <?php if(($uri->getSegment(4))=="part_heard_search"){ echo 'active';}?>" href="#Part_Heard" id="part_heard_search_click" data-toggle="tab">Part Heard</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(3)" class="nav-link <?php if(($uri->getSegment(4))=='daily_disposal_search'){ echo 'active';}?>" href="#DDR" id="daily_disposal_search_click" data-toggle="tab">Daily Disposal Remarks</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(4)" class="nav-link <?php if(($uri->getSegment(4))=='gist_module_search'){ echo 'active';}?>" href="#Gist Module" id="gist_module_search_click" data-toggle="tab">Gist Module</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(12)" class="nav-link <?php if(($uri->getSegment(4))=='cav_module_search'){ echo 'active';}?>" href="#CAV Module" id="cav_module_search_click" data-toggle="tab">CAV</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(5)" class="nav-link <?php if(($uri->getSegment(4))=='matters_disposed_through_mentioning_search'){ echo 'active';}?>" href="#Matters_Disposed_through_Mentioning" id="matters_disposed_through_mentioning_click" data-toggle="tab">Matters Disposed through Mentioning</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(6)" class="nav-link <?php if(($uri->getSegment(4))=='final_disposal_matters_search'){ echo 'active';}?>" href="#Final_Disposal_Matters" id="final_disposal_matters_search_click" data-toggle="tab">Final Disposal Matters</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(7)" class="nav-link <?php if(($uri->getSegment(4))=='fixed_date_matters_search'){ echo 'active';}?>" href="#Fixed_Date_Matters" id="fixed_date_matters_search_click" data-toggle="tab">Fixed Date Matters</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(8)" class="nav-link <?php if(($uri->getSegment(4))=='cause_list_with_OR_search'){ echo 'active';}?>" href="#Cause_List_With_OR" id="cause_list_with_OR_click" data-toggle="tab">Cause List With OR</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(9)" class="nav-link <?php if(($uri->getSegment(4))=='appearance_search'){ echo 'active';}?>" href="#Appearance" id="appearance_search_click" data-toggle="tab">Appearance</a></li>
<!--                                        <li class="nav-item"><a onclick="get_search_view(10)" class="nav-link --><?php //if(($uri->getSegment(4))=='upload_search'){ echo 'active';}?><!--" href="#Upload" id="upload_search_click" data-toggle="tab">Upload</a></li>-->
                                        <li class="nav-item"><a onclick="get_search_view(11)" class="nav-link <?php if(($uri->getSegment(4))=='vernacular_judgments_report'){ echo 'active';}?>" href="#vernacular_judgments_report" id="vernacular_judgments_report_search_click" data-toggle="tab">Vernacular Judgments Report</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(13)" class="nav-link <?php if(($uri->getSegment(4))=='rop_not_uploaded'){ echo 'active';}?>" href="#rop_not_uploaded" id="rop_not_uploaded_search_click" data-toggle="tab">ROP Not Uploaded</a></li>

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

        get_search_view(1);

        function get_search_view(type) {
            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "GET",
                data: { type: type},
                url: "<?php echo base_url('Reports/Court/Report/get_search_view'); ?>",
                success: function (data)
                {
                    $('#load_search_view').html('');
                    $('#load_search_view').html(data);

                  //  updateCSRFToken();
                }
                // error: function () {
                //     updateCSRFToken();
                // }
            });

        }



    </script>



 <?//=view('sci_main_footer') ?>