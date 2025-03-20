<?=view('header'); 

$search_details = session()->get('filing_details');
?>
 
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?= view('Filing/filing_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <h4 class="basic_heading"> Verification Details </h4>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="row ">

                                            <div class="col-md-5">
                                                <div class="form-group row">
                                                <!-- <button class="btn btn-primary" onclick="redirectToCategory('category')">Edit</button> -->

                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Cases remaining to be verified till</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" class="form-control" id="verificationDate" name="verificationDate" value="<?= date('Y-m-d'); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($get_verification_text)) { ?>
                                                <div class="cl_center"><b>
                                                        <font color="red"> <?php echo $get_verification_text; ?></font>
                                                    </b></div>
                                            <?php } ?>




                                        </div>

                                    </div>

                                    <?php if (empty($get_verification_text)) { ?>
                                        <?= view('Filing/add_button'); ?>


                                        <?php if (session()->has('success')) : ?>
                                            <div class="alert alert-success">
                                                <?= session('success') ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (session()->has('info')) : ?>
                                            <div class="alert alert-info">
                                                <?= session('info') ?>
                                            </div>
                                        <?php endif; ?>
                                        <?= view('Filing/verification_search'); ?>


                                        <input type="hidden" name="sub_cate" id="sub_cate" value=" <?php foreach ($cat as $cat_details) : ?> <?= $cat_details->id   ?> <?php endforeach; ?>"> <br>
                                        <?php if (!empty($Url_Similarity)) {
                                            // echo $Url_Similarity;
                                            ?>
                                           
                                           <hr>
                      <button type="button" class="btn btn-primary" id="submit" onclick="getSimilarityDetails()" style="margin: 0 auto;display: block;">View Similarities</button>
                      <hr>
                        <div id="div_result"></div>
                                     <?php   } ?>

                                        <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <?php $tagging_user = 0; ?>
                    <?php $diary_no = session('diary_no'); ?>
                    <input type="hidden" name="hd_tagging_user" id="hd_tagging_user" value="<?php echo $tagging_user; ?>" />
                    <input type="hidden" name="hd_diary_nos" id="hd_diary_nos" value="<?= $diary_no ?>" />



                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>

        <!-- /.container-fluid -->
        <!--  Start Similarity -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

<?php } ?>


</section>
<!-- <script>
function redirectToCategory(category) {
    window.location.href = "<?php echo site_url('/Filing/Category?cat='); ?>" + category;

}
</script> -->
<script>

