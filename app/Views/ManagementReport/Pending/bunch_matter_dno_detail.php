<div class="table-responsive">
    <?php if ($results) { ?>
        <div class="font-weight-bold text-center mt-26">Bunch Matters</div>
        <table id="example2" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="5%">SNo.</th>
                    <th width="25%">Reg No / Diary No</th>
                    <th width="35%">Petitioner / Respondent</th>
                    <th width="35%">Advocate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($results as $ro) {
                    $dno = $ro['diary_no'];
                    
                    $conn_no = $ro['conn_key'];   
                    $filno_array = explode("-",$ro['active_fil_no']); 
                    if(empty($filno_array[0])){
                        $fil_no_print = "Unregistred";
                    } else{
                        $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                        $fil_no_print .= "/".$ro['active_reg_year'];
                    }  

                    if (isset($ro['section_name']) && ($ro['section_name'] == null or $ro['section_name'] == '') and $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {
                        if ($ro['active_reg_year'] != 0)
                        $ten_reg_yr = $ro['active_reg_year'];
                        else
                        $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));

                        if ($ro['active_casetype_id'] != 0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if ($ro['casetype_id'] != 0)
                            $casetype_displ = $ro['casetype_id'];
                    }  


                    $dispaly_name = !empty($ro['pet_name']) ? $ro['pet_name'] : '';
                    $dispaly_name .= !empty($ro['pet_name']) && !empty($ro['res_name']) ? "<br/>Vs<br/>" : '';
                    $dispaly_name .= !empty($ro['res_name']) ? $ro['res_name'] : '';

                    $advocate_name = !empty($ro['padvname']) ? $ro['padvname'] : '';
                    $advocate_name .= !empty($ro['padvname']) && !empty($ro['radvname']) ? "<br/>Vs<br/>" : '';
                    $advocate_name .= !empty($ro['radvname']) ? $ro['radvname'] : '';
                ?>
                    <tr id="<?php echo $dno; ?>">
                        <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>                
                        <td align="left" style='vertical-align: top;'><?php echo $fil_no_print."<br>Dno ".substr_replace($ro['diary_no'], '-', -4, 0); 
                        if($ro['conn_key'] == $ro['diary_no']){
                            echo " Main";
                        }
                        ?>
                        </td>                                 
                        <td align="left" style='vertical-align: top;'><?php echo $dispaly_name ?: "NA"; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $advocate_name ?: "NA"; ?></td>                
                
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div class="mt-26 red-txt center">No Recrods Found</div>
    <?php
    } ?>
</div>
<script>
    $("#example2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "searching": false,
        "buttons": [{
            extend: 'print',
            text: 'Print',
            className: 'btn-primary quick-btn',
            customize: function(win) {
                $(win.document.body).find('h1').remove();
            }
        }]
        
    });
</script>