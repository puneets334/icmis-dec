<hr/>
            <div class="row">
                <div class="col-12">
                    <?php if(isset($sentence_undergone_list) && !empty($sentence_undergone_list)){ ?>
                    <center><font color ="red"> <h4>Data Already Entered in The Database</h4> </font></center><br/>
                    <?php }else{ ?>
                        <!--<div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>-->
                    <?php } ?>
                        <div class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                            <table id="dv_add_det" class="table table-bordered table-striped datatable_report">
                                <!--<table id="dv_add_det" border="1"  width="100%">-->
                                <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Status</th>
                                    <th>From date</th>
                                    <th>To Date</th>
                                    <th>Period Undergone ( in No. of Days )</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $sno=1; $difference='';
                                if(!empty($sentence_undergone_list)) {
                                foreach ($sentence_undergone_list as $row)  {
                                    if($row['status']=='C')
                                    {
                                        $row['status']='CUSTODY';
                                    }
                                    else if($row['status']=='P')
                                    {
                                        $row['status']='PAROLE';
                                    }
                                    else if($row['status']=='A')
                                    {
                                        $row['status']='ABSCONDING';
                                    }
                                    else if($row['status']=='B')
                                    {
                                        $row['status']='BAIL';
                                    }
                                    else if($row['status']=='O')
                                    {
                                        $row['status']='OTHERS';
                                    }
                                    else if($row['status']=='M')
                                    {
                                        $row['status']='APPROXIMATION';
                                    }
                                    else if($row['status']=='U')
                                    {
                                        $row['status']='UNDER TRIAL';
                                    } else{
                                        $row['status']='FURLOUGH';
                                    }
                                    if ($row['to_date'] == '1970-01-01') {
                                        $tdate = '';
                                    } else {
                                        $tdate = date('d-m-Y', strtotime($row['to_date']));
                                    }

                                    if($row['to_date'] == '1970-01-01' and trim($row['status'])=='BAIL')
                                    {
                                        $difference= "Presently On Bail";
                                    }
                                    else if($row['to_date'] == '1970-01-01' and trim($row['status'])=='UNDER TRIAL')
                                    {
                                        $difference= "Presently Under Trial";
                                    }
                                    else
                                    {
                                        $difference= $row['difference'] ;
                                    }
                                    ?>
                   <tr>
                    <td><?php echo $sno;?> <input type="hidden" name="hd_ped_ungone<?php echo $sno; ?>" id="hd_ped_ungone<?php echo $sno; ?>" value="<?php if (!isset($row['add_details'])) { echo $row['id']; } ?>"/></td>
                    <td> <span id="sp_m_status<?php echo $sno; ?>"><?php if (isset($row['add_details'])) { echo $row['m_status']; }else{ echo $row['status'];} ?></span></td>
                    <td><span id="sp_txt_frm_dt<?php echo $sno; ?>"><?php echo date('d-m-Y',strtotime($row['frm_date']));?></span></td>
                    <td><span id="sp_txt_to_dt<?php echo $sno;?>"><?php echo $tdate; ?></span></td>
                    <td><center><?php echo $difference;?></center></td>
                    <td> <?php echo $row['rem']; ?></td>
                        <td>
                            <?php if (!isset($row['add_details'])) {?>
                                <div class="btn btn-danger" id="<?php echo $row['id'];?>" onclick="delete_sentence_undergone(this.id)"><i class="fa fa-trash"></i></div>
                            <input type="hidden" class="btn btn-outline-danger " value="Delete" id="<?php echo $row['id'];?>" onclick="delete_sentence_undergone(this.id)" value="<?php echo $sno;?>">
                              <?php }?>
                        </td>
                    </tr>
                    <?php $sno++; }   }?>
                               <!-- <div id="dv_add_det_ok"></div>-->
                                </tbody>
                            </table>
                            <center><div class="btn btn-success actionSave" name="btn_save_rec" id="btn_save_rec" style="display: none;">Save</div></center>
                        </div>
                        </div>
                        <!--<script>
                            $(function () {
                                $(".datatable_report").DataTable({
                                    "responsive": true, "lengthChange": false, "autoWidth": false,
                                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                                }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

                            });
                        </script>-->




                </div>
            </div>
