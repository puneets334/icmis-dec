<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
  a:hover{
            color: red;
        }
        a:visited{
            color:green;
        }
        #reportTable1_filter{
            padding-right: 84%
        }
       

        .dt-buttons{
            display: ruby;
            position: fixed;
            margin-left: 66%;
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
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
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
                                    <form method="POST" action="<?=base_url();?>/MasterManagement/CaseBlockLooseDoc/Menu_List" class="form-horizontal" id="push-form">
                                    <?= csrf_field() ?>
                                        <div class="box-body">
                                            <div class="form-group">
                                            <label for="reportType" class="col-sm-2 col-md-8 col-lg-12 " style="text-align:center;"><h2>SELECT USER FOR MENU PRIVILEGE DETAILS</h2> </label>
                                            </div>

                                            <div class="row">
                                                <div class="col-2 mt-2">
                                                <label class="radio-inline">SELECT USER :</label>
                                                </div>
                                                <div class="col-4">
                                                <select class="select2" id="sect" name="emp" onchange="document.getElementById('target').value=this.value;">
                                                        <option value="">Enter User Name or ID</option>
                                                        <?php foreach ($user_code as $result): ?>
                                                            <option value="<?= $result['usercode']; ?>" <?= (isset($target) && $target == $result['usercode']) ? 'selected' : ''; ?>>
                                                                <?= $result['empid'] . ', ' . $result['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                    <input type="hidden" id="target" name="target"
                                                      value="<?php echo isset($target) ? $target : (isset($param[0]) ? $param[0] : ''); ?>" />
                                                      <div class="col-1">
                                                      </div>

                                                <div class="col-2">
                                                <button type="submit" onclick="return check();" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                            </div>

                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    if (is_array($menu_list) && !empty($menu_list))
                                    {

                                        ?>
                                    <hr>
                                    <div id="printable" class="box box-success" style="box-sizing: border-box";>
                                        <div class="table-responsive">
                                        <table id="reportTable1"class="table table-striped custom-table">
                                            <h3 style="text-align: center;"> Menu Privilege details of : <strong><?=$menu_list[0]['name'];?></strong> (<strong><?=$menu_list[0]['empid'];?>)</strong></h3>
                                            <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Menu</th>
                                                <th>Sub Menu</th>
                                                <th>Sub Sub Menu</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            //var_dump($menu_list);
                                            $s_no=1;
                                            foreach ($menu_list as $result)
                                            {
                                                ?>
                                            <tr>
                                            <td><?php echo $s_no;?></td>
                                            <td><a href="<?=base_url();?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?mn_me_per=<?php echo $result['main_menu_id'];?>&emp_rem=<?php if(isset($_POST['target'])) { echo $_POST['target']; } else { echo $param[0]; } ?>" onClick="return confirm('Do you want to remove this Menu (including all the sub-menus)')"><?php echo $result['menu_nm'];?></td>
                                            <td><a href="<?=base_url();?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?sub_me_per=<?php echo $result['sub_me_per'];?>&emp_rem=<?php if(isset($_POST['target'])) { echo $_POST['target']; } else { echo $param[0]; } ?>" onClick="return confirm('Do you want to remove this Sub-Menu including all the Sub-Sub-menus')"><?php echo $result['sub_mn_nm'];?></td>
                                            <td><a href="<?=base_url();?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?sub_sub_menu=<?php echo $result['su_su_menu_id'];?>&emp_rem=<?php if(isset($_POST['target'])) { echo $_POST['target']; } else { echo $param[0]; } ?>" onClick="return confirm('Do you want to remove this Sub-Sub-Menu')"><?php echo $result['sub_sub_mn_nm'];?></td>
                                            </tr>
                                                <?php
                                                $s_no++;
                                                //echo str_replace('&', 'and', $result['state']);
                                                }   //for each
                                                ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <?php
                                    }   //for each
                                    ?>
                                    </div>
                                    <!-- Report Div Start -->
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
<script src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/select2/select2.full.min.js"></script>


<script>
     


    $(document).ready(function() {
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            });
        });


        var t= $('#reportTable1').DataTable( {
            /* dom: 'Bfrtip',
             buttons: [
                 'excelHtml5',
                 'pdfHtml5'
             ]*/

            "bProcessing"   :   true,
            dom: 'Bfrtip',
            margin: [ 0, 0, 100, 5 ],
            pageLength:500,
            buttons: [
                'excelHtml5',
                {
                    extend: 'pdfHtml5',
                    pageSize: 'A4',
                    title: "Menu Privileges",
                    filename: "Menu Privileges of the User  <?php echo date('d-m-Y'); ?>",
                    customize: function ( doc ) {
                        doc.content.splice( 0, 0, {
                            margin: [ 0, 0, 0, 5 ],
                            alignment: 'center',
                            image: 'data:image/png;base64,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'
                        });
                        doc.watermark = {text: 'SUPREME COURT OF INDIA', color: 'blue', opacity: 0.05, margin: [ 0, 0, 0, 5 ] }
                        doc['header']=(function() {
                            return {
                                columns: [
                                    {
                                        alignment: 'center',
                                        fontSize: 15,
                                        text: "MENU PRIVILEGES REPORT"
                                    }
                                ],
                                "columnDefs": [ {
                                    "searchable": false,
                                    "orderable": false,
                                    "targets": 0
                                } ],
                                "order": [[ 1, 'asc' ]],
                                margin: 20
                            }
                        });
                    }

                }

            ]

        });
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
                t.cell(cell).invalidate('dom');
            } );
        } ).draw();
    });
</script>

<script type="text/javascript">

    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        //document.getElementById('header').style.display = 'none';
        // document.getElementById('footer').style.display = 'none';
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function check() {

        var menuList = $('#sect').val();

        if(menuList == ""){
            alert("Please Select User.");
            $('#sect').focus();
            return false;
        }
        else
        {
            return true;
        }
    }
</script>
