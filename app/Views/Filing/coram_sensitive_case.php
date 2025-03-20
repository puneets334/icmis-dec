<?=view('header'); ?>
 
<style>
    .custom-radio{float: left; display: inline-block; margin-left: 10px; }
    .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
    .basic_heading{text-align: center;color: #31B0D5}
    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }
    .card-header {
        padding: 5px;
    }
    h4 {
        line-height: 0px;
    }
</style>
<style>
                fieldset{
                   padding:5px; background-color:#F5FAFF; border:1px solid #0083FF; 
                }
                legend{
                    background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF; font-weight: bold;
                }
                .table3, .subct2, .subct3, .subct4, #res_on_off, #resh_from_txt{
                    display:none;
                }
                .toggle_btn{
                    text-align: left; color: #00cc99; font-size:18px; font-weight: bold; cursor: pointer;
                }
                div, table, tr, td{
                    font-size:14px;
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
                                    <h3 class="card-title">Filing >> Coram >> Sensitive Cases</h3>
                                </div>

                                <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						<form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>
							<br>
							<div id="dv_content1"   >
								<div class="cl_center text-center">
									<input type="button" name="btn_sensetive" id="btn_sensetive" value="Submit"/>
								</div>
								</br>
								 <div id="div_result"></div>
							</div>
							 
						</form>
						
					
					
					
					  </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
			</div>
    </section>   
	
	<script>
	$(document).ready(function() {
    $(document).on('click', '#btn_sensetive', function() {
        get_report();
    });
  $(document).on("click","#prnnt1",function(){
            var prtContent = $("#prnnt").html();            
            var temp_str=prtContent;
            var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        });   
    });
    
    function get_report()
    {
		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: base_url+'/Filing/Coram/get_sensitive_cases',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            beforeSend: function() {
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
				updateCSRFToken();
                $('#div_result').html(data);
             
            },
            error: function(xhr) {
				updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
	</script>