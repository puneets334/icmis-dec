<?= view('header') ?>
<style>
    .dropdown-content {
        display: none;
        /* Start hidden */
        position: absolute;
        background-color: #f6f6f6;
        width: 35vw;
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
        /* Highlight on hover */
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Registration Form</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="margin-top: 5%;">
                            <input type="text" id="myInput" class="form-control" placeholder="Search Employee..">
                            <div id="myDropdown" class="dropdown-content"></div>
                        </div>

                        <?php
                        echo form_open();
                        csrf_field(); ?>
                        <div id="formData" class="form-horizontal" style="display:none; padding: 30px;">
                            <input type="hidden" name="empid" id="empid" />
                            <div class="form-group">
                                <label for="fullname">Name:</label>
                                <input type="text" class="form-control" id="fullname" placeholder="Enter Full Name"
                                    name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter email"
                                    name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="username">User Name:</label>
                                <input type="text" class="form-control" id="username" placeholder="Enter User name"
                                    name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="selectedUsertype">User Type:</label>
                                <select id="selectedUsertype" name="selectedUsertype" class="form-control" required>
                                    <option value="SUPERADMIN">SUPER ADMIN</option>
                                    <option value="LIBRARIAN">LIBRARIAN</option>
                                    <option value="COURT ASSISTANT">COURT ASSISTANT</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ph1">Phone Number:</label>
                                <input type="text" class="form-control" id="ph1" placeholder="Enter Phone Number"
                                    name="ph1" required maxlength="10">
                            </div>

                            <div class="form-group">
                                <label for="ph2">Alternate Phone Number:</label>
                                <input type="text" class="form-control" id="ph2"
                                    placeholder="Enter Alternate Phone Number" name="ph2" maxlength="10">
                            </div>

                            <div class="form-group">
                                <label for="court_no">Court Number:</label>
                                <select id="court_no" name="court_no" class="form-control">
                                    <option value="">Select Court</option>
                                    <?php foreach ($courtArr as $res) { ?>
                                        <option value="<?php echo $res['requisition_dep_name']; ?>">
                                            <?php echo $res['requisition_dep_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <button id="changedata" type="button" class="btn btn-primary">Submit</button>
                                <button style="display:none;" id="deactiv" type="button"
                                    class="btn btn-warning actionCmd">De-Activate</button>
                                <button style="display:none;" id="activ" type="button"
                                    class="btn btn-warning actionCmd">Activate</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- End of Form Data Section -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myInput').on('keyup change', function(e) {
            const inputVal = e.target.value.trim();
            if (inputVal !== '') {
                $.ajax({
                    url: '<?php echo base_url('Library/Registration/getEmployees'); ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#myDropdown').empty().show();

                        if (data.length > 0) {
                            data.forEach(employee => {
                                $('#myDropdown').append(
                                    `<a href="#" class="suggestion" data-usercode="${employee.usercode}">${employee.name} (${employee.empid})</a>`
                                );
                            });
                        } else {
                            $('#myDropdown').append(
                                '<a class="noRecords" style="display: block;" id=""> No Records.</a>'
                            );
                        }
                        filterDropdown(inputVal);
                    },
                    error: function() {
                        console.error('Error fetching employees.');
                    }
                });
            } else {
                $('#myDropdown').hide();
            }
        });

        function filterDropdown(inputVal) {
            const filter = inputVal.toUpperCase();
            $('#myDropdown a').each(function() {
                const txtValue = $(this).text() || '';
                $(this).toggle(txtValue.toUpperCase().includes(filter));
            });
        }

        $(document).on('click', '.suggestion', function(e) {
            e.preventDefault();
            const selectedText = $(this).text();
            const empid = $(this).data('usercode');

            $('#myInput').val(selectedText);
            $('#empid').val(empid);
            $('#myDropdown').hide();
            $('#formData').show();

            fetchEmployeeDetails(empid);
        });

        function fetchEmployeeDetails(empid) {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                    url: "<?php echo base_url('Library/Registration/searchData'); ?>",
                    type: "POST",
                    data: {
                        selectedValue: empid,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    dataType: 'json',
                    success: function(response) {
                        updateCSRFToken();

                        if (response && Object.keys(response).length > 0) {  // ✅ Proper check for object
                          
                            $('#fullname').val(response.fullname || '');
                            $('#email').val(response.adminemail || '');
                            $('#username').val(response.username || '');
                            $('#selectedUsertype').val(response.user_type || '');
                            $('#ph1').val(response.phone_number || '');
                            $('#ph2').val(response.alternative_phone_no || '');
                            $('#court_no').val(response.court_no || '');

                            if (response.status == 1) {
                                $('#deactiv').show();
                                $('#activ').hide();
                            } else {
                                $('#activ').show();
                                $('#deactiv').hide();
                            }
                        } else {
                            clearEmployeeDetails();  // ✅ Reset form if no data
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching employee details: ", textStatus, errorThrown);
                    }
                });
        }

        function clearEmployeeDetails() {
            $('#fullname').val('');
            $('#email').val('');
            $('#username').val('');
            $('#selectedUsertype').val('');
            $('#ph1').val('');
            $('#ph2').val('');
            $('#court_no').val('');
            $('#deactiv').hide();
            $('#activ').hide();
        }

        $('#changedata').on('click', function(e) {
            e.preventDefault();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            const empid = $('#empid').val();
            const fname = $('#fullname').val();
            const email = $('#email').val();
            const uname = $('#username').val();
            const utype = $('#selectedUsertype').val();
            const ph1 = $('#ph1').val();
            const ph2 = $('#ph2').val();
            const court_no = $('#court_no').val();

            if (!fname || !email || !uname || !utype || !ph1) {
                alert('Please fill all required fields.');
                return;
            }

            if (ph1 && !/^\d+$/.test(ph1)) {
                alert('Invalid phone number');
                return;
            }

            if (ph2 && !/^\d+$/.test(ph2)) {
                alert('Invalid alternate phone number');
                return;
            }

            if (court_no && !/^\d+$/.test(court_no)) {
                alert('Invalid Court number');
                return;
            }

            $.ajax({
                url: "<?php echo base_url('Library/Registration/insertUpdate'); ?>",
                type: "POST",
                data: {
                    empid: empid,
                    fullname: fname,
                    email: email,
                    username: uname,
                    usertype: utype,
                    phone1: ph1,
                    alterphone: ph2,
                    court_no: court_no,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(response) {
                    updateCSRFToken();
                    if (response == '1') {
                        alert('Data inserted successfully.');
                        location.reload();
                    } else if (response == '2') {
                        alert('Data updated successfully.');
                        location.reload();
                    } else {
                        alert('Something went wrong.');
                    }
                },

            });
        });




        // Activate/Deactivate buttons functionality
        $('#deactiv').on('click', function() {
            const empid = $('#empid').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Library/Registration/changeStatus'); ?>",
                type: "POST",
                data: {
                    name: 'changeStatus',
                    status: '0',
                    empid: empid,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(response) {
                    updateCSRFToken();
                    alert("Employee deactivated successfully.");
                    $('#deactiv').hide();
                    $('#activ').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error changing status: ", textStatus, errorThrown);
                }
            });
        });

        $('#activ').on('click', function() {
            const empid = $('#empid').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Library/Registration/changeStatus'); ?>",
                type: "POST",
                data: {
                    name: 'changeStatus',
                    status: '1',
                    empid: empid,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(response) {
                    alert("Employee activated successfully.");
                    $('#activ').hide();
                    $('#deactiv').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error changing status: ", textStatus, errorThrown);
                }
            });
        });

        // Hide dropdown when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#myInput, #myDropdown').length) {
                $('#myDropdown').hide();
            }
        });
    });
</script>