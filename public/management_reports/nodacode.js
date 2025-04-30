
$(document).ready(function(){
    $("#btnreport").click(function(){
        $("#btnreport").attr("disabled", true);
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: base_url + "/ManagementReports/NoDaCode/get_nodacode_report",
            beforeSend: function (xhr) {
                //$("#result_main").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{part:1 ,CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg_new){
            updateCSRFToken();
            $("#result_main").html(msg_new);
            $("#btnreport").removeAttr("disabled");
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room"); 
            $("#btnreport").removeAttr("disabled");
        });
    });
    
});
