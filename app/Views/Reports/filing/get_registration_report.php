<div style="text-align: center">
    <div class="dv_right">
        <input type="button" name="btn_pnt" id="btn_pnt" value="Print"/>
    </div>
</div>
<div id="dv_print">
    <h4 align="center">
        Supreme Court of India
    </h4>
    <div style="text-align: center">
        <h3>Diary No.- <?php echo $diary_no; ?> - <?php echo $diary_year; ?></h3>
    </div>
    <?php
    $dairy_no=$diary_no.$diary_year;
    $db = \Config\Database::connect();
    $sql = "Select fil_no,casetype_id,fil_dt,bench from main where diary_no='$dairy_no' union Select fil_no,casetype_id,fil_dt,bench from main_a where diary_no='$dairy_no'";
    $fil_no =  $db->query($sql)->getRowArray();

    $sql_ct_type = "Select short_description from casetype where casecode='$fil_no[casetype_id]' and display='Y'";
    $res_ct_typ = $db->query($sql_ct_type)->getRowArray();

    $bn_sql = "select bench_name from master_bench where display='Y' and id='$fil_no[bench]'";
    $res_bnch = $db->query($bn_sql)->getRowArray();
    ?>
    <table align="center" width="100%" cellpadding="1" cellspacing="1" class="c_vertical_align tbl_border">
        <tr>
            <td>CASE TYPE : <strong><?php echo $res_ct_typ; ?></strong></td>
            <td>CASE NUMBER: <strong><?php echo substr($fil_no['fil_no'], 3); ?></strong></td>
            <td>CASE YEAR :
                <strong><?php if (!empty(trim($fil_no['fil_dt']))) echo date('Y', strtotime($fil_no['fil_dt'])); ?></strong>
            </td>
            <td>Bench : <strong><?php echo $res_bnch; ?></strong></td>
        </tr>
    </table>

        <?php
        $sql = "SELECT p.sr_no, p.pet_res,p.ind_dep, p.partyname, p.sonof,p.prfhname, p.age,p.sex,p.caste, p.addr1, p.addr2,
 		p.pin, p.state, p.city,p.email, p.contact AS mobile,
 		p.deptcode,
 		(SELECT deptname  FROM  master.deptt WHERE deptcode=p.deptcode)deptname,c.skey
 	      FROM party p 
 		INNER JOIN main m ON  m.diary_no=p.diary_no  and sr_no=1 and pflag='P' and pet_res in ('P','R')
         LEFT JOIN master.casetype c ON c.casecode::text=SUBSTRING(m.fil_no,3,3)
         where m.diary_no='$dairy_no'  order by p.pet_res,p.sr_no";
        $result = $db->query($sql)->getResultArray();
        $ctr_p = 0; //for counting petining
        $ctr_r = 0; // for couting respondent
        if (sizeof($result) > 0) {
        $grp_pet_res = '';
        foreach ($result as $row) {
            ?>
            <div class="cl_center">
                <h3><?php if ($row['pet_res'] == 'P') { ?> Petitioner <?php } else { ?> Respondent <?php } ?></h3>
            </div>
            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                <tr>
                    <td style="width: 15%">
                        Name
                    </td>
                    <td colspan="12">
                        <?php echo $row['partyname']; ?>
                    </td>
                <tr>
                    <td>
                        C/o
                    </td>
                    <td colspan="12">
                        <?php
                        if ($row['sonof'] != '') {
                            echo $row['sonof'] . "/o " . $row[prfhname];
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Department
                    </td>
                    <td colspan="12">
                        <?php echo $row['deptname']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Address
                    </td>
                    <td colspan="12">
                        <?php
                        if ($row['addr1'] == '')
                            echo $row['addr2'];
                        else
                            echo $row['addr1'] . ', ' . $row['addr2'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        District
                    </td>
                    <td style="width: 250px">
                        <?php
                        $district = "Select Name from master.state where State_code='$row[state]' and District_code='$row[city]' and Sub_Dist_code=0 and Village_code=0 and display='Y'";
                        $district = $db->query($district)->getRowArray();
                        echo $district['Name'];
                        ?>
                    </td>
                    <td style="width: 60px">
                        Pincode
                    </td>
                    <td>
                        <?php echo $row['pin']; ?>
                    </td>
                    <td style="width: 50px">
                        Mobile
                    </td>
                    <td>
                        <?php echo $row['mobile']; ?>
                    </td>
                    <td style="width: 50px">
                        Gender
                    </td>
                    <td>
                        <?php echo $row['sex']; ?>
                    </td>
                    <td style="width: 40px">
                        Age
                    </td>
                    <td style="width: 50px">
                        <?php echo $row['age']; ?>
                    </td>
                    <td style="width: 60px">
                        Email Id
                    </td>
                    <td>
                        <?php echo $row['email']; ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="cl_center">
        <input type="button" name="btn_pnt" id="btn_pnt" value="Print"/>
    </div>
    <?php
    } else {
        ?>
        <div class="cl_center"><b>No Record Found</b></div>
        <?php
    }
    ?>
</section>