<?php //echo '<pre>';print_r($_SESSION['filing_details']);
if(isset($result) && sizeof($result)>0 && is_array($result)){ $row=$result; ?>

    <table class="tblmid" style="margin-left: auto;margin-right: auto;" border="1" cellspacing="2" cellpadding="4">
        <tr><td><b>Is Efiled</b></td>
            <td><input type="checkbox" name="if_efil" id="if_efil" <?php if($row['is_efiled']=='Y') echo "checked";?>/>

            </td></tr>
        <tr><td><b>Document Now</b></td>
            <td><input type="text" id="docnum" value="<?php echo $row['docnum']; ?>" size="5" maxlength="7" readonly/> /
                <input type="text" id="docyr" value="<?php echo $row['docyear']; ?>" size="4" maxlength="4" readonly/>
                <?php echo ' - '.$row['kntgrp']; ?></td></tr>
        <tr><td><b>Description</b></td>
            <td>
                <select name="m_doc" id="m_doc" <?php if($from=='H'|| $from=='L') echo "disabled"; ?>>
                    <option value="0">Select</option>
                    <?php
                    foreach ($docmaster as $row1){ ?>
                        <option value="<?php echo $row1['doccode']; ?>" <?php if($row1['doccode']==$row['doccode']) echo "Selected";?>>
                            <?php echo $row1['doccode'].' - '.$row1['docdesc']; ?></option>
                        <?php } ?>
                </select>
                <input type="text" id="m_doc1" name="m_doc1" style="width: 450px" value="<?php echo $row['docdesc']; ?>"
                    <?php if(($row['doccode']!=8)||($from=='H'|| $from=='L')  ) echo "disabled"; ?> />
                <input type="hidden" id="hd_doc_type1" value="<?php echo $row['doccode1']; ?>"/>
                <br>
                Description
                <input type="text" size="50" name="m_desc" id="m_desc" maxlength="50" onBlur="replace_amp(this.id)" value="<?php echo $row['other1'];?>"
                    <?php if($row['doccode1']!=19) echo "disabled";?> />
                <?php
                if(($row['doccode']== 1 || $row['doccode']== 12 || $row['doccode']== 13) && ($row['advocate_id'] != 0)) echo '<br><span class=undertxt>Please remove advocate from case also</span>';
                if($row['doccode'] == 8 && ( $from=='H' ||  $from=='L')){
                    echo '<br><span class=undertxt>Auto Proposed Case</span>';

                }
                ?></td>
        </tr>
        <tr><td><b>Remark</b></td><td><input type="text" value="<?php echo $row['remark']; ?>" id="remark_ld" maxlength="200" size="100"   onBlur="replace_amp(this.id)"/></td></tr>
        <tr><td><b>No of Copies</b></td><td><input type="text" value="<?php echo $row['no_of_copy']; ?>" id="noc" size="4"/></td></tr>
        <tr><td><b>Fee</b></td><td><input type="text" value="<?php echo $row['docfee']; ?>" id="df" size="5"/></td></tr>
        <tr><td><b>Advocate</b></td><td><input type="text" value="<?php echo $row['aor_code'];?>" id="aor_code" size="4" onblur="getAdvocateAOR()"/>
                <span id="adv_name"><?php echo $row['advname']; ?></span></td></tr>
        <tr><td><b>Filing Date</b></td> <td><?=!empty($row['ent_dt']) ? date('d-m-Y h:i:s A',strtotime($row['ent_dt'])):''; ?></td></tr>
        <tr><td><b>Received By</b></td><td><?php echo $row['entryuser']; ?></td></tr>

        <tr><th colspan="10">
                <center><input type="button" value="UPDATE" onclick="ia_update_Funct(this.id)" id="<?php echo $idfull; ?>"/>
                &nbsp;
                <input type="button" value="CANCEL" onclick="calcelFunct()" /></b>
                </center>
            </th></tr>
    </table>
<br/>
    <script>
        function getAdvocateAOR()
        {
            var aor_code=document.getElementById('aor_code').value;
            $.ajax({
                type: "GET",
                data: { aorcode: aor_code},
                url: "<?php echo base_url('ARDRBM/IA/get_adv_name_aor'); ?>",
                success: function (data)
                {
                    val = data.split('~');
                    $("#adv_name").html(val[0]);
                }
            });
        }
    </script>
<?php } ?>
