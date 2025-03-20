<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .class_red {
        color: red;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
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
                                <h3 class="card-title">ADVANCE VACATION LIST PRINT MODULE</h3>
                            </div>


                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE VACATION LIST PRINT MODULE</span>
                                
                            <div style="col-md-6">
                                <table class="table table-bordered mt-4">
                                    <tr>
                                        <td>
                                            <fieldset>
                                                <legend>Advance Vacation Year</legend>
                                                <select class="ele" name="vac_yr" id="vac_yr">              
                                                <?php
                                                    for($i=2018;$i<=date('Y');$i++){
                                                    ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    }
                                                ?>
                                                </select> 
                                            </fieldset>

                                        </td>

                                        <td id="rs_actio_btn1" style="text-align:center;">
                                        <fieldset>
                                                <legend>Action</legend>
                                                <input class="ele" type="button" name="bt1" id="bt1" value="Submit" />
                                            </fieldset>

                                        </td>
                                    </tr>
                                </table>
                                <div id="res_loader"></div>
                            </div>

            <div id="dv_res1">
             <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
            <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px"/><br/>
              
            SUPREME COURT OF INDIA
                  
            <br/>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php echo "ADVANCE REGULAR HEARING MATTERS LIST TO BE LISTED DURING SUMMER VACATION " . $v_year; ?>
                </th>
            </tr>
           <tr>
                <th colspan="4" style="font-size: 18px; text-align: left; text-decoration: underline;">
                    <?php echo "CASES WHICH ARE DIRECTED TO BE LISTED DURING SUMMER VACATION"; ?>
                </th>
            </tr>
            </thead>
            

            <?php
            if(!empty($results)){
            $psrno = "1";
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            foreachr ($results as $row) {
                $coram = $row['coram'];
                $fix_dt = date('d-m-Y', strtotime($row['next_dt']));
                $main_supp_fl = $row['main_supp_flag'];
                $diary_no = $row['diary_no'];
               if($row['is_fixed']=='Y'){
                    $vaca_note = 1;
                }
                if($vaca_note == 1 AND $row['listorder'] != 4 AND $row['listorder'] != 5 AND $row['listorder'] != 7 AND $row['listorder'] != 8){
                    $vaca_note++;
                    ?>
                    <tr><td colspan="4" style="font-size: 18px; text-align: left; text-decoration: underline;">
                    <?php echo "READY REGULAR HEARING MATTERS REGISTERED UPTO YEAR 2013"; ?>
                    </td>
                    </tr>
                <?php
                }
                if ($mainhead == "F") {
                    $retn = $row["sub_name1"];
                    if ($row["sub_name2"])
                        $retn .= " - " . $row["sub_name2"];
                    if ($row["sub_name3"])
                        $retn .= " - " . $row["sub_name3"];
                    if ($row["sub_name4"])
                        $retn .= " - " . $row["sub_name4"];
                
                } else {
                    $subheading = $row["stagename"];
                }
                if ($mnhead_print_once == 1) {
                    if ($mainhead == 'M' AND $subheading != "FOR JUDGEMENT" AND $subheading != "FOR ORDER") {
                        if ($row['board_type'] == 'C') {
                            $print_mainhead = "CHAMBER MATTERS";
                        } else {
                            $print_mainhead = "MISCELLANEOUS HEARING";
                        }
                    }
                    if ($mainhead == 'F')
                        $print_mainhead = "REGULAR HEARING";
                    if ($mainhead == 'L')
                        $print_mainhead = "LOK ADALAT HEARING";
                    if ($mainhead == 'S')
                        $print_mainhead = "MEDIATION HEARING";
                    if ($main_supp_fl == "2") {
                        echo "<tr><td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>";
                    }
                    ?>
                    <tr>
                        <th colspan="4"
                            style="text-align: center; text-decoration: underline;"><?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
                                echo $print_mainhead;
                            } ?></th>
                    </tr>
                    <tr style="font-weight: bold; background-color:#cccccc;">
                        <td style="width:5%;">SNo.</td>
                        <td style="width:20%;">Case No.</td>
                        <td style="width:35%;">Petitioner / Respondent</td>
                        <td style="width:40%;">
                            <?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") { ?>
                                Petitioner/Respondent Advocate
                            <?php } ?>
                        </td>
                    </tr>

                    <?php
                    $mnhead_print_once++;
                }

                if ($subheading != $subheading_rep) {
                    if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
                        echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                        $subheading_rep = $subheading;
                    }

                }
            
                if ($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0) {
                  
                    $print_brdslno=$psrno;
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
           
                } else if ($row['main_or_connected'] == 1) {
                    $print_brdslno = "&nbsp;".$print_srno.".".++$con_no;
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }

                $m_f_filno = $row['active_fil_no'];
                $m_f_fil_yr = $row['active_reg_year'];

                $filno_array = explode("-", $m_f_filno);
                if ($filno_array[1] == $filno_array[2]) {
                    $fil_no_print = ltrim($filno_array[1], '0');
                } else {
                    $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                }
                if ($row['active_fil_no'] == "") {
                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                }

                else {
                    $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
                }
                 $padvname = ""; $radvname = ""; $impldname = "";$intervenorname = "";
                    
                          
                            if($resultsadv > 0) {
                          
                                $radvname=  $resultsadv["r_n"];
                                $padvname=  $resultsadv["p_n"];
                                $impldname = $resultsadv["i_n"];
                                $intervenorname = $resultsadv["intervenor"];
                              
                            }
                    
                        if($row['pno'] == 2){
                            $pet_name = $row['pet_name']." AND ANR.";
                        }
                        else if($row['pno'] > 2){
                            $pet_name = $row['pet_name']." AND ORS.";
                        }
                        else{
                            $pet_name = $row['pet_name'];
                        }
                        if($row['rno'] == 2){
                            $res_name = $row['res_name']." AND ANR.";
                        }
                        else if($row['rno'] > 2){
                            $res_name = $row['res_name']." AND ORS.";
                        }
                        else{
                            $res_name = $row['res_name'];
                        }
                if (($row['section_name'] == null OR $row['section_name'] == '') AND $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                    if ($row['active_reg_year'] != 0)
                        $ten_reg_yr = $row['active_reg_year'];
                    else
                        $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                    if ($row['active_casetype_id'] != 0)
                        $casetype_displ = $row['active_casetype_id'];
                    else if ($row['casetype_id'] != 0)
                        $casetype_displ = $row['casetype_id'];

                     $section_ten_q="SELECT tentative_section(".$row["diary_no"].") as section_name";
                            $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                            if(mysql_num_rows($section_ten_rs)>0){
                                $section_ten_row = mysql_fetch_array($section_ten_rs);
                                $row['section_name']=$section_ten_row["section_name"];
                            }
                }


                if ($is_connected != '') {

                } else {
                    $print_srno = $print_srno;
                    $psrno++;
                }
               $doc_desrip = "";
                $listed_ias = $row[listed_ia];
                                        $listed_ia = rtrim(trim($listed_ias),",");
                if($listed_ias){
                 $listed_ia = "I.A. ".str_replace(',', '<br>I.A.',$listed_ia)." In <br>";
    
                foreach($row_dc as $rs_dc){
                    $doc_desrip .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                    $doc_desrip .= "IA No. ".$row_dc[docnum]."/".$row_dc[docyear]." - ".$row_dc[docdesp];
                    $doc_desrip .= "</td><td></td></tr>";
                }
        
            }
                 $cate_old_id1 = "";
                $cate_old_id1=$res_sm['category_sc_old'];

                $output .= "<tr><td style='vertical_align:top;' valign='top'>$print_brdslno</td>";
                $output .= "<td style='vertical_align:top;' valign='top'>".$is_connected."$comlete_fil_no_prt"."<br/>".$if_sclsc." ".$row['section_name']."<br/>".$cate_old_id1."</td>";
                
                $output .= "<td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top'>".$pet_name."</td>";
                     
                        $padvname_x = str_replace(",",", ",trim($padvname,","));
                        if($padvname_x){
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($padvname_x, $x60));
                            $lines_cnt = count($lines);
                                for($k=0;$k<count($lines);$k++){
                                    if($k==0){
                                        $output .= "<td valign='top'>".$lines[$k]."</td></tr>";
                                    }
                                    else if($k==1 OR $k==2){
                                        $output .= "<tr><td></td><td></td><td></td><td valign='top'>".$lines[$k]."</td></tr>";
                                    }
                                    else{
                                        $output .= "<tr><td></td><td></td><td></td><td valign='top'>".$lines[$k]."</td></tr>";
                                    }
                                }
                        }
                        else{
                            $output .= "<td></td></tr>";
                        }
                       
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; font-style: italic;' valign='top'>Versus</td><td style='font-style: italic;'></td></tr>";
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top' > ".$res_name."</td>";
                        $radvname_x = str_replace(",",", ",trim($radvname,","));
                        if($impldname){
                            $radvname_x .= "<br/>".str_replace(",",", ",trim($impldname,","));
                        }
                        if($intervenorname){
                            $radvname_x .= "<br/>".str_replace(",",", ",trim($intervenorname,","));
                        }

                        if($radvname_x){
                        $x60 = 150;
                        $lines = explode("\n", wordwrap($radvname_x, $x60));
                        $lines_cnt = count($lines);
                            for($k=0;$k<count($lines);$k++){
                                if($k==0){
                                    $output .= "<td valign='top'>".$lines[$k]."</td></tr>";
                                }
                                else{
                                    $output .= "<tr><td></td><td></td><td></td><td valign='top'>".$lines[$k]."</td></tr>";
                                }
                            }


                        }
                        else{
                            $output .= "<td></td></tr>";
                        }

                
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                            if($row['listorder'] == '4' OR $row['listorder'] == '5' OR $row['listorder'] == '7' OR $row['listorder'] == '8')
                                $output .= "{".$row['purpose']."}";
                            $output .= "</td><td></td></tr>";
                 
                      
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                foreachr($ro_lct as $rs_lct){
                                    $output .= " IN ".$ro_lct['type_sname']." - ".$ro_lct['lct_caseno']."/".$ro_lct['lct_caseyear'].", ";
                                }
                            $output .= "</td><td></td></tr>";
                            

                                                $str_brdrem = get_cl_brd_remark($diary_no);

                                $x60 = 150;
                                $lines = explode("\n", wordwrap($str_brdrem, $x60));
                                for($k=0;$k<count($lines);$k++){
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= $lines[$k];
                                    $output .= "</td><td></td></tr>";
                                }


                                if($relief){
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= "Relief : ".$relief;
                                    $output .= "</td><td></td></tr>";
                                }

                        
                            $output .= $doc_desrip;

                   
                        $output .= "<tr><td style='border-bottom:0px dotted #999999; padding-bottom:10px; size : 2px; height:2px;' colspan=4></td></tr>";
                        echo $output;
                $output = "";

                /// Start Connected case details here

                /// END Connected case details

            }//END OF WHILE LOOP
            ?>
        </table>
        <?php
        }
        else {
            echo "No Records Found";
        }

        ?>


                            
                                </div>

                            </div>

                       </div>


                    </form>
                    <div id="jud_all_al">
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
<script>


$(document).on("click","#bt1",function(){
    get_cl_1();
});

function get_cl_1(){            
    var vac_yr = $("#vac_yr").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('Listing/PrintAdvance/get_cause_list_vacation');?>',
        cache: false,
        async: true,
        data: {vac_yr: vac_yr, CSRF_TOKEN:CSRF_TOKEN_VALUE},
        beforeSend:function(){
            $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url('images/scilogo.png'); ?>"/></td></tr></table>');
        },
        success: function(data, status) {
            $('#dv_res1').html(data);
            if(data)
                $('#res_on_off').show();
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

$(document).on("click","#ebublish",function(){
    var prtContent = $("#prnnt").html();
    var vac_yr = $("#vac_yr").val();

    $.ajax({
        url: 'cl_print_save_vacation.php',
        cache: false,
        async: true,
        data: {vac_yr: vac_yr,prtContent:prtContent},
        beforeSend:function(){
            $('#res_loader').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {
            $('#res_loader').html(data);
            alert(data);
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
});

//function CallPrint(){
$(document).on("click","#prnnt1",function(){
    var prtContent = $("#prnnt").html();            
    var vac_yr = $("#vac_yr").val();
    var temp_str=prtContent;
    var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});

</script>