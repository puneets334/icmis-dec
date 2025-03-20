<?= view('header') ?>
 
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
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
                                    <h3 class="card-title">Web Casting >> WebCast Management & Link Details</h3>
                                </div>


                            </div>
                            <br><br>

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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a id="advocate" class="nav-link active" href="#court_tab_panel" data-toggle="tab">Court List</a></li>
                                            <li class="nav-item"><a id="search" class="nav-link" href="#journalist_tab_panel" data-toggle="tab">Journalist List</a></li>
                                            <li class="nav-item"><a id="search" class="nav-link" href="#vc_tab_panel" data-toggle="tab">VC Web Cast Details</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="court_tab_panel">

                                                <table id="example1" class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>

                                                        <th>S.No.</th>
                                                        <th>Court No</th>
                                                        <th>Is No FN</th>
                                                        <th>Is VC Meet</th>
                                                        <th>Display</th>
                                                        <th>Action</th>

                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php

                                                    if (!empty($court)) {
                                                        $i = 1;

                                                        foreach ($court as $row) {
                                                            $id = $row['id'];

                                                            ?>

                                                            <tr>
                                                                <td><?= $i++; ?></td>
                                                                <td>VC No.<?php echo (int)$row['courtno'] - 30; ?></td>
                                                                <td><?= $row['is_nofn']; ?></td>
                                                                <td><?= $row['is_vcmeet']; ?></td>
                                                                <td><?= $row['display']; ?></td>
                                                                <td>

                                                                    <button type="button" name="edit" onclick="editFunction(<?= $id; ?>)"><i class="fas fa-pen" style="color: #5cb85c" aria-hidden="true"></i></button>
                                                                    |

                                                                    <button type="button" onclick="deleteFunction(<?= $id; ?>)" name="delete" class="btn btn-danger btn-sm"> <i class="fas fa-trash" aria-hidden="true"></i> </button>


                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } ?>

                                                    </tbody>
                                                </table>

                                            </div>


                                            <div class="tab-pane" id="journalist_tab_panel">
                                                <table id="example1" class="table table-striped table-bordered">
                                                    <button type="button" class="btn btn-danger thoughtbot" name="add_journalist" onclick="addFunc()" >Add Journalist</button>
                                                    <thead>

                                                    <tr>

                                                        <th>S.No.</th>
                                                        <th>Name</th>
                                                        <th>Media Name</th>
                                                        <th>Mobile</th>
                                                        <th>Display</th>
                                                        <th>Action</th>

                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php
                                                    if (!empty($fuel)) {
                                                        $i = 1;

                                                        foreach ($fuel as $row) {
                                                            $id = $row['id'];
                                                            ?>

                                                            <tr>
                                                                <td><?= $i++; ?></td>
                                                                <td><?= $row['name']; ?></td>
                                                                <td><?= $row['media_name']; ?></td>
                                                                <td><?= $row['mobile']; ?></td>
                                                                <td><?= $row['display']; ?></td>
                                                                <td>

                                                                    <button type="button" name="edit_j" onclick="editJFunction(<?= $id; ?>)"><i class="fas fa-pen" style="color: #5cb85c" aria-hidden="true"></i></button>
                                                                    |

                                                                    <button type="button" onclick="deleteJFunction(<?= $id; ?>)" name="delete_j" class="btn btn-danger btn-sm"> <i class="fas fa-trash" aria-hidden="true"></i> </button>


                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } ?>


                                                    </tbody>
                                                </table>


                                            </div>

                                            <div class="tab-pane" id="vc_tab_panel">
                                                <h4>VC Link</h4>
                                                <?php
                                                $attribute = array('class' => 'form-horizontal','enctype'=>'multipart/form-data', 'name' => 'vcform', 'id' => 'vcform', 'autocomplete' => 'off');
                                                echo form_open(base_url('#'), $attribute);

                                                ?>
                                                <br><br>


                                                <div>
                                                    <input type="radio" id="single" name="sorb" value="s" onclick="showhide(this.id)" checked>
                                                    <label  for="single">Single Entry</label>
                                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                    <input type="radio" id="bulk" name="sorb" value="b" onclick="showhide(this.id)">
                                                    <label for="bulk">Entry from file</label>
                                                </div><br>



                                                        <div id="sentry" >

                                                            <div style="margin-top: 30px;margin-left: 15px;" id="row1" >
                                                                <div id="datp" >
                                                                    <label for="webex" class="control-label col-md-3">Cause List Date</label>
                                                                    <div class="control-label col-md-3">
                                                                        <input type="date" class="form-control"  id="bench_date" name="bench_date" placeholder="">
                                                                    </div>
                                                                </div>

                                                                <label class="control-label col-md-3">Court No.</label>
                                                                <div class="control-label col-md-3">
                                                                    <select  class="select form-control" id="virtual_court_number" name="virtual_court_number">
                                                                        <option value=""> ----select Court----</option>
                                                                        <?php
                                                                        $value= "<p id='result'>"."</p>";
                                                                        for ($x = 31; $x <= 47; $x++) {
                                                                            echo "<option value='$x'>"."Virtual Court No.".($x-30)."</option>";
                                                                            echo $value;
                                                                        }
                                                                        ?>
                                                                        <option value="61"> Virtual Registrar Court 1</option>
                                                                        <option value="62"> Virtual Registrar Court 2</option>

                                                                    </select>
                                                                </div>


                                                            </div>

                                                            <div style="margin-top: 30px;margin-left: 15px;" id="row2" class="form-group">
                                                                <label for="issb"> Is link for Special Bench</label>
                                                                <div class="control-label col-md-6">
                                                                    <input type="checkbox" id="issb" name="issb" value="Y">
                                                                </div>

                                                            </div>


                                                            <div style="margin-top: 30px;margin-left: 15px;" id="row3" class="form-group">

                                                                <div id="web" >
                                                                    <label for="webex" class="control-label col-md-3">Webex Link:</label>
                                                                    <div class="control-label col-md-3">
                                                                        <input class="form-control"  type="text" id="webex" name="webex" placeholder="">
                                                                    </div>
                                                                </div>

                                                                <div id="sb" style="display: none;">
                                                                    <label for="speclink" class="control-label col-md-3">Special Bench Link:</label>
                                                                    <div class="control-label col-md-3">
                                                                        <input class="form-control"  type="text" id="speclink" name="speclink" placeholder="">
                                                                    </div>
                                                                </div>

                                                                <label for="bench_timing" class="control-label col-md-3">Bench Timing:</label>
                                                                <div class="control-label col-md-3">
                                                                    <input class="form-control" type="time" id="bench_timing" name="bench_timing" placeholder="">
                                                                </div>


                                                            </div>



                                                            <div style="margin-top: 30px;margin-left: 15px;" id="row4" class="form-group">
                                                                <label for="remarks" class="control-label col-md-3">Remarks:</label>
                                                                <div class="control-label col-md-3">
                                                                    <textarea  class="form-control" id="remarks" name="remarks" rows="4" cols="20"></textarea>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div id="bentry" style="display: none;">
                                                            <div >
                                                                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />

                                                                <label for="myfile">Select a file:</label>
                                                                <input name="userfile" type="file" />
                                                            </div>
                                                        </div><br><br>


                                                        <div >
                                                            <button type="button"  id="view" name="view" class="btn btn-primary" onclick="savedata();"><B>INSERT</B></button>
                                                            <button type="button"  id="update" name="update" class="btn btn-primary" onclick="updatedata();"><B>UPDATE</B></button>

                                                        </div>


                                                        <?= form_close(); ?>


                                            </div>






                                            <!-- /.tab-content -->
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>


                    </div> <!-- card div -->



                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- modal start for ADD JOURNALIST-->
            <div class="modal fade" id="modal_add_journalist">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 style="color:orange">Add Journalist</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'modal_edit', 'id' => 'modal_edit', 'autocomplete' => 'off');
                            echo form_open(base_url('WebCasting/Home/AddMediaPersons'), $attribute);

                            ?>

                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label"> Name : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name" onkeyup="this.value = this.value.toUpperCase();" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Media Name : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="media_name" placeholder="Enter Media Name" onkeyup="this.value = this.value.toUpperCase();" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Mobile : </label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="mobile" placeholder="Enter Mobile" pattern="^(\+91[\-\s]?)?[0]?(91)?[6789]\d{9}$" required>
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Display : </label>
                                <div class="col-sm-9">
                                    <input type="radio" name="display" id="display1_modal_add" value="Y" checked>YES
                                    <input type="radio" name="display" id="display2_modal_add" value="N"> NO
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success rounded pull-right">Submit</button> &nbsp;&nbsp;
                                    <button type="button" class="btn btn-default rounded pull-left">Close</button>

                                </div>
                            </div>




                            <?= form_close(); ?>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <!-- modal end ADD JOURNALIST-->



            <!-- modal start for UPDATE JOURNALIST-->
            <div class="modal fade" id="modal_edit_journalist">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 style="color:orange">Update Journalist</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'modal_editj', 'id' => 'modal_editj', 'autocomplete' => 'off');
                            echo form_open(base_url('WebCasting/Home/UpdateMediaPerson'), $attribute);

                            ?>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label"> Id : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="id_editj" id="id_editj"  required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label"> Name : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name_editj" id="name_editj"  onkeyup="this.value = this.value.toUpperCase();" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Media Name : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="media_name_editj" id="media_name_editj"  onkeyup="this.value = this.value.toUpperCase();" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Mobile : </label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="mobile_editj" id="mobile_editj"  pattern="^(\+91[\-\s]?)?[0]?(91)?[6789]\d{9}$" required>
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Display : </label>
                                <div class="col-sm-9">
                                    <input type="radio" name="display" id="display1_modal_editj" value="Y" checked>YES
                                    <input type="radio" name="display" id="display2_modal_editj" value="N"> NO
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success rounded pull-right">Submit</button> &nbsp;&nbsp;
                                    <button type="button" class="btn btn-default rounded pull-left">Close</button>

                                </div>
                            </div>




                            <?= form_close(); ?>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <!-- modal end  UPDATE JOURNALIST-->






            <!-- modal start UPDATE COURT LIST-->
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'modal_edit', 'id' => 'modal_edit', 'autocomplete' => 'off');
                            echo form_open(base_url('WebCasting/Home/Update_Courtno'), $attribute);

                            ?>

                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label"> ID : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="id" id="id_modal">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Court No : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="courtno" id="courtno_modal" onkeyup="this.value = this.value.toUpperCase();" required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">IS No FN : </label>
                                <div class="col-sm-9">
                                    <input type="radio" name="fn" id="fn_no1_modal" value="Y"> YES
                                    <input type="radio" name="fn" id="fn_no2_modal" value="N">NO


                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">IS VC MEET : </label>
                                <div class="col-sm-9">
                                    <input type="radio" name="vc" id="vc1_modal" value="Y">YES
                                    <input type="radio" name="vc" id="vc2_modal" value="N"> NO

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name3" class="col-sm-3 control-label">Display : </label>
                                <div class="col-sm-9">
                                    <input type="radio" name="display" id="display1_modal" value="Y">YES
                                    <input type="radio" name="display" id="display2_modal" value="N"> NO
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success rounded pull-right">Submit</button> &nbsp;&nbsp;
                                    <button type="button" class="btn btn-default rounded pull-left">Close</button>

                                </div>
                            </div>




                            <?= form_close(); ?>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <!-- modal end -->



        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->
    <script>
        function addFunc()
        {
            $('#modal_add_journalist').modal('toggle');

        }
    </script>
