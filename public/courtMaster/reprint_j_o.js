var hd_ud=$('#hd_ud').val();
function get_records()
{
     var order_upload='';
     if($('#ddl_od').is(':checked'))
         {
             order_upload=$('#ddl_od').val();
         }
      else if($('#ddl_ud').is(':checked'))
          {
              order_upload=$('#ddl_ud').val();
          }
     var txt_o_frmdt=$('#txt_o_frmdt').val();
      var txt_o_todt=$('#txt_o_todt').val();
      
      if(order_upload=='' || (txt_o_frmdt=='' || txt_o_frmdt.length<10) || 
              (txt_o_todt=='' || txt_o_todt.length<10))
              {
                  if(order_upload=='')
                      {
                          alert("Please select Order Date or Uploaded Date");
                      }
                  else if(txt_o_frmdt=='')
                      {
                           alert("Please enter from date");
                      }
                      else if(txt_o_frmdt.length<10)
                      {
                           alert("Please enter valid from date");
                      }
                    else if(txt_o_todt=='')
                      {
                           alert("Please enter to date");
                      }
                      else if(txt_o_todt.length<10)
                      {
                           alert("Please enter valid to date");
                      }
              }
              else
                  {
     $.ajax({
        url:base_url + "/Court/CourtCauseListController/get_reprint_j_o",
        type:'GET',
        cache:false,
        async:true,
        beforeSend:function(){
            $('#dv_get_res').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{order_upload:order_upload,txt_o_frmdt:txt_o_frmdt,txt_o_todt:txt_o_todt,hd_ud:hd_ud},
        success:function(data,status)
        {
          
            $('#dv_get_res').html(data);
        },
        error:function(xhr){
            alert("Error:"+ xhr.status+' '+xhr.statusText)
        }
    });
                  }
}

async function save_upload(docid)
{
    await updateCSRFTokenSync();
     
    document.getElementById('dv_sh_hd').style.display = 'block';
    document.getElementById('dv_fixedFor_P2').style.display = 'block';

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
    $.ajax({
        type: 'POST',
        url: base_url + "/Court/CourtCauseListController/get_pdf_name",
        beforeSend: function (xhr) {
            $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{docid:docid, CSRF_TOKEN : CSRF_TOKEN_VALUE}
    })
    .done(function(responseText){
        updateCSRFToken();
        if(responseText == 0)
        {
            document.getElementById('sar1').innerHTML += "<h3>No PDF Found</h3>";
        }else{
            document.getElementById('ggg_object2').setAttribute('data',responseText);
        }
        
    })
    .fail(function(){
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    });

 

}


function get_sp_type(str,idd,orderdate,fil_noo,p_pdf)
{
    $('#hd_main_id').val(idd);
    $('#hd_fl_nm').val(str);
    $('#hd_orderdate').val(orderdate);
    $('#hd_fil_no').val(fil_noo);
    $('#hd_p_pdf').val(p_pdf);
    
    document.getElementById('ggg').style.height=' 500px';
             document.getElementById('ggg').style.overflow='scroll'; 
        
               document.getElementById('ggg').style.marginLeft ='auto';
                document.getElementById('ggg').style.marginRight ='auto';
                 document.getElementById('ggg').style.marginBottom ='25px';
                  document.getElementById('ggg').style.marginTop ='1px';
                   document.getElementById('dv_edi').style.marginLeft ='auto';
                document.getElementById('dv_edi').style.marginRight ='auto';
                 document.getElementById('dv_edi').style.paddingTop='10px';
                 document.getElementById('dv_edi').style.paddingBottom ='10px';
                  document.getElementById('dv_edi').style.backgroundColor='#bdd5ff';
                  document.getElementById('dv_sh_hd').style.display='block';
               document.getElementById('dv_fixedFor_P').style.display='block';
                document.getElementById('dv_fixedFor_P').style.marginTop='3px';
  
  var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
             
             document.getElementById('ggg').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';
           
             
               xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                    document.getElementById('ggg').innerHTML=xmlhttp.responseText;
                   }
                }
                xmlhttp.open("GET",base_url + "/Court/CourtCauseListController/pnt_file_dls?str="+str,true);
                xmlhttp.send(null);
}


