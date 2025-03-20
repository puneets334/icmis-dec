<?= view('header') ?>
<?php
/*$sql2 = "select * from case_status_flag where flag_name = 'vc_room' and to_date = '0000-00-00 00:00:00' 
and display_flag = 0 and FIND_IN_SET('".$_SESSION['dcmis_user_idd']."', always_allowed_users) and FIND_IN_SET('".$_SESSION['ipadd']."', ip) ";
$sql_ck = mysql_query($sql2) or die(mysql_error()); */
if (empty($checkaccess)) {
    echo "You are not authorized to access this resource";
    exit();
}
?>

<div class="container-fluid m-0 p-0">
    <div class="row clearfix m-1 p-0">
        <div class="col-12 m-0 p-0">
            <form method="post">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header bg-info_ text-white_ font-weight-bolder">VC Room Creation and Send SMS/Email - &nbsp;&nbsp;&nbsp;
                        <label class="radio-inline text-black">
                            <input type="radio" class="selected_flag_radio" name="rdbtn_select" id="radio_all" value="1" checked> <span class="text-warning_">ALL</span>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="selected_flag_radio" name="rdbtn_select" id="radio_selected" value="2"> <span class="text-warning_">Selected (Ex. Consent received via Email, Portal etc.)</span>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="selected_flag_radio" name="rdbtn_select" id="radio_pip" value="3"> <span class="text-warning_">Only Party-in-Person</span>
                        </label>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="row ml-1">
                                <div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="next_dt_addon">Listing Date<span style="color:red;">*</span></span>
                                        </div>
                                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                        <select class="form-control" id="next_dt" aria-describedby="next_dt_addon">
                                            <option value="">-select-</option>
                                            <?php
 
                                            if (!empty($getUpcomingDates)) {
                                                foreach ($getUpcomingDates as $row) {
                                            ?>
                                                    <option value="<?= $row['next_dt']; ?>"><?= date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                            <?php
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>





                                </div>

                                <div>
                                    <div class="input-group mb-3">
                                        <div>
                                            <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12 m-0 p-0" id="result"></div>
                        </div>
                    </div>
                </div>
            </form>

        </div>


    </div>


    <div class="modal fade " id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>


</div>

<script>
    $(document).on('click', '.selected_flag_radio', function() {
        $("#result").html('After appropriate selection, click on search button');
    });
    $(document).on('change', '#next_dt', function() {
        $("#result").html('After appropriate selection, click on search button');
    });
    $("#btn_search").click(function() {        
        var next_dt = $("#next_dt").val();
        $('#result').html("");
        var flag = '';
        if ($("#radio_all").is(':checked')) {
            flag = $("#radio_all").val();
        } else if ($("#radio_selected").is(':checked')) {
            flag = $("#radio_selected").val();
        } else if ($("#radio_pip").is(':checked')) {
            flag = $("#radio_pip").val();
        } else {
            alert("Radio button not selected.");
            return false;
        }

        if (next_dt == '') {
            alert('Listing Date Required');
            $("#next_dt").focus();
            return false;
        } else {
            var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: '<?php echo base_url()?>/MasterManagement/VcRoom/index_get',
                //cache: false,
                //async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    next_dt: next_dt,
                    flag: flag
                },
                type: 'POST',
                beforeSend: function() {
                    $('#result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                success: function(data, status) {
                    updateCSRFToken();
                    $("#result").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });


    $(document).on('click', '.btn_save_send', function() {
        var next_dt = $("#next_dt").val();
        var roster_id = $(this).data('roster_id');
        var courtno = $(this).data('courtno');
        var vc_url = $('.vc_url').filter('[data-roster_id="' + roster_id + '"]').val();
        var vc_item = $('.vc_item').filter('[data-roster_id="' + roster_id + '"]').val();
        var vc_item_csv = selectItem(vc_item);
        //var vc_url_value = $('.vc_url').val();

        var flag = '';
        if (vc_url.length < 5) {
            alert("Please enter VC URL");
            return false;
        }

        if ($("#radio_all").is(':checked')) {
            flag = $("#radio_all").val();
        } else if ($("#radio_selected").is(':checked')) {
            flag = $("#radio_selected").val();
        } else if ($("#radio_pip").is(':checked')) {
            flag = $("#radio_pip").val();
        } else {
            alert("Radio button not selected.");
            return false;
        }

        console.log(vc_item_csv);

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: '<?php echo base_url()?>/MasterManagement/VcRoom/vc_room_save',
            cache: false,
            async: true,
            data: {
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: encodeURI(vc_url),
                vc_items_csv: vc_item_csv,
                vc_item: vc_item,
                courtno: courtno,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data, status) {
                if (data.trim() == 'Data Saved') {

                    if (flag == 1) {
                        
                        create_content_vc_email(next_dt,roster_id,encodeURI(vc_url),vc_item_csv,'vc_url_index_a');

                        
                    } else if (flag == 3) {
                       
                        create_content_vc_pip_email(next_dt,roster_id,encodeURI(vc_url),vc_item_csv,'vc_url_index_a');
                        
                    } else {
                        
                        create_content_vc_email_consent_recv(next_dt,roster_id,encodeURI(vc_url),vc_item_csv,'vc_url_index_b');
                       
                    }

                    //$('.action_save_sent').filter('[data-roster_id="' + roster_id + '"]').html("<div class='text-success'><strong>Success</strong></div>");
                    $('.vc_url_success').filter('[data-roster_id="' + roster_id + '"]').html("<div class='text-success'><strong>Success</strong></div>");


                } else {
                    $('.vc_url_success').filter('[data-roster_id="' + roster_id + '"]').append("<div class='text-danger'>" + data + "</div>");
                }
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });

    async function create_content_vc_email(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                url: '<?php echo base_url()?>/MasterManagement/VcRoom/create_content_vc_email',
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    next_dt: next_dt,
                    roster_id: roster_id,
                    vc_url: evc_url,
                    vc_items_csv: vc_item_csv,
                    vc_qry_from: vc_qry_from
                },
                type: 'POST',
                success: function(data, status) {
                    create_content_vc_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from);
                },
                error: function(xhr) {
                    create_content_vc_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    }

    async function create_content_vc_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: 'create_content_vc_sms.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: vc_url,
                vc_items_csv: vc_item_csv,
                vc_qry_from: vc_qry_from
            },
            type: 'POST',
            success: function(data, status) {

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    async function create_content_vc_pip_email(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: 'create_content_vc_pip_email.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: vc_url,
                vc_items_csv: vc_item_csv,
                vc_qry_from: vc_qry_from
            },
            type: 'POST',
            success: function(data, status) {
                create_content_vc_pip_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
            },
            error: function(xhr) {
                create_content_vc_pip_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    async function create_content_vc_pip_sms(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: 'create_content_vc_pip_sms.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: vc_url,
                vc_items_csv: vc_item_csv,
                vc_qry_from: vc_qry_from
            },
            type: 'POST',
            success: function(data, status) {

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    async function create_content_vc_email_consent_recv(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: 'create_content_vc_email_consent_recv.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: vc_url,
                vc_items_csv: vc_item_csv,
                vc_qry_from: vc_qry_from
            },
            type: 'POST',
            success: function(data, status) {
                create_content_vc_sms_consent_recv(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from);
            },
            error: function(xhr) {
                create_content_vc_sms_consent_recv(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from);
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    async function create_content_vc_sms_consent_recv(next_dt,roster_id,vc_url,vc_item_csv,vc_qry_from)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: 'create_content_vc_sms_consent_recv.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: vc_url,
                vc_items_csv: vc_item_csv,
                vc_qry_from: vc_qry_from
            },
            type: 'POST',
            success: function(data, status) {

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function selectItem(l) {
        /*var checkBoxList=document.getElementsByName("proceeding[]");
        for (var i1 = 0; i1<checkBoxList.length; i1++){
            checkBoxList[i1].checked=false;
        }
        var l=document.getElementById("snoselect").value;*/
        temp = l.split(",");
        var t;
        var p;
        var s = new Array();
        var j = 0;
        for (a in temp) {
            t = temp[a].split("-");
            var k = parseInt(t.length);
            if (k == 2) {
                var f = parseInt(t[0]);
                var f1 = parseInt(t[1]);
                for (var h = f; h <= f1; h++) {
                    s[j] = h;
                    j = j + 1;
                }
            } else {
                s[j] = parseInt(temp[a]);
                j = j + 1;
            }
        }
        return s;

    }

    $(document).on("click", "#prnnt1", function() {
        //var prtContent = $("#prnnt").html();
        //var temp_str=prtContent;
        //var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        // WinPrint.document.write(temp_str);
        // WinPrint.document.close();
        // WinPrint.focus();
        // WinPrint.print();
        var divContents = $("#print_area").html();
        var a = window.open('', '', 'height=1200, width=800');
        //a.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"><html>');
        a.document.write('<link rel="stylesheet" href="../../offline_copying/css/bootstrap.min.css" ><html>');
        a.document.write('<body >');
        a.document.write(divContents);
        a.document.write('</body></html>');
        a.document.close();
        a.print();
    });
</script>