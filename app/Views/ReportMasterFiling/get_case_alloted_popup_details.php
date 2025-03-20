<style>
.table-striped tr:nth-child(odd) td {
    background: #fff !important;
    box-shadow: none;
    border: 1px solid #8080805e;
    text-align: center;
}

.table-striped tr:nth-child(even) td {
    background: #f5f5f5;
	border: 1px solid #8080805e;
    text-align: center;
}
</style>

<?php 
if(!empty($result_array)){ ?>
<div class="table-responsive">
 <table class="table table-striped custom-table" id="example1">
  <thead>
     <tr>
		<th>
            S.No.
        </th>
        <th>
            Diary No.
        </th>
        <th>
            Dispatched By
        </th>
        <th>
            Dispatch Date
        </th>
		<?php if(($case=='spcomp' && $ddl_users!='101') || $case=='spcompr'){ ?>
		<th>
			Dispatched To
		</th>
		<th>
			Dispatched To Date
		</th>
        <?php } if($case=='sptwd'){?>
		<th>
			Dispatched to
		</th>
		<th>
			Dispatched date
		</th>
		<th>
			Remark
        </th>
        <?php }?>
        <th>
            Category
        </th>
        <th>
            N.B./B./C.
        </th>
        <?php if($ddl_users=='109'){ ?>
		<th>
			Document No./Year
		</th>
		<th>
			Document
		</th>
        <?php }?>
        <th>Pages</th>
        <?php  if($ddl_users=='107' || $ddl_users=='9796') {?>
            <th>
                <span style="color: red">Listed</span>/<span style="color: green">Updated</span> on
            </th>
        <?php } ?>
      
    </tr>
	<?php
    $sno=1; $total_pages=0;
	//print_r($result_array); die;
	foreach($result_array as $row){?>
        <tr>
            <td>
                <?php echo $sno; ?>
            </td>
            <td>
                <?php   echo substr( $row['diary_no'], 0, strlen( $row['diary_no'] ) -4 ) ; ?>-<?php echo substr( $row['diary_no'] , -4 ); ?>
            </td>
            <td><?php if (array_key_exists('d_to_empid', $row)) {
			                echo get_user_name_info($row['d_to_empid']);
			          }else{
						  echo '-';
					  }
				?>	   
					   
			</td>
            <td>
                <?php echo date('d-m-Y H:i:s',strtotime($row['disp_dt'])); ?>
            </td>
            <?php  if(($case=='spcomp' && $ddl_users!='101') || $case=='spcompr') {?>
            <td>
                    <?php if($row['rece_dt']=='0000-00-00 00:00:00') {
                        if($ddl_users=='105' || $ddl_users=='106'){
                            echo get_user_name_info($row['d_to_empid']);
                        } else {
                            echo '-';
						}
                    }else{
                        if($ddl_users=='109'){
							echo get_user_name_uid($row['d_to_empid']);
                        } else{
                           echo get_user_name_info($row['d_by_empid']);
						}
					} ?>
            </td>
            <td>
                    <?php
                    if($row['rece_dt']=='0000-00-00 00:00:00'){
                         echo '-';
                    }else{
						if($row['rece_dt']!=''){
							echo date('d-m-Y H:i:s', strtotime($row['rece_dt']));
						}else{
							echo '-';
						}
					}
                    ?>
            </td>
            <?php }
			if($case=='sptwd') {?>
				<td>
                    <?php
                    if($row['d_disp_dt']=='0000-00-00 00:00:00'){
                        echo '-';
					} else {
                        echo get_user_name_info($row['d_d_to_empid']);
					}
                    ?>
                </td>
                <td>
                    <?php
                    if($row['d_disp_dt']=='0000-00-00 00:00:00')
                        echo '-';
                    else
                        echo date('d-m-Y H:i:s', strtotime($row['d_disp_dt']));
                    ?>
                </td>
                <td>
                    <?php echo $row['remarks']; ?>
                </td>
            <?php }?>
            <td>
                <?php  $category_details = get_mul_category_details($row['diary_no']);
				     if(!empty($category_details)){
						 foreach($category_details as $row1){
							 echo $row1['category_sc_old'].'- '.$row1['sub_name1'].': '.$row1['sub_name4'].'<br/>';
						 }
					 }else{
						 echo '-';
					 }						 
				?>
            </td>
            <td>
                <?php  $not_bef = get_not_before_details($row['diary_no']);
				       $tot_bef_jud=''; $tot_nbef_jud=''; $tot_coram='';
				      if(!empty($not_bef)){
						  $b_n='';
						  foreach($not_bef as $row2){
						      if($b_n!=$row2['notbef']) {
								$be_nb='';
								$clr='';
								
								if($row2['notbef']=='B'){
									$be_nb="Before";
									$clr="green";
								} else{
                                  $be_nb="Not Before";
                                   $clr="red";
                                }
								
								if($row2['notbef']=='B'){
                                     $tot_bef_jud_h="<span style=color:$clr;>$be_nb-</span>";
								} elseif($row2['notbef']=='N'){
									$tot_nbef_jud_h="<span style=color:$clr;>$be_nb-</span>";
								}
								$b_n=$row2['notbef'];
                        }
                        $get_judge_nm=  get_judge_nm($row2['j1']);
						
                        if($row2['notbef']=='B'){
                            if($tot_bef_jud==''){
                                $tot_bef_jud=$tot_bef_jud_h.$get_judge_nm;
                            }else{
                                $tot_bef_jud=$tot_bef_jud.', '.$get_judge_nm;
							}
                        }
                        if($row2['notbef']=='N'){
                            if($tot_nbef_jud==''){
                                $tot_nbef_jud=$tot_nbef_jud_h.$get_judge_nm;
                            }else{
                                $tot_nbef_jud=$tot_nbef_jud.', '.$get_judge_nm;
							}
                        }
                    }
                    if($tot_bef_jud!=''){
                        echo $tot_bef_jud;
					}
                    if($tot_nbef_jud!=''){
                        if($tot_bef_jud!=''){
                            echo '<br/>';
                        echo $tot_nbef_jud;
                    }}
                }
						  
						
                $get_b_c = get_b_c($row['diary_no']);
				if(!empty($get_b_c)){
					$ex_get_b_c=explode(',',$get_b_c);
					for ($index = 0; $index < count($ex_get_b_c); $index++) {
						$get_judge_nm=  get_judge_nm($ex_get_b_c[$index]);
						if($tot_coram=='')
							$tot_coram=$get_judge_nm;
						else
							$tot_coram=$tot_coram.', '.$get_judge_nm;
					}
					if($tot_coram!='')
					{
						if($tot_bef_jud!='' || $tot_nbef_jud!='')
							echo "<br/>";
						?>
						<span style=color:#5d9c0a>BEFORE CORAM- </span><?php echo $tot_coram; ?>
						<?php
					}
				}
                ?>
            </td>
            <?php
            if($ddl_users=='109'){ ?>
                <td>
                    <?php
                    echo $row['docnum'].'/'.$row['docyear'];
                    ?>
                </td>
                <td>
                    <?php
                    /* $other='';
                    if($row['other1']!='')
                        $other= '-'.$row['other1'];
                    echo $row['docdesc'].$other; */
                    ?>
                </td>
            <?php } ?>

            <?php if($ddl_users=='107' || $ddl_users=='9796'){
                    $r_h_dt =  r_h_dt($row['diary_no']);
					$clr='';
                     if(!empty($r_h_dt)){
						if($r_h_dt['clno']==0 && $r_h_dt['brd_slno']==0){
							$clr="green";
						} else if($r_h_dt['clno']!=0 && $r_h_dt['brd_slno']!=0){
							$clr="red";
					  }
			       }
                ?>
			<td>
                 <span style="color: <?php echo $clr; ?>"><?= $r_h_dt['next_dt']!=''?date('d-m-Y',strtotime($r_h_dt['next_dt'])):'-'; ?></span>
           </td>
           <?php } ?>
		   
           <?php  $rs_query =  case_pages($row['diary_no']); 
		          if(!empty($rs_query)){
                  foreach($rs_query as $row_query){
				      $pages=$row_query[0];
                      $total_pages=(int)$total_pages+(int)$pages; ?>
               <td><?php echo $pages; ?></td>
			<?php }}else{ ?>
               <td>0</td>
			<?php }?>   
        </tr>
        <?php
        $sno++;
    }
         echo "<center style='margin-top: 20px;'><font  ><b><h2 style='color: red;'> Total pages = "."$total_pages</h2></b>";
 ?>
</table>
</div>
<?php }else{ ?>
<h3 style='margin-top: 20px; text-align:center;' >Not Found</h3>
<?php }?>