function getSimilarityDetails()
{
    var diary_number = '<?php echo $search_details['diary_number'] ?>';
    var diary_year = '<?php echo $search_details['diary_year']?>';
    if (diary_number.length == 0) {
        alert("Please enter Diary number");
        $("#diary_number").focus();
        validationError = false;
        return false;
    }else if (diary_year.length == 0) {
        alert("Please select Diary year");
        $("#diary_year").focus();
        validationError = false;
        return false;
    }
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: base_url+'/Filing/Similarity/viewSimilarity',
        cache: false,
        async: true,
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_number: diary_number,diary_year:diary_year},
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



    var other_catg = ['10', '20', '46', '75', '87', '101', '115', '129', '141', '151', '163', '182', '201', '215', '227', '250', '259', '262', '270', '276', '289', '295', '300', '304', '311'];

    $(document).ready(function() {
        $("#radiodn").click(function() {
            $("#dno").removeProp('disabled');
            $("#dyr").removeProp('disabled');
            $("#selct").prop('disabled', true);
            $("#case_no").prop('disabled', true);
            $("#case_yr").prop('disabled', true);
            $("#selct").val("-1");
            $("#case_no").val("");
            //$("#case_yr").val("");
        });

        $("#radioct").click(function() {
            $("#dno").prop('disabled', true);
            $("#dyr").prop('disabled', true);
            $("#dno").val("");
            //$("#dyr").val("");
            $("#selct").removeProp('disabled');
            $("#case_no").removeProp('disabled');
            $("#case_yr").removeProp('disabled');
        });


        $(document).on('click', '.cl_details', function() {
            //        var t_h_cno = $('#t_h_cno').val();
            //        var t_h_cyt = $('#t_h_cyt').val();
            var idd = $(this).attr('id');
            var sp_id = idd.split('_');
            var hd_diary_no = $('#hd_diary_no' + sp_id[1]).val();
            var d_yr = hd_diary_no.substr(-4);
            var d_no = hd_diary_no.substr(0, (hd_diary_no.length) - 4);
            $('#hd_diary_nos').val(hd_diary_no);
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
                //url: '../case_status/case_status_process.php',
                url: 'http://10.40.186.139:92/Filing/Verification/verifySearch',
                cache: false,
                async: true,
                //data: {d_no: d_no, d_yr: d_yr},
                data: {
                    dno: d_no,
                    dyr: d_yr
                },
                beforeSend: function() {
                    $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {

                    $('#ggg').html(data);
                    //                add_button();
                    add_button(d_no, d_yr);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });
        $(document).on('click', '.cl_a_rv', function() {
            var hd_tagging_user = $('#hd_tagging_user').val();
            var idd = $(this).attr('id');
            if (hd_tagging_user == 1) {
                chk_user(idd);
            } else {
                verify_records('', '', idd);
            }
        });

        $(document).on('click', '.cl_c_diary', function() {
            var d_no = $(this).html();
            var sp_d_no = d_no.split('-');
            var idd = $(this).attr('id');
            //        var sp_id=idd.split('sp_c_diary');
            //        var hd_diary_no=$('#hd_link'+sp_id[1]).val();
            var d_yr = sp_d_no[1];
            var d_no = sp_d_no[0];
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
                data: {
                    d_no: d_no,
                    d_yr: d_yr
                },
                beforeSend: function() {
                    $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {

                    $('#ggg').html(data);
                    add_button(d_no, d_yr);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });

        $(document).on('click', '[id^=accordion] a', function(event) {
            var accname = $(this).attr('data-parent');

            if (typeof $(this).attr('data-parent') !== "undefined") {
                //var collapse_element = event.target;
                var url = "";
                //    alert(collapse_element);
                var href = this.hash;
                var depId = href.replace("#collapse", "");
                var accname1 = accname.replace("#accordion", "");
                var acccnt = accname1 * 100;
                var diaryno = document.getElementById('diaryno' + accname1).value;

                if (depId != (acccnt + 1)) {
                    if (depId == (acccnt + 2)) url = "../case_status/get_earlier_court.php";
                    if (depId == (acccnt + 3)) url = "../case_status/get_connected.php";
                    if (depId == (acccnt + 4)) url = "../case_status/get_listings.php";
                    if (depId == (acccnt + 5)) url = "../case_status/get_ia.php";
                    //    if(depId==6) url="get_earlier_court.php";
                    if (depId == (acccnt + 6)) url = "../case_status/get_court_fees.php";
                    if (depId == (acccnt + 7)) url = "../case_status/get_notices.php";
                    if (depId == (acccnt + 8)) url = "../case_status/get_default.php";
                    if (depId == (acccnt + 9)) url = "../case_status/get_judgement_order.php";
                    if (depId == (acccnt + 10)) url = "../case_status/get_adjustment.php";
                    if (depId == (acccnt + 11)) url = "../case_status/get_mention_memo.php";
                    if (depId == (acccnt + 12)) url = "../case_status/get_restore.php";
                    if (depId == (acccnt + 13)) url = "../case_status/get_drop.php";
                    if (depId == (acccnt + 14)) url = "../case_status/get_appearance.php";
                    if (depId == (acccnt + 15)) url = "../case_status/get_office_report.php";
                    if (depId == (acccnt + 16)) url = "../case_status/get_similarities.php";

                    // var dataString = 'depId='+ depId + '&do=getDepUsers';
                    $.ajax({
                            type: 'POST',
                            url: url,
                            beforeSend: function(xhr) {
                                $("#result" + depId).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='images/load.gif'></div>");
                            },
                            data: {
                                diaryno: diaryno
                            }
                        })
                        .done(function(msg) {
                            $("#result" + depId).html(msg);
                        })
                        .fail(function() {
                            alert("ERROR, Please Contact Server Room");
                        });
                }
            }
        });

        $(document).on('keyup', '#txt_search', function() {
            var txt_search = $('#txt_search').val();
            var cl_rdn_supreme = '';
            $('.cl_rdn_supreme').each(function() {
                if ($(this).is(':checked')) {
                    cl_rdn_supreme = $(this).val();
                }
            });

            //     alert(cl_rdn_supreme);
            $.ajax({
                url: '../scrutiny/search_cat.php',
                cache: false,
                async: true,
                data: {
                    txt_search: txt_search,
                    cl_rdn_supreme: cl_rdn_supreme
                },
                beforeSend: function() {
                    $('#sp_mul_rec').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    //alert(data);
                    $('#sp_mul_rec').html(data);


                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });

    });

    //function closeData()
    //{
    //    document.getElementById('ggg').scrollTop=0; 
    //    document.getElementById('dv_fixedFor_P').style.display="none";
    //    document.getElementById('dv_sh_hd').style.display="none";
    //    alert("dfdfdf");
    //    get_def_rec();
    //    //window.location="verification.php";
    //} 

    function add_button(d_no, d_yr) {
        var flag = $('#hd_flag').val();

        $.ajax({
            url: 'add_button.php',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                hd_flag: flag
            },
            success: function(data, status) {

                $('#dv_dup').prepend(data);
                $('#td_category').html("<input type='button' name='btn_edit_cat' id='btn_edit_cat' value='Edit'/>")

                //                get_listing_dates(d_no,d_yr);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }

    function get_listing_dates(d_no, d_yr) {
        //    alert(d_no); 
        $.ajax({
            url: 'get_listing.php',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                d_no: d_no,
                d_yr: d_yr
            },
            success: function(data, status) {
                //alert(data);
                $('#dv_listing').html(data);


            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }

    function get_def_rec() {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        if ($("#radioct").is(':checked')) {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        } else if ($("#radiodn").is(':checked')) {
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        } else {
            alert('Please Select Any Option');
            return false;
        }
        // var d_yr=hd_diary_no.substr(-4);
        //   var d_no=hd_diary_no.substr(0,(hd_diary_no.length)-4);


        $.ajax({
                type: 'POST',
                url: "./get_verification_dup.php",
                beforeSend: function(xhr) {
                    $("#dv_dup").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                data: {
                    d_no: diaryno,
                    d_yr: diaryyear,
                    ct: cstype,
                    cn: csno,
                    cy: csyr
                }
            })
            .done(function(msg) {
                $("#dv_dup").html(msg);
                //$('#hd_diary_nos').val(diaryno+diaryyear);

                if ($('.cl_center').html().trim() != '<b>Matter is Disposed</b>') {
                    if ($('.cl_center').html().trim() != '<b>No Record Found</b>') {
                        if ($('.cl_center').html().trim() != '<b>Record Already Verified</b>') {
                            if ($('.cl_center').html().trim() != '<b>Matter is unregistered and Interlocutary Application not found</b>') {
                                if ($('.cl_center').html().trim() != '<b>No Record Found</b>') {
                                    add_button(diaryno, diaryyear);
                                    $("#edit_lb").html("<input type=button value='Edit' id='editcoram'/>");
                                }
                            }
                        }
                    }
                }

                //$("#result2").html("");
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });

        /*var t_h_cno = $('#t_h_cno').val();
        var t_h_cyt = $('#t_h_cyt').val();

        if (t_h_cno != '' && (t_h_cyt == '' || t_h_cyt.length != 4))
        {
            alert("Please enter year");
            $('#t_h_cyt').focus();
        }
        else if ((t_h_cyt != '' && t_h_cyt.length == 4) && t_h_cno == '')
        {
            alert("Please enter Diary No.");
            $('#t_h_cno').focus();
        }
        else
        {
            var xmlhttp;
            if (window.XMLHttpRequest)
            {
                xmlhttp = new XMLHttpRequest();
            }
            else
            {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            document.getElementById('dv_dup').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

            xmlhttp.onreadystatechange = function()
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                {

                    document.getElementById('dv_dup').innerHTML = xmlhttp.responseText;

                }
            }


            // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
            xmlhttp.open("GET", "get_verification_dup.php?d_no=" + t_h_cno + "&d_yr=" + t_h_cyt, true);
            xmlhttp.send(null);
        }*/
    }

    $(document).on("click", "#editcoram", function() {
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
        var diary_no = $("#hd_diary_nos").val();
        var d_yr = diary_no.substr(-4);
        var d_no = diary_no.substr(0, (diary_no.length) - 4);
        $.ajax({
                type: 'POST',
                url: "../listbefore/get_case_listing_all.php",
                /*beforeSend: function (xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },*/
                data: {
                    dno: d_no,
                    dyr: d_yr
                }
            })
            .done(function(msg) {
                $('#ggg').html(msg);
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    });

    // function find_and_set_da(dirno, diryr) {
    //     $.ajax({
    //             type: 'POST',
    //             url: "../scrutiny/get_and_set_da.php",
    //             /*beforeSend: function (xhr) {
    //                 $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
    //             },*/
    //             data: {
    //                 dno: dirno,
    //                 dyr: diryr
    //             }
    //         })

    //         .done(function(msg) {
    //             // alert(msg);
    //             document.getElementById('ggg').style.width = 'auto';
    //             document.getElementById('ggg').style.height = ' 500px';
    //             document.getElementById('ggg').style.overflow = 'scroll';
    //             //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
    //             document.getElementById('ggg').style.marginLeft = '50px';
    //             document.getElementById('ggg').style.marginRight = '50px';
    //             document.getElementById('ggg').style.marginBottom = '25px';
    //             document.getElementById('ggg').style.marginTop = '1px';
    //             //call_prop_s(dirno,diryr);
    //             get_def_rec();
    //         })


    //         .fail(function() {
    //             alert("ERROR, Please Contact Server Room");
    //         });
    // }

    function call_listing(dirno, diryr) {
        //var d_no=document.getElementById('t_h_cno').value;
        //var d_yr=document.getElementById('t_h_cyt').value;
        //var dno=d_no+d_yr;
        var obj = 'Y';
        $.ajax({
                type: 'POST',
                url: "../scrutiny/call_listing.php",
                /*beforeSend: function (xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },*/
                data: {
                    dno: dirno,
                    dyr: diryr,
                    objrem: obj
                }
            })
            .done(function(msg) {
                // alert(msg);
                find_and_set_da(dirno, diryr);

            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    }

    function call_prop_s(dirno, diryr) {
        $.ajax({
                type: 'POST',
                url: "../scrutiny/show_proposal.php",
                /*beforeSend: function (xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },*/
                data: {
                    dno: dirno,
                    dyr: diryr
                }
            })
            .done(function(msg) {
                //alert(msg);
                document.getElementById('dv_fixedFor_P').style.marginTop = '50px';
                document.getElementById('dv_sh_hd').style.display = 'block';
                document.getElementById('dv_fixedFor_P').style.display = 'block';
                //  document.getElementById('sp_mnb_p').style.width=screen.width/2+'px';
                document.getElementById('ggg').innerHTML = msg;
                /*document.getElementById('tb_clr').style.backgroundColor = 'white';
                if (document.getElementById('tb_clr_n'))
                    document.getElementById('tb_clr_n').style.backgroundColor = 'white';*/
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    }

    function closeData() {
        document.getElementById('ggg').scrollTop = 0;

        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
        get_def_rec();
    }

    $(document).on('click', '#btn_edit_cat', function() {

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
        var hd_diary_nos = $('#hd_diary_nos').val();
        $.ajax({
            url: 'get_categories.php',
            cache: false,
            async: true,
            data: {
                hd_diary_nos: hd_diary_nos
            },
            beforeSend: function() {
                $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#ggg').html(data);
                //                add_button(d_no,d_yr);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });

    var cnt_data1 = 1;
    var cnt_sno = 1;

    function getSlide(str) {
        //  cnt_sno=1;





        var ck_ca_sb = 0;

        if (document.getElementById('hd_ssno').value != '0') {
            cnt_data1 = parseInt(document.getElementById('hd_ssno').value) + 1;
            document.getElementById('hd_ssno').value = '0';
        }
        var hd_co_tot = document.getElementById('hd_co_tot').value;
        var idd = str.split('chk_sno');

        var subject = document.getElementById('hd_subcode' + idd[1]).value;
        var cat = document.getElementById('hd_subcodes' + idd[1]).value;
        var subcat = document.getElementById('hd_subcodess' + idd[1]).value;

        var main_id = document.getElementById('hd_id' + idd[1]).value;


        //var other_catg = ['10','20','46','75','87','101','115','129','141','151','163','182','201','215','227','250','259','262','270','276','289','295','300','304','311'];

        var exist = other_catg.includes(main_id);
        //alert(main_id);
        //alert(document.getElementById(str).checked);
        if (exist == true && document.getElementById(str).checked == true) {
            //alert('exist getdone');
            document.getElementById("otherdiv").style.display = "block";
            document.getElementById("ortext").value = '';
            //alert(document.getElementById("ortext").value);

        }


        for (var i = 1; i <= hd_co_tot; i++) {

            if (document.getElementById('hd_sp_a' + i)) {
                //           alert(document.getElementById('hd_sp_a'+i).value);
                //            alert(subject);
                //             alert(document.getElementById('hd_sp_b'+i).value.trim());
                //                alert(cat);
                //               alert(document.getElementById('hd_sp_c'+i).value);
                //                 alert(subcat);
                //                if((subject.trim()==document.getElementById('hd_sp_a'+i).value.trim() ) && 
                //                    (cat.trim()==document.getElementById('hd_sp_b'+i).value.trim() ) &&
                //                (subcat.trim()==document.getElementById('hd_sp_c'+i).value.trim() ))
                if (main_id.trim() == document.getElementById('hd_sp_d' + i).value.trim()) {
                    ck_ca_sb = 1;
                    //  alert("A");
                }
            }
        }
        if (ck_ca_sb == 1) {
            alert("Already Selected");
            // cnt_data1++;
        } else {
            //        var subject=document.getElementById('sp_subject'+idd[1]).options[document.getElementById('sp_subject'+idd[1]).selectedIndex].innerHTML;
            //               var cat=document.getElementById('sp_category'+idd[1]).options[document.getElementById('sp_category'+idd[1]).selectedIndex].innerHTML;
            //           // alert(cat);
            //            var subcat=document.getElementById('sp_subcategory'+idd[1]).options[document.getElementById('sp_subcategory'+idd[1]).selectedIndex].innerHTML;
            //alert(subject);
            var subject = document.getElementById('sp_subject' + idd[1]).innerHTML;
            var cat = document.getElementById('sp_category' + idd[1]).innerHTML;
            // alert(cat);
            var sp_sub_category = document.getElementById('sp_sub_category' + idd[1]).innerHTML;

            var sp_sub_sub_category = document.getElementById('sp_sub_sub_category' + idd[1]).innerHTML;

            var subcat = document.getElementById('sp_subcategory' + idd[1]).innerHTML;
            var sub_id = document.getElementById('hd_subcode' + idd[1]).value;
            var cat_id = document.getElementById('hd_subcodes' + idd[1]).value;
            var subcat_id = document.getElementById('hd_subcodess' + idd[1]).value;
            var hd_id_z = document.getElementById('hd_id' + idd[1]).value;

            var hd_color = document.getElementById('hd_color' + idd[1]).value;

            //             if(sub_id=='0')
            //                 {
            //                      alert("Pease Select Subject");
            //                 }
            //             else
            {
                var row0 = document.createElement("tr");
                row0.setAttribute('id', 'tr_uo' + cnt_data1);
                var column0 = document.createElement("td");
                var column1 = document.createElement("td");
                var column2 = document.createElement("td");
                var column3 = document.createElement("td");
                var column4 = document.createElement("td");
                var column5 = document.createElement("td");
                var column6 = document.createElement("td");
                var spAddObj = document.getElementById('tb_new');

                var hd_chk_add = document.createElement('input');
                hd_chk_add.setAttribute('type', 'checkbox');
                hd_chk_add.setAttribute('id', 'hd_chk_add' + cnt_data1);
                hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
                // var hd_id=document.createElement('span');
                //     hd_id.setAttribute('type', 'hidden');
                //   hd_id.setAttribute('id', 'sp_a'+cnt_data1);
                var colors = '';
                if (hd_color == 's')
                    colors = 'cl_supreme';
                else
                    colors = 'cl_other';
                var hd_id_txtcnt = document.createElement('span');
                //  hd_id_txtcnt.setAttribute('type', 'hidden');
                hd_id_txtcnt.setAttribute('id', 'sp_b' + cnt_data1);
                hd_id_txtcnt.setAttribute('class', colors);
                var sp = document.createElement('span');
                //sp.setAttribute('id', 'spAddObj'+str1[1]);
                sp.setAttribute('id', 'sp_c' + cnt_data1);
                sp.setAttribute('class', colors);
                var sp_e = document.createElement('span');
                sp_e.setAttribute('id', 'sp_e' + cnt_data1);
                sp_e.setAttribute('class', colors);
                var sp_f = document.createElement('span');
                sp_f.setAttribute('id', 'sp_f' + cnt_data1);
                sp_f.setAttribute('class', colors);
                var chkbx = document.createElement('span');
                // chkbx.setAttribute('type', 'checkbox');
                chkbx.setAttribute('id', 'sp_d' + cnt_data1);


                chkbx.setAttribute('class', colors);
                //  chkbx.setAttribute('onclick', 'getDone_upd(this.id);');

                var hd_1 = document.createElement('input');
                hd_1.setAttribute('type', 'hidden');
                hd_1.setAttribute('id', 'hd_sp_a' + cnt_data1);

                var hd_2 = document.createElement('input');
                hd_2.setAttribute('type', 'hidden');
                hd_2.setAttribute('id', 'hd_sp_b' + cnt_data1);

                var hd_3 = document.createElement('input');
                hd_3.setAttribute('type', 'hidden');
                hd_3.setAttribute('id', 'hd_sp_c' + cnt_data1);
                column0.appendChild(hd_chk_add);

                var hd_4 = document.createElement('input');
                hd_4.setAttribute('type', 'hidden');
                hd_4.setAttribute('id', 'hd_sp_d' + cnt_data1);
                column0.appendChild(hd_chk_add);


                column0.appendChild(hd_1);
                column0.appendChild(hd_2);
                column0.appendChild(hd_3);
                column0.appendChild(hd_4);

                row0.appendChild(column0);
                // table1.appendChild(row0); 
                //    column1.appendChild(hd_id); 
                //  row0.appendChild(column1);
                column4.appendChild(chkbx);
                row0.appendChild(column4);
                column2.appendChild(hd_id_txtcnt);
                row0.appendChild(column2);
                column3.appendChild(sp);
                row0.appendChild(column3);
                column5.appendChild(sp_e);
                row0.appendChild(column5);
                column6.appendChild(sp_f);
                row0.appendChild(column6);

                var tb_res = document.getElementById('tb_new');
                tb_res.appendChild(row0);
                //                  if(document.getElementById('hd_ck_tot').value=='')
                //                     document.getElementById('hd_ck_tot').value= document.getElementById('hd_co_tot').value;
                //                 else
                //                        document.getElementById('hd_ck_tot').value= document.getElementById('hd_ck_tot').value-1;
                // document.getElementById('sp_a'+cnt_data1).innerHTML= cnt_data1;
                //                   document.getElementById('tr_uo'+cnt_data1).style.borderWidth='1px';
                //                      document.getElementById('tr_uo'+cnt_data1).style.borderColor='black';
                //                        document.getElementById('tr_uo'+cnt_data1).style.borderStyle='solid';
                document.getElementById('sp_b' + cnt_data1).innerHTML = subject;
                if (cat_id == '0')
                    document.getElementById('sp_c' + cnt_data1).innerHTML = '-';
                else
                    document.getElementById('sp_c' + cnt_data1).innerHTML = cat;
                if (subcat_id == '0')
                    document.getElementById('sp_d' + cnt_data1).innerHTML = '-';
                else
                    document.getElementById('sp_d' + cnt_data1).innerHTML = subcat;

                document.getElementById('sp_e' + cnt_data1).innerHTML = sp_sub_category;
                document.getElementById('sp_f' + cnt_data1).innerHTML = sp_sub_sub_category;

                document.getElementById('hd_sp_a' + cnt_data1).value = sub_id;
                document.getElementById('hd_sp_b' + cnt_data1).value = cat_id;
                document.getElementById('hd_sp_c' + cnt_data1).value = subcat_id;
                document.getElementById('hd_sp_d' + cnt_data1).value = hd_id_z;
                document.getElementById('hd_chk_add' + cnt_data1).checked = true;;
                // spAddObj.appendChild(table1);

                //spAddObj.appendChild(sp);
                //spAddObj.appendChild(sp1);
                //spAddObj.appendChild(sp2);
                ////document.getElementById('hd_id'+cnt_data).value='hdSH'+str1[1];
                //document.getElementById('hd_id_txtcnt'+cnt_data).value=document.getElementById('hdSH'+str1[1]).value;
                //document.getElementById('spAddObj'+cnt_data).innerHTML=document.getElementById('spInner'+str1[1]).innerHTML;
                //document.getElementById('hd_tot').value=cnt_data;
                document.getElementById('hd_co_tot').value = cnt_data1;
                cnt_data1++;
                cnt_sno++;
                var hd_ck_cf_natue = $('#hd_ck_cf_natue').val();
                if (hd_ck_cf_natue == 0)
                    get_court_fee();
            }
        }
        document.getElementById(str).checked = false;
    }

    function sav_mul_cat() {
        // var other_catg = ['10','20','46','75','87','101','115','129','141','151','163','182','201','215','227','250','259','262','270','276','289','295','300','304','311'];
        var exist = false;
        if (document.getElementById('dv_jb'))
            document.getElementById('dv_jb').style.display = 'none';
        //   alert(hd_sp_a_rem);
        var ytq = '0';
        var ytq1 = '0';
        var ent_ft = '';
        //         var m_fbench=document.getElementById('m_fbench').value; 
        //    var lst_case=document.getElementById('lst_case').value;
        //       if(lst_case.length==2)
        //         lst_case='0'+lst_case;
        //       var fil_no='01'+lst_case+document.getElementById('txtcaseno').value+
        //                   document.getElementById('txtyear').value;
        //          del_mul_cat(fil_no);

        var sensitive_case = 0;
        var txt_sen_case = '';
        if ($('#chk_sen_cs').is(':checked')) {
            txt_sen_case = $('#txt_sen_case').val();
            sensitive_case = 1;
        }


        // alert(lst_case);
        var cl_rdn_supreme = 0;
        $('.cl_rdn_supreme').each(function() {
            if ($(this).is(':checked')) {
                cl_rdn_supreme = 1;
            }
        });
        var hd_diary_nos = $('#hd_diary_nos').val();

        //    var d_yr=hd_diary_nos.substr(-4);
        //        var d_no=hd_diary_nos.substr(0,(hd_diary_nos.length)-4);

        var t_h_cno = hd_diary_nos.substr(0, (hd_diary_nos.length) - 4);
        var t_h_cyr = hd_diary_nos.substr(-4);

        //   var m_cat=document.getElementById('m_cat').value;
        //      var m_fixed=document.getElementById('m_fixed').value;



        var hd_co_tot = document.getElementById('hd_co_tot').value;
        for (var itt = 1; itt <= hd_co_tot; itt++) {
            if (document.getElementById('hd_chk_add' + itt)) {
                if (document.getElementById('hd_chk_add' + itt).checked == true) {
                    ytq++;
                    var cat = document.getElementById('hd_sp_d' + itt).value;
                    exist = other_catg.includes(cat);
                }
            }
        }
        if (cl_rdn_supreme == 0 && ytq == '0') {
            alert("Please select Category");
            return false;
        }
        if (ytq == '0') {
            alert("Please Add atleast one subject")
        }

        if (ytq > 1) {
            alert("Only one category can be updated!!");
            return false;
        }
        var var_ortext = document.getElementById('ortext').value.trim();
        if (var_ortext == '' && exist == true) {
            alert("Please enter Other category remarks");
            return false;
        }
        //                 else if(lst_case=='')
        //       {
        //           alert("Please Enter Case Type ");
        //          
        //       }
        else if (t_h_cno == '') {
            alert("Please Enter Diary No.");
            $('#t_h_cno').focus();

        } else if (t_h_cyr == '') {
            alert("Please Enter Diary Year");
            $('#t_h_cyr').focus();

        }
        //      else if(m_cat=='0')
        //       {
        //             alert("Please select SUBJECT");
        //          
        //      } 
        //      else if(m_fixed=='')
        //          {
        //               alert("Please select Fixed For");
        //                
        //          }
        else if ($('#tr_val').css('display') == 'table-row' && $('#txt_valuation').val() == '') {
            alert("Please enter valuation");
            $('#txt_valuation').focus();
        } else if ($('#tr_court_fee').css('display') == 'table-row' && $('#txt_court_fee').val() == '') {
            alert("Please enter court fee");
            $('#txt_court_fee').focus();
        } else if (sensitive_case == 1 && txt_sen_case == '') {
            alert("Please enter reason of case to be sensitive");
            $('#txt_sen_case').focus();

        } else {
            //  alert(ytq);

            $('#ok2').attr('disabled', true);
            del_mul_cat(hd_co_tot, ytq, ytq1, t_h_cno, t_h_cyr);

        }
    }

    function del_mul_cat(hd_co_tot, ytq, ytq1, t_h_cno, t_h_cyr) {
        var other_cat = document.getElementById('ortext').value;
        //alert(other_cat);
        var hd_sp_a_rem = document.getElementById('hd_sp_a_rem').value;
        var hd_sp_b_rem = document.getElementById('hd_sp_b_rem').value;
        var hd_sp_c_rem = document.getElementById('hd_sp_c_rem').value;
        var hd_sp_d_id = document.getElementById('hd_sp_d_id').value;

        //var xmlhttp;
        //if (window.XMLHttpRequest)
        // {// code for IE7+, Firefox, Chrome, Opera, Safari
        //   xmlhttp=new XMLHttpRequest();
        //}
        // else
        // {// code for IE6, IE5
        // xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        // }


        // xmlhttp.onreadystatechange=function()
        // {
        // if (xmlhttp.readyState==4 && xmlhttp.status==200)
        //{
        //                  alert(xmlhttp.responseText);
        for (var itt = 1; itt <= hd_co_tot; itt++) {

            if (document.getElementById('hd_chk_add' + itt)) {
                if (document.getElementById('hd_chk_add' + itt).checked == true) {
                    ytq1++;
                    if (ytq1 == '1') {
                        var main_cat = document.getElementById('hd_sp_a' + itt).value;
                        var main_subcat = document.getElementById('hd_sp_b' + itt).value;
                        var main_sub_subcat = document.getElementById('hd_sp_c' + itt).value;
                    }
                    var hd_sp_a = document.getElementById('hd_sp_a' + itt).value;
                    var hd_sp_b = document.getElementById('hd_sp_b' + itt).value;
                    var hd_sp_c = document.getElementById('hd_sp_c' + itt).value;
                    var hd_sp_d = document.getElementById('hd_sp_d' + itt).value;
                    var xmlhttp4;
                    if (window.XMLHttpRequest) {
                        xmlhttp4 = new XMLHttpRequest();
                    } else { // code for IE6, IE5
                        xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    if (ytq1 == ytq) {

                        xmlhttp4.onreadystatechange = function() {
                            if (xmlhttp4.readyState == 4 && xmlhttp4.status == 200) {
                                if (xmlhttp4.responseText == '') {
                                    alert("Category Updated Successfully");
                                    get_def_rec();
                                } else {
                                    alert(xmlhttp4.responseText);
                                }

                            }
                        }
                    }


                    //                xmlhttp4.open("GET","../scrutiny/insert_mul_cat.php?t_h_cno="+t_h_cno+"&hd_sp_a="+hd_sp_a+
                    //                    "&hd_sp_b="+hd_sp_b+"&hd_sp_c="+hd_sp_c+"&ytq1="+ytq1+"&t_h_cyr="+t_h_cyr+"&hd_sp_d="+hd_sp_d+"&other_cat="+other_cat,false);
                    xmlhttp4.open("GET", "../scrutiny/insert_mul_cat.php?t_h_cno=" + t_h_cno + "&hd_sp_a=" + hd_sp_a + "&hd_sp_b=" + hd_sp_b + "&hd_sp_c=" + hd_sp_c + "&ytq1=" + ytq1 + "&t_h_cyr=" + t_h_cyr + "&hd_sp_d=" + hd_sp_d + "&other_cat=" + other_cat + "&verify_req_page=" + "Y", false);

                    xmlhttp4.send(null);

                }
            }
        }

        // }
        // }
        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        // xmlhttp.open("POST","../scrutiny/del_mul_cat.php?t_h_cno="+t_h_cno+"&hd_sp_a_rem="+hd_sp_a_rem+
        // "&hd_sp_b_rem="+hd_sp_b_rem+"&hd_sp_c_rem="+hd_sp_c_rem+"&t_h_cyr="+t_h_cyr+"&hd_sp_d_id="+hd_sp_d_id,true);
        // xmlhttp.send(null);
    }

    function getDone_upd_cat(str) {
        var str1 = str.split('hd_chk_add');

        // var other_catg = ['10','20','46','75','87','101','115','129','141','151','163','182','201','215','227','250','259','262','270','276','289','295','300','304','311'];
        var cat = document.getElementById('hd_sp_d' + str1[1]).value;
        var exist = other_catg.includes(cat);

        if (exist == true && document.getElementById(str).checked == false) {
            //alert('exist getdone');
            document.getElementById("otherdiv").style.display = "none";
            document.getElementById("ortext").value = '';
            //alert(document.getElementById("ortext").value);

        }



        var hd_sp_a_rem = '';
        var hd_sp_b_rem = '';
        var hd_sp_c_rem = '';
        var hd_sp_d_id = '';
        if (document.getElementById('hd_sp_a_rem').value == '')
            document.getElementById('hd_sp_a_rem').value = document.getElementById('hd_sp_a' + str1[1]).value;
        else {
            document.getElementById('hd_sp_a_rem').value = document.getElementById('hd_sp_a_rem').value + '^' +
                document.getElementById('hd_sp_a' + str1[1]).value;
        }
        //document.getElementById('hd_sp_a_rem').value=hd_sp_a_rem;
        if (document.getElementById('hd_sp_b_rem').value == '')
            document.getElementById('hd_sp_b_rem').value = document.getElementById('hd_sp_b' + str1[1]).value;
        else
            document.getElementById('hd_sp_b_rem').value = document.getElementById('hd_sp_b_rem').value + '^' + document.getElementById('hd_sp_b' + str1[1]).value;
        // document.getElementById('hd_sp_b_rem').value=hd_sp_b_rem;
        if (document.getElementById('hd_sp_c_rem').value == '')
            document.getElementById('hd_sp_c_rem').value = document.getElementById('hd_sp_c' + str1[1]).value;
        else
            document.getElementById('hd_sp_c_rem').value = document.getElementById('hd_sp_c_rem').value + '^' + document.getElementById('hd_sp_c' + str1[1]).value;

        if (document.getElementById('hd_sp_d_id').value == '')
            document.getElementById('hd_sp_d_id').value = document.getElementById('hd_sp_d' + str1[1]).value;
        else
            document.getElementById('hd_sp_d_id').value = document.getElementById('hd_sp_d_id').value + '^' + document.getElementById('hd_sp_d' + str1[1]).value;
        //document.getElementById('hd_sp_c_rem').value=hd_sp_c_rem;
        // var str1=str.split('hd_chk_add') ;


        $("#tr_uo" + str1[1]).remove();
        var hd_ck_cf_natue = $('#hd_ck_cf_natue').val();

    }


    function setter(x) {
        var sta = document.getElementById(x).value;
        $.ajax({
                type: 'POST',
                url: "../listbefore/get_notbefore_reason.php",
                data: {
                    sta: sta
                }
            })
            .done(function(msg) {
                //alert(msg);
                document.getElementById('reason_listing').innerHTML = msg;
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    }


    function save_call() {
        if (document.getElementById('select_save_as').value == 0) {
            alert('Please Select List/Not List/Coram Save Type');
            document.getElementById('select_save_as').focus();
            return false;
        }
        if (document.getElementById('show_reason').value == '') {
            alert('Please Select List/Not List/Coram Before Reason');
            document.getElementById('show_reason').focus();
            return false;
        }

        var total_j = document.getElementById('total_jdg').value;
        var ctrl_j = 0;
        var judge_array = new Array();

        for (var i = 1; i < total_j; i++) {
            var chkbx_j = "jdg" + i;
            var chkbox = document.getElementById(chkbx_j);
            if (null != chkbox && true == chkbox.selected) {
                ctrl_j++;
                if (ctrl_j == 12) {
                    alert('You Can Not Select More than 11 Judges');
                    return false;
                }
                judge_array.push(document.getElementById(chkbx_j).value);
            }
        }

        if (ctrl_j == 0)
            alert('No Judge is Selected');
        else {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var select_save_as = document.getElementById('select_save_as').value;
            var show_reason = document.getElementById('show_reason').value;

            $.ajax({
                url: "<?php echo base_url('Coram/Coram/add/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    ctrl: 'I',
                    j: judge_array,
                    save: select_save_as,
                    list_res: show_reason
                },
                success: function(result) {

                    var obj = JSON.parse(result);

                    if (obj.inserted) {
                        alert(obj.inserted);
                        window.location.href = '';
                    }

                    if (obj.delete_coram_msg) {
                        alert(obj.delete_coram_msg);
                        window.location.href = '';
                    }

                    //$('#part_name').html(result);
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        }
    }
    $('#saveStep1').click(function() {
            var main_category = $('#main_category').val();
            var sub_category = $('#sub_category').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    main_category: main_category,
                    sub_category: sub_category
                },
                url: "<?php echo base_url('Filing/Category/updateCategory/'); ?>",
                success: function(data) {
                    // alert(data);
                    updateCSRFToken();
                },
                error: function() {
                    updateCSRFToken();
                }
            });

        });
    function chk_user(idd) {
        var hd_diary_nos = $('#hd_diary_nos').val();
        $.ajax({
            url: 'chk_taging.php',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                hd_diary_nos: hd_diary_nos
            },
            success: function(data, status) {

                //             alert(data);
                if (data != '') {
                    var cnf = confirm("Diary No." + hd_diary_nos + " auto linked with Diary No. " + data + ". Click ok to list cases together");
                    if (cnf == true) {
                        verify_records('1', data, idd);
                    } else {
                        verify_records('0', data, idd);
                    }
                } else {
                    verify_records('', '', idd);
                }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
</script>

<!-- /.content -->
