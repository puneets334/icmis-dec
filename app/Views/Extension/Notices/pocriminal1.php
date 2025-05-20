<?php
$dis_co_nm  = '';
$diary_no   = $_REQUEST['fil_no'];
$year_s     = substr( $diary_no , -4 );
$no_s       = substr( $diary_no, 0, strlen( $diary_no ) -4 );
$fil_nm     = isset($_REQUEST['fil_nm']) ? $_REQUEST['fil_nm'] : '';
$ind_org    = '';

$short_description_s = ''; 

    $res_fil_det = $noticesModel->getFileDetails($diary_no);
    $r_nature = '';
    if(isset($res_fil_det['nature']) && $res_fil_det['nature'] == 'C')
    {
        $r_nature = 'Civil';
    }
    else if(isset($res_fil_det['nature']) && $res_fil_det['nature'] == 'R')
    {
        $r_nature = 'Criminal';

    }

    if(isset($res_fil_det['casetype_id']) && $res_fil_det['casetype_id'] == 0)
    {
        $res_fil_det['casename'] = 'Diary';
        $case_range = substr($diary_no,0,-4);
        $reg_year = substr($diary_no,-4);
       
        $c_t_id = $res_fil_det['c_t_id'];
        $get_ten_casetype = is_data_from_table('master.casetype',  " casecode= $c_t_id and display='Y' ", 'casename', $row = '');
        $short_description_s= $get_ten_casetype['casename'];
    }
    else
    {
        $short_description_s = isset($res_fil_det['casename']) ? $res_fil_det['casename'] : '';
        $case_range = substr($res_fil_det['fil_no'], 3);
        $ex_case_range=  explode('-', $case_range);
        $c_range = '';
        $chk_range = '';
        $cnt_tot_reg_cases = 0;
        for ($index1 = 0; $index1 < count($ex_case_range); $index1++) {
            if($c_range == '')
                $c_range =  intval ($ex_case_range[$index1]);
            else
            {
                if($chk_range!=intval ($ex_case_range[$index1]))
                    $c_range =$c_range.'-'.intval ($ex_case_range[$index1]);
            }
            $chk_range=intval ($ex_case_range[$index1]);
            $cnt_tot_reg_cases++;
        }

        if($c_range!='')
            $case_range=$c_range;
        else
            $case_range=  intval ($case_range) ;
        $reg_year=date('Y',strtotime($res_fil_det['fil_dt']));

    }
    $last_order=$res_fil_det['lastorder'];
    $ex=  explode("Ord dt:",$last_order);
    $last_order_dt= date('d-m-Y',strtotime($ex[1]));
    $pno='';
    $rno='';
    if($res_fil_det['pno']!=0)
    {
        if($res_fil_det['pno']==2)
            $pno=" AND ANOTHER";
        else if($res_fil_det['pno']>2)
            $pno=" AND OTHERS";
    }
    if($res_fil_det['rno']!=0)
    {
        if($res_fil_det['rno']==2)
            $rno=" AND ANOTHER";
        else if($res_fil_det['rno']>2)
            $rno=" AND OTHERS";
    }

    $nt_type_ar=array();

    $dt = $_REQUEST['dt'];
    $sql_letter = is_data_from_table('tw_tal_del a',  " diary_no = '$diary_no' AND rec_dt = '$dt' AND print = '0' AND a.display = 'Y' GROUP BY a.nt_type ", " count( a.nt_type ) s , a.nt_type ", $row = 'A');
    if(!empty($sql_letter))
    {
        foreach ($sql_letter as $row2) {
            $nt_type_ar[$row2['nt_type']]=$row2['s'];
        }
    }

    $r_sql_letter= (!empty($sql_letter)) ? count($sql_letter) : 0;
    if($r_sql_letter==1)
        $r_sql_letter=0;

    $sql_res = $noticesModel->getTalDelDetails($diary_no, $_REQUEST['dt'], $fil_nm);    // $_REQUEST['fil_nm']
   
    if((!empty($sql_res)) && count($sql_res)>0)
    {
        $xzaq11_ben='';
        $send_too=array(); 

        $sql_res_rw=count($sql_res);

        if($r_sql_letter>0)
        {
            $sql_res_rw=$sql_res_rw-$r_sql_letter;
        }
        $v_cx=0;
        $res_nms='';
        $get_fx_fo_dt='';
        $ct_ntt_type='';
        $ck_pbb=0;
        $ck_mul_ind=0;
        $tot_copy='';
        $c_sno=1;
        $chk_copy_type=array();
        $chk_del_type=array();
        $chk_mul_letter='';
        $chk_break=1;
        $brk_limit=0;
        foreach ($sql_res as $row)
        {

            if($row['individual_multiple'] == 2 && $chk_mul_letter != $row['nt_type'])
            {
                foreach ($nt_type_ar as $key => $value) {
                    if($key == $row['nt_type'])
                    {
                        $brk_limit = $value;
                    }
                }
                $chk_mul_letter = $row['nt_type'];
            }
            $ck_mul_ind     = $ck_mul_ind+1;
            $state_nm_m     = state_name($row['tal_state']);
            $district_nm_m  = state_name($row['tal_district']);
            $fixed_for      = ($row['fixed_for']) ? date('d-m-Y', strtotime($row['fixed_for'])) : NULl;

            $del_type_s = is_data_from_table('tw_o_r',  " tw_org_id='$row[id]' and display='Y' group by tw_org_id "," STRING_AGG(del_type, '') AS del_types ",'');
            $del_type   = $del_type_s['del_types'];
             


            $chk_pet_res='';
            if($row['pet_res']=='P')
                $chk_pet_res='Petitioner';
            else if($row['pet_res']=='R')
                $chk_pet_res='Respondent';
            else
                $chk_pet_res='';



            $nt_type = explode(',', $row['nt_type']);
            $res_ct = '';

            for ($index = 0; $index < count($nt_type); $index++)
            {
                $n_ind_d = $nt_type[$index];
 
                $del_type_ct = strlen($del_type);
                $ct_del_type = 0;
                for($dtc=0;$dtc<$del_type_ct;$dtc++)
                {
                    $address_m = '';
                    $mul_del_ty = 0;

                    $rw_sn_type =  $noticesModel->getSendToDetails($row['id'], $del_type[$dtc],$copy_type = '0');
 
                    if(!empty($rw_sn_type))
                    {
                        $state_nm = state_name($rw_sn_type['sendto_state']);
                        $district_nm = state_name($rw_sn_type['sendto_district']);
                        $tw_sendto_type = '';
                        if($rw_sn_type['send_to_type'] == '2')
                        {
                            $tw_sn_to = send_to_nm($rw_sn_type['tw_sn_to']);
                            $tw_sendto_type = 2;
                        }
                        else  if($rw_sn_type['send_to_type']=='1')
                        {
 
                            $tw_sn_to=  send_to_advocate($rw_sn_type['tw_sn_to'],$row['p_sno']);
                            $tw_sendto_type=1;
                        }
                        else  if($rw_sn_type['send_to_type']=='3')
                        {

                            $tw_sn_to=  send_to_court($rw_sn_type['tw_sn_to'],'');
                            $tw_sendto_type=3;
                        }

                    }
                    else
                    {
                        $state_nm=$state_nm_m;
                        $district_nm=$district_nm_m;
                        $tw_sn_to= $row['name'];
                        $address_m=$row['address'];
                    }
 

                    $tot_copy_send_to='';                  

                    $send_copy_to_det =  $noticesModel->getSendToDetails($row['id'], $del_type[$dtc],$copy_type = '1');
                    if(!empty($send_copy_to_det))
                    {
                        $cnt_snd_cpy=0;
                        foreach ($send_copy_to_det as $rw_send_copy_to_det)
                        {

                            if($row['individual_multiple']==1 ||  $ck_mul_ind>$sql_res_rw)
                            {
                                if($rw_send_copy_to_det['send_to_type']=='1')
                                    $advocate_nm= send_to_advocate($rw_send_copy_to_det['tw_sn_to']);
                                else if($rw_send_copy_to_det['send_to_type']=='2')
                                    $advocate_nm=  send_to_nm($rw_send_copy_to_det['tw_sn_to']);
                                else  if($rw_send_copy_to_det['send_to_type']=='3')
                                {
                                    $advocate_nm=  send_to_court($rw_send_copy_to_det['tw_sn_to'],'');

                                }

                                $state_nm_c=state_name($rw_send_copy_to_det['sendto_state']);
                                $district_nm_c=state_name($rw_send_copy_to_det['sendto_district']);
                                if($tot_copy_send_to=='')
                                    $tot_copy_send_to=$advocate_nm.'!'.$state_nm_c.'!'.$district_nm_c;
                                else
                                    $tot_copy_send_to=$tot_copy_send_to.'@'.$advocate_nm.'!'.$state_nm_c.'!'.$district_nm_c;
                            }

                            $cnt_snd_cpy++;
                        }
 
                    }

                    if($row['individual_multiple']==2)
                    {                         
 
                        $ch_con_not=0;
 
                        if($ck_mul_ind!=$brk_limit )
                        {
                             continue;
                        } 
                        $ck_mul_ind=0;
                        $chk_break++; 

                    }
                    ?>
                    <div id="<?php echo $row['id'] ?>_<?php echo $n_ind_d; ?>_<?php echo $del_type[$dtc]; ?>" class="ind_no_w_vc" style="position: relative;background-image: linear-gradient(to right, rgba(255,255,255, 0.5) 0 100%),url('/images/scilogo.png');background-position: center;background-repeat: no-repeat;padding-left: 2px;padding-right: 2px;position: relative;margin-top: 20px;<?php if($ck_pbb!=0) { ?> page-break-before:always; <?php ;} ?>">
                        <?php
                        if($row['individual_multiple']==2 )
                        {                            
                          $mul_send_tp =   $noticesModel->getMultiSendTp($diary_no, $dt, $nt_type, $del_type);
                          
                            if(!empty($mul_send_tp))
                            {
                                $tot_records='';
                                $cnt_first_rec=0;
                                $ind_org=-1;
                                $ss_no=1;
                                $get_petitioner_advocate='';
                                $notice_type='';

                                $tot_records=$tot_records.'<div><table style="width: 100%;text-transform: uppercase;">';
                                foreach ($mul_send_tp as $row1) {
                                    $pin_code='';
                                    // if($n_ind_d=='12')
                                    $fir_dls='';
                                    $dis_det='';
                                    if($row1['nt_type']=='51' && $row1['send_to_type']==2)
                                    {
                                        if($row1['tw_sn_to']==9)
                                        {                                          

                                            $r_fir_detail = is_data_from_table('lowerct',  " diary_no='$diary_no' and lw_display='Y' and crimeno!=0 and crimeno is not null "," rimeno,crimeyear ",'');

                                            $fir_dls=' <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;width: 100%;float: left;clear:both"><b> <font  style="font-size: 13pt; " face="Times New Roman" > <b style="font-size: 13pt; " face="Times New Roman" > (FIR NO.' .$r_fir_detail['crimeno'].'/'.$r_fir_detail['crimeyear'].')</b></font></b></p>';
                                        }
                                        else
                                        {                                          

                                            $r_fir_detail = $noticesModel->getFirDetail($diary_no);

                                            $dis_det=' <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;
                                                width: 100%;float: left;clear:both"><b> <font  style="font-size: 13pt; " face="Times New Roman" > 
                                                <b style="font-size: 13pt; " face="Times New Roman" > (REF.:' .$r_fir_detail['type_sname'].' '.INTVAL($r_fir_detail['lct_caseno']).'/'.$r_fir_detail['lct_caseyear'].')</b></font></b>
                                                </p>';
                                        }
                                    }

                                    if($row1['nt_type']=='53' && $row1['send_to_type']==1 )
                                    {                                      
                                        $tw_sn_to = $row1['tw_sn_to'];
                                        $adv_parties = is_data_from_table('advocate',  " diary_no='$diary_no' and lw_display='Y' and advocate_id='$tw_sn_to' order by pet_res,pet_res_no "," pet_res,pet_res_no ",'A');
                                        $tot_parties='';
                                        foreach ($adv_parties as $row3) {
                                            if($tot_parties=='')
                                                $tot_parties=$row3['pet_res'].'['.$row3['pet_res_no'].']';
                                            else
                                                $tot_parties=$tot_parties.', '.$row3['pet_res'].'['.$row3['pet_res_no'].']';
                                        }
                                    }
                                    $ref_det='';
 
                                    $h_c_rg='';
                                    if($row1['section']=='11' || $row1['section']=='13' )
                                    {
                                        if($row1['send_to_type']==3)
                                        {
                                            $check_record_frm_which_cout= check_record_frm_which_cout($dairy_no,$row1['tw_sn_to']);                                           
                                            $lower_court= lower_court_conct_tp($dairy_no,$row1['tw_sn_to']);

                                            if($check_record_frm_which_cout==1)
                                            {
                                                $h_c_rg="The REGISTRAR GENERAL<br/>";
                                            }
                                        }
                                        else
                                            $lower_court= lower_court_conct($dairy_no);
                                        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                                            $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
                                            $agency_name=$lower_court[$index1][2];
                                            $skey=$lower_court[$index1][3];
                                            $lct_caseno=$lower_court[$index1][4];
                                            $lct_caseyear=$lower_court[$index1][5];

                                            $ref_det= '<div style="font-size: 13pt;margin-bottom: 10px;margin-top: 10px;text-align: left">(Ref.:';

                                            $ex_skey=  explode(',',$skey );
                                            $ex_lct_caseno=explode(',',$lct_caseno );
                                            $ex_lct_caseyear=explode(',',$lct_caseyear );
                                            for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
                                                if($index2>0){ echo ',';}

                                                $ref_det=$ref_det.'<b style="font-size: 13pt"  face= "Times New Roman"> '.  $ex_skey[$index2]. '</b> No. <b style="font-size: 13pt"  face= "Times New Roman">'.$ex_lct_caseno[$index2].'</b> of <b style="font-size: 13pt"  face= "Times New Roman">'.$ex_lct_caseyear[$index2].'</b>';}
                                            $ref_det=$ref_det.')</div>';

                                        }

                                    }

                                    $notice_type=$row1['nt_type'];
                                    if($res_fil_det['nature']=='C' && $ind_org!=0)
                                    {
                                        $pet_res = $row1['pet_res'];
                                        $sr_no = $row1['sr_no'];
                                        $check_party = is_data_from_table('party',  " diary_no='$diary_no' and pet_res='$pet_res' and pflag='P' and sr_no='$sr_no' "," ind_dep ",'A');
                                        $res_check_party = !empty($check_party) ? $check_party['ind_dep'] : '';
                                        if($res_check_party=='I')
                                        {
                                            $ind_org=0;
                                        }
                                        else if($res_check_party!='I')
                                        {
                                            $ind_org=1;
                                        }
                                    }
                                    if($n_ind_d=='56')
                                    {

                                        $get_petitioner_advocate_party= $noticesModel->get_petitioner_advocate_party($dairy_no,$row1['pet_res'],$row1['sr_no']);
                                        if($get_petitioner_advocate=='')
                                        {
                                            $get_petitioner_advocate=$get_petitioner_advocate_party;
                                        }
                                        else
                                        {
                                            $get_petitioner_advocate=$get_petitioner_advocate.', '.$get_petitioner_advocate_party;
                                        }
                                    }

                                    if($cnt_tot_reg_cases>1)
                                    {
                                        $get_party_case_no= $noticesModel->get_party_case_no($dairy_no,$row1['pet_res'],$row1['sr_no']);
                                        $get_lowercourt_id= $noticesModel->get_lowercourt_id($get_party_case_no);
                                        $in_reg_cases='';
                                        for ($index3 = 0; $index3 < count($get_lowercourt_id); $index3++) {

                                            $get_regisred_case_from_lowerct_id= $noticesModel->get_regisred_case_from_lowerct_id($dairy_no,$get_lowercourt_id[$index3][0]);
                                            $get_regisred_case_from_lowerct_id[0];
                                            $get_casetype_code= $noticesModel->get_casetype_code($get_regisred_case_from_lowerct_id[0]);
                                            if($get_casetype_code!='')
                                            {
                                                if($in_reg_cases=='')
                                                    $in_reg_cases=' in '.$get_casetype_code.' '.$get_regisred_case_from_lowerct_id[1].'/'.$get_regisred_case_from_lowerct_id[2];
                                                else
                                                    $in_reg_cases=$in_reg_cases.', '.$get_casetype_code.' '.$get_regisred_case_from_lowerct_id[1].'/'.$get_regisred_case_from_lowerct_id[2];
                                            }
                                        }
                                    }

                                    $ext_data='';
                                    if($row1['tw_sn_to']!=0)
                                    {
                                        
                                        if(trim($row1['name'])!='')
                                        {

                                            $ext_data='<p style="color: #000000;margin:'. $margin_t.' ;0px 0px 0px;padding: 0px 2px 0px 2px;width: 100%;float:left;clear:both;" >
                                            <b> <font style="font-size: 13pt; " face="Times New Roman"  >'.$row1['name'].'</b>, </p>';
                                                                                $ext_data=$ext_data.'<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;width: 100%;clear:both" > <b> 
                                        <font style="font-size: 13pt; " face="Times New Roman"  >  <b style="font-size: 13pt; " face="Times New Roman" >'. $row1['address']. '</b>, </font></b></p>';
                                                                                $ext_data=$ext_data.'<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;width: 100%;float: left;clear:both"><b> <font  style="font-size: 13pt; " face="Times New Roman" >   District- <b style="font-size: 13pt; " face="Times New Roman" >' .trim(state_name($row1['tal_district'])).', '.state_name($row1['tal_state']).$pin_code.'<br/> Through</b></font></b>
                                                </p>';
                                        }


                                        $state_nm=state_name($row1['sendto_state']);
                                        $district_nm=state_name($row1['sendto_district']);
                                        $tw_sendto_type='';
                                        if($row1['send_to_type']=='2')
                                        {
                                            $tw_sn_to=  send_to_nm($row1['tw_sn_to']);
                                            $tw_sendto_type=2;
                                        }
                                        else  if($row1['send_to_type']=='1')
                                        {
        
                                            $tw_sn_to=  send_to_advocate($row1['tw_sn_to'],$row1['p_sno'],'',$tot_parties);
                                            $tw_sendto_type=1;

                                        }
                                        else  if($row1['send_to_type']=='3')
                                        {

                                            $tw_sn_to=  send_to_court($row1['tw_sn_to'],$res_fil_det['c_t_id']);
                                            $tw_sendto_type=3;
                                        }
                                        $address_m='';
                                    }
                                    else
                                    {
                                        $state_nm_m=state_name($row1['tal_state']);
                                        $district_nm_m=state_name($row1['tal_district']);

                                        $state_nm=$state_nm_m;
                                        $district_nm=$district_nm_m;     
                                        $tw_sn_to= $row1['name'];

                                        $address_m=$row1['address'];
                                        $get_pin_code= $noticesModel->get_pin_code($dairy_no,$row1['pet_res'],$row1['sr_no']);

                                        if($get_pin_code!=0)
                                        {
                                            $pin_code=" - ". $get_pin_code;
                                        }
                                    }

                                    $address_c='';
                                    if($address_m!='')
                                        $address_c='<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;width: 100%;clear:both" > <b>  <font style="font-size: 13pt; " face="Times New Roman"  >  <b style="font-size: 13pt; " face="Times New Roman" >'. $address_m. '</b>, </font></b></p>';
                                    $margin_t='';
                                    if($cnt_first_rec==0 || $ext_data!='')
                                        $margin_t="0px";
                                    else
                                        $margin_t="10px";


                                    $tot_records=$tot_records.' <tr> <td style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;font-size: 13pt;width:6%;vertical-align:top;"> <p style="margin:'. $margin_t.' 0px 0px 42px;font-size: 13pt">'.$ss_no.'</p></td><td style="width:49%">'.$ext_data.'<p style="color: #000000;margin:'. $margin_t.' 0px 0px 0px;padding: 0px 2px 0px 2px;width: 100%;float:left;clear:both;" > <b> <font style="font-size: 13pt; " face="Times New Roman"  >'.$h_c_rg.$tw_sn_to.'</b>, </p> ';

 
                                    if($row1['sr_no']!='0')
                                    {
                                        $row1['sr_no']='['.$row1['sr_no'].']';
                                    }
                                    else {
                                        $row1['sr_no']='';
                                    }
                                    if($row1['pet_res']!='')
                                        $row1['pet_res']=' for '.$row1['pet_res'];
                                    else
                                        $row1['pet_res']='';


                                    $tot_records=$tot_records.$address_c;
                                    $tot_records=$tot_records.' <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 2px;width: 100%;float: left;clear:both"><b> <font  style="font-size: 13pt; " face="Times New Roman" >   District- <b style="font-size: 13pt; " face="Times New Roman" >' .  strtoupper(trim($district_nm)).', '.$state_nm.$pin_code.'</b></font></b> </p>'.$fir_dls.$dis_det.$ref_det.'</td> <td>';

                                    $caseno_='';
                                    if($case_range!='')
                                    {
                                        if($res_fil_det['short_description']!='')
                                        {  
                                            $caseno_= $caseno_.$res_fil_det['short_description']; 
                                        }
                                        else $caseno_=$caseno_. "Diary No. ";
                                            $caseno_=$caseno_.$case_range.'/'.$reg_year; 
                                    }

                                    //     $tot_records=$tot_records.'<p style="color: #000000;margin:'. $margin_t.'0px 0px 0px;padding: 0px 30px 0px 2px;float:left" >  <b> <font style="font-size: 13pt; " face="Times New Roman"  >PId: '.$row1['process_id'].$row1[pet_res].$row1[sr_no].$in_reg_cases.' (Sec '.get_section($dairy_no).')('.$caseno_.')</b></p></td></tr><tr style="width: 100%;"><td colspan="3" /></tr>';
                                    $tot_records=$tot_records.'<p style="color: #000000;margin:'. $margin_t.'0px 0px 0px;padding: 0px 30px 0px 2px;float:left" > <b> <font style="font-size: 13pt; " face="Times New Roman"  >PId: '.$row1['process_id'].$row1['pet_res'].$row1['sr_no'].' in '.$in_reg_cases.$caseno_.' (Sec '.get_section($dairy_no).')</b></p></td></tr><tr style="width: 100%;"><td colspan="3" /></tr>';
                                    $cnt_first_rec++;
                                    $ex_del_type=$row1['del_type'];

                                    $ss_no++;
                                }
                                //$tot_records=$tot_records.' Case No.';
                                $tot_records=$tot_records.'</table></div>';
                            }
                            
                            /* $mul_send_tp1="SELECT distinct tw_sn_to, sendto_state, sendto_district, send_to_type,del_type
                                FROM tw_tal_del z
                                JOIN tw_o_r a ON z.id = a.tw_org_id
                                JOIN tw_comp_not b ON a.id = b.tw_o_r_id
                                join tw_notice tn on tn.id=z.nt_type and tn.display='Y' and  	
                                war_notice!='L'
                                WHERE a.display = 'Y'
                                AND z.display = 'Y'

                                AND diary_no = '$dairy_no'
                                AND rec_dt = '$_REQUEST[dt]'
                                AND print = '0'
                                AND b.display = 'Y' and nt_type='$row[nt_type]'
                                AND copy_type =1
                                AND del_type = '$del_type[$dtc]'";
                                $mul_send_tp1=  mysql_query($mul_send_tp1) or die("Error: ".__LINE__.mysql_error()); */

                            $mul_send_tp1 = $noticesModel->getMulSendTp1($diary_no, $_REQUEST['dt'], $row['nt_type'], $del_type, $dtc);
                            if(!empty($mul_send_tp1))
                            {
                                $tot_copy_send_to='';
                                $tot_copy_send_to_adv='';
                                foreach ($mul_send_tp1 as $row11) 
                                {
                                    if($row11['send_to_type']=='1')
                                        $advocate_nm1= send_to_advocate($row11['tw_sn_to']);
                                    else if($row11['send_to_type']=='2')
                                        $advocate_nm1=  send_to_nm($row11['tw_sn_to']);
                                    else if($row11['send_to_type']=='3')
                                        $advocate_nm1=  send_to_court($row11['tw_sn_to']);

                                    $state_nm_c=state_name($row11['sendto_state']);
                                    $district_nm_c=state_name($row11['sendto_district']);
                                    if($tot_copy_send_to=='')
                                        $tot_copy_send_to=$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c;
                                    else
                                        $tot_copy_send_to=$tot_copy_send_to.'@'.$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c;

                                    if($row['nt_type']=='57' && $row11['send_to_type']=='1')
                                    {
                                       /* $get_parties="Select group_concat(concat(pet_res,'[',pet_res_no,']')) from advocate where   	
                                            diary_no='$dairy_no' and display='Y' and advocate_id='$row11[tw_sn_to]'";
                                        $get_parties=  mysql_query($get_parties) or die("Error: ".__LINE__.mysql_error());
                                        $r_get_parties=  mysql_result($get_parties, 0); */
                                        $r_get_parties =  $noticesModel->getPetitioners($diary_no, $row11['tw_sn_to']);
                                        if($tot_copy_send_to_adv=='')
                                            $tot_copy_send_to_adv=$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c.'!'.' for '.$r_get_parties;
                                        else
                                            $tot_copy_send_to_adv=$tot_copy_send_to_adv.'@'.$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c.'!'.' for '.$r_get_parties;
                                    }
                                }
                            }

                            if($tot_copy_send_to!='')
                            {
                                $c_sno=1;
                                $tot_copy='';
                                $ex_c_s_t=explode('@',$tot_copy_send_to);
                                $tot_copy=$tot_copy.'<div style="margin-left: 30px"><table>';
                                for ($index = 0; $index < count($ex_c_s_t); $index++) 
                                {
                                    $ex_explode=explode('!',$ex_c_s_t[$index]);
                                    $tot_copy=$tot_copy.'<tr><td style="font-size: 13pt;vertical-align: top">'.$c_sno; $tot_copy=$tot_copy.'</td>
                                    <td >
                                        <div style="font-size: 13pt; " face="Times New Roman" >';
                                        $ex_exp=  explode('~', $ex_explode[0]);
                                        // $ex_exp1 = ($ex_exp[1]) ?? '';
                                        // $ex_exp2 = ($ex_exp[2]) ?? '';
                                        $tot_copy=$tot_copy. (isset($ex_exp[1]) ? $ex_exp[1] : '').' '. ucwords(strtolower($ex_exp[0])).' '.(isset($ex_exp[2]) ? ucwords(strtolower($ex_exp[2])) : '');
                                        $tot_copy=$tot_copy.'</div>
                                        <div style="font-size: 13pt; " face="Times New Roman" >'.ucwords(strtolower($ex_explode[2])).', '.ucwords(strtolower($ex_explode[1])).'</div></td>
                                    </tr>';
                                    $c_sno++;

                                }  
                                $tot_copy=$tot_copy.'</table></div>';

                            }
                            else
                            {
                                $tot_copy='';
                            }

                            if(isset($ex_del_type) &&  $ex_del_type=='H')
                            {
                                if($notice_type != '63')
                                {
                                    $tot_copy = $tot_copy.'<p style="font-size: 13pt; " face="Times New Roman" > (*Copies of dasti notice are enclosed herewith. You are requested to file affidavit of service forthwith.) </p>';
                                }
                            }
                        }


                        $v_cx++;
                        $data['fn_del_type'] = $del_type[$dtc];
                        $data['row'] = $row;
                        $data['dairy_no'] = $diary_no;
                        $data['res_fil_det'] = $res_fil_det;
                        $data['case_range'] = $case_range;
                        $data['reg_year'] = $reg_year;
                        $data['pno'] = $pno;
                        $data['rno'] = $rno;
                        $data['tot_records'] = ($tot_records) ?? '';
                        $data['tot_copy'] = $tot_copy;
                        $data['ind_org'] = $ind_org;
                        $data['tw_sn_to'] = $tw_sn_to;
                        $data['address_m'] = $address_m;
                        $data['district_nm'] = $district_nm;
                        $data['state_nm'] = $state_nm;
                        $data['tot_copy_send_to'] = $tot_copy_send_to;
                        $data['doc_names'] = '';
                       

                        switch ($n_ind_d)
                        {
                            case 1:
                                echo view('NoticeType/SLP(C)/returnable_notice.php', $data);
                                break;
                            case 2:
                                echo view('NoticeType/SLP(Crl)/2.php', $data);
                                break;
                            case 3:
                                // include ('office_report/criminal/after_notice.php');
                                echo view('office_report/criminal/after_notice.php', $data);
                                break;
                            case 4:
                                echo view('NoticeType/SLP(Crl)/4.php', $data);
                                break;
                            case 5:
                                echo view('NoticeType/SLP(Crl)/5.php', $data);
                                break;
                            case 6:
                                echo view('NoticeType/SLP(Crl)/6.php', $data);
                                break;
                            case 7:
                                echo view('NoticeType/SLP(C)/7.php', $data);
                                break;
                            case 8:
                                echo view('NoticeType/SLP(C)/8.php', $data);
                                break;
                            case 9:
                                echo view('NoticeType/SLP(C)/9.php', $data);
                                break;
                            case 10:
                                echo view('NoticeType/SLP(C)/10.php', $data);
                                break;
                            case 11:
                                echo view('NoticeType/SLP(C)/11.php', $data);
                                break;
                            case 12:
                                echo view('NoticeType/SLP(C)/12.php', $data);
                                break;
                            case 13:
                                echo view('NoticeType/SLP(C)/13.php', $data);
                                break;
                            case 14:
                                echo view('NoticeType/SLP(C)/14.php', $data);
                                break;
                            case 15:
                                echo view('NoticeType/SLP(C)/15.php', $data);
                                break;
                            case 16:
                                echo view('NoticeType/SLP(C)/16.php', $data);
                                break;
                            case 17:
                                echo view('NoticeType/SLP(C)/17.php', $data);
                                break;
                            case 18:
                                echo view('NoticeType/SLP(C)/18.php', $data);
                                break;
                            case 19:
                                echo view('NoticeType/SLP(C)/19.php', $data);
                                break;
                            case 20:
                                echo view('NoticeType/SLP(C)/20.php', $data);
                                break;
                            case 21:
                                echo view('NoticeType/SLP(Crl)/21.php', $data);
                                break;
                            case 22:
                                echo view('NoticeType/SLP(Crl)/22.php', $data);
                                break;
                            case 23:
                                echo view('NoticeType/SLP(Crl)/23.php', $data);
                                break;
                            case 24:
                                echo view('NoticeType/SLP(Crl)/24.php', $data);
                                break;
                            case 25:
                                echo view('NoticeType/SLP(Crl)/25.php', $data);
                                break;
                            case 26:
                                echo view('NoticeType/SLP(Crl)/26.php', $data);
                                break;
                            case 27:
                                echo view('NoticeType/SLP(Crl)/27.php', $data);
                                break;
                            case 28:
                                echo view('NoticeType/SLP(Crl)/28.php', $data);
                                break;
                            case 29:
                                echo view('NoticeType/SLP(Crl)/29.php', $data);
                                break;
                            case 30:
                                echo view('NoticeType/SLP(Crl)/30.php', $data);
                                break;
                            case 31:
                                echo view('NoticeType/SLP(Crl)/31.php', $data);
                                break;
                            case 32:
                                echo view('NoticeType/SLP(Crl)/32.php', $data);
                                break;
                            case 33:
                                echo view('NoticeType/SLP(Crl)/33.php', $data);
                                break;
                            case 34:
                                echo view('NoticeType/SLP(Crl)/34.php', $data);
                                break;
                            case 35:
                                echo view('NoticeType/SLP(Crl)/35.php', $data);
                                break;
                            case 36:
                                echo view('NoticeType/SLP(Crl)/36.php', $data);
                                break;
                            case 37:
                                echo view('NoticeType/SLP(Crl)/37.php', $data);
                                break;
                            case 38:
                                echo view('NoticeType/SLP(Crl)/38.php', $data);
                                break;
                            case 39:
                                echo view('NoticeType/SLP(Crl)/39.php', $data);
                                break;
                            case 40:
                                echo view('NoticeType/SLP(C)/40.php', $data);
                                break;
                            case 41:
                                echo view('NoticeType/SLP(C)/41.php', $data);
                                break;
                            case 42:
                                echo view('NoticeType/SLP(C)/42.php', $data);
                                break;
                            case 43:
                                echo view('NoticeType/SLP(Crl)/43.php', $data);
                                break;
                            case 44:
                                echo view('NoticeType/SLP(C)/44.php', $data);
                                break;
                            case 45:
                                echo view('NoticeType/SLP(C)/45.php', $data);
                                break;
                            case 46:
                                echo view('NoticeType/SLP(C)/46.php', $data);
                                break;
                            case 47:
                                echo view('NoticeType/SLP(C)/47.php', $data);
                                break;
                            case 48:
                                echo view('NoticeType/SLP(C)/48.php', $data);
                                break;
                            case 49:
                                echo view('NoticeType/SLP(C)/49.php', $data);
                                break;
                            case 50:
                                echo view('NoticeType/SLP(C)/12.php', $data);
                                break;
                            case 51:
                                echo view('NoticeType/SLP(C)/51.php', $data);
                                break;
                            case 52:
                                echo view('NoticeType/SLP(C)/52.php', $data);
                                break;
                            case 53:
                                echo view('NoticeType/SLP(C)/53.php', $data);
                                break;
                            case 54:
                                echo view('NoticeType/SLP(C)/54.php', $data);
                                break;
                            case 55:
                                echo view('NoticeType/SLP(C)/55.php', $data);
                                break;
                            case 56:
                                echo view('NoticeType/SLP(C)/56.php', $data);
                                break;
                            case 57:
                                echo view('NoticeType/SLP(C)/57.php', $data);
                                break;
                            case 58:
                                echo view('NoticeType/SLP(Crl)/58.php', $data);
                                break;
                            case 59:
                                echo view('NoticeType/SLP(C)/59.php', $data);
                                break;
                            case 60:
                                echo view('NoticeType/SLP(C)/60.php', $data);
                                break;
                            case 61:
                                echo view('NoticeType/SLP(C)/61.php', $data);
                                break;
                            case 62:
                                echo view('NoticeType/SLP(C)/62.php', $data);
                                break;
                            case 63:
                                echo view('NoticeType/SLP(C)/63.php', $data);
                                break;
                            case 64:
                                echo view('NoticeType/SLP(Crl)/2.php', $data);
                                break;
                            case 65:
                                echo view('NoticeType/SLP(C)/65.php', $data);
                                break;
                            case 66:
                                echo view('NoticeType/SLP(Crl)/66.php', $data);
                                break;
                            case 67:
                                echo view('NoticeType/SLP(C)/67.php', $data);
                                break;
                            case 68:
                                echo view('NoticeType/SLP(C)/68.php', $data);
                                break;
                            case 69:
                                echo view('NoticeType/SLP(C)/69.php', $data);
                                break;
                            case 70:
                                echo view('NoticeType/SLP(Crl)/70.php', $data);
                                break;
                            case 71:
                                echo view('NoticeType/SLP(Crl)/71.php', $data);
                                break;
                            case 72:
                                echo view('NoticeType/SLP(C)/72.php', $data);
                                break;
                            case 73:
                                echo view('NoticeType/SLP(C)/73.php', $data);
                                break;
                            case 74:
                                echo view('NoticeType/SLP(Crl)/2.php', $data);
                                break;
                            case 75:
                                echo view('NoticeType/SLP(Crl)/4.php', $data);
                                break;
                            case 76:
                                echo view('NoticeType/SLP(C)/76.php', $data);
                                break;
                            case 77:
                                echo view('NoticeType/SLP(Crl)/77.php', $data);
                                break;
                            case 78:
                                echo view('NoticeType/SLP(Crl)/4.php', $data);
                                break;
                            case 79:
                                echo view('NoticeType/SLP(Crl)/79.php', $data);
                                break;
                            case 80:
                                echo view('NoticeType/SLP(Crl)/80.php', $data);
                                break;
                            case 81:
                                echo view('NoticeType/SLP(Crl)/81.php', $data);
                                break;
                            case 82:
                                echo view('NoticeType/SLP(Crl)/82.php', $data);
                                break;
                            case 83:
                                echo view('NoticeType/SLP(Crl)/83.php', $data);
                                break;
                            case 84:
                                echo view('NoticeType/SLP(Crl)/84.php', $data);
                                break;
                            case 85:
                                echo view('NoticeType/SLP(Crl)/85.php', $data);
                                break;
                            case 86:
                                echo view('NoticeType/SLP(Crl)/86.php', $data);
                                break;
                            case 87:
                                echo view('NoticeType/SLP(Crl)/87.php', $data);
                                break;
                            case 88:
                                echo view('NoticeType/SLP(Crl)/88.php', $data);
                                break;
                            case 89:
                                echo view('NoticeType/SLP(Crl)/89.php', $data);
                                break;
                            case 90:
                                echo view('NoticeType/SLP(Crl)/90.php', $data);
                                break;
                            case 91:
                                echo view('NoticeType/SLP(Crl)/91.php', $data);
                                break;
                            case 92:
                                echo view('NoticeType/SLP(Crl)/92.php', $data);
                                break;
                            case 93:
                                echo view('NoticeType/SLP(Crl)/93.php', $data);
                                break;
                            case 94:
                                echo view('NoticeType/SLP(Crl)/94.php', $data);
                                break;
                            case 95:
                                echo view('NoticeType/SLP(Crl)/95.php', $data);
                                break;
                            case 96:
                                echo view('NoticeType/SLP(Crl)/96.php', $data);
                                break;
                            case 97:
                                echo view('NoticeType/SLP(Crl)/97.php', $data);
                                break;
                            case 98:
                                echo view('NoticeType/SLP(Crl)/98.php', $data);
                                break;
                            case 99:
                                echo view('NoticeType/SLP(C)/99.php', $data);
                                break;
                            case 100:
                                echo view('NoticeType/SLP(C)/100.php', $data);
                                break;
                            case 101:
                                echo view('NoticeType/SLP(C)/101.php', $data);
                                break;
                            case 102:
                                echo view('NoticeType/SLP(C)/102.php', $data);
                                break;
                            case 103:
                                echo view('NoticeType/SLP(C)/103.php', $data);
                                break;
                            case 104:
                                echo view('NoticeType/SLP(C)/104.php', $data);
                                break;
                            case 105:
                                echo view('NoticeType/SLP(C)/105.php', $data);
                                break;
                            case 106:
                                echo view('NoticeType/SLP(C)/106.php', $data);
                                break;
                            case 107:
                                echo view('NoticeType/SLP(C)/107.php', $data);
                                break;
                            case 108:
                                echo view('NoticeType/SLP(C)/108.php', $data);
                                break;
                            case 109:
                                echo view('NoticeType/SLP(C)/109.php', $data);
                                break;
                            case 110:
                                echo view('NoticeType/SLP(C)/110.php', $data);
                                break;
                            case 111:
                                echo view('NoticeType/SLP(C)/111.php', $data);
                                break;
                            case 112:
                                echo view('NoticeType/SLP(C)/112.php', $data);
                                break;
                            case 113:
                                echo view('NoticeType/SLP(C)/113.php', $data);
                                break;
                            case 114:
                                echo view('NoticeType/SLP(C)/114.php', $data);
                                break;
                            case 115:
                                echo view('NoticeType/SLP(C)/115.php', $data);
                                break;
                            case 116:
                                echo view('NoticeType/SLP(C)/116.php', $data);
                                break;
                            case 117:
                                echo view('NoticeType/SLP(C)/returnable_notice.php', $data);
                                break;
                            case 118:
                                echo view('NoticeType/SLP(C)/118.php', $data);
                                break;
                            case 119:
                                echo view('NoticeType/SLP(C)/119.php', $data);
                                break;
                            case 120:
                                echo view('NoticeType/SLP(Crl)/120.php', $data);
                                break;
                            case 121:
                                echo view('NoticeType/SLP(Crl)/121.php', $data);
                                break;
                            case 122:
                                echo view('NoticeType/SLP(C)/122.php', $data);
                                break;
                            case 123:
                                echo view('NoticeType/SLP(Crl)/123.php', $data);
                                break;
                            case 124:
                                echo view('NoticeType/SLP(C)/124.php', $data);
                               break;
                            case 125:
                                echo view('NoticeType/SLP(C)/125.php', $data);
                               break;
                            case 126:
                                echo view('NoticeType/SLP(C)/126.php', $data);
                                break;
                            case 127:
                                echo view('NoticeType/SLP(C)/127.php', $data);
                                break;                                
                            case 128:
                                echo view('NoticeType/SLP(C)/128.php', $data);
                                break;
                            case 129:
                                echo view('NoticeType/SLP(C)/11.php', $data);
                                break;                                                                
                            case 130:
                                echo view('NoticeType/SLP(C)/130.php', $data);
                                break;
                            case 131:
                                echo view('NoticeType/SLP(C)/131.php', $data);
                                break;
                            case 132:
                                echo view('NoticeType/SLP(C)/132.php', $data);
                                break;
                            case 133:
                                echo view('NoticeType/SLP(C)/133.php', $data);
                                break;
                            case 134:
                                echo view('NoticeType/SLP(C)/122.php', $data);
                                break; 
                            case 135:
                                echo view('NoticeType/SLP(C)/135.php', $data);
                                break;
                            case 136:
                                echo view('NoticeType/SLP(C)/135.php', $data);
                                break;
                            case 137:
                                echo view('NoticeType/SLP(C)/136.php', $data);
                                break;
                            case 138:
                                echo view('NoticeType/SLP(C)/136.php', $data);
                                break;
                            case 139:
                                echo view('NoticeType/SLP(C)/139.php', $data);
                                break;
                            case 140:
                                echo view('NoticeType/SLP(C)/140.php', $data);
                                break;
                            case 141:
                                echo view('NoticeType/SLP(C)/141.php', $data);
                                break;
                            case 142:
                                echo view('NoticeType/SLP(C)/142.php', $data);
                                break;
                            case 143:
                                echo view('NoticeType/SLP(C)/141.php', $data);
                                break;
                            case 144:
                                echo view('NoticeType/SLP(C)/144.php', $data);
                                break;
                            case 145:
                                echo view('NoticeType/SLP(C)/145.php', $data);
                                break;
                            case 146:
                                echo view('NoticeType/SLP(Crl)/96.php', $data);
                                break;
                            case 147:
                                echo view('NoticeType/SLP(C)/147.php', $data);
                                break;
                            case 148:
                                echo view('NoticeType/SLP(C)/148.php', $data);
                                break;
                            case 149:
                                echo view('NoticeType/SLP(Crl)/149.php', $data);
                                break;
                            case 150:
                                echo view('NoticeType/SLP(Crl)/150.php', $data);
                                break;
                            case 151:
                                echo view('NoticeType/SLP(Crl)/151.php', $data);
                                break;
                            case 152:
                                echo view('NoticeType/SLP(Crl)/152.php', $data);
                                break;
                            case 153:
                                echo view('NoticeType/SLP(Crl)/153.php', $data);
                                break;
                            case 154:
                                echo view('NoticeType/SLP(Crl)/154.php', $data);
                                break;
                            case 155:
                                echo view('NoticeType/SLP(C)/155.php', $data);
                                break;
                            case 156:
                                echo view('NoticeType/SLP(C)/156.php', $data);
                                break;
                            case 157:
                                echo view('NoticeType/SLP(Crl)/157.php', $data);
                                break;
                            case 160:
                                echo view('NoticeType/SLP(C)/160.php', $data);
                                break;
                            case 161:
                                echo view('NoticeType/SLP(Crl)/161.php', $data);
                                break;
                            case 166:
                                echo view('NoticeType/SLP(C)/166.php', $data);
                                break;
                            case 167:
                                echo view('NoticeType/SLP(C)/167.php', $data);
                                break;
                            case 168:
                                echo view('NoticeType/SLP(Crl)/168.php', $data);
                                break;
                            default:
                                break;
                        }

                        ?>
                        <div style="clear: both" id="qr_<?php echo $row['id'] ?>_<?php echo $n_ind_d; ?>_<?php echo $del_type[$dtc]; ?>"></div>
                    </div>
                    <?php
                    $ck_pbb++;
                    $ct_del_type++;

                    if($ck_mul_ind==$sql_res_rw)
                    {
                        break;
                    }
                }
            }

            ?>
            <!-- </div>   -->
            <?php

        }

        ?>
        <input type="hidden" name="hd_tot_po" id="hd_tot_po" value="<?php echo $v_cx; ?>"/>
        <?php
    }
    else
    {     
        $fil_nm= trim($_REQUEST['fil_nm'],' ');
        $ds=fopen($fil_nm, 'r');
       $b_z= fread($ds, filesize($fil_nm) );
       fclose($ds);
       echo utf8_encode($b_z);
    }