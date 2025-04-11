$(document).ready(function(){
     $(document).on('click','#btn_submit',function(){
		 var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

         var txt_frm_date=$("#txt_frm_date").val();
           var txt_to_date=$("#txt_to_date").val();
           var txt_aor_code=$('#txt_aor_code').val();
           var ddl_judge=$('#ddl_judge').val();
//           alert(ddl_judge);
            $.ajax({
            url: base_url+ '/Listing/Report/get_causelist_or_rop',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,txt_frm_date: txt_frm_date, txt_to_date: txt_to_date,txt_aor_code:txt_aor_code,ddl_judge:ddl_judge},
            beforeSend: function() {
                $('#dv_f_t_dates').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
				updateCSRFToken();
                $('#dv_f_t_dates').html(data);
            },
            error: function(xhr) {
				updateCSRFToken();
				$('#dv_f_t_dates').html('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
        updateCSRFToken();
     });
     $(document).on('click','.cl_off_rop',function(){
        var idd=$(this).attr('id');
//        alert(idd);
        var str_idd=idd.split('.');
      
         
                  
    
        if(str_idd[1]=='html')
        {
        document.getElementById('ggg1').scrollTop=0;
   document.getElementById('ggg1').style.width='auto';
    document.getElementById('ggg1').style.height=' 550px';
    document.getElementById('ggg1').style.overflow='hidden'; 
  
    document.getElementById('ggg1').style.marginLeft ='40px';
    document.getElementById('ggg1').style.marginRight ='50px';
    document.getElementById('ggg1').style.marginBottom ='25px';
    document.getElementById('ggg1').style.marginTop ='20px';
    document.getElementById('ggg1').style.overflow='scroll';
      document.getElementById('dv_sh_hd1').style.display='block';
    document.getElementById('dv_fixedFor_P1').style.display='block';
    document.getElementById('dv_fixedFor_P1').style.marginTop='3px';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
     $.ajax({
            //url: 'get_text_file.php',
            url: base_url+ '/Listing/Report/get_text_file',
            cache: false,
            async: true,
            data: {idd: idd, CSRF_TOKEN: CSRF_TOKEN_VALUE},
            beforeSend: function() {
                //$('#ggg1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $('#ggg1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {

                updateCSRFToken();
                $('#ggg1').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
    else if(str_idd[1]=='pdf')
    {
             document.getElementById('ggg').scrollTop=0;
   document.getElementById('ggg').style.width='auto';
    document.getElementById('ggg').style.height=' 550px';
    document.getElementById('ggg').style.overflow='hidden'; 
  
    document.getElementById('ggg').style.marginLeft ='18px';
    document.getElementById('ggg').style.marginRight ='18px';
    document.getElementById('ggg').style.marginBottom ='25px';
    document.getElementById('ggg').style.marginTop ='20px';
    document.getElementById('ggg').style.overflow='scroll';
      document.getElementById('dv_sh_hd').style.display='block';
    document.getElementById('dv_fixedFor_P').style.display='block';
    document.getElementById('dv_fixedFor_P').style.marginTop='3px';
//             idd="../officereport/"+idd;
 idd="../"+idd;
//             alert(idd);
             document.getElementById('ob_shw').setAttribute('data',idd);
    }
    
     });
});
function closeData()
{
    document.getElementById('ggg').scrollTop=0; 
  
    document.getElementById('dv_fixedFor_P').style.display="none";
      document.getElementById('dv_sh_hd').style.display="none";
} 
function closeData1()
{
    document.getElementById('ggg1').scrollTop=0; 
  
    document.getElementById('dv_fixedFor_P1').style.display="none";
      document.getElementById('dv_sh_hd1').style.display="none";
} 

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            alert("Please enter only numbers.");
            return false;
        }
        return true;
  }