function get_set_prt()
           {

 var prtContent='';
 prtContent=document.getElementById('ggg').innerHTML;
var fnt_sz=document.getElementById('ggg').style.fontSize;
var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
//WinPrint.document.write('<style type="text/css" >   @page { size:auto; margin: 345px 70px 100px 175px; }   table { page-break-inside : avoid }   .sp_caseno { text-align:center;display:block; }  .dv_c_co_u div,.dv_c_co_u table,.dv_c_co_u th {font-size:17px;} </style>'+prtContent);
WinPrint.document.write(prtContent);
//var sp_jud1=WinPrint.document.getElementById('sp_jud1').offsetWidth;
//var sp_jud2=WinPrint.document.getElementById('sp_jud2').offsetWidth;
//WinPrint.document.getElementById('sp_judge1').style.paddingLeft=sp_jud1/3;
//WinPrint.document.getElementById('sp_judge2').style.paddingLeft=sp_jud2/3;
//WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
document.getElementById('ggg').innerHTML=document.getElementById('ggg').innerHTML. replace('/@page { size:portrait; margin: 84.28125mm 16.520833333mm 35mm 48.9mm; }/gi',' @page {size:portrait; margin: 89.3mm 21.2mm 37mm 55mm; }');
//      save_content(encodeURIComponent('<style type="text/css" >  @page { size:auto; margin: 345px 70px 100px 175px; }  table { page-break-inside : avoid } .sp_caseno { text-align:center;display:block; }  .dv_c_co_u div,.dv_c_co_u table,.dv_c_co_u th {font-size:17px;} </style>'+document.getElementById('ggg').innerHTML));
    save_content(encodeURIComponent('<style type="text/css" >    table { page-break-inside : avoid } .sp_caseno { text-align:center;display:block; }  .dv_c_co_u div,.dv_c_co_u table,.dv_c_co_u th {font-size:17px;} </style>'+document.getElementById('ggg').innerHTML));
    }
          
           function  save_content(str)
{
 //$('#hd_full_data').val(str);
            var  total_fil= $('#hd_main_id').val();
            var hd_fl_nm=$('#hd_fl_nm').val();
             var hd_orderdate=   $('#hd_orderdate').val();
            var hd_fil_no= $('#hd_fil_no').val();
            var hd_p_pdf= $('#hd_p_pdf').val();
//          alert(hd_fil_no);
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
                   alert(xmlhttp.responseText);   
        
                   }
                }
                
                 
           
                 xmlhttp.open("POST",base_url + "/Court/CourtCauseListController/upload_html_pdf",true);
               

               
                 xmlhttp.setRequestHeader("Content-Type","text/html;charset=utf-8;");
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("accept-charset","UTF-8");
                 xmlhttp.send("&total_fil="+total_fil+"&str="+str+'&hd_fl_nm='+hd_fl_nm+'&hd_orderdate='+hd_orderdate+'&hd_fil_no='+hd_fil_no+'&hd_p_pdf='+hd_p_pdf);
             
                  
}
    
    
    
    function getItalic()
            {
                document.execCommand('styleWithCSS', false, null);
               document.execCommand('Italic', false, null);
                document.getElementById('ggg').focus();
                checkStat();
            }
            function getBold()
            {
              
               document.execCommand('styleWithCSS', false, null);
              
               document.execCommand('Bold', false ,null);
           document.getElementById('ggg').focus();
          checkStat();
            }
             function getUnderline()
            {
                 document.execCommand('styleWithCSS', false, null);
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
            
              
              
//         var html = "";
//    if (typeof window.getSelection != "undefined") {
//        var sel = window.getSelection();
//        if (sel.rangeCount) {
//            var container = document.createElement("div");
//            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
//                container.appendChild(sel.getRangeAt(i).cloneContents());
//            }
//            html = container.innerHTML;
//        }
//    } else if (typeof document.selection != "undefined") {
//        if (document.selection.type == "Text") {
//            html = document.selection.createRange().htmlText;
//        }
//    }
//    alert(html);
//              document.execCommand("insertHTML", false, "<span style='font-size:"+str+"px;'>"+ document.getSelection()+"</span>");  
//              document.execCommand('fontSize', false, str);
//              document.execCommand('removeFormat', false, 'font');


 document.getElementById('hd_f_size').value=str+'pt';
 var fontElements = document.getElementsByTagName("font");
    document.execCommand('styleWithCSS', false, null);     
//document.execCommand("formatblock", false, 'span');   
//var quote = window.getSelection().anchorNode.parentNode;
//
//var quote1 = window.getSelection().focusNode.parentNode;
//
//$(quote).addClass("quote");
//$(quote1).addClass("quote");
//    
//$('font').contents().unwrap();
//document.execCommand("foreColor",false,"inherit")
//$('.quote font').contents().unwrap();
// $('font').contents().unwrap();
//alert("dfdfdf");
//var container = null;
//if (document.selection) //for IE
//    container = document.selection.createRange().parentElement();
//else {
//    var select = window.getSelection();
//    if (select.rangeCount > 0)
//        container = select.getRangeAt(0).startContainer.parentNode;
//}
//if (document.selection)
//$(container).contents().unwrap();
 for (var i = 0, len = fontElements.length; i < len; ++i) {

      if (fontElements[i].size == "7") {
          
         fontElements[i].removeAttribute("style");
           
        }
//           }
    }
    document.execCommand("fontSize", false, '7');
    for (var i = 0, len = fontElements.length; i < len; ++i) {
//       if(document.getSelection())
//           {
      // fontElements[i].style.fontSize = str+"pt";
      if (fontElements[i].size == "7") {
          
         fontElements[i].removeAttribute("style");
            fontElements[i].style.fontSize = str+"pt";
        }
//           }
    }
  
               document.getElementById('ggg').focus();
             
            }
            
            function get_l_s(str)
            {
                   var fontElements = document.getElementsByTagName("font");
               for (var i = 0, len = fontElements.length; i < len; ++i) {
                 
        if (fontElements[i].size == "7") {
            fontElements[i].removeAttribute("style");
//            fontElements[i].style.lineHeight = str;
        }
    }
                document.execCommand("fontSize", false, "7");

    for (var i = 0, len = fontElements.length; i < len; ++i) {
        if (fontElements[i].size == "7") {
            fontElements[i].removeAttribute("style");
            fontElements[i].style.lineHeight = str;
        }
    }
            }
            
            function jus_cen()
            {
                 document.execCommand('styleWithCSS', false, null);
                document.execCommand('JustifyCenter', false ,null);
                 document.getElementById('ggg').focus();
                 checkStat();
            }
            function jus_left()
            {
                document.execCommand('styleWithCSS', false, null);
               document.execCommand('JustifyLeft', false ,null);
                document.getElementById('ggg').focus();
                checkStat();
            }
             function jus_right()
            {
                 document.execCommand('styleWithCSS', false, null);
                document.execCommand('JustifyRight', false ,null);
                 document.getElementById('ggg').focus();
                 checkStat();
            }
             function jus_full()
            {
                document.execCommand('styleWithCSS', false, null);
//              alert( document.getSelection());
               document.execCommand('JustifyFull', false ,null);
                document.getElementById('ggg').focus();
                checkStat();
            }
             
              function nb(e)
            {
         
//if (range)
//  range.insertNode(elementWhichYouWantToAddToContentEditable);
            var key;
if(window.e)
{
key=e.keyCode;
}
else if(e.which)
{
key=e.which;
}
//        if(chk_no==1)
//                {
//                 String.fromCharCode(e.which);
//                 alert( String.fromCharCode(e.which).toUpperCase());
//                 return String.fromCharCode(e.which).toUpperCase();
//                }
              if(e.keyCode=='9')
                   {
//           e.keyCode='32';
//           nb(e);
                 //document.execCommand('styleWithCSS', false, null);
////               document.execCommand('indent', false ,null);
////               return false;
////                 for(var j=0;j<2;j++)
////                     {
////                         e.keyCode='32';
////                     }
//document.execCommand('styleWithCSS', false, null);
document.execCommand("insertHTML", false, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+ document.getSelection());
return false;
                           
                   }
                   if(e.which=='102')
                       {
                           
                           var sel=document.getSelection();
                          document.getElementById('btnFind').value=sel;
                          
                         
                       }
                      else if (e.ctrlKey && e.which == '115')
                        {
                           //alert("cxcxcxc");
                            var prtContent =document.getElementById('ggg');
                         save_content(encodeURIComponent('<style type="text/css" >   @page {size:auto; margin: 345px 70px 100px 175px; }  table { page-break-inside : avoid } .sp_caseno { text-align:center;display:block; }  .dv_c_co_u div,.dv_c_co_u table,.dv_c_co_u th {font-size:17px;} </style>'+prtContent.innerHTML));
                           return false;
                        }
               
                  
            }
             var  chk_no='';
        function ent_dt(e)
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
   if(key=='13')
                           {
                             

document.execCommand("insertHTML", false, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+ document.getSelection());
chk_no=1;                     
                     }
        }
            
            function getFonts(str)
            {
                document.execCommand('styleWithCSS', false, null);
               document.execCommand('FontName', false ,str);
               //document.execCommand('focus', false ,null);
               document.getElementById('ggg').focus();
             //  return false;
             checkStat();
            }
            
             function un_ord_bu()
            {
                document.execCommand('styleWithCSS', false, null);
               document.execCommand('insertUnorderedList', false ,null);
              
               document.getElementById('ggg').focus();
            
             checkStat();
            }
            
             function ord_bu()
            {
                document.execCommand('styleWithCSS', false, null);
               document.execCommand('insertOrderedList', false ,null);
              
               document.getElementById('ggg').focus();
            
             checkStat();
            }
            
            function get_supScr()
            {
                document.execCommand('styleWithCSS', false, null);
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
    
     var insertUnorderedList=document.queryCommandState("insertUnorderedList");
     var insertOrderedList=document.queryCommandState("insertOrderedList");
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

if(insertUnorderedList==true)
    document.getElementById('insertUnorderedList').style.backgroundColor='#bbb51f';
    else
    document.getElementById('insertUnorderedList').style.backgroundColor='';

if(insertOrderedList==true)
    document.getElementById('insertOrderedList').style.backgroundColor='#bbb51f';
    else
    document.getElementById('insertOrderedList').style.backgroundColor='';

 document.getElementById('ddlFontFamily').value=fon_nm;
        //  alert(document.getElementById('ddlFontFamily').value)    ;  
       //  document.getElementById('ggg').focus();
            }
            
            function selectal()
            {
               
               document.execCommand('selectAll', false ,null);
            document.execCommand("insertHTML", false,  ff1);
             document.getElementById('ggg').focus();
            
            }
            function fin_rep()
{
 
//   document.execCommand('styleWithCSS', false, null);
//  
//  
// var selectionContents1=encodeURIComponent(document.getElementById('btnFind').value.toString());
//
//    var txtReplace=document.getElementById('txtReplace').value.toString();
//
// 
//  var ggg=encodeURIComponent(document.getElementById('ggg').innerHTML.toString());
//    
// // var ggg=encodeURIComponent(document.getSelection());
// var query = new RegExp( selectionContents1, "gim");
// 
//   var ff=ggg.replace(query, txtReplace);
//  ff1= decodeURIComponent(ff);
//  selectal();
// //  document.getElementById('ggg').innerHTML=ff1;
//   
//   document.execCommand("insertHTML", false,  ff1);
//   document.getElementById('ggg').focus();
//   //document.execCommand("insertHTML", false, "&nbsp;&nbsp;&nbsp;&nbsp;"+ document.getSelection());
//   //document.execCommand('Bold', false ,null);
//   
////}

var selectionContents1=encodeURIComponent(document.getElementById('btnFind').value.toString());

    var txtReplace=document.getElementById('txtReplace').value.toString();

 
  var ggg=encodeURIComponent(document.getElementById('ggg').innerHTML.toString());
    

 var query = new RegExp( selectionContents1, "gim");
 
   var ff=ggg.replace(query, txtReplace);
  ff1= decodeURIComponent(ff);

   document.getElementById('ggg').focus();
    selectal(ff1);
}

