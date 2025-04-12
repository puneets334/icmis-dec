

<table border="1" class="table table-bordered table-striped">
<h4>Report Between <?= $tdt1 ?> AND <?= $tdt2 ?></h4>
    <thead>
        <tr>
            <th><b>Description</b></th>
            <th><b>Total</b></th>
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

<h4><b>Pendency included: All Registered Cases + Un-Registered Cases Listed Before Court + All Restored Cases<b></h4>
<br><br>
<h4><b>Pendency not included: Un-Registered Cases Listed Before Chamber Judge and Registrar Court + All Misc. Applications (M.A.s filed in Disposed cases)</b></h4>

<div>
    <button id="cmdPrnRqs2" onClick="CallPrint('r_box');" class="btn btn-primary">PRINT</button>
</div>