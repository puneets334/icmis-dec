<?= view('header') ?>
 
<style>
    .item {
        border: 1px solid #eee;
        box-shadow: 0 0 10px -3px #ccc;
        border-radius: 5px;
        margin-bottom: 30px;
        padding: 25px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Add Application </h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_registration_breadcrum'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">Add Application </h4>
                    </div>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('error') ?></strong>
                                        </div>

                                    <?php } ?>
                                    <?php if (session()->getFlashdata('success_msg')) : ?>
                                        <div class="alert alert-success alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <span id="show_error" class="ml-4 mr-4"></span> <!-- This Segment Displays The Validation Rule -->
                                    <form class="form-horizontal" id="employee-form" method="post" action="<?php echo base_url('Copying/Copying/add_new_application'); ?>">
                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Case Type</label>
                                                    <div class="col-sm-9">
                                                        <select  class="form-control"  name="case_type" style="width: 100%;" id="case_type" data-placeholder="Select Case Type" required>
                                                            <option value="">Select Case Type</option>
                                                            <?php
                                                            foreach ($case_types as $row) {
                                                            ?>
                                                                <option value="<?= $row['casecode'] ?>"><?= $row['skey'] . '::' . $row['casename'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Case No.</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="case_number" name="case_number" placeholder="Case number" class="form-control" onchange="get_diary()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Case Year</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="case_year" name="case_year" placeholder="Case Year" maxlength="4" minlength="4" class="form-control" onchange="get_diary()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Source</label>
                                                    <div class="col-sm-9">
                                                        <select  class="form-control"  name="case_source" style="width: 100%;" id="case_source" data-placeholder="Select Source" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($case_source as $row) {
                                                            ?>
                                                                <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Diary No.</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="diary_number" name="diary_number" placeholder="Diary number" class="form-control" onchange="advocate_or_party_details()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Diary Year</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="diary_year" name="diary_year" placeholder="Diary Year" maxlength="4" minlength="4" class="form-control" onchange="advocate_or_party_details()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Section</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="section" name="section" placeholder="Section" class="form-control" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Category</label>
                                                    <div class="col-sm-9">
                                                        <select  class="form-control"  name="category" style="width: 100%;" id="category" data-placeholder="Select Category" required>
                                                            <option value="">Select Copy Category</option>
                                                            <?php
                                                            foreach ($copy_category as $row) {
                                                            ?>
                                                                <option value="<?= $row['id'] ?>"><?= $row['code'] . '-' . $row['description'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Applied By</label>
                                                    <div class="col-sm-9">
                                                        <select  class="form-control" name="filed" style="width: 100%;" id="filed" data-placeholder="Select Applied By" onchange="advocate_or_party_details()" required>
                                                            <option value="">Select Filed By</option>
                                                            <option value="1">Advocate</option>
                                                            <option value="2">Party of the case</option>
                                                            <option value="3">Appearing Council</option>
                                                            <option value="4">Third Party</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Delivery Mode</label>
                                                    <div class="col-sm-9">
                                                        <select  class="form-control"  name="deliver_mode" style="width: 100%;" id="deliver_mode" data-placeholder="Select Delivery Mode" required>
                                                            <option value="">Select Delivery Mode</option>
                                                            <option value="1">By Post</option>
                                                            <option value="2">By Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label id="type_code" class="col-sm-3 col-form-label">Advocate Code</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control"  name="advocate_or_party" style="width: 100%;" id="advocate_or_party" data-placeholder="Select Delivery Mode" onchange="contact_detail()">
                                                            <option value="">Select</option>
                                                            <option value="0">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Mobile</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" class="form-control" minlength="10" maxlength="10">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-2 col-form-label">Address</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <input type="checkbox" name="send_section" id="send_section" value="t"> Send to Section
                                                        </span>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="multi-field-wrapper">
                                            <div class="multi-fields">

                                                <div class="multi-field">
                                                    <button type="button" class="remove_out_row  d-none btn btn-danger btn-outline-danger" value="1">out-Removed</button>
                                                    <span class="add-field btn btn-outline-success float-sm-right"><i class='fas fa-plus-circle'></i></span>
                                                    <button type="button" class="remove_in_row remove-field d-none btn btn-outline-danger float-sm-right" value="1"><i class='fas fa-minus-circle'></i></button>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group row">
                                                                <label for="From" class="col-sm-3 col-form-label">Document</label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" name="order_type[]" id="order_type[]" required>
                                                                        <option value="" title="Select">Select Document Type</option>
                                                                        <?php
                                                                        foreach($order_type as $doc){

                                                                            echo '<option value="'.$doc['id'].'">'.$doc['order_type'].'</option>';

                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group row">
                                                                <label for="From" class="col-sm-3 col-form-label">Order Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="date" class="form-control" id="orderDate[]" name="orderDate[]" value="" required >
                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group row">
                                                                <label for="From" class="col-sm-3 col-form-label">Copies</label>
                                                                <div class="col-sm-9">
                                                                    <input type="number" class="form-control copies_set" id="copies[]" name="copies[]" onkeypress="return onlynumbers(event);" value="1" required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">Court Fee</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="court_fee" name="court_fee" placeholder="Court Fee" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-7">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-2 col-form-label">Remarks</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            </div>
                                            <div class="col-sm-6">
                                                <span class="input-group-append">
                                                    <input type="submit" class="btn btn-success" onclick="addApplication()" value="Add Application">
                                                </span>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                    </div>

                </div>
                <div id="result_data" style="display: none;">
                    <div class="col-md-12">
                        <div class="well">
                            <div class="card">
                                <div class="card-header mt-4" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Previous Applies </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <tbody id="dataforpreviousApplies">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
<script>
    $('.remove_in_row').click(function() {
        var v = $(this).val();
        $('.delete_out_row_' + v).click();
        $(this).parent('.multi-field').remove();
    });

    $('.multi-field-wrapper').each(function() {
        var $wrapper = $('.multi-fields', this);
        $(".add-field", $(this)).click(function(e) {
            var length_row = $('.multi-field', $wrapper).length;
            var delete_out_row = 'delete_out_row_' + length_row;
            var delete_in_row = 'delete_in_row_' + length_row;
            $(".remove_out_row:first").val(length_row);
            $(".remove_in_row:first").val(length_row);

            $(".remove_out_row:first").addClass(delete_out_row);
            $(".remove_in_row:first").addClass(delete_in_row);
            $(".add-field:first").addClass("d-none");
            $(".remove-field:first").removeClass("d-none");

            $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('');
            $(".remove_out_row:first").removeClass(delete_out_row);
            $(".remove_in_row:first").removeClass(delete_in_row);

            $(".add-field:first").removeClass("d-none");
            $(".remove-field").removeClass("d-none");
            $(".remove-field:first").addClass("d-none");
            $(".copies_set").val("1");
        });

        $('.multi-field .remove_out_row', $wrapper).click(function() {
            var length_row = $('.multi-field', $wrapper).length;
            // alert('length_row='+length_row);
            if ($('.multi-field', $wrapper).length > 1)
                $(this).parent('.multi-field').remove();
        });


    });

    function filed_by() {
        $("#mobile").attr("readonly", true);
        $("#mobile").removeAttr("required");
        $("#mobile").val("");
        var filed = $("#filed").val();
        if (filed == 1) {
            $('#type_code').html("Advocate Code");
        } else if (filed == 2) {
            $('#type_code').html("Party");
        } else if (filed == 3 || filed == 4) {
            $("#mobile").attr("required", true);
            $("#mobile").removeAttr("readonly");
            $('#type_code').html("Advocate Code");
        }
    }

    function isEmpty(str) {
        return (!str || 0 === str.length);
    }

    function get_diary() {
        var case_type = $("#case_type").val();
        var case_number = $("#case_number").val();
        var case_year = $("#case_year").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (!isEmpty(case_type) && !isEmpty(case_number) && !isEmpty(case_year)) {

            $.ajax({
                url: '<?php echo base_url('Copying/Copying/get_diary'); ?>',
                cache: false,
                async: true,
                data: {
                    case_type: case_type,
                    case_number: case_number,
                    case_year: case_year,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data, status) {
                    if (data.length == 0) {
                        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please try adding application by Diary Number* </strong></div>');
                        return false;
                    } else {
                        data=data.trim();
                        d_yr = data.slice(-4);
                        d_no = data.slice(0,-4);
                        $("#diary_number").val(d_no);
                        $("#diary_year").val(d_yr);
                        $('#show_error').html("");
                        setTimeout(function() {
                            previous_applies();
                        }, 500);

                    }
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });

        }
    }

    function previous_applies() {
        previous_applies_list = [];
        var diary_number = $("#diary_number").val();
        var diary_year = $("#diary_year").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if(!isEmpty(diary_number) && !isEmpty(diary_year) ){
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/previous_applies'); ?>',
                data: {
                    diary_number: diary_number,
                    diary_year: diary_year,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    if (data.length == 0) {
                        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please try adding application by Diary Number* </strong></div>');
                        return false;
                    } else {
                        data = data.trim();
                        if(data != 0){
                            //alert(data);
                            $('#result_data').css("display","block");

                            var html = "";
                            var obj =$.parseJSON(data);

                                html += '<tr><th style="width: 10px">#</th><th>Application Number</th><th>Court Fee</th> <th>Applied By</th> <th>Applied On</th> <th>Status</th></tr>';
                                // Loop the parsed JSON
                                $.each(obj, function(key,value) {
                                    var indexing = parseInt(key)+1;
                                    var formattedDate = new Date(value.received_on);
                                    var f_d = formattedDate.getDate();
                                    var m_received_on =  formattedDate.getMonth();
                                    m_received_on += 1;  // JavaScript months are 0-11
                                    var y_received_on = formattedDate.getFullYear();
                                    var received_on_date = f_d + "-" + m_received_on + "-" + y_received_on;

                                    html += '<tr>';
                                    html += "<td>"+indexing+"</td>";
                                    html += "<td>"+value.application_number_display+"</td>";
                                    html += "<td>"+value.court_fee+"</td>";
                                    html += "<td>"+value.name+"</td>";
                                    html += "<td>"+received_on_date+"</td>";
                                    html += "<td>"+value.status+"</td>";
                                    html += '</tr>';
                                });

                            $('#dataforpreviousApplies').html(html);
                        }else{
                            $('#dataforpreviousApplies').html('');
                        }

                    }
                },
                error: function(xhr) {
                    updateCSRFToken();
                }

            });
        }else{
            $('#show_error').html("");
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please try adding application by Diary Number* </strong></div>');
            return false;
        }

    }

    function contact_detail(){
        var diary_number = $("#diary_number").val();
        var diary_year = $("#diary_year").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var applied_by = $('#filed').val();
        var selected_val = $('#advocate_or_party').val();
        var diary_no = $('#diary_number').val()+''+$('#diary_year').val();
        if((applied_by==1 && selected_val>0) || applied_by==2) {
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/contact_detail'); ?>',
                cache: false,
                async: true,
                data: {
                    diary_no: diary_no,
                    selected_val: selected_val,
                    applied_by: applied_by,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function (data, status) {
                  contactdetailsArray = data.split("|");
                  $('#name').val(contactdetailsArray[0]);
                  $('#mobile').val(contactdetailsArray[1]);
                  $('#address').val(contactdetailsArray[2]);
                  updateCSRFToken();
                },
                error: function (xhr) {
                    updateCSRFToken();
                }
            });
        }else{
            $('#name').val('');
            $('#mobile').val('');
            $('#address').val('');
        }
    }

    function advocate_or_party_details(){
        filed_by();
        $('#name').val('');
        $('#mobile').val('');
        $('#address').val('');

        // setTimeout(function() {
        //     previous_applies();
        // }, 200);
        var diary_number = $("#diary_number").val();
        var diary_year = $("#diary_year").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var filed = $("#filed").val();

        if (!isEmpty(diary_number) && !isEmpty(diary_number) && !isEmpty(filed)) {
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/advocate_or_party_details'); ?>',
                cache: false,
                async: true,
                data: {
                    diary_number: diary_number,
                    diary_year: diary_year,
                    filed: filed,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function (data, status) {
                    advocateString = data.trim();
                    const advocatesArray = advocateString.split("|");
                    advocateData = advocatesArray[0];
                    section_name = advocatesArray[1];

                    //advocateString =[{"code":"2665","name":"RONY OOMMEN JOHN(R)","type":"1","sec":"IX"},{"code":"1841","name":"PRAGYA BAGHEL(P)","type":"1","sec":"IX"}];
                    $('#advocate_or_party').children().remove();
                    var advocate_or_party = $("#advocate_or_party");
                    advocate_or_party.append(advocateData);
                    $('#section').val(section_name);
                    updateCSRFToken();
                },
                error: function (xhr) {
                    updateCSRFToken();
                }
            });
        }
    }

    function onlynumbers(evt) {
        evt = evt ? evt : window.event;
        var charCode = evt.which ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
            return true;
        }
        return false;
    }

    function isEmpty(str) {
        return (!str || 0 === str.length);
    }

    function addApplication(){
        var diary_number = $("#diary_number").val();
        var diary_year = $("#diary_year").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var category = $("#category").val();
        var deliver_mode = $("#deliver_mode").val();
        var filed = $("#filed").val();
        var court_fee = $("#court_fee").val();
        var address = $("#address").val();
        var mobile = $("#mobile").val();

        if(!isEmpty(category) && !isEmpty(filed) && !isEmpty(diary_number) && !isEmpty(diary_year) && !isEmpty(deliver_mode) && !isEmpty(court_fee)  && !isEmpty(address) &&  (filed==1 || filed==2 || (filed==3 && !isEmpty(mobile)) || (filed==4 && !isEmpty(mobile)))             ){
            console.log($scope.fields);

            var employee_form = jQuery("#employee-form");
            jQuery.post("<?php echo base_url('Copying/Copying/add_new_application'); ?>", {
                data: employee_form.serialize(),
            })
            .done(function(data) {
                if(data == 1){
                    alert("Data saved successfully.")
                    location.reload();
                }
                updateCSRFToken();
            });
            updateCSRFToken();

        }
    }
</script>