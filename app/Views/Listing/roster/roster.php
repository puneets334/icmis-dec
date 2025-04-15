<?= view('header'); ?>

<script type="text/javascript" src="<?php echo base_url('listing/roster.js'); ?>" defer="defer"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title"> Add Roster</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
<style type="text/css">
.ses_more2, .ses_more3, .ses_more2s, .ses_more3s{display:none;}

.cp_spcatall:hover
{
    color: blue;
    cursor: pointer;
    text-decoration: underline;
}

/* #sp_add,.del_rec,#sp_addz
{
    color:blue;
}
#sp_add:hover,.del_rec:hover,#sp_addz:hover
{
    text-decoration: underline;
    cursor: pointer;
} */
#FrmRoster .form-control {
    width: auto !important;
    display: inline-block;
	margin-top: 6px;
    min-width: 120px;
}

.btn-out-dark {
    color: #343a40;
    background-color: transparent;
    background-image: none;
    border-color: #343a40;
    cursor: pointer;
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid #383232f2;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.btn-out-dark:hover {
    color: #fff;
    z-index: 1;
    background: #383232f2;
}

ul.select2-selection__rendered>li {
    margin-bottom: 2px;
}

.border-side-left{
    border-top: 0.5px solid; 
    /* border-bottom: 0.5px solid;  */
    border-left: 0.5px solid;
    border-color: #e9ecef;
}

.border-side-right{
    border-top: 0.5px solid; 
    /* border-bottom: 0.5px solid;  */
    border-right: 0.5px solid;
    border-color: #e9ecef;
}

.border-side-top{
    border-top: 0.5px solid; 
    border-right: 0.5px solid; 
    /* border-left: 0.5px solid; */
    border-color: #e9ecef;
}

.border-side-bottom{
    border-right: 0.5px solid; 
    /* border-bottom: 0.5px solid;  */
    /* border-left: 0.5px solid; */
    border-color: #e9ecef;
}

#row_del_add1 td {
  background: #f5f5f5;
}

#jud-nm-area .selection .select2-search__field {
  width: 100% !important;
}

</style>
<script type="text/javascript">
    var leavesOnDates = <?= next_holidays_new(); ?>;

    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $('.ddtp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: date,
            todayHighlight: true,
            changeMonth : true, 
            changeYear : true,
            yearRange : '1950:2050',
            datesDisabled: leavesOnDates,
            isInvalidDate: function(date) {
                return (date.day() == 0 || date.day() == 6);
            },
        });
    });
