<?php
 
 $ucode = session()->get('login')['usercode'];

     $row_type = is_data_from_table('master.users', " usercode=$ucode ", 'usertype','');
 
     $utype=$row_type['usertype'];
  

    if(($ucode !=1) && ($utype!=14))
    {
         $user_code=" and a.user_id='$ucode'";
    }
     
     $cl_date= trim($_REQUEST['cl_date']);
     $date1 = strtotime($cl_date);
     $list_type=trim($_REQUEST['list_type']);
     $sorting=trim($_REQUEST['sort']);
     if($sorting=='diary_no')

      {      
      $sorting= " CAST(SUBSTRING(a.diary_no::TEXT FROM LENGTH(a.diary_no::TEXT) - 3 FOR 4) AS INTEGER), 
          CAST(SUBSTRING(a.diary_no::TEXT FROM 1 FOR LENGTH(a.diary_no::TEXT) - 4) AS INTEGER) ";

      }
      if($sorting=='docnum')
      {
          $sorting= "docyear,docnum";
      }
      if($sorting=='active_fil_no')
      {
        $sorting="active_casetype_id,active_reg_year,active_fil_no";
      }
 
      $ct = '';
    if($list_type==1)
    {
        if(($ucode !=1) && ($utype!=14))            
        {
             

            $row_matters = is_data_from_table('master.godown_user_allocation', " usercode=$ucode ", " STRING_AGG(casetype_id::TEXT || caseyear::TEXT, ', ') AS ct ",'');
            $ct=$row_matters['ct'];
          
        }
        
        $serve_status = $paperModal->getServeStatus($ucode, $utype, $cl_date, $ct, $sorting);
      
        if(!empty($serve_status)) {

            ?>
            <input type="button" onclick="printDiv('r')" value="print " />
 <div id ="r">
<CENTER> List of  matters <b>Fresh</b> for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">
                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">IA No.</td>                     
                </tr>

                <?php

                $sno = 1;

                foreach($serve_status as $ro){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                       
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];
              $IA=$ro['IA'] ?? '';



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    

                    $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) {
                        
                        $radvname=  $rowadv["r_n"] ?? '';
                        $padvname=  $rowadv["p_n"] ?? '';
                        $impldname = $rowadv["i_n"] ?? '';
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];
 

                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){
                           
                            $ro['section_name']=$section_ten_row["section_name"] ?? '';
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                      <td align="left" style='vertical-align: top;'><?php echo $IA; ?></td>
       
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==2)
    {
        if(($ucode !=1) && ($utype!=14))
            
        {
              

            $row_matters = is_data_from_table('master.godown_user_allocation', " usercode=$ucode ", " STRING_AGG(casetype_id::TEXT || caseyear::TEXT, ', ') AS ct ",'');
            $ct=$row_matters['ct'];
 
        
        }
 
 
        $serve_status = $paperModal->getServeStatus($ucode, $utype, $cl_date, $ct, $sorting);
        if(!empty($serve_status)) {

            ?>
            <input type="button" onclick="printDiv('r')" value="print " />
 <div id ="r">
<CENTER> List of  matters <b>except fresh</b> for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table  class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">IA No.</td>
                     <!--<td width="20%" style="font-weight: bold; color: #dce38d;">Purpose.</td>-->
                </tr>

                <?php

                $sno = 1;

                foreach($serve_status as $ro){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{                         
                        $fil_no_print = $ro['reg_no_display'];
                    }

                    $purpose = $ro['purpose'];
                    $IA=$ro['IA'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }

                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    

                    $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) {
                       
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];
 

                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){                           
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                      <td align="left" style='vertical-align: top;'><?php echo $IA; ?></td>       
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==3)
    {
        if(($ucode !=1) && ($utype!=14))            
        {   
            $row_matters = $paperModal->getMatters($ucode);
            $ct=$row_matters['ct'];
            if($ct==null)
            {
                echo " NO Fresh  Matters  ";
                exit();

            }
        } 

        $serve_status = $paperModal->getServeStatusType3($cl_date, $sorting);
        if(!empty($serve_status)) {

            ?>
            <input type="button" onclick="printDiv('r')" value="print " />
 <div id ="r">
 <CENTER> Consolidated List of  matters  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">
                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>                     
                </tr>

                <?php

                $sno = 1;

                foreach($serve_status as $ro){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                      
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";                 

                     $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) {
                       
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];
                     

                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){
                            $ro['section_name']=$section_ten_row["section_name"] ?? '';
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
         
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==4)
    {
        if(($ucode !=1) && ($utype!=14))            
        {
            $row_matters = $paperModal->getMatters($ucode);
            $ct=$row_matters['ct'];
            if($ct==null)

            {
                echo " NO Fresh  Matters  ";
                exit();

            }
        }
        else
        {
             if(!empty($ma) &&  $ma==1)

                  {
                     $ct='2,4,6,8,12,14,33,35,41,10,20,26,39';
                   }
              else
               {

                    $ct='2,4,6,8,12,14,33,35,41,39';
               }
        }
        
          
        $serve_status = $paperModal->getServeStatusType4($cl_date, $sorting);
        if(!empty($serve_status)) {

            ?>
<input type="button" onclick="printDiv('r')" value="print " />
<div id ="r">
<CENTER> Consolidated List of <b> Review/Curative/Contempt matters</b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                  
                </tr>

                <?php

                $sno = 1;

                foreach($serve_status as $ro){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                       
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";                

                    $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) {                  
                       
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){                         
                            $ro['section_name']=$section_ten_row["section_name"] ?? '';
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>       
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }   // end of if


    if($list_type==5)
    {    
        
         $serve_status =  $paperModal->getServeStatusType5($cl_date, $sorting);
       
        if(!empty($serve_status)) {

            ?>
<input type="button" onclick="printDiv('r')" value="print " />
          <div id ="r">
              <CENTER> Consolidated List of  <b>Unallocated matters </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    
                </tr>

                <?php

                $sno = 1;

                foreach($serve_status as $ro){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{                        
                        $fil_no_print = $ro['reg_no_display'];
                    }
                    $purpose = $ro['purpose'];

                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                
                     $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) { 
                        
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];
                    
                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){
                         
                            $ro['section_name']=$section_ten_row["section_name"] ?? '';
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                    <!-- <td align="left" style='vertical-align: top;'><?php //echo $purpose; ?></td>-->
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
        </div>
        <?php
    }
//    code for other diary matters


     if($list_type==6)
     {      
 

        $serve_status = $paperModal->getServeStatusType6($cl_date, $sorting);
        
         if(!empty($serve_status))
           {
             ?>

                    <input type="button" onclick="printDiv('r')" value="print " />

                       <div id ="r">
                       <CENTER> Consolidated List of  <b>Diary Matters- civil </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>


                       <BR>
             <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

<tr style="background: #918788;">

    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>

    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
    <td width="20%" style="font-weight: bold; color: #dce38d;">IA. No.</td>

</tr>

<?php

      $sno = 1;

        foreach($serve_status as $ro){
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            if($ro['active_fil_dt'] != '')
                $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
            else
                $active_fil_dt = "";
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if($conn_no == $dno){
                $m_c = "Main";
            }
            if($conn_no != $dno AND $conn_no > 0){
                $m_c = "Conn.";
            }
            $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
            $filno_array = explode("-",$ro['active_fil_no']);

            if(empty($ro['reg_no_display'])){

                $fil_no_print = "Unregistred";
            }
            else{ 
                $fil_no_print = $ro['reg_no_display'];
            }


           if($sno1 == '1'){ ?>
            <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
            <?php } else { ?>
            <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
            <?php
            }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                     $padvname = ""; $radvname = ""; $impldname= "";
  
                    
                    $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) { 
                        
                                  $radvname=  $rowadv["r_n"];
                                  $padvname=  $rowadv["p_n"];
                                  $impldname = $rowadv["i_n"];
                       
                    }


 if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                    if($ro['active_reg_year']!=0)
    $ten_reg_yr = $ro['active_reg_year'];
else
    $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

if($ro['active_casetype_id']!=0)
    $casetype_displ = $ro['active_casetype_id'];
else if($ro['casetype_id']!=0)
    $casetype_displ = $ro['casetype_id'];
 

                        $section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
                        if(!empty($section_ten_row)){                           
                            $ro['section_name']=$section_ten_row["section_name"] ?? '';
                        }
}




            ?>
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->

            <td align="left" style='vertical-align: top;'><?php echo $ro['docnum']."-".$ro['docyear'] ?></td>

            </tr>

    <?php
            $sno++;
        }
        ?>
    </table>
                <?php
    }
    else{
        echo "No Records Found";
    }
    ?>
<BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
</div>
</div>

<?php
}
if($list_type==7)
     {   

         $serve_status =  $paperModal->getServeStatusType7($cl_date, $sorting);
         
         if(!empty($serve_status))
           {
             ?>

                   <input type="button" onclick="printDiv('r')" value="print " />

                       <div id ="r">
                         <CENTER> Consolidated List of  <b>Diary Matters- criminal </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>


                       <BR>
             <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

<tr style="background: #918788;">

    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>

    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
    <td width="20%" style="font-weight: bold; color: #dce38d;">IA. No.</td>

</tr>

<?php

      $sno = 1;

        foreach($serve_status as $ro){
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            if($ro['active_fil_dt'] != '')
                $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
            else
                $active_fil_dt = "";
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if($conn_no == $dno){
                $m_c = "Main";
            }
            if($conn_no != $dno AND $conn_no > 0){
                $m_c = "Conn.";
            }
            $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
            $filno_array = explode("-",$ro['active_fil_no']);

            if(empty($ro['reg_no_display'])){

                $fil_no_print = "Unregistred";
            }
            else{
 
                $fil_no_print = $ro['reg_no_display'];
            }


           if($sno1 == '1'){ ?>
            <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
            <?php } else { ?>
            <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
            <?php
            }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                     $padvname = ""; $radvname = ""; $impldname= "";  

                    $rowadv  =  $paperModal->getAdvocatesByDiary($ro["diary_no"]);
                    if(!empty($rowadv)) {                        
                                  $radvname=  $rowadv["r_n"];
                                  $padvname=  $rowadv["p_n"];
                                  $impldname = $rowadv["i_n"];                    
                    }


 if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                    if($ro['active_reg_year']!=0)
    $ten_reg_yr = $ro['active_reg_year'];
else
    $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

if($ro['active_casetype_id']!=0)
    $casetype_displ = $ro['active_casetype_id'];
else if($ro['casetype_id']!=0)
    $casetype_displ = $ro['casetype_id'];
 

$section_ten_row = $paperModal->getSectionTenData($casetype_displ, $ten_reg_yr, $ro['ref_agency_state_id']);
if(!empty($section_ten_row)){    
    $ro['section_name']=$section_ten_row["section_name"];
}
}




            ?>
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->

            <td align="left" style='vertical-align: top;'><?php echo $ro['docnum']."-".$ro['docyear'] ?></td>

            </tr>

    <?php
            $sno++;
        }
        ?>
    </table>
                <?php
    }
    else{
        echo "No Records Found";
    }
    ?>
<BR/><BR/><BR/><BR/> 
</div>
</div>

<?php
}
 
?>
