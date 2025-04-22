function call_cs(d_no,d_yr,ct,cn,cy)
{
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: base_url+'/Common/Case_status/case_status',
        beforeSend: function (xhr) {
            $("#modData").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
        },
        data:{diary_number:d_no,diary_year:d_yr,ct:ct,cn:cn,cy:cy,search_type:'D',opt:2,CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
        .done(function(msg){
            updateCSRFToken();
            $("#modData").html(msg);
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
}

function call_cs1(d_no,d_yr,ct,cn,cy)
{
    var divname = "";
    divname = "newcs123";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.width = 'auto';
    document.getElementById(divname).style.height = '500px';
    document.getElementById(divname).style.overflow = 'scroll';
    document.getElementById(divname).style.marginLeft = '18px';
    document.getElementById(divname).style.marginRight = '18px';
    document.getElementById(divname).style.marginBottom = '25px';
    document.getElementById(divname).style.marginTop = '30px';
    document.getElementById('dv_fixedFor_P').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
    
    $('#overlay').height($(window).height());
    document.getElementById('overlay').style.display = 'block';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: base_url+'/Common/Case_status/case_status',
        beforeSend: function (xhr) {
            $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
            
        },
        //data:{d_no:d_no,d_yr:d_yr,ct:ct,cn:cn,cy:cy,tab:'Case Details',opt:2,CSRF_TOKEN: CSRF_TOKEN_VALUE}
        data:{diary_number:d_no,diary_year:d_yr,ct:ct,cn:cn,cy:cy,search_type:'D',opt:2,CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
        .done(function(msg){
            updateCSRFToken();
            $("#newcs123").html(msg);
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
}
function close_cs()
{
    var divname = "";
    divname = "newcs123";
    document.getElementById('dv_fixedFor_P').style.display = "none";
    document.getElementById(divname).style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

$(document).ready(function(){
    $(document).on('click','[id^=accordion] a',function (event) {
        var accname=$(this).attr('data-parent');

        if(typeof $(this).attr('data-parent') !== "undefined"){
//var collapse_element = event.target;
            var url="";
//    alert(collapse_element);
            var href = this.hash;
            var depId = href.replace("#collapse","");
            var accname1=accname.replace("#accordion","");
            var acccnt=accname1*100;
            var diaryno = document.getElementById('diaryno'+accname1).value;
            if(depId!=(acccnt+1)){
                if(depId==(acccnt+2)) url="../../case_status/get_earlier_court.php";
                if(depId==(acccnt+3)) url="../../case_status/get_connected.php";
                if(depId==(acccnt+4)) url="../../case_status/get_listings.php";
                if(depId==(acccnt+5)) url="../../case_status/get_ia.php";
//    if(depId==6) url="get_earlier_court.php";
                if(depId==(acccnt+6)) url="../../case_status/get_court_fees.php";
                if(depId==(acccnt+7)) url="../../case_status/get_notices.php";
                if(depId==(acccnt+8)) url="../../case_status/get_default.php";
                if(depId==(acccnt+9)) url="../../case_status/get_judgement_order.php";
                if(depId==(acccnt+10)) url="../../case_status/get_adjustment.php";
                if(depId==(acccnt+11)) url="../../case_status/get_mention_memo.php";
                if(depId==(acccnt+12)) url="../../case_status/get_restore.php";
                if(depId==(acccnt+13)) url="../../case_status/get_drop.php";
                if(depId==(acccnt+14)) url="../../case_status/get_appearance.php";
                if(depId==(acccnt+15)) url="../../case_status/get_office_report.php";
                if(depId==(acccnt+16)) url="../../case_status/get_similarities.php";
                if(depId==(acccnt+17)) url="../../case_status/get_caveat.php";
                // var dataString = 'depId='+ depId + '&do=getDepUsers';
                $.ajax({
                    type: 'POST',
                    url:url,
                    beforeSend: function (xhr) {
                        $("#result"+depId).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                    },
                    data:{diaryno:diaryno}
                })
                    .done(function(msg){
                        $("#result"+depId).html(msg);
                    })
                    .fail(function(){
                        alert("ERROR, Please Contact Server Room");
                    });
            }
        }
    });
    $("#radiodn").click(function(){
        $("#dno").removeProp('disabled');
        $("#dyr").removeProp('disabled');
        $("#selct").prop('disabled',true);
        $("#case_no").prop('disabled',true);
        $("#case_yr").prop('disabled',true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
    });

    $("#radioct").click(function(){
        $("#dno").prop('disabled',true);
        $("#dyr").prop('disabled',true);
        $("#dno").val("");
        $("#dyr").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
    });
    $(document).on('click',"input[type='checkbox'][name^='ccchk']", function(){
        if($('#ttlconn').length) {
            var cntr=$('#ttlconn').html();
            var cntr1 = parseInt(cntr);
            if ($(this).is(':checked')) {
                cntr1++;
            }
            else{
                cntr1--;
            }
            $('#ttlconn').html(cntr1);
        }
    });
    $(document).on('click','#checkall', function(){

        $("input[type='checkbox'][name^='ccchk']").each(function () {
            if ($("#checkall").is(':checked')) {
                $(this).prop("checked", true);
            }
            else{
                $(this).prop("checked", false);
            }
        });
    });

    $("input[name=btnGetR]").click(function(){
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if($("#radioct").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if(!regNum.test(cstype)){
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if(!regNum.test(csno)){
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if(!regNum.test(csyr)){
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if(csno == 0){
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(csyr == 0){
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        }
        else if($("#radiodn").is(':checked')){
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if(!regNum.test(diaryno)){
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if(!regNum.test(diaryyear)){
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if(diaryno == 0){
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if(diaryyear == 0){
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        }
        else{
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'POST',
            url:"./verify_get.php",
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr}
        })
            .done(function(msg){
                $("#dv_res1").html(msg);
                // get_subheading();
//            $("#result2").html("");
                get_subheading();
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room");
            });
    });
});