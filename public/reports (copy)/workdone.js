$(document).ready(function () {
    $("#btnreport").click(function () {
        var ddl_all_blank = $('#ddl_all_blank').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url + "/Reports/Filing/Report/get_workdone",
            beforeSend: function (xhr) {
                $("#result_main").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: { date: $("#date_for").val(), ddl_all_blank: ddl_all_blank, CSRF_TOKEN: CSRF_TOKEN_VALUE }
        })
            .done(function (msg_new) {
                updateCSRFToken();
                $("#result_main").html(msg_new);
            })
            .fail(function () {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    });
});

$(document).on("click", "[id^='doc_']", function () {
    var tempid = this.id.split('_');
    //alert("type="+tempid[0]+", id="+tempid[1]);
    //return false;
    $('#dv_sh_hd').css("display", "block");
    $('#dv_fixedFor_P').css("display", "block");
    $.ajax({
        type: 'POST',
        url: "./get_workdone_full.php",
        beforeSend: function (xhr) {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: { date: $("#date_for").val(), type: tempid[0], id: tempid[1], name: $("#name_" + tempid[1]).html() }
    })
        .done(function (msg_new) {
            $("#sar").html(msg_new);
        })
        .fail(function () {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
});


$(document).on("click", "[id^='notvdoc_']", function () {
    var tempid = this.id.split('_');
    //alert("type="+tempid[0]+", id="+tempid[1]);
    //return false;
    $('#dv_sh_hd').css("display", "block");
    $('#dv_fixedFor_P').css("display", "block");
    $.ajax({
        type: 'POST',
        url: "./get_workdone_full.php",
        beforeSend: function (xhr) {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: { date: $("#date_for").val(), type: tempid[0], id: tempid[1], name: $("#name_" + tempid[1]).html() }
    })
        .done(function (msg_new) {
            $("#sar").html(msg_new);
        })
        .fail(function () {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
});

$(document).on("click", "[id^='totup_']", function () {
    var tempid = this.id.split('_');
    //alert("type="+tempid[0]+", id="+tempid[1]);
    //return false;
    $('#dv_sh_hd').css("display", "block");
    $('#dv_fixedFor_P').css("display", "block");
    $.ajax({
        type: 'POST',
        url: "./get_workdone_full.php",
        beforeSend: function (xhr) {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: { date: $("#date_for").val(), type: tempid[0], id: tempid[1], name: $("#name_" + tempid[1]).html() }
    })
        .done(function (msg_new) {
            $("#sar").html(msg_new);
        })
        .fail(function () {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
});

$(document).on("click", "[id^='supuser_']", function () {
    var tempid = this.id.split('_');
    //alert("type="+tempid[0]+", id="+tempid[1]);
    //return false;
    $('#dv_sh_hd').css("display", "block");
    $('#dv_fixedFor_P').css("display", "block");
    $.ajax({
        type: 'POST',
        url: "./get_workdone_full.php",
        beforeSend: function (xhr) {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: { date: $("#date_for").val(), type: tempid[0], id: tempid[1], name: $("#name_" + tempid[1]).html() }
    })
        .done(function (msg_new) {
            $("#sar").html(msg_new);
        })
        .fail(function () {
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
});

$(document).on("click", "#sp_close", function () {
    $('#dv_fixedFor_P').css("display", "none");
    $('#dv_sh_hd').css("display", "none");
});
