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
<link href="<?php echo base_url();?>/css/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>/filing/add_extra_cav_adv.js"></script>
<style type="text/css">
	#sp_amo
	{
		cursor: pointer;
		color: blue;
	}
	#sp_amo:hover
	{
		text-decoration: underline
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
                                    <h3 class="card-title">Filing >> Extra Advocate >> Add</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						
						<form method="post" action="<?= site_url(uri_string()) ?>">
							<?= csrf_field() ?>
						  <div id="dv_content1"   >
						<div style="text-align: center">
						   <table align="center" >
									<tr >
									   <td><b>Caveat No.</b>
										   
											<input type="text" class="form-control" id="dno" size="4" value="<?php echo $_SESSION['caveat_d_no'] ?? ''; ?>"/>
									  
										</td>
										<td><b>Caveat Year</b> 
										   
											<!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
									<?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y'); 
			   print '<select id="dyr" style="width:100% !important" class="form-control">';   foreach ( range( $latest_year, $earliest_year ) as $i ) {
			   print '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';   }
			   print '</select>'; ?>                      
										</td>
										<td style="vertical-align: bottom;">
											<input type="button" name="btnGetAdv" class="mt-3" value="GET DETAILS"  /></th>
										</td>

									</tr>
									
								</table>
						   
						</div>
						  <div id="result1">
        
							</div>
							<div id="result2" style="text-align: center;color:green;font-size: larger"></div>
						
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

/*
$(document).on("click","input[name=setPageBtn]",function(){
    setPage();
});

var p_adv_total=document.getElementById('p_adv_total').value;
var r_adv_total=document.getElementById('r_adv_total').value;
if(p_adv_total!=0 || r_adv_total!=0)
{            
	setPage();
}
 */
function setPage_()
{
    
    var p_ttl = parseInt(document.getElementById('p_adv_total').value);
    var r_ttl = parseInt(document.getElementById('r_adv_total').value);
    var i_ttl = '';
    var n_ttl = '';
    if(document.getElementById('i_adv_total'))
        i_ttl = parseInt(document.getElementById('i_adv_total').value);
    else
        i_ttl = parseInt('0');
    
    if(document.getElementById('n_adv_total'))
        n_ttl = parseInt(document.getElementById('n_adv_total').value);
    else
        n_ttl = parseInt('0');
    //alert(i_ttl);
    
    if(p_ttl==0 && r_ttl==0 && i_ttl==0 && n_ttl==0)
        return false;
    
    if((p_ttl===''||p_ttl>10)||(r_ttl===''||r_ttl>10)||(i_ttl===''||i_ttl>10)||(n_ttl===''||n_ttl>10))
    {
        //alert("("+p_ttl+"==''||"+p_ttl+">10)||("+r_ttl+"==''||"+r_ttl+">10)||("+i_ttl+"==''||"+i_ttl+">10)");
        alert('No. of Advocate(s) could not be blank or greater than 10');
        if(p_ttl==''||p_ttl>10)
            document.getElementById('p_adv_total').focus();
        else if(r_ttl==''||r_ttl>10)
            document.getElementById('r_adv_total').focus();
        
        /*if(document.getElementById('i_adv_total')){
            if(i_ttl==''||i_ttl>10)
                document.getElementById('i_adv_total').focus();
        }*/
        
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url:base_url+"Filing/Caveat/cav_adv_fetch_parties",
        beforeSend: function (xhr) {
            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data:{filno:$("#fil_hd").val(),pt:p_ttl,rt:r_ttl,it:i_ttl,nt:n_ttl}
    })
    .done(function(msg){
        $("#result1").html(msg);
        $("#result2").html("");
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room"); 
    });
}   
</script>