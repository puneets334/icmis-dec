<div class="row">
    <div class="col-sm-6">
        <?php $f_no='';
       ?>

            <table border="1" class="table table-striped custom-table showData dataTable no-footer dtr-inline" style="border-collapse: collapse" align="center">
                <thead><tr>
                    <th> S.No.</th>
                    <th> Petitioner</th>
                    <th>P</th>
                    <th>R</th>
                </tr> 
            </thead>

                <?php
                 if (!empty($party_petitioner)) 
                 {
                    $s_p = 1; 
                    foreach ($party_petitioner as $row) 
                    {
                        $pet_deptcode=!empty($row['deptcode']) ? $row['deptcode'] :0;
                        $pet_deptname=!empty($row['deptname']) ? $row['deptname'] :'';
                        ?>
                        <tr>
                            <td>
                                <?php
                                echo $s_p;
                                ?>
                            </td>
                            <td>

                                <span id="sp_partyname<?php echo $s_p; ?>"><?php echo $row['partyname']; ?></span>
                                <input type="hidden" name="hd_sr_no<?php echo $s_p; ?>" id="hd_sr_no<?php echo $s_p; ?>" value="<?php echo $row['sr_no']; ?>"/>
                                <input type="hidden" name="hd_pet_res<?php echo $s_p; ?>" id="hd_pet_res<?php echo $s_p; ?>" value="<?php echo $row['pet_res']; ?>"/>
                                <input type="hidden" name="hd_fil_no<?php echo $s_p; ?>" id="hd_fil_no<?php echo $s_p; ?>" value="<?php echo $f_no; ?>"/>
                                <input type="hidden" name="hd_ind_dep<?php echo $s_p; ?>" id="hd_ind_dep<?php echo $s_p; ?>" value="<?php echo $row['ind_dep']; ?>"/>
                                <input type="hidden" name="hd_sonof<?php echo $s_p; ?>" id="hd_sonof<?php echo $s_p; ?>" value="<?php echo $row['sonof']; ?>"/>
                                <input type="hidden" name="hd_prfhname<?php echo $s_p; ?>" id="hd_prfhname<?php echo $s_p; ?>" value="<?php echo $row['prfhname']; ?>"/>
                                <input type="hidden" name="hd_sex<?php echo $s_p; ?>" id="hd_sex<?php echo $s_p; ?>" value="<?php echo $row['sex']; ?>"/>
                                <input type="hidden" name="hd_age<?php echo $s_p; ?>" id="hd_age<?php echo $s_p; ?>" value="<?php echo $row['age']; ?>"/>
                                <input type="hidden" name="hd_addr1<?php echo $s_p; ?>" id="hd_addr1<?php echo $s_p; ?>" value="<?php echo $row['addr1']; ?>"/>
                                <input type="hidden" name="hd_addr2<?php echo $s_p; ?>" id="hd_addr2<?php echo $s_p; ?>" value="<?php echo $row['addr2']; ?>"/>
                                <input type="hidden" name="hd_dstname<?php echo $s_p; ?>" id="hd_dstname<?php echo $s_p; ?>" value="<?php echo $row['dstname']; ?>"/>
                                <input type="hidden" name="hd_pin<?php echo $s_p; ?>" id="hd_pin<?php echo $s_p; ?>" value="<?php echo $row['pin']; ?>"/>
                                <input type="hidden" name="hd_state<?php echo $s_p; ?>" id="hd_state<?php echo $s_p; ?>" value="<?php echo $row['state']; ?>"/>
                                <input type="hidden" name="hd_city<?php echo $s_p; ?>" id="hd_city<?php echo $s_p; ?>" value="<?php echo $row['city']; ?>"/>
                                <input type="hidden" name="hd_contact<?php echo $s_p; ?>" id="hd_contact<?php echo $s_p; ?>" value="<?php echo $row['contact']; ?>"/>
                                <input type="hidden" name="hd_email<?php echo $s_p; ?>" id="hd_email<?php echo $s_p; ?>" value="<?php echo $row['email']; ?>"/>
                                <input type="hidden" name="hd_deptcode<?php echo $s_p; ?>" id="hd_deptcode<?php echo $s_p; ?>" value="<?php echo $pet_deptcode.'->'.$pet_deptname; ?>"/>
                                <input type="hidden" name="hd_authcode<?php echo $s_p; ?>" id="hd_authcode<?php echo $s_p; ?>" value="<?php echo $row['authcode']; ?>"/>
                            </td>
                            <td>
                                <input type="radio" name="rdn_p_r<?php echo $s_p; ?>" id="rdn_p<?php echo $s_p; ?>" value="P" class="cl_rdn_p1"/>
                            </td>
                            <td>
                                <input type="radio" name="rdn_p_r<?php echo $s_p; ?>" id="rdn_r<?php echo $s_p; ?>" value="R" class="cl_rdn_r1"/>
                            </td>
                        </tr>
                        <?php
                        $s_p++;
                    }

                } else {
            ?>
            <tr><td colspan="100%" style="text-align: center"><b>No Record Found...</b></td></tr>
            <?php
        }
        ?>
         </table>
    </div>


    <div class="col-sm-6">
        <?php
       ?>
            <table border="1" class="table table-striped custom-table showData dataTable no-footer dtr-inline" style="border-collapse: collapse" align="center">
                <thead><tr>
                    <th>S.No.</th>
                    <th>Respondent</th>
                    <th> P </th>
                    <th>R</th>
                </tr></thead>

                <?php
                 if (!empty($party_respondent)) {
                $s_r = 1; foreach ($party_respondent as $row1){
                    $res_deptcode=!empty($row['deptcode']) ? $row['deptcode'] :'';
                    $res_deptname=!empty($row['deptname']) ? $row['deptname'] :'';
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo $s_r;
                            ?>
                        </td>
                        <td>
                            <span id="sp_partyname<?php echo $s_p; ?>"><?php echo $row1['partyname'];?></span>
                            <input type="hidden" name="hd_sr_no<?php echo $s_p; ?>" id="hd_sr_no<?php echo $s_p; ?>" value="<?php echo $row1['sr_no']; ?>"/>
                            <input type="hidden" name="hd_pet_res<?php echo $s_p; ?>" id="hd_pet_res<?php echo $s_p; ?>" value="<?php echo $row1['pet_res']; ?>"/>
                            <input type="hidden" name="hd_fil_no<?php echo $s_p; ?>" id="hd_fil_no<?php echo $s_p; ?>" value="<?php echo $f_no; ?>"/>
                            <input type="hidden" name="hd_ind_dep<?php echo $s_p; ?>" id="hd_ind_dep<?php echo $s_p; ?>" value="<?php echo $row1['ind_dep']; ?>"/>
                            <input type="hidden" name="hd_sonof<?php echo $s_p; ?>" id="hd_sonof<?php echo $s_p; ?>" value="<?php echo $row1['sonof']; ?>"/>
                            <input type="hidden" name="hd_prfhname<?php echo $s_p; ?>" id="hd_prfhname<?php echo $s_p; ?>" value="<?php echo $row1['prfhname']; ?>"/>
                            <input type="hidden" name="hd_sex<?php echo $s_p; ?>" id="hd_sex<?php echo $s_p; ?>" value="<?php echo $row1['sex']; ?>"/>
                            <input type="hidden" name="hd_age<?php echo $s_p; ?>" id="hd_age<?php echo $s_p; ?>" value="<?php echo $row1['age']; ?>"/>
                            <input type="hidden" name="hd_addr1<?php echo $s_p; ?>" id="hd_addr1<?php echo $s_p; ?>" value="<?php echo $row1['addr1']; ?>"/>
                            <input type="hidden" name="hd_addr2<?php echo $s_p; ?>" id="hd_addr2<?php echo $s_p; ?>" value="<?php echo $row1['addr2']; ?>"/>
                            <input type="hidden" name="hd_dstname<?php echo $s_p; ?>" id="hd_dstname<?php echo $s_p; ?>" value="<?php echo $row1['dstname']; ?>"/>
                            <input type="hidden" name="hd_pin<?php echo $s_p; ?>" id="hd_pin<?php echo $s_p; ?>" value="<?php echo $row1['pin']; ?>"/>
                            <input type="hidden" name="hd_state<?php echo $s_p; ?>" id="hd_state<?php echo $s_p; ?>" value="<?php echo $row1['state']; ?>"/>
                            <input type="hidden" name="hd_city<?php echo $s_p; ?>" id="hd_city<?php echo $s_p; ?>" value="<?php echo $row1['city']; ?>"/>
                            <input type="hidden" name="hd_contact<?php echo $s_p; ?>" id="hd_contact<?php echo $s_p; ?>" value="<?php echo $row1['contact']; ?>"/>
                            <input type="hidden" name="hd_email<?php echo $s_p; ?>" id="hd_email<?php echo $s_p; ?>" value="<?php echo $row1['email']; ?>"/>
                            <input type="hidden" name="hd_deptcode<?php echo $s_p; ?>" id="hd_deptcode<?php echo $s_p; ?>" value="<?php echo $res_deptcode.'->'.$res_deptname; ?>"/>
                            <input type="hidden" name="hd_authcode<?php echo $s_p; ?>" id="hd_authcode<?php echo $s_p; ?>" value="<?php echo $row1['authcode']; ?>"/>
                        </td>
                        <td>
                            <input type="radio" name="rdn_p_r<?php echo $s_p; ?>" id="rdn_p<?php echo $s_p; ?>" value="P" class="cl_rdn_p"/>
                        </td>
                        <td>
                            <input type="radio" name="rdn_p_r<?php echo $s_p; ?>" id="rdn_r<?php echo $s_p; ?>" value="R" class="cl_rdn_r"/>
                        </td>
                    </tr>
                    <?php
                    $s_p++;
                    $s_r++;
                }
                ?>
            
            <?php
        } else {
            ?>
            <tr><td colspan="100%" style="text-align: center"><b>No Record Found...</b></td></tr>
            <?php
        }
        ?>
        </table>
    </div>
</div>
