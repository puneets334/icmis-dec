<?= view('header') ;
//pr($usercode);die;
?>
 
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

        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
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
                                <h3 class="card-title">Master Management >> Transfer File >> Transfer Cases </h3>
                            </div>
                        </div>
                        <br><br>

                        <?php if (session()->getFlashdata('infomsg')): ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('success_msg')): ?>
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

                    <!-- Form Section -->
                     <br>
                    <div class="container-fluid">
                        <?php $ucode = session()->get('login')['usercode'] ?>
                        <!-- <h3 class="page-header">Dispatch To R&I</h3> -->
                        <form id="dispatchDakToRI" method="post" action="">
                            <?= csrf_field(); ?>
                            <div class="row" id="divSection" style="display: block">
                                <div class="row">
                                   


                                    <div class="form-group col-sm-2" >
                                    <label for="to">User Type</label>
                                    <select name="ddl_users" id="ddl_users" class="form-control">
                                        <?php
                                        if($usercode=='1' || $r_section=='30' ||  $r_usertype=='4' ||  $r_usertype=='9' ||  $r_usertype=='14')
                                        {
                                        ?>
                                        <option value="">Select</option>
                                        <?php
                                        }
                                        foreach ($fil_trap_users as $row) {
                                            ?>
                                        <option value="<?php echo $row['id'] ?>"><?php echo $row['type_name'] ?></option>
                                        <?php
                                        }
                                        if($usercode=='1' || $r_section=='30' ||  $r_usertype=='4' || $r_section=='20' ||  $r_usertype=='9' ||  $r_usertype=='14')
                                        {
                                        ?>
                                        <option value="9796">Scaning</option>
                                        <option value="107">IB-Extention</option>
                                        <?php }
                                        else   if($usercode=='9796')
                                        {
                                            ?>
                                        <option value="9796">Scaning</option>
                                        <?php
                                        }
                                        else if($r_user_type=='107')
                                        {
                                            ?>
                                        <option value="107">IB-Extention</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    </div>
                                    <div class="form-group col-sm-2" >
                                      <label for="to">User Name</label>
                                        <select name="ddl_users_nm" id="ddl_users_nm" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-2">
                                        <label for="from">From Date2</label>
                                        <input type="date" name="txt_frm_dt" id="txt_frm_dt"  class="form-control datepick" autocomplete="off" placeholder="From Date" value="<?= date('Y-m-d') ?>" /> 
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="to">To Date</label>
                                        <input type="date" name="txt_to_dt" id="txt_to_dt" class="form-control datepick" placeholder="To Date" autocomplete="off" value="<?= date('Y-m-d') ?>">
                                    </div>

                                    <div class="form-group col-sm-3 pull-right">
                             
                                    <input type="button" name="btn_submit" id="btn_submit" class="mt-4" value="Submit"/>
                                    </div>
                                    
                                </div> 
                                
                                
                                    <div id="dv_data" style="margin-top: 15px"></div>
                                        <div id="dv_load" style="text-align: left"></div>
                                    </div>
                            </div>
                        </form>

                        <div id="dv_data">

                        </div>

                       

                       
                    <br><br><br>
                </div>
            </div>
        </div>
    </div>
</section>
                         
<!-- /.section -->

<script src="<?= base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?= base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
      function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }

    
    /////////////from old
    $(document).ready(function() {
    $(document).on('change','#ddl_users',function(){
        var idd=$(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        document.getElementById('dv_data').innerHTML = "";
        document.getElementById('dv_load').innerHTML = "";
      
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('RI/DispatchController/getuser_for_transfer_case'); ?>",
            data:{idd: idd, CSRF_TOKEN:CSRF_TOKEN_VALUE},
            dataType: 'html', 
            success: function(data) {
                updateCSRFToken();
                $('#ddl_users_nm').html(data);
               
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    

    $(document).on('change','#ddl_users_nm',function() {
        document.getElementById('dv_data').innerHTML = "";
        document.getElementById('dv_load').innerHTML = "";
    });

    $(document).on('click', '#btn_submit', function() {
        get_records();

    });

    $(document).on('click','#btn_transfer',function(){
    var diary_no=[];
    var users=[];
    var sno=0;
    var u_sno=0;
    $('.cl_allot_case').each(function(){
        if($(this).is(':checked'))
            {
            diary_no[sno]=$(this).val();
            sno++;
            }
    });
    $('.cl_users').each(function(){
        if($(this).is(':checked'))
            {
            var idd=$(this).attr('id');
            var sp_idd=idd.split('cl_users_m');
            users[u_sno]=$(this).val()+','+$('#txt_tot_alt_case'+sp_idd[1]).val();
            u_sno++;
            }
    });
    //   alert(users);
    if(sno==0)
    {
        alert("Please select atleast one Diary No. to be transfered");
    }
    else if(u_sno==0)
        {
                alert("Please select atleast one User whom case to be transfered");
        }
    else
        {
    var ddl_users = $('#ddl_users').val();
        var txt_frm_dt = $('#txt_frm_dt').val();
        var txt_to_dt = $('#txt_to_dt').val();
        var ddl_users_nm=$('#ddl_users_nm').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
            url: "<?php echo base_url('RI/DispatchController/transfer_cases_user'); ?>",
            cache: false,
            async: true,
            beforeSend: function() {
                $('#dv_load').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: {diary_no: diary_no, users: users, txt_to_dt: txt_to_dt,ddl_users_nm:ddl_users_nm,txt_frm_dt:txt_frm_dt,ddl_users:ddl_users,CSRF_TOKEN:CSRF_TOKEN_VALUE},
            type: 'POST',
            success: function(data, status) {
                $('#dv_load').html(data);
                alert(data);
                get_records();
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
        }

    });

    $(document).on('click','.cl_users,.cl_allot_case',function(){

    var idd= this.id;
    //  alert(idd);
    var sp_idd=idd.split('cl_users_m');
    if($('#'+idd).is(':checked'))
    {

    var sno=0;
    var u_sno=0;
    $('.cl_allot_case').each(function(){
        if($(this).is(':checked'))
            {
            sno++;
            }
    });
    var users=[];
    $('.cl_users').each(function(){
        if($(this).is(':checked'))
            {
            var idd= $(this).attr('id');
            var sp_idd=idd.split('cl_users_m');
            users[u_sno]=$('#txt_tot_alt_case'+sp_idd[1]).attr('id');
            u_sno++;
            }
    });
   
        {
            var qunt=Math.floor(sno/u_sno);
        
            var rem_s=Math.floor(sno%u_sno);
  
            var sp_users=users.toString().split(',');
            var cnt_usr=0;
            var chk_tot=0;
            var cnt_qunt=1;
            for(var z=0;z<sp_users.length;z++)
                {
   
                    $('#'+sp_users[z]).val(qunt);
   
                }
                
                for(var y=1;y<=rem_s;)
                {
                    for(var zz=0;zz<sp_users.length;zz++)
                        {
                                $('#'+sp_users[zz]).val(parseInt($('#'+sp_users[zz]).val())+1);
                            
                                if(y==rem_s)
                                break;
                                y++;
                        }
                            if(y==rem_s)
                                break;
                }
                
        }
    }
    else 
        {
            $('#txt_tot_alt_case'+sp_idd[1]).val('');
            var sno=0;
    var u_sno=0;
    $('.cl_allot_case').each(function(){
        if($(this).is(':checked'))
            {
            sno++;
            }
    });
    var users=[];
    $('.cl_users').each(function(){
        if($(this).is(':checked'))
            {
            var idd= $(this).attr('id');
            var sp_idd=idd.split('cl_users_m');
            users[u_sno]=$('#txt_tot_alt_case'+sp_idd[1]).attr('id');
            u_sno++;
            }
    });
    //   if(u_sno==1)
    //       {
    //           $('#txt_tot_alt_case'+sp_idd[1]).val(sno);
    //       }
    //       else 
        {
            var qunt=Math.floor(sno/u_sno);
        
            var rem_s=Math.floor(sno%u_sno);
    //                 alert(rem_s);
            var sp_users=users.toString().split(',');
            var cnt_usr=0;
            var chk_tot=0;
            var cnt_qunt=1;
            for(var z=0;z<sp_users.length;z++)
                {
    //                      var ext_rec=0;
    //                       if(rem_s!=0 && rem_s!=cnt_qunt)
    //                           ext_rec=1;
                    $('#'+sp_users[z]).val(qunt);
    //                       cnt_qunt++;
                }
                
                for(var y=1;y<=rem_s;)
                {
                    for(var zz=0;zz<sp_users.length;zz++)
                        {
                                $('#'+sp_users[zz]).val(parseInt($('#'+sp_users[zz]).val())+1);
                            
                                if(y==rem_s)
                                break;
                                y++;
                        }
                            if(y==rem_s)
                                break;
                }
                
        }
        }

    });
    });
    //////

  async  function  get_records()
    {   
        await updateCSRFTokenSync();
          
            var ddl_users = $('#ddl_users').val();
            var txt_frm_dt = $('#txt_frm_dt').val();
            var txt_to_dt = $('#txt_to_dt').val();
            var ddl_users_nm=$('#ddl_users_nm').val();
            if(ddl_users=='')
                {
                    alert("Pleas Select User Type");
                }
            else  if(ddl_users_nm=='')
                {
                    alert("Pleas Select User Name");
                }
                else 
                    {

                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('RI/DispatchController/getuser_for_transfer_case_alloted'); ?>",
                beforeSend: function() {
                    $('#dv_data').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                data: {ddl_users: ddl_users, txt_frm_dt: txt_frm_dt, txt_to_dt: txt_to_dt,ddl_users_nm:ddl_users_nm,CSRF_TOKEN:CSRF_TOKEN_VALUE},
                dataType: 'html', 
                success: function(data) {
                    updateCSRFToken();
                    $('#dv_data').html(data);
                    
                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        
        }
    }






   
</script>

 <?//=view('sci_main_footer') ?>