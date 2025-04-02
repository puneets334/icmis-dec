<?= view('header') ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">PIL(E) >> Query PIL Report</h3>
                                </div>
                            </div>
            

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>



                        </div>


                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?= view('PIL/pilReportHeading'); ?>
                      
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'reportpilgroup', 'id'=>"push-form", 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('#'), $attribute);
                        ?>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group row">
                                    <label><h4 style="margin-top: 15%;">Search By:</h4></label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="columnName" id="columnName">
                                            <option value="" >Search by</option>
                                            <option value="n">Applicant Name</option>
                                            <option value="a">Address</option>
                                            <option value="m">mobile</option>
                                            <option value="e">email</option>
                                            <option value="d">Inward Number</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-3 form-group row">

                                    <label><h4 style="margin-top: 15%;">Query Text:</h4></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="qryText" name="qryText" placeholder="Query Text" style="width: 265%;">
                                    </div>

                            </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="col-sm-3">
                                <div class="form-group row">
                                 <button type="button" class="btn bg-blue" style="float:right" id="view" name="view" onclick="return checkSubmit(); ">Search</button>
                                </div>

                          </div>

                        </div><br><br>
                        <?php form_close(); ?>


                        <div id="data_result"> </div>



                    </div>



            </div> <!-- card div -->



        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->




        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->
    <script>


        function checkSubmit() {

            var qryText = document.getElementById('qryText').value;
            var columnName = document.getElementById('columnName').value;
            var regex = /^[a-zA-Z ]+$/;
            var regex_alphanumeric =  /^[A-Za-z0-9_@./#&\-\ ]*$/ ;
            var reg_mail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (qryText.trim()=="") {
                alert("Search Text should not be blank !!");
                return false;
            }
            if(columnName=='m'){
                if (qryText.trim().length<10 || isNaN(qryText.trim())) {
                    alert("Mobile Number Search Text less than 10 digits !!");
                    return false;
                }else if (qryText.trim().length>10 || isNaN(qryText.trim())) {
                    alert("Mobile Number Search Text cannot be more than 10 digits !!");
                    return false;
                }
            } else if(columnName == 'n')
             {
                if(!regex.test(qryText.trim())) {
                    alert("Only Alphabet Is Allowed While Searching For Applicant Name");
                    return false;
                }

            }else if( columnName == 'a')
            {
                // alert("QQ");return false;
                if(!regex_alphanumeric.test(qryText)) {
                    alert("Only Alphanumeric Is Allowed While Searching For Address");
                    return false;
                }

            }else if(columnName == 'e'){
                if(!reg_mail.test(qryText)) {
                    alert('Invalid Email Address');
                    return false;
                }

            }else if(columnName =='d')
            {
                if(qryText.trim().length<5)
                {
                    alert("Please Enter Minimum 5 Digit While Searching For Inward Number(diary no + diary year)");
                    return false;
                }

                if (isNaN(qryText.trim())) {
                    alert("Inward Number should be numeric !!");
                    return false;
                }

            }else if(columnName!='d')
            {
                if (qryText.trim().length<3) {
                    alert("Search Text should not be blank and less than 3 characters !!");
                    return false;
                }
            }else{

            }
            // alert("EEEE");

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('PIL/PilController/queryPilData'); ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    columnName: columnName,
                    qryText: qryText,


                },
                success: function (data) {
                    // console.log(data);
                    // return false;
                    updateCSRFToken();
                    $("#data_result").html(data);

                },
                error: function (data) {
                    updateCSRFToken();
                    alert(data);

                }

            });


        }

    </script>

    <script>
        // $(function () {
        //     $(".datatable_report").DataTable({
        //         "responsive": true, "lengthChange": false, "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        //             { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        //     }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        // });

        


    </script>