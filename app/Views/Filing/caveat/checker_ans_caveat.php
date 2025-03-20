 <?=view('header') ?>
 <style>
 /* input[type="text"], input[type="date"], input[type="email"], input[type="tel"], input[type="number"], input[type="url"], input[type="password"], input[type="search"], select, textarea {
    
    width: auto !important;
     */
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
                                    <h3 class="card-title"> Category</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						 <span class="alert alert-error" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </span>
						<form method="post" action="<?= site_url(uri_string()) ?>">
				        <?= csrf_field() ?>
              <div id="dv_content1"   >
            <div style="text-align: center">
               <table align="center" >
                        <tr >
                           <td><b>Caveat No.</b>
                               
                                <input type="text" id="t_h_cno" class="form-control" name="t_h_cno" style="width:100% !important"  size="5" />
                          
                            </td>
                            <td><b>Caveat Year</b> 
                               
                                <!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
                        <?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y'); 
   print '<select id="t_h_cyt" style="width:100% !important" class="form-control">';   foreach ( range( $latest_year, $earliest_year ) as $i ) {
   print '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';   }
   print '</select>'; ?>                      
                            </td>
                            <td>
                            <b style="display:block;">&nbsp;</b>
                                <input type="button" name="sub" class="btn btn-primary" value="SUBMIT" onClick=" get_detail_for_checker();" >
                            </td>

                        </tr>
                        
                    </table>
               
            </div>
              <div id="dv_res1"> </div>      
            
             <div id="dv_nb"></div>
             <div id="hdd_low_cc"></div>
              <div id="dv_mul_cat"></div>
                
                 
                  <input  type="hidden" name="hd_sp_a_rem" id="hd_sp_a_rem"/>
                   <input  type="hidden" name="hd_sp_b_rem" id="hd_sp_b_rem"/>
                    <input  type="hidden" name="hd_sp_c_rem" id="hd_sp_c_rem"/>
                    <input  type="hidden" name="hd_sp_d_id" id="hd_sp_d_id"/>
                    <div id="dv_c_fee_d">
                        
                    </div>
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
    <!-- /.content -->
    <link href="<?php echo base_url('autocomplete/autocomplete.css');?>" rel="stylesheet">
    <!--<script src="<?php /*echo base_url('autocomplete/autocomplete.min.js'); */?>"></script>-->
    <script src="<?php echo base_url('autocomplete/autocomplete-ui.min.js'); ?>"></script>
    <script src="<?php echo base_url('filing/diary_add_filing.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('caveat/checker_ans_caveat.js'); ?>" defer="defer"></script>
    
 
    <script>
	
        function check(browser) {
            // // document.getElementById("answer").value=browser;
            //  alert(browser);

            if(browser=='c')   // case type is selected
            {
                document.getElementById('diary_no').value='';
                document.getElementById('dyr').value='';


                document.getElementById('diary_no').disabled=true;
                document.getElementById('dyr').disabled=true;


                document.getElementById('no').disabled = false;
                document.getElementById('ddl_nature_sci').disabled=false;

                document.getElementById('t_h_cyt').disabled = false;


            }
            else
            {


                document.getElementById('no').value = '';
                document.getElementById('ddl_nature_sci').value = '';

                document.getElementById('t_h_cyt').value = '';
                document.getElementById('no').disabled = true;
                document.getElementById('ddl_nature_sci').disabled=true;

                document.getElementById('t_h_cyt').disabled = true;
                document.getElementById('diary_no').disabled=false;
                document.getElementById('dyr').disabled=false;


            }

        }
		

    </script>
 
 
 