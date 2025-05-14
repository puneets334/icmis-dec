<?= view('header'); ?>


<style>
    .scrol {
        width: 80%;
        height: 200px;
        overflow: auto;
        margin: 0 auto;
        border: 0.6px dotted #d8d8d8;
    }
    .add_result{
        font-size: 20px;
        text-align: center;
    }

    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }
</style>


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">IA / Document Details</h3>
                            </div>
                             <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?//= view('Filing/filing_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills inner-comn-tabs ">
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents">Insert / Modification of Docs.</a></li>
                                        <li class="nav-item"><a class="nav-link active" href="<?= base_url() ?>/Filing/Ia_documents/caseBlockList_view">Case Block for Loose Doc. </a></li>
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents/verify_defective_view">Verify / Defects </a></li>
                                    </ul>
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'party_view_form', 'id' => 'party_view_form', 'autocomplete' => 'off');
                                    echo form_open('Filing/Ia_documents/save_iaDoc_details', $attribute);
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="case_block_tab_panel">
                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                                            <div class="row mt-1">
                                                <div class="col-md-3">
                                                    <label>Diary No.</label>
                                                    <input type="text" class="form-control" id="dno" maxlength="10" placeholder="Diary No.">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Year</label>
                                                    <input type="text" class="form-control" maxlength="4" id="dyr" placeholder="Year">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Reason to Block the Case</label>
                                                    <input type="text" class="form-control" maxlength="100" id="bl_reason" placeholder="Reason">
                                                </div>

                                                <div class="col-md-3 ">
                                                    <button id="btnMain" class="btn btn-primary" style="margin-top: 1.6rem;" type="button">Add</button>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-12"><div class="add_result"></div></div>
                                            </div>

                                            <!-- Edit table 2 -->
                                            <div class="row mt-5">
                                                <div class="col-md-12">
                                                    <h3 class="card-title" style="float: none !important; text-align: center;">CASE BLOCK TO RECEIVE MISC. DOCS</h3>
                                                    <!-- <table id="example2" class="table table-hover showData"> -->
													<div class="table-responsive">
                                                    <table id="result_main" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Action</th>
                                                                <th>S.No.</th>
                                                                <th>Diary No.</th>
                                                                <th>Parties</th>
                                                                <th>Reason to Block</th>
                                                                <th>Section</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                      <!--  <tbody>

                                                            <?php
                                                            // echo "<pre>"; print_r($caseBlock_list); die;
                                                            if (!empty($caseBlock_list)) {
                                                                foreach ($caseBlock_list as $sno => $row) {
                                                                     ?>
                                                                    <tr>
                                                                        <td>
                                                                           
                                                                            <button type="button" id="btnDelete<?php echo $row['id']; ?>" value="Remove" class="caseBlckDelete btn btn-danger btn-sm table-edit-btn"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                                                        </td>
                                                                        <td><?= $sno + 1 ?></td>
                                                                        <td><?= substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4) ?></td>
                                                                        <td><?= ($row['pet_name'] != '' && $row['res_name'] != '') ? $row['pet_name'] . '<b> V/S </b>' . $row['res_name'] : '' ?></td>
                                                                        <td><?= $row['reason_blk'] ?></td>
                                                                        <td><?= $row['section_name'] ?></td>
                                                                        <td><?= date('d-m-Y h:i:s A', strtotime($row['ent_dt'])) ?></td>

                                                                    </tr>
                                                            <?php  }
                                                            }
                                                            ?>
                                                        </tbody>  -->
                                                    </table>
													</div>
                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
      var table = $("#result_main").DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "lengthChange": true, // Allow users to change page length
            //"pageLength": 10, // Default number of records per page
            "autoWidth": false,
            "dom": '<"top"lfB>rt<"bottom"ip><"clear">',
            "buttons": ["copy", "csv", "excel", "pdf", "print"],            
            "ajax": {
                "url": "<?php echo base_url('Filing/Ia_documents/caseBlockList_viewAjax')?>", // Replace with your API endpoint
                "type": "GET", // HTTP method
                //"dataSrc": ""  // Adjust if your data has a specific format
            },
            "columns": [
                { "data": "action","value": "action" },
                { "data": "sn","value": "sn" },
                { "data": "diary_no","value": "diary_no" },
                { "data": "parties","value": "parties" },
                { "data": "reason_blk" ,"value": "reason_blk" },
                { "data": "section_name" ,"value": "section_name" },
                { "data": "date","value": "date" }
            ],
             "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });
    $(document).ready(function() {
     

        $("#btnMain").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var low = $("#dno").val().trim();
            var up = $("#dyr").val().trim();
            var reg123 = new RegExp('^[0-9]+$');
            if (!reg123.test(low)) {
                alert("Please Enter Numeric Value Only");
                $("#dno").focus();
                return false;
            }
            if (!reg123.test(up)) {
                alert("Please Enter Numeric Value Only");
                $("#dyr").focus();
                return false;
            }
            if ($("#bl_reason").val() == "") {
                alert("Please Enter Reason");
                $("#bl_reason").focus();
                return false;
            }

            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('Filing/Ia_documents/addCaseBlock'); ?>",
                    async: false,
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        dno: low,
                        dyr: up,
                        reason: $("#bl_reason").val()
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    // alert(msg);
                    var msg2 = msg.split('~');
                    if (msg2[0] == 1) {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "green");
                        $(".add_result").html(msg2[1]);
                        $("#dno").val("");
                        $("#dyr").val("");
                        $("#bl_reason").val("");
                        $(".add_result").slideUp(3000);
                        //$("#result_main").reload();

                       /* $.ajax({
                                type: 'GET',
                                url: "<?php //echo base_url('Filing/Ia_documents/get_case_block'); ?>",
                                // data: {
                                //     mat: 2
                                // }
                            })
                            .done(function(msg_new) {
                                $("#result_main").html(msg_new);
                            })
                            .fail(function() {
                                alert("ERROR, Please Contact Server Room");
                            }); */
                    } else {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "red");
                        $(".add_result").html(msg);
                    }
                    table.ajax.reload(null, false);
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });
    });

    $(document).on("click", "[id^='btnDelete']", function() {
        var num = this.id.split('btnDelete');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (confirm("ARE YOU SURE TO REMOVE THIS RECORD") == true) {
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('Filing/Ia_documents/delete_case_block'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        id: num[1]
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    var msg2 = msg.split('~');
                    if (msg2[0] == 1) {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "#90C695");
                        $(".add_result").html(msg2[1]);
                        $("#dno").val("");
                        $("#dyr").val("");
                        $("#bl_reason").val("");
                        $(".add_result").slideUp(3000);
                        
                      /*  $.ajax({
                                type: 'GET',
                                url: "<?php //echo base_url('Filing/Ia_documents/get_case_block'); ?>",
                                // data: {
                                //     mat: 2
                                // }
                            })
                            .done(function(msg_new) {
                                updateCSRFToken();
                                $("#result_main").html(msg_new);
                            })
                            .fail(function() {
                                updateCSRFToken();
                                alert("ERROR, Please Contact Server Room");
                            }); */
                    } else {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "red");
                        $(".add_result").html(msg);
                    }
                    table.ajax.reload(null, false);
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        }
    });

    function remove_apos(value, id) {
        var string = value.replace("'", "");
        string = string.replace("#", "No");
        string = string.replace("&", "and");
        $("#" + id).val(string);
    }

    $(document).on('click', '#btn_pnt', function() {
        var prtContent = document.getElementById('result_main');
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        //document.getElementById('btn_pnt').style.display= 'block';
    });
