<?= view('header') ?>
<?php  $uri = current_url(true); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="card-title">Appearance List</h3>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <p id="show_error"></p>
                            <div class="card-body">


<?php
    $ucode = $usercode;
    $icmis_user_jcode = $icmis_user_jcode;
?>
  
    <script type="text/javascript" src="court_process.js"></script>
<style>
    * {
        box-sizing: border-box;
    }
    .scrollup {
        position: fixed;
        bottom:30px;
        left:280px;
        z-index: 20;
       /* display:none;*/

        /*background-color: #000;*/
    }
    .list-group-item:nth-child(even) {
        background-color: #e6f2ff;
    }
    .list-group-item:nth-child(odd) {
        background-color: #F5F5F5;
    }
    /*.list-group-item:nth-child(even) {
        background-color: #cce6ff;
        border-top: 1px solid #0091b5;
        border-left-color: #fff;
        border-right-color: #fff;
    }
    .list-group-item:nth-child(odd) {
        background-color: #b3d9ff;
        border-top: 1px solid #0091b5;
        border-left-color: #fff;
        border-right-color: #fff;
    }*/

.list-group-mine .list-group-item:hover:not(.disabled) {
        background-color: #009acd;
        color: black;
        cursor: -webkit-grabbing;
        cursor: grabbing;
    }
    .list-group-item.active{
        background-color: #03C200 !important;
        color: #0a0a0a;
    }
