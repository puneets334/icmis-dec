<?=view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-title">Court >> Court Master (NSH) >> Court Master Cause List >> Cause List</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">


                            <form method="post" name="frm" id="frm" action="<?= site_url(uri_string()) ?>">
                            <?= csrf_field() ?>
                                    <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>"/>
                                    <!--<div id="dv_content1"  style="width: 100%; float: right">-->
                                    <div class="container-fluid" style="width: 100%; height:100vh; overflow:hidden;">
                                    <?php
                                    
                                    $ucode = $_SESSION['login']['usercode'];                                     
                                    $icmis_user_jcode = $_SESSION['login']['jcode'];
                                    $dcmis_section = $_SESSION['login']['section'];

                                        $file_list = "";
                                        $cntr = 0;
                                        $chk_slno = 0;
                                        $chk_pslno = 0;
                                        $dtd = date("d-m-Y");
                                      
                                        ?>
                                        <input type="hidden" name="caseno" id="caseno">
                                        <input type="hidden" name="t_cs" id="t_cs">
                                        <input type="hidden" name="uid" id="uid" value="<?php echo $_SESSION['login']['empid']; ?>" >
                                        <input type="hidden" name="sid" id="sid" value="" >
                                        <input type="hidden" name="flnm" id="flnm" value="" >
                                        
                                    <!--    <span class="scrollup"><a href="#" style="font-size:2.5vw; text-decoration: none;" class="glyphicon glyphicon-circle-arrow-up"></a></span>
                                        <div class="menu_plc" style="font-size:1.5vw;">
                                            <a data-placement="bottom" data-toggle="tooltip" title="Open Full Screen!" style="padding-left:5px;" id="openfullscreen_btn" href="#" ><i class="fas fa-expand fa-fw"></i></a>
                                            <a data-placement="bottom" data-toggle="tooltip" title="Close Full Screen!" style="display: none;" id="closefullscreen_btn"  href="#" ><i class="fas fa-compress-alt fa-fw"></i></a>
                                            <a data-placement="bottom" data-toggle="tooltip" title="HomeScreen!" style="padding-left:5px;" href="#" onclick="homepage();">HomeScreen!</a>
                                            <a data-placement="bottom" data-toggle="tooltip" title="LogOut!" style="padding-left:5px;" href="#" onclick="logout();">LogOut!</a>
                                        </div> -->
                                        <div id="s_box" align="center" style="padding:0px;">

                                            <!--<div>-->
                                            <div class="row" >
                                            <div class="col-md-4">
                                                <div style="text-align:left;" class="row">



                                                     
                                                </div>
                                                <?php
                                                $judge_code = '';
                                                $select_display_none = '';
                                                if($dcmis_section == 62){
                                                    $judge_code = "and (t1.courtno = 21 OR t1.courtno = 61 )";
                                                    $select_display_none = "display:none;";
                                                }
                                                if($dcmis_section == 81){
                                                    $judge_code = "and (t1.courtno = 22 OR t1.courtno = 62";
                                                    $select_display_none = "display:none;";
                                                }
                                                if($dcmis_section == 71){
                                                    //computer cell
                                                    $selectOption = "<option value=''>select</option>";
                                                }

                                             
                                                $results_reg = $CourtCausesListModel->getRosterDetailsReport($judge_code);
                                                ?>
                                                <div class="row">
                                                    <div class="input-group">
                                                        <div class="input-group-addon col-md-4" style="background-color:silver; font-size: 1vw;margin-top: 6px;padding-top: 6px;">Cause List Date</div>
                                                        <input style="font-size: 1vw;" class="form-control dtp  col-md-8" type="text" value="<?=$dtd;?>" name="dtd" id="dtd" readonly="readonly" ></td>
                                                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <select name="courtno" id="courtno" class="form-control" style="<?=$select_display_none;?> font-size: 0.7vw;">
                                                        <?php
                                                        if (!empty($results_reg)) {
                                                            echo $selectOption;
                                                            foreach ($results_reg as $row_reg) {
                                                                $judge_name = $row_reg["jname"];
                                                                echo '<option value="' . $row_reg["courtno"].'">' . str_replace("\\", "", $row_reg["jname"]) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="left_panel_data_row1 row" style="height:95vh; overflow-y: scroll;"></div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row row_column11"></div>
                                                <div class="row">
                                                    <div class="column2">
                                                        
                                                    </div>
                                                    <div class="column4">
                                                         
                                                    </div>
                                                </div>
                                            </div>
                                           </div>
                                             
                                            <div id="r_box" align="center" ></div>
 
                                        </div>
                                         
 


                                    </div>

                                    <div class="module_msg"></div>
 
                                </form>
 

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="<?php echo base_url();?>/courtMaster/court_process.js"></script>

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
       /* $(document).on('change', '#dtd', function () {
            get_item_nos();
        }); */

        $(document).on('click', '.item_no', function () {
            $(".item_no").removeClass("active");
            $(this).addClass("active");
        });

        $(document).on('change', '#dtd', function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            var dtd = $("#dtd").val();
            $.ajax({
                url: base_url + "/Court/CourtCauseListController/get_cl_date_judges",
                cache: false,
                async: true,
                data: {dtd:dtd,flag:'reader',CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    //$('.left_panel_data_row1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                   // updateCSRFToken();                   
                    $('#courtno').html(data);
                    get_item_nos();
                   


                },
                error: function(xhr) {
                    //updateCSRFToken();
                    get_item_nos();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        async function get_item_nos(){
            await updateCSRFTokenSync();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();

            $.ajax({
                url: base_url + "/Court/CourtCauseListController/get_title",
                cache: false,
                async: true,
                data: {courtno:courtno,dtd:dtd,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('.left_panel_data_row1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    
                    get_item_nos_sub(courtno,dtd);
                    $('.row_column11').html(data);
                },
                error: function(xhr) {
                   
                   get_item_nos_sub(courtno,dtd);
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

           
            
        }

        async  function get_item_nos_sub(courtno,dtd)
        {
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax({
                url: base_url + "/Court/CourtCauseListController/get_item_nos",
                cache: false,
                async: true,
                data: {courtno:courtno,dtd:dtd,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('.left_panel_data_row1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('.left_panel_data_row1').html(data);
                    
                },
                error: function(xhr) {
                    updateCSRFToken();
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
           
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax({
                url: base_url + "/Court/CourtCauseListController/get_right_panel_data_row2",
                cache: false,
                async: true,
                data: {diary_no:diary_no,listdt:listdt,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('.column4').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    get_gist_details_nsh(diary_no,listdt,withoutLastFourChars,lastFour)
                    $('.column4').html(data);
                },
                error: function(xhr) {
                    get_gist_details_nsh(diary_no,listdt,withoutLastFourChars,lastFour)
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
            
        });

        async function get_gist_details_nsh(diary_no,listdt,withoutLastFourChars,lastFour)
        {
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax({
                url: base_url + "/Court/CourtCauseListController/get_gist_details_nsh",
                cache: false,
                async: true,
                data: {diary_no:diary_no,listdt:listdt,withoutLastFourChars:withoutLastFourChars,lastFour:lastFour,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('.column2').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('.column2').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }


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
        var str = base_url + "/Court/CourtCauseListController/insert_show?str=" + encodeURIComponent(str1);
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


    function updateRecordGist(dno)
    {
        var r = confirm("Please verify gist before Updating.");
        if (r == true) {
            var splt_str = dno.split("_");
            var dno1 = splt_str[0];
            var list_dt = splt_str[1];
            // var roster_id = splt_str[2];
            var rremark = $("#rremark_"+splt_str[0]).val();
            var trimrremark = $.trim(rremark);
            if(trimrremark.length == 0){
                alert("Please enter gist details.");
                return false;
            }
            //var dataString = "dno="+dno1+"&list_dt="+list_dt+"&rremark="+trimrremark;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax
            ({
                type: "POST",
                url: base_url + "/Court/CourtCauseListController/gist_updation",
                data: {dno:dno1, list_dt: list_dt,rremark:trimrremark,CSRF_TOKEN : CSRF_TOKEN_VALUE},
                cache: false,
                success: function(data)
                {
                    updateCSRFToken();
                    // alert(data);
                    if(data == 1){
                        $("#bsave").hide();
                        $(".module_msg").html("<span style='font-size: 2.0vw; color:green;'>Summary Updated Successfully.</span>").fadeIn(1000);
                        $(".module_msg").fadeOut(5000);
                    }
                    else{
                        $(".module_msg").html("<span style='font-size: 2.0vw;  color:red;'>Summary Not Updated.</span>").fadeIn(1000);
                        $(".module_msg").fadeOut(5000);
                    }
                }
            });
        } else {
            
            txt = "You pressed Cancel!";
        }
    }
    function addRecordGist(dno)
    {
        var r = confirm("Please verify gist before saving.");
        if (r == true) {
            var splt_str = dno.split("_");
            var dno1 = splt_str[0];
            var list_dt = splt_str[1];
            //var roster_id = splt_str[2];
            var rremark = $("#rremark_"+splt_str[0]).val();
            var trimrremark = $.trim(rremark);
            if(trimrremark.length == 0){
                alert("Please enter gist details.");
                return false;
            }
            //var dataString = "dno="+dno1+"&list_dt="+list_dt+"&rremark="+trimrremark;
            $.ajax
            ({
                type: "GET",
                url: base_url + "/Court/CourtCauseListController/gist_action",
                data: {dno:dno1, list_dt: list_dt,rremark:trimrremark},
                cache: false,
                success: function(data)
                {
                    updateCSRFToken();
                    // alert(data);
                    if(data == 1){
                        /*var r = "#"+dno;
                        var row = "<tr><td colspan='5' style='text-align:center;color:green; font-weight: bold;'>DN : "+splt_str[0]+" Summary Saved Successfully.</td></tr>";
                        $(r).replaceWith(row);*/
                        $("#bsave").hide();
                        $(".module_msg").html("<span style='font-size: 2.0vw; color:green;'>Summary Saved Successfully.</span>").fadeIn(1000);
                        $(".module_msg").fadeOut(5000);
                    }
                    else{
                        $(".module_msg").html("<span style='font-size: 2.0vw; color:red;'>Summary Not Saved.</span>").fadeIn(1000);
                        $(".module_msg").fadeOut(5000);
                    }
                }
            });
        } else {
            updateCSRFToken();
            txt = "You pressed Cancel!";
        }
    }


</script>