</script>
    
       <form method="post" id="FrmRoster" action="<?= site_url(uri_string()) ?>">
		<?= csrf_field() ?>

             
              <div id="dv_content1"   >
                   <?php // $one= get_jc_jnm(); ?>
                  <table width="100%" id="tb_nms" class="tbl_border c_vertical_align" border="1" cellpadding="2" cellspacing="2">
                <tr>
                    <th class="border-side-left" style="width: 130px;">
                        Bench
                    </th>
                    <td class="border-side-right" style="width: 150px">
                        <select class="form-control" name="ddlBench" id="ddlBench" onchange="get_ben_no(this.value)">
                            <option value="">Select</option>
                            <?php 
                            //$sql=  mysql_query("Select id,bench_name from master_bench where display='Y'");
							$sql = is_data_from_table('master.master_bench',  " display = 'Y' order by bench_name ", ' id,bench_name', 'A');
                            foreach ($sql as $row1) {
                               if($hd_ud!='990') {
                                ?>
								<option value="<?php echo $row1['id'] ?>"><?php echo $row1['bench_name'] ?></option>
                            <?php
                               } else {
                                   if($row1['id']==5 || $row1['id']==6) {
                                   ?>
                            <option value="<?php echo $row1['id'] ?>"><?php echo $row1['bench_name'] ?></option>
                            <?php
                                   }
                               }
                            }
                            ?>
                        </select>
                    </td>
                    <th class="border-side-left" style="width: 100px; ">
                        Bench No. :
                    </th>
                    <td class="border-side-right">
                        <select name="bench_name" id="bench_name" class="input_style form-control" style="width: 150px">
                        <option value="">-Select-</option>
                       </select> 
                    </td>
					 <td  colspan="2"></div>

                  
                </tr>
                <tr>
                     <th class="border-side-top">
                            Effected From :
                    </th>
                     <th class="border-side-top">
                            Session :
                    </th>
                    <th class="border-side-top" style="width: 300px; padding-left:80px;" colspan="2">
                         Timing :
                    </th>
                    <th class="border-side-top">
                        No. of Cases :
                    </th>
                     <th class="border-side-top">
                       Delete
                    </th>
                </tr>
                <tr id="row_del_add1">
                    
                    <td class="border-side-bottom">
                     
                        <input type="text" name="from_dt1" id="from_dt1" class="ddtp form-control" maxsize="10" autocomplete="off" size="9" readonly/>   
                        <div id="dv_add_dts" ></div>
                      
                        <input type="hidden" name="hd_from_dt" id="hd_from_dt" value="1"/>
                    </td>
                    <td class="border-side-bottom">
                        <select name="sess1" id="sess1" class="form-control">
                            <option value="" selected >-Select-</option>
                            <option value="Whole Day">Whole Day</option>
                            <option value="Before Lunch">Before Lunch</option>                        
                            <option value="After Lunch">After Lunch</option>            
                            <option value="After Regular Bench">After Regular Bench</option>
                            <option value="After DB">After DB</option>
                            <option value="After SPL. DB">After SPL. DB</option>
                        </select>
                        <div id="dv_add_ses"></div>
                    </td>
                    <td class="border-side-bottom" colspan="2">
                        <select name="ddl_hrs1" id="ddl_hrs1" onchange="set_min(this.value,this.id)" class="form-control">
                            <option value="" selected >Select</option>
                            <?php
                            for($j=1;$j<=12;$j++)
                            {
                            ?>
                                <option value="<?php echo $j; ?>"><?php echo $j; ?></option>
                            <?php
                            }
                            ?>
                        </select> &nbsp;<b> : </b>&nbsp;
                     
                        <select name="ddl_min1" id="ddl_min1" disabled="true" class="form-control">
                            <option value="" selected >Select</option>
                            <?php
                            for($k=0;$k<=60;$k++)
                            {
                                if(strlen($k)==1)
                                {
                                    $k='0'.$k;
                                }
                            ?>
                            <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <select name="ddl_am_pm1" id="ddl_am_pm1" disabled="true" class="form-control">
                            <option value="" selected >Select</option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                     
                        <div id="dv_timing" ></div>
                    </td>
                    
                    <td class="border-side-bottom">
                        <input type="text" name="txt_no_case1" id="txt_no_case1" size="4" class="form-control" onkeypress="return OnlyNumbersTalwana(event,this.id)" maxlength="4" required />
                        <div id="dv_txt_no_case" ></div>
                    </td>
                    <td class="border-side-bottom">
                        <div ></div>
                        <div id="dv_delete"></div>
                    </td>
                </tr>
                <tr id="tr_insert_rows"></tr>
                <tr>
                    <td colspan="6" style="text-align: center">
                          <span id="sp_add" class="btn btn-primary" onclick="ad_textbox()" >Add</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Judge Name :
                    </td> 
                    <td id="jud-nm-area" colspan="5">
                        <select name="judge_code" id="judge_code" style="width:50% !important;" class="form-control multipleselect">
                            <option value="0" disabled>Select</option>
                        </select>
                    </td>
                </tr>
                 <tr id="tr_mt_de">
                <td>
                    Heading :
                </td>
                <td colspan="4">
                    <?php
                    if($hd_ud!='990')
                    {
                    ?>
                    <input type="radio" name="rdn_m_f" id="rdn_motion" value="1" onclick="get_head()" />Motion
                    &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_final" value="2" onclick="get_head()"/>Final
                     &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_lok" value="3" onclick="get_head()"/>Lok Adalat
                    &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_med" value="4" onclick="get_head()"/>Mediation 
                    <?php }
                    else 
                    {
                    ?>
                    <input type="radio" name="rdn_m_f" id="rdn_motion" value="1" onclick="get_head()" style="display: none"/>
                    &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_final" value="2" onclick="get_head()" style="display: none"/>
                     &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_lok" value="3" onclick="get_head()"/>Lok Adalat
                    &nbsp;&nbsp;
                    <input type="radio" name="rdn_m_f" id="rdn_med" value="4" onclick="get_head()"/>Mediation 
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width: 50px">
                    Sitting :
                    </td>
                    <td colspan="5">
                    <input type="radio" name="rdn_court_hl" id="rdn_court" value="0" checked="checked"/>Court &nbsp;&nbsp;
                    <input type="radio" name="rdn_court_hl" id="rdn_hl" value="1"/>Hall &nbsp;&nbsp;
                   Court No. <input type="text" name="txt_court_no" id="txt_court_no" class="form-control" size="6"/>
                         Print IN before Court No.
                        <select id="printInBeforeCourt" name="printInBeforeCourt" class="form-control">
                             <option value="0">No</option>
                             <option value="1">YES</option>
                         </select>
                </td>
            </tr>
            <tr id="tr_s_hdd" style="display: none">
                <td>
                    Select Head:
                </td>
                <td colspan="5">
                    <div id="dv_motion_final">
                        <input type="button" name="btnAll" id="btnAll0" value="All" onclick="get_cor_cat(this.id,'')"/>&nbsp;&nbsp;
                        <input type="button" name="btnCivil" id="btnAll1" value="Civil" onclick="get_cor_cat(this.id,'C')"/>&nbsp;&nbsp;
                        <input type="button" name="btnCriminal" id="btnAll3" value="Criminal" onclick="get_cor_cat(this.id,'R')"/>&nbsp;&nbsp;
                        <input type="button" name="btnWC" id="btnAll4" value="Writ Civil" onclick="get_cor_cat(this.id,'WC')"/>&nbsp;&nbsp;
                        <input type="button" name="btnWR" id="btnAll5" value="Writ Criminal" onclick="get_cor_cat(this.id,'WR')"/>&nbsp;&nbsp;
                        <input type="button" name="btnEP" id="btnAll2" value="Election Petition" onclick="get_cor_cat(this.id,'EP')"/>&nbsp;&nbsp;
                        <input type="button" name="btnPIL" id="btnAll6" value="PIL" onclick="get_cor_cat(this.id,'PIL')"/>&nbsp;&nbsp;
                        <input type="button" name="btnWA" id="btnAll7" value="WA" onclick="get_cor_cat(this.id,'WA')"/>&nbsp;&nbsp;
                    </div>
                    <br/>
                    <select name="srcList" id="srcList" class="input_style form-control multipleselect" multiple="multiple" size="6" STYLE=" height: 200px;width: 100%">                        
                        <option value="">Select</option>
                    </select>
                    <div style="text-align: center">
                        <input type="button" value=" -> " onclick="get_selecteds_recss()"/>   
                        <input type="button" value=" <- " onclick="javascript:deleteFromDestList('destList','0');"/>
                    </div>
                    <select size="6" name="destList" id="destList" multiple="multiple" class="input_style form-control multipleselect" STYLE="height: 200px;width: 100%">
                    </select>
                    <div style="text-align: center;padding-top: 10px">  
                        <input type="button" name="btn_cha_pri" id="btn_cha_pri" value="Change Priority" onclick="shuffle()"/>
                    </div>
                </td>
            </tr>
            <tr id="dv_mot_record_tr">
                <td colspan="6">
                    <div id="dv_mot_record" style="width: 100%"></div>
                </td>
            </tr>
            <tr>                
                <td align="center" colspan="6"><input type="button" name="btnsave" id="btnsave" value="SAVE" class="btn btn-primary"/></td>
            </tr>
        </table>
        <h2><div id="myerr" style="text-align: center;color: red"></div></h2>
        <div class="get_roster" id="list1" style="font-size: 110%; width:98%; text-align: center;" ></div>
        <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
       &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;	left:0;	width:100%;	height:100%;z-index: 105; padding-right: 25px; padding-left: 15px;">
        <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="<?php echo base_url()?>/images/close_btn.png" style="width:30px;height:30px"/></b></div>
        <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()"></div>
    </div>
        
    <!-- <div id="dv_extra_ress"></div>-->
    <div id="dv_cl_roster"></div>
        <input type="hidden" name="hd_hd_show_hide_dt_s" id="hd_hd_show_hide_dt_s"/>
              </div>
</form>   
</div> <!--end dv_content1-->



                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
	
	<script>
	$(document).ready(function(){
		$('.multipleselect').select2();
	});


    // $('.select2-search__field').trigger('change.select2');
	</script>
	
	 