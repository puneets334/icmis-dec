
$(document).ready(function(){

    $("[id^='rece']").click(function()
    {
        var idd=$(this).attr('id').split('rece');
   // alert('received');
    //alert(idd);return;
        $(this).attr('disabled',true);
        var type='R';
        $.ajax({
            type: 'POST',
            url:"./receive.php",
            beforeSend: function (xhr) {
                $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{id:idd[1],value:type}
        })
        .done(function(msg){
            //$("#result").html(msg);
            alert(msg);

            window.location.reload();
            return;
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
    });
    
    $("[id^='comp']").click(function() {
        var c = confirm("Are You Sure You Want to Dispatch");
        if(c==true)
        {
        var idd = $(this).attr('id').split('comp');
        $(this).attr('disabled', true);
        var type = 'C';
        $.ajax({
            type: 'POST',
            url: "./receive.php",
            //   url:"./return_to_aor.php",
            beforeSend: function (xhr) {
                $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data: {id: idd[1], value: type, nature: idd[2]}
        })
            .done(function (msg) {
                //$("#result").html(msg);
                alert(msg);
                window.location.reload();
                //document.getElementById('newresult').innerText='';
                return;
            })
            .fail(function () {
                alert("ERROR, Please Contact Server Room");
            });
        }
    });
    
    $("[id^='tag']").click(function(){
        var tag='Y';
        var idd=$(this).attr('id').split('tag');
        $(this).attr('disabled',true);
        var type='C';
        $.ajax({
            type: 'POST',
            url:"./receive.php",
            beforeSend: function (xhr) {
                $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{id:idd[1],value:type,tag:tag}
        })
        .done(function(msg){
            //$("#result").html(msg);
            alert(msg);
            window.location.reload();
            return;
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
    });
});

$(document).on("click","#print1",function(){
    var prtContent = $("#dv_content1").html();
    var temp_str=prtContent;
    var WinPrint = window.open('','','left=10,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});
