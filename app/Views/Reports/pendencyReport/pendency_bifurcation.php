<style>
    .table_tr_th_w_clr td {padding:10px;}
@media print {
  #cmdPrnRqs2 {    display: none;  }
}
</style>
<script>
function CallPrint(strid)
{
document.getElementById('cmdPrnRqs2').style.display= 'none';
 var prtContent = document.getElementById(strid);
 var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
 
 WinPrint.document.write(prtContent.innerHTML);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
 document.getElementById('cmdPrnRqs2').style.display= 'block';
 //WinPrint.close();
 //prtContent.innerHTML=strOldOne;
}
</script>
<?php include("../template.php"); 
include ('../extra/lg_out_script.php');
$dtd = date("d-m-Y");
if(session_status() == PHP_SESSION_NONE or session_id() == '')
session_start();
$ucode = $_SESSION['dcmis_user_idd'];
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$ip_address=get_client_ip();
//if($ip_address=='10.40.191.41')
if($ucode==1||$ucode==1124)
{
?>
<style>
#newb { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;}
#newc { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;}

#overlay {
    background-color: #000;
    opacity: 0.7;
    filter:alpha(opacity=70);
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
</style>
<script>
$(document).on("focus",".dtp",function(){   
$('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'});
        });
</script>
<script type="text/javascript" src="pendency_bifurcation.js"></script>
<form name="frm" id="frm">
<input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>"/>
<div id="dv_content1"   >
<div style="text-align: center">
<?php
$file_list = "";
$cntr = 0;
$chk_slno = 0;
$chk_pslno = 0;
$temp_msg="";


?>
        
            <div id="rightcontainer" align="center"> 
                <div id="s_box" align="center" >
                    <table border="0" height="35" width="100%">
                        <tr valign="middle" align="center">
                            <td>
Dated :
<input class="dtp" type="text" value="<?php print $dtd; ?>" name="dtd1" id="dtd1" size="10" style="font-family:verdana; font-size:9pt;" readonly="readonly">
                                <input class="pdbutton" type="button" name="bt11" value="Submit" onclick='get_pending_data();'>
</td>
                        </tr>
                    </table>

                </div>
                <div id="r_box" align="center" ></div>

            </div>
    </div>
        </form>
<?php
}
?>

<script>
    function get_pending_data(){
var dt = document.getElementById("dtd1").value;
var dt1 = dt.split("-");
var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
 var url = "<?php echo base_url('Reports/PendencyReport/Reports/pendency_bifurcation_process'); ?>";
$("input.pdbutton").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: url,
        data: {dt1: dt_new1
        },
        beforeSend: function (xhr) {
            $("#r_box").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        success: function (msg) {
                document.getElementById("r_box").innerHTML = msg;
                $("input.pdbutton").attr("disabled", false);
        },
        error: function () {
            alert("ERROR");
        }
    });


}
</script>
