<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
 <style>
.radios {
    display: flex;
  
    padding-left: 38px;

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
                        <h3 class="card-title">Master Management >> Master</h3>
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
                                      
                                     <div>
                                        <?php 
                                         
                                        ?>
                                        <div>
                                            <section>
                                                <form role="form" id="judge_category" method="POST" action = "<?=base_url();?>/MasterManagement/MasterController/judgeCategoryUpdate">
                                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                                    <div class="col-md-12">
                                                        <div class="well">
                                                            <div class="row">
                                                                <input type="hidden" name="usercode" id="usercode" value="<?= isset($_SESSION['dcmis_user_idd']) ? $_SESSION['dcmis_user_idd'] : @$usercodeses_get; ?>"/>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="judge1">Judge</label>
                                                                    <select class="form-control" id="judge" name="judge" placeholder="judge" style="margin: 19px;" required>
                                                                    <option value="">Select Judge</option>
                                                                    <?php
                                                                    $selected_judge_code = isset($judge_selected_code) ? $judge_selected_code : '';
                                                                    foreach ($judge as $j1) {
                                                                        $selected = ($selected_judge_code == $j1['jcode']) ? 'selected="selected"' : '';
                                                                        echo '<option value="'.$j1['jcode'].'" '.$selected.'>'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>

                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <label for="m_f">Misc/Regular</label><br/>
                                                                    <div class="radios">
                                                                    Miscelleneous
                                                                    <?php
                                                                    $selected_mf_code = isset($mf_code) ? $mf_code : '';
                                                                     
                                                                     ?>
                                                                    <input type="radio" name="mf" id="mf" class="rd_active" value="M" <?= ($selected_mf_code === 'M' || $selected_mf_code === '') ? 'checked' : '' ?>> Regular 
                                                                    <input type="radio" name="mf" id="mf" class="rd_active" value="F"  <?= $selected_mf_code === 'F' ? 'checked' : '' ?> > 
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                                <br/>
                                                            <div class="row" style="justify-content: center;">
                                                                <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3"><button type="submit" id="btn-update" class="btn bg-olive btn-flat pull-right" ><i class="fa fa-save"></i> Submit</button></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>
                                            </section>
                                            <?php
                                    if (!empty($app_name)) {
                                        if (isset($judge_details) && !empty($judge_details)) {
                           
                                            $jname = end($judge_details)['jname']; 
                                            switch ($matters) {
                                                case 'M':
                                                    $mattersList = "Miscellaneous Matters";
                                                    break;
                                                case 'F':
                                                    $mattersList = "Regular Matters";
                                                    break;
                                                case 'B':
                                                    $mattersList = "All Matters";
                                                    break;
                                                default:
                                                    $mattersList = "Unknown Matters";
                                            }
                                            ?>
                                            <table id="reportTable11111" class="table table-striped table-hover">
                                                <thead>
                                                    <tr align="center">
                                                        <h1 align="center" style="margin-top: 19px;"><?php echo htmlspecialchars($jname); ?></h1>
                                                        <h3 align="center"><?php echo '(' . htmlspecialchars($mattersList) . ')'; ?></h3>
                                                    </tr>
                                                    <tr>
                                                        <th><h3>#</h3></th>
                                                        <th><h3>Subject Category</h3></th>
                                                        <th><h3>Priority</h3></th>
                                                        <th><h3>To Date</h3></th>
                                                        <th><h3>Action</h3></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i=0;
                                                    foreach ($judge_details as $ijt => $result) {
                                                        $i++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td hidden id="td_<?php echo $i; ?>"><?php echo htmlspecialchars($result['id']); ?></td>
                                                            <td width="50%"><?php echo htmlspecialchars($result['catg']); ?></td>
                                                            <td>
                                                                <input type="number" class="priority_jcu_cls" value="<?php echo htmlspecialchars($result['priority']); ?>" name="priority_<?php echo $i; ?>" id="priority_<?php echo $i; ?>"> 
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control datepick" value="<?php 

                                                                    if (!empty($result['to_dt']) && $result['to_dt'] != '0000-00-00') {
                                                                        echo date('d-m-Y', strtotime($result['to_dt'])) ?? $result['to_dt'];
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                ?>" name="toDate_<?php echo $i; ?>" id="toDate_<?php echo $i; ?>"> 
                                                            </td>
                                                            <td>
                                                                <button class="btn bg-olive btn-flat pull-right" onclick="update(<?php echo $i; ?>);"><i class="fa fa-save"></i> Update</button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <input type="hidden" name="rows" id="rows" value="<?php echo count($judge_details); ?>">
                                            <?php
                                        } else {
                                            echo "<p style='margin-top: 37px; color: red;'>Data not available!</p>";
                                        }
                                    } 
                                    ?>


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
    $(document).on('input', '.priority_jcu_cls', function () {
            const maxLength = 7;
            let value = $(this).val();

            // If value exceeds 6 digits, trim it
            if (value.length > maxLength) {
                $(this).val(value.slice(0, maxLength));
            }
        });
     
 
//      $(document).ready(function() {
//         /*if($("input[name=rd_active]:checked").val()=='I')
//             $('#dt_show').show();
//         else
//             $('#dt_show').hide();*/

//         $(function () {
//             $('.datepick').datepicker({
//                 format: 'dd-mm-yyyy',
//                 autoclose: true

//             });
//         });

//         /*$("input[name='rd_active']").click(function() {

//             var searchValue = $(this).val();

//             if(searchValue=='I')
//                 $('#dt_show').show();
//             else
//                 $('#dt_show').hide();
//         });
// */

//     });

//     function isEmpty(obj) {
//         if (obj == null) return true;
//         if (obj.length > 0)    return false;
//         if (obj.length === 0)  return true;
//         if (typeof obj !== "object") return true;

//         // Otherwise, does it have any properties of its own?
//         // Note that this doesn't handle
//         // toString and valueOf enumeration bugs in IE < 9
//         for (var key in obj) {
//             if (hasOwnProperty.call(obj, key)) return false;
//         }

//         return true;
//     }

//     function validation()
//     {
//         var judge_from= $("#judge").prop('selectedIndex');
//         var judge_to= $("#judge1").prop('selectedIndex');
//        // var val_active = $("input[name=rd_active]:checked").val();
//         if(judge_from==judge_to)
//         {
//             alert("Both Judges cannot be same.");
//             return false;
//         }
//         /*else if (val_active == '' || typeof val_active === 'undefined') {
//             alert("Please select Category");
//             $("input[name=rd_active]").focus();
//             return false;
//         }*/
//         else if (judge_from == '') {
//             alert("Please select transfer from Judge.");
//             $("#judge").focus();
//             return false;
//         }
//         else if (judge_to == '') {
//             alert("Please select transfer to Judge.");
//             $("#judge1").focus();
//             return false;
//         }
//         else
//             return true;
//     }








$(document).ready(function() {
        // $('#reportTable1').DataTable().destroy();
        //$('#reportTable1 tbody').empty();

        //getAllNotices();
        //$("#display").hide();
        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true

            });
        });
    });

    function isEmpty(obj) {
        if (obj == null) return true;
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (hasOwnProperty.call(obj, key)) return false;
        }

        return true;
    }




    /* function update($id)
    {
       // debugger;
      
        if (!isEmpty(priority) && !isEmpty(idDb) && !isEmpty(usercode)) {
            $.post("<?php //echo base_url();?>/MasterManagement/MasterController/update_judge_category", {
                priority: priority,
                id: idDb,
                toDate: toDate,
                usercode: usercode,
                mf:mf
            }, function (result) {
                if (!alert(result)) {
                    location.reload();
                }
            });
        }

    }*/


    async function update($id) {
        await updateCSRFTokenSync();

        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var priority=$('#priority_'+$id).val();
        var idDb=$('#td_' + $id).text();
        var toDate=$('#toDate_'+$id).val();
        var usercode=$('#usercode').val();
        var rows=$('#rows').val();
        var mf='<?php echo $matters;?>';
        
         if (!isEmpty(priority) && !isEmpty(idDb) && !isEmpty(usercode)) {
            $.ajax({
                url: "<?=base_url();?>/MasterManagement/MasterController/update_judge_category",
                type: "POST",
                data: {
                    priority: priority,
                    id: idDb,
                    toDate: toDate,
                    usercode: usercode,
                    mf:mf
                },
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
                success: function (result) {
                    if (!alert(result)) {
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    //updateCSRFToken()
                    console.error("Error: " + error);
                    alert("An error occurred. Please try again.");
                }
            });
        }
        else{
            if(isEmpty(priority)){ $('#priority_' + $id).css({ // Use the dynamic ID selector
                'background-color': 'rgb(255 0 0 / 40%)',
                'color': 'darkgreen',
                'border': '2px solid blue',
                'padding': '8px',
                'font-weight': 'bold'
            }); }
             
            alert("Please enter required values.");
        }
    }
 
 

 
</script>
