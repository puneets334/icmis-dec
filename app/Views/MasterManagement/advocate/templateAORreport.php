<style>
        /* body {
            font-family: "Open Sans", helvetica, arial;
        }

        .css-serial {
            counter-reset: serial-number;
        }

        .css-serial td:first-child:before {
            counter-increment: serial-number;
            content: counter(serial-number);
        }

        #para {
            color: red;
            text-align: center;
            font-size: 20px;
            margin-left: 5px;
            margin-top: 5px;
        } */
    </style>
<script>
        
    </script>
      <?php if ($Reportsget): ?>
        <h4 style="text-align:center;font-size: 16px;">
            <b>List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?></b>
        </h4>
        <br>
        <div>
            <table id="tab" class="table table-bordered">
                <thead>
                    <tr style="color:red">
                    <th>SNo</th>
                        <th>Case</th>
                        <th>Cause Title</th>
                        <th>Main/Connected</th>
                        <th>Misc Regular</th>
                        <th>Ready NotReady</th>
                        <th>Section Name</th>
                        <th>Dealing Assistant</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($Reportsget as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['no']; ?></td>
                            <td><?php echo $row['causetitle']; ?></td>
                            <td><?php echo $row['main_connected']; ?></td>
                            <td><?php echo $row['misc_regular']; ?></td>
                            <td><?php echo $row['ready_notready']; ?></td>
                            <td><?php echo $row['section_name']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p id="para">No data Available!!!</p>
    <?php endif; ?>