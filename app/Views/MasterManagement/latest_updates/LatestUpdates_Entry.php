<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
 <style>
    #reportTable1_filter{
    padding-right: 84%
}
       
.dataTables_filter{
    display: table;
}
div.dt-buttons {
    margin-bottom: -38px;
    margin-right: -80%;
}
div.dataTables_wrapper {
    position: relative;
    margin-top: 24px;
}

.dataTables_info{
    margin-top: 34px;
}
#grid_paginate{
    margin-top: 25px;
}

thead{
    color: rgb(169, 68, 66);
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
                        <h3 class="card-title">Master Management >> Latest Updates </h3>
                    </div>
                    <div class="col-sm-2"> </div>
                </div>
            </div>
            <br /><br />
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12"> <!-- Right Part -->
                            <div class="form-div">
                                <div class="d-block text-center">


                                     <!-- Main content -->  
                                  
                            <div class="box box-info">
                           
                                    <form class="form-horizontal">
                                    <?= csrf_field() ?>
                                        <div class="box-body">
                                            <div class="row mt-3">
                                                <div class="col-sm-4">
                                                    <label for="updated_for" class="">Updated for</label>
                                                    <select class="form-control" id="updated_for" name="updated_for" placeholder="Updated For" required>
                                                        <option value="0">-- Select Updated For --</option>
                                                        <?php
                                                        foreach($updates as $update) {
                                                            echo '<option value="' .$update['mno'].'^'.$update['menu_name']. '" ' . (isset($_POST['updated_for']) && $_POST['updated_for'] == $update['menu_name'] ? 'selected="selected"' : '') . '>' . $update['menu_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                    <div class="col-sm-4">
                                                        <label for="from_date" id="lbl_from_date" class="col-sm-6">From Date:</label>
                                                        <input type="text" id="from_date" value="<?php if(isset($_POST['from_date'])) echo date("d-m-Y", strtotime(strtr($_POST['from_date'],'/','-')));?>" name="from_date" class="form-control datepick"  placeholder="From Date" required="required">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="to_date" id="lbl_to_date" class="col-sm-6">To Date:</label>
                                                        <input type="text" id="to_date" value="<?php if(isset($_POST['to_date'])) echo date("d-m-Y", strtotime(strtr($_POST['to_date'],'/','-'))); ?>" name="to_date" class="form-control datepick"  placeholder="To Date" required="required">
                                                    </div>
                                               
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-sm-4">
                                                    <label for="description" class="col-sm-6">Description</label>
                                                    <textarea class="form-control" rows="1"  id="description" required></textarea>
                                                </div>
                                                <div class="col-6 mt-5 justify-content-end">
                                                <button type="button" id="button_id" name="button_id" class="btn btn-primary">Insert</button>
                                            </div>
                                            </div>
                                            <input type="hidden" name="usercode" id="usercode" value="<?php  echo $usercode; ?>" >
                                        </div>
                                    </form>
                                </div>


                                <div id="display" class="box box-danger">
                                   
                                    <br>
                                    <hr>
                                    <div class="table-responsive">
                                    <table width="100%" id="reportTable1" class="table table-striped table-hover">
                                        <thead>
                                        <h3 style="text-align: center;"> Latest Updates</h3>
                                        <tr>
                                            <th>S No.</th>
                                            <th>updated For</th>
                                            <th>Description</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <!--  <th>Traced Ip</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="alert alert-info alert-dismissable fade in" id="info-alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Info! </strong>
                                    No Latest Updates Found.
                                </div>      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<!-- <script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript">
     
     $(document).ready(function() {
        $('#reportTable1').DataTable().destroy();
        $('#reportTable1 tbody').empty();
        getAllNotices();
        $("#display").hide();

        $(function () {
            //debugger;
            $('#from_date, #to_date').datepicker({
                format: 'dd-mm-yyyy',
                startDate: new Date(),
                autoclose:true

            });
        });

        $('#info-alert').hide();




    } );


        $("#button_id").click(function()
        {
            
            if($('#updated_for').val() == 0){
                alert("Please select Updated for.");
            }
            
           // debugger;
           var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
           
            $("#display").show();
            var updated_for = $('select[name=updated_for]').val();
            var from_date = $('input[name=from_date]').val();
            var to_date = $('input[name=to_date]').val();
            var dsc=$('#description').val();
            var usercode=$('#usercode').val();
            if(from_date!='' && to_date!='' && dsc!='') {
                $.ajax({
                    url: '<?=base_url();?>/MasterManagement/LatestUpdatesController/insert_Latest_updates',
                    data: {
                        updated_for: updated_for,
                        from_date: from_date,
                        to_date: to_date,
                        dsc: dsc,
                        usercode: usercode
                    },
                    cache: false,
                    dataType: 'json',
                    type: "POST",
                    headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE  
                    },
                    success: function (data) {
                      alert('Insert successful!');
                      location.reload();
                       // updateCSRFToken();
                        //$('#reportTable1').DataTable().destroy();
                        // $('#reportTable1 tbody').empty();
                        if(updateCSRFToken())
                        {
                            getAllNotices();
                        }
                    },
                    error: function (ts) {
                       /* $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });*/
                        //alert(ts.responseText)
                    }
                });
            }
            else
            {
                updateCSRFToken();
                alert("Blank Data can't be inserted !");
            }
        });

        function getAllNotices()
        {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: '<?=base_url();?>/MasterManagement/LatestUpdatesController/display_Latest_Updates',
                data: {},
                cache:  false,
                dataType: 'json',
                type: "POST",
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE  
                    },
                success: function(data){
                    updateCSRFToken();
                    if(data.length > 0)
                    {
                        $("#display").show();
                        $('#reportTable1 tbody').empty();
                        sno = 1;
                        $.each(data, function (index) {

                            $('#reportTable1 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].menu_name + "</td><td>" + data[index].title_en + "</td><td>" + data[index].f_date + "</td><td>" + data[index].t_date + "</td></tr>");
                            sno++;
                        });

                        $('#reportTable1').DataTable({
                            "bSort": true,
                            dom: 'Bfrtip',
                            "scrollX": true,
                            iDisplayLength: 8,

                            buttons: [
                                {
                                    extend: 'print',
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                }
                            ]
                        });
                    }
                    else
                    {
                        $("#display").hide();
                        $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });
                    }

                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    console.log(xhr);
                    if (xhr == 'undefined' || xhr == undefined) {
                        alert('undefined');
                    } else {
                        alert('object is there');
                    }
                    alert(status);
                    alert(error);
                }
                //error: function(ts) { alert(ts.responseText) }
            });
        }





         
</script>
