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
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */
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
                                    <h3 class="card-title">PIL(E) >> Pil Group</h3>
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

                        <?= view('PIL/pilEntryHeading'); ?>

                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'frmGetPilDetail', 'id' => 'frmGetPilDetail', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('PIL/PilController/addToPilGroupShow'), $attribute);
                        //                        echo "dfghdf";

                        //                        var_dump($pilGroup[0]['id']);
                        //                        echo "<pre>";
                        //                        print_r($casesInPilGroup);
                        ?>

                        <br>
                        <div class="row col-md-12 ">

                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary" onclick="addEditPilGroupDetail(0, '<?=base_url()?>');"><i class="fa fa-plus"></i> Add New PIL Group</button>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 pull-right">
<!--                                    <span style="color: red">--><?//= $msg?><!--</span>-->
                                </div>
                            </div>

                        </div>

                        <?php form_close(); ?>

                        <br>   

                        <div id="tabledata" >

                            <br>
                            <table id="example1" class="table table-striped table-bordered custom-table">
                                <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Group File Number</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (is_array($pilGroup)) {
                                    if(count($pilGroup) > 0) {
                                 foreach ($pilGroup as $result){

                                 ?>
                                    <tr>
                                        <td width="10%"><button type="button" class="btn btn-primary" onclick="addEditPilGroupDetail(<?=$result['id']?>, '<?=base_url()?>')"><?=$result['id']?></button></td>
                                        <td><?=$result['group_file_number']?></td>
                                    </tr>

                                <?php
                                } }} 
                                ?>

                                </tbody>
                            </table>
                        </div>



                    </div>


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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,                 
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
        function addEditPilGroupDetail(ecPilGroupId, basePath){
            // alert(basePath+"/PIL/PilController/editPilGroupData/"+ ecPilGroupId);

            window.location.href = basePath+"/PIL/PilController/editPilGroupData/"+ ecPilGroupId ;
        }
    </script>