<script>

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function showhide(id)
    {

    var entrytype = document.getElementById(id).value;
    // alert(entrytype);
    if (entrytype=='s')
    {
    document.getElementById("vcform").reset();
    document.getElementById("single").checked = true;
    // document.getElementById('contentdiv').innerHTML = "";
    document.getElementById('sentry').style.display='block';
    document.getElementById('bentry').style.display='none';
    // $("#grid").html(null);


    }
    else if(entrytype=='b')
    {
    document.getElementById("vcform").reset();
    document.getElementById("bulk").checked = true;
    document.getElementById('bentry').style.display='block';
    document.getElementById('sentry').style.display='none';
    // $("#grid").html(null);
    }
    }

    $('#issb').on('change', function(){
        var isChecked = $('#issb').is(':checked');
        if (isChecked==true)
        {

            document.getElementById('sb').style.display='block';
            document.getElementById('web').style.display='none';
            document.getElementById('webex').value=null;

        }else {
            document.getElementById('web').style.display='block';
            document.getElementById('sb').style.display='none';
            document.getElementById('speclink').value=null;

        }

    });

    function savedata()
    {
        // updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var courtdate = $('#bench_date').val();
        var courtno = $('#virtual_court_number').val();
        var linksb = $('#issb').val();
        var webex = $('#webex').val();
        var btime = $('#bench_timing').val();
        var remark = $('#remarks').val();
        // alert(CSRF_TOKEN_VALUE);
        // var myform = document.getElementById("vcform");
        //  console.log(myform);
        // var fd = new FormData(myform);
        // alert(fd);

        if($('#issb').is(':checked'))
        {
            var spl = $('#speclink').val();
        }
        alert(courtdate+">>"+courtno+">>"+linksb+">>"+webex+">>"+btime+">>"+remark+">>"+spl);

        let dat = {
            CSRF_TOKEN: CSRF_TOKEN_VALUE,
            'cd': courtdate,
            'cn': courtno,
            'webex':webex,
        }

        setTimeout(function(){
            $.ajax({
                type: "POST",
                data:{
                    dat
                },
                // cache : false,
                // processData: false,
                // dataType: 'JSON',
                url: "<?php echo base_url('WebCasting/Home/insert_data'); ?>",
                success: function (data) {
                    console.log(data);
                    alert(data);
                    // updateCSRFToken();
                },
                error: function(data) {
                    alert(data);
                    alert('No Rows inserted');
                    updateCSRFToken();
                }
            });
        },500)


    }


