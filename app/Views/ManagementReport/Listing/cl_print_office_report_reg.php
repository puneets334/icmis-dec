
<?= view('header') ?>
<style>
    fieldset{
        padding:5px; background-color:#F5FAFF; border:1px solid #0083FF; 
    }
    legend{
        background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF; font-weight: bold;
    }
    .table3, .subct2, .subct3, .subct4, #res_on_off, #resh_from_txt{
        display:none;
    }
    .toggle_btn{
        text-align: left; color: #00cc99; font-size:18px; font-weight: bold; cursor: pointer;
    }
    div, table, tr, td{
        font-size:10px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <!-- <h5 class="text-center mb-0">Cases Listed then Deleted</h5> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="">
                                            <div id="dv_content1" >
                                                <div style="text-align: center">
                                                    <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">CONSOLIDATED CAUSE LIST (ONLY PUBLISHED LIST)</span>
                                                    <table border="0" align="center">
                                                        <tr valign="middle">
                                                            <td id="id_mf">
                                                                <?php field_mainhead(); ?>
                                                            </td>
                                                            <td id="id_dts">
                                                                <?php field_sel_roster_dts(); ?>
                                                            </td>
                                        <td>
                                            <?php 
                                                // field_board_type_reg(); 
                                                field_board_type();
                                            ?>
                                        </td>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                            <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Court No.</b></legend>
                                            <select class="ele" name="courtno" id="courtno">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="21">21 (Registrar)</option>
                                                <option value="22">22 (Registrar)</option>
                                            </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                            <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Purpose of Listing</b></legend>
                                            <select class="ele" name="listing_purpose" id="listing_purpose">
                                            <?php  //f_listorder(); ?> 
                                            <option value="all" selected="selected">-ALL-</option>
                                                        <?php if (!empty($f_listorder)) : ?>
                                                            <?php foreach ($f_listorder as $row) : ?>
                                                                <option value="<?= $row->code; ?>"><?= $row->code; ?>. <?= $row->purpose; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1">EMPTY</option>
                                                        <?php endif; ?>
                                            </select>
                                            </fieldset>
                                        </td> 
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Main/Suppl.</b></legend>
                                                <select class="ele" name="main_suppl" id="main_suppl">
                                                    <option value="0">-ALL-</option>
                                                    <option value="1">Main</option>
                                                    <option value="2">Suppl.</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                            
                                        
                                                <input type="hidden" name="sec_id" id="sec_id" value="0">
                                        <!--<td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Order By</b></legend>
                                                <select class="ele" name="orderby" id="orderby">
                                                <option value="0">-ALL-</option>
                                                <option value="1">Court Wise</option>
                                                <option value="2">Section Wise</option>            
                                            </select>
                                        </td>-->
                                        <td id="rs_actio_btn1">
                                            <?php field_action_btn1(); ?>
                                        </td>
                                    </tr>
                                    </table>   
                                    <div id="res_loader"></div>
                                    </div>
                                                
                                    <!-- <div id="dv_res1"></div>     -->


                                    </div>
                                        
                                    </form>
<div id="dv_res1"></div>
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

<script>
        $(document).on("click","[id^='doc_']",function(){
            var tempid = this.id.split('_');
            //alert("court no="+tempid[1]+", date ="+tempid[2]+",query="+tempid[3]);

            //return false;
            $('#dv_sh_hd').css("display","block");
            $('#dv_fixedFor_P').css("display","block");
            $.ajax({
                type: 'POST',
                url:"./get_or_not_uploaded.php",
                beforeSend: function (xhr) {
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                data:{type:tempid[0],courtno:tempid[1],date: tempid[2],dataquery: tempid[3]}
            })
                .done(function(msg_new){
                    $("#sar").html(msg_new);
                })
                .fail(function(){
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
                });
        });

        $(document).on("click","#sp_close",function(){
            $('#dv_fixedFor_P').css("display","none");
            $('#dv_sh_hd').css("display","none");
        });

        
        $(document).on("change","input[name='mainhead']", async function(){
            var mainhead = get_mainhead();
            var board_type = $("#board_type").val();
            if(board_type == 0){
                alert('Please select Board Type')
                $("#board_type").focus();
            }
            var res = await updateCSRFTokenSync();
            var CSRF_TOKEN_VALUE = res.CSRF_TOKEN_VALUE;
            $.ajax({
                url: '<?= base_url(); ?>/Listing/CaseDrop/get_cl_print_mainhead',
                cache: false,
                async: true,
                data: {CSRF_TOKEN:CSRF_TOKEN_VALUE,mainhead:mainhead, board_type: board_type},
                beforeSend:function(){
                    //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('#listing_dts').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });


        $(document).on("click","#btn1",function(){
            get_cl_1();
        });

        async function get_cl_1(){

            var mainhead = get_mainhead();
            var list_dt = $("#listing_dts").val();
            var courtno = $("#courtno").val();
            var lp = $("#listing_purpose").val();
            var board_type = $("#board_type").val();
            var orderby = $("#orderby").val();
            var sec_id = $("#sec_id").val();
            var main_suppl = $("#main_suppl").val();
            var res = await updateCSRFTokenSync();
            var CSRF_TOKEN_VALUE = res.CSRF_TOKEN_VALUE;
            $.ajax({
                url: '<?= base_url(); ?>/ManagementReports/Listing/PrintOfficeReportReg/get_cause_list_or',
                cache: false,
                async: true,
                data: {CSRF_TOKEN:CSRF_TOKEN_VALUE,list_dt: list_dt, mainhead:mainhead, lp:lp, courtno: courtno, board_type: board_type, orderby:orderby, sec_id:sec_id, main_suppl:main_suppl},
                beforeSend:function(){
                    $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('#dv_res1').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }



        function get_mainhead(){
            var mainhead = "";
            $('input[type=radio]').each(function () {
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
            return mainhead;
        }

        //function CallPrint(){ ManagementReports/common/get_cl_print_mainhead.php
        $(document).on("click","#prnnt1",function(){
            var prtContent = $("#prnnt").html();
            var temp_str=prtContent;
            var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        });

    </script>

<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
</div>
<div id="dv_fixedFor_P" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">
    <!--<div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()">
        <img src="close_btn.png" style="width: 30px;height: 30px;">
    </div>-->
    <div id="sar1" style="background-color: white;overflow: auto;margin: 60px 250px 30px 250px;height: 80%;">
        <div id="sp_close" style="text-align: right;cursor: pointer;float: right" >
            <img src="../../images/close_btn.png" style="width: 20px;height: 20px;">
        </div>
        <div id="sar" style="border: 0px solid red"></div>
    </div>
</div>
