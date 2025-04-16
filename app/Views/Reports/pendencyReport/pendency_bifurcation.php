<?= view('header') ?>

<style>
    .table_tr_th_w_clr td {
        padding: 10px;
    }

    @media print {
        #cmdPrnRqs2 {
            display: none;
        }
    }
</style>
<script>
    function CallPrint(strid) {
        document.getElementById('cmdPrnRqs2').style.display = 'none';
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('cmdPrnRqs2').style.display = 'block';
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }
</script>
<?php
$dtd = date("d-m-Y");

$ucode = session()->get('login')['usercode'];
// function get_client_ip() {
//     $ipaddress = '';
//     if (getenv('HTTP_CLIENT_IP'))
//         $ipaddress = getenv('HTTP_CLIENT_IP');
//     else if(getenv('HTTP_X_FORWARDED_FOR'))
//         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//     else if(getenv('HTTP_X_FORWARDED'))
//         $ipaddress = getenv('HTTP_X_FORWARDED');
//     else if(getenv('HTTP_FORWARDED_FOR'))
//         $ipaddress = getenv('HTTP_FORWARDED_FOR');
//     else if(getenv('HTTP_FORWARDED'))
//        $ipaddress = getenv('HTTP_FORWARDED');
//     else if(getenv('REMOTE_ADDR'))
//         $ipaddress = getenv('REMOTE_ADDR');
//     else
//         $ipaddress = 'UNKNOWN';
//     return $ipaddress;
// }
// $ip_address=get_client_ip();
//if($ip_address=='10.40.191.41')
if ($ucode == 1 || $ucode == 1124) {
?>
    <style>
        #newb {
            position: fixed;
            padding: 12px;
            left: 50%;
            top: 50%;
            display: none;
            color: black;
            background-color: #D3D3D3;
            border: 2px solid lightslategrey;
            height: 100%;
        }

        #newc {
            position: fixed;
            padding: 12px;
            left: 50%;
            top: 50%;
            display: none;
            color: black;
            background-color: #D3D3D3;
            border: 2px solid lightslategrey;
            height: 100%;
        }

        #overlay {
            background-color: #000;
            opacity: 0.7;
            filter: alpha(opacity=70);
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
        }
    </style>
    <script>

    </script>
    <script type="text/javascript" src="pendency_bifurcation.js"></script>
    <form name="frm" id="frm">
        <?= csrf_field() ?>
        <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
        <div id="dv_content1">
            <div style="text-align: center">
                <?php
                $file_list = "";
                $cntr = 0;
                $chk_slno = 0;
                $chk_pslno = 0;
                $temp_msg = "";


                ?>

                <div id="rightcontainer" align="center">
                    <div id="s_box" align="center">
                        
                                <div class="col-md-4">
                                    <label for="dtd1" class="font-weight-bold">Dated :</label>
                                    <input type="text" class="form-control dtp" name="dtd1" id="dtd1"  />
                                    <input class="button" type="button" name="bt11" value="Submit" onclick='get_pending_data();'>
                                </div>
                          

                    </div>
                    <div id="r_box" align="center"></div>

                </div>
            </div>
    </form>
<?php
}
?>

<script>
    function get_pending_data() {
        var dt = document.getElementById("dtd1").value;
        var dt1 = dt.split("-");
        var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var url = "<?php echo base_url('Reports/PendencyReport/Reports/pendency_bifurcation_process'); ?>";
        $("input.pdbutton").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: url,
            data: {
                dt1: dt_new1,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function(xhr) {


                $('#r_box').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../../images/load.gif"/></td></tr></table>');
            },
            success: function(msg) {
                updateCSRFToken();
                document.getElementById("r_box").innerHTML = msg;
                $("input.pdbutton").attr("disabled", false);
            },
            error: function() {
                updateCSRFToken();
                alert("ERROR");
            }
        });


    }
</script>