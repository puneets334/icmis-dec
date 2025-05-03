<div class="row table-responsive" style="width:98%;overflow-x:scroll;">
 <?php  
     if(!empty($serve_status))
     {
         ?>
         <style>
    table> thead > tr >th{
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
</style>
<table align="center" style="margin-top: 10px;" class="table table-bordered">
    <thead>
    <tr>
        <th>SNo.</th>        
        <th>Diary No.</th>
        <th>Case No.</th>
        <th>Issue Date</th> 
         <th>
            Prepared By
        </th>
        <th>
            Published By
        </th>
        <th>
            Notice Type
        </th>
        <th>Process Id</th>
         <th>
           Name
        </th>
        <th>Address</th>
        <th>
            Mode
        </th>
        <th>
         Generate Notice
        </th>
        <th>
            Discard Notice
        </th>
    </tr>
     </thead>
    <?php
    $sno=1;
    $srlno=1;
    $cnt_diary='';
    $file_name='';
    foreach($serve_status as $row) 
    {
                 ?>
            <tr>
                <?php
                if($cnt_diary!=$row['diary_no'] || $file_name!=$row['notice_path'])
                {
                ?>
                <td rowspan="<?php echo $row['s'] ?>">
                    <?php echo $srlno; ?>
                </td>        
            
                <td rowspan="<?php echo $row['s'] ?>">
                    <?php 
                    echo substr($row['diary_no'],0,-4).'-'. substr($row['diary_no'],-4); 
                    ?>
                    <input type="hidden" name="hd_diary_no<?php echo $sno; ?>" id="hd_diary_no<?php echo $sno; ?>" value="<?php echo $row['diary_no']; ?>"/>
                    <input type="hidden" name="hd_rec_dt<?php echo $sno; ?>" id="hd_rec_dt<?php echo $sno; ?>" value="<?php echo $row['rec_dt']; ?>"/>
                </td>
                <td rowspan="<?php echo $row['s'] ?>">
                    <?php 
                    echo $row['reg_no_display'];
                    ?>
                </td>
                <td rowspan="<?php echo $row['s'] ?>">
                    <?php 
                    echo date('d-m-Y',strtotime($row['rec_dt']));
                    ?>
                </td>
                
                <td rowspan="<?php echo $row['s'] ?>">
                    
                    <?php             
                    $user_id = $row['user_id'];
                    $nn = is_data_from_table('master.users', ['usercode' => $user_id], 'name','');                       
                    echo $nn['name'] ?? ''; 
                    ?>
                </td>
                <td rowspan="<?php echo $row['s'] ?>">
                    
                    <?php               
                $published_by = $row['published_by'];
                $nn1 = is_data_from_table('master.users', ['usercode' => $published_by], 'name','');                       
                    echo $nn1['name'] ?? '';  
                    ?>
                </td>
                <?php  
                } ?>
                <td>
                    <?php echo $row['nt_type']; ?>
                </td>
                <td>
                    <?php 
                    echo $row['process_id'].'/'. date('Y',strtotime($row['rec_dt']));
                    ?>
                    <div style="color: red">
                        <?php
                        if($row['copy_type']==1)
                        {
                            echo "Copy";
                        }
                        ?>
                    </div>
                </td>
                <td>
                    <?php
                
                    if($row['name']!='' && $row['send_to_type']==0)
                    {
                        echo $row['name'];
                    }
                    if($row['name']!='' && $row['tw_sn_to']!=0 && $row['send_to_type']==0)
                    {
                        echo "Through ";
                    }
                    if($row['tw_sn_to']!=0)
                    {
                        $send_to_name= send_to_name($row['send_to_type'],$row['tw_sn_to']);
                    }             
                    echo $send_to_name ?? '';              
                    ?>          
                </td>
                <td>
                    <?php
                    if($row['address']!='')
                    {
                        echo $row['address'];
                    }
                    $district=get_district($row['sendto_district']);
                    $state=get_state($row['sendto_state']);
                    echo $district.' '.$state;
                    ?>
                </td>
                <td>
                    <?php 
                    echo $row['del_type'];
                    ?>
                </td>     
                <?php        
                $fil_nm="../pdf_notices/".$row['notice_path'];       
            ?>
                    <input type="hidden" name="hd_diary_no<?php echo $sno; ?>" id="hd_diary_no<?php echo $sno; ?>" value="<?php echo $row['diary_no']; ?>"/>
                    <input type="hidden" name="hd_rec_dt<?php echo $sno; ?>" id="hd_rec_dt<?php echo $sno; ?>" value="<?php echo $row['rec_dt']; ?>"/>
                        
                    <input type="hidden" name="hd_fil_nm<?php echo $sno; ?>" id="hd_fil_nm<?php echo $sno; ?>" value="<?php echo $fil_nm; ?>"/>
                    <?php
                    if($row['dispatch_dt'] != '') {
        
                        if($cnt_diary!=$row['diary_no'] || $file_name!=$row['notice_path']){
                            ?>
                        <td rowspan="<?php echo $row['s']; ?>">
                                <a href="<?php echo $fil_nm; ?>" target="popup"
                                onclick="window.open('<?php echo $fil_nm; ?>', 'popup', 'width=800,height=400'); return false;">
                                    View Notice
                                </a>
                            </td>
                        
                            <?php
                        }
                }
                else {
        
                        if($cnt_diary!=$row['diary_no'] || $file_name!=$row['notice_path']){
                        ?>
                        <td rowspan="<?php echo $row['s'] ?>">
                            <input type="button" name="btn_generate<?php echo $sno; ?>" id="btn_generate<?php echo $sno; ?>"
                                    value="Generate" class="cl_generate"/></td>
                        <?php

                        }
                    }
                    
                    if($cnt_diary!=$row['diary_no'] || $file_name!=$row['notice_path'])
                    {
                        ?>
                            <td rowspan="<?php echo $row['s'] ?>">
                                <input type="button" name="btn_back<?php echo $sno; ?>" id="btn_back<?php echo $sno; ?>"
                                        value="Discard Notice" class="cl_back"/>
                            </td>
                        <?php 
                    }  
                    if($cnt_diary!=$row['diary_no'] || $file_name!=$row['notice_path']) {
                        $srlno++;
                    }
                        
                    $sno++;
                    $cnt_diary=$row['diary_no'];
                    $file_name=$row['notice_path'];

                ?>
            </tr>
        <?php
   
    }
    ?>
    </table>
			<input type="hidden" name="hd_active_filez" id="hd_active_filez"/>
			<input type="hidden" name="hd_fil_no_x" id="hd_fil_no_x"/>
            <input type="hidden" name="hd_recdt" id="hd_recdt"/>
            <input type="hidden" name="hd_off_notice" id="hd_off_notice"/>
		<?php    
         include ('editor_design.php');
     }
     else 
     {		 
         ?>
		<div style="text-align: center"><b>No Record Found</b></div>
		<?php
     }
 
    ?>
</div>