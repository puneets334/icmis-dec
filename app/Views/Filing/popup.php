<input type="hidden" id="fil_hd" value="<?php echo $diary_no; ?>"/>
<input type="hidden" id="side_hd" value="<?php echo trim($details[0]['side']); ?>"/>

<table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
<?php
if($details[0]['c_status']=='D')
{
?>
    <tr><th colspan="4" style="color:red"><center>The Case is Disposed!!!</center></th></tr>
<?php
}
?>
    <tr style="color:blue"><th colspan="4"><center><?php echo "Diary No ".substr($diary_no,0,-4).'/'.substr($diary_no,-4)." <u style=color:black>@ ".$details[0]['status']." stage</u>"; ?></center></th></tr>
    <tr><th colspan="4" style="color:blue"><center><?php echo $details[0]['pet_name']."<span style='color:black'> - Vs - </span>".$details[0]['res_name']?></center></th></tr>
    <?php 
    $category = '';
    foreach($row_cate as $row_cate_val){
        $category .= $row_cate_val['sub_name1'].'-'.$row_cate_val['sub_name2'].'-'.$row_cate_val['sub_name3'].'-'.$row_cate_val['sub_name4'].'<br>';
    }
    ?>
    <tr><th colspan="4"><center><i>Category:</i> <span style="font-size:14px;color:brown"><?php echo $category; ?></span></center></th></tr>
    <tr><th colspan="4" style="text-align: center;font-size: 14px;">
        <?php

        foreach($main_case as $main_case_val){
        	if($main_case_val['conn_key']==$diary_no){
                echo "This is Main Diary No";
        	}
            else{
                echo "This is Connected Diary No, Main Diary No is <span style='color:red'>".substr($diary_no,0,-4).'/'.substr($diary_no,-4)."</span>";
            }
        }

        echo "</br>List Before/Not Before Logic is Pending";
        
        if(!empty($details[0]['tentative_cl_dt'])){
		    $tentative_cl_dt = date('d/M/Y',strtotime($details[0]['tentative_cl_dt']));
		}else{
			$tentative_cl_dt = '';
		}

		if(!empty($details[0]['next_dt'])){
		    $next_dt = date('d/M/Y',strtotime($details[0]['next_dt']));
		}else{
			$next_dt = '';
		}

        ?>
        </th></tr>
    <tr><td style="">Filing Date:</td><td style=""><?php if($details[0]['diary_no_rec_date']!='') echo date('d-M-Y',strtotime($details[0]['diary_no_rec_date'])).' on '.date('h:i A',strtotime($details[0]['diary_no_rec_date'])); else echo '--';?></td>
        <td style="">Registration Date:</td><td style=""><?php if($details[0]['fil_dt']!='') echo date('d-M-Y',strtotime($details[0]['fil_dt'])).' on '.date('h:i A',strtotime($details[0]['fil_dt'])); else echo '--';?></td></tr>
    <tr><td>Tentative Cause-List Date:</td><td><input type="text" id="tdt" value="<?php echo $tentative_cl_dt;?>" readonly=""/></td>
        <td>Last Order:</td><td><?php if($details[0]['lastorder']!=''||$details[0]['lastorder']!=NULL) echo $details[0]['lastorder']; else echo '--'; ?></td></tr>
    <tr><td>Next Date:</td><td><input type="text" id="ndt" value="<?php echo $next_dt;?>" readonly=""/></td>
        <td style="color:red" colspan="2">Part:<!--</td><td>--><input type="text" id="session" size="2" maxlength="3" onkeypress="return onlynumbers(event)" value="<?php echo $details[0]['clno']; ?>" readonly=""/>
            <span style="color:red;padding-left: 20px;">Board No:</span><input type="text" id="brd_slno" size="1" maxlength="4" onkeypress="return onlynumbers(event)" value="<?php echo $details[0]['brd_slno']; ?>" readonly=""/>
        </td></tr>
    <tr><td>Heading:</td><td><select id="heading" onchange="getCoram(); getSubhead();" disabled="">
            <option value="M" <?php if($details[0]['mainhead'] == "M") {print "selected";}?>>Miscelleneous</option>
            <option value="F" <?php if($details[0]['mainhead'] == "F") {print "selected";}?>>Regular</option>
            <option value="L" <?php if($details[0]['mainhead'] == "L") {print "selected";}?>>Lok Adalat</option>
            <option value="S" <?php if($details[0]['mainhead'] == "S") {print "selected";}?>>Mediation</option></select>
        </td>
        <input type="hidden" value="<?php echo $details[0]['subhead']?>" id="hd_subhead"/>
        <td>Sub Heading:</td><td>

            <select id="subhead" style="width: 400px;" disabled=""><option value="">Select Sub Heading</option>    
            <?php
            foreach($rw_subh as $rw_subh_val):
                if($rw_subh_val['stagecode']==$details[0]['subhead'])
                { ?>
                <option value="<?php echo $rw_subh_val['stagecode']?>" selected><?php echo $rw_subh_val['stagename']?></option>
                <?php
                }
                else
                {?>
                <option value="<?php echo $rw_subh_val['stagecode']?>"><?php echo $rw_subh_val['stagename']?></option>
                <?php
                }
            endforeach;
            ?>
            </select>
        </td></tr>

    <tr><td><label for="coram" style="padding-left: 0px;">Coram:</label></td>
        <td><?php
        ?>  <select id="coram" style="width: 390px;" disabled=""><option value="0">NO CORAM</option>
                <?php
                foreach($row_judge as $row_judge_val):
                    ?>
                <option value="<?php echo $row_judge_val['id'].'~'.$row_judge_val['jcd']; ?>" <?php if($row_judge_val['id']==$details[0]['roster_id']) echo "selected"; ?>><?php echo $row_judge_val['abbr'].' - '.$row_judge_val['bench_no'].' - '.$row_judge_val['jnm']; ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </td>
        <td>Main/Supplementary:</td><td>
            <select id="main_supp" disabled="">
                <?php
                foreach($main_supp_row as $main_supp_row_val):

                	if(!empty($main_supp_row_val['descrip'])){
                		$desc = $main_supp_row_val['descrip'];
                	}else{
                		$desc = '';
                	}
                    ?>
                <option value="<?php echo $main_supp_row_val['id']; ?>" <?php if($main_supp_row_val['id']==$details[0]['main_supp_flag']) echo "Selected"; ?>><?php echo $desc; ?></option>
                        <?php
                endforeach;
                ?>
            </select></td>
    </tr>
    <tr><td>Sitting Judges:</td>
        <td><input type="text" id="sitt_jud" value="<?php echo $details[0]['sitting_judges']; ?>" readonly=""/></td>
        <td>Purpose of Listing:</td><td>
            <select id="purList" disabled="">
                <?php
                $g_=0;
                foreach($row_purpose as $row_purpose_val)
                {
                    ?>
                <option value="<?php echo $row_purpose_val['code'];?>" <?php if($row_purpose_val['code']==$details[0]['listorder']){ echo " Selected"; $g_=1;} ?>><?php echo $row_purpose_val['code'].' - '.$row_purpose_val['purpose'];?></option>
                        <?php
                }
                if($g_ == 0)
                {
                    ?>
                <option <?php echo $details[0]['listorder'];?> selected><?php echo $details[0]['listorder'];?></option>        
                        <?php
                }
                ?>
            </select></td>  
    </tr>
    <tr><td>Statutory Information:</td><td><textarea rows="2" cols="50" id="sinfo" readonly=""><?php echo $details[0]['remark'];?></textarea></td>
        <td>Board Type:</td><td><select id="board_type" disabled="">
                <option value="J" <?php if($details[0]['board_type']=='J') echo "selected"; ?>>Judge</option>
                <option value="C" <?php if($details[0]['board_type']=='C') echo "selected"; ?>>Chamber Judge</option>
                <option value="R" <?php if($details[0]['board_type']=='R') echo "selected"; ?>>Registrar</option></select></td></tr>

    </table>
    <?php if(!empty($row)){ ?>
    <div>
        <h3 style="text-align: center">INTERLOCUTARY APPLICATIONS OF CASE</h3>
    <div >
        <table align="center" id="tb_clr_n" >
        <tr><th>IA No.</th><th>Annual Reg.No.</th><th>Particular</th><th>Filed By and Date</th><th>Status</th></tr>
            <?php
            $sno=1;
            foreach($row as $row_val)
            {
            	if(!empty($row_val['ent_dt'])){
            		$date = date('d-m-Y',strtotime($row_val['ent_dt']));
            	}else{
            		$date = '';
            	}
               ?>
        <tr><td><?php echo $sno;?></td><td><?php echo $row_val['docnum'].'/'.$row_val['docyear'];?></td>
            <td><?php if(trim($row_val['docdesc'])=="XTRA") echo $row_val['other1']; else echo $row_val['docdesc'];?></td>
            <td><?php echo $row_val['filedby'].' Dt.'.$date; ?></td><td><?php if($row_val['iastat']=='P') echo 'Pending'; else if($row_val['iastat']=='D') echo 'Disposed';?></td></tr>
                <?php
                $sno++;    
            }
            ?>
        </table>
        <?php } ?></div></div>
<br>