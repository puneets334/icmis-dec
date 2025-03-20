  function getDetails()
{

  
  var t_h_cno=$('#t_h_cno').val();
   var t_h_cyt=$('#t_h_cyt').val();
     var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
               document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';
            
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       // var txtAdeshika="txtAdeshika"+rowCount;
                        document.getElementById('div_result').innerHTML=xmlhttp.responseText;
                       
                    }
                }
               // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                 xmlhttp.open("GET","get_data_del.php?d_no="+t_h_cno+"&d_yr="+t_h_cyt,true);
               xmlhttp.send(null);
}    

        
 function UpdateData(str)
 {
     var t_h_cno=$('#t_h_cno').val();
   var t_h_cyt=$('#t_h_cyt').val();


     var str1=str.split('_');
     
     var hd_id=document.getElementById('hd_id'+str1[1]).value;
      var sp_remark=document.getElementById('sp_remark'+str1[1]).value;
    var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
            
            
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                       // var txtAdeshika="txtAdeshika"+rowCount;
                        document.getElementById('div_show').innerHTML=xmlhttp.responseText;
                        if(document.getElementById('hd_res_d').value=='1')
                            {
                            alert("Data Delete Sucessfully");
                            getDetails();
                            }
                        else
                             alert("Data Not Deleted");
                       
                    }
                }
               // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                 xmlhttp.open("GET","del_obj.php?hd_id="+hd_id+"&sp_remark="+sp_remark+"&d_no="+t_h_cno+"&d_yr="+t_h_cyt,true);
               xmlhttp.send(null);
 }
 
 function getUppercase(str)
{
  
   document.getElementById(str).value= document.getElementById(str).value.toUpperCase();
    
}
