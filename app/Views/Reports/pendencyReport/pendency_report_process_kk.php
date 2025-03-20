<h4>Report Between <?= $tdt1 ?> AND <?= $tdt2 ?></h4>

<table border="1">
    <thead>
        <tr>
            <th>Description</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Pendency as on <?= $tdt1 ?> (Morning)</td>
            <td><?= $prev_dt_pendency ?></td>
        </tr>
        <tr>
            <td>Institution between dates <?= $tdt1 ?> AND <?= $tdt2 ?></td>
            <td><?= $inst ?></td>
        </tr>
        <tr>
            <td>Disposal between dates <?= $tdt1 ?> AND <?= $tdt2 ?></td>
            <td><?= $dispose ?></td>
        </tr>
        <tr>
            <td>Pendency as on <?= $tdt2 ?> (Evening)</td>
            <td><?= $to_dt_pendency ?></td>
        </tr>
        <tr>
            <td>Difference Pendency as on <?= $tdt2 ?> (Evening)</td>
            <td><?= (($prev_dt_pendency + $inst) - $dispose) - $to_dt_pendency ?></td>
        </tr>
        <tr>
            <td>Pendency As On <?= date('d-m-Y H:i:s') ?></td>
            <td><?= $pendency ?></td>
        </tr>
    </tbody>
</table>

<h4>Pendency included: All Registered Cases + Un-Registered Cases Listed Before Court + All Restored Cases</h4>
<h4>Pendency not included: Un-Registered Cases Listed Before Chamber Judge and Registrar Court + All Misc. Applications (M.A.s filed in Disposed cases)</h4>

<div>
    <button id="cmdPrnRqs2" onClick="CallPrint('r_box');">PRINT</button>
</div>