<?= view('header') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
<style type="text/css">
    .ses_more2,
    .ses_more3,
    .ses_more2s,
    .ses_more3s {
        display: none;
    }

    .cp_spcatall:hover {
        color: blue;
        cursor: pointer;
        text-decoration: underline;
    }

    #sp_add,
    .del_rec,
    #sp_addz {
        color: blue;
    }

    #sp_add:hover,
    .del_rec:hover,
    #sp_addz:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    .form-style-10 {
        width: 700px;
        padding: 10px;
        margin: 10px auto;
        background: #FFF;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
        -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
        -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
    }

    .form-style-10 .inner-wrap {
        padding: 10px;
        background: #F8F8F8;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .form-style-10 h1 {
        background: #2A88AD;
        padding: 10px 20px 10px 20px;
        margin: -20px -20px 20px -20px;
        border-radius: 10px 10px 0 0;
        -webkit-border-radius: 10px 10px 0 0;
        -moz-border-radius: 10px 10px 0 0;
        color: #fff;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
        font: normal 30px 'Bitter', serif;
        -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        border: 1px solid #257C9E;
    }

    .form-style-10 h1>span {
        display: block;
        margin-top: 1px;
        font: 13px Arial, Helvetica, sans-serif;
    }

    .form-style-10 label {
        display: block;
        font: 13px Arial, Helvetica, sans-serif;
        color: #888;
        margin-bottom: 15px;
    }

    .form-style-10 input[type="text"],
    .form-style-10 input[type="date"],
    .form-style-10 input[type="datetime"],
    .form-style-10 input[type="email"],
    .form-style-10 input[type="number"],
    .form-style-10 input[type="search"],
    .form-style-10 input[type="time"],
    .form-style-10 input[type="url"],
    .form-style-10 input[type="password"],
    .form-style-10 textarea,
    .form-style-10 select {
        display: block;
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        uncheck_p_or_e_filed width: 100%;
        padding: 8px;
        border-radius: 6px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border: 2px solid #fff;
        box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
        -moz-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
        -webkit-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
    }

    .form-style-10 .section {
        font: normal 20px 'Bitter', serif;
        color: #2A88AD;
        margin-bottom: 5px;
    }

    .form-style-10 .section span {
        background: #2A88AD;
        padding: 5px 10px 5px 10px;
        position: absolute;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border: 4px solid #fff;
        font-size: 14px;
        margin-left: -45px;
        color: #fff;
        margin-top: -3px;
    }

    .form-style-10 input[type="button"],
    .form-style-10 input[type="submit"] {
        background: #2A88AD;
        padding: 8px 20px 8px 20px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        color: #fff;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
        font: normal 30px 'Bitter', serif;
        -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
        border: 1px solid #257C9E;
        font-size: 15px;
    }

    .form-style-10 input[type="button"]:hover,
    .form-style-10 input[type="submit"]:hover {
        background: #2A6881;
        -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
        -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
        box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
    }

    .form-style-10 .privacy-policy {
        float: right;
        width: 250px;
        font: 12px Arial, Helvetica, sans-serif;
        color: #4D4D4D;
        margin-top: 5px;
        text-align: right;
    }

    table {
        width: 100%;
        /* For Responsive design set 100% */
        border-collapse: collapse;
        margin: 30px 0px 30px;
        background-color: #fff;
        font-size: 12px;
    }

    table tr {
        height: 40px;
    }

    table th {
        color: #111;
        font-weight: bold;
        font-size: 14px;
    }

    table td,
    th {
        padding: 4px 4px 4px 8px;
        border: 1px solid #ccc;
        text-align: left;
        vertical-align: text-top;
    }

    /* CSS3 Zebra Striping */
    table tr:nth-of-type(odd) {
        background: #eee;
    }
</style>
</head>

<body>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Record Room >>&nbsp; Advocate on Record >> &nbsp; Aor wise matters (Pending & Disposed)</h3>
                                </div>

                            </div>
                        </div>
                        <br><br>


                        <form method="post" class="form-horizontal" role="form">
                            <?= csrf_field() ?>
                            <div id="dv_content1" class="form-style-10">
                                <table width="100%" id="tb_nms" class="tbl_border c_vertical_align" cellpadding="2" cellspacing="2">
                                    <tr>
                                        <td style="text-align: center;font-weight: bold" colspan="3">AOR Pending/Disposed Matters Report</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>AOR Name :</b>
                                        </td>
                                        <td colspan="2">
                                            <select class="e1" id='aor' style="width:300px">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($aor_record as $aots):
                                                ?>
                                                    <option value=<?= $aots['aor_code'] ?>><?= $aots['aor_code'] ?> : <?= $aots['adv_name'] ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Status :</b>
                                        </td>
                                        <td colspan="2">
                                            <select class="e1" id='status' style="width:300px">
                                                <option value="">All</option>
                                                <option value="P">Pending</option>
                                                <option value="D">Disposed</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Filing Date:</b>
                                        </td>
                                        <td>
                                            From: <input type="text" name="from_dt1" id="from_dt1" class="dtp" maxsize="10" autocomplete="on" size="9" />

                                        </td>
                                        <td>

                                            To: <input type="text" name="from_dt2" id="from_dt2" class="dtp" maxsize="10" autocomplete="on" size="9" />
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Case Type</b>
                                        </td>
                                        <td colspan="4">
                                            <select class="e1" id='caseType' style="width:300px">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($case_type as $r_nature):
                                                ?>
                                                    <option value="<?php echo $r_nature['casecode']; ?>"><?php echo $r_nature['casename']; ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>

                                <div align="center">
                                    <input type="button" id="btnGetDiaryList" value="Get Records" onclick="aor_fetch_data();" />
                                </div>
                            </div>
                            <div align="center"><img id="image" src="<?php echo base_url('images/load.gif'); ?>" style="display:none;"></div>
                            <div class="center" id="record"></div>

                        </form>


                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        $(document).ready(function() {
            $(".e1").select2();
        });

        $(document).on("focus", ".dtp", function() {
            $('.dtp').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: '1950:2050'
            })
        });

        async function aor_fetch_data() {
            await updateCSRFTokenSync();
            $('#record').hide();
            var aor = $('#aor').val();
            var status = $('#status').val();
            var from_dt1 = $('#from_dt1').val();
            var from_dt2 = $('#from_dt2').val();
            var caseType = $('#caseType').val();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var tvap = aor + ";" + status + ";" + from_dt1 + ";" + from_dt2 + ";" + caseType;

            if (aor == '') {
                alert("Please select AOR");
                $('#aor').focus();
                aor.focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: '<?= base_url('Record_room/advt_on_record/aor_wise_pendency_process') ?>',
                data: {
                    tvap: tvap,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                beforeSend: function() {
                    $("#image").show();
                },

                complete: function() {
                    $('#image').hide();
                },

                success: function(data) {
                    $('.center').html(data);
                    $('#record').show();

                },

                error: function() {
                    alert('Error');
                }


            });
        }
    </script>