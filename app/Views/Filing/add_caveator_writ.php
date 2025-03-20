 <?=view('header') ?>
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title"> Add caveator in writ</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						 <span class="alert alert-error" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </span>
						<?php
						 $attribute = array('class' => 'form-horizontal', 'name' => 'frm', 'id' => 'frm', 'autocomplete' => 'off');
                         echo form_open('#', $attribute);
						?>
						 
						 
						<div id="dv_content1"   >
							<div id="main" >	 

									<table class="table" align ="center"  cellpadding="1" cellspacing="1" style="width:70%">

										<tr>
											<td align="center" valign="center"><br> <b>Diary no. </b> </td>
											<td align="center"> <input type="text" class="form-control" id="cavno" name=cavno" placeholder="Diary no" onkeypress="return onlynumbers(event)" /> </td>
											<td> <input type="text" id="cavyr" name="cavyr"  class="form-control" maxlength="4" placeholder="Year"  id="1" onblur="check(1)"   onkeypress="return onlynumbers(event)" /> </td>
										</tr>
										<tr> <td align="center" colspan=3><div id = 'div_result'> </div></td> </tr>
										
										<tr>
											<td  align="center"> <b> Select Aor Code :</b></td>
													<td><select id="aorcode"  class="form-control">
															<option value="">-select-</option>
														<?php
												
												 
												foreach($aor_list as $rw)
												{
												 ?>
												 
															<option value="<?php echo $rw['bar_id'] ?>"><?php echo $rw['aor_code']."-".$rw['name'];  ?></option>           
												<?php
												}
												?>
															
														</select></td><td></td>
											
										</tr>
										<tr><td><b>Enter Remarks :</b></td> <td><input type ="text"  class="form-control" size ="50" id="remarks"</td></tr>
										
										 <tr>
											<td align="center" colspan=3> 
												<input type="button" id="button" value="Add Advocate" onClick="insert_advocate()" name="btn" <?php if(!empty($is_renewed) && $is_renewed > 0){ ?> disabled <?php }?> class="btn btn-primary"  />
												</td>
										</tr>
								   
									</table>

								<br>

							</div>
						</div>
						 <?php echo form_close();?>

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
    <script type="text/javascript" src="<?php echo base_url('caveat/writs.js'); ?>" defer="defer"></script>
    
 
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