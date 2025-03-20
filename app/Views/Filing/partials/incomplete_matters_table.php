<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <div id="dv_content1">
    <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
      <tr>
        <th>Incomplete Matters for <span style="color: #d73d5a"></span>
          <?php
          $cur_date = date('d-m-Y');

          $new_date = date('d-m-Y', strtotime($cur_date . ' + 60 days'));

          $cat = 0;
          $ref = 0;
          $condition = "and remarks=''";
          $fil_trap_type_q = "SELECT usertype,type_name,disp_flag FROM fil_trap_users a 
                LEFT JOIN usertype b ON usertype=b.id AND b.display='E' WHERE usercode=$_SESSION[dcmis_user_idd] AND a.display='Y' ";

          $fil_trap_type_rs = mysql_query($fil_trap_type_q) or die(__LINE__ . '->' . mysql_error());
          if (mysql_num_rows($fil_trap_type_rs) > 0) {
            $fil_trap_type_row = mysql_fetch_array($fil_trap_type_rs);
            if ($fil_trap_type_row['usertype'] == 104)
              $ref = 1;
            //                    if($fil_trap_type_row['usertype']==105)
            //                        $cat=1;
            if ($fil_trap_type_row['usertype'] == 108) {
              $ref = 2;


              //exit();
            }
            //                     if($fil_trap_type_row['usertype']==109)
            //                        $ref=3;
            if ($fil_trap_type_row['usertype'] == 105 || $fil_trap_type_row['usertype'] == 106)  // for category and tagging user  
            {
              $cat = 1;

              if ($fil_trap_type_row['usertype'] == 105) {
                $text = "Category";
              } else {
                $text = "Tagging";
              }
          ?>

              <select id="type_report" onChange="get_list(this.value)">
                <option value="">Select</option>
                <option value=<?php echo $_SESSION['dcmis_user_idd']; ?>> <?php echo $_SESSION['emp_name_login']; ?></option>
                <option value="<?php echo $fil_trap_type_row['usertype']; ?>">Pending Matters of <?php echo $text; ?></option>
              </select>


            <?php
            }
            //                      if($fil_trap_type_row['usertype']==107)
            //                        $ref=3;
            ?>

            <span style="color: #737add">[<?php echo $fil_trap_type_row['type_name']; ?>]</span>
            <div id='txtHint'></div>
          <?php
            if ($fil_trap_type_row['usertype'] == 102)
              $condition = "and remarks='FIL -> DE'";
            if ($fil_trap_type_row['usertype'] == 103)
              $condition = "and remarks in('DE -> SCR','FDR -> SCR')";
            if ($fil_trap_type_row['usertype'] == 107)
              $condition = "and remarks in('CAT -> IB-Ex','TAG -> IB-Ex','SCN -> IB-Ex') ";
          } else {
            echo "<br>No record found!!!!";

            exit();
          }


          /*$fil_trap_type_q = "SELECT usercode, NAME, empid FROM sub_sub_me_per a LEFT JOIN users b ON a.sub_sub_us_code=b.usercode
                        WHERE sub_sub_menu=13 AND a.display='Y' AND b.display='Y' AND section=19 AND attend='P' AND usertype IN (50,51,17)
                        AND empid=$_SESSION[icmic_empid]";
                        $fil_trap_type_rs = mysql_query($fil_trap_type_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($fil_trap_type_rs)>0){
                            ?>
                            <span style="color: #737add">[DE]</span>
                                <?php
                        }
                        else{
                            $fil_trap_type_q = "SELECT usercode, NAME, empid FROM sub_sub_me_per a LEFT JOIN users b ON a.sub_sub_us_code=b.usercode
                            WHERE sub_sub_menu=14 AND a.display='Y' AND b.display='Y' AND section=19 AND attend='P' AND usertype IN (50,51,17)
                            AND empid=$_SESSION[icmic_empid]";
                            $fil_trap_type_rs = mysql_query($fil_trap_type_q) or die(__LINE__.'->'.mysql_error());
                            if(mysql_num_rows($fil_trap_type_rs)>0){
                                ?>
                                <span style="color: #737add">[SCR]</span>
                                    <?php
                            }
                            else{
                                $fil_trap_type_q = "SELECT usercode, NAME, empid FROM sub_sub_me_per a LEFT JOIN users b ON a.sub_sub_us_code=b.usercode
                                WHERE sub_sub_menu=79 AND a.display='Y' AND b.display='Y' AND section=19 AND attend='P' AND usertype=14
                                AND empid=$_SESSION[icmic_empid]";
                                $fil_trap_type_rs = mysql_query($fil_trap_type_q) or die(__LINE__.'->'.mysql_error());
                                if(mysql_num_rows($fil_trap_type_rs)>0){
                                    $vfn=1;
                                    ?>
                                    <span style="color: #737add">[VFN]</span>
                                        <?php
                                }
                                else{
                                    $fil_trap_type_q = "SELECT usercode, NAME, empid FROM sub_sub_me_per a LEFT JOIN users b ON a.sub_sub_us_code=b.usercode
                                    WHERE sub_sub_menu=38 AND a.display='Y' AND b.display='Y' AND section=19 AND attend='P' AND usertype IN (50,51,17)
                                    AND empid=$_SESSION[icmic_empid]";
                                    $fil_trap_type_rs = mysql_query($fil_trap_type_q) or die(__LINE__.'->'.mysql_error());
                                    if(mysql_num_rows($fil_trap_type_rs)>0){
                                        $ref=1;
                                        ?>
                                        <span style="color: #737add">[REF]</span>
                                            <?php
                                    }
                                }
                            }
                        }*/

          ?>
        </th>
      </tr>
      <tr>
        <th>
          <hr>
        </th>
      </tr>
    </table>
    <div id="result">