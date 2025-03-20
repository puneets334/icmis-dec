<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataGistModule)):?>
                <?php

                    $mainhead_descri = '';

                    if($courtno == "0"){
                        $courtno = "0";
                    }
                    else{
                        if($courtno == 21){
                            $display_court_no = '1';
                        }
                        else if($courtno == 22){
                            $display_court_no = '2';
                        }
                        else{
                            $display_court_no = $courtno;
                        }
                    }

                    if($board_type == "0"){
                        $board_type = "";
                    }
                    else{
                        if($board_type == 'C'){
                            $mainhead_descri = "Chamber Matters in ";
                        }
                        else if($board_type == 'R'){
                            $mainhead_descri = "Registrar ";
                        }
                    }
                ?>
                <h3><center>Gist of Office Report dated <?php echo date('d-m-Y', strtotime($listing_dts)); ?> (<?php echo $mainhead_descri." Court No. ".$display_court_no; ?>)</center></h3>
                <table  id="ReportsPartHead" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Item No</th>
                        <th>Case No</th>
                        <th>Cause Title</th>
                        <th>Entry Time / Print Time</th>
                        <th>Gist</th>
                       </tr>
                     </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataGistModule as $result):

                        $con_no = '';
                        $m_c = '';

                        if($result['diary_no'] == $result['conn_key'] OR $result['conn_key'] == 0){
                            $print_brdslno = $result['brd_slno'];
                            $con_no = "0";
                            $m_c = "";
                        }
                        else{
                            $print_brdslno = "&nbsp;".$result["brd_slno"].".".++$con_no;
                            $m_c = "<span style='color:red;'>Conn.</span><br/>";
                        }

                        if(empty($result['reg_no_display'])){
                            $fil_no_print = "Diary No. ".substr_replace($result['diary_no'], '/', -4, 0);
                        }
                        else{
                            $fil_no_print = $result['reg_no_display']." @ Diary No. ".substr_replace($result['diary_no'], '/', -4, 0);
                        }

                        if($result['pno'] == 2){
                            $pet_name = $result['pet_name']." AND ANR.";
                        }
                        else if($result['pno'] > 2){
                            $pet_name = $result['pet_name']." AND ORS.";
                        }
                        else{
                            $pet_name = $result['pet_name'];
                        }



                        if($result['rno'] == 2){
                            $res_name = $result['res_name']." AND ANR.";
                        }
                        else if($result['rno'] > 2){
                            $res_name = $result['res_name']." AND ORS.";
                        }
                        else{
                            $res_name = $result['res_name'];
                        }


                        if(!is_null($max_date) && $result['rec_dt'] > $max_date){
                            $causeTitle = '<u>'.$pet_name."<br/>Vs<br/>".$res_name.'</u>';
                        }else{
                            $causeTitle = $pet_name."<br/>Vs<br/>".$res_name;
                        }

                    ?>
                        <tr>
                            <td><?php echo $print_brdslno."<br>".$m_c; ?> (<?=$result['section_name']?>)</td>
                            <td><?php echo $fil_no_print;?></td>
                            <td><?php echo $causeTitle;?></td>
                            <td>
                                <?php 
                                    if($result['rec_dt']){ 
                                        echo 'E - '. date('d-m-Y h:i a',strtotime($result['rec_dt']));
                                    }
                                    echo '<br>';
                                    if(!empty($result['gist_last_read_datetime']) && $result['gist_last_read_datetime'] != NULL){
                                        echo 'P - '. date('d-m-Y h:i a',strtotime($result['gist_last_read_datetime']));
                                    } 
                                ?>
                            </td>
                            <td><?php echo $result['summary'];?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>

            $(function () {
                $("#ReportsPartHead").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
