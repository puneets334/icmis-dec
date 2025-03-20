
function getDetails()
{

    com_data='';
    nm_cnt='';
    cnt_data=1;
    cntRem='';
    cn_upd_dt='';
    ck_totals='';
    ck_hd_id='';
    hd_show_dt='';
    ans='';
    $('#div_show').html('');
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
    document.getElementById('div_result').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            if(document.getElementById('hdChk_num_row'))
            {
                var hdChk_num_row=document.getElementById('hdChk_num_row').value;
                if(hdChk_num_row<=0)
                    document.getElementById('fiOD').style.display='none';
            }
            if(document.getElementById('hdChk_num_row_j'))
            {
                var hdChk_num_row_j=document.getElementById('hdChk_num_row_j').value;

                if(hdChk_num_row_j<=0)
                {
                    document.getElementById('fdDR').style.display='none';
                    document.getElementById('ftAO').style.display='block';
                }
                else  if(hdChk_num_row_j>0)
                {
//                         document.getElementById('sp_amo').style.display='none';
                    if(document.getElementById('ftAO'))
                        document.getElementById('ftAO').style.display='none';
                }
            }
            if(document.getElementById('hd_bnb'))
            {
                if(document.getElementById('hd_bnb').value=='2')
                {
                    document.getElementById('ftAO').style.display='none';
                }
            }
//                     if( document.getElementById('hd_kl').value==1)
//                             document.getElementById('fd_md').style.display='none';
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET","get_obj_data.php?d_no="+t_h_cno+"&d_yr="+t_h_cyt,true);
    xmlhttp.send(null);
}
var com_data='';
var cnt_data=1;
var nm_cnt='';
var cntRem='';
var cn_upd_dt='';
var ck_totals='';
var ck_hd_id='';
var hd_show_dt='';




$(document).ready(function(){
    $(document).on('click','#btn_backdate',function(){
        var hd_diary_no=$('#hd_diary_no').val();
        var back_date=$('#back_dt').val();
        if(back_date=='')
        {
            alert('Please enter date');
            $('#back_dt').focus();
            return;
        }

        var currentDate = new Date();
        var dateToCompare = new Date(back_date);

        //alert(currentDate);
        //alert(dateToCompare);

        if (dateToCompare > currentDate) {
            alert("Date cannot be greater than Today's Date ");
            $('#back_dt').focus();
            return;
        }
	var r = confirm("Are you sure to refile on back date?");
        if (r == false) {
            exit();
        }
        //alert(back_date);
        $.ajax({
            url: 'save_back_date.php',
            cache: false,
            async: true,
            data: {d_no: hd_diary_no,back_date:back_date},
            beforeSend: function() {
                $('#sp_sms_status').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#sp_sms_status').html(data);
                $('#btn_backdate').attr("disabled", true);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
});


 
