<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
 <style>
    #reportTable1_filter{
    padding-right: 84%
}
       
.dataTables_filter{
    display: table;
}
div.dt-buttons {
    margin-bottom: -38px;
    margin-right: -80%;
}
div.dataTables_wrapper {
    position: relative;
    margin-top: 24px;
}

.dataTables_info{
    margin-top: 34px;
}
#grid_paginate{
    margin-top: 25px;
}

thead{
    color: rgb(169, 68, 66);
}

.error-message{
    color:red;
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
                        <h3 class="card-title">Master Management >> Master</h3>
                    </div>
                    <div class="col-sm-2"> </div>
                </div>
            </div>
            <br /><br />
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12"> <!-- Right Part -->
                            <div class="form-div">
                                <div class="d-block text-center">


                                     <!-- Main content -->  
                                     <div class="container mt-5">
                                            <h2 class="text-center" style="margin-bottom: revert;">Law Firm Add</h2>
                                            <form method="post" class="mt-5">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="law_firm_id">Firm</label>
                                                        <select id="law_firm_id" name="law_firm_id" class="form-control">
                                                            <option value="">--Select Option--</option>
                                                            <?php foreach ($get_law_firm as $law_firm): ?>
                                                                <option value="<?= $law_firm['law_id'] ?>"><?= $law_firm['law_firm_name'] ?></option>
                                                            <?php endforeach; ?>                                                       
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="enroll_no">Enroll No</label>
                                                        <input type="text" id="enroll_no" name="enroll_no" class="form-control" maxlength="10">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="enroll_yr">Year</label>
                                                        <input type="number" id="enroll_yr" name="enroll_yr" onblur="get_adv_by_enroll_no();" class="form-control" maxlength="4">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="state_id">State</label>
                                                        <select id="state_id" name="state_id" class="form-control">
                                                            <option value="">--Select Option--</option>
                                                            <?php foreach ($get_state as $state): ?>
                                                                    <option value="<?= $state['id_no'] ?>"><?= $state['name'] ?></option>
                                                                <?php endforeach; ?>
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="from_date">From Date</label>
                                                        <input type="text" id="from_date" name="from_date" onBlur="valid_date(this.id);" class="form-control dtp" maxlength="10">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="to_date">To Date</label>
                                                        <input type="text" id="to_date" name="to_date" onBlur="valid_date(this.id);" class="form-control dtp" maxlength="10">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label>&nbsp;</label>
                                                        <input type='button' id='cmd_save' class="btn btn-primary btn-sm" value='Save' onClick="save_law_firm()" >
                                                    </div>
                                                </div>
                                            </form>

                                            <div id="result1">
                                              
                                                </div>
                                                <br>
                                                <div id="result2" style="text-align: center;color:green;font-size: larger"></div>
                                                Action Result
                                        </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<!-- <script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript">
     
     var dateToday = new Date();
$(document).on("focus", ".dtp", function () {
    $('.dtp').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050',
	 });
});

   function getXMLHTTP()
    { //fuction to return the xml http object
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e1) {
                    xmlhttp = false;
                }
            }
        }
        return xmlhttp;
    }