</script>
    <script>
        function editJFunction(id)
        {
            $('#modal_edit_journalist').modal('toggle');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var id = id;
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'id': id,
                },
                dataType: 'JSON',
                url: "<?php echo base_url('WebCasting/Home/editMediaPersons'); ?>",
                success: function(data) {
                    if (data) {
                        // alert(data);
                         $('#id_editj').val((data[0].id));
                         $('#name_editj').val((data[0].name));
                        $('#media_name_editj').val((data[0].media_name));
                        $('#mobile_editj').val((data[0].mobile));
                        if (data[0].display == 'Y') {
                            $('#display1_modal_editj').prop("checked", true);
                        } else {
                            $('#display2_modal_editj').prop("checked", true);
                        }


                    } else {
                        alert(data);
                    }
                    updateCSRFToken();
                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });

        }

        function deleteJFunction(id)
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var text = 'Are you sure you want to Delete this record?';
            if (confirm(text) == true) {

                var display = "N";
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        'id': id,
                        'display': display,
                    },
                    url: "<?php echo base_url('WebCasting/Home/DeleteJournalist'); ?>",
                    success: function(data) {
                        if (data) {
                            alert(data);
                            window.location.reload();
                        }
                        updateCSRFToken();
                    },
                    error: function(data) {
                        alert(data);
                        updateCSRFToken();
                    }
                });
            }
        }
    </script>

    <script>
        function updateCSRFToken() {
            $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }

        function deleteFunction(id) {
            // alert("SD");
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var text = 'Are you sure you want to Delete this record?';
            if (confirm(text) == true) {

                var display = "N";
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        'id': id,
                        'display': display,
                    },
                    url: "<?php echo base_url('WebCasting/Home/DeleteCourtNo'); ?>",
                    success: function(data) {
                        if (data) {
                            alert(data);
                            window.location.reload();
                        }
                        updateCSRFToken();
                    },
                    error: function(data) {
                        alert(data);
                        updateCSRFToken();
                    }
                });
            }


        }
    </script>

    <script>
        function editFunction(id) {
            // alert("EDI");
            $('#modal-default').modal('toggle');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var id = id;
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'id': id,
                },
                dataType: 'JSON',
                url: "<?php echo base_url('WebCasting/Home/ModelUpdate'); ?>",
                success: function(data) {
                    if (data) {
                        console.log(data);
                        $('#id_modal').val((data[0].id));
                        $('#courtno_modal').val((data[0].courtno));
                      //  alert("fn="+data[0].is_nofn);
                      //  alert("vc="+data[0].is_vcmeet);
                        if (data[0].is_nofn == 'Y') {
                            $('#fn_no1_modal').prop("checked", true);

                        } else {
                            $('#fn_no2_modal').prop("checked", true);
                        }
                        if (data[0].is_vcmeet == 'Y') {
                            $('#vc1_modal').prop("checked", true);

                        } else {
                            $('#vc2_modal').prop("checked", true);
                        }
                        if (data[0].display == 'Y') {
                            $('#display1_modal').prop("checked", true);

                        } else {
                            $('#display2_modal').prop("checked", true);
                        }


                    } else {
                        alert(data);
                    }
                    updateCSRFToken();
                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });

        }
    </script>


    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>


 <?=view('sci_main_footer') ?>