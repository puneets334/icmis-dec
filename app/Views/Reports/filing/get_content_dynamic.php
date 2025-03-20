<div id="printable">
    <center>
        <h2><b>SUPREME COURT OF INDIA</b></h2>

       <?php echo " <h4> (Result generated through Dynamic Report on ".date('d-m-Y')." at ".date('H:i:s A')." )</h4>";?>
    </center>
    <fieldset>
        <legend style="border-bottom: black;"><u>Filtering Criteria</u></legend>
        <?php echo $criteria ?>
    </fieldset>
    <!--<br/>
    <br/>-->
<hr style="height:1px;border-width:0;color:black;background-color:grey;" >
        <div id="disp" class=" tableContainer">
        <?php
        //print_r($result);exit;
        if(isset($result) && $result!=false && $option=="2")
        {
            ?>


            <table id="tblCasesUploadStatus" class="table table-striped table-hover table-bordered" style="width: 100%;">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Diary No/Case No.</th>
                    <th>Cause Title</th>
                    <th>Filing Date</th>
                    <th>Registration Date</th>
                    <th><?php if((int)$showDA==1){?>Dealing Assistant-Section<?php } else {?>Section <?php } ?></th>
                    <th>Matter Type</th>
                    <th>Agency State</th>
                    <th>Agency Name</th>
                    <th>Subject</th>
                    <th>Case Status</th>
                </tr>

                </thead>
                <tbody>

                <?php
                $s_no=1;
                if(!empty($result)){
                    //print_r($result);
                foreach($result as $case)
                { //print_r($case);exit;
                    ?>
                    <tr>
                        <td><?php echo $s_no ?></td>
                        <td>    
                            <?php
                            echo  substr($case['diary_no'],0,-4).'/'.substr($case['diary_no'],-4). " - ".$case['reg_no_display']; ?>
                        </td>
                        <td>
                            <?php echo $case['pet_name']. "<b> VS </b>".$case['res_name']; ?>
                        </td>

                        <td><?php
                            if ($case['diary_no_rec_date'] === "0000-00-00 00:00:00")
                                echo "";
                            else
                                echo  date('d-m-Y', strtotime($case['diary_no_rec_date'])); ?>
                        </td>
                        <td><?php
                            if ($case['active_fil_dt'] === "0000-00-00 00:00:00")
                                echo "";
                            else
                                echo date('d-m-Y', strtotime($case['active_fil_dt'])); ?></td>
                        <td><?php if((int)$showDA==1) echo $case['name']." (".$case['empid'].") -".$case['section_name']; else echo $case['section_name'];?></td>

                        <td><?php if($case['mf_active']=="M")
                                echo "Miscellaneous";
                            elseif($case['mf_active']=="F")
                                echo "Regular"; ?>
                        </td>
                        <td>
                            <?php echo $case['agency_state']; ?>
                        </td>
                        <td>
                            <?php echo $case['agency_name']; ?>
                        </td>
                        <td>
                            <?php echo $case['subject']; ?>
                        </td>
                        <td>
                            <?php if( $case['c_status']=="P")
                                echo "Pending";
                            else
                                echo "Disposed <br>(Order date: ".date('d-m-Y',strtotime($case['ord_dt'])).")";
                            ?>
                        </td>

                    </tr>
                    <?php
                    $s_no++;
                }   //for each
            }
                ?>
                </tbody>
            </table>


        <?php } else if($option=="1") {?>

            <div>
                <font style="font-size: 18px; font-weight: bold;"> Total number of such matters are : </font><font style="font-size:18px;"><?php echo $result; ?></font>

                <button style="margin-right: 20px; float:right; width:60px; height:40Px; background-color: lightgrey;" type="submit" id="Print" name="Print" onclick="hideButton();printDiv('printable')"><b>Print</b></button>
            </div>
        <?php } else {?>
            <center> <span style="color: red;"><b>No Data Found !!</b></span></center>
        <?php } ?>
    </div>

<script>

    $(document).ready(function() {
        $.extend( $.fn.dataTable.defaults, {
            "buttons": [
                $.extend( true, {},  {
                    extend: 'pdfHtml5',

                    title: 'Sports Event Report',
                    filename: 'Sports_Event_Report'
                } )
            ]
        } );

        $('#tblCasesUploadStatus').DataTable( {
            //dom: 'Bfrtip',
            dom: 'B<"top"lfip>rt<"bottom"ip><"clear">',
            paging: true,
            "lengthMenu": [10,20,50,100],
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            /*"scrollY": "50vh",
            "scrollX": true,
            "scrollCollapse": true,*/
            "footerCallback":"",
            buttons: [
                {
                    extend: 'print',
                    title: '',
                    footer:'',
                    header:'',
                    messageTop: function () {
                          return '<?php echo "<center><h2><b>Supreme Court Of India</b></h2><h4> (Result generated through Dynamic Report on ".date('d-m-Y')." at ".date('H:i:s A')." )</h4></center><br/><u>Conditions Selected</u><br/>".$criteria; ?>';
                    },
                    messageBottom: null
                },
                {
                    extend:'pdf',
                    title:''
                }
            ]
        } );
    } );

    function hideButton() {
        document.getElementById('Print').style.visibility='hidden';
    }
</script>