<?php
 
     ?>
<table class="table_tr_th_w_clr">
                   <tr>
                       <th>
                          Check
                       </th>
                       <th>
                           Description
                       </th>
                   </tr>
               
               <?php
              /*  $s_keyword="Select id,keyword_description from ref_keyword where is_deleted='f' and keyword_description LIKE '%$_REQUEST[txt_src_key]%'";
               $s_keyword=mysql_query($s_keyword) or die("Error: ".__LINE__.mysql_error()); */
			   
			   $s_keyword = $CaveatModel->searchKeyword($txt_src_key);
               $s_k=0;
			   if(!empty($s_keyword))
			   {
				   foreach($s_keyword as $r_kw) 
				   {
					   ?>
					   <tr>
						   <td>
							   <input type="checkbox" class="cl_keyword" name="chk_keyword<?php echo $s_k; ?>" id="chk_keyword<?php echo $s_k; ?>" value="<?php echo $r_kw['id']; ?>"/>
						   </td>
							<td>
							   <span id="sp_k_des<?php echo $s_k; ?>"><?php echo $r_kw['keyword_description']; ?></span>
						   </td>
					   </tr>
				  
				   <?php
				   $s_k++;
				   }
			   }else{?>
				   <tr>
						   <td colspan="100%">
							   
							   <span id="sp_k_des<?php echo $s_k; ?>">No record found...</span>
						   </td>
					   </tr>
			  <?php }
               ?>
               </table>                