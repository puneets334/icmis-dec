//$(document).ready(function(){
//$(".data_page_open").each(function () {
//    $(this).unbind().click(function () {
//
//        var this_page = ($(this).attr("data-page"));
//
//        $.ajax({
//            url: '../content.php',
//            cache: false,
//            async: true,
//            data: {this_page: this_page},
//            beforeSend: function () {
//                $('.main-content').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
//            },
//            type: 'POST',
//            success: function (data, status) {
//                $('.main-content').html(data);
//            },
//            error: function (xhr) {
//                alert("Error: " + xhr.status + " " + xhr.statusText);
//            }
//        });
//
//    });
//});

//
//$(".input_masquerade").change(function () {
//    alert("dfk");
//    var usercode = $(this).val();
//    $.ajax({
//        url: 'set_masquerade.php',
//        cache: false,
//        async: true,
//        data: {usercode: usercode},
//        beforeSend: function () {
//            $('.masquerade_result').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
//        },
//        type: 'POST',
//        success: function (data, status) {
//            //alert(data);
//            if(data == 1){
//                //window.location.replace("https://10.40.186.23/icmis/index.php");
//                //window.location.href = "https://10.40.186.23/icmis/index.php"
//                window.location.reload();
//                //parent.location.reload();
//                // window.location.href='/icmis/index.php';
//                //$('.masquerade_result').html('<a href="index.php">click</a>');
//            }
//            else{
//                $('.masquerade_result').html('Error!');
//            }
//        },
//        error: function (xhr) {
//            alert("Error: " + xhr.status + " " + xhr.statusText);
//        }
//    });
//
//});

//$(".input_da").change(function () {
//    var input_da = $(this).val();
//    var this_page ='dashboard/pendency_l2.php';
//    $.ajax({
//        url: this_page,
//        cache: false,
//        async: true,
//        data: {input_da:input_da},
//        beforeSend: function () {
//            $('.main-content').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
//        },
//        type: 'POST',
//        success: function (data, status) {
//            $('.main-content').html(data);
//        },
//        error: function (xhr) {
//            alert("Error: " + xhr.status + " " + xhr.statusText);
//        }
//    });
//});


//});

