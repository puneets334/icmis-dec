
function get_data()
{
    var txtFromDate=document.getElementById('txtFromDate').value;
     var txtToDate=document.getElementById('txtToDate').value;
    var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
            
             document.getElementById('app_data').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                 
                       
                       document.getElementById('app_data').innerHTML=xmlhttp.responseText;
//                       document.getElementById('sp_ctt').innerHTML=document.getElementById('hd_ct_pr_id').value;
                   }
                }
                
                 
            
           xmlhttp.open("GET", base_url+"/Extension/Notices/get_rep_rec_tal?txtFromDate="+txtFromDate+"&txtToDate="+txtToDate,true);
          

          xmlhttp.send(null);
}

/************************************************** */

$(document).ready(function(){
    $(document).on('click','.cl_back',function(){
        var idd=$(this).attr('id');
        var sp_idd=idd.split('btn_back');
        var hd_diary_no=$('#hd_diary_no'+sp_idd[1]).val();

        var hd_rec_dt=$('#hd_rec_dt'+sp_idd[1]).val();
        var hd_fil_nm=$('#hd_fil_nm'+sp_idd[1]).val();
     //   alert(hd_fil_nm);

       // alert(hd_diary_no);
       // alert(hd_rec_dt);
        var txt;
        if (confirm("Do you really want to discard notice ? ")) {
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
                  
                   if(xmlhttp.responseText==1)
                   {
                    alert("SUCCESSFULLY DISCARDED !!!");
                    }
                    else
                    {
                        alert("ERROR !!!");
                    } 
                    location.reload();
                }
            }
            url=base_url+"/Extension/Notices/notice_back?fil_no="+hd_diary_no+"&dt="+hd_rec_dt+"&fil_nm="+hd_fil_nm;         
             
            xmlhttp.open("GET",url,true);

            xmlhttp.send(null);
        } else {
           return;
        }


    });
});



/****************************************************/




$(document).ready(function(){
    $(document).on('click','.cl_generate',function(){
        var idd=$(this).attr('id');
        var sp_idd=idd.split('btn_generate');
        var hd_diary_no=$('#hd_diary_no'+sp_idd[1]).val();    
        var hd_rec_dt=$('#hd_rec_dt'+sp_idd[1]).val();
        var hd_fil_nm=$('#hd_fil_nm'+sp_idd[1]).val();
        $('#hd_active_filez').val(hd_fil_nm);
      
          var x=$('#x'+sp_idd[1]).val();
      
     //   alert(hd_diary_no+hd_rec_dt);
       // alert("the file name is "+hd_fil_nm);
        var sp_ofr=$('#sp_ofr'+sp_idd[1]).html();
        document.getElementById('ggg').style.width='auto';
            document.getElementById('ggg').style.height=' 500px';
             document.getElementById('ggg').style.overflow='scroll'; 
           //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
               document.getElementById('ggg').style.marginLeft ='18px';
                document.getElementById('ggg').style.marginRight ='18px';
                 document.getElementById('ggg').style.marginBottom ='25px';
                  document.getElementById('ggg').style.marginTop ='1px';
               //    document.getElementById('dv_edi').style.marginLeft ='18px';
                //document.getElementById('dv_edi').style.marginRight ='18px';
                 //document.getElementById('dv_edi').style.paddingTop='10px';
                 //document.getElementById('dv_edi').style.paddingBottom ='10px';
                 // document.getElementById('dv_edi').style.backgroundColor='#bdd5ff';
                    $('#btn_publish').css('display','inline');
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
             document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                        document.getElementById('hd_fil_no_x').value=hd_diary_no;
                        document.getElementById('hd_recdt').value=hd_rec_dt;
                        document.getElementById('hd_off_notice').value=sp_ofr;

                        //$('#hd_fil_no_x').val(hd_diary_no);
                        //$('#hd_recdt').val(hd_rec_dt);
                        //$('#hd_off_notice').val(sp_ofr);
                        $('#btn_publish').css('display','none');
                   }else{
                    
                    document.getElementById('hd_fil_no_x').value=hd_diary_no;
                        document.getElementById('hd_recdt').value=hd_rec_dt;
                        document.getElementById('hd_off_notice').value=sp_ofr;
                   }
                }
       xmlhttp.open("GET",base_url+"/Extension/Notices/pocriminal1?fil_no="+hd_diary_no+"&dt="+hd_rec_dt+"&fil_nm="+hd_fil_nm+"&x"+x,true);
          

          xmlhttp.send(null);
                  
    });
});

