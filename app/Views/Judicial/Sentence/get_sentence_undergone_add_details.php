
                    <?php if(isset($sentence_undergone_list) && !empty($sentence_undergone_list)){ ?>

                                <?php $sno=$sentence_undergone_list[0]['cnt_rw']; $difference='';
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
                            <div id="dv_ins_up_cls<?php echo $row['cnt_rw']; ?>"></div>
                        </td>
                    </tr>
                    <?php $sno++; }   }?>

                    <?php }else{ ?>

                    <?php } ?>
