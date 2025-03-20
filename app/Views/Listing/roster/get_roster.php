<?php
 
    function DateToDMY($str)  {
        $day = substr($str, 8, 2);
        $month = substr($str, 5, 2);
        $year = substr($str, 0, 4);
        return $day . "-" . $month . "-" . $year;
     }

    static $ct_total = 0;
 
	 
    ///$rs = mysql_query($sql) or die("Error: ".__LINE__.mysql_error());
    if (!empty($rs))  {
        $str_to_display = "<table border='0' width='100%' style='font-size: 110%;'>";
        $bench_name_rep = '0';
        $ck_cl_open_dt = '';
        $jud_open_dt_jud = '';
        $ck_close_button = '';

        foreach ($rs as $row)  {

            $id = $row['id'];
            $from_date = (!empty($row['from_date'])) ? date('d-m-Y',strtotime($row['from_date'])) : '';
            $too_dta = $row['to_date'];
            $too_dt = '';
            if ($too_dta == '')  {
                $ck_cl_open_dt = '';
                $jud_open_dt_jud = '';
                $too_dt = '';
                $ck_close_button = "style='display:inline'";
             }  else  {
//            $ck_cl_open_dt='disabled=true';
//             $jud_open_dt_jud=';color:red';
                $too_dt = (!empty($too_dta)) ? date('d-m-Y',strtotime($too_dta)) : ''; 
                $ck_close_button = "style='display:none'";
             }
            //$nature = $row['nature'];
//        $sess_no = $row['sess_no'];       
            $bench_name = $row['bench_name'];
            $abbr = $row['abbr'];
            $bench_no = $row['bench_no'];


            if ($bench_name != $bench_name_rep)  {
//                $str_to_display .= "<tr><td align='center' colspan='2'><br/><b><u>" . $bench_name . "ES</u></b><br/></td></tr>";
                 $str_to_display .= "<tr><td align='center' colspan='2'><br/><b><u> ROSTER</u></b><br/></td></tr>";
             }
            $bench_name_rep = $bench_name;
            if ($row['m_f'] == '1')  {
                $m_f_all = 'M';
             }  else if ($row['m_f'] == '2')  {
                $m_f_all = 'F';
             }  else if ($row['m_f'] == '3')  {
                $m_f_all = 'L';
             }  else if ($row['m_f'] == '4')  {
                $m_f_all = 'M';
             }
         ?>
<input type="hidden" name="hd_roster_id<?php echo $ct_total; ?>" id="hd_roster_id<?php echo $ct_total; ?>" value="<?php echo $id; ?>"/>
<?php
            
            $str_to_display .= "
           
<tr><td><table id=trPrint_" . $ct_total . " width='100%'><tr><td width='23%' style='text-align:left;'><b>" . "(<span id=sp_bn_r" . $ct_total . ">" . $abbr . "-" . $bench_no . "</span>)&nbsp;&nbsp;" . $from_date . '<br/><span style="color:red">' . $too_dt . '</span> &nbsp;&nbsp;' . $m_f_all . '<br/>' . $row['session'] . '<br/>' . $row['frm_time'] . '<br/> E dt. ' . $row['entry_dt']. '<br/>' .'<input type="text" name="ros_cases' . $ct_total . '" id="ros_cases' . $ct_total . '" size="4" maxlength="4" onkeypress="return OnlyNumbersTalwana(event,this.id)" value="' . $row['tot_cases'] . '" style="display:none" class="form-control"/><br/><input type="text" name="txt_court_no' . $ct_total . '" id="txt_court_no' . $ct_total . '" size="5" placeholder="Court No." value="' . $row['courtno'] . '" class="form-control" /><input type="button" name="btn_ros_cases' . $ct_total . '" id="btn_ros_cases' . $ct_total . '"  value="Court" class="btn btn-primary" onclick="updcases(this.id)"/>';

 $str_to_display .= '<br/><input type="text" name="txt_rtime' . $ct_total . '" id="txt_rtime' . $ct_total . '" size="5" placeholder="Time" value="' . $row['frm_time'] . '" class="form-control"/><input type="button" class="btn btn-primary" name="btn_ros_time' . $ct_total . '" id="btn_ros_time' . $ct_total . '"  value="Time" onclick="updttime(this.id)"/>';

 $selected="";

 if($row['if_print_in']==1){
     $selected="selected=\"selected\"";
 }


            $str_to_display .= '<br/>                   
                         <select id="printInBeforeCourt' . $ct_total . '" name="printInBeforeCourt' . $ct_total . '" class="form-control">
                             <option value="0">No</option>
                             <option value="1" '.$selected.'>YES</option>
                         </select>
                         <input type="button" class="btn btn-primary" name="btnPrintInBeforeCourt' . $ct_total . '" id="btnPrintInBeforeCourt' . $ct_total . '"  value="Print IN before Court No." onclick="updatePrintInBeforeCourt(this.id)"/>';
            $str_to_display .= "</b></td><td width='75%' style='text-align:left;'><b><span id='spcatall_$ct_total' class='cp_spcatall' onclick='show_hide_dt(this.id)' style='font-size:12px" . $jud_open_dt_jud . "'>";
            
            if($row['judge_id'])
            {
                $ex_judge = explode(',', $row['judge_id']);
                // pr($ex_judge);
                $mul_jud = '';
                for ($z = 0; $z < count($ex_judge); $z++)  {
                    $judge1_id = $ex_judge[$z];
                    /*  $sql_judg1 = "SELECT if(jtype = 'R', concat(first_name,' ',sur_name,' ',jname),jname) jname FROM judge WHERE jcode='$judge1_id' AND display='Y'";
                    
                    $rs_sess1 = mysql_query($sql_judg1) or die("Error: ".__LINE__.mysql_error());
                    */
                    
                    $rs_sess1 = is_data_from_table('master.judge',  " jcode=$judge1_id AND display='Y' ", " CASE 
                            WHEN jtype = 'R' THEN concat(first_name, ' ', sur_name, ' ', jname) 
                            ELSE jname 
                        END AS jname ", 'A');
                    if(!empty($rs_sess1))
                    {
                        foreach ($rs_sess1 as $row_sess1)  {
                            if ($mul_jud == '')
                                $mul_jud = $row_sess1['jname'];
                            else
                                $mul_jud = $mul_jud . ' and ' . $row_sess1['jname'];
                        }
                    }
                }
                $str_to_display.=$mul_jud;
            }
            //  $matter_ids = explode(',', $row['matter_ids']);
       
            $matter_ids = explode(',', $row['sc']);
            $ex_stage_nature = explode(',', $row['stage_nature']);
            $ex_case_type = explode(',', $row['case_type']);
           /*  $ex_cat1 = explode(',', $row['cat1']);
            $ex_cat2 = explode(',', $row['cat2']);
            $ex_cat3 = explode(',', $row['cat3']); */
            
             $ex_submaster_id = explode(',', $row['submaster_id']);

            $ex_b_n = explode(',', $row['b_n']);
            //count how many commas existed in $matter_ids
            $commaCount = count($matter_ids);
//            display:none;border-collapse:collapse
            $str_to_display .= "</span></b><table width='99%' border='1' align='left' style='font-size: 110%;display:none' id='tb_cat_all_$ct_total' class='cp_spcatall1 table_tr_th_w_clr c_vertical_align'>";
            $sno = 1;
            $sss = 0;
            // $in_ct_total=0;
           
            $str_to_display .= "</table>";
            $str_to_display .= "<br/></td>";
            $str_to_display .= "</tr></table></td></tr>";
            $ct_total++;
         }
        $str_to_display .= "</table>";
        ?>
        <div id="dv_print">
            <center><u><h3>SUPREME COURT OF INDIA</h3></u>
                <br/>    
                <div align="center"><b><u>NOTIFICATION</u></b></div>

                <div style="text-align:left; ">No. ........... 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Dated , .............
                </div>
                <br/>
                <div align="center"><b><u>ROSTER/ASSIGNMENT FROM <?php echo $from_date; ?> TILL FURTHER ORDERS</u></b></div>


                <br/>
                <div style="text-align: justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    In supersession of all earlier Orders/Notifications, Hon'ble the Chief Justice has been pleased to order that the following Roster/Assignment on Board shall be in force from <b><?php echo $from_date; ?></b> till further orders :-
                </div>    
            </center>
        <?php echo $str_to_display;
        ?>

        </div>
        <div><input class="btn btn-primary" type="button" name="print_r" id="print_r" value="Print" onClick="printdiv('dv_print');"/>
            <!--<input class="btn" type="button" name="btnedit" id="btnedit" value="Editor"/>-->
        </div>  
        <?php
     }
    else  {
        echo "Problem to Display Record.";
     }
//CONVERT DATE FORMAT
    ?>
    

    <script>
	$('.multipleselect').select2();
        $("#btnedit").click(function()
         {
            window.open("roster_editor.php");
         } );
    </script>
 
