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
                                    <h3 class="card-title">Copying / Report</h3>
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
                                        <li class="nav-item"><a onclick="get_search_view(1)" class="nav-link <?php if($type=="1"){ echo 'active';}?>" href="#AOR_signature_report" data-toggle="tab">AOR signature report</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(2)" class="nav-link <?php if($type=="2"){ echo 'active';}?>" href="#Consumed_Barcodes" id="consumed_barcodes_search_click" data-toggle="tab">Consumed Barcodes</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(3)" class="nav-link <?php if($type=='3'){ echo 'active';}?>" href="#CopyingRequest" id="copying_request_click" data-toggle="tab">Copying Request</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(4)" class="nav-link <?php if($type=='4'){ echo 'active';}?>" href="#DA_Wise" id="DA_wise_search_click" data-toggle="tab">DA Wise</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(5)" class="nav-link <?php if($type=='5'){ echo 'active';}?>" href="#EcopyStats" id="ecopy_stats_search_click" data-toggle="tab">E-Copy Stats</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(6)" class="nav-link <?php if($type=='6'){ echo 'active';}?>" href="#ePay" id="epay_search_click" data-toggle="tab">ePay</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(7)" class="nav-link <?php if($type=='7'){ echo 'active';}?>" href="#File_Request" id="file_request_search_click" data-toggle="tab">File Request</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(8)" class="nav-link <?php if($type=='8'){ echo 'active';}?>" href="#Received by R&amp;I" id="received_by_r_and_i_search_search_click" data-toggle="tab">Received by R&amp;I</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(9)" class="nav-link <?php if($type=='9'){ echo 'active';}?>" href="#Search_by_Diary_Number" id="search_by_diary_number_search_click" data-toggle="tab">Search by Diary Number</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(10)" class="nav-link <?php if($type=='10'){ echo 'active';}?>" href="#Userwise_Report" id="userwise_report_search_click" data-toggle="tab">Userwise Report</a></li>
                                        <li class="nav-item"><a onclick="get_search_view(11)" class="nav-link <?php if($type=='11'){ echo 'active';}?>" href="#View" id="view_search_click" data-toggle="tab">View</a></li>
                                    </ul>
                                </div>
							</div>	
						<div class="card-body">
							<div class="tab-content">
								<div id="load_search_view"> </div>
							</div>
						</div>
					</div>
				</div>	
			</div>	
		</div>	
	 </div>	
  </section>
<script>

        get_search_view('<?=$type?>');

        function get_search_view(type) {
            $.ajax({
                type: "GET",
                data: { type: type},
                url: "<?php echo base_url('Reports/Copying/Report/get_search_view'); ?>",
				beforeSend: function() {
					$('#load_search_view').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
                success: function (data)
                {
                    $('#load_search_view').html(data);
				}
                
            });

        }



    </script>