</script>


<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function replace_amp(x) {
        document.getElementById(x).value = document.getElementById(x).value.trim();
        document.getElementById(x).value = document.getElementById(x).value.replace('&', ' and ');
        document.getElementById(x).value = document.getElementById(x).value.replace("'", "");
        document.getElementById(x).value = document.getElementById(x).value.replace("#", "No");
    }


    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })

    function getDocTypeDetails() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let dno = $("#hdfno").val()
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dno
            },
            url: "<?php echo base_url('Filing/Ia_documents/getDoc_type1'); ?>",
            success: function(data) {

                if (data != '') {
                    data = JSON.parse(data)
                    // console.log(data)
                    let html = ''
                    data.forEach(el => {
                        html += '<option value="' + el.value + '">' + el.label + '</option>'
                    })
                    $('#m_doc1').append(html)
                }
                updateCSRFToken();
            },
            error: function() {
                $('#m_doc1').append('')
                updateCSRFToken();
            }
        });
    }

    function getRemarksList(txt) {
        updateCSRFToken()

        setTimeout(() => {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    txt
                },
                url: "<?php echo base_url('Filing/Ia_documents/getRemarksList'); ?>",
                success: function(data) {

                    if (data != '') {
                        data = JSON.parse(data)
                        // console.log(data)
                        let html = ''
                        data.forEach(el => {
                            html += '<option value="' + el.remark_data + '">'
                        })
                        $('#docRemarks').append(html)
                    } else {
                        $('#docRemarks').html('')
                    }
                    updateCSRFToken();
                },
                error: function() {
                    $('#docRemarks').html('')
                    updateCSRFToken();
                }
            });
        }, 300);

    }

    $(document).ready(function() {


       /* table.buttons().container().appendTo('#result_main_wrapper .col-md-6:eq(0)');
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)'); */
        $("#example3").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

        $('#get_court_details').append('<hr />');
        $('.aftersubm').append('<hr />');
        $('.befSubmt').prepend('<hr />');

        $('input[type=radio][name=radio_filed_by]').change(function() {
            updateCSRFToken();
            if (this.value == 'A') {
                $('.filed_Aor').show()
                $('.filed_Res').hide()
                $('.filed_Pet').hide()
            } else if (this.value == 'P') {
                $('.filed_Pet').show()
                $('.filed_Res').hide()
                $('.filed_Aor').hide()
            } else if (this.value == 'R') {
                $('.filed_Res').show()
                $('.filed_Pet').hide()
                $('.filed_Aor').hide()

            }

            if (this.value == 'P' || this.value == 'R') {

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                let type = this.value
                let obj = {
                    dno: $('#hdfno').val(),
                    type
                }
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        data: obj
                    },
                    url: "<?php echo base_url('Filing/Ia_documents/getPetResList'); ?>",
                    success: function(data) {
                        if (data != '') {
                            data = JSON.parse(data)
                            let html = ''
                            data.forEach(el => {
                                html += '<option value="' + el.value + '">' + el.label + '</option>'
                            })
                            if (type == 'P') {
                                $('#name_pet_filed_by').html('<option value="">Select</option> ')
                                $('#name_pet_filed_by').append(html)
                            } else if (type == 'R') {
                                $('#name_res_filed_by').html('<option value="">Select</option> ')
                                $('#name_res_filed_by').append(html)
                            }
                            updateCSRFToken();
                        }
                    },
                    error: function() {
                        $('#name_res_filed_by').html('<option value="">Select</option> ')
                        updateCSRFToken();
                    }
                });
            }

        });

        // 7case
        $("#m_doc").change(function() {
            let valOpt = $("option:selected", this).val();
            // alert(valOpt)
            if (valOpt == 7) {
                $('.7case').show()
                $('#m_amt').attr('disabled', true)
            } else {
                $('.7case').hide()
                $('#m_amt').attr('disabled', false)
            }


            if (valOpt == 8) {

                setTimeout(() => {
                    getDocTypeDetails();
                }, 200);

                $("#m_doc1").removeAttr("disabled");
                $("#m_doc1").val("");
                $("#hd_doc_type1").val("");
            } else {
                $("#m_doc1").attr('disabled', true);
                $("#m_doc1").val("");
                $("#hd_doc_type1").val("");

                $('#m_doc1').html('')
                $('#select2-m_doc1-container').text('')
            }

            if (valOpt == 8 && document.getElementById('m_doc1').value == 19) {
                $("#m_desc").removeAttr('disabled');
            } else if (valOpt == 10) {
                $("#m_desc").attr('disabled', false);
            } else {
                $("#m_desc").attr('disabled', true);
            }


            var am = document.getElementById('m_doc').options[document.getElementById('m_doc').selectedIndex].text.split('::');
            if (document.getElementById('m_doc').value > 0 && document.getElementById('m_doc').value != 7) {
                document.getElementById('m_amt').value = am[1];
            } else {
                document.getElementById('m_amt').value = 0
            }

        })


        $('input#doc_remark').on('keypress', function(e) {
            // e.preventDefault()
            // e.stopPropagation()
            if (this.value.length >= 3) {
                let txt = this.value
                setTimeout(() => {
                    getRemarksList(txt)
                }, 200);
            }
        });


    })


    $('#m_doc1').on('change', function() {
        let val = $("option:selected", this).val();
        let text = $("option:selected", this).text()
        $('#hd_doc_type1').val(val);
        $("#m_doc1").val(val);
        $('#select2-m_doc1-container').text($("#m_doc1 option:selected").text())


        if (document.getElementById('m_doc').value == 8 && (val == 50 || val == 63)) {
            var t_pet = document.getElementById('hd_pcount').value;
            for (var i = 1; i <= t_pet; i++) {
                document.getElementById('chk_p' + i).style.display = 'inline';
                document.getElementById('img_p' + i).style.display = 'inline';
            }
        } else {
            var t_pet = document.getElementById('hd_pcount').value;
            for (var i = 1; i <= t_pet; i++) {
                if (document.getElementById('chk_p' + i))
                    document.getElementById('chk_p' + i).style.display = 'none';
                if (document.getElementById('img_p' + i))
                    document.getElementById('img_p' + i).style.display = 'none';
            }
        }

        if (val > 0) {
            if ((document.getElementById('m_doc').value == 8 && val == 19) || (document.getElementById('m_doc').value == 9 && val == 10)) {
                document.getElementById('m_desc').disabled = false;
                document.getElementById('m_desc').focus();
            } else {
                document.getElementById('m_desc').disabled = true;
                document.getElementById('m_desc').value = '';
            }
        } //end if
        else {
            document.getElementById('m_desc').disabled = true;
            document.getElementById('m_amt').value = 0;
        }

    })


    $('#aor_code').on('change', function(e) {
        if (this.value != '') {
            let val = this.value
            let valu = val.split('-')

            setTimeout(() => {
                $('#select2-aor_code-container').text(valu[0])
                $('#name_aor_filed_by').val(valu[1])
            }, 100);
        } else {
            $('#name_aor_filed_by').val('')
        }
    })





    function setFields() {
        $("#b_save").removeProp('disabled');
        $("#doc_remark").val("");
        $("#m_desc").val("");
        $("#m_desc").prop('disabled', true);
        $("#no_of_copy").val("4");
        $("#m_amt").val("0");
        $("#m_doc1").val("");
        $("#m_doc1").prop('disabled', true);
        $("#hd_doc_type1").val("");
        $("#m_doc").val("0");
        $("#name_aor_filed_by").prop('readonly', true);
        $("#if_efil").prop("checked", false);


        var radio_value = $('input[name=radio_filed_by]:checked').val();
        if (radio_value == 'A') {
            $("#aor_code").val("");
            $("#name_aor_filed_by").val("");
        } else if (radio_value == 'P') {
            $("#name_pet_filed_by").val("");
        } else if (radio_value == 'R') {
            $("#name_res_filed_by").val("");
        }

    }


    function delete_ld(id, rno) {
        if (confirm('Sure to Delete')) {
            updateCSRFToken();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Filing/Ia_documents/del_for_ld_del'); ?>',
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        type: 'D',
                        idfull: id
                    }
                })
                .done(function(msg) {
                    alert(msg);
                    location.reload()
                    updateCSRFToken();
                })
                .fail(function() {
                    alert('ERROR, Please Contact Server Room');
                    updateCSRFToken();
                });
        } else {
            return false;
        }
    }


    function verifyFunction() {
        var full_data = new Array();
        var full_data_tb = new Array();
        var check = false;
        var vr = '';
        $("input[type='checkbox'][name^='chk']").each(function() {
            if ($(this).is(":checked") == true) {
                full_data.push($(this).val());
                var thisid = $(this).attr('id');
                thisid = thisid.replace('chk', '');
                full_data_tb.push($('#tb' + thisid).val());
                check = true;
            }
        })

        $("input[type='radio'][name^='radio_verified']").each(function() {
            if ($(this).is(":checked") == true) {
                vr = $(this).val();
            }
        })
        if (check == false) {
            alert("Please select at least one document");
            return false;
        }
        if (vr == '') {
            alert("Please select Verify or Reject");
            return false;
        }
        if (vr == 'V') {
            var vr_text = 'Verify';
        }
        if (vr == 'R') {
            var vr_text = 'Reject';
        }
        vr_text = "Are you sure to " + vr_text + " selected documents";
        if (confirm(vr_text)) {

            updateCSRFToken();

            $("#btnrece").prop('disabled', 'disabled');
            let obj = {
                alldata: full_data,
                vr: vr,
                tb: full_data_tb
            }
            // console.log(obj)

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Filing/Ia_documents/verify_save'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        data: obj
                    }
                })
                .done(function(msg) {
                    // $("#btnrece").removeProp('disabled');
                    if (msg != '') {
                        msg = Number(msg);
                        // console.log("msg: ", msg)
                        if (msg >= 1) {
                            alert(msg + ' : Record updated.');
                            location.reload();
                        } else {
                            alert('No record updated');
                        }
                        updateCSRFToken();
                    } else {
                        alert('Error updating ');
                        updateCSRFToken();
                    }

                })
                .fail(function() {
                    // $("#btnrece").removeProp('disabled');
                    alert("Error Occured, Please Contact Server Room");
                    updateCSRFToken();
                });

        }
    }
</script>