function check_valid(){
	if(document.getElementById('law_firm_id').value=='')
	{
		alert("Please select Law Firm");
		document.getElementById('law_firm_id').focus();
		return false;
	}
	
	if(document.getElementById('enroll_no').value=='')
	{
		alert("Please Enter enroll no");
		document.getElementById('enroll_no').focus();
		return false;
	}
	
	if(document.getElementById('enroll_yr').value=='')
	{
		alert("Please Enter enroll year");
		document.getElementById('enroll_yr').focus();
		return false;
	}
	
	if(document.getElementById('state_id').value=='')
	{
		alert("Please select State");
		document.getElementById('state_id').focus();
		return false;
	}
	
	//check_date();
}	

   
function valid_num(){
var txtyear=document.getElementById('enroll_yr');
var rgx = /^[0-9]+$/;
    if(txtyear.value !="" )
    {        
        if(!txtyear.value.match(rgx)){
            alert('Please enter year in numeric only'); 
            return false;
			
        }
    }
}
/////// date validation ///////
function valid_date(x) {
    var txtdate = document.getElementById(x);
    var rgx = /^(((0[1-9]|[12]\d|3[01])\-(0[13578]|1[02])\-((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\-(0[13456789]|1[012])\-((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\-02\-((19|[2-9]\d)\d{2}))|(29\-02\-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

    // Check if the input value is not empty
    if (txtdate.value !== "") {        
        if (!txtdate.value.match(rgx)) {
            document.getElementById('cmd_save').disabled = true;	
            alert("Please enter a valid date.");
            $('#from_date').val('');
            $('#to_date').val('');
            setTimeout(function() {
                txtdate.focus();
            }, 0);

            return false;
        } else {
            document.getElementById('cmd_save').disabled = false;
        }
    }
}


function check_date()
{	
	if(document.getElementById('from_date').value=='')
	{
		alert("Please enter from date ");
		document.getElementById('from_date').focus();
		return false;
	}
	
	if(document.getElementById('to_date').value=='')
	{
		alert("Please enter to date ");
		document.getElementById('to_date').focus();
		return false;
	}

	
var d1=document.getElementById('from_date').value.split("-"); 
var d2=document.getElementById('to_date').value.split("-"); 
var from=d1[2]+'-'+d1[1]+'-'+d1[0];//YYYY-MM-DD
var to=d2[2]+'-'+d2[1]+'-'+d2[0];//YYYY-MM-DD
	if( (new Date(from).getTime() > new Date(to).getTime()))
	{
   	alert ("from date should not be greater than to date");
	from_date.focus();
	return false;
	}
}	


function get_adv_by_enroll_no() {
    var xhr1 = getXMLHTTP();
    var url = "<?= base_url(); ?>/MasterManagement/MasterController/get_adv_by_enroll_no?enroll_no=" + document.getElementById('enroll_no').value + "&enroll_yr=" + document.getElementById('enroll_yr').value;

    xhr1.open("GET", url, true);
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState == 4) {
            if (xhr1.status == 200) {
                var data = xhr1.responseText; 
                var aa = data.split('||'); 

                if (aa[0] == 1) {
                    document.getElementById('result1').innerHTML = aa[1];
                    document.getElementById('cmd_save').disabled = false;    
                } else if (aa[0] == 0) {
                    document.getElementById('result1').innerHTML = aa[1];
                    document.getElementById('cmd_save').disabled = true;    
                }
            } else {
                console.error("Error: HTTP status " + xhr1.status); 
            }
        }
    };
    xhr1.send(null);
}



function save_law_firm(){

//check_valid();
/////////start check valid //////////////////
	if(document.getElementById('law_firm_id').value=='')
	{
		alert("Please select Law Firm");
		document.getElementById('law_firm_id').focus();
		return false;
	}
	
	if(document.getElementById('enroll_no').value=='')
	{
		alert("Please Enter enroll no");
		document.getElementById('enroll_no').focus();
		return false;
	}
	
	if(document.getElementById('enroll_yr').value=='')
	{
		alert("Please Enter enroll year");
		document.getElementById('enroll_yr').focus();
		return false;
	}
	
	if(document.getElementById('state_id').value=='')
	{
		alert("Please select State");
		document.getElementById('state_id').focus();
		return false;
	}
	
/////////end check valid //////////////////

//////////////start check date/////////////////////////////////////
	if(document.getElementById('from_date').value=='')
	{
		alert("Please enter from date ");
		document.getElementById('from_date').focus();
		return false;
	}
	
	if(document.getElementById('to_date').value=='')
	{
		alert("Please enter to date ");
		document.getElementById('to_date').focus();
		return false;
	}

	
var d1=document.getElementById('from_date').value.split("-"); 
var d2=document.getElementById('to_date').value.split("-"); 
var from=d1[2]+'-'+d1[1]+'-'+d1[0];//YYYY-MM-DD
var to=d2[2]+'-'+d2[1]+'-'+d2[0];//YYYY-MM-DD
	if( (new Date(from).getTime() > new Date(to).getTime()))
	{
   	alert ("from date should not be greater than to date");
	from_date.focus();
	return false;
	}

$('#whole_page_loader').show();
var xhr4=getXMLHTTP();
document.getElementById('result2').innerHTML='Processing...';
var str="<?= base_url(); ?>/MasterManagement/MasterController/lawfirmaddprocess?law_firm_id="+document.getElementById('law_firm_id').value
+"&enroll_no="+document.getElementById('enroll_no').value
+"&enroll_yr="+document.getElementById('enroll_yr').value
+"&state_id="+document.getElementById('state_id').value
+"&from_date="+document.getElementById('from_date').value
+"&to_date="+document.getElementById('to_date').value;
	 alert(str);
	 xhr4.open("GET",str,true);
		xhr4.onreadystatechange=function()
		{	
			if(xhr4.readyState==4  && xhr4.status==200)
			{	
				var data=xhr4.responseText; 
				alert(data);
                $('#whole_page_loader').hide();
				document.getElementById('result2').innerHTML=data;

				
			}
      }		
	   xhr4.send(null);	
       $('#whole_page_loader').hide();	
   
}	

         
</script>
