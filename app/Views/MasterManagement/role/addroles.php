<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }

    .hide {
    display: none !important;
}
ul ul, ol ul, ul ol, ol ol {
    margin-bottom: 0 !important;
}
.input-group-addon, .input-group-btn, .input-group .form-control {
    display: table-cell;
}
.input-group-addon, .input-group-btn {
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;
}
.input-group-addon {
    padding: 10px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center  !important;
    background-color: #eee !important;
    border: 1px solid #ccc  !important;;
    border-radius: 4px;
    margin: 5px 0px 0px 0px !important;
}
.input-group-addon:first-child {
    border-right: 0;
}
</style>
<link href="<?php echo base_url(); ?>/user_management/style.css" rel="stylesheet">
<?php 
      $request = \Config\Services::request();
		//if(!$dbo) include 'config.php'; 
		$action='Create';  
        $addNewBtn='';
		$roleMid=@(int)$request->getPost('menu_id'); 
		$rHeading=@htmlentities(trim($request->getPost('rHeading')));
		if($roleMid != 0) {
			$action='Update';
			$addNewBtn='<a href="'.site_url(uri_string()).'"><i class="fa fa-plus">&nbsp;</i>Add Role</a>';
		}
	?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"> <!-- Right Part -->
                            <div class="form-div">

                                <div class="d-block text-center">
                                    <!--<span class="btn btn-danger">Add Menus/ Child</span>-->

                                    <div class="alert alert-success hide" role="alert" id="msgDiv">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong></strong>
                                    </div>

                                    <div id="loginbox" style="margin-top:20px;" class="mainbox">

                                        <div class="panel panel-info" id="addroleDiv">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <?php echo $action ?> <span class="text-danger">[ Group of Role ]</span>
                                                    <span class="menusList">
                                                        <?php echo $addNewBtn; ?>
                                                        <a href="#" id="roleList"><i class="fa fa-list">&nbsp;</i>Alloted Role list</a>
                                                    </span>
                                                </div>
                                            </div>

                                            <div style="margin-top: 10px" class="panel-body">

                                                <div class="alert hide"></div>

                                                <form id="addNewrole" class="form-horizontal" autocomplete="off">
                                                <?= csrf_field() ?>
                                                    <input type="hidden" value="<?php echo $roleMid ?>" name="roleMid" id="roleMid" />

                                                    <div class="row no-margin">

                                                        <div class="col-md-12">
                                                            <div class="input-group row">
                                                                <span class="input-group-addon col-md-2">Role's Caption</span>
                                                                <input id="rcaption" name="rcaption" type="text" class="form-control col-md-10" value="<?php echo $rHeading ?>" placeholder="Enter role's caption" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mtop-12">

                                                            <div class="table-responsive text-left" style="box-shadow: 5px 5px 8px 0px #737373;padding: 0px 6px;margin-bottom: 20px;">
                                                                <table class="table table-striped text-left">
                                                                    <thead>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="text-left btn-block font-size-30 text-danger"><i class="fa fa-list"></i>&nbsp;Role List</div>
                                                                            </td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                                <?php $model->fetchSubMenus($data['roleMid']); ?>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row no-margin mtop-12">

                                                        <div class="col-md-4">
                                                            <div class="form-group no-margin">
                                                                <input type="submit" class="btn btn-success btn-block" value="<?php echo $action ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-offset-4 col-md-4">
                                                            <div class="form-group no-margin">
                                                                <input type="reset" class="btn btn-warning btn-block" value="Clear/ Reset">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                        <div class="panel panel-danger hide" id="roleListDiv">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    Create <span class="text-success">[ Role ]</span>
                                                    <span class="menusList">
                                                        <a href="#" id="addrole"><i class="fa fa-plus">&nbsp;</i>Add Role</a>
                                                    </span>
                                                </div>
                                            </div>

                                            <div style="margin-top: 10px" class="panel-body">

                                                <div class="table-responsive" style="box-shadow: 5px 5px 8px 0px #737373;padding: 0px 6px;margin-bottom: 20px;">

                                                    <div class="text-left btn-block font-size-30 text-danger"><i class="fa fa-list"></i>&nbsp;Role List</div>

                                                    <table id="roleTable" class="table table-striped table-bordered text-left" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th><b>#</b></th>
                                                                <th><b>Role Name</b></th>
                                                                <th><b>Action</b></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $count = 1;
                                                           // $query = "select id,role_desc from role_master where display='Y' order by id;";
                                                           // $rs = $dbo->prepare($query);
                                                            //$rs->execute();

                                                            foreach ($rolelist as $rows) {
                                                                echo '<tr>
														<td>' . $count . '</td>								
														<td>' . $rows['role_desc'] . '</td>								
														<td>
															<a class="fa fa-trash text-danger" data-id="' . $rows['id'] . '" id="roleDelete" href="javascript: void(0);" title="Click to delete this role"></a>&nbsp;&nbsp;
															<form action="' . site_url(uri_string()) . '" method="post" id="FormRoleEdit" style="display: inline;">
																'. csrf_field().'
                                                                <input type="hidden" value="' . $rows['id'] . '" name="menu_id">
																<input type="hidden" value="' . $rows['role_desc'] . '" name="rHeading">
																<div class="fa fa-edit" style="position: relative;"></div>
																<input type="submit" value="Edit" style="color:transparent;border: 0px !important;background: transparent !important;margin-left: -22px;position: relative;z-index: 9; outline:0;">
															</form>
														</td>
													</tr>';
                                                                $count++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>/user_management/selfjs.js"></script>
<script>
    $("#roleTable").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    /*  $('#roleList, #addrole').click(function(e) {
          e.preventDefault();
          var activeid;
          activeid = $(this).attr('id');

          if (activeid == 'roleList') {
              $('#addroleDiv').addClass('hide');
              $('#roleListDiv').removeClass('hide');
          } else if (activeid == 'addrole') {
              $('#roleListDiv').addClass('hide');
              $('#addroleDiv').removeClass('hide');
          }
      });

      $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
      $('.tree li.parent_li > span').on('click', function(e) {
          var children = $(this).parent('li.parent_li').find(' > ul > li');
          if (children.is(":visible")) {
              children.hide('fast');
              $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus').removeClass('fa-minus');
              $(this).attr('title', 'Collapse this branch').prev("#menuIds").removeAttr('disabled');
          } else {
              children.show('fast');
              $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus').removeClass('fa-plus');
              $(this).attr('title', 'Collapse this branch').prev("#menuIds").attr('disabled', true).prop('checked', false);
          }
          e.stopPropagation();
      });

      function check_uncheck_checkbox(isChecked) {
          if (isChecked) {
              $('input[name="menus"]').each(function() {
                  this.checked = false;
                  $(this).attr('disabled', true);
              });
              $('.all-menus-box').removeAttr('disabled').prop('checked', true);
          } else {
              $('input[name="menus"]').each(function() {
                  $(this).removeAttr('disabled');
              });
          }
      }

      function moveMenu(chk) {
          if (chk == true) {
              $('#smlv1').removeClass('hide').removeAttr('disabled');
              $('input[type="submit"]').val('Move to another level').removeClass('btn-success').addClass('btn-danger');
          } else {
              $('#smlv1').val("").addClass('hide').attr('disabled', true);
              $('.smlv2, .smlv3, .smlv4, .smlv5, .smlv6').addClass('hide', true).find('select').removeAttr('required');
              $('input[type="submit"]').val('Update').removeClass('btn-danger').addClass('btn-success');
          }
      }

      $(document).on("click", "#btn1", function() {
          var CSRF_TOKEN = 'CSRF_TOKEN';
          var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
          var selected_smenus = "",
              data_form, action = 'createRole',
              submitBtnType = '',
              roleMid = '';
          submitBtnType = $("input[type='submit']").val(), roleMid = $('#roleMid').val();
          data_form = new FormData(this);
          if (submitBtnType == 'Update') {
              action = 'updateRole';
              data_form.append('roleMid', roleMid);
          }
          data_form.append('action', action);
          $.each($("input[name='menuIds']:checked"), function() {
              selected_smenus += $(this).val() + ',';
          });
          data_form.append('menuIds', selected_smenus);
          if (selected_smenus) {
              $.ajax({
                  url: "<?php //echo base_url('MasterManagement/RolesController/roleparameter'); 
                        ?>",
                  method: 'POST',
                  beforeSend: function() {
                      $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php //echo base_url('images/load.gif'); 
                                                                                                            ?>'></div>");
                  },
                  data: {
                      data_form,
                      CSRF_TOKEN: CSRF_TOKEN_VALUE
                  },
                  cache: false,
                  processData: false,
                  contentType: false,
                  dataType: 'json',
                  success: function(response) {
                      updateCSRFToken();
                      if (resp.data == 'success') {
                          msg = 'Role created successfully';
                          if (action == 'updateRole') {
                              msg = 'Role updated successfully';
                          }
                          alert(msg);
                          window.location.reload(true);
                      } else if (resp.data == '0') {
                          alert(resp.error);
                      }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      updateCSRFToken();
                      alert("Error: " + jqXHR.status + " " + errorThrown);
                  }
              });
          } else {
              alert('Please choose atleast one value.');
          }
      });

      function save_verification(dno) {

          var r = confirm("Are you Verfied this case");
          if (r == true) {
              if ($("#rremark_" + dno).val() == 'R' && $("#reject_remark_" + dno).val() == "") {
                  alert("Please Entry Valid Rejection Reason");
                  return false;
              }
              var rremark = $("#rremark_" + dno).val();
              var rejection_remark = $("#reject_remark_" + dno).val();
              var cl_date = $("#" + dno).data('cl_date');
              var dataString = "dno=" + dno + "&rremark=" + rremark + "&rejection_remark=" + rejection_remark + "&cl_date=" + cl_date;
              var CSRF_TOKEN = 'CSRF_TOKEN';
              var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
              $.ajax({
                  type: "POST",
                  url: "<?php //echo base_url('ManagementReports/DA/DA/response_case_remarks_verification'); 
                        ?>",
                  data: dataString + '&' + CSRF_TOKEN + '=' + CSRF_TOKEN_VALUE,
                  cache: false,
                  success: function(data) {
                      // alert(data);
                      updateCSRFToken();
                      if (data == 1) {
                          var r = "#" + dno;
                          var row = "<tr><td colspan='7' style='text-align:center;color:red;'>DN : " + dno + " Verified Successfully</td></tr>";
                          $(r).replaceWith(row);
                      } else {
                          alert("Not Verified.");
                      }
                  }
              }).fail(function() {
                  updateCSRFToken();
                  alert("ERROR, Please Contact Server Room");
              });
          } else {

              txt = "You pressed Cancel!";
          }

      }  */
</script>