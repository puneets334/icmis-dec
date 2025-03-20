$(document).ready(function(){
    $(document).on('click','#sub',function(){
        getDetails();
    });
    
    $(document).on('click','#btnPrintable',function(){
        save_off_report();

    });

    $(document).on('click','.cl_link',function(){
        var t_h_cno = $('#caveat_number').val();
        var t_h_cyt = $("#caveat_year :selected").val();
        var idd=$(this).attr('id');
        var ex_btnlink=idd.split('btnlink_');
        var hd_link=$('#hd_caveat_no'+ex_btnlink[1]).val();
        var hd_caveat_rec_dt=$('#hd_caveat_rec_dt'+ex_btnlink[1]).val();
        var hd_rec_date=$('#hd_rec_date').val();
        var cn_res=confirm("Are you sure you want to link Caveat no. "+t_h_cno+'/'+t_h_cyt+' with diary no. '+hd_link.substr(0,hd_link.length-4)+'/'+hd_link.substr(-4));
        if(cn_res==true)
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: '/Caveat/Similarity/get_diary_linked',
                cache: false,
                async: true,
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,d_no: t_h_cno,d_yr:t_h_cyt,hd_link:hd_link,hd_rec_date:hd_rec_date,hd_caveat_rec_dt:hd_caveat_rec_dt,check_caveat: 'Y'},
                type: 'GET',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#sub').attr('disabled',false);
                    alert(data+' '+hd_link.substr(0,hd_link.length-4)+'/'+hd_link.substr(-4));
                    getDetails();
                    //notice_pnt(hd_link);


                    /*if(data.trim()=='Caveat already linked with Diary No.')
                    {
                        alert(data+' '+hd_link.substr(0,hd_link.length-4)+'/'+hd_link.substr(-4));
                    }
                    else {

                        if(data.includes("party namely")){

                            var cn_res = confirm(data + " Are you sure you want to link caveat no. " + t_h_cno + '/' + t_h_cyt + ' with diary no. ' +hd_link.substr(0,hd_link.length-4)+'/'+hd_link.substr(-4));
                            if (cn_res == true) {
                                $.ajax({
                                    url: 'get_diary_linked.php',
                                    cache: false,
                                    async: true,
                                    data: {
                                        d_no: t_h_cno,
                                        d_yr: t_h_cyt,
                                        hd_link: hd_link,
                                        hd_rec_date: hd_rec_date,
                                        hd_caveat_rec_dt: hd_caveat_rec_dt
                                    },
                                    type: 'POST',
                                    success: function (data, status) {
                                        alert(data);
                                        $('#sub').attr('disabled', false);
                                        if (data.trim() != 'Caveat already linked with Diary No.') {
                                               getDetails();
                                               notice_pnt(hd_link);
                                        }
                                    },
                                    error: function (xhr) {
                                        updateCSRFToken();
                                    }

                                });
                            }


                            // }
                        }
                        else{
                             getDetails();
                             notice_pnt(hd_link);
                        }
                    }*/
                },
                error: function(xhr) {
                    updateCSRFToken();
                }

            });
        }
        /* if(cn_res==true)
         {
         $.ajax({
             url: 'get_diary_linked.php',
             cache: false,
             async: true,
             data: {d_no: t_h_cno,d_yr:t_h_cyt,hd_link:hd_link,hd_rec_date:hd_rec_date,hd_caveat_rec_dt:hd_caveat_rec_dt},
 //            beforeSend: function () {
 //                $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
 //            },
             type: 'POST',
             success: function(data, status) {

                alert(data);
                    $('#sub').attr('disabled',false);
 //                   $('#btnlink_'+ex_btnlink[1]).css('display','none');
                    if(data.trim()!='Caveat already linked with Diary No.')
                    {
 //                   getDetails();
 //alert(hd_link);
 notice_pnt(hd_link);
                    }
                },
             error: function(xhr) {
                 alert("Error: " + xhr.status + " " + xhr.statusText);
             }

         });
     }*/
    });

    $(document).on('click','.cl_unlink',function(){

//         var t_h_cno = $('#caveat_number').val();
//    var t_h_cyt = $('#caveat_year').val();
        var idd=$(this).attr('id');
        var ex_btnlink=idd.split('hd_unlink');
        var hd_caveat_no=$('#hd_caveat_no'+ex_btnlink[1]).val();

        var hd_linked_no=$('#hd_linked_no'+ex_btnlink[1]).val();

//    var hd_caveat_rec_dt=$('#hd_caveat_rec_dt'+ex_btnlink[1]).val();
//    var hd_rec_date=$('#hd_rec_date').val();
//    var dv_mn_case=$('#dv_mn_case').html();
        var sp_cav_diary_lnl_dt=$('#sp_cav_diary_lnl_dt'+ex_btnlink[1]).html();
        var cn_res=confirm("Are you sure you want to unlink caveat No "+ hd_linked_no.substr(0,hd_linked_no.length-4)+'/'+hd_linked_no.substr(-4)+' with '+ hd_caveat_no.substr(0,hd_caveat_no.length-4)+'/'+hd_caveat_no.substr(-4));
        if(cn_res==true)
        {
            $.ajax({
                //url: 'get_diary_unlinked.php',
                url: '/Caveat/Similarity/get_diary_unlinked',
                cache: false,
                async: true,
                data: {hd_caveat_no: hd_caveat_no,hd_linked_no:hd_linked_no,sp_cav_diary_lnl_dt:sp_cav_diary_lnl_dt},
//            beforeSend: function () {
//                $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
//            },
                type: 'GET',
                success: function(data, status) {
                    updateCSRFToken();
                    alert(data);

//                   $('#btnlink_'+ex_btnlink[1]).css('display','none');
                    if(data.trim()!='Caveat already linked with Diary No.')
                        getDetails();
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        }
    });

    $(document).on('click','.cl_c_diary',function(){
        var d_no=$(this).html();
        var sp_d_no=d_no.split('-');
        var idd=$(this).attr('id');
//        var sp_id=idd.split('sp_c_diary');
//        var hd_diary_no=$('#hd_link'+sp_id[1]).val();
        var d_yr=sp_d_no[1];
        var d_no=sp_d_no[0];
//        $('#hd_diary_nos').val(hd_diary_no);
        document.getElementById('ggg').style.width = 'auto';
        document.getElementById('ggg').style.height = ' 500px';
        document.getElementById('ggg').style.overflow = 'scroll';

        document.getElementById('ggg').style.marginLeft = '18px';
        document.getElementById('ggg').style.marginRight = '18px';
        document.getElementById('ggg').style.marginBottom = '25px';
        document.getElementById('ggg').style.marginTop = '30px';
        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        $.ajax({
            url: '../case_status/case_status_process.php',
            cache: false,
            async: true,
            data: {d_no: d_no, d_yr: d_yr},
            beforeSend: function () {
                $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function (data, status) {

                $('#ggg').html(data);
                add_button();
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });

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
                if(depId==(acccnt+2)) url="../case_status/get_earlier_court.php";
                if(depId==(acccnt+3)) url="../case_status/get_connected.php";
                if(depId==(acccnt+4)) url="../case_status/get_listings.php";
                if(depId==(acccnt+5)) url="../case_status/get_ia.php";
//    if(depId==6) url="get_earlier_court.php";
                if(depId==(acccnt+6)) url="../case_status/get_court_fees.php";
                if(depId==(acccnt+7)) url="../case_status/get_notices.php";
                if(depId==(acccnt+8)) url="../case_status/get_default.php";
                if(depId==(acccnt+9)) url="../case_status/get_judgement_order.php";
                if(depId==(acccnt+10)) url="../case_status/get_adjustment.php";
                if(depId==(acccnt+11)) url="../case_status/get_mention_memo.php";
                if(depId==(acccnt+12)) url="../case_status/get_restore.php";
                if(depId==(acccnt+13)) url="../case_status/get_drop.php";
                if(depId==(acccnt+14)) url="../case_status/get_appearance.php";
                if(depId==(acccnt+15)) url="../case_status/get_office_report.php";
                if(depId==(acccnt+16)) url="../case_status/get_similarities.php";

                // var dataString = 'depId='+ depId + '&do=getDepUsers';
                $.ajax({
                    type: 'POST',
                    url:url,
                    beforeSend: function (xhr) {
                        $("#result"+depId).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='images/load.gif'></div>");
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
});

function getDetails()
{
    var caveat_number = $("#caveat_number").val();
    var caveat_year = $("#caveat_year :selected").val();
    if (caveat_number.length == 0) {
        alert("Please enter caveat number");
        $("#caveat_number").focus();
        validationError = false;
        return false;
    }else if (caveat_year.length == 0) {
        alert("Please select caveat year");
        $("#caveat_year").focus();
        validationError = false;
        return false;
    }
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: base_url+'/Caveat/Similarity/get_report',
        cache: false,
        async: true,
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,caveat_number: caveat_number,caveat_year:caveat_year},
        beforeSend: function () {
            //$('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            $('#div_result').html(data);
        },
        error: function(xhr) {
            updateCSRFToken();
            //alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

function closeData()
{
    document.getElementById('ggg').scrollTop=0;

    document.getElementById('dv_fixedFor_P').style.display="none";
    document.getElementById('dv_sh_hd').style.display="none";
}

function notice_pnt(hd_link)
{
    var d_no = $('#caveat_number').val();
    var d_yr = $('#caveat_year').val();
    document.getElementById('ggg').style.width = 'auto';
    document.getElementById('ggg').style.height = ' 500px';
    document.getElementById('ggg').style.overflow = 'scroll';

    document.getElementById('ggg').style.marginLeft = '18px';
    document.getElementById('ggg').style.marginRight = '18px';
    document.getElementById('ggg').style.marginBottom = '25px';
    document.getElementById('ggg').style.marginTop = '30px';
    document.getElementById('dv_sh_hd').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
    $.ajax({
        url: '../caveat/get_notice.php',
        cache: false,
        async: true,
        data: {d_no:d_no,d_yr:d_yr,hd_link:hd_link},
        beforeSend: function () {
            $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function (data, status) {

            $('#ggg').html(data);
            getDetails();
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });

}


function save_off_report()
{
    var hd_caveat_no=$('#hd_caveat_no').val();

    var ggg=$('#ggg').html();
    var sp_d_no='';
    $('.cl_diary_no').each(function(){
        if(sp_d_no=='')
            sp_d_no=$(this)[0].outerHTML+'~~~'+$(this).attr('id');
        else
            sp_d_no=sp_d_no+'~!@#$'+$(this)[0].outerHTML+'~~~'+$(this).attr('id');
    });
    sp_d_no=escape('<link rel="stylesheet" href="../css/menu_css.css">'+sp_d_no);
//        alert(sp_d_no);
    $.ajax({
        url: 'save_caveat_report.php',
        cache: false,
        async: true,
        data: {hd_caveat_no: hd_caveat_no,sp_d_no:sp_d_no},

        type: 'POST',
        success: function(data, status) {
//                alert(data);
            if(data.trim()==1)
            {
                alert("Record Updated Successfully");
            }
            else
            {
                alert("Problem in saving record");
            }
//                $('#div_result').html(data);
            var prtContent = document.getElementById('ggg');

            var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
//  WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.write('<link rel="stylesheet" href="../css/menu_css.css">'+prtContent.innerHTML);
            WinPrint.print();
            get_report();
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}