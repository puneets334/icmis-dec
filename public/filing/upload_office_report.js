$(document).ready(function() {
    $(document).on('click', '#sub', function() {
        get_report();
    });
    
  
    $("#radiodn").click(function(){
        $("#t_h_cno").removeProp('disabled');
        $("#t_h_cyt").removeProp('disabled');
        $("#selct").prop('disabled',true);
        $("#case_no").prop('disabled',true);
        $("#case_yr").prop('disabled',true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
        $('#div_result').empty();
    });
    
    $("#radioct").click(function(){
        $("#t_h_cno").prop('disabled',true);
        $("#t_h_cyt").prop('disabled',true);
        $("#t_h_cno").val("");
        $("#t_h_cyt").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
        $('#div_result').empty();
    });


    $(document).on('change','#case_yr',function(){
        var diaryno, diaryyear, cstype, csno, csyr;
        var cstype = $("#selct").val();
       var  csno = $("#case_no").val();
        var csyr = $("#case_yr").val();
        $.ajax({
            url: 'get_cause_title.php',
            cache: false,
            async: true,
            data:{d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr},

            type: 'POST',
            success: function(data, status) {

                $('#div_result').html(data);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
    $(document).on('change','#t_h_cyt',function(){
        var t_h_cno=$('#t_h_cno').val();
        var t_h_cyt=$(this).val();
        $.ajax({
            url: 'get_cause_title.php',
            cache: false,
            async: true,
            data: {d_no: t_h_cno, d_yr: t_h_cyt},

            type: 'POST',
            success: function(data, status) {

                $('#div_result').html(data);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
     });
     
      function  get_report()
     {
           
          var chk_status=0;      
          var cstype,csno,csyr,t_h_cno,t_h_cyt;
          var regNum = new RegExp('^[0-9]+$');
        if($("#radioct").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();
            chk_status=1;
            if(!regNum.test(cstype)){
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if(!regNum.test(csno)){
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if(!regNum.test(csyr)){
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if(csno == 0){
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(csyr == 0){
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        }
        else if($("#radiodn").is(':checked')){
           
     var t_h_cno=$('#t_h_cno').val();
   var t_h_cyt=$('#t_h_cyt').val();
     chk_status=2;
   if(t_h_cno.trim()=='')
   {
       alert("Please enter Diary No.");
       $('#t_h_cno').focus();
       return false;
   }
   if(t_h_cyt.trim()=='')
   {
       alert("Please enter Diary Year");
       $('#t_h_cyt').focus();
       return false;
   }
    var fno = t_h_cno+t_h_cyt;
        }
   
     var upd_file=   $('#upd_file').val();
   if(upd_file=='')
   {
       alert("Please select pdf file to upload");
       return false;
    }
           var isValid = /\.pdf$/i.test(document.getElementById('upd_file').value);
   var upd_file= document.getElementById('upd_file').value;

     if (!isValid && $('#upd_file').val() != '')
    {
        alert('Only pdf files allowed');
        document.getElementById('upd_file').focus();

    }

    else
    {
       
     
          
        var data = new FormData();
        data.append('file', document.getElementById('upd_file').files[0]);
var ddl_ord_date=$('#ddl_ord_date').val();
if(ddl_ord_date=='' || ddl_ord_date.length<10)
{
    alert("Please enter order date");
    $('#ddl_ord_date').focus();
    return false;
}
        data.append('ct', cstype );
        data.append('cn', csno);
        data.append('cy', csyr);
        data.append('fno', fno);
        
         data.append('d_no', t_h_cno);
        data.append('d_yr', t_h_cyt);
         data.append('ddl_ord_date', ddl_ord_date);
          data.append('upd_file', upd_file);
           data.append('chk_status', chk_status);
        var summary=$('#summary').val();
        data.append('summary', summary);
		
		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
	
		data.append(CSRF_TOKEN, CSRF_TOKEN_VALUE);
		
        $.ajax({
            url: base_url+'/Extension/OfficeReport/get_upload_office_report',
           // cache: false,
            //async: true,
            beforeSend: function() {
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            processData: false,
            contentType: false,
            data: data,
            enctype: 'multipart/form-data',
            type: 'POST',
            success: function(data, status) {
				updateCSRFToken();
               $('#div_result').html(data); 
				$('#upd_file').val('');
              },
            error: function(xhr) {
				updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
     }
 }