function tb_create()
{
    var tb_r_c="<table width='100%' border='1' style='border-collapse:collapse;'>";
    var tb_row=document.getElementById('tb_row').value;
     var tb_column=document.getElementById('tb_column').value;
     var wt=100/tb_column;
     for(var i=0;i<tb_row;i++)
         {
             tb_r_c=tb_r_c+"<tr>";
             for(var j=0;j<tb_column;j++)
                 {
                     tb_r_c=tb_r_c+"<td style='border-collapse:collapse;width:"+wt+"%;word-wrap: break-word;'></td>"
                 }
             tb_r_c=tb_r_c+"</tr>";
         }
         tb_r_c=tb_r_c+"</table>";
     document.execCommand("insertHTML", false,  tb_r_c);
     
 
}
function gt_redo()
{
   document.execCommand('styleWithCSS', false, null);
   document.execCommand('undo', false ,null);
}

function closeData()
{
    document.getElementById('ggg').scrollTop = 0;
    document.getElementById('dv_fixedFor_P').style.display = "none";
    document.getElementById('dv_sh_hd').style.display = "none";
} 

function closeData2(){
    $('#dv_fixedFor_P2').css("display","none");
    $('#dv_sh_hd').css("display","none");
}

function update_app()
{
 alert($('.textLayer').html());
}