/*.item_no:hover{
    background-color: silver;
}*/
/*    .item_no:nth-child(even) {background-color: #99ddff;}
    .item_no:nth-child(odd) {background-color:#b3e6ff;}*/
    /* Create two equal columns that floats next to each other */
    .column_item1 {
        float: left;
        width: 15%;
        font-weight: bold;
    }
    .column_item4 {
        float: left;
        width: 85%;
        padding: 1px;
    }
    .column1 {
        float: left;
        width: 20%;
        padding: 5px 0px 0px 20px;

    }
    .column11 {
        float: left;
        width: 80%;
        padding: 0px 0px 0px 20px;

    }
    .column2 {
        float: left;
        width: 26%;
        padding: 0px 5px 5px 40px;
/*        position: relative;
        z-index:3000;*/
    }
    .column4 {
        float: left;
        width: 74%;
        padding: 0px 5px 5px 40px;
    }
    /* Clear floats after the columns */
    .row:after {
        padding: 1px;
        content: "";
        display: table;
        clear: both;
    }
    .right_panel_data_row1{
        text-align: center;
        /*height: 10%;*/
     /*   background-color: LightGray;*/
    }
   /* .right_panel_data_row2{
        text-align: center;
        height: 90%;
        padding: 5px;
    }*/
    .left_panel_data_row1{
     /*   padding: 5px;*/
        text-align: left;
        /*background-color: Gray;*/
    }
    .menu_plc{
       /* width: 100%;*/
        position: absolute;
        top:-5px;
        right:10px;
        /*z-index: 300;*/
        /*padding-right: 10px;*/
    }





    .blink_me {
        -webkit-animation-name: blinker;
        -webkit-animation-duration: 1.5s;
        -webkit-animation-timing-function: linear;
        -webkit-animation-iteration-count: infinite;

        -moz-animation-name: blinker;
        -moz-animation-duration: 1.5s;
        -moz-animation-timing-function: linear;
        -moz-animation-iteration-count: infinite;

        animation-name: blinker;
        animation-duration: 1.5s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    @-moz-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }
    @-webkit-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }
    @keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }
    /*html, body { height: 100%; }*/
    /*body { position: relative; font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; }*/
    .lblclass{font-size: 12pt;}
    /*#s_box { width: 100%; background-color: #ADAEC0; border-top: 1px solid #fff; position: fixed; top: 0px; left: 0; right: 0; z-index: 0; }*/
    /*#messagepost { z-index: 9999; }*/
    #newparty{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}
    #newparty1{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}
    /*#r_box { overflow:auto;  height:75%; bottom:0; width:98%;  }*/
    #newb { position: absolute; padding-left: 12px;padding-right: 12px; padding-top: 5px;padding-bottom: 5px;left: 10%; top: 10%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
    #newc { position: absolute; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
    #newa { position: absolute; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
    #mrq { color: black; text-shadow: grey 0.1em 0.1em 0.2em; font-size:13px; }
    #jodesg { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
    #joname { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
    table.mytable3 { width: 100%; }
    table.mytable { width: 100%;  -moz-box-shadow: 2px 2px 2px #ccc;  -webkit-box-shadow: 2px 2px 2px #ccc;  box-shadow: 2px 2px 2px #ccc;}
    table.mytable3 td { font-size: 12px; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; vertical-align: top; padding: 0px;  }
    table.mytable td { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
    table.mytable th { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
    hr { color: #666666; background-color:#999999; height: 1px; width:95%; }
    .newb123t td {padding: 1px;}
    #paps123p {max-height: 100px; overflow: auto;}

</style>
<form name="frm" id="frm" >
<input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>"/>
<!--<div id="dv_content1"  style="width: 100%; float: right">-->
<div class="container" style="width: 100%; height:100vh; overflow:hidden;">
<?php
$file_list = "";
$cntr = 0;
$chk_slno = 0;
$chk_pslno = 0;
$dtd = date("d-m-Y");
$hd_ud = $_GET['hd_ud'];
$hd_sno = $_GET['hd_sno'];
$sql = "select * from users where usercode='" . $hd_ud . "' and display='Y'";
$t = mysql_query($sql);
$row = mysql_fetch_array($t);
$paps=$row['pa_ps'];
$_SESSION['jcode'] = $row['jcode'];
function c_list($tpaps) {
//if (!($_SESSION['jcode'] == 0 and ($tpaps=='RDR' OR $tpaps=='RDR_ABS'))){
//if ($_SESSION['jcode'] == 0)
            $sql2 = "SELECT jcode AS jcode, trim(jname) AS jname FROM judge WHERE display = 'Y' AND is_retired= 'N' AND jtype IN('J','R')  ORDER BY jtype,judge_seniority";
//else
//$sql2 = "SELECT t1.jcode AS jcode, trim(t1.jname) AS jname FROM judge t1 WHERE t1.jcode=".$_SESSION['jcode']." AND t1.display = 'Y' AND t1.is_retired= 'N' AND t1.jtype IN('J','R') ORDER BY t1.jtype,t1.jcode";

////$sql2 = "SELECT t1.jcode AS jcode, trim(t1.jname) AS jname FROM judge t1 WHERE t1.jcourt > 0 AND t1.display = 'Y' ORDER BY if(t1.jsen=0,9999,t1.jsen)";
            $results2 = mysql_query($sql2);
            if (mysql_affected_rows() > 0) {
                while ($row2 = mysql_fetch_array($results2)) {
//            if ($_POST['aw1'] == $row2["jcode"])
                    echo '<option value="' . $row2["jcode"] . '">' . str_replace("\\", "", $row2["jname"]) . '</option>';
//            else
//                echo '<option value="' . $row2["jcode"] . '">' . str_replace("\\", "", $row2["jname"]) . '</option>';
                }
            }
}
?>
            <input type="hidden" name="caseno" id="caseno">
            <input type="hidden" name="t_cs" id="t_cs">
            <input type="hidden" name="uid" id="uid" value="<?php echo $_SESSION['userid']; ?>" >
            <input type="hidden" name="sid" id="sid" value="" >
            <input type="hidden" name="flnm" id="flnm" value="" >
            <!--<div id="rightcontainer" align="center" style="height:100%; min-height:100%;">-->
    <!--<span class="glyphicon glyphicon-circle-arrow-up"></span>-->
        <span class="scrollup"><a href="#" style="font-size:2.5vw; text-decoration: none;" class="glyphicon glyphicon-circle-arrow-up"></a></span>
    <div class="menu_plc" style="font-size:1.5vw;">
        <a data-placement="bottom" data-toggle="tooltip" title="Open Full Screen!" style="padding-left:5px;" id="openfullscreen_btn" href="#" ><span class="glyphicon glyphicon-resize-full"></span></a>
        <a data-placement="bottom" data-toggle="tooltip" title="Close Full Screen!" style="display: none;" id="closefullscreen_btn"  href="#" ><span class="glyphicon glyphicon-resize-small"></span></a>
        <a data-placement="bottom" data-toggle="tooltip" title="HomeScreen!" style="padding-left:5px;" href="#" onclick="homepage();"><span style="color:green;" class="glyphicon glyphicon-home"></span></a>
        <a data-placement="bottom" data-toggle="tooltip" title="LogOut!" style="padding-left:5px;" href="#" onclick="logout();"><span style="color:red;" class="glyphicon glyphicon-log-out"></span></a>
    </div>
                <div id="s_box" align="center" style="padding:0px;">

                    <!--<div>-->
                        <!--<div class="row" >-->
                            <div class="column1">
                                <div style="text-align:left;" class="row">



<!--                                    <input type="button" onclick="logout();" class="btn btn-danger btn-xs" value="Logout">
                                    <input type="button" onclick="homepage();" class="btn btn-success btn-xs" value="Home">
                                    <input type="button" id="openfullscreen_btn" class="btn btn-info btn-xs" value="Open Full Screen">
                                    <input type="button" id="closefullscreen_btn" style="display: none;" class="btn btn-info btn-xs" value="Close Full Screen">-->
                                </div>
                            <?php

                            if($icmis_user_jcode > 0 and $ucode != 1){
                                $judge_code = "and t3.jcode = $icmis_user_jcode";
                                $select_display_none = "display:none;";
                            }
                            else{
                                $selectOption = "<option value=''>select</option>";
                            }
                                  $sql_reg="SELECT distinct t1.courtno, concat(t3.jname,' ',t3.first_name,' ',t3.sur_name) jname
      FROM roster t1
      INNER JOIN roster_judge t2 ON t1.id = t2.roster_id
      INNER JOIN judge t3 ON t3.jcode = t2.judge_id
      LEFT JOIN cl_printed cp on cp.next_dt = '".date('Y-m-d')."' and cp.roster_id = t1.id and cp.display = 'Y'
      WHERE cp.next_dt is not null and '".date('Y-m-d')."' >= t1.from_date
        AND t1.to_date = '0000-00-00'
        AND t3.jtype = 'R' $judge_code
        AND t3.is_retired = 'N'
        AND t1.display = 'Y'
        AND t2.display = 'Y'
      ORDER BY t3.jcode";


                                $results_reg = mysql_query($sql_reg);
                                ?>
                                <div class="row">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="background-color:silver; font-size: 1vw;">Cause List Date</span>
                                        <input style="font-size: 1vw;" class="form-control dtp" type="text" value="<?=$dtd;?>" name="dtd" id="dtd" readonly="readonly" ></td>
                                        <!--<input class="form-control dtp" type="text" value="30-08-2019" name="dtd" id="dtd" readonly="readonly" ></td>-->

                                    </div>
                                </div>
                                <div class="row">
                                    <select name="courtno" id="courtno" class="form-control" style="<?=$select_display_none;?> font-size: 0.7vw;">
                                        <?php
                                        if (mysql_affected_rows() > 0) {
                                            echo $selectOption;
                                            while ($row_reg = mysql_fetch_array($results_reg)) {
                                                $judge_name = $row_reg["jname"];
                                                echo '<option value="' . $row_reg["courtno"].'">' . str_replace("\\", "", $row_reg["jname"]) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="left_panel_data_row1 row" style="height:95vh; overflow-y: scroll;"></div>
                            </div>
                    <div class="column11">
                    <div class="row row_column11"></div>
                        <div class="row">
                            <div class="column2">
                               <!-- <div class="right_panel_data_row1 row" ><h3>
                                       Welcome to Paper Less Court
                                    </h3></div>-->
                            </div>
                            <div class="column4">
                                <!--<div class="right_panel_data_row2 row"></div>-->
                            </div>
                        </div>
                    </div>
                        <!--</div>-->
                        <!--<input class="btn btn-primary form-control" type="button" name="bt11" value="Submit" onclick='fsubmit();'>-->

                        <div id="r_box" align="center" ></div>

                    <!--</div>-->

                </div>
    <!--<div id="hint" style="text-align: center"></div>
    <div id="newb" style="position: fixed">

        <div id="newa123" style="overflow:auto;"></div>-->



<!--        <div id="overlay" style="display:none;">&nbsp;</div>-->
            </div>





    <!--<div id="overlay" style="display:none;">&nbsp;</div>-->

    <!--</div>-->
        </form>





</div>


                            
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
     $(document).on("focus",".dtp",function(){
        $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
        });
    });

    $(".scrollup").hide();
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        get_item_nos();
        $(document).on('change', '#courtno', function () {
            get_item_nos();
        });
        $(document).on('change', '#dtd', function () {
            get_item_nos();
        });

        $(document).on('click', '.item_no', function () {
            $(".item_no").removeClass("active");
            $(this).addClass("active");
        });

        $(document).on('change', '#dtd', function () {
            var dtd = $("#dtd").val();
            $.ajax({
                url: 'get_cl_date_judges.php',
                cache: false,
                async: true,
                data: {dtd:dtd,flag:'court'},
                beforeSend:function(){
                    //$('.left_panel_data_row1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('#courtno').html(data);
                    get_item_nos();


                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        function get_item_nos(){
            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();

            $.ajax({
                url: 'get_title.php',
                cache: false,
                async: true,
                data: {courtno:courtno,dtd:dtd},
                beforeSend:function(){
                     $('.left_panel_data_row1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('.row_column11').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

            $.ajax({
                url: 'get_item_nos.php',
                cache: false,
                async: true,
                data: {courtno:courtno,dtd:dtd},
                beforeSend:function(){
                    $('.left_panel_data_row1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('.left_panel_data_row1').html(data);
                    //if(data)
                    //$(".scrollup").show();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }

        $(document).on('click', '.item_no', function () {
            var diary_no = this.getAttribute("data-dno");
            var listdt = this.getAttribute("data-listdt");
            var displayboardval1 = this.getAttribute("data-displayboardval1");
            var displayboardval2 = this.getAttribute("data-displayboardval2");
            var cName = this.classList[2];
            var withoutLastFourChars = diary_no.slice(0, -4);
            var lastFour = diary_no.substr(diary_no.length - 4);

            if(cName == 'disabled'){
                $('.column2').html("<h3 style='color:#D81800;'>Deleted From List.</h3>");
                $('.column4').html("");
                return false;
            }
            var curr_date = $("#curr_date").val();
            var jcodes = ""; var sbdb = "";
            if(listdt == curr_date){
               // insert_disp(displayboardval1,diary_no,displayboardval2,jcodes,sbdb);
            }
            /* $.ajax({
             url: 'get_title.php',
             cache: false,
             async: true,
             data: {diary_no:diary_no,listdt:listdt},
             beforeSend:function(){
             $('.column2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
             },
             type: 'POST',
             success: function(data, status) {
             $('.column2').html(data);
             },
             error: function(xhr) {
             alert("Error: " + xhr.status + " " + xhr.statusText);
             }
             });*/

            $.ajax({
                url: 'get_right_panel_data_row2.php',
                cache: false,
                async: true,
                data: {diary_no:diary_no,listdt:listdt},
                beforeSend:function(){
                    $('.column4').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('.column4').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
            $.ajax({
                url: 'get_gist_details.php',
                cache: false,
                async: true,
                data: {diary_no:diary_no,listdt:listdt,withoutLastFourChars:withoutLastFourChars,lastFour:lastFour},
                beforeSend:function(){
                    $('.column2').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('.column2').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
        $(document).on('click', '.scrollup', function () {
            $(".left_panel_data_row1").scrollTop(0);
        });

        $(".left_panel_data_row1").on( 'scroll', function(){
            console.log('Event Fired');
            var y = $(this).scrollTop();
            if (y > 500) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });





    });

    function insert_disp(str,filno,j1,jcodes,sbdb)
    {
        var xhr2 = getXMLHTTP();
        var str1 = str;
        //document.getElementById("clrbrd").disabled = '';
        //alert("Here");
        str1 = str1 + ": :D" + ":" + filno + ":" + j1 + ":" + jcodes + ":" + sbdb;
        var str = "../reader/insert_show.php?str=" + encodeURIComponent(str1);
        //alert(str);
        xhr2.open("GET", str, true);
        xhr2.onreadystatechange = function ()
        {
            if (xhr2.readyState == 4 && xhr2.status == 200)
            {
                var data = xhr2.responseText;
            }
        }// inner function end
        xhr2.send(null);
    }
    </script>
