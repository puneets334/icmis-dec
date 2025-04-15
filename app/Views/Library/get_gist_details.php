<style>
    .panel-heading .accordion-toggle:after {
        /* symbol for "opening" panels */
        font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
        content: "\e114";    /* adjust as needed, taken from bootstrap.css */
        float: right;        /* adjust as needed */
        color: grey;         /* adjust as needed */
    }
    .panel-heading .accordion-toggle.collapsed:after {
        /* symbol for "collapsed" panels */
        content: "\e080";    /* adjust as needed, taken from bootstrap.css */
    }
</style>
<div style="text-align: left; padding:0px; ">
    <p style="font-size: 1.2vw; color: #4169E1;">AOR Submissions</span></p>
</div>
<div class = "row text-left" >
    <div class="panel-group" id="accordion">
        <?php
       
        $ucode = session()->get('login')['usercode'];
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];

        //$advocate_qur = "select pet_res from advocate where diary_no=".$diary_no." and display='Y' " ;
        //$advocate_sql = mysql_query($advocate_qur) or die(mysql_error());
        //$advocate_res = mysql_fetch_assoc($advocate_sql);

        //$advocate_res = is_data_from_table('advocate'," diary_no='$diary_no' and display='Y' ", 'pet_res','');

        if($advocate_res['pet_res']=='P'){
            $name = 'Petitioner';
        }
        elseif($advocate_res['pet_res']=='R'){
            $name = 'Respondent';
        }
        elseif($advocate_res['pet_res']=='I'){
            $name = 'Impleader';
        }
        elseif($advocate_res['pet_res']=='N'){
            $name = 'Intervenor';
        }else{
            $name = '';
        }
        //include("../menu_assign/config.php");
       $sql_verify1 =  $AdminusersModel->getEservicesData($diary_no,$list_dt);
      // pr( $sql_verify1);
      //  die;

         
        if (!empty($sql_verify1)) {

        //if(mysql_num_rows($res_org)>0){
            $sno = 1;
           foreach ($sql_verify1 as $row10) {
           // while ($row10 = mysql_fetch_array($res_org)) {
//$gist_dt = date('d-m-Y H:i:s', strtotime($row_org[ent_dt]));
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$sno?>">
                                <?=ucwords(strtolower($row10['title'].' '.$row10['name']))?><br>
                                <?='<span style="color:chocolate">('.$name.')</span>'?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?=$sno?>" class="panel-collapse collapse <?=$sno == 1 ? 'in' : ''?>">
                        <div class="panel-body">




                            <?php
                           /* $sql = "select a.id, a.header_details, a.file_name,a.icmis_file_name, b.name_of_header 
from library_referance_material_child a
inner join library_master_headers as b on b.id = a.library_master_headers_id
where a.library_reference_material_id = ".$row10['id']." and a.is_active = 1 order by a.id
";
                        
                            $sql_verify2 = $dbo_eservices->prepare($sql);
                            $sql_verify2->execute(); */

                            $sql_verify2 =  $AdminusersModel->getLibraryReferenceMaterialChild($row10['id']);
                            if (!empty($sql_verify2)) {


                          /*  $res_sql=mysql_query($sql) or die(mysql_error());*/

                           // if(mysql_num_rows($res_sql)>0) {
                                $sno = 1;
                            foreach ($sql_verify2 as $files) {
                                //while ($files = mysql_fetch_array($res_sql)) {
                                    ?>
                                    <div class="panel-group" >
                                        <div class="panel">
                                            <a data-toggle="collapse" ><?= $files['name_of_header']?> &raquo;
                                            </a>
                                            <div class="panel-collapse ">
                                                <div class="panel-body"><?= $files['header_details']?><br>
                                                    <?php if($files['file_name']){ ?>
                                                        <br><a href="#" class="pdflink" data-file="<?="files/library_aor_uploads/".$files['file_name'];?>" data-title="File">Uploaded File View (AOR)</a><br>
                                                    <?php }
                                                    else {
                                                        echo '<span style="color:red">Not uploaded by AOR</span>'.'<br>';
                                                    }

                                                    if($files['icmis_file_name']){
                                                        ?>
                                                        <a href="#" class="pdflink" data-file="<?="files/library_aor_uploads/".$files['icmis_file_name'];?>" data-title="File">Uploaded File View (Library)</a><br>
                                                    <?php }
                                                    else{
                                                        echo '<span style="color:red">Not uploaded by Library</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <?php
                $sno++;
            }

        }
        else{
            ?>
            <li class="list-group-item">
                <b>No Submission ...</b>
            </li>
            <?php
        }
        ?>

    </div> <!-- end container -->
</div>


<?php
//include("../includes/db_inc.php");
?>

<div style="text-align: left; padding:0px; ">
    <p style="font-size: 1.2vw; color: #4169E1;">Court Requisitions</span></p>
</div>

<div class = "row text-left" >
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">

            <div class="panel-collapse collapse in">
                <div class="panel-body">
        <?php
     /*   $court_requisition = "SELECT a.*, b.file_path FROM tbl_court_requisition a  
        left join requistion_upload b on b.req_id = a.id 
            where a.diary_no=$diary_no and a.itemDate= '".$list_dt."' " ;
        $res_court_requisition=mysql_query($court_requisition) or die(mysql_error()); */

        $res_court_requisition =  $AdminusersModel->getCourtRequisition($diary_no, $list_dt);
        if(!empty($res_court_requisition)){
            $sno_req = 1;
            foreach ($res_court_requisition as $row_req) {
                ?>

                                    <div class="panel-group" >
                                        <div class="panel">
                                            <a data-toggle="collapse" ><?= $row_req['remark1']?> &raquo;
                                            </a>
                                            <div class="panel-collapse ">
                                                <div class="panel-body"><br>
                                                    <?php if($row_req['file_path']){ ?>
                                                        <br><a href="#" class="pdflink" data-file="<?="files/library_aor_uploads/".$row_req['file_path'];?>" data-title="File">Uploaded File View</a><br>
                                                    <?php }
                                                    else {
                                                        echo '<span style="color:red">Not Uploaded</span>'.'<br>';
                                                    }


                                                    ?>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                <?php
            }
            ?>
            </div>
            </div>
            </div>

            <?php
        }
        else{
            ?>
            <li class="list-group-item">
                <b>No Court Requisitions ...</b>
            </li>
            <?php
        }
        ?>

    </div> <!-- end container -->
</div>


<br>
<br>