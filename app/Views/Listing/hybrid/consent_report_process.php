<?php
$title = "Directions Received for Physical Hearing Mode (With Virtual Option) : ";
if($_POST['list_type'] == 1){
    $title .= "Weekly List No. ".$_POST['weeklyno']." Year ".$_POST['weeklyyear'];
}
// pr($consentReport);

if($consentReport) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="../../js/jquery3.3.1.js"></script>
        <script src="../../js/datatable.min.js"></script>
        <script src="../../js/datatables.buttons.min.js"></script>
        <script src="../../js/buttons.flash.min.js"></script>
        <script src="../../js/jszip.min.js"></script>
        <script src="../../js/pdfmake.min.js"></script>
        <script src="../../js/vfs_fonts.js"></script>
        <script src="../../js/buttons.html5.min.js"></script>
        <script src="../../js/buttons.print.min.js"></script>


            <style>
            #err{
                color: red;
                margin-left: 30%;
                font-size: 24px;
            }

            body{
                font-family: "Open Sans", helvetica, arial;
                font-size: 14px;
            }

            table{
                border-collapse: collapse;
                margin: 30px 0px 30px;
                background-color: #fff;
                font-size: 14px;
            }

            table tr{
                height: 40px;
            }
            table th{
                color: #111;
                font-weight: bold;
                font-size: 14px;
                text-align: center;
            }

            table td, th{
                padding: 6px 1px 6px 5px;
                border: 1px solid #ccc;
            }

            table tr:nth-of-type(odd)
            {
                background: #eee;
            }

            #head{
                font-weight: bold;
                font-size: 35px;
                text-align: center;
            }

        </style>

        <script>
            var filename = '<?=$title?>';
            var title = '<?=$title?>';
            $(document).ready(function() {
                $('#tab').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt',
                            filename: filename,
                            title:title,
                            text: 'Export to Excel',
                            autoFilter: true,
                            sheetName: 'Sheet1'

                        },

                        {
                            extend: 'pdf', className: 'btn btn-primary glyphicon glyphicon-file',
                            filename: filename,
                            title: title,
                            pageSize: 'A4',
                            orientation: 'landscape',
                            text: 'Save as Pdf',
                            customize: function(doc) {
                                doc.styles.title = {

                                    fontSize: '18',
                                    alignment: 'left'

                                }
                                // doc.content[1].table.widths = [25,88,230,130]; Width of Column in PDF
                            }
                        },
                        {
                            extend: 'print',className: 'btn btn-primary glyphicon glyphicon-print',
                            // filename: filename,
                            title: title,
                            pageSize: 'A4',
                            orientation: 'portrait',
                            text: 'Print',
                            autoWidth: false,
                            columnDefs: [{
                                "width": "20px", "targets":[0] }],

                            customize: function ( win ) {
                                $(win.document.body).find('h1').css('font-size', '20px');
                                $(win.document.body).find('h1').css('text-align', 'left');
                                $(win.document.body).find('tab').css('width', 'auto');

                            }

                        }
                    ],

                    paging: false,
                    ordering: false,
                    info: false,
                    // columnDefs: [{"width": "20px", "targets": [0]},
                    //                 {"width": "40px", "targets": [1]},
                    //                 {"width": "250px", "targets": [2]}],
                    searching: false,


                } );
            } );
        </script>
    </head>
    <body>


    <div style="align:center;">
        <h2><?=$title?></h2>
        <table id="tab">
            <thead><tr style="background-color:darkgrey;">
                <th style="width:10%;">SNo</th>
                <th style="width:20%;">Case No.</th>
                <th style="width:25%;">Cause Title</th>
                <th style="width:5%;">Court</th>
                <th style="width:10%;">Hearing Mode</th>
                <th style="width:30%;">Details</th>
            </thead><tbody>

            <?php
            // pr($consentReport);
            $psrno = "1";
            foreach($consentReport as $row){
                if($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0){
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                }
                else{
                    $is_connected = "<span style='color:red;'>Conn.</span>";
                }
                if($is_connected != ''){
                    $print_srno = "";

                }
                else{
                    $print_srno = $print_srno;
                    $psrno++;
                }
                ?>
                <tr>
                    <td><?=$print_srno.$is_connected?></td>
                    <td><?=$row['reg_no_display'].' @ '.$row['diary_no']?></td>
                    <td><?=$row['pet_name'].' Vs. '.$row['res_name']?></td>
                    <td><?=$row['court_no']?></td>
                    <td><?php
                        if($row['consent'] == 'P'){echo "Physical";}
                        else if($row['consent'] == 'V'){echo "VC";}
                        else if($row['consent'] == 'H'){echo "Hybrid";}
                        ?></td>

                    <td>
                        <?php

                        echo "List from dt. ".date("d-m-Y", strtotime($row['from_dt'])).' to '.date("d-m-Y", strtotime($row['to_dt']));
                        if($row['hearing_from_time'] != '00:00:00'){
                            echo " From Time : ".date("g:i A", strtotime($row['hearing_from_time']));
                        }
                        if($row['hearing_to_time'] != '00:00:00'){
                            echo " To Time : ".date("g:i A", strtotime($row['hearing_to_time']));
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>
    </div>
    </body>
    </html>
    <?php
}
else{
    echo "No Records Found";
}

?>