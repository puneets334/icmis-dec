$(document).ready(function(){
       $(document).on('click','.cl_add_cst',function(){
        var idd=$(this).attr('id');
        var sp_idd=idd.split('_');
        var o_r_h=sp_idd[1];
        var hd_Sendcopyto_o=$('#hd_Sendcopyto_'+sp_idd[1]+sp_idd[2]).val();
       
        var ddlSendCopyTo_o=$('#ddlSendCopyTo_'+sp_idd[1]+sp_idd[2]).val();
        var ddlSendCopyTo_o_id=$('#ddlSendCopyTo_'+sp_idd[1]+sp_idd[2]).attr('id');
         var ddlSendCopyTo_o_html=$('#ddlSendCopyTo_'+sp_idd[1]+sp_idd[2]).html();
        var ddl_cpsndto_state_o=$('#ddl_cpsndto_state_'+sp_idd[1]+sp_idd[2]).val();
        var ddl_cpsndto_state_o_id=$('#ddl_cpsndto_state_'+sp_idd[1]+sp_idd[2]).attr('id');
        var ddl_cpsndto_state_o_html=$('#ddl_cpsndto_state_'+sp_idd[1]+sp_idd[2]).html();
         var ddl_cpsndto_dst_o=$('#ddl_cpsndto_dst_'+sp_idd[1]+sp_idd[2]).val();
        var ddl_cpsndto_dst_o_id=$('#ddl_cpsndto_dst_'+sp_idd[1]+sp_idd[2]).attr('id');
        var ddl_cpsndto_dst_o_html=$('#ddl_cpsndto_dst_'+sp_idd[1]+sp_idd[2]).html();
        var ddl_send_copy_typeo=$('#ddl_send_copy_type'+sp_idd[1]+sp_idd[2]).val();
         var ddl_send_copy_typeo_id=$('#ddl_send_copy_type'+sp_idd[1]+sp_idd[2]).attr('id');
           var ddl_send_copy_typeo__html=$('#ddl_send_copy_type'+sp_idd[1]+sp_idd[2]).html();
        $.ajax({
            url: 'get_dynamic_cst.php',
            cache: false,
            async: true,
            data: {hd_Sendcopyto_o: hd_Sendcopyto_o,ddlSendCopyTo_o:ddlSendCopyTo_o,
                ddlSendCopyTo_o_id:ddlSendCopyTo_o_id,ddl_cpsndto_state_o:ddl_cpsndto_state_o,
            ddl_cpsndto_state_o_id:ddl_cpsndto_state_o_id,ddl_cpsndto_dst_o:ddl_cpsndto_dst_o,
        ddl_cpsndto_dst_o_id:ddl_cpsndto_dst_o_id,ddlSendCopyTo_o_html:ddlSendCopyTo_o_html,
        ddl_cpsndto_state_o_html:ddl_cpsndto_state_o_html,ddl_cpsndto_dst_o_html:ddl_cpsndto_dst_o_html,o_r_h:o_r_h,
    ddl_send_copy_typeo:ddl_send_copy_typeo,ddl_send_copy_typeo_id:ddl_send_copy_typeo_id,ddl_send_copy_typeo__html:ddl_send_copy_typeo__html},
           
            type: 'POST',
            success: function(data, status) {

                $('#dv_ext_cst'+sp_idd[1]+sp_idd[2]).append(data);
               
                var hd_Sendcopyto_inc=parseInt(hd_Sendcopyto_o)+1;
                 
                $('#hd_Sendcopyto_'+sp_idd[1]+sp_idd[2]).val(hd_Sendcopyto_inc);
                
//                $('#ddlSendCopyTo_o'+sp_idd[1]+'_'+hd_Sendcopyto_o).val(ddlSendCopyTo_o);
                $('#ddl_cpsndto_state_'+sp_idd[1]+sp_idd[2]+'_'+hd_Sendcopyto_o).val(ddl_cpsndto_state_o);
                $('#ddl_cpsndto_dst_'+sp_idd[1]+sp_idd[2]+'_'+hd_Sendcopyto_o).val(ddl_cpsndto_dst_o);
                  $('#ddl_send_copy_type'+sp_idd[1]+sp_idd[2]+'_'+hd_Sendcopyto_o).val(ddl_send_copy_typeo);
               
               },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
     $("#radiodn").click(function(){
        $("#diary_number").removeProp('disabled');
        $("#diary_year").removeProp('disabled');
        $("#case_type").prop('disabled',true);
        $("#case_number").prop('disabled',true);
        $("#case_year").prop('disabled',true);
        $("#case_type").val("-1");
        $("#case_number").val("");
        $("#case_year").val("");
    });
    
    $("#radioct").click(function(){
        $("#diary_number").prop('disabled',true);
        $("#diary_year").prop('disabled',true);
        $("#diary_number").val("");
        $("#diary_year").val("");
        $("#case_type").removeProp('disabled');
        $("#case_number").removeProp('disabled');
        $("#case_year").removeProp('disabled');
    });
    
    $(document).on('click','.cl_chk_parties',function(){
        var idd=$(this).attr('id');
        var sp_idd=idd.split('chk_id');
       if($(this).is(':checked'))
       {
           $('#tr_del_send_copy'+sp_idd[1]).css('display','table-row');
       }
       else
       {
           $('#tr_del_send_copy'+sp_idd[1]).css('display','none');
       }
       
       var chk_parties='';
       $('.cl_chk_parties').each(function(){
           if($(this).is(':checked'))
           {
               chk_parties=$(this).attr('id');
               return false;
               
           }
       });
      
       if(chk_parties!='')
       {
          var sp_chk_id=chk_parties.split('chk_id');
           var del_modes='';
           var ddl_send_type='';
           var ddlSendTo='';
           var ddl_sndto_state='';
           var ddl_sndto_dst_o='';
           
             var ddl_send_copy_type='';
             var ddlSendCopyTo='';
                var ddl_cpsndto_state='';
                var ddl_cpsndto_dst='';
                var hd_Sendcopyto='';
           
           var ddl_nt= $('#ddl_nt'+sp_chk_id[1]).val();
          var txtAmount=$('#txtAmount'+sp_chk_id[1]).val();
        
           var cl_del_mod=$('.cl_del_mod'+sp_chk_id[1]).attr('class');
           $('.'+cl_del_mod).each(function(){
               if($(this).is(':checked'))
               {
                  
                   if(del_modes=='')
                       {
                       del_modes=$(this).val();
                       var s_del_modes=$(this).val().toLowerCase();
//                       alert(s_del_modes);
                       ddl_send_type=$('#ddl_send_type'+s_del_modes+sp_chk_id[1]).val();
                       ddlSendTo=$('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).val();
                       ddl_sndto_state=$('#ddl_sndto_state_'+s_del_modes+sp_chk_id[1]).val();
                        ddl_sndto_dst_o=$('#ddl_sndto_dst_'+s_del_modes+sp_chk_id[1]).val();
                        
                        ddl_send_copy_type=$('#ddl_send_copy_type'+s_del_modes+sp_chk_id[1]).val();
                            ddlSendCopyTo=$('#ddlSendCopyTo_'+s_del_modes+sp_chk_id[1]).val();
                              ddl_cpsndto_state=$('#ddl_cpsndto_state_'+s_del_modes+sp_chk_id[1]).val();
                               ddl_cpsndto_dst=$('#ddl_cpsndto_dst_'+s_del_modes+sp_chk_id[1]).val();
                                  hd_Sendcopyto=$('#hd_Sendcopyto_'+s_del_modes+sp_chk_id[1]).val();
//                        alert($('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).val());
// alert($('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).attr('id'));
                       }
                   else 
                       {
                     del_modes=del_modes+','+$(this).val();
                      var s_del_modes=$(this).val().toLowerCase();
                     ddl_send_type=ddl_send_type+','+$('#ddl_send_type'+s_del_modes+sp_chk_id[1]).val(); 
                      ddlSendTo=ddlSendTo+','+$('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).val();
                          ddl_sndto_state=ddl_sndto_state+','+$('#ddl_sndto_state_'+s_del_modes+sp_chk_id[1]).val();
                             ddl_sndto_dst_o=ddl_sndto_dst_o+','+$('#ddl_sndto_dst_'+s_del_modes+sp_chk_id[1]).val();
                             
                             ddl_send_copy_type=ddl_send_copy_type+','+$('#ddl_send_copy_type'+s_del_modes+sp_chk_id[1]).val();
                                 ddlSendCopyTo=ddlSendCopyTo+','+$('#ddlSendCopyTo_'+s_del_modes+sp_chk_id[1]).val();
                                  ddl_cpsndto_state=ddl_cpsndto_state+','+$('#ddl_cpsndto_state_'+s_del_modes+sp_chk_id[1]).val();
                                     ddl_cpsndto_dst=ddl_cpsndto_dst+','+$('#ddl_cpsndto_dst_'+s_del_modes+sp_chk_id[1]).val();
                                       hd_Sendcopyto=hd_Sendcopyto+','+$('#hd_Sendcopyto_'+s_del_modes+sp_chk_id[1]).val();
//                       alert($('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).val());
//                       alert($('#ddlSendTo_'+s_del_modes+sp_chk_id[1]).attr('id'));
                       }
                     
               }
           });
//           alert(ddl_sndto_dst_o);
          
          $('#ddl_nt'+sp_idd[1]).val(ddl_nt);
          $('#txtAmount'+sp_idd[1]).val(txtAmount);
          
          var ex_del_mode=del_modes.split(',');
           var ex_ddl_send_type=ddl_send_type.split(',');
             var ex_ddlSendTo=ddlSendTo.split(',');
                 var ex_ddl_sndto_state=ddl_sndto_state.split(',');
                  var ex_ddl_sndto_dst_o=ddl_sndto_dst_o.split(',');
                  
                   var ex_ddl_send_copy_type=ddl_send_copy_type.split(',');
                    var ex_ddlSendCopyTo=ddlSendCopyTo.split(',');
                     var ex_ddl_cpsndto_state=ddl_cpsndto_state.split(',');
                       var ex_ddl_cpsndto_dst=ddl_cpsndto_dst.split(',');
                       
                        var ex_hd_Sendcopyto=hd_Sendcopyto.split(',');
                   
          for(var j=0;j<ex_del_mode.length;j++)
              {
              
               $('.cl_del_mod'+sp_idd[1]).each(function(){
                            
//                    alert($(this).val()+'^^'+ex_del_mode[j]);       
                   if($(this).val().trim()==ex_del_mode[j].trim())
                             {
                          
                         
//                            alert(in_idd);
                       $(this).prop('checked',true);
                       $('#ddl_send_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).val(ex_ddl_send_type[j]);
                    var idd_ddl_send_type= $('#ddl_send_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).attr('id');
                    var val_ddl_send_type=$('#ddl_send_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).val();
                    var val_ddlSendTo=ex_ddlSendTo[j];
                    var val_ddlSendTodis=ex_ddl_sndto_dst_o[j];
//                    alert(val_ddlSendTodis);
                     $('#ddl_sndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).val(ex_ddl_sndto_state[j]);
//                    alert(idd_ddl_send_type+'~~'+val_ddl_send_type+'~~'+'1'+'~~'+$(this).val().trim().toLowerCase()+'~~'+val_ddlSendTo);

                       get_send_to_type(idd_ddl_send_type,val_ddl_send_type,'1',$(this).val().trim().toLowerCase(),'','',val_ddlSendTo);
                         var idd_ddl_sndto_state= $('#ddl_sndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).attr('id');
                          var val_ddl_sndto_state=$('#ddl_sndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).val();
//                          alert(idd_ddl_sndto_state+'#'+val_ddl_sndto_state+'#'+'1'+'#'+$(this).val().trim().toLowerCase()+'#'+''+'#'+val_ddlSendTodis);
                       getCity(val_ddl_sndto_state,idd_ddl_sndto_state,'1',$(this).val().trim().toLowerCase(),'',val_ddlSendTodis);
                       
                       
                         $('#ddl_send_copy_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).val(ex_ddl_send_copy_type[j]);
                    var idd_ddl_send_copy_type= $('#ddl_send_copy_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).attr('id');
                    var val_ddl_send_copy_type=$('#ddl_send_copy_type'+$(this).val().trim().toLowerCase()+sp_idd[1]).val();
                      var val_ddlSendCopyTo=ex_ddlSendCopyTo[j];
                       var val_ddl_cpsndto_dst=ex_ddl_cpsndto_dst[j];
                         $('#ddl_cpsndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).val(ex_ddl_cpsndto_state[j]);
                          get_send_to_type(idd_ddl_send_copy_type,val_ddl_send_copy_type,'2',$(this).val().trim().toLowerCase(),'','',val_ddlSendCopyTo);
                          var idd_ddl_cpsndto_state= $('#ddl_cpsndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).attr('id');
                           var val_ddl_cpsndto_state=$('#ddl_cpsndto_state_'+$(this).val().trim().toLowerCase()+sp_idd[1]).val();
                            getCity(val_ddl_cpsndto_state,idd_ddl_cpsndto_state,'2',$(this).val().trim().toLowerCase(),'',val_ddl_cpsndto_dst);
                            
//                            var val_hd_Sendcopyto=ex_hd_Sendcopyto[j];
                             }
                });
                     }
          
       }
       
    });
    
    
    
   });

function get_send_to_type(idd,id_val,sta,type,d_ta,zx,ddlSendTo_zx)
{
//    alert(sta);
//    alert(type);
//     alert(idd);
    var  city_id='';
     var d_no=document.getElementById('diary_number').value;
    var d_yr=document.getElementById('diary_year').value; 
 if(sta=='1')
     city_id=idd.split('ddl_send_type'+type);
else if(sta=='2')
     city_id=idd.split('ddl_send_copy_type'+type);
else if(sta=='3')
    {
    
          city_id=idd.split('ddl_send_copy_type'+type);
    var new_id= city_id[1];
   
    var org_id=new_id.split('_');
    }
//    alert(city_id);
    $.ajax({
            url: 'get_send_to_type.php',
            cache: false,
            async: true,
            data: {id_val: id_val,d_no:d_no,d_yr:d_yr},
         
            type: 'POST',
            success: function(data, status) {
//alert('ddlSendTo_'+type+city_id[1]);
                 if(sta=='1')
                      document.getElementById('ddlSendTo_'+type+city_id[1]).innerHTML="<option value=''>Select</option>"+data;
              else if(sta=='2')
                      document.getElementById('ddlSendCopyTo_'+type+city_id[1]).innerHTML="<option value=''>Select</option>"+data;
                     else if(sta=='3')
                  document.getElementById('ddlSendCopyTo_'+type+org_id[0]+'_'+org_id[1]).innerHTML="<option value=''>Select</option>"+data;
//             alert(id_val);
                if(id_val==1)
             {
                 if(sta==1)
                 {
                    $('#ddl_sndto_state_'+type+city_id[1]).val('490506');
                     getCity('490506','ddl_sndto_state_'+type+city_id[1],sta,type,'u');
                }
                else if(sta==2)
                {
                    $('#ddl_cpsndto_state_'+type+city_id[1]).val('490506');
                 getCity('490506','ddl_cpsndto_state_'+type+city_id[1],sta,type,'u');
             }
             }
             
             if(d_ta!='')
             {
                  var sp_data=d_ta.split('~');
//                  alert(sp_data);
                  $('#ddlSendTo_'+type+city_id[1]).val(sp_data[0]);
                  $('#ddl_send_copy_type'+type+city_id[1]).val('1');
                  var ddl_send_copy_type= $('#ddl_send_copy_type'+type+city_id[1]).attr('id');
                  if(zx=='z')
                  {
                      $('#ddlSendCopyTo_'+type+city_id[1]).val(sp_data[1]); 
                    exit();
                  }
                  get_send_to_type(ddl_send_copy_type,'1','2',type,d_ta,'z');
                  
             }
//             alert('ddlSendTo_'+type+city_id[1]);
             if(ddlSendTo_zx!='' && ddlSendTo_zx!=undefined)
                 {
                     if(sta=='1')
                    $('#ddlSendTo_'+type+city_id[1]).val(ddlSendTo_zx);
                   else if(sta==2)
                         $('#ddlSendCopyTo_'+type+city_id[1]).val(ddlSendTo_zx);
                 }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
}
        
function getDetails() {
    var d_no = document.getElementById('diary_number').value;
    var d_yr = document.getElementById('diary_year').value;
    var cstype, csno, csyr, fno;
    var chk_status = 0;
    var regNum = new RegExp('^[0-9]+$');

    // Case type check
    if ($("#radioct").is(':checked')) {
        cstype = $("#case_type").val();
        csno = $("#case_number").val();
        csyr = $("#case_year").val();
        chk_status = 1;

        if (!regNum.test(cstype)) {
            alert("Please Select Casetype");
            $("#case_type").focus();
            return false;
        }
        if (!regNum.test(csno)) {
            alert("Please Fill Case No in Numeric");
            $("#case_number").focus();
            return false;
        }
        if (!regNum.test(csyr)) {
            alert("Please Fill Case Year in Numeric");
            $("#case_year").focus();
            return false;
        }
        if (csno == 0) {
            alert("Case No Can't be Zero");
            $("#case_number").focus();
            return false;
        }
        if (csyr == 0) {
            alert("Case Year Can't be Zero");
            $("#case_year").focus();
            return false;
        }
    }
    // Diary number check
    else if ($("#radiodn").is(':checked')) {
        var t_h_cno = $('#diary_number').val();
        var t_h_cyt = $('#diary_year').val();
        chk_status = 2;

        if (t_h_cno.trim() == '') {
            alert("Please enter Diary No.");
            $('#diary_number').focus();
            return false;
        }
        if (t_h_cyt.trim() == '') {
            alert("Please enter Diary Year");
            $('#diary_year').focus();
            return false;
        }
        fno = t_h_cno + t_h_cyt;
    }

    // Prepare the AJAX request
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Show loading image
    document.getElementById('div_results').innerHTML = '<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

    // Handle the response
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('div_results').innerHTML = xmlhttp.responseText;

            // Populate judges if available
            if (document.getElementById('jud1_a') && document.getElementById('hd_jud1')) {
                document.getElementById('jud1_a').value = document.getElementById('hd_jud1').value;
                if (document.getElementById('hd_jud1').value != '') {
                    document.getElementById('jud1_a').disabled = true;
                }
            }
            if (document.getElementById('jud2_a') && document.getElementById('hd_jud2')) {
                document.getElementById('jud2_a').value = document.getElementById('hd_jud2').value;
                if (document.getElementById('hd_jud2').value != '') {
                    document.getElementById('jud2_a').disabled = true;
                }
            }
            // Update diary fields only if 'hd_diary_no' exists
            var hd_diary_no = $('#hd_diary_no').val();
            if (hd_diary_no && hd_diary_no.length >= 4) {
                var diary_no = hd_diary_no.substr(0, hd_diary_no.length - 4);
                var diary_yr = hd_diary_no.substr(-4);

                $('#diary_number').val(diary_no);
                $('#diary_year').val(diary_yr);
            } else {
                console.error('hd_diary_no is undefined or too short.');
            }
        }
    }

    var host_docroot=baseURL;
    // Prepare the data to be sent
    var data = new FormData();
    data.append("d_no", d_no);
    data.append("d_yr", d_yr);
    data.append("fno", fno);
    data.append("ct", cstype);
    data.append("cn", csno);
    data.append("cy", csyr);
    data.append("chk_status", chk_status);
    data.append(csrfName, csrfHash); // Append CSRF token

    // Send the request
    xmlhttp.open("POST", host_docroot + "/Extension/Notices/generated", true);
    xmlhttp.send(data);
}



function get_mul_si(str)
{
     
      var str1=str.split('sp_mul');
   if( document.getElementById(str).innerHTML.trim()=='Multiple')
       {

    document.getElementById('ddl_nt'+str1[1]).multiple='multiple';
    document.getElementById(str).innerHTML='Single';
       }
      else if( document.getElementById(str).innerHTML.trim()=='Single')
       {

    document.getElementById('ddl_nt'+str1[1]).multiple=false;
    document.getElementById(str).innerHTML='Multiple';
       }
       document.getElementById('ddl_nt'+str1[1]).value='';
}

function get_save_dt()
{
  
  var check_or_nt=0;
    var check_or_nt_ct=0;
     var txtFFX='0';
    var not_ex_jud='0';
     var not_ex_jud1='0';
     var txtSub_nm='';
   
   
      var d_no=document.getElementById('diary_number').value;
    var d_yr=document.getElementById('diary_year').value;
   
 var individual_multiple='';
       $('.cl_ind_mul').each(function(){
           if($(this).is(':checked'))
           {
               individual_multiple=$(this).val();
           }
       });
//       alert(individual_multiple);
       if(individual_multiple=='')
       {
           alert("Please select Individual or Multiple");
           return false;
       }
  
   
   var chk_chk_c=0;
   var chk_vali_re=0;
   var hd_tot=document.getElementById('hd_tot').value;
    for(var y=0;y<hd_tot;y++)
        {
             var chk_id=document.getElementById('chk_id'+y);
  if(chk_id.checked==true)
      {
          chk_chk_c=1;
          break;
      }
        }
        if(document.getElementById('txtFFX'))
        txtFFX=document.getElementById('txtFFX').value;
   
        if(chk_chk_c==0  || txtFFX=='')
            {
               if(chk_chk_c==0)
                alert("Please check atleast one party");
            else if(txtFFX=='')
                {
                     document.getElementById('txtFFX').style.backgroundColor='red';
                     alert("Please Select Fixed For");
                }
            }
            else
                {
//  alert(hd_tot);
        for(var k=0;k<hd_tot;k++)
        {
            
            var chk_id=document.getElementById('chk_id'+k);
  if(chk_id.checked==true)
      {
          check_or_nt++;
     var sp_nm=document.getElementById('sp_nm'+k).value;
      var sp_add=document.getElementById('sp_add'+k).value;
      
     var hdinenroll='-1';
     var hdinenrollyr='-1';
     
     if(document.getElementById('hdinenroll_'+k))
      hdinenroll=document.getElementById('hdinenroll_'+k).value;
       
      if(document.getElementById('hdinenrollyr_'+k))
       hdinenrollyr=document.getElementById('hdinenrollyr_'+k).value;
      
      var ddl_nt=document.getElementById('ddl_nt'+k);
      var ddl_val='';
      var del_ty='';
      $('.cl_del_mod'+k).each(function(){
          if($(this).is(':checked'))
              {
              var idd=$(this).attr('id');
              var sp_id=idd.split('chkOrd');
              var v_val= $(this).val();
                 var d_val=v_val.toLowerCase();
                 var ddlSendTo=$('#ddlSendTo_'+d_val+sp_id[1]).val();
                  var ddl_sndto_state=$('#ddl_sndto_state_'+d_val+sp_id[1]).val();
                  var ddl_sndto_dst=$('#ddl_sndto_dst_'+d_val+sp_id[1]).val();
                  
                  var ddlSendCopyTo=$('#ddlSendCopyTo_'+d_val+sp_id[1]).val();
                  var ddl_cpsndto_state=$('#ddl_cpsndto_state_'+d_val+sp_id[1]).val();
                  var ddl_cpsndto_dst=$('#ddl_cpsndto_dst_'+d_val+sp_id[1]).val();
                  var hd_Sendcopyto=$('#hd_Sendcopyto_'+d_val+sp_id[1]).val();
//                  alert(hd_Sendcopyto);
                  if(hd_Sendcopyto!=0)
                      {
                  var ex_data='';        
                  for(var j=0;j<hd_Sendcopyto;j++)
                              {
                                  var ddlSendCopyToe=$('#ddlSendCopyTo_'+d_val+sp_id[1]+'_'+j).val();
                  var ddl_cpsndto_statee=$('#ddl_cpsndto_state_'+d_val+sp_id[1]+'_'+j).val();
                  var ddl_cpsndto_dste=$('#ddl_cpsndto_dst_'+d_val+sp_id[1]+'_'+j).val();
                  if(ex_data=='')
                      ex_data='$'+ddlSendCopyToe+'~'+ddl_cpsndto_statee+'~'+ddl_cpsndto_dste;
                  else
                      ex_data=ex_data+'$'+ddlSendCopyToe+'~'+ddl_cpsndto_statee+'~'+ddl_cpsndto_dste;
                              }
                      }
                     
              if(del_ty=='')
                      {
                      del_ty=v_val+'!'+ddlSendTo+'~'+ddl_sndto_state+'~'+ddl_sndto_dst+'!'+
                              ddlSendCopyTo+'~'+ddl_cpsndto_state+'~'+ddl_cpsndto_dst+ex_data;
                      }
                  else 
                      {
                    del_ty=del_ty+','+v_val+'!'+ddlSendTo+'~'+ddl_sndto_state+'~'+ddl_sndto_dst+'!'+
                              ddlSendCopyTo+'~'+ddl_cpsndto_state+'~'+ddl_cpsndto_dst+ex_data;  
                      }
              }
      });
      for(var dd=0;dd<ddl_nt.length;dd++)
          {
              if(ddl_nt[dd].selected==true)
                  {
                      if(ddl_val=='')
                          ddl_val=ddl_nt[dd].value;
                      else
                           ddl_val=ddl_val+','+ddl_nt[dd].value;
                  }
                  
          }
//         var chkOrd= document.getElementById('chkOrd'+k);
//          var chkReg= document.getElementById('chkReg'+k);
//           var chkAdvReg= document.getElementById('chkAdvReg'+k);
//           
//            var chkAdvHum= document.getElementById('chkAdvHum'+k);

       var ddlState=document.getElementById('ddlState'+k).value;
        var ddlCity=document.getElementById('ddlCity'+k).value;
        
//        var ddlSendTo=document.getElementById('ddlSendTo'+k).value;
//        
//         var ddlSendCopyTo=document.getElementById('ddlSendCopyTo'+k).value;
         var chk_pet_res_rd=0;
         if(document.getElementById('rdnChkPet'+k))
             {
         var rdnChkRes=document.getElementById('rdnChkRes'+k);
          var rdnChkPet=document.getElementById('rdnChkPet'+k);
            var rdnChkOth=document.getElementById('rdnChkOth'+k);
         if(rdnChkRes.checked==false &&  rdnChkPet.checked==false &&  rdnChkOth.checked==false)
             {
                 chk_pet_res_rd=1;
             }
             }

     if((sp_nm=='') || (sp_add=='' ) || ddl_val=='' || del_ty=='' || ddlState=='' || ddlCity==''   || chk_pet_res_rd==1 || hdinenroll=='' || hdinenrollyr=='')
         {
    chk_vali_re=1;
    if(sp_nm=='' )
           document.getElementById('sp_nm'+k).style.backgroundColor='red';
       if(sp_add=='' )
           document.getElementById('sp_add'+k).style.backgroundColor='red';
        if(ddl_val=='')
           document.getElementById('ddl_nt'+k).style.backgroundColor='red';
       if(del_ty=='')
           {
           document.getElementById('sp_ordinary_ck'+k).style.color='red';
            if(document.getElementById('sp_reg_ck'+k))
              document.getElementById('sp_reg_ck'+k).style.color='red';
           if(document.getElementById('sp_adv_reg_ck'+k)) 
              document.getElementById('sp_adv_reg_ck'+k).style.color='red';
            if(document.getElementById('sp_hum_ck'+k))
              document.getElementById('sp_hum_ck'+k).style.color='red';
           }
        if(ddlState=='')
            document.getElementById('ddlState'+k).style.backgroundColor='red';
        if(ddlCity=='')
            document.getElementById('ddlCity'+k).style.backgroundColor='red';
       
        
        if(chk_pet_res_rd==1)
            {
                document.getElementById('sp_ChkPet_P'+k).style.color='red';
                 document.getElementById('sp_sp_ChkRes_R'+k).style.color='red';
                  document.getElementById('sp_sp_ChkOth_R'+k).style.color='red';
            }
         
//         alert(hdinenroll);
         if(hdinenroll=='')
                {
                    document.getElementById('hdinenroll_'+k).style.backgroundColor='red';
                }
           if(hdinenrollyr=='')
                {
                    document.getElementById('hdinenrollyr_'+k).style.backgroundColor='red';
                }
            
         }
        }
        }
        if(chk_vali_re==1)
            {
                alert("Please Fill Details shown in red color");
            }
            else
                {
  
       $(".bb_sub_m").attr("disabled",true);     
            var hd_ck_pf_nt=-1;
 if(document.getElementById('hd_ck_pf_nt'))
 hd_ck_pf_nt=document.getElementById('hd_ck_pf_nt').value;
 
 if(document.getElementById('hd_ck_mul_re_st'))
 {
 if(document.getElementById('hd_ck_mul_re_st').value=='0')
     {
         ck_mul_rem(d_no,d_yr);
//         if(hd_ck_pf_nt==0 && txtFFX!='0')
//     {
//        
//        sv_up_ten_dt(txtFFX,d_no,d_yr);
//     }
     }
 }

//var jud1=document.getElementById('hd_jud_code1').value;
  
  for(var z=0;z<hd_tot;z++)
        {
             var chk_id=document.getElementById('chk_id'+z);
             if(chk_id.checked==false)
      {
           var hd_mn_id=document.getElementById('hd_mn_id'+z).value;
                if(hd_mn_id!='')
                    {
                        delete_record(hd_mn_id);
                    }
      }
        }
  
  
  for(var i=0;i<hd_tot;)
        {
// alert(i);
  var chk_id=document.getElementById('chk_id'+i);
  if(chk_id.checked==true)
      {
          check_or_nt_ct++;
     var sp_nm=escape(document.getElementById('sp_nm'+i).value);
     var hd_sr_no=document.getElementById('hd_sr_no'+i).value;

      if(hd_sr_no=='')
          hd_sr_no='0';

       var hd_pet_res=document.getElementById('hd_pet_res'+i).value;
      var sp_add=encodeURIComponent(document.getElementById('sp_add'+i).value);
      
      var hdinenroll='';
     var hdinenrollyr='';
      if(document.getElementById('hdinenroll_'+i))
      hdinenroll=document.getElementById('hdinenroll_'+i).value;
       
      if(document.getElementById('hdinenrollyr_'+i))
       hdinenrollyr=document.getElementById('hdinenrollyr_'+i).value;
     
      var ddl_nt=document.getElementById('ddl_nt'+i);
      var ddl_val='';
      var del_ty='';
      var copy_send_to='';
//       var ex_data='';     
      for(var dd=0;dd<ddl_nt.length;dd++)
          {
              if(ddl_nt[dd].selected==true)
                  {
                      if(ddl_val=='')
                          ddl_val=ddl_nt[dd].value;
                      else
                           ddl_val=ddl_val+','+ddl_nt[dd].value;
                  }
                  
          }
        $('.cl_del_mod'+i).each(function(){
          if($(this).is(':checked'))
              {
              var idd=$(this).attr('id');
              if($(this).val()=='O')
              var sp_id=idd.split('chkOrd');
          else if($(this).val()=='R')
              var sp_id=idd.split('chkReg');
           else if($(this).val()=='H')
              var sp_id=idd.split('chkAdvHum');
            else if($(this).val()=='A')
              var sp_id=idd.split('chkAdvReg');
              var v_val= $(this).val();
                 var d_val=v_val.toLowerCase();
                 var ddlSendTo=$('#ddlSendTo_'+d_val+sp_id[1]).val();
                  var ddl_sndto_state=$('#ddl_sndto_state_'+d_val+sp_id[1]).val();
                  var ddl_sndto_dst=$('#ddl_sndto_dst_'+d_val+sp_id[1]).val();
                  
                   var ddl_send_type=$('#ddl_send_type'+d_val+sp_id[1]).val();
                  
                  var ddlSendCopyTo=$('#ddlSendCopyTo_'+d_val+sp_id[1]).val();
                  var ddl_cpsndto_state=$('#ddl_cpsndto_state_'+d_val+sp_id[1]).val();
                  var ddl_cpsndto_dst=$('#ddl_cpsndto_dst_'+d_val+sp_id[1]).val();
                  var hd_Sendcopyto=$('#hd_Sendcopyto_'+d_val+sp_id[1]).val();
                  
                  var ddl_send_copy_type=$('#ddl_send_copy_type'+d_val+sp_id[1]).val();
//                  alert(hd_Sendcopyto);
                    var ex_data='';        
                  if(hd_Sendcopyto!=0)
                      {
                
                  for(var j=0;j<hd_Sendcopyto;j++)
                              {
                                  var ddlSendCopyToe=$('#ddlSendCopyTo_'+d_val+sp_id[1]+'_'+j).val();
                  var ddl_cpsndto_statee=$('#ddl_cpsndto_state_'+d_val+sp_id[1]+'_'+j).val();
                  var ddl_cpsndto_dste=$('#ddl_cpsndto_dst_'+d_val+sp_id[1]+'_'+j).val();
                  
                   var ddl_send_copy_type_e=$('#ddl_send_copy_type'+d_val+sp_id[1]+'_'+j).val();
                  
                 if(ddlSendCopyToe!='' && ddl_cpsndto_statee!='' && ddl_cpsndto_dste!='' && ddl_send_copy_type!='')
                     {
                        if(ex_data=='')
                      ex_data='$'+ddlSendCopyToe+'~'+ddl_cpsndto_statee+'~'+ddl_cpsndto_dste+'~'+ddl_send_copy_type_e;
                  else
                      ex_data=ex_data+'$'+ddlSendCopyToe+'~'+ddl_cpsndto_statee+'~'+ddl_cpsndto_dste+'~'+ddl_send_copy_type_e;
                              }
                              }
                      }
                  if(ddlSendCopyTo!='')
                        copy_send_to='!'+ddlSendCopyTo+'~'+ddl_cpsndto_state+'~'+ddl_cpsndto_dst+'~'+ddl_send_copy_type; 
                    else 
                        copy_send_to='';
              if(del_ty=='')
                      {
                   
                    del_ty=v_val+'!'+ddlSendTo+'~'+ddl_sndto_state+'~'+ddl_sndto_dst+'~'+ddl_send_type+copy_send_to+ex_data;
                      }
                  else 
                      {
                    del_ty=del_ty+','+v_val+'!'+ddlSendTo+'~'+ddl_sndto_state+'~'+ddl_sndto_dst+'~'+ddl_send_type+copy_send_to+ex_data;  
                      }
//                      alert(del_ty);
              }
      });
              
              
//         var txtMob=document.getElementById('txtMob'+i).value;
//         var hd_jud_code=document.getElementById('hd_jud_code').value;
//         var txtNote=escape(document.getElementById('txtNote'+i).value);
        
          
          var ddlState=document.getElementById('ddlState'+i).value;
          var ddlCity=document.getElementById('ddlCity'+i).value;
            var txtAmount=document.getElementById('txtAmount'+i).value;
          var nm_wd=numToString(txtAmount);
         
            var hd_new_upd=document.getElementById('hd_new_upd'+i).value;
            var hd_mn_id=document.getElementById('hd_mn_id'+i).value;
           if(document.getElementById('txtSub_nm'))
           txtSub_nm=escape(document.getElementById('txtSub_nm').value);
       var hd_order_date=$('#hd_order_date').val();
//      alert(hd_order_date);
      //   var hd_sec_id=document.getElementById('hd_sec_id').value;
  var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
           //   document.getElementById('div_results').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';
           
//             if(check_or_nt==check_or_nt_ct)
//                 {
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
//                       alert(xmlhttp.responseText);
                        i++;
                      if(check_or_nt==check_or_nt_ct)
                 { 
                     document.getElementById('div_results').innerHTML=xmlhttp.responseText;
                        var hd_ent_suc_f=document.getElementById('hd_ent_suc_f').value;
                        
                     var cl_chk_cnt_case='';
                     
                          
                    
                       
                        if(hd_ent_suc_f==1)
                            {
                              var hd_new_upd=$('#hd_new_upd').val();
//                             if(hd_new_upd==0)
//                                 {
//                                    ins_t_o_r(d_no,hd_new_upd,d_yr);  
                               show_details(d_no,d_yr);
//                               show_details(filling_no)
                            }
                            else
                                {
                                    alert("Data Not Saved");
                                }
                            
                   }
                    }
                }
//                 }
                 
            
                 xmlhttp.open("GET","save_talwana.php?sp_nm="+sp_nm+"&sp_add="+sp_add+"&ddl_val="
                     +ddl_val+"&del_ty="+del_ty+"&hd_sr_no="+hd_sr_no+"&hd_pet_res="+hd_pet_res+"&ddlState="+ddlState+
                     "&ddlCity="+ddlCity+"&txtAmount="+txtAmount+"&hd_new_upd="+hd_new_upd+
                     "&hd_mn_id="+hd_mn_id+"&nm_wd="+nm_wd+"&txtFFX="+txtFFX+"&txtSub_nm="+txtSub_nm+'&hdinenroll='+hdinenroll+
                     '&hdinenrollyr='+hdinenrollyr+'&d_no='+d_no+'&d_yr='+d_yr+'&hd_order_date='+hd_order_date+'&individual_multiple='+individual_multiple,false);
          xmlhttp.send(null);
        }
        else
            {
                i++;
            }

                }
                }
                }
}

//function ins_t_o_r(d_no,hd_new_upd,d_yr)
//{
//     $.ajax({
//        url:'save_t_o_r.php',
//        cache:false,
//        async:true,
//    
//     data:{d_no:d_no,hd_new_upd:hd_new_upd,d_yr:d_yr
//         },
//        type:'POST',
//        success:function(data,status){
//
////         alert(data);
//         show_details(d_no,d_yr);
//         
//     
//        },
//        error:function(xhr){
//            alert("Error: "+xhr.status+" "+xhr.statusText);
//        }
//        
//    });
//}

function delete_record(str)
{
   alert(str);
   var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
             
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       
                       // document.getElementById('div_results').innerHTML=xmlhttp.responseText;
                   }
                }
                
                 
            
                 xmlhttp.open("GET","delete_records.php?str="+str,true);
          xmlhttp.send(null);
}

function  show_details(d_no,d_yr)
{
    var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
             
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       
                        document.getElementById('div_results').innerHTML=xmlhttp.responseText;
                   }
                }
                
                 
            
                 xmlhttp.open("GET","get_records.php?d_no="+d_no+"&d_yr="+d_yr,true);
          xmlhttp.send(null);
}

function clear_data(str)
{
    document.getElementById(str).style.backgroundColor='';
}

function show_hd(str)
{
 
  if($('#'+str).is(':checked'))
  {
    var chkOrd=$('#'+str).val();
   chkOrd=chkOrd.toLowerCase();
 if(chkOrd=='o')
   var str1=str.split('chkOrd');
   else if(chkOrd=='r')
   var str1=str.split('chkReg');
     else if(chkOrd=='h')
   var str1=str.split('chkAdvHum');
    else if(chkOrd=='a')
   var str1=str.split('chkAdvReg');
  
//     alert($('#ddl_nt'+str1[1]).val());
     if($('#ddl_nt'+str1[1]).val()=='5')
     {
          var sp_pet_res_id=$('#sp_pet_res_id'+str1[1]).html();
    var d_no=document.getElementById('diary_number').value;
    var d_yr=document.getElementById('diary_year').value; 
        $.ajax({
            url: 'auto_fill_rec.php',
            cache: false,
            async: true,
            data: {sp_pet_res_id: sp_pet_res_id,d_no:d_no,d_yr:d_yr},
           
            type: 'POST',
            success: function(data, status) {

               
                $('#ddl_send_type'+chkOrd+str1[1]).val(1);
             var ddlSendTo=$('#ddl_send_type'+chkOrd+str1[1]).attr('id');
                get_send_to_type(ddlSendTo,'1','1',chkOrd,data);
             
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
     }
 }
      document.getElementById('sp_ordinary_ck'+str1[1]).style.color='';
    document.getElementById('sp_reg_ck'+str1[1]).style.color='';
     document.getElementById('sp_adv_reg_ck'+str1[1]).style.color='';
}

function show_hd_x(str)
{
   
var str1=str.split('rdnChkPet');
   document.getElementById('sp_ChkPet_P'+str1[1]).style.color='';
   document.getElementById('sp_sp_ChkRes_R'+str1[1]).style.color='';
      document.getElementById('sp_sp_ChkOth_R'+str1[1]).style.color='';
    document.getElementById('hd_pet_res'+str1[1]).value='P';
}
function show_hd_x1(str)
{
   
var str1=str.split('rdnChkRes');
   document.getElementById('sp_sp_ChkRes_R'+str1[1]).style.color='';
    document.getElementById('sp_ChkPet_P'+str1[1]).style.color='';
     document.getElementById('sp_sp_ChkOth_R'+str1[1]).style.color='';
     document.getElementById('hd_pet_res'+str1[1]).value='R';
}

function show_hd_x2(str)
{
   
var str1=str.split('rdnChkOth');
   document.getElementById('sp_sp_ChkRes_R'+str1[1]).style.color='';
    document.getElementById('sp_ChkPet_P'+str1[1]).style.color='';
     document.getElementById('sp_sp_ChkOth_R'+str1[1]).style.color='';
     document.getElementById('hd_pet_res'+str1[1]).value='Z';
}

function show_hd1(str)
{
   
   var str1=str.split('chkReg');
   document.getElementById('sp_ordinary_ck'+str1[1]).style.color='';
    document.getElementById('sp_reg_ck'+str1[1]).style.color='';
     document.getElementById('sp_adv_reg_ck'+str1[1]).style.color='';
}
function show_hd2(str)
{
   
   var str1=str.split('chkAdvReg');
   document.getElementById('sp_ordinary_ck'+str1[1]).style.color='';
    document.getElementById('sp_reg_ck'+str1[1]).style.color='';
     document.getElementById('sp_adv_reg_ck'+str1[1]).style.color='';
}

function dummy(fil_no,dt)
{
      
         var d_no=document.getElementById('diary_number').value;
    var d_yr=document.getElementById('diary_year').value; 
//var hd_ud=document.getElementById('hd_ud_name').value;
//var hd_ud_idd=document.getElementById('hd_ud').value;
document.getElementById('hd_fil_no_x').value=fil_no;
document.getElementById('hd_recdt').value=dt;
//var case_type=document.getElementById('cs_tp').options[document.getElementById('cs_tp').selectedIndex].innerHTML;
 document.getElementById('ggg').style.width='auto';
            document.getElementById('ggg').style.height=' 500px';
             document.getElementById('ggg').style.overflow='scroll'; 
           //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
               document.getElementById('ggg').style.marginLeft ='18px';
                document.getElementById('ggg').style.marginRight ='18px';
                 document.getElementById('ggg').style.marginBottom ='25px';
                  document.getElementById('ggg').style.marginTop ='1px';
                   document.getElementById('dv_edi').style.marginLeft ='18px';
                document.getElementById('dv_edi').style.marginRight ='18px';
                 document.getElementById('dv_edi').style.paddingTop='10px';
                 document.getElementById('dv_edi').style.paddingBottom ='10px';
                  document.getElementById('dv_edi').style.backgroundColor='#bdd5ff';
                  $('#btn_publish').css('display','inline');
                 // #ebc8f4
 
                var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
              document.getElementById('dv_sh_hd').style.display='block';
               document.getElementById('dv_fixedFor_P').style.display='block';
                document.getElementById('dv_fixedFor_P').style.marginTop='3px';
             document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                     //  document.getElementById('dv_fixedFor_P').style.marginTop='3px';
            //  document.getElementById('dv_sh_hd').style.display='block';
            // document.getElementById('dv_fixedFor_P').style.display='block';
              
                       
                       document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                       $('.ind_no_w_vc').each(function(){
                        var id=$(this).attr('id');
//                        alert('#qr_'+id);
//                         $('#qr_'+id).qrcode('Anshul');   
 $('#qr_'+id).html('');
                          $('#qr_'+id).qrcode({render	: "div",size:90,text:'http://sci.gov.in/notice/'+d_yr+'/'+d_no+'/'+id+'.pdf'});
                       });
                       
                      $('#btn_publish').css('display','none');
                   }
                }
                
                 
            
           xmlhttp.open("GET","pocriminal1.php?fil_no="+fil_no+"&dt="+dt,true);
          

          xmlhttp.send(null);
}


            function getItalic()
            {
             //   document.execCommand('styleWithCSS', false, null);
               document.execCommand('Italic', false, null);
                document.getElementById('ggg').focus();
                checkStat();
            }
            function getBold()
            {
              
             //  document.execCommand('styleWithCSS', false, null);
              
               document.execCommand('Bold', false ,null);
           document.getElementById('ggg').focus();
          checkStat();
            }
             function getUnderline()
            {
                // document.execCommand('styleWithCSS', false, null);
                document.execCommand('Underline', false ,null);
                 document.getElementById('ggg').focus();
                 checkStat();
            }
              function getFS(str)
            {
               // alert(str);
    //                document.execCommand("styleWithCSS", true, null);
              //  document.execCommand("insertHTML", false, "<font style='font-size:12px;'>"+ document.getSelection()+"</font>");
            //  document.execCommand('fontSize', false ,str)
            
             // document.execCommand('styleWithCSS', false, null);
               
              document.execCommand('fontSize', false, str);
               document.getElementById('ggg').focus();
              
            }
            
            function jus_cen()
            {
               //  document.execCommand('styleWithCSS', false, null);
                document.execCommand('JustifyCenter', false ,null);
                 document.getElementById('ggg').focus();
                 checkStat();
            }
            function jus_left()
            {
               // document.execCommand('styleWithCSS', false, null);
               document.execCommand('JustifyLeft', false ,null);
                document.getElementById('ggg').focus();
                checkStat();
            }
             function jus_right()
            {
                // document.execCommand('styleWithCSS', false, null);
                document.execCommand('JustifyRight', false ,null);
                 document.getElementById('ggg').focus();
                 checkStat();
            }
             function jus_full()
            {
              //  document.execCommand('styleWithCSS', false, null);
               document.execCommand('JustifyFull', false ,null);
                document.getElementById('ggg').focus();
                checkStat();
            }
              function nb(e)
            {
             var key;
if(window.e)
{
key=e.keyCode;
}

else if(e.which)
{
key=e.which;

}

 if(e.ctrlKey)
{
    key1=e.ctrlKey;
}
//                  alert(key+'##'+key1);
              if(e.keyCode=='9')
                   {
          
            
             // document.execCommand('styleWithCSS', false, null);
               document.execCommand('indent', false ,null);
               return false;
                   }
//                   if(key=='98' && key1==true)
//                   {
//                       getBold();
//                   }
//                   else if(key=='115' && key1==true)
//                   {
//                       getItalic();
//                   }
//                    else if(key=='117' && key1==true)
//                   {
//                       getUnderline();
//                   }
                  
            }
            
            function getFonts(str)
            {
//                document.execCommand('styleWithCSS', false, null);
               document.execCommand('FontName', false ,str);
               //document.execCommand('focus', false ,null);
               document.getElementById('ggg').focus();
             //  return false;
             checkStat();
            }
            
            function get_supScr()
            {
                document.execCommand('superscript', false,null);
            }
            
            function checkStat()
            {

      var fon_nm=document.queryCommandValue("FontName");
//document.execCommand('styleWithCSS', false, null);
  var fon_sz=document.queryCommandValue("FontSize");
    var ital=document.queryCommandState("Italic");
    var bld=document.queryCommandState("Bold");
    var undell=document.queryCommandState("Underline");
    
   var jc=document.queryCommandState("JustifyCenter");
  
    var jl=document.queryCommandState("JustifyLeft");
    var jr=document.queryCommandState("JustifyRight");
    var jf=document.queryCommandState("JustifyFull");
  
    document.getElementById('ddlFS').value=fon_sz;
    if(ital==true)
    document.getElementById('btnItalic').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnItalic').style.backgroundColor=''; 

     if(bld==true)
    document.getElementById('btnBold').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnBold').style.backgroundColor=''; 

    if(undell==true)
    document.getElementById('btnUnderline').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnUnderline').style.backgroundColor=''; 
    
    if(jc==true)
    document.getElementById('btnJustify').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnJustify').style.backgroundColor=''; 

    if(jl==true)
    document.getElementById('btnAliLeft').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnAliLeft').style.backgroundColor='';

    if(jr==true)
    document.getElementById('btnAliRight').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnAliRight').style.backgroundColor='';
if(jf==true)
    document.getElementById('btnFull').style.backgroundColor='#bbb51f';
    else
    document.getElementById('btnFull').style.backgroundColor='';
 document.getElementById('ddlFontFamily').value=fon_nm;
        //  alert(document.getElementById('ddlFontFamily').value)    ;  
       //  document.getElementById('ggg').focus();
            }
           
           function get_set_prt()
           {
 var d_no=document.getElementById('diary_number').value;
    var d_yr=document.getElementById('diary_year').value; 
$('.ind_no_w_vc').each(function(){
                        var id=$(this).attr('id');
//                        alert('#qr_'+id);
                        $('#qr_'+id).html('');
//                         $('#qr_'+id).qrcode('Anshul');    
//                           $('#qr_'+id).qrcode({render	: "div",size:90,text:'http://mphc.gov.in/'+txtYear+'/'+case_type+'/'+txtFNo+'/'+id+'.pdf'});
                            $('#qr_'+id).qrcode({render	: "div",size:90,text:'http://sci.gov.in/notice/'+d_yr+'/'+d_no+'/'+id+'.pdf'});
                       });
//var  hd_tot_po=document.getElementById('hd_tot_po').value;
//    
 var prtContent = document.getElementById('ggg');
save_content(escape(prtContent.innerHTML));

           }
           
           function closeData()
{
    document.getElementById('ggg').scrollTop=0; 
  
    document.getElementById('dv_fixedFor_P').style.display="none";
      document.getElementById('dv_sh_hd').style.display="none";

//       document.getElementById('sp_close').style.display='none';
} 

function  save_content(str)
{
  // alert(str);
  
   var fil_no= document.getElementById('hd_fil_no_x').value;
//   alert(fil_no);
var dt=document.getElementById('hd_recdt').value;
    var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
               //  xmlhttp.overrideMimeType('text/xml; charset=iso-8859-1');
           //   document.getElementById('div_results').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
//                   alert(xmlhttp.responseText);    
              $('#dis_notice').html(xmlhttp.responseText);
               var id_d='';
            
            $('.ind_no_w_vc').each(function(){
             
                if(id_d=='')
                id_d=$(this).attr('id');
                else
                    id_d=id_d+','+$(this).attr('id');

             });
//              alert(id_d);
              save_pdf_html(id_d,fil_no,dt);
             
//                  $('#ggg').attr('contenteditable',false);     
                       //document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                   }
                }
                
                
            
                 xmlhttp.open("POST","save_content.php",true);
               

               
//                 xmlhttp.setRequestHeader("Content-Type","text/html;charset=utf-8;");
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("accept-charset","UTF-8");
                 xmlhttp.send("str="+str+"&fil_no="+fil_no+"&dt="+dt);
//                  xmlhttp.send(null);
}

//function save_pdf_html(id_d,fil_no,dt)
//{
//     $.ajax({
//        
//        url:'save_pdf_html.php',
//        cache:false,
//        async:true,
//    
//     data:{id_d:id_d,fil_no:fil_no,dt:dt},
//        type:'POST',
//        success:function(data,status){
//
//          alert(data);
//          $('#dis_notice').html(data);
//         
//        },
//        error:function(xhr){
//            alert("Error: "+xhr.status+" "+xhr.statusText);
//        }
//        
//    });
//}

function save_pdf_html(id_d,fil_no,dt)
{
    var cks_ids='';
     $('.ind_no_w_vc').each(function(){
             
                if(cks_ids=='')
                cks_ids=$(this).html();
                else
                    cks_ids=cks_ids+'~!@#$'+$(this).html();

             });
//             alert(cks_ids);
             cks_ids=encodeURIComponent(cks_ids);
    $.ajax({
        
        url:'save_pdf_html.php',
        cache:false,
        async:true,
    
     data:{id_d:id_d,fil_no:fil_no,dt:dt,cks_ids:cks_ids},
        type:'POST',
        success:function(data,status){

//          alert(data);
          $('#dis_notice').html(data);
          
//            var txtFNo=parseInt(document.getElementById('txtFNo').value);
//    var txtYear=document.getElementById('txtYear').value;
// 
//    
//    var case_type=document.getElementById('cs_tp').options[document.getElementById('cs_tp').selectedIndex].innerHTML;     
//$('.ind_no_w_vc').each(function(){
//                        var id=$(this).attr('id');
////                        alert('#qr_'+id);
//                        $('#qr_'+id).html('');
////                         $('#qr_'+id).qrcode('Anshul');    
//                           $('#qr_'+id).qrcode({render	: "div",size:90,text:'http://mphc.gov.in/'+txtYear+'/'+case_type+'/'+txtFNo+'/'+id+'.pdf'});
//                       });
var  hd_tot_po=document.getElementById('hd_tot_po').value;
    
 var prtContent = document.getElementById('ggg');
 var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
//WinPrint.document.write('<style type="text/css">*{ font-size:8px;}</style>'+prtContent.innerHTML);
WinPrint.document.write(prtContent.innerHTML);
 //WinPrint.document.getElementById('pba').style.pageBreakBefore='always';
// if(WinPrint.document.getElementById('pba'))
 //WinPrint.document.getElementById('pba').style.display='none';

  
 for(var e=0;e<=hd_tot_po;e++)
     {
 if(WinPrint.document.getElementsByTagName("hr")[e])
     WinPrint.document.getElementsByTagName("hr")[e].style.display='none';
     }
     
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
         
        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
}
  
  function getCity(str,idd,sta,type,upd,val_ddlSendTodis)
  {
//     alert("www"+val_ddlSendTodis);
      var  city_id='';
      if(sta=='0')
     city_id=idd.split('ddlState');
else if(sta=='1')
     city_id=idd.split('ddl_sndto_state_'+type);
else if(sta=='2')
     city_id=idd.split('ddl_cpsndto_state_'+type);
else if(sta=='3')
    {
//     alert(idd);
          city_id=idd.split('ddl_cpsndto_state_'+type);
    var new_id= city_id[1];
   
    var org_id=new_id.split('_');
    }
//    alert(new_id);
     var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
             
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       
//              alert(xmlhttp.responseText);
                       if(sta=='0')
                       document.getElementById('ddlCity'+city_id[1]).innerHTML="<option value=''>Select</option>"+xmlhttp.responseText;
                       else if(sta=='1')
                      document.getElementById('ddl_sndto_dst_'+type+city_id[1]).innerHTML="<option value=''>Select</option>"+xmlhttp.responseText;
              else if(sta=='2')
                      document.getElementById('ddl_cpsndto_dst_'+type+city_id[1]).innerHTML="<option value=''>Select</option>"+xmlhttp.responseText;
                     else if(sta=='3')
                  document.getElementById('ddl_cpsndto_dst_'+type+org_id[0]+'_'+org_id[1]).innerHTML="<option value=''>Select</option>"+xmlhttp.responseText;
                   if(upd=='u')
                   {
                       if(sta==1)
                           $('#ddl_sndto_dst_'+type+city_id[1]).val('490611');
                       else  if(sta==2)
                           $('#ddl_cpsndto_dst_'+type+city_id[1]).val('490611');
                   }
                
                   if(val_ddlSendTodis!='' && val_ddlSendTodis!=undefined)
                       {
//                           alert("anshul"+val_ddlSendTodis);
                           if(sta==1)
                           $('#ddl_sndto_dst_'+type+city_id[1]).val(val_ddlSendTodis);
                       else if(sta==2)
                           $('#ddl_cpsndto_dst_'+type+city_id[1]).val(val_ddlSendTodis);
                       }
                   
                   }
                }
                
                 
            
                //  xmlhttp.open("GET","getCityName.php?str="+str,true);
                 xmlhttp.open("GET",baseURL + "/Extension/Notices/getCityName?str="+str,true);
                  xmlhttp.send(null);
  }
  
  
  function getcityss_ex(str,idd,sta)
  {
     
    var city_id=idd.split('ddlState');

     var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
             
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       
              
                     
                       document.getElementById('ddlCity'+city_id[1]).innerHTML="<option value=''>Select</option>"+xmlhttp.responseText;
                     
              }
                }
                
                 
            
                 xmlhttp.open("GET","getCityName_s.php?str="+str,true);
                  xmlhttp.send(null);
  }
  
   function OnlyNumbersTalwana(event,str)
{
var key;
if(window.event)
{
key=event.keyCode;
}
else if(event.which)
{
key=event.which;
}

var ln_val=$('#'+str).val().length;
//alert(ln_val);

if((key>=48 && key<=57) || key==8 || ((key==77 || key==109) && ln_val==0) ||  ((key==80 || key==112) && ln_val==1) )
{

    return true;
}
else if(key==undefined)
    {
      //  alert("Anshul");
        return true;
    }
else
{


return false;
    
}

}

function get_nms(str_val,str_id)
{
   var str=str_id.split('ddlSendTo');
//   if(str_val=='999')
//        document.getElementById('tr_show_hd'+str[1]).style.display='table-row';
//    else
//        document.getElementById('tr_show_hd'+str[1]).style.display='none';
//    document.getElementById('txtExtName'+str[1]).value='';
//    document.getElementById('txtExtAddress'+str[1]).value='';
//      document.getElementById('txtExtAge'+str[1]).value='';
//       document.getElementById('ddlExtState'+str[1]).value='';
//       document.getElementById('ddlExtCity'+str[1]).value='';
//       document.getElementById('txtExtName'+str[1]).style.backgroundColor='';
//    document.getElementById('txtExtAddress'+str[1]).style.backgroundColor='';
//    document.getElementById('ddlExtState'+str[1]).style.backgroundColor='';
//       document.getElementById('ddlExtCity'+str[1]).style.backgroundColor='';
}

function appendRow(tableID)
{
$('.sp_aex').prop('disabled',true);
   var table=document.getElementById(tableID);
    var rowcount=table.rows.length;
     var hd_tot=document.getElementById('hd_tot').value;
     var hd_hd_sec_id=$('#hd_hd_sec_id').val();
     var hd_hd_res_ca_nt=$('#hd_hd_res_ca_nt').val();
     var hd_n_status=$('#hd_n_status').val();
      var hd_casetype_id=$('#hd_casetype_id').val();
     $.ajax({
            url: 'add_additional_data.php',
            cache: false,
            async: true,
            data: {rowcount: rowcount,hd_tot:hd_tot,hd_hd_sec_id:hd_hd_sec_id,
                hd_hd_res_ca_nt:hd_hd_res_ca_nt,hd_n_status:hd_n_status,hd_casetype_id:hd_casetype_id},
//            beforeSend: function () {
//                $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
//            },
            type: 'POST',
            success: function(data, status) {

                $('#'+tableID).append(data);
                hd_tot++;
 document.getElementById('hd_tot').value=hd_tot;
 $('.sp_aex').prop('disabled',false);
// $('.sp_aex').prop('onclick',"appendRow('tb_ap_ck')");
               },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
//  //  rowcount=rowcount+1;
//   // alert(rowcount);
//   var hd_tot=document.getElementById('hd_tot').value;
//   //hd_tot=hd_tot+hd_tot;
//   var row1=table.insertRow(rowcount);
//   row1.setAttribute("style", "background-color: #BE9FD2");
//   var cell1=row1.insertCell(0);
//  
//   var chk_id=document.createElement('input');
//   chk_id.type='checkbox';
//    chk_id.name='chk_id'+hd_tot;
//    chk_id.id='chk_id'+hd_tot;
//    cell1.appendChild(chk_id);
//    
//    var cell2=row1.insertCell(1);
//     cell2.id='td_cell_s'+hd_tot;
//    var sp_nm=document.createElement('textarea');
//    sp_nm.name='sp_nm'+hd_tot;
//    sp_nm.id='sp_nm'+hd_tot;
//    sp_nm.setAttribute("onfocus","clear_data(this.id)");
//    sp_nm.setAttribute("style", "resize: none; width: 100%;");
//    cell2.appendChild(sp_nm);
//    
//    var hd_sr_no=document.createElement("input");
//    hd_sr_no.type='hidden';
//    hd_sr_no.id='hd_sr_no'+hd_tot;
//     hd_sr_no.name='hd_sr_no'+hd_tot;
//     hd_sr_no.setAttribute("value", "0");
//     cell2.appendChild(hd_sr_no);
//     
//      var hd_pet_res=document.createElement("input");
//    hd_pet_res.type='hidden';
//    hd_pet_res.id='hd_pet_res'+hd_tot;
//     hd_pet_res.name='hd_pet_res'+hd_tot;
//     cell2.appendChild(hd_pet_res);
//    
//    var cell3=row1.insertCell(2);
//    var sp_add=document.createElement('textarea');
//    sp_add.name='sp_add'+hd_tot;
//    sp_add.id='sp_add'+hd_tot;
//     sp_add.setAttribute("onfocus","clear_data(this.id)");
//    sp_add.setAttribute("style", "resize: none; width: 100%;");
//     cell3.appendChild(sp_add);
//     
//     var cell4=row1.insertCell(3);
//     var dv=document.createElement("div");
//     var ddlState=document.createElement('select');
//     ddlState.name='ddlState'+hd_tot;
//      ddlState.id='ddlState'+hd_tot;
//      ddlState.setAttribute("onfocus","clear_data(this.id)");
//      ddlState.setAttribute("style","width: 120px");
//      ddlState.setAttribute("onchange","getCity(this.value,this.id,'0')");
//      
//      var theOption=document.createElement("OPTION");
//  
//                var theText=document.createTextNode("Select");
//                theOption.appendChild(theText);
//                theOption.setAttribute("value","");
//                 ddlState.appendChild(theOption);
//                 getState_ex(ddlState.id);
//              
//       dv.appendChild(ddlState);
//       
//       var dv1=document.createElement('div');
//       dv1.setAttribute('style', 'margin-top: 10px');
//       var ddlcity=document.createElement('select');
//       ddlcity.name='ddlCity'+hd_tot;
//       ddlcity.id='ddlCity'+hd_tot;
//       ddlcity.setAttribute("onfocus","clear_data(this.id)");
//      ddlcity.setAttribute("style","width: 100%");
//      
//      var theOption1=document.createElement("OPTION");
//  
//                var theText1=document.createTextNode("Select");
//                theOption1.appendChild(theText1);
//                theOption1.setAttribute("value","");
//                 ddlcity.appendChild(theOption1);
//                  getcityss_ex('23',ddlState.id,'0');
//                 dv1.appendChild(ddlcity);
//       
//       cell4.appendChild(dv);
//        cell4.appendChild(dv1);
//        
//        var cell5=row1.insertCell(4); 
//         var dv_5=document.createElement("div");
//        dv_5.setAttribute("style","text-align: center");
//        var sp_mul=document.createElement("span");
//        sp_mul.id="sp_mul"+hd_tot;
//        sp_mul.name="sp_mul"+hd_tot;
//        sp_mul.setAttribute("class", "sp_c_mul");
//        sp_mul.setAttribute("onclick", "get_mul_si(this.id)");
//        sp_mul.setAttribute("style", "color: red;font-size: 9px");
//        sp_mul.innerHTML='Multiple';
//        dv_5.appendChild(sp_mul);
//       
//       var hd_hd_sec_id=document.getElementById('hd_hd_sec_id').value;
//       var hd_hd_res_ca_nt=document.getElementById('hd_hd_res_ca_nt').value;
//          
//        var ddl_nt=document.createElement('select');
//        ddl_nt.id='ddl_nt'+hd_tot;
//        ddl_nt.name='ddl_nt'+hd_tot;
//        ddl_nt.setAttribute("onfocus", "clear_data(this.id)");
//        ddl_nt.setAttribute("style", "width: 100%;");
//        ddl_nt.setAttribute("onchange", "get_wh_n(this.value,this.id)");
//        get_not_types(ddl_nt.id,hd_hd_sec_id,hd_hd_res_ca_nt);
//         cell5.appendChild(dv_5);
//        cell5.appendChild(ddl_nt);
//        
//        var cell6=row1.insertCell(5);
//        var chkOrd=document.createElement("input");
//        chkOrd.type='checkbox';
//        chkOrd.title='Ordinary';
//        chkOrd.id='chkOrd'+hd_tot;
//        chkOrd.name='chkOrd'+hd_tot;
//        chkOrd.setAttribute("onclick", "show_hd(this.id)");
//        
//        var sp_ordinary_ck=document.createElement("span");
//        sp_ordinary_ck.id='sp_ordinary_ck'+hd_tot;
//        sp_ordinary_ck.innerHTML='O';
//       var br1= document.createElement('br');
//        var br2= document.createElement('br');
//        
//        var chkReg=document.createElement("input");
//        chkReg.type='checkbox';
//        chkReg.title='Registry';
//        chkReg.id='chkReg'+hd_tot;
//        chkReg.name='chkReg'+hd_tot;
//        chkReg.setAttribute("onclick", "show_hd1(this.id)");
//        
//        var sp_reg_ck=document.createElement("span");
//        sp_reg_ck.id='sp_reg_ck'+hd_tot;
//        sp_reg_ck.innerHTML='R';
//       var br3= document.createElement('br');
//        var br4= document.createElement('br');
//        
//         var chkAdvReg=document.createElement("input");
//        chkAdvReg.type='checkbox';
//        chkAdvReg.title='Humdust';
//        chkAdvReg.id='chkAdvReg'+hd_tot;
//        chkAdvReg.name='chkAdvReg'+hd_tot;
//        chkAdvReg.setAttribute("onclick", "show_hd2(this.id)");
//        
//        var sp_adv_reg_ck=document.createElement("span");
//        sp_adv_reg_ck.id='sp_adv_reg_ck'+hd_tot;
//        sp_adv_reg_ck.innerHTML='H';
//        
//        
//        
//        
//         var br5= document.createElement('br');
//        
//         var chkAdvHum=document.createElement("input");
//        chkAdvHum.type='checkbox';
//        chkAdvHum.title='Advocate Registry';
//        chkAdvHum.id='chkAdvHum'+hd_tot;
//        chkAdvHum.name='chkAdvHum'+hd_tot;
//        chkAdvHum.setAttribute("onclick", "show_hd2(this.id)");
//        
//        var sp_hum_ck=document.createElement("span");
//        sp_hum_ck.id='sp_hum_ck'+hd_tot;
//        sp_hum_ck.innerHTML='A';
//        
//        
//        
//        cell6.appendChild(chkOrd);
//         cell6.appendChild(sp_ordinary_ck);
//          cell6.appendChild(br1);
//           // cell6.appendChild(br2);
//            
//            cell6.appendChild(chkReg);
//         cell6.appendChild(sp_reg_ck);
//          cell6.appendChild(br3);
//           // cell6.appendChild(br4);
//            
//            cell6.appendChild(chkAdvReg);
//         cell6.appendChild(sp_adv_reg_ck);
//         cell6.appendChild(br5);
//        
//         cell6.appendChild(chkAdvHum);
//          cell6.appendChild(sp_hum_ck);
//        
//        var cell7=row1.insertCell(6);
//        var txtMob=document.createElement("input");
//        txtMob.type='text';
//        txtMob.id='txtMob'+hd_tot;
//         txtMob.name='txtMob'+hd_tot;
//         txtMob.setAttribute("size", "10");
//          txtMob.setAttribute("maxlength", "10");
//        cell7.appendChild(txtMob);
//    row1.appendChild(cell1);
//     row1.appendChild(cell2);
//    row1.appendChild(cell3);
//        row1.appendChild(cell4);
//        row1.appendChild(cell5);
//           row1.appendChild(cell6);
//           row1.appendChild(cell7);
//           
//  var row2=table.insertRow(rowcount+1);
//  row2.setAttribute("style","border: 0px;border-color: white");
//  var cell_row=row2.insertCell(0);
//    cell_row.setAttribute("style","border: 0px;border-color: white");
//    cell_row.setAttribute("colspan", "7");
// var tb_tab =document.createElement("table");
// 
// tb_tab.setAttribute("style","border: 0px;border-color: white");
// var rw_tab=tb_tab.rows.length;
// var rw_tab_c=tb_tab.insertRow(rw_tab);
//  rw_tab_c.setAttribute("style","border: 0px;border-color: white");
// 
// var cell_tab=rw_tab_c.insertCell(0);
// 
// cell_tab.setAttribute("style", "border: 0px;border-color: white");
// var b_s=document.createElement('b');
// b_s.innerHTML='SendTo'
//// cell_tab.innerHTML="<b>SendTo</b>";
// cell_tab.appendChild(b_s);
// 
// var cell_tab1=rw_tab_c.insertCell(1);
//  cell_tab1.setAttribute("style", "border: 0px;border-color: white");
//  var ddlSendTo=document.createElement("select");
//  ddlSendTo.id='ddlSendTo'+hd_tot;
//  ddlSendTo.name='ddlSendTo'+hd_tot;
// ddlSendTo.setAttribute("onchange", "get_nms(this.value,this.id)");
// ddlSendTo.setAttribute("onfocus", "clear_data(this.id)");
// ddlSendTo.setAttribute("style", "width: 230px");
// 
// get_sens_to(ddlSendTo.id,'0');
// cell_tab1.appendChild(ddlSendTo);
// 
// 
// var cell_tab2=rw_tab_c.insertCell(2);
// cell_tab2.setAttribute("style", "border: 0px;border-color: white");
// cell_tab2.innerHTML="<b>CopySendTo</b>";
// 
// var cell_tab3=rw_tab_c.insertCell(3);
//  cell_tab3.setAttribute("style", "border: 0px;border-color: white");
//  var ddlSendCopyTo=document.createElement("select");
//  ddlSendCopyTo.id='ddlSendCopyTo'+hd_tot;
//  ddlSendCopyTo.name='ddlSendCopyTo'+hd_tot;
//
// ddlSendCopyTo.setAttribute("onfocus", "clear_data(this.id)");
// ddlSendCopyTo.setAttribute("style", "width: 230px");
// get_sens_to(ddlSendCopyTo.id,'1');
// cell_tab3.appendChild(ddlSendCopyTo);
// 
// var cell_tab4=rw_tab_c.insertCell(4);
// cell_tab4.setAttribute("style", "border: 0px;border-color: white");
// cell_tab4.innerHTML="<b>Amount</b>";
// 
//  var cell_tab5=rw_tab_c.insertCell(5);
//   cell_tab5.setAttribute("style", "border: 0px;border-color: white");
//   var txtAmount=document.createElement("input");
//   txtAmount.type='text';
//   txtAmount.id='txtAmount'+hd_tot;
//   txtAmount.name='txtAmount'+hd_tot;
//   txtAmount.setAttribute("onkeypress", "return OnlyNumbersTalwana(event,this.id)");
//   cell_tab5.appendChild(txtAmount);
// 
// rw_tab_c.appendChild(cell_tab);
//  rw_tab_c.appendChild(cell_tab1);
//    rw_tab_c.appendChild(cell_tab2);
//    rw_tab_c.appendChild(cell_tab3);
//     rw_tab_c.appendChild(cell_tab4);
//      rw_tab_c.appendChild(cell_tab5);
//  
// tb_tab.appendChild(rw_tab_c);
// cell_row.appendChild(tb_tab);
// row2.appendChild(cell_row);
// 
// var row3=table.insertRow(rowcount+2);
// row3.id='tr_show_hd'+hd_tot;
// row3.setAttribute("style", "display: none;");
// var cells_1=row3.insertCell(0);
// cells_1.setAttribute("colspan", '7');
//var b_za=document.createElement("b");
//b_za.innerHTML='Name';
//
//var txtExtName=document.createElement('input');
//txtExtName.type='text';
//txtExtName.id='txtExtName'+hd_tot;
//txtExtName.name='txtExtName'+hd_tot;
//txtExtName.setAttribute("onfocus", "clear_data(this.id)");
//txtExtName.setAttribute("style", "width: 150px;margin:0px 5px 0px 5px");
//
//var b_za1=document.createElement("b");
//b_za1.innerHTML='Address';
//
//var txtExtAddress=document.createElement('input');
//txtExtAddress.type='text';
//txtExtAddress.id='txtExtAddress'+hd_tot;
//txtExtAddress.name='txtExtAddress'+hd_tot;
//txtExtAddress.setAttribute("onfocus", "clear_data(this.id)");
//txtExtAddress.setAttribute("style", "width: 200px;margin:0px 5px 0px 5px");
//
//
//var b_za2=document.createElement("b");
//b_za2.innerHTML='Age';
//
//var txtExtAge=document.createElement('input');
//txtExtAge.type='text';
//txtExtAge.id='txtExtAge'+hd_tot;
//txtExtAge.name='txtExtAge'+hd_tot;
//txtExtAge.setAttribute("maxlength", "3");
//txtExtAge.setAttribute("style", "width: 40px;margin:0px 5px 0px 5px");
//
//
//var b_za3=document.createElement("b");
//b_za3.innerHTML='State';
//
//var ddlExtState=document.createElement('select');
//
//ddlExtState.id='ddlExtState'+hd_tot;
//ddlExtState.name='ddlExtState'+hd_tot;
//ddlExtState.setAttribute("onfocus", "clear_data(this.id)");
//ddlExtState.setAttribute("onchange", "getCity(this.value,this.id,'1')");
//ddlExtState.setAttribute("style", "width: 100px;margin:0px 5px 0px 5px");
//getState_ex(ddlExtState.id);
//
//var b_za4=document.createElement("b");
//b_za4.innerHTML='District';
//
//var ddlExtCity=document.createElement('select');
//
// var theOptions=document.createElement("OPTION");
//  
//                var theTexts=document.createTextNode("Select");
//                theOptions.appendChild(theTexts);
//                theOptions.setAttribute("value","");
//                 ddlExtCity.appendChild(theOptions);
//
//ddlExtCity.id='ddlExtCity'+hd_tot;
//ddlExtCity.name='ddlExtCity'+hd_tot;
//ddlExtCity.setAttribute("onfocus", "clear_data(this.id)");
//
//ddlExtCity.setAttribute("style", "width: 70px;margin:0px 5px 0px 5px");
//
//
//
//
//
//cells_1.appendChild(b_za);
//cells_1.appendChild(txtExtName);
//cells_1.appendChild(b_za1);
//cells_1.appendChild(txtExtAddress);
//cells_1.appendChild(b_za2);
//cells_1.appendChild(txtExtAge);
//cells_1.appendChild(b_za3);
//cells_1.appendChild(ddlExtState);
//cells_1.appendChild(b_za4);
//cells_1.appendChild(ddlExtCity);
//
//row3.appendChild(cells_1);
//
// var row4=table.insertRow(rowcount+3);
// row4.setAttribute("style", "border: 0px;border-color: white");
// var cll=row4.insertCell(0);
//  cll.setAttribute("style", "border: 0px;border-color: white");
//  cll.setAttribute("valign", "middle");
//   cll.setAttribute("colspan", "9");
//   var dv_s=document.createElement("div");
//   dv_s.setAttribute("style", "float: left;padding-top: 20px");
//   var bd_dv_s=document.createElement('b');
//   bd_dv_s.innerHTML='Note';
//   dv_s.appendChild(bd_dv_s);
//   
//    var dv_s1=document.createElement("div");
//    var txtNote=document.createElement('textarea');
//    txtNote.id='txtNote'+hd_tot;
//    txtNote.name='txtNote'+hd_tot;
//    txtNote.setAttribute("style", "resize:none;width: 80%;height: 35px;overflow-y: scroll");
//   dv_s1.appendChild(txtNote);
//   
//   var rdnChkPet=document.createElement("input");
//   rdnChkPet.type='radio';
//   rdnChkPet.id='rdnChkPet'+hd_tot;
//   rdnChkPet.title="Petitioner";
//   rdnChkPet.setAttribute("onclick", "show_hd_x(this.id)");
//   rdnChkPet.name='rdnChkPet_res'+hd_tot;
//   dv_s1.appendChild(rdnChkPet);
//   var sp_ChkPet=document.createElement("span");
//   sp_ChkPet.id="sp_ChkPet_P"+hd_tot;
//   sp_ChkPet.innerHTML='P';
//   dv_s1.appendChild(sp_ChkPet);
////   var br_z=document.createElement("br");
////   dv_s1.appendChild(br_z);
//   
//   var rdnChkRes=document.createElement("input");
//   rdnChkRes.type='radio';
//   rdnChkRes.id='rdnChkRes'+hd_tot;
//    rdnChkRes.title="Respondent";
//      rdnChkRes.setAttribute("onclick", "show_hd_x1(this.id)");
//   rdnChkRes.name='rdnChkPet_res'+hd_tot;
//   dv_s1.appendChild(rdnChkRes);
//   
//    var sp_ChkRes=document.createElement("span");
//    sp_ChkRes.id="sp_sp_ChkRes_R"+hd_tot;
//   sp_ChkRes.innerHTML='R';
//   dv_s1.appendChild(sp_ChkRes);
//   
//   
//   
//   var rdnChkOth=document.createElement("input");
//   rdnChkOth.type='radio';
//   rdnChkOth.id='rdnChkOth'+hd_tot;
//    rdnChkOth.title="Other";
//      rdnChkOth.setAttribute("onclick", "show_hd_x2(this.id)");
//   rdnChkOth.name='rdnChkPet_res'+hd_tot;
//   dv_s1.appendChild(rdnChkOth);
//   
//    var sp_ChkOth=document.createElement("span");
//    sp_ChkOth.id="sp_sp_ChkOth_R"+hd_tot;
//   sp_ChkOth.innerHTML='O';
//   dv_s1.appendChild(sp_ChkOth);
//   
//   
//   
//   var hd_new_upd=document.createElement("input");
//   hd_new_upd.type='hidden';
//   hd_new_upd.id='hd_new_upd'+hd_tot;
//   hd_new_upd.name='hd_new_upd'+hd_tot;
//   hd_new_upd.setAttribute("value", '0');
//   
//   var hd_mn_id=document.createElement("input");
//   hd_mn_id.type='hidden';
//   hd_mn_id.id='hd_mn_id'+hd_tot;
//   hd_mn_id.name='hd_mn_id'+hd_tot;
//   hd_mn_id.setAttribute("value", '');
//   
//    dv_s1.appendChild(hd_new_upd);
//     dv_s1.appendChild(hd_mn_id);
//   
//   cll.appendChild(dv_s);
//   cll.appendChild(dv_s1);
//   row4.appendChild(cll);
 
 
      
}

function appendRow_hc(tableID)
{
$('.sp_aex_hc').prop('disabled',true);
   var table=document.getElementById(tableID);
    var rowcount=table.rows.length;
     var hd_tot=document.getElementById('hd_tot').value;
     var hd_hd_sec_id=$('#hd_hd_sec_id').val();
     var hd_hd_res_ca_nt=$('#hd_hd_res_ca_nt').val();
     var hd_n_status=$('#hd_n_status').val();
      var hd_casetype_id=$('#hd_casetype_id').val();
     $.ajax({
            url: 'add_additional_data_hc.php',
            cache: false,
            async: true,
            data: {rowcount: rowcount,hd_tot:hd_tot,hd_hd_sec_id:hd_hd_sec_id,
                hd_hd_res_ca_nt:hd_hd_res_ca_nt,hd_n_status:hd_n_status,hd_casetype_id:hd_casetype_id},
//            beforeSend: function () {
//                $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
//            },
            type: 'POST',
            success: function(data, status) {

                $('#'+tableID).append(data);
             var hd_tot_hcs=  $('#hd_tot_hcs').val();
 document.getElementById('hd_tot').value=hd_tot_hcs;
 $('.sp_aex_hc').prop('disabled',false);
// $('.sp_aex').prop('onclick',"appendRow('tb_ap_ck')");
               },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }


function get_sens_to(ddlSendTo,str)
{
          var xmlhttpp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
            
                xmlhttpp.onreadystatechange=function()
                {
                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
                    {
                        if(str=='0')
                        document.getElementById(ddlSendTo).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText+"<option value='0'>None</option>"+"<option value='999'>Other</option>";
                        else if(str=='1')
                        document.getElementById(ddlSendTo).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText+"<option value='0'>None</option>";     
              
                        var hd_hd_sec_id=document.getElementById('hd_hd_sec_id').value;
                        if(hd_hd_sec_id=='4' || hd_hd_sec_id=='5' || hd_hd_sec_id=='6' || hd_hd_sec_id=='7' 
                            || hd_hd_sec_id=='8' || hd_hd_sec_id=='9' || hd_hd_sec_id=='10' || hd_hd_sec_id=='14' || hd_hd_sec_id=='15' || hd_hd_sec_id=='16' || hd_hd_sec_id=='17' || hd_hd_sec_id=='18' || hd_hd_sec_id=='19' || hd_hd_sec_id=='20' || hd_hd_sec_id=='21' || hd_hd_sec_id=='22' || hd_hd_sec_id=='23' || hd_hd_sec_id=='24' || hd_hd_sec_id=='25' || hd_hd_sec_id=='26' || hd_hd_sec_id=='11' || hd_hd_sec_id=='27' || hd_hd_sec_id=='13' || hd_hd_sec_id=='1' || hd_hd_sec_id=='28')
                           {
                                document.getElementById(ddlSendTo).value=0;
                            }
                    }
                }
                xmlhttpp.open("GET","get_sens_to.php",true);
                xmlhttpp.send(null);
}



function  get_not_types(ddl_nt,hd_hd_sec_id,hd_hd_res_ca_nt)
{
   var hd_c_stat=document.getElementById('hd_c_stat').value;
   var xmlhttpp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
            
                xmlhttpp.onreadystatechange=function()
                {
                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
                    {
                     
                        document.getElementById(ddl_nt).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText;
                    }
                }
                xmlhttpp.open("GET","get_notices.php?hd_hd_sec_id="+hd_hd_sec_id+"&hd_hd_res_ca_nt="+hd_hd_res_ca_nt+"&hd_c_stat="+hd_c_stat,true);
                xmlhttpp.send(null);
}

function getState_ex(ddlState)
{
          var xmlhttpp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
            
                xmlhttpp.onreadystatechange=function()
                {
                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
                    {
                     
                        document.getElementById(ddlState).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText+"<option value='0'>None</option>";
                   
               }
                }
                xmlhttpp.open("GET","getState.php",true);
                xmlhttpp.send(null);
}


function numToString(x)
{
var r=0;
var txter=x;
var sizer=txter.length;
var numStr="";
if(isNaN(txter))
{
alert(" Invalid number");
exit();
}
var n=parseInt(x);
var places=0;
var str="";
var entry=0;
while(n>=1)
{
r=parseInt(n%10);

if(places<3 && entry==0)
{
numStr=txter.substring(txter.length-0,txter.length-3) // Checks for 1 to 999.
str=onlyDigit(numStr); //Calls function for last 3 digits of the value.
entry=1;
}

if(places==3)
{
 numStr=txter.substring(txter.length-5,txter.length-3) 
 if(numStr!="")
 {
  str=onlyDigit(numStr)+ " Thousand "+str;
 }
}

if(places==5)
{
 numStr=txter.substring(txter.length-7,txter.length-5) //Substring for 5 place to 7 place of the string
 if(numStr!="")
 {
  str=onlyDigit(numStr)+ " Lakhs "+str; //Appends the word lakhs to it
 }
}

if(places==6)
{
 numStr=txter.substring(txter.length-9,txter.length-7)  //Substring for 7 place to 8 place of the string
 if(numStr!="")
 {
  str=onlyDigit(numStr)+ " Crores "+str;        //Appends the word Crores
 }
}

n=parseInt(n/10);
places++;
}
return str;
//alert(str);
}

function onlyDigit(n)
{
//Arrays to store the string equivalent of the number to convert in words
var units=['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine'];
var randomer=['','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
var tens=['','Ten','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
var r=0;
var num=parseInt(n);
var str="";
var pl="";
var tenser="";
while(num>=1)
{
r=parseInt(num%10);
tenser=r+tenser;
 if(tenser<=19 && tenser>10) //Logic for 10 to 19 numbers
 {
 str=randomer[tenser-10];
 }
 else
 {
  if(pl==0)        //If units place then call units array.
  {
  str=units[r];
  }
  else if(pl==1)    //If tens place then call tens array.
  {
  str=tens[r]+" "+str;
  }
 }
 if(pl==2)        //If hundreds place then call units array.
 {
 str=units[r]+" Hundred "+str;
 }
 
num=parseInt(num/10);
pl++;
}
return str;
}

function ck_mul_rem(d_no,d_yr)
{
    var xmlhttpp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
            
                xmlhttpp.onreadystatechange=function()
                {
                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
                    {
                     
                      //  document.getElementById(ddlState).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText;
                    }
                }
                xmlhttpp.open("GET","get_ck_mul_rem.php?d_no="+d_no+'&d_yr='+d_yr,true);
                xmlhttpp.send(null);
}

function getColor(str)
{
  
  for(var i=1;i<=6;i++)
       {
             if(str==('sp_app_rs'+i) || str==('sp_app_pai'+i) || str==('sp_res_rs'+i) || str==('sp_rs_pai'+i))
                 {
                    if(str==('sp_app_rs'+i))
                        {
                    document.getElementById('sp_app_rs'+i).style.backgroundColor='#cccccc';
                    if(document.getElementById('sp_app_rs'+i).value=='00')
                    document.getElementById('sp_app_rs'+i).value='';
                     document.getElementById('sp_app_pai'+i).style.backgroundColor='';
                document.getElementById('sp_res_rs'+i).style.backgroundColor='';
                  document.getElementById('sp_rs_pai'+i).style.backgroundColor='';
                        }
                        else if(str==('sp_app_pai'+i))
                            {
                                 document.getElementById('sp_app_rs'+i).style.backgroundColor='';
                     document.getElementById('sp_app_pai'+i).style.backgroundColor='#cccccc';
                       if(document.getElementById('sp_app_pai'+i).value=='00')
                     document.getElementById('sp_app_pai'+i).value='';
                document.getElementById('sp_res_rs'+i).style.backgroundColor='';
                  document.getElementById('sp_rs_pai'+i).style.backgroundColor='';
                            }
                             else if(str==('sp_res_rs'+i))
                            {
                                 document.getElementById('sp_app_rs'+i).style.backgroundColor='';
                     document.getElementById('sp_app_pai'+i).style.backgroundColor='';
                document.getElementById('sp_res_rs'+i).style.backgroundColor='#cccccc';
                 if(document.getElementById('sp_res_rs'+i).value=='00')
                 document.getElementById('sp_res_rs'+i).value='';
                  document.getElementById('sp_rs_pai'+i).style.backgroundColor='';
                            }
                             else if(str==('sp_rs_pai'+i))
                            {
                                 document.getElementById('sp_app_rs'+i).style.backgroundColor='';
                     document.getElementById('sp_app_pai'+i).style.backgroundColor='';
                document.getElementById('sp_res_rs'+i).style.backgroundColor='';
                  document.getElementById('sp_rs_pai'+i).style.backgroundColor='#cccccc';
                   if(document.getElementById('sp_rs_pai'+i).value=='00')
                     document.getElementById('sp_rs_pai'+i).value='';
                            }
                 }
                 else
                     {
             document.getElementById('sp_app_rs'+i).style.backgroundColor='';
              document.getElementById('sp_app_pai'+i).style.backgroundColor='';
                document.getElementById('sp_res_rs'+i).style.backgroundColor='';
                  document.getElementById('sp_rs_pai'+i).style.backgroundColor='';
                     }
       }
  
}

function chk_bnk(str)
{
//   alert(document.getElementById(str).innerHTML);
   if(document.getElementById(str).value=='')
       {
        document.getElementById(str).setAttribute("value", "00");
       document.getElementById(str).value='00';
       }
    else if(document.getElementById(str).value.length==1)
        document.getElementById(str).value='0'+document.getElementById(str).value;
}


function addDetails(str)
{

 document.getElementById(str).setAttribute("value", document.getElementById(str).value);

 var en_rs=0;

 for(var i=1;i<=6;i++)
       {
           
var ck_ze=  document.getElementById('sp_app_rs'+i).value;
  //alert(ck_ze);
if(ck_ze=='')
    ck_ze=0;
 
//     en_rs+=Math.round(parseFloat(parseFloat(ck_ze)+'.'+
//         parseFloat(document.getElementById('sp_app_pai'+i).value))*10*10)/100;
//  en_rs+=Math.round((parseFloat((parseFloat(ck_ze))+'.'+
//         parseFloat(document.getElementById('sp_app_pai'+i).value))*100).toFixed(2))/100;
   var nc=document.getElementById('sp_app_pai'+i).value.length;
if(nc==0)
    var xc='00';
else if(nc==1)
    var xc='0'+document.getElementById('sp_app_pai'+i).value;
else
    var xc=document.getElementById('sp_app_pai'+i).value;

// en_rs+=parseFloat((parseInt(ck_ze).toString())+'.'+
//         xc,2);

en_rs+= Math.round((parseFloat((ck_ze+"."+ xc)))*100)/100;
//en_rs+= parseFloat((ck_ze+'.'+ xc).p);
 }
// alert(ck_ze+'.'+ xc);
 //alert(en_rs);
//
var add_en_pai=Math.round(en_rs*100)/100;
var add_en_pai=add_en_pai.toString();

   var patt=new RegExp(".");
   var e=patt.test(add_en_pai);
  
   if(e==true)
       {
           var dec_sp=add_en_pai.split('.');
            document.getElementById('sp_app_rs_res').innerHTML=dec_sp[0];
            if(dec_sp[1]=='0')
                dec_sp[1]='00';
            else if(dec_sp[1]==undefined)
             dec_sp[1]='00';
            document.getElementById('sp_app_pai_res').innerHTML=dec_sp[1];
       }
       else
           {
             document.getElementById('sp_app_rs_res').innerHTML=add_en_pai;
             document.getElementById('sp_app_pai_res').innerHTML='00';
           }

       }
       
       
       function addDetails1(str)
{
  document.getElementById(str).setAttribute("value", document.getElementById(str).value);
 var en_rs=0;

 for(var i=1;i<=6;i++)
       {
           
var ck_ze=  document.getElementById('sp_res_rs'+i).value;
if(ck_ze=='')
    ck_ze=0;
 
//     en_rs+=parseFloat(parseFloat(ck_ze)+'.'+
//         parseFloat(document.getElementById('sp_rs_pai'+i).value));
  
//  en_rs+=Math.round((parseFloat((parseFloat(ck_ze))+'.'+
//         parseFloat(document.getElementById('sp_rs_pai'+i).value))*100).toFixed(2))/100;
var nc=document.getElementById('sp_rs_pai'+i).value.length;
if(nc==1)
    var xc='0'+document.getElementById('sp_rs_pai'+i).value;
else
    var xc=document.getElementById('sp_rs_pai'+i).value;

// en_rs+=parseFloat((parseInt(ck_ze).toString())+'.'+
//         xc,2);
en_rs+= Math.round((parseFloat((ck_ze+"."+ xc)))*100)/100;

     // alert(en_rs) ;
    }
 
  var add_en_pai=Math.round(en_rs*100)/100;
var add_en_pai=add_en_pai.toString();

   var patt=new RegExp(".");
   var e=patt.test(add_en_pai);
  
   if(e==true)
       {
           var dec_sp=add_en_pai.split('.');
            document.getElementById('sp_res_rs_res').innerHTML=dec_sp[0];
            if(dec_sp[1]=='0')
                dec_sp[1]='00';
            else if(dec_sp[1]==undefined)
             dec_sp[1]='00';
            document.getElementById('sp_rs_pai_res').innerHTML=dec_sp[1];
       }
       else
           {
             document.getElementById('sp_res_rs_res').innerHTML=add_en_pai;
             document.getElementById('sp_rs_pai_res').innerHTML='00';
           }

       }
       
       function get_sp_cc(str)
       {
//        alert("sdsdsdsd");
          if(str=='sp_cc_no')
               {
                   document.getElementById('sp_cc_no_s').innerHTML=
                       document.getElementById('sp_cc_no').innerHTML;
               }
       }
       
     
//       function get_wh_p_r(str,idd)
//       {
//           var idd_split=idd.split("ddl_nt");          
//           if(str=='269')
//                      {
//               
//               document.getElementById('sp_nm'+idd_split[1]).innerHTML='';
//                   document.getElementById('sp_add'+idd_split[1]).innerHTML='';
//                   
//                    var sp_enroll=document.createElement("span");
//                     sp_enroll.id="sp_enroll"+idd_split[1];
//        sp_enroll.innerHTML='No.';
//                  
//                  var in_enroll=document.createElement("input");
//                   in_enroll.type='text';
//                   in_enroll.setAttribute('size', '2');
//                     in_enroll.setAttribute('maxlength', '6');
//                   in_enroll.id='hdinenroll_'+idd_split[1];
//                   in_enroll.name='hdinenroll_'+idd_split[1];
//                   in_enroll.setAttribute('onfocus', 'clear_data(this.id)'); 
//                 
//                  
//                   
//                      var sp_enroll_yr=document.createElement("span");
//                      sp_enroll_yr.innerHTML='Yr';
//                   sp_enroll_yr.id="sp_enrollyr"+idd_split[1];
////                    var sp_ce=document.createElement("label");
//                   
//                    var in_enroll_yr=document.createElement("input");
//                   in_enroll_yr.type='text';
//                   in_enroll_yr.setAttribute('size', '2');
//                     in_enroll_yr.setAttribute('maxlength', '4');
//                      in_enroll_yr.setAttribute('onblur', 'get_eroll_yr(this.id)');
//                   in_enroll_yr.id='hdinenrollyr_'+idd_split[1];
//                   in_enroll_yr.name='hdinenrollyr_'+idd_split[1];
//                 in_enroll_yr.setAttribute('onfocus', 'clear_data(this.id)'); 
//                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(sp_enroll); 
//                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(in_enroll);
//                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(sp_enroll_yr); 
//                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(in_enroll_yr);
//                   
//                   document.getElementById('sp_nm'+idd_split[1]).disabled=true;
//                    document.getElementById('sp_add'+idd_split[1]).disabled=true;
//                      }
//                   else 
//                       {
//                            if(str=='107'  || str=='63' || str=='67' || str=='72' || str=='80' || str=='116' || str=='122'
//                     || str=='128' || str=='134' || str=='140' || str=='146' || str=='154' || str=='160' || str=='166' || str=='172'
//                     || str=='178' || str=='184' || str=='190' || str=='239' || str=='257' || str=='261')
//                             {
//                                
//                                 $('#ddlSendTo'+idd_split[1]).val('11');
//                                 $('#ddlSendCopyTo'+idd_split[1]).val('0');
//                                 
//                             }
//                             else if( str=='18')
//                                 {
//                                      $('#ddlSendTo'+idd_split[1]).val('8');
//                                 $('#ddlSendCopyTo'+idd_split[1]).val('0');
//                                 }
//               
//                         $('#hdinenroll_'+idd_split[1]).remove();
//                         $('#hdinenrollyr_'+idd_split[1]).remove();
//                         $('#sp_enroll'+idd_split[1]).remove();
//                         $('#sp_enrollyr'+idd_split[1]).remove();
//                         document.getElementById('sp_nm'+idd_split[1]).disabled=false;
//                    document.getElementById('sp_add'+idd_split[1]).disabled=false;
//                   
//                       }
//       }
       
       
       function get_wh_n(str,idd)
       {
           var idd_split=idd.split("ddl_nt");
           if(str=='87' || str=='96' || str=='99' || str=='100' || str=='91' || str=='86' || str=='90' || str=='98' || str=='74' || str=='97' || str=='60' || str=='85' || str=='106' || str=='55' || str=='109' || str=='118' || str=='124' || str=='130' || str=='136' || str=='142' || str=='148' || str=='156' || str=='162' || str=='168' || str=='174' || str=='180' || str=='186' || str=='192' || str=='193' || str=='194' || str=='197' || str=='202' || str=='205' || str=='204'  || str=='56' || str=='209' || str=='214' || str=='212'  || str=='219' || str=='241' || str=='259' || str=='218' || str=='265' || str=='266' || str=='269' || str=='272' || str=='276' || str=='277' || str=='281' || str=='282' || str=='289' || str=='292' || str=='293' || str=='294') 
               {
               //  alert(str);
                 if(str=='269')
                      {
                          document.getElementById('sp_nm'+idd_split[1]).innerHTML='';
                   document.getElementById('sp_add'+idd_split[1]).innerHTML='';
                   
                   document.getElementById('sp_nm'+idd_split[1]).value='';
                   document.getElementById('sp_add'+idd_split[1]).value='';
                   
                    var sp_enroll=document.createElement("span");
                     sp_enroll.id="sp_enroll"+idd_split[1];
        sp_enroll.innerHTML='No.';
                  
                  var in_enroll=document.createElement("input");
                   in_enroll.type='text';
                   in_enroll.setAttribute('size', '2');
                     in_enroll.setAttribute('maxlength', '6');
                   in_enroll.id='hdinenroll_'+idd_split[1];
                   in_enroll.name='hdinenroll_'+idd_split[1];
                   in_enroll.setAttribute('onfocus', 'clear_data(this.id)'); 
                    in_enroll.setAttribute('onblur', 'cl_bar(this.id)'); 
                   
                      var sp_enroll_yr=document.createElement("span");
                      sp_enroll_yr.innerHTML='Yr';
                   sp_enroll_yr.id="sp_enrollyr"+idd_split[1];
//                    var sp_ce=document.createElement("label");
                   
                    var in_enroll_yr=document.createElement("input");
                   in_enroll_yr.type='text';
                   in_enroll_yr.setAttribute('size', '2');
                     in_enroll_yr.setAttribute('maxlength', '4');
                      in_enroll_yr.setAttribute('onblur', 'get_eroll_yr(this.id)');
                   in_enroll_yr.id='hdinenrollyr_'+idd_split[1];
                   in_enroll_yr.name='hdinenrollyr_'+idd_split[1];
                 in_enroll_yr.setAttribute('onfocus', 'clear_data(this.id)'); 
                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(sp_enroll); 
                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(in_enroll);
                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(sp_enroll_yr); 
                    document.getElementById('td_cell_s'+idd_split[1]).appendChild(in_enroll_yr);
                   
                   document.getElementById('sp_nm'+idd_split[1]).disabled=true;
                    document.getElementById('sp_add'+idd_split[1]).disabled=true;
                    
                      }
                    else
                        {
                  document.getElementById('sp_nm'+idd_split[1]).innerHTML='N';
                   document.getElementById('sp_add'+idd_split[1]).innerHTML='N';
                   
                    document.getElementById('sp_nm'+idd_split[1]).value='N';
                   document.getElementById('sp_add'+idd_split[1]).value='N';
                   
                        }
                     
                    document.getElementById('ddlState'+idd_split[1]).value=0;
                    document.getElementById('ddlCity'+idd_split[1]).innerHTML+="<option value='0'>None</option>";
                    document.getElementById('ddlCity'+idd_split[1]).value=0;
                     document.getElementById('ddlSendCopyTo'+idd_split[1]).value=0;
                      document.getElementById('rdnChkOth'+idd_split[1]).checked=true;
                      document.getElementById('chkOrd'+idd_split[1]).checked=true;
                       document.getElementById('hd_pet_res'+idd_split[1]).value='Z';
                      
             if(str=='96' || str=='99'  || str=='91' || str=='86' || str=='90'  || str=='74' || str=='60' || str=='85' || str=='109' || str=='118'  || str=='124' || str=='130' || str=='136' || str=='142' || str=='148' || str=='156' || str=='162' || str=='168' || str=='174' || str=='180' || str=='186' || str=='192' || str=='193' || str=='194' || str=='197' || str=='202' || str=='205' || str=='204' || str=='209' || str=='214' || str=='212' || str=='241' || str=='259' || str=='218' || str=='265' || str=='266' || str=='269' || str=='272' || str=='276' || str=='277' || str=='281' || str=='282' || str=='289')
                    {
                        
                        document.getElementById('ddlSendTo'+idd_split[1]).value=0;
                    }
                    else
                        {
                            
                            document.getElementById('ddlSendTo'+idd_split[1]).value='';
                        }
               }
               else
                   {
                       document.getElementById('sp_nm'+idd_split[1]).innerHTML='';
                   document.getElementById('sp_add'+idd_split[1]).innerHTML='';
                   
                    document.getElementById('sp_nm'+idd_split[1]).value='';
                   document.getElementById('sp_add'+idd_split[1]).value='';
                  if(document.getElementById('ddlState'+idd_split[1]).value=='0')
                      {
                  document.getElementById('ddlState'+idd_split[1]).value='';
                   
                      }
                      if( document.getElementById('ddlCity'+idd_split[1]).value=='0')
                          {
                    document.getElementById('ddlCity'+idd_split[1]).value='';
                     document.getElementById('ddlCity'+idd_split[1]).innerHTML="<option value=''>Select</option>";
                          }
                     document.getElementById('ddlSendCopyTo'+idd_split[1]).value='';
                      document.getElementById('rdnChkOth'+idd_split[1]).checked=false;
                      document.getElementById('chkOrd'+idd_split[1]).checked=false;
                       document.getElementById('ddlSendTo'+idd_split[1]).value='';
                         document.getElementById('hd_pet_res'+idd_split[1]).value='';
                         
                         
                   }
                   
                   if(str!='269')
                       {
                           $('#hdinenroll_'+idd_split[1]).remove();
                         $('#hdinenrollyr_'+idd_split[1]).remove();
                         $('#sp_enroll'+idd_split[1]).remove();
                         $('#sp_enrollyr'+idd_split[1]).remove();
                         document.getElementById('sp_nm'+idd_split[1]).disabled=false;
                    document.getElementById('sp_add'+idd_split[1]).disabled=false;
                    
                       }
       }
       
//       var ct_co_cas=0;
//       function get_co_cas()
//       {
//          var mn=document.getElementById('mn').value;
//    var cs_tp=document.getElementById('cs_tp').value;
//    var cs_tp_x=cs_tp;
//    if(cs_tp.length==2)
//        {
//            cs_tp='0'+cs_tp;
//        }
//    var txtFNo=document.getElementById('txtFNo').value;
//    var txtYear=document.getElementById('txtYear').value;
//    var filling_no=mn+cs_tp+txtFNo+txtYear;
//          
//         
//          var xmlhttpp;
//                if (window.XMLHttpRequest)
//                {// code for IE7+, Firefox, Chrome, Opera, Safari
//                    xmlhttpp=new XMLHttpRequest();
//                }
//                else
//                {// code for IE6, IE5
//                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
//                }
//                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
//            
//                xmlhttpp.onreadystatechange=function()
//                {
//                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
//                    {
//                     
//                     
//                      //  document.getElementById(ddlState).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText;
//                    }
//                }
//                xmlhttpp.open("GET","get_conn_cases.php?filling_no="+filling_no,true);
//                xmlhttpp.send(null);
//       }

function sv_up_ten_dt(str,d_no,d_yr)
{
   var hd_hd_mn_con=0;

   if(document.getElementById('hd_hd_mn_con'))
      hd_hd_mn_con= document.getElementById('hd_hd_mn_con').value;

   var xmlhttpp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttpp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                // document.getElementById('Data').innerHTML = '<table widht="100%" align="center"><tr><td><img src="loading.gif"/></td></tr></table>';
            
                xmlhttpp.onreadystatechange=function()
                {
                    if (xmlhttpp.readyState==4 && xmlhttpp.status==200)
                    {
                     
                      //  document.getElementById(ddlState).innerHTML="<option value=''>Select</option>"+xmlhttpp.responseText;
                    }
                }
                xmlhttpp.open("GET","get_sv_up_ten_dt.php?str="+str+"&d_no="+d_no+'&d_yr='+d_yr+"&hd_hd_mn_con="+hd_hd_mn_con,true);
                xmlhttpp.send(null);
}

function fin_rep()
{
 // document.execCommand("insertHTML", false, "<span class='sp_12344_s'>"+ document.getSelection()+"</span>");
  // var txtFind = document.getElementById('txtFind').value;
   // var rex = /(<([^>]+)>)/ig;
   // alert(txtFind.replace(rex , ""));
   // var cc=document.getSelection().getRangeAt(0);
  //  var selectionContents = cc.extractContents();
 //   selectionContents=selectionContents.outerHTML;
//    // extractContents
//  
  // alert(selectionContents);
  
//   var div = document.createElement("div");
//div.style.color = "yellow";
//div.appendChild(selectionContents);
//cc.insertNode(div);
   var txtFind=document.getElementById('txtFind').value;
   // alert(txtFind);
//    var txtFind = txtFind.replace(/<([^>]+)>/ig,"");

    var txtReplace=document.getElementById('txtReplace').value;
//     document.execCommand('copy', false ,null);
//     document.execCommand('paste', false ,txtReplace);
    var ggg=document.getElementById('ggg').innerHTML;
  //  var n=ggg.search('/'+txtFind+'/i');
 var query = new RegExp("(\\b" + txtFind + "\\b)", "gim");
   var ff=ggg.replace(query, txtReplace);
   document.getElementById('ggg').innerHTML=ff;
   // document.getElementById('ggg').focus();
}

//function fin_find()
//{
//    var txtFind=document.getElementById('txtFind').value;
//      var ggg=document.getElementById('ggg').innerHTML;
//}

//function fin_find() {
//   var text = document.getElementById("txtFind").value;
//    var query = new RegExp("(\\b" + text + "\\b)", "gim");
//    var e = document.getElementById("ggg").innerHTML;
//   // var e = e.replace(/<([^>]+)>/ig,"");
//   // var enew = e.replace(/(<span>|<\/span>)/igm, "");
//   var enew = e.replace(/(<span>|<\/span>)/igm, "");
//   
//    document.getElementById("ggg").innerHTML = enew;
//    var newe = enew.replace(query, "<span class='sp_dd'>$1</span>");
//    document.getElementById("ggg").innerHTML = newe;
//
//
//}

function fin_rep()
{
 // document.execCommand("insertHTML", false, "<span class='sp_12344_s'>"+ document.getSelection()+"</span>");
  // var txtFind = document.getElementById('txtFind').value;
   // var rex = /(<([^>]+)>)/ig;
   // alert(txtFind.replace(rex , ""));
  // document.execCommand('cut', false ,null);
   
    var cc=document.getSelection().getRangeAt(0);
    var selectionContents = cc.cloneContents();
 // var selectionContents = cc.extractContents();
    var div = document.createElement('div');
          div.appendChild(selectionContents);
 //   selectionContents=selectionContents.outerHTML;
//    // extractContents
// alert(div.textContent);
 if(div.innerHTML!='')
     {
var selectionContents1=encodeURIComponent(div.innerHTML.toString());
  // selectionContents1=selectionContents1.trim();
  
  
//   var div = document.createElement("div");
//div.style.color = "yellow";
//div.appendChild(selectionContents);
//cc.insertNode(div);
  // var txtFind=document.getElementById('txtFind').value;
   // alert(txtFind);
//    var txtFind = txtFind.replace(/<([^>]+)>/ig,"");

    var txtReplace=document.getElementById('txtReplace').value.toString();
//     document.execCommand('copy', false ,null);
//     document.execCommand('paste', false ,txtReplace);
 
  var ggg=encodeURIComponent(document.getElementById('ggg').innerHTML.toString());
    
   // alert(ggg);
  //  var n=ggg.search('/'+txtFind+'/i');
 //  ggg = new RegExp("(\\b" + ggg + "\\b)", "gim");
  // alert(ggg);
 var query = new RegExp( selectionContents1, "gim");
 //var query = new RegExp( selectionContents1, "gim");
//var query = new RegExp( selectionContents1, "gim");
  // alert(query);
//   var bbb=query.toString();
//    query1=bbb.replace(/<\//gim,"</");
//   alert(query1);
   var ff=ggg.replace(query, txtReplace);
  ff1= decodeURIComponent(ff);
   document.getElementById('ggg').innerHTML=ff1;
   // document.getElementById('ggg').focus();
}
}

function gt_redo()
{
    document.execCommand('undo', false ,null);
}

function fin_rep1()
{
 // document.execCommand("insertHTML", false, "<span class='sp_12344_s'>"+ document.getSelection()+"</span>");
  // var txtFind = document.getElementById('txtFind').value;
   // var rex = /(<([^>]+)>)/ig;
   // alert(txtFind.replace(rex , ""));
  // document.execCommand('cut', false ,null);
 
    var cc=document.getSelection().getRangeAt(0);
    var selectionContents = cc.cloneContents();
 // var selectionContents = cc.extractContents();
    var div = document.createElement('div');
          div.appendChild(selectionContents);
 //   selectionContents=selectionContents.outerHTML;
//    // extractContents
 alert(div.textContent);
 if(div.textContent!='')
     {
var selectionContents1=encodeURIComponent(div.textContent.toString());
  // selectionContents1=selectionContents1.trim();
  
  
//   var div = document.createElement("div");
//div.style.color = "yellow";
//div.appendChild(selectionContents);
//cc.insertNode(div);
  // var txtFind=document.getElementById('txtFind').value;
   // alert(txtFind);
//    var txtFind = txtFind.replace(/<([^>]+)>/ig,"");

    var txtReplace=document.getElementById('txtReplace').value.toString();
//     document.execCommand('copy', false ,null);
//     document.execCommand('paste', false ,txtReplace);
    var ggg=encodeURIComponent(document.getElementById('ggg').textContent.toString());
    
    alert(ggg);
  //  var n=ggg.search('/'+txtFind+'/i');
 //  ggg = new RegExp("(\\b" + ggg + "\\b)", "gim");
  // alert(ggg);
 var query = new RegExp( selectionContents1, "gim");
 //var query = new RegExp( selectionContents1, "gim");
//var query = new RegExp( selectionContents1, "gim");
   alert(query);
//   var bbb=query.toString();
//    query1=bbb.replace(/<\//gim,"</");
//   alert(query1);
   var ff=ggg.replace(query, txtReplace);
  ff1= decodeURIComponent(ff);
  
   document.getElementById('ggg').textContent=ff1;
   // document.getElementById('ggg').focus();
}
}

function get_eroll_yr(str)
{
   var str1=str.split('_');
   var hd_in_enroll=$('#hdinenroll_'+str1[1]).val();
    var hd_in_enroll_yr=$('#hdinenrollyr_'+str1[1]).val();
   
   if(hd_in_enroll.trim()=='')
        {
        alert("Please enter Enroll No.");
        $('#hdinenroll_').focus();
        }
      else if(hd_in_enroll_yr.trim()=='' || hd_in_enroll_yr.length<4)
      {
            alert("Please enter Enroll Year and length should be 4.");
        $('#hdinenrollyr_').focus();
      }
      else
    {
    $.ajax({
        url:'get_adv_nm_add.php',
        cache:false,
        async:true,
        data:{hd_in_enroll:hd_in_enroll,hd_in_enroll_yr:hd_in_enroll_yr},
        type:'GET',
        success:function(data,status){
            $('#dv_enr_id').html(data);
            var hd_status=$('#hd_status').val();
            if(hd_status==1)
                {
                    var hd_cur_sts=$('#hd_cur_sts').val();
                   var sp_hd_cur_sts=hd_cur_sts.split('~');
                   document.getElementById('sp_nm'+str1[1]).value=sp_hd_cur_sts[0];
                    document.getElementById('sp_add'+str1[1]).value=sp_hd_cur_sts[1];
                    
                   document.getElementById('sp_nm'+str1[1]).disabled=false;
                    document.getElementById('sp_add'+str1[1]).disabled=false;
                }
                else   if(hd_status==0)
                    {
                        alert("No Record Found");
                    }
        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
        
    });
    }
}

function cl_bar(str)
{
    
    var str1=str.split('_');
      $('#sp_nm'+str1[1]).html('');
//      alert($('#sp_nm'+str1[1]).html());
     document.getElementById('sp_add'+str1[1]).innerHTML='';
    
}

//function get_wh_p_r(str,idd)
//       {
//           var idd_split=idd.split("ddl_nt"); 
//           $.ajax({
//        url:'get_order_date.php',
//        cache:false,
//        async:true,
//        data:{str:str},
//        type:'GET',
//        success:function(data,status){
//           $('#hd_order_date').val(data);
//           
//        },
//        error:function(xhr){
//            alert("Error: "+xhr.status+" "+xhr.statusText);
//        }
//        
//        
//    });
//          
//       }

//function get_wh_p_r(val_nm,id_nm)
//{
//   var sp_id_nm=id_nm.split('ddl_nt');
//   var sp_pet_res_id=$('#sp_pet_res_id'+sp_id_nm[1]).html();
//    var d_no=document.getElementById('diary_number').value;
//    var d_yr=document.getElementById('diary_year').value; 
//    
//    if(val_nm=='5')
//   {
//    $.ajax({
//            url: 'auto_fill_rec.php',
//            cache: false,
//            async: true,
//            data: {sp_pet_res_id: sp_pet_res_id,d_no:d_no,d_yr:d_yr},
//           
//            type: 'POST',
//            success: function(data, status) {
//
//                alert(data);
//             
//            },
//            error: function(xhr) {
//                alert("Error: " + xhr.status + " " + xhr.statusText);
//            }
//
//        });
//    }
//}

function save_con_rec(hd_chk_cnt_case,d_no,d_yr,hd_ent_suc_f)
{
     $.ajax({
            url: 'save_connected_rec.php',
            cache: false,
            async: true,
            data: {hd_chk_cnt_case: hd_chk_cnt_case,d_no:d_no,d_yr:d_yr},
           
            type: 'POST',
            success: function(data, status) {

                alert(data);
             
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
}

function draft_record()
{
    //alert("hello");
     alert("Draft Saved Successfully !!");
    
     var prtContent = document.getElementById('ggg');
     save_content1(escape(prtContent.innerHTML));
 /*var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

WinPrint.document.write(prtContent.innerHTML);

 for(var e=0;e<=hd_tot_po;e++)
     {
 if(WinPrint.document.getElementsByTagName("hr")[e])
     WinPrint.document.getElementsByTagName("hr")[e].style.display='none';
     }
     
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();*/
}

function  save_content1(str)
{
  // alert(str);
  
   var fil_no= document.getElementById('hd_fil_no_x').value;
//   alert(fil_no);
var dt=document.getElementById('hd_recdt').value;
    var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
               //  xmlhttp.overrideMimeType('text/xml; charset=iso-8859-1');
           //   document.getElementById('div_results').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
//                   alert(xmlhttp.responseText);    
              $('#dis_notice').html(xmlhttp.responseText);
               var id_d='';
            
            $('.ind_no_w_vc').each(function(){
             
                if(id_d=='')
                id_d=$(this).attr('id');
                else
                    id_d=id_d+','+$(this).attr('id');

             });
//              alert(id_d);
              //save_pdf_html(id_d,fil_no,dt);
             
//                  $('#ggg').attr('contenteditable',false);     
                       //document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                   }
                }
                
                
            
                 xmlhttp.open("POST","save_content_draft.php",true);
               

               
//                 xmlhttp.setRequestHeader("Content-Type","text/html;charset=utf-8;");
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("accept-charset","UTF-8");
                 xmlhttp.send("str="+str+"&fil_no="+fil_no+"&dt="+dt);
//                  xmlhttp.send(null);
}

//
function draft_record1()
{
    //alert("hello");
    // alert("Draft Saved Successfully !!");
    
     var prtContent = document.getElementById('ggg');
     //save_content1(escape(prtContent.innerHTML));
var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

WinPrint.document.write(prtContent.innerHTML);

 for(var e=0;e<=hd_tot_po;e++)
     {
 if(WinPrint.document.getElementsByTagName("hr")[e])
     WinPrint.document.getElementsByTagName("hr")[e].style.display='none';
     }
     
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
}