function re_in_lr()
{
     document.getElementById('ddlFS').value=document.getElementById('hd_f_size').value;
     document.getElementById('hd_f_size').value=document.getElementById('ddlFS').value;
 var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
            //alert(html);
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
//       alert(html);
       
//       var tb_row=document.getElementById('tb_row').value;
//        var tb_column=document.getElementById('tb_column').value;
        
        var tb_row=document.getElementById('btn_in_lr').value;
var tb_column=document.getElementById('btn_in_lr').value;
      var hd_f_size=  document.getElementById('hd_f_size').value;
      var ddlFontFamily=document.getElementById('ddlFontFamily').value;
       document.execCommand('styleWithCSS', false, null);
//        document.execCommand("insertHTML", false, "<p  style='margin-top:6.0pt;margin-right:"+tb_column+"in;margin-bottom:6.0pt;margin-left:"+tb_row+"in;font-family:"+ddlFontFamily+";font-size:"+hd_f_size+"pt;line-height: 1.25;text-align: justify;word-wrap:break-word;font-weight: normal'>"+ document.getSelection()+"</p>");        
    document.execCommand("insertHTML", false, "<p  style='margin-top:6.0pt;margin-right:"+tb_column+"in;margin-bottom:6.0pt;margin-left:"+tb_row+"in;font-family:"+ddlFontFamily+";font-size:"+hd_f_size+"pt;line-height: 1.25;text-align: justify;word-wrap:break-word;font-weight: normal'>"+ html+"</p>");        
   document.getElementById('ggg').focus();
                
   
}







function re_fmt()
{
//   document.getElementById('hd_f_size').value=document.getElementById('ddlFS').value;
 document.getElementById('ddlFS').value=document.getElementById('hd_f_size').value;
     document.getElementById('hd_f_size').value=document.getElementById('ddlFS').value;
     var hd_f_size=  document.getElementById('hd_f_size').value;
//     alert(hd_f_size);
  var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
            //alert(html);
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    } 
   
   document.execCommand('styleWithCSS', false, null);
//        document.execCommand("insertHTML", false, "<p  style='font-family: Times New Roman;font-size: 15pt;line-height: 1.25;text-indent: .5in;text-align: justify;margin: 0px;padding: 0px;word-wrap:break-word;font-weight: normal'>"+ document.getSelection()+"</p>");        
         document.execCommand("insertHTML", false, "<p  style='font-family: Times New Roman;font-size:"+hd_f_size+"pt;line-height: 1.25;text-indent: .5in;text-align: justify;margin: 0px;padding: 0px;word-wrap:break-word;font-weight: normal'>"+ html+"</p>");        
        document.getElementById('ggg').focus();
                
}




