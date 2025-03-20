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
                                    <h3 class="card-title">PIL(E) >> Pil Entry</h3>
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
                        echo form_open(base_url('PIL/PilController/addToPilGroupShow'), $attribute);
//                        echo "dfghdf";
//                        echo "TTT".$ecPilGroupId;
//                        var_dump($pilGroup[0]['id']);
//                        echo "<pre>";
//                        print_r($casesInPilGroup);
                        ?>


                        <div class="row col-md-12 ">
                            <label for="pilGroup" class="control-label">Select Group</label>
                            <div class="col-md-3">
                                <select  class="form-control" name="ecPilGroupId" id="ecPilGroupId" onchange="showGroupCases()">
                                    <option value="0">Select</option>
                                    <?php
                                    if (is_array($pilGroup)) {
                                    if(count($pilGroup) > 0) {

                                        //echo "ecid".$ecPilGroupId.">>".$pilGroup['id'];
                                          foreach ($pilGroup as $pilGrp){

                                            if ($ecPilGroupId == $pilGrp['id']) {
                                                echo '<option value="' . $pilGrp['id'] . '" selected="selected">' . $pilGrp['group_file_number'] . '</option>';
                                            } else {
                                                echo '<option value="' . $pilGrp['id'] . '">' . $pilGrp['group_file_number'] . '</option>';
                                            }
                                        }
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3" id="divDiaryNo">
                                <input type="text" class="form-control" placeholder="Inward No"  id="diaryNo" name="diaryNo">
                            </div>

                            <div class="col-md-3">
                                <select class="form-control" id="diaryYear" name="diaryYear">
                                    <?php
                                    if(!empty($searchedYear)) {
                                        for ($year = date('Y'); $year >= 1950; $year--)
                                            if ($searchedYear == $year) {
                                                echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                                            } else {
                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                            }
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="input-group-btn" style="text-align:center ">
                                <button type="button" name="search" id="search-btn" class="btn btn-primary" onclick="checkDiarynumberToAddInGroup();">Search </button>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-6 pull-right">
                                <span style="color: red;margin-left: 65%;"><?=$msg?></span>
                            </div>
                        </div>

                        <?php form_close(); ?>


                        <br><br>


                        <br>
                        <br>


                        <?php
//                        $casesInPilGroup='';
                        if(!empty($casesInPilGroup))
                        {
//                            echo "<pre>";
//                            print_r($casesInPilGroup);
                        ?>
                        <div class="row">
                            <div class="pull-right dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" style="margin-left:850%"  data-toggle="dropdown">Dropdown Report
                                </button>
                                <?php
                                //                                echo base_url()."PIL/PilController/downloadFormatReport/1/".$ecPilGroupId."/".$_SESSION['login']['usercode'];


                                ?>
                                <ul class="dropdown-menu" style="width: max-content;">
                                    <li ><a href=" <?= base_url() ?>/PIL/PilController/downloadFormatReport?id=1&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Not To SCI</a></li>
                                    <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=2&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Vernacular</a></li>
                                    <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=3&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Email Unsigned</a></li>
                                    <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=4&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Unsigned</a></li>
                                    <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=5&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Anonymous letter-petitions</a></li>

                                </ul>
                            </div>

                        </div>


                        <div id="tabledata" >
                            <h4 align="center">PILs in Group</h4>
                            <br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Inward No/Year</th>
                                    <th>Received From</th>
                                    <th>Received On</th>
                                    <th>Petition Date</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody id="data_set">
                                <tbody>
                                <?php
                                $i = 0;
                                $s=1;
                                $rowserial = "odd";
                                foreach ($casesInPilGroup as $result){
                                $i++;
                                if ($i % 2 == 0)
                                    $rowserial = "even";
                                else {
                                    $rowserial = "odd";
                                }
                                ?>
                                <tr role="row" class="<?= $rowserial ?>">
                                    <td><?= $s++;?></td>
                                    <td><?=$result['pil_diary_number']?></td>
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
                                        <td><?= !empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>

                                       <td><?=!empty($result['petition_date'])?date("d-m-Y", strtotime($result['petition_date'])):null?></td>

                                       <td><a href="<?=base_url()?>/PIL/PilController/removeCaseFromPilGroup/<?=$result['id']?>/<?=$ecPilGroupId?>/<?=$_SESSION['login']['usercode']?>" onclick="if (confirm('Do you really want to remove this PIL from PIL Group?')){return true;}else{event.stopPropagation(); event.preventDefault();};">
                                               <i class="fas fa-trash" aria-hidden="true" style="color: red;"></i></td>
                                </tr>
                                    <?php
                                    }
                                    }
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
        function updateCSRFToken() {
            $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }

        function showGroupCases(){
            document.getElementById("frmAddToPilGroup").submit();
        }

        //function formSubmit()
        //{
        //    // alert("FFFF");
        //    var CSRF_TOKEN = 'CSRF_TOKEN';
        //    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        //
        //    var diaryNo=$("#diaryNo").val();
        //    var diaryYear=$("#diaryYear").val();
        //    var ecPilGroupId=$("#ecPilGroupId").val();
        //    // alert("eid="+ecPilGroupId+"dno="+diaryNo+"dy="+diaryYear);
        //    var data = new Array();
        //    if((diaryNo !== null && diaryNo == undefined) && (diaryYear !==null && diaryYear == undefined))
        //    {
        //        // alert("TTTTTtt");
        //        data= {
        //            'dno': diaryNo,
        //            'dy': diaryYear,
        //            'ecid': ecPilGroupId
        //        };
        //    }else{
        //        // alert('YYYYYYY');
        //        data= {
        //            'ecid':ecPilGroupId,
        //            'dy': diaryYear,
        //        };
        //    }
        //
        //    $.ajax({
        //        type:"POST",
        //        dataType:'json',
        //        data: {
        //            CSRF_TOKEN: CSRF_TOKEN_VALUE,
        //            'dt':data
        //
        //        },
        //        url: "<?php //echo base_url('PIL/PilController/addToPilGroup'); ?>//",
        //        success: function(data) {
        //            //    alert(data);
        //            // console.log(data.casesInPilGroups);
        //            var dataArray=data.casesInPilGroups;
        //            // alert(typeof (dataArray));
        //            var html="";
        //            if(dataArray !== undefined && dataArray !== null)
        //            {
        //                var i=1;
        //                dataArray.forEach(dt=>{
        //
        //                    // console.log(new Date(dt['received_on']).toLocaleDateString('en-GB').replace(/\//g,'-'));
        //
        //                    html +='<tr>'
        //                    html +='<td>'+ i++ +'</td>'
        //                    html +='<td>'+ dt['pil_diary_number'] +'</td>'
        //                    html +='<td>'+ dt['received_from']
        //                        if(dt['address'] !== null)
        //                        {
        //                          + "\n Address: "+ dt['address']
        //                        }
        //                        if(dt['email']!== null)
        //                        {
        //                            + " \n Email: "+ dt['email']
        //                        }
        //                        if(dt['mobile']!== null)
        //                        {
        //                            + "\n Mobile: "+dt['mobile']
        //                        } + '</td>'
        //                    html +='<td>'+ (dt['received_on']?(new Date(dt['received_on']).toLocaleDateString('en-GB').replace(/\//g,'-')):null) +'</td>'
        //                    html +='<td>'+ (dt['petition_date']?(new Date(dt['petition_date']).toLocaleDateString('en-GB').replace(/\//g,'-')):null) +'</td>'
        //                    html +='<td><button type="button" class="btn btn-danger btn-sm" name="pil_remove" onclick="pilRemove('+ dt['id']+')"><i class="fas fa-trash" aria-hidden="true"></i></button> </td>'
        //                    html +='</tr>';
        //
        //                })
        //                console.log(html);
        //                $('#data_set').append(html);
        //
        //                $('#tabledata').css("display","block");
        //                // window.location.reload();
        //            }else{
        //                alert("No record Found");
        //            }
        //
        //            //console.log("SSSS"+data);
        //            //   $('.message_display').append(data);
        //            // $('#sub_category').html(data);
        //            updateCSRFToken();
        //        },
        //        error: function(data) {
        //            alert(data);
        //            updateCSRFToken();
        //        }
        //    });
        //
        //
        //}

        function checkDiarynumberToAddInGroup()
        {
            var diaryNo=document.getElementById("diaryNo").value;
            var diaryYear=document.getElementById("diaryYear").value;
            var ecPilGroupId=document.getElementById("ecPilGroupId").value;
            if(ecPilGroupId==0){
                alert("Please Select Group to add.");
                document.getElementById("ecPilGroupId").focus();
                return false;
            }
            if(diaryNo==""){
                alert("Please Enter Inward Number");
                document.getElementById("diaryNo").focus();
                return false;
            }
            if(diaryYear==""){
                alert("Please Enter Inward Year");
                document.getElementById("diaryYear").focus();
                return false;
            }
            //alert("Test");
            document.getElementById("frmAddToPilGroup").submit();
        }



    </script>

    <script>
        function pilRemove(id)
        {
            alert(id);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var ecPilGroupId=$("#ecPilGroupId").val();

            if (confirm('Do you really want to remove this PIL from PIL Group?'))
            {
                $.ajax({
                    type:"POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        'id':id,
                        'pilgpid':ecPilGroupId,
                    },
                    url: "<?php echo base_url('/PIL/PilController/removeCaseFromPilGroup/'); ?>",
                    success: function(data) {
                        // alert(data);
                        if(data === 1)
                        {
                            alert("PIL removed from this PIL Group."+ecPilGroupId);
                            window.location.reload();
                        }else{
                            alert("There is some problem while removing PIL from this PIL Group");
                        }
                        updateCSRFToken();
                    },
                    error: function(data) {
                        alert(data);
                        updateCSRFToken();
                    }
                });
            }else{

            }

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
            // $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //     "responsive": true,
            // });
        });
    </script>

    <script>
        function showGroupCases(){
            document.getElementById("frmAddToPilGroup").submit();
        }
    </script>

