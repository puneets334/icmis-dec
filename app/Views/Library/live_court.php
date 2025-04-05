<?= view('header') ?>
<style>
.dropdown-content {
    display: none;
    /* Start hidden */
    position: absolute;
    background-color: #f6f6f6;
    width: 35vw;
    overflow: auto;
    border: 1px solid #ddd;
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
    /* Highlight on hover */
}
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
                            <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>" />
                            <div class="container-fluid" style="width: 100%; height:100vh; overflow:hidden;">
                                <input type="hidden" name="caseno" id="caseno">
                                <input type="hidden" name="t_cs" id="t_cs">
                                <input type="hidden" name="uid" id="uid" value="<?php echo session()->get('login')['usercode']?>">
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
                                                <input style="font-size: 1vw;" class="form-control dtp  col-md-8" type="text" value="<?php echo date('d-m-Y');?>" name="dtd" id="dtd" readonly="readonly">
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

                                        <div class="left_panel_data_row1 row" style="height:95vh; overflow-y: scroll;"></div>
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
        $("#display_pdf_section").html('<embed src="'+ pdf +'" frameborder="0" width="100%" height="100%">');
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
                CSRF_TOKEN:csrf,
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


    function get_item_nos(){
            var courtno = $("#courtno").val();
            var dtd = $("#dtd").val();
            $("#display_pdf_section").html("");
            $.ajax({
                url: '<?= base_url('Library/LiveCourt/get_title'); ?>',
                cache: false,
                async: true,
                data: {courtno:courtno,dtd:dtd},
                beforeSend:function(){
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
                data: {courtno:courtno,dtd:dtd},
                beforeSend:function(){
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
                CSRF_TOKEN:CSRF_TOKEN_VALUE,
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

    function loadGistDetails(item) {
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
            url: '<?= base_url('Library/LiveCourt/get_gist_details'); ?>',
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
         
    }
});

</script>