function dummy(fil_no,dt,cs_ty,hd_id)
{
      
   document.getElementById('hd_req_f_no').value=hd_id;

document.getElementById('hd_fil_no_x').value=fil_no;
document.getElementById('hd_recdt').value=dt;
//var case_type=cs_ty;
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
             document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                                  document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                
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
             
              if(e.keyCode=='9')
                   {
            
             // document.execCommand('styleWithCSS', false, null);
               document.execCommand('indent', false ,null);
               return false;
                   }
                  
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
 
            var prtContent = document.getElementById('ggg');

            save_content(escape(prtContent.innerHTML));

           }
           
           function closeData()
            {
                document.getElementById('ggg').scrollTop=0; 
            
                document.getElementById('dv_fixedFor_P').style.display="none";
                document.getElementById('dv_sh_hd').style.display="none";

            //       document.getElementById('sp_close').style.display='none';
            get_data();
            } 

function  save_content(str)
{
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  
   var fil_no= document.getElementById('hd_fil_no_x').value;
var dt=document.getElementById('hd_recdt').value;
var hd_off_notice=document.getElementById('hd_off_notice').value;
var hd_active_filez=$('#hd_active_filez').val();
//alert(hd_active_filez);
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
 
                       $('#dis_notice').html(xmlhttp.responseText);
                        var id_d='';
            
                        $('.ind_no_w_vc').each(function(){                        
                            if(id_d=='')
                            id_d=$(this).attr('id');
                            else
                                id_d=id_d+','+$(this).attr('id');
                        });
             
                        save_pdf_html(id_d,fil_no,dt,hd_off_notice);
                   }else{
                    updateCSRFToken();
                   }
                } 
                 xmlhttp.open("POST",base_url+"/Extension/Notices/save_content",true);               
 
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("accept-charset","UTF-8");
                 xmlhttp.send("str="+str+"&fil_no="+fil_no+"&dt="+dt+"&hd_active_filez="+hd_active_filez+"&z_chk_status=1&CSRF_TOKEN="+CSRF_TOKEN_VALUE);
                //  xmlhttp.send(null);
}

async function save_pdf_html(id_d,fil_no,dt,hd_off_notice)
{
    await updateCSRFTokenSync();
    var cks_ids='';
//    alert(id_d);
     $('.ind_no_w_vc').each(function(){
             
                if(cks_ids=='')
                cks_ids=$(this).html();
                else
                    cks_ids=cks_ids+'~!@#$'+$(this).html();

             });
//             alert(cks_ids);
             cks_ids=encodeURIComponent(cks_ids);
    $.ajax({
        
        url: base_url+"/Extension/Notices/save_pdf_html",
        cache:false,
        async:true,
    
     data:{id_d:id_d,fil_no:fil_no,dt:dt,cks_ids:cks_ids,hd_off_notice:hd_off_notice},
        type:'POST',
        success:function(data,status){

            updateCSRFToken();
          $('#dis_notice').html(data);
          
var prtContent = document.getElementById('ggg');
 var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
WinPrint.document.write(prtContent.innerHTML);

 //WinPrint.document.getElementById('pba').style.pageBreakBefore='always';
 if(WinPrint.document.getElementById('pba'))
 WinPrint.document.getElementById('pba').style.display='none';
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();

         
        },
        error:function(xhr){
            updateCSRFToken();
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
}

function publish_record()
{
    var fil_no= document.getElementById('hd_fil_no_x').value;
var dt=document.getElementById('hd_recdt').value;
var hd_off_notice=$('#hd_off_notice').val();
var ddl_not_office='';
 $('.cl_not_off').each(function(){
        if($(this).is(':checked'))
        {
            ddl_not_office=$(this).val();
        }
    });
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
            url: base_url+"/Extension/Notices/publish_record",
            cache: false,
            async: true,
            data: {fil_no: fil_no, dt: dt,ddl_not_office:ddl_not_office,hd_off_notice:hd_off_notice,CSRF_TOKEN:CSRF_TOKEN_VALUE},
           
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                alert(data);
             
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
}
function draft_record()
{

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
//alert(str);
  
   var fil_no= document.getElementById('hd_fil_no_x').value;
var dt=document.getElementById('hd_recdt').value;
var hd_off_notice=document.getElementById('hd_off_notice').value;
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
                       
//            var sd=  document.getElementById('hd_req_f_no').value;
//            document.getElementById(sd).disabled=true;
                       
                       //document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                       $('#dis_notice').html(xmlhttp.responseText);
               var id_d='';
            
            $('.ind_no_w_vc').each(function(){
             
                if(id_d=='')
                id_d=$(this).attr('id');
                else
                    id_d=id_d+','+$(this).attr('id');

             });
//              alert(id_d);
           //   save_pdf_html(id_d,fil_no,dt,hd_off_notice);
                   }
                }
                
                
            
                 xmlhttp.open("POST","save_content_draft.php",true);
               

               
//                 xmlhttp.setRequestHeader("Content-Type","text/html;charset=utf-8;");
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("accept-charset","UTF-8");
                 xmlhttp.send("str="+str+"&fil_no="+fil_no+"&dt="+dt);
                //  xmlhttp.send(null);
}


function draft_record1()
{

  //   alert("Draft fdg Successfully !!");
     var prtContent = document.getElementById('ggg');
    // save_content1(escape(prtContent.innerHTML));
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
