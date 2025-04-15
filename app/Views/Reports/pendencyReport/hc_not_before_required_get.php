<?php if (!empty($result)) {   ?>
    <div id="prnnt" style="text-align: center;">

        <style>
            #customers {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
            }

            #customers td,
            #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #customers tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #customers tr:hover {
                background-color: #ddd;
            }

            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #0d48be;
                color: white;
            }

            /*.button {
                border-radius: 3px;
                background-color: #f4511e;
                border: none;
                color: #FFFFFF;
                text-align: center;
                font-size: 20px;
                padding: 4px;
                width: 100px;
                transition: all 0.5s;
                cursor: pointer;
            }

            .button span {
                cursor: pointer;
                display: inline-block;
                position: relative;
                transition: 0.5s;
            }

            .button span:after {
                content: '\00bb';
                position: absolute;
                opacity: 0;
                top: 0;
                right: -20px;
                transition: 0.5s;
            }

            .button:hover span {
                padding-right: 25px;
            }

            .button:hover span:after {
                opacity: 1;
                right: 0;
            }*/
        </style>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 ml-n4 text-left"><input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary bk_out"></div>
                <div class="col-md-8 text-center">
                    <h3 class="mt-3" style="text-align:center">Not Before Verification</h3>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>

        <table id="customers">
            <tr style="background: #0d48be;">
                <td width="10%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">SrNo.</td>
                <td width="15%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Case No. / Diary No.</td>
                <td width="25%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Cause Title</td>
                <td width="15%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Hon'ble Judge Name</td>
                <td width="15%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Lower Court Case No.</td>
                <td width="15%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Agency</td>
                <td width="15%" style="font-weight: bold; color: #fff; background: #0d48be;padding: 10px">Section / DA</td>
            </tr>
            <?php
            $sno = 1;
            foreach ($result as $key => $ro) {
                $sno1 = $sno % 2;
            ?>
                <tr class="">
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['case_no'] . ' @ ' . $ro['diary_no']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['cause_title']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['judge_name']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['type_sname'] . ' ' . $ro['lct_caseno'] . ' / ' . $ro['lct_caseyear'];  ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['agency_name']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['tentative_section'] . "<br>" . $ro['tentative_da']; ?></td>
                </tr>
            <?php
                $sno++;
            }
            ?>
        </table>
    </div>
<?php } else {
    echo "No Recrods Found";
} ?>