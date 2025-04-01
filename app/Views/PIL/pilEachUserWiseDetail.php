<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><h2 align="center">PIL Updation on <?=date('d-m-Y',strtotime($dated))?></h2></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?= view('header') ?>
     


    <style>
        .box.box-danger {
            border-top-color: #dd4b39;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>
</head>
<body >

<div class="content-fluid">
    <section class="content">
        <div id="printable" class="box box-danger">

            <h3 align="center">PIL Updation on <?=date('d-m-Y',strtotime($dated))?></h3>
            <?php
            if(isset($pil_result) && sizeof($pil_result)>0 ) {
                ?>

                <table id="reportTable1" class="table table-striped table-hover">
                    <!--    <table id="example1" class="table table-striped table-bordered">-->
                    <thead>
                    <tr>
                        <th width="4%">S.No.</th>
                        <th width="7%">Inward Number</th>
                        <th width="15%">Address To</th>
                        <th width="25%">Received From</th>
                        <th width="7%">Received On</th>
                        <th width="6%">Petition Date</th>
                        <th width="20%">Status</th>
                        <th width="16%">Updated By</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    foreach ($pil_result as $result) {
                        $i++;
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$result['pil_diary_number'];?></td>
                            <td><?=$result['address_to'];?></td>
                            <td><?=$result['received_from'];?><br/><?=$result['address'];?>
                                <?php
                                if(!empty($result['state_name'])){
                                    echo " ,State: ".$result['state_name'];
                                }
                                if(!empty($result['email'])){
                                    echo "<br/> Email: ".$result['email'];
                                }
                                if(!empty($result['mobile'])){
                                    echo "<br/> Mobile: ".$result['mobile'];
                                }
                                ?>
                            </td>
                            <td><?=!empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>
                            <td><?=!empty($result['petition_date'])?date("d-m-Y", strtotime($result['petition_date'])):null?></td>
                            <td><?php
                                if(!empty($result['action_taken']))
                                {
                                    switch (trim($result['action_taken'])){
                                        case "L":{
                                            $actionTakenText = "No Action Required"; break;
                                        }
                                        case "W":{
                                            $actionTakenText = "Written Letter to ".$result['written_to']. " on ".date('d-m-Y', strtotime($result['written_on'])) ; break;
                                        }
                                        case "R":{
                                            $actionTakenText = "Letter Returned to Sender on ".date('d-m-Y', strtotime($result['return_date'])) ; break;
                                        }
                                        case "S":{
                                            $actionTakenText = "Letter Sent To ".$result['sent_to']. " on ".date('d-m-Y', strtotime($result['sent_on'])); break;
                                        }
                                        case "T":{
                                            $actionTakenText = "Letter Transferred To ".$result['transfered_to']." on ".date('d-m-Y', strtotime($result['transfered_on'])); break;
                                        }
                                        case "I":{
                                            $actionTakenText = "Letter Converted To Writ"; break;
                                        }
                                        case "O":{
                                            $actionTakenText = "Other Remedy"; break;
                                        }
                                        default:{
                                            $actionTakenText = "UNDER PROCESS"; break;
                                        }
                                    }
                                    echo $actionTakenText;
                                }else{
                                    $actionTakenText = "UNDER PROCESS";
                                    echo $actionTakenText;
                                }
                                ?>
                            </td>
                            <td><?=$result['username'].'('.$result['empid'].')'?>
                                <br/> At: <?=date('d-m-Y h:i:s A', strtotime($result['updated_on']))?></td>
                        </tr>

                        <?php
                    }?>
                    </tbody>


                </table>

                <?php
            }

            ?>
        </div>

    </section>
    <!-- /.content -->
    <!--</div>-->
    <!-- /.container -->
</div>


<script>

    //$(function() {
        //     $("#example1").DataTable({
        //         "responsive": true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //
        // });

        $(document).ready(function() {
    $('#reportTable1').DataTable({
        dom: 'Bfrtip',
        "pageLength": 15,
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                title: 'PIL Updation on <?= date("d-m-Y", strtotime($dated)) ?>', // Ensuring no unwanted title appears
                customize: function (win) {
                    $(win.document.body).css('text-align', 'center'); // Align all content centrally
                    //$(win.document.body).prepend('<h2>PIL Updation on <?= date("d-m-Y", strtotime($dated)) ?></h2>'); // Insert title manually
                }
            },
            'pageLength'
        ],
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ]
    });
});



        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#reportTable1 thead tr').clone(true).appendTo( '#reportTable1 thead' );
            $('#reportTable1 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                var width = $(this).width();
                if(width>260){
                    width=width-80;
                }
                else if(width<100){
                    width=width+20;
                }
                $(this).html( '<input type="text" style="width: '+width+'px" placeholder="'+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

        });
</script>
</body>
</html>