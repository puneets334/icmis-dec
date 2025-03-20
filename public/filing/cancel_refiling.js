
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
    document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

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
    xmlhttp.open("GET","get_and_save_data.php?d_no="+t_h_cno+"&d_yr="+t_h_cyt+"&flag="+"A",true);
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

        var r = confirm("Are you sure to cancel refiling?");
        if (r == false) {
            exit();
        }

        //alert(back_date);
        $.ajax({
            url: 'get_and_save_data.php',
            cache: false,
            async: true,
            data: {d_no: hd_diary_no,flag:"B"},
            beforeSend: function() {
                $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
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


