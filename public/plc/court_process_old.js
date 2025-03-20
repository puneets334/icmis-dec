var elem = document.documentElement;

function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
    }
}

function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
}

function homepage() {
    window.location = '../menu.php';
}
function logout() {
    window.location = '../logout.php';
}

$(document).on('click','#openfullscreen_btn', function(){
    $("#openfullscreen_btn").hide();
    $("#closefullscreen_btn").show();
    openFullscreen();
});

$(document).on('click','#closefullscreen_btn', function(){
    $("#closefullscreen_btn").hide();
    $("#openfullscreen_btn").show();
    closeFullscreen();
});

var dtCh = "/";
var minYear = 1900;
var maxYear = 2100;


function setFocusToTextBox(cb) {
    var textbox = document.getElementById('hdremp' + cb);
    $("#hdremp" + cb).focus();
    textbox.scrollIntoView();
}




function call_mg()
{
    $('#intabdiv3').toggle();
}
function close_w(cnt)
{
    var divname = "";
    if (cnt == 1)
        divname = "newb";
    if (cnt == 2)
        divname = "newc";
    if (cnt == 3)
        divname = "newa";
    document.getElementById(divname).style.display = 'none';
    $('#newa').append($('#newa123'));
    if (cnt == 1)
        check_parties();
}


function closeW()
{
    $('#fade').click();
}




//<!-- Function for Creating XMLHTTP Request --->
function getXMLHTTP()
{ //fuction to return the xml http object
    var xmlhttp = false;
    try {
        xmlhttp = new XMLHttpRequest();
    }
    catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e1) {
                xmlhttp = false;
            }
        }
    }
    return xmlhttp;
}

function getXMLHttpRequestObject()
{
    var xmlhttp;
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {

            xmlhttp = false;
        }
    }
    return xmlhttp;
}
//<!-- ***End of Creating Object --->






function fsubmit()
{
    var mf = 'M';
    /*if (document.frm.mf[0].checked)
        mf = document.frm.mf[0].value;
    if (document.frm.mf[1].checked)
        mf = document.frm.mf[1].value;*/



    var courtno = document.getElementById("courtno").value;

    var dtd = document.getElementById("dtd").value;


    document.getElementById("hint").innerHTML = "<table align=center><tr><td><img src='../images/load.gif'></td></tr></table>";
    var ajaxRequest; // The variable that makes Ajax possible!
    try {
        ajaxRequest = new XMLHttpRequest(); // Opera 8.0+, Firefox, Safari
    } catch (e)
    {
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer Browsers
        } catch (e)
        {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e)
            {
                alert("Your browser broke!"); // Something went wrong
                return false;
            }
        }
    }
    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function ()
    {
        if (ajaxRequest.readyState == 4) {
            document.getElementById("hint").innerHTML = '';
            document.getElementById("r_box").innerHTML = ajaxRequest.responseText;
            //make_paps_div();
        }
    }
    var url = "court_process_get.php";
    url = url + "?&dtd=" + dtd + "&mf=" + mf + "&courtno=" + courtno;
    ajaxRequest.open("GET", url, true);
    ajaxRequest.send(null);
}




function call_office_report(d_no,d_yr,or_path)
{
    var divname = "";
    divname = "newcs";
    document.getElementById(divname).style.display = 'block';
    $('#' + divname).width($(window).width() - 150);
    $('#' + divname).height($(window).height() - 120);
    $('#newcs123').height($('#newcs').height() - $('#newcs1').height() - 50);
    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    document.getElementById(divname).style.marginTop = "-" + newY + "px";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.zIndex = 10;
    $('#overlay').height($(window).height());
    document.getElementById('overlay').style.display = 'block';
    $.ajax({
        type: 'POST',
        url:"office_report_get.php",
        beforeSend: function (xhr) {
            $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data:{d_no:d_no,d_yr:d_yr,or_path:or_path}
    })
        .done(function(msg){
            $("#newcs123").html(msg);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
}

function call_cs(d_no,d_yr,ct,cn,cy)
{
    var divname = "";
    divname = "newcs";
    document.getElementById(divname).style.display = 'block';
    $('#' + divname).width($(window).width() - 150);
    $('#' + divname).height($(window).height() - 120);
    $('#newcs123').height($('#newcs').height() - $('#newcs1').height() - 50);
    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    document.getElementById(divname).style.marginTop = "-" + newY + "px";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.zIndex = 10;
    $('#overlay').height($(window).height());
    document.getElementById('overlay').style.display = 'block';
    $.ajax({
        type: 'POST',
        url:"../case_status/case_status_process.php",
        beforeSend: function (xhr) {
            $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data:{d_no:d_no,d_yr:d_yr,ct:ct,cn:cn,cy:cy,tab:'Case Details',opt:2}
    })
        .done(function(msg){
            $("#newcs123").html(msg);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
}
function close_cs()
{
    var divname = "";
    divname = "newcs";
    document.getElementById(divname).style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

