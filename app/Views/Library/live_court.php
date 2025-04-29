<?= view('header') ?>
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
        padding: 0px;
   /*      padding: 0px 5px 5px 40px;
       position: relative;
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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Live Court</h3>
                    </div>

                    <div class="card-body">

                        <?php echo form_open();
                        csrf_field(); ?>
                        <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                        <div class="container-fluid" style="width: 100%; height:100vh; overflow:hidden;">
                            <input type="hidden" name="caseno" id="caseno">
                            <input type="hidden" name="t_cs" id="t_cs">
                            <input type="hidden" name="uid" id="uid" value="<?php echo session()->get('login')['usercode'] ?>">
                            <input type="hidden" name="sid" id="sid" value="">
                            <input type="hidden" name="flnm" id="flnm" value="">

                            <span class="scrollup">
                                <a href="#" style="font-size:2.5vw; text-decoration: none;" class="glyphicon glyphicon-circle-arrow-up"></a>
                            </span>

                            <div class="menu_plc" style="font-size:1.5vw;">
                                <a data-placement="bottom" data-toggle="tooltip" title="Open Full Screen!" style="padding-left:5px;" id="openfullscreen_btn" href="#">
                                    <span class="glyphicon glyphicon-resize-full"></span>
                                </a>
                                <a data-placement="bottom" data-toggle="tooltip" title="Close Full Screen!" style="display: none;" id="closefullscreen_btn" href="#">
                                    <span class="glyphicon glyphicon-resize-small"></span>
                                </a>
                                <a data-placement="bottom" data-toggle="tooltip" title="HomeScreen!" style="padding-left:5px;" href="#" onclick="homepage();">
                                    <span style="color:green;" class="glyphicon glyphicon-home"></span>
                                </a>
                                <a data-placement="bottom" data-toggle="tooltip" title="LogOut!" style="padding-left:5px;" href="#" onclick="logout();">
                                    <span style="color:red;" class="glyphicon glyphicon-log-out"></span>
                                </a>
                            </div>

                            <div id="s_box" align="center" style="padding:0px;">
                                <div class="row">
                                    <div class="col-md-4 column1">
                                        <div class="row">
                                            <div class="input-group">
                                                <span class="input-group-addon  col-md-4" style="background-color:silver;font-size: 1vw;margin-top: 6px;padding-top: 6px;">Cause List Date</span>
                                                <input style="font-size: 1vw;" class="form-control dtp  col-md-8" type="text" value="<?php echo date('d-m-Y'); ?>" name="dtd" id="dtd" readonly="readonly">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <select name="courtno" id="courtno" class="form-control">
                                                <option value="0">-Select-</option>
                                                <?php for ($i = 1; $i <= 22; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?> <?= in_array($i, [21, 22]) ? '(Registrar)' : '' ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>

                                        <div class="left_panel_data_row1 row" style="height:80vh; overflow-y: scroll;"></div>
                                    </div>

                                    <div class="col-md-8 column11">
                                        <div class="row row_column11"></div>
                                        <div class="row">
                                            <div class="column2" style="height:95vh; overflow-y: scroll;"></div>
                                            <div class="column4">
                                                <div style="text-align: left; padding:0px;">
                                                    <p id="display_pdf_title" style="font-size: 1.2vw; color: #4169E1;"></p>
                                                    <div class="embed-responsive" style="padding-bottom: 97%;">
                                                        <div id="display_pdf_section" style="height:90vh;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    $(document).ready(function() {

        $("#dtd").datepicker();
        $('[data-toggle="tooltip"]').tooltip();

        // Initial token update and data fetch

        get_item_nos();

        $(document).on('change', '#courtno, #dtd', function() {
            get_item_nos();
        });

        $(document).on('click', '.item_no', function() {
            $(".item_no").removeClass("active");
            $(this).addClass("active");
            loadGistDetails($(this));
        });

        $(document).on('click', '.scrollup', function() {
            $(".left_panel_data_row1").scrollTop(0);
        });

        $(".left_panel_data_row1").on('scroll', function() {
            var y = $(this).scrollTop();
            if (y > 500) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $(document).on("click", '.pdflink', function() {
            var pdf = $(this).data('file');
            var title = $(this).attr('title');
            $("#display_pdf_title").html(title);
            $("#display_pdf_section").html('<embed src="' + pdf + '" frameborder="0" width="100%" height="100%">');
        });

        function gettitles() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();
            var CSRF_TOKEN_VALUE = $('[name="<?= csrf_token() ?>"]').val();

            $("#display_pdf_section").html("");

            // Clear previous content
            $('.left_panel_data_row1').html('<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');

            // Fetch titles
            $.ajax({
                url: '<?= base_url('Library/LiveCourt/gettitles'); ?>',
                type: 'POST',
                data: {
                    CSRF_TOKEN: csrf,
                    courtno: courtno,
                    dtd: dtd,

                },
                success: function(data) {

                    updateCSRFToken();
                    $('.row_column11').html(data.html);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }


        function get_item_nos() {
            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();
            $("#display_pdf_section").html("");
            $.ajax({
                url: '<?= base_url('Library/LiveCourt/get_title'); ?>',
                cache: false,
                async: true,
                data: {
                    courtno: courtno,
                    dtd: dtd
                },
                beforeSend: function() {
                    $('.left_panel_data_row1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'GET',
                success: function(data, status) {
                    $('.row_column11').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

            $.ajax({
                url: '<?= base_url('Library/LiveCourt/get_item_nos'); ?>',
                cache: false,
                async: true,
                data: {
                    courtno: courtno,
                    dtd: dtd
                },
                beforeSend: function() {
                    $('.left_panel_data_row1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'GET',
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

        function get_item_nos_old() {


            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();


            // Fetch item numbers
            $.ajax({
                url: '<?= base_url('Library/LiveCourt/get_item_nos'); ?>',
                type: 'POST',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    courtno: courtno,
                    dtd: dtd,

                },
                success: function(data) {
                    updateCSRFToken(); // Adjust based on server response structure
                    $('.left_panel_data_row1').html(data.html); // Adjust to your expected structure
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }

        /* function loadGistDetails(item) {
             var diary_no = item.data("dno");
             var listdt = item.data("listdt");
             var withoutLastFourChars = diary_no.slice(0, -4);
             var lastFour = diary_no.substr(diary_no.length - 4);
             var CSRF_TOKEN_VALUE = $('[name="<?= csrf_token() ?>"]').val();

             if (item.hasClass('disabled')) {
                 $('.column2').html("<h3 style='color:#D81800;'>Deleted From List.</h3>");
                 $('.column4').html("");
                 return;
             }

             $.ajax({
                 url: '<? //= base_url('Library/LiveCourt/get_gist_details'); 
                        ?>',
                 type: 'POST',
                 data: {
                     CSRF_TOKEN:csrf,
                     diary_no: diary_no,
                     listdt: listdt,
                     withoutLastFourChars: withoutLastFourChars,
                     lastFour: lastFour,
                     
                 },
                 beforeSend: function() {
                     $('.column2').html('<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                 },
                 success: function(data) {
                     updateCSRFToken(); // Adjust based on server response structure
                     $('.column2').html(data.html); // Adjust to your expected structure
                     
                 },
                 error: function(xhr) {
                     updateCSRFToken();
                     alert("Error: " + xhr.status + " " + xhr.statusText);
                 }
             });
              
         } */
    });


    $(document).on('click', '.item_no', function() {
        var $this = $(this);
        var diary_no = this.getAttribute("data-dno");
        var listdt = this.getAttribute("data-listdt");
        var displayboardval1 = this.getAttribute("data-displayboardval1");
        var displayboardval2 = this.getAttribute("data-displayboardval2");
        var cName = this.classList[2];
        var withoutLastFourChars = diary_no.slice(0, -4);
        var lastFour = diary_no.substr(diary_no.length - 4);
        $("#display_pdf_section").html("");
        if (cName == 'disabled') {
            $('.column2').html("<h3 style='color:#D81800;'>Deleted From List.</h3>");
            $('.column4').html("");
            return false;
        }
        var curr_date = $("#curr_date").val();
        var jcodes = "";
        var sbdb = "";
        if (listdt == curr_date) {
            // insert_disp(displayboardval1,diary_no,displayboardval2,jcodes,sbdb);
        }

        var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?= base_url('Library/LiveCourt/get_gist_details'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                diary_no: diary_no,
                listdt: listdt,
                withoutLastFourChars: withoutLastFourChars,
                lastFour: lastFour
            },
            beforeSend: function() {
                $this.addClass('disabled'); 
                $this.prop('disabled', true);
                $('.column2').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('.column2').html(data);
            },
            complete:function()
            {
                $this.removeClass('disabled');
                $this.prop('disabled', false);
                updateCSRFToken();                
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    
    $(document).on('click', '.scrollup', function() {
        $(".left_panel_data_row1").scrollTop(0);
    });

    $(".left_panel_data_row1").on('scroll', function() {
        console.log('Event Fired');
        var y = $(this).scrollTop();
        if (y > 500) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });


    $(document).on("click", '.pdflink', function() {
        $("#display_pdf_section").html("");
        var pdf = $(this).data('file');
        var title = $(this).attr('title');
        //if($("#dv_shw_pdf").length > 0) {
        $("#display_pdf_title").html(title);
        $("#display_pdf_section").html('<embed  src="' + pdf + '" frameborder="0" width="100%" height="100%">');
        //}

    });
</script>