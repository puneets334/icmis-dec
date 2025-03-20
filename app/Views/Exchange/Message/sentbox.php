<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }
</style>
<style>
    .myTable tr:nth-child(even){ /*(even) or (2n 0)*/
        background: #EBEBF9;
    }
    .myTable tr:nth-child(odd) { /*(odd) or (2n 1)*/
        background: #d1d1f2;
    }
    .myTable { width:100%; border-collapse:collapse;  }
    .myTable td { padding:8px; border:#999 1px solid; }

    #container
    {
        width: 780px;  
        background: #FFFFFF;
        margin: 0 auto; 
        font-size:14px;
        text-align: left; 
    }

    #mainContent
    {
        padding: 0 60px;
        min-height:600px;
        line-height:25px
    }
    img {border:0px}

    /*LINKS*/

    #mainContent a:link, #mainContent a:visited
    {   
        color:#fff; text-decoration:none; font-size:18px; background:#000000; padding:5px; -webkit-border-radius:10px; -moz-border-radius:10px
    }
    #mainContent a:hover, #mainContent a:active
    {
        color:#fff; text-decoration:none; font-size:18px; background:#333333; padding:5px; -webkit-border-radius:10px; -moz-border-radius:10px
    }

    /*STYLES FOR CSS POPUP*/

    #blanket
    {
        background-color:#111;
        opacity: 0.65;
        *background:none;
        position:absolute;
        z-index: 9001;
        top:0px;
        left:0px;
        width:100%;
        height: 100%;
        bottom: 0px;
    }

    #popUpDiv
    {
        /*  position:absolute;*/
        /*background:url(pop-back.jpg) no-repeat;*/
        position: fixed; z-index: 0;
        width:400px;
        height:400px;
        border:2px solid #000;
        z-index: 9002;
    }

    /*#popUpDiv*/
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">SentBox</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'MessageInboxId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="causelistDate">Date</label>
                                    <input class="form-control dtp" type="text" id="dtp" value="<?php echo date('d-m-Y'); ?>" size="10"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="causelistDate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <button type="button" value="On Date" onclick="showrpt('P')">On Date</button>
                                </div>
                                <div class="col-md-2">
                                    <label for="causelistDate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                             
                                    <button type="button" value="On Date" onclick="showrpt('all')">All Messages</button>
                                </div>
                            </div>
                            <br>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="txtHint"><b>Messages will be showed here.</b></div>
                            <input type="hidden" id="btnId"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/js/jquery.tokeninput.js"></script>
<script>
    $(function()
    {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            // yearRange: '1950:2050'
        });
    });
</script>

<script>
    function showrpt(str)
    {
        document.getElementById("btnId").value = str;
        if (str == "")
        {
            document.getElementById("txtHint").innerHTML = "";
            return;
        }
        var dtp = document.getElementById("dtp").value;

        $.ajax({
            url: "<?php echo base_url('Exchange/Message/sentboxPro'); ?>?q="+str+"&dtp="+dtp,
            type: "GET",
            beforeSend: function()
            {
                $("#txtHint").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                updateCSRFToken();
                $("#txtHint").html('');
                $("#txtHint").html(response.data);
            },
            error: function(xhr, status, error)
            {
                updateCSRFToken();
                $("#txtHint").html('');
                $("#txtHint").html("<div style='color: red;'>An error occurred. Please try again.</div>");
            }
        });
    }

     function savethis(str)
    {
        var url = "<?php echo base_url('Exchange/Message/sentSave'); ?>?id=" + str;

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function()
            {
                $("#hint").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                if ($('#btnId').val() === 'P')
                {
                    showrpt('P');
                }
                else if ($('#btnId').val() === 'all')
                {
                    showrpt('all');
                }
            },
            error: function(xhr, status, error)
            {
                $("#hint").html('');
                alert("An error occurred: " + error);
                return false;
            }
        });
    }


    /*function savethis(str)
    {

    //var id=str.split('savesingle_');

    var ajaxRequest;  // The variable that makes Ajax possible!

    try{
    // Opera 8.0+, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
    } catch (e)
    {
    // Internet Explorer Browsers
    try{
    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) 
    {

    try{
    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e)
    {
    // Something went wrong
    alert("Your browser broke!");
    return false;
    }
    }
    }


    //document.getElementById("txtHint").innerHTML='<table><tr><td><img src="../includes/images/ajax-preloader.gif"/></td></tr></table>';
    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function()
    {
    if(ajaxRequest.readyState == 4){

    document.getElementById("hint").innerHTML=ajaxRequest.responseText;

    if (document.getElementById('btnId').value == 'P')
    showrpt('P');
    else if (document.getElementById('btnId').value == 'all')
    showrpt('all');
    } 
    }



    //var id = document.getElementById('').value;


    var url="sent_save.php";
    url=url+"?id="+str;
    //alert(url);

    ajaxRequest.open("GET", url , true);
    ajaxRequest.send(null); 


    }*/
</script>