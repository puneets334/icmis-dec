<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataCauseListwithor)):?>
                <table  id="ReportsPartHead" class="table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;"><?php echo $report_title;?></h3>
                    <tr>
                        <th>S.No.</th>
                        <th>Court No.</th>
                        <th>Listed Before</th>
                        <th>Item No.</th>
                        <th>Diary No</th>
                        <th>Reg No.</th>
                        <th>Petitioner / Respondent</th>
                        <th>Advocate</th>
                        <th>Office<br> Report</th>
                       </tr>

                     </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataCauseListwithor as $ro):
                        $remark = $ro['remark'];
                        $sno1 = $sno % 2;
                        $dno = $ro['diary_no'];
                        $active_fil_dt = "";
                        $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                        if(!empty($ro['active_fil_dt'])){
                            $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));

                        }
                        $conn_no = $ro['conn_key'];
                        $m_c = "";
                        if ($conn_no == $dno) {
                            $m_c = "Main";
                        }
                        if ($conn_no != $dno and $conn_no > 0) {
                            $m_c = "Conn.";
                        }

                        if ($ro['diary_no'] == $ro['conn_key'] or $ro['conn_key'] == 0) {
                            $print_brdslno = $ro['brd_slno'];
                            $con_no = "0";
                            $m_c = "";
                        } else {
                            $print_brdslno = "&nbsp;" . $ro["brd_slno"] . "." . ++$con_no;
                            $m_c = "<span style='color:red;'>Connected</span><br/>";
                        }
                        $coram = $ro['coram'];
                        if ($ro['board_type'] == "J") {
                            $board_type1 = "Court";
                        }
                        if ($ro['board_type'] == "C") {
                            $board_type1 = "Chamber";
                        }
                        if ($ro['board_type'] == "R") {
                            $board_type1 = "Registrar";
                        }
                        $filno_array = explode("-", $ro['active_fil_no']);

                        if (empty($ro['reg_no_display'])) {
                            $fil_no_print = "Unregistred";
                        } else {

                            $fil_no_print = $ro['reg_no_display'];
                        }

                        if ($ro['pno'] == 2) {
                            $pet_name = $ro['pet_name'] . " AND ANR.";
                        } else if ($ro['pno'] > 2) {
                            $pet_name = $ro['pet_name'] . " AND ORS.";
                        } else {
                            $pet_name = $ro['pet_name'];
                        }



                        if ($ro['rno'] == 2) {
                            $res_name = $ro['res_name'] . " AND ANR.";
                        } else if ($ro['rno'] > 2) {
                            $res_name = $ro['res_name'] . " AND ORS.";
                        } else {
                            $res_name = $ro['res_name'];
                        }
                        $padvname = "";
                        $radvname = "";
                        $impldname = "";


                        $pet_res_array = get_pet_respondentby_diary($ro['diary_no']);
                        //print_r($pet_res_array);
                        if(is_array($pet_res_array)){
                            foreach($pet_res_array as $row){
                                if($row['pet_res'] == 'P'){
                                    $padvname = $row['name'];
                                }else if($row['pet_res'] == 'R'){
                                    $radvname = $row['name'];
                                }else{
                                    $impldname = $row['name'];
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?php
                                if ($ro['courtno'] == 31)
                                    echo 'VC 1';
                                else if ($ro['courtno'] == 32)
                                    echo 'VC 2';
                                else if ($ro['courtno'] == 33)
                                    echo 'VC 3';
                                else if ($ro['courtno'] == 34)
                                    echo 'VC 4';
                                else if ($ro['courtno'] == 35)
                                    echo 'VC 5';
                                else if ($ro['courtno'] == 36)
                                    echo 'VC 6';
                                else if ($ro['courtno'] == 37)
                                    echo 'VC 7';
                                else if ($ro['courtno'] == 38)
                                    echo 'VC 8';
                                else if ($ro['courtno'] == 39)
                                    echo 'VC 9';
                                else if ($ro['courtno'] == 40)
                                    echo 'VC 10';
                                else if ($ro['courtno'] == 41)
                                    echo 'VC 11';
                                else if ($ro['courtno'] == 42)
                                    echo 'VC 12';
                                else if ($ro['courtno'] == 43)
                                    echo 'VC 13';
                                else if ($ro['courtno'] == 44)
                                    echo 'VC 14';
                                else if ($ro['courtno'] == 45)
                                    echo 'VC 15';
                                else if ($ro['courtno'] == 46)
                                    echo 'VC 16';
                                else if ($ro['courtno'] == 47)
                                    echo 'VC 17';
                                else if ($ro['courtno'] == 21)
                                    echo 'R 1';
                                else if ($ro['courtno'] == 22)
                                    echo 'R 2';
                                else if ($ro['courtno'] == 61)
                                    echo 'R VC 1';
                                else if ($ro['courtno'] == 62)
                                    echo 'R VC 2';
                                else
                                    echo $ro['courtno'];
                                ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo $board_type1 ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo $m_c . $print_brdslno; ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                            <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")) . " ", str_replace(",", ", ", trim($impldname, ",")); ?></td>
                            <td>
                               <?php $office_report = get_office_report($ro['diary_no'],$listing_date);
                               $ssno = 0;
                                if(!empty($office_report)){
                                    echo 'Uploaded on<br>';
                                    foreach($office_report as $row){
                                        $ssno++;
                                        $res_office_report = $row['office_repot_name'];
                                        $res_max_o_r = $row['office_report_id'];
                                        if ($res_max_o_r == 0)
                                            $res_max_o_r = "&nbsp;";
                                        $dno = $row['dno'];
                                        $d_yr = $row['d_yr'];
                                        $order_dt = $row['order_dt'];
                                        $rec_dt = $row['rec_dt'];
                                        //    chdir("listing");
                                        $fil_nm = "../../officereport/" . $d_yr . '/' . $dno . '/' . $res_office_report;

                                        //  echo "the fil num is ".$fil_nm;
                                        $pos = stripos($res_office_report, '.pdf');
                                        if ($pos !== false) {
                                            echo '<a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                                        } else {
                                            echo '<a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                                        }
                                    }
                                }
                                ?>

                            </td>
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
