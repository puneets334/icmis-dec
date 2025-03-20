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
                                    <h3 class="card-title">PIL(E) >> Pil Report</h3>
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
                        $attribute = array('class' => 'form-horizontal', 'name' => 'frmAddToPilGroup', 'id' => 'frmAddToPilGroup', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('PIL/PilController/getPilDetailByDiaryNumber'), $attribute);
                        //                        echo "dfghdf";

                        //                        var_dump($pilGroup[0]['id']);
                        //                        echo "<pre>";
                        //                        print_r($casesInPilGroup);
                        ?>

<br>
                        <div class="row col-md-12 ">

                            <div class="col-md-3">
                               <button type="button" class="btn btn-primary" onclick="addEditPilDetail(0, '<?=base_url()?>');"><i class="fa fa-plus"></i> Add New PIL</button>
                            </div>
                            <div class="col-md-3" id="divDiaryNo">
                                <label style="display: flex;margin-left: -13%;"><h5>Search by PIL Inward Number</h5></label>
                                <input type="text" class="form-control" placeholder="Inward No" required id="diaryNo" name="diaryNo" style="margin-left: 54%;margin-top: -11%;">

                            </div>

                            <div class="col-md-3">
                                <select class="form-control" required id="diaryYear" name="diaryYear" style="margin-left: 53%;">
                                    <?php
                                    for($year=date('Y'); $year>=1950; $year--)
                                        echo '<option value="'.$year.'">'.$year.'</option>';
                                    ?>

                                </select>
                            </div>

                            <div class="input-group-btn" style="text-align:center ">
                                <button type="submit" name="search" id="search-btn" class="btn btn-primary"  style="margin-left: 278%;">Search </button>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 pull-right">
                                    <span style="color: red"><?=$msg?></span>
                                </div>
                            </div>

                        </div>

                        <?php form_close(); ?>


                        <br><br>


                        <br>
                        <br>



                        <div id="tabledata" >
                            <h4 align="center">PIL Received</h4>
                            <br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Inward No/Year</th>
                                    <th>Received From</th>
                                    <th>Received On</th>
                                    <th>Petition Date</th>
                                    <th>Summary of Request</th>
                                </tr>
                                </thead>
                                <tbody id="data_set">
                                <tbody>
                                <?php
                                $i = 0;
                                $s=1;
                                $rowserial = "odd";
                                foreach ($pilData as $result){
                                    $i++;
                                    if ($i % 2 == 0)
                                        $rowserial = "even";
                                    else {
                                        $rowserial = "odd";
                                    }
                                    ?>
                                    <tr role="row" class="<?= $rowserial ?>">
                                        <td><?= $s++; ?></td>
                                        <td><button type="button" class="btn btn-primary" onclick="addEditPilDetail(<?=$result['id']?>, '<?=base_url()?>')"><?=$result['pil_diary_number']?></button></td>
                                        <td><?=$result['received_from']?>
                                            <?php
                                            if(!empty($result['address'])){
                                                echo "<br/> Address: ".$result['address'];
                                            }
                                            if(!empty($result['email'])){
                                                echo "<br/> Email: ".$result['email'];
                                            }
                                            if(!empty($result['mobile'])){
                                                echo "<br/> Mobile: ".$result['mobile'];
                                            }
                                            ?>
                                        </td>

                                        <td><?=!empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>
                                        <td><?=(!empty($result['petition_date']) &&  $result['petition_date']!=null && $result['petition_date']!='30-11--0001')?date("d-m-Y", strtotime($result['petition_date'])):null?></td>
                                        <td><?=$result['request_summary']?></td>
                                    </tr>

                                <?php }
                                ?>


                                </tbody>
                            </table>
                        </div>



                    </div><br><br>


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
 function addEditPilDetail(ecPilGroupId, basePath){
            // alert(basePath+"/PIL/PilController/editPilGroupData/"+ ecPilGroupId);

            window.location.href = basePath+"/PIL/PilController/editPilData/"+ ecPilGroupId ;
        }
    </script>
