$(document).ready(function(){
     $(document).on('click','#btn_submit',function(){
        
         var ddl_party_type=$('#ddl_party_type').val();
        var txt_name=$('#txt_name').val();
        var ddl_diary_caveat=$('#ddl_diary_caveat').val();
        var ddl_status=$('#ddl_status').val();
        var ddl_year=$('#ddl_year').val();
        if(txt_name.trim()=='')
        {
            alert("Please enter text to be searched");
           $('#txt_name').focus();
        }
        else 
        {
        //$(this).attr('disabled',true);
		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

         $.ajax({
            url: base_url+'/Reports/Filing/Filing_Reports/get_diary_search',
           // cache: false,
            //async: true,

  data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,ddl_party_type: ddl_party_type,txt_name:txt_name,ddl_diary_caveat:ddl_diary_caveat,ddl_status:ddl_status,ddl_year:ddl_year},
            beforeSend: function () {
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
				updateCSRFToken();
                $('#div_result').html(data);
                   $('#btn_submit').attr('disabled',false);
                 
               },
            error: function(xhr) {
				updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
    });
    
    $(document).on('click','#btn_left',function(){
         $('#btn_left').attr('disabled',true);
    var ddl_party_type=$('#ddl_party_type').val();
        var txt_name=$('#txt_name').val();
        var ddl_diary_caveat=$('#ddl_diary_caveat').val();
   var ddl_status=$('#ddl_status').val();
        var ddl_year=$('#ddl_year').val();

 var ct_count=parseInt($('#inc_count').val());
                var hd_fst=parseInt($('#hd_fst').val());
//                alert($('#inc_count').val());
                var inc_val=parseInt($('#inc_val').val());
                 var inc_tot=parseInt($('#inc_tot').val());
                 
              var sp_frst=parseInt($('#sp_frst').html())-inc_val;
                  var inc_tot_pg=sp_frst;
//               alert(inc_tot_pg);
               if($('#btn_right').is(':disabled'))
                   {
                       $('#btn_right').attr('disabled',false);
                   }
//                if(hd_fst==0)
//                    {
//                     $('#btn_left').attr('disabled',false);
//                   
//                    }
                 var nw_hd_fst=hd_fst-inc_val;
                $('#inc_count').val(ct_count-1);
                if($('#inc_count').val()==1)
                    {
                        $('#btn_left').attr('disabled',true);
                    }
					var CSRF_TOKEN = 'CSRF_TOKEN';
					var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                            url:base_url+'/Reports/Filing/Filing_Reports/include_diary_search',
                            type:"GET",
                            cache:false,
                            async:true,
                            beforeSend:function(){
                               
                                $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                            },
                            data:{CSRF_TOKEN: CSRF_TOKEN_VALUE,nw_hd_fst:nw_hd_fst,inc_val:inc_val,u_t:1,inc_tot_pg:inc_tot_pg,
                    ddl_party_type: ddl_party_type,txt_name:txt_name,ddl_diary_caveat:ddl_diary_caveat,ddl_status:ddl_status,ddl_year:ddl_year
               },
                            success:function(data,status){
                                  
								updateCSRFToken();                          
                                 $('#dv_include').html(data);
                           
//                           alert( $('#inc_count').val());
                             $('#hd_fst').val(nw_hd_fst);
                             $('#sp_last').html(parseInt($('#sp_frst').html())-1);
                             $('#sp_frst').html(parseInt($('#sp_frst').html())-inc_val);
                             
                              if(sp_frst==1)
                                   $('#btn_left').attr('disabled',true);
                               else 
                                    $('#btn_left').attr('disabled',false);
                            },
                            error:function(xhr){
								updateCSRFToken();
                                alert("Error: "+xhr.status+' '+xhr.statusText);
                            }
                        });
        });
        
      $(document).on('click','#btn_right',function(){     
//    $('#btn_right').click(function(){
           
          $('#btn_right').attr('disabled',true);
          
            var ddl_party_type=$('#ddl_party_type').val();
        var txt_name=$('#txt_name').val();
        var ddl_diary_caveat=$('#ddl_diary_caveat').val();
             var ddl_status=$('#ddl_status').val();
        var ddl_year=$('#ddl_year').val();
         
          
        var ct_count=parseInt($('#inc_count').val());
                var hd_fst=parseInt($('#hd_fst').val());
                var inc_val=parseInt($('#inc_val').val());
               
                 var inc_tot=parseInt($('#inc_tot').val());
                 var inc_tot_pg=parseInt($('#inc_tot_pg').val());
//              alert(inc_tot_pg);
                if(hd_fst==0)
                    {
                     $('#btn_left').attr('disabled',false);
                   
                    }
                 var nw_hd_fst=hd_fst+inc_val;
//                 alert(ct_count);
//                  alert(inc_val);
//   alert(ct_count+'@@'+hd_fst+'@@'+inc_val+'@@'+inc_tot+'@@'+inc_tot_pg+'@@'+nw_hd_fst);
                if(ct_count==inc_tot-1)
                    {
                        $('#btn_right').attr('disabled',true);
                    }
					var CSRF_TOKEN = 'CSRF_TOKEN';
					var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                    $.ajax({
                            url: base_url+'/Reports/Filing/Filing_Reports/include_diary_search',
                            type:"GET",
                            cache:false,
                            async:true,
                            beforeSend:function(){
                               
                                $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                            },
                            data:{CSRF_TOKEN: CSRF_TOKEN_VALUE,nw_hd_fst:nw_hd_fst,inc_val:inc_val,u_t:1,inc_tot_pg:inc_tot_pg,
                    ddl_party_type: ddl_party_type,txt_name:txt_name,ddl_diary_caveat:ddl_diary_caveat,ddl_status:ddl_status,ddl_year:ddl_year
               },
                            success:function(data,status){
                                  updateCSRFToken();
//                             alert(data);
                          
                                 $('#dv_include').html(data);
//                                 alert($('dv_include').html());
                           $('#inc_count').val(ct_count+1);
//                           alert( $('#inc_count').val());
                             $('#hd_fst').val(nw_hd_fst);
                            
                             $('#sp_frst').html(parseInt($('#sp_last').html())+1);
                         var sp_last_ck= parseInt($('#sp_last').html())+inc_val;
                         var sp_nf = parseInt($('#sp_nf').html());
//                         alert(sp_last_ck+'$$'+sp_nf);
                         if(sp_last_ck<=sp_nf)
                         {
                          $('#sp_last').html(parseInt($('#sp_last').html())+inc_val);
                            $('#btn_right').attr('disabled',false);
                                }
                          else
                              {
//                                  $('#sp_last').html('');
$('#sp_last').html(sp_nf);
  $('#btn_right').attr('disabled',true);
                              }
                               
                            },
                            error:function(xhr){
								updateCSRFToken();
                                alert("Error: "+xhr.status+' '+xhr.statusText);
                            }
                        });
             
               
        });
    
});