<h2 style="text-align: center;text-transform: capitalize;color: blue;"> Section-Year wise Pendency Report as on <?= date('d/m/Y'); ?> </h2>
<table class="table table-striped custom-table">
    <thead>
        <tr>
            <th>Sr.No.</th>
            <th>Case Year</th>
            <th>II</th>
            <th>II-A</th>
            <th>II-B</th>
            <th>II-C</th>
            <th>III</th>
            <th>III-A</th>
            <th>III-B</th>
            <th>IV</th>
            <th>IV-A</th>
            <th>IV-B</th>
            <th>IX</th>
            <th>PIL-W</th>
            <th>X</th>
            <th>XI</th>
            <th>XI-A</th>
            <th>XII</th>
            <th>XII-A</th>
            <th>XIV </th>
            <th>XV</th>
            <th>XVI</th>
            <th>XVI-A</th>
            <th>XVII</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $srno = 1;
        $total_ii = 0;
        $total_iia = 0;
        $total_iib = 0;
        $total_iic = 0;
        $total_iii = 0;
        $total_iiia = 0;
        $total_iiib = 0;
        $total_iv = 0;
        $total_iva = 0;
        $total_ivb = 0;
        $total_ix = 0;
        $total_pil = 0;
        $total_x = 0;
        $total_xi = 0;
        $total_xia = 0;
        $total_xii = 0;
        $total_xiia = 0;
        $total_xiv = 0;
        $total_xv = 0;
        $total_xvi = 0;
        $total_xvia = 0;
        $total_xvii = 0;
        if (!empty($array_result)) {
            if (count($array_result) > 0) {
                foreach ($array_result as $row) {
                    // pr($row);
                    $total = $row['total'];
        ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $srno ?></td>
                        <td><?php echo $row['caseyear'] ?></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=20"><?php echo $row['II'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=21"><?php echo $row['II-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=55"><?php echo $row['II-B'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=74"><?php echo $row['II-C'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=22"><?php echo $row['III'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=23"><?php echo $row['III-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=75"><?php echo $row['III-B'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=24"><?php echo $row['IV'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=25"><?php echo $row['IV-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=26"><?php echo $row['IV-B'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=27"><?php echo $row['IX'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=32"><?php echo $row['PIL-W'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=42"><?php echo $row['X'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=43"><?php echo $row['XI'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=44"><?php echo $row['XI-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=45"><?php echo $row['XII'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=54"><?php echo $row['XII-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=48"><?php echo $row['XIV'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=49"><?php echo $row['XV'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=50"><?php echo $row['XVI'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=51"><?php echo $row['XVI-A'] ?></a></td>
                        <td><a target="_blank" href="<?php echo base_url('ManagementReports/Pending')?>/details?year=<?= $row['caseyear'] ?>&section=52"><?php echo $row['XVII'] ?></a></td>
                    </tr>
        <?php $srno++;
                    $total_ii += $row['II'];
                    $total_iia += $row['II-A'];
                    $total_iib += $row['II-B'];
                    $total_iic += $row['II-C'];
                    $total_iii += $row['III'];
                    $total_iiia += $row['III-A'];
                    $total_iiib += $row['III-B'];
                    $total_iv += $row['IV'];
                    $total_iva += $row['IV-A'];
                    $total_ivb += $row['IV-B'];
                    $total_ix += $row['IX'];
                    $total_pil += $row['PIL-W'];
                    $total_x += $row['X'];
                    $total_xi += $row['XI'];
                    $total_xia += $row['XI-A'];
                    $total_xii += $row['XII'];
                    $total_xiia += $row['XII-A'];
                    $total_xiv += $row['XIV'];
                    $total_xv += $row['XV'];
                    $total_xvi += $row['XVI'];
                    $total_xvia += $row['XVI-A'];
                    $total_xvii += $row['XVII'];
                }
            }
        }
        ?>
        <tr style="font-weight: bold;">
            <td colspan="2">Current Pendency:<?= $total; ?></td>
            <td><?= $total_ii; ?></td>
            <td><?= $total_iia; ?></td>
            <td><?= $total_iib; ?></td>
            <td><?= $total_iic; ?></td>
            <td><?= $total_iii; ?></td>
            <td><?= $total_iiia; ?></td>
            <td><?= $total_iiib; ?></td>
            <td><?= $total_iv; ?></td>
            <td><?= $total_iva; ?></td>
            <td><?= $total_ivb; ?></td>
            <td><?= $total_ix; ?></td>
            <td><?= $total_pil; ?></td>
            <td><?= $total_x; ?></td>
            <td><?= $total_xi; ?></td>
            <td><?= $total_xia; ?></td>
            <td><?= $total_xii; ?></td>
            <td><?= $total_xiia; ?></td>
            <td><?= $total_xiv; ?></td>
            <td><?= $total_xv; ?></td>
            <td><?= $total_xvi; ?></td>
            <td><?= $total_xvia; ?></td>
            <td><?= $total_xvii; ?></td>
        </tr>
    </tbody>
</table>