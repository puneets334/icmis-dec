<?php

namespace App\Models\Reports\FilingMonitoring;

use CodeIgniter\Model;

class FilingMonitoringModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function weekly_filing_stats()
    {
        // pr($_REQUEST);

        date_default_timezone_set('Asia/kolkata');
        set_time_limit(25000);

        $st = date('Y-m-d', strtotime($_POST['sdate']));  // start date
        $et = date('Y-m-d', strtotime($_POST['edate']));  // end date

        $cur_dttime = date('d-m-Y H:i');
        $curdate = date('Y-m-d');
        $cur_time = date('H:i');
        $previous_day = date('Y-m-d',strtotime("-1 days"));
        $subject = '';
        $output = '';

        $from_email_id = 'sci@nic.in';
        $to_email_ids='ppavan.sc@nic.in,ca.pnbartwal@sci.nic.in,pavansid@gmail.com';

        $sql = "SELECT DISTINCT ON (filing_date) * FROM filing_stats WHERE filing_date BETWEEN '$st' AND '$et' ORDER BY filing_date ASC, id DESC";

        /*$sql = "SELECT DISTINCT ON (filing_date) * FROM filing_stats WHERE filing_date BETWEEN '2022-07-01' AND '2024-12-31' ORDER BY filing_date ASC, id DESC";*/

        $sql_query = $this->db->query($sql);
        if($sql_query->getNumRows() >= 1)
        {
            $result = $sql_query->getResultArray();

            $subject='Filing Section Statistical Information as on '.date("d/m/y h:i:s");

            $output.='<center><b>SUPREME COURT OF INDIA</b></center>';
            $output.= '<center><i>Computer Cell</i></center><br>';
            $output.= '<div align=right'.'>(Status as on '.date("d/m/y").' @ '.  date("h:i:s").')</div><br>';

            $tot_filing=$tot_efiled=$tot_counter=$tot_refiled=$tot_registered=$tot_verified=0;

            $output .= "<body>";
            $output .= '<table width="100%" cellpadding="5" cellspacing="5" style="border: 1px solid #000000; border-collapse: collapse;">
            <tr style="border: 1px solid #000000; border-collapse: collapse; text-align:center">
                <th rowspan= 2 style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Date</th>
                <th colspan= 3 style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Filed</th>
                <th rowspan= 2 style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Re-Filed</th>
                <th rowspan= 2 style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Registered</th>
                <th rowspan= 2 style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Verified</th>
            </tr>
            <tr style="border: 1px solid #000000; border-collapse: collapse;text-align:center;">
                <th style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Total</th>
                <th style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">Physical</th>
                <th style="background-color: #CCFFFF; border: 1px solid #000000; border-collapse: collapse;">E-Filing</th>
            </tr>';

            

            foreach ($result as $key => $row)
            {
                $timestamp = strtotime($row['filing_date']);

                // Creating new date format from that timestamp
                $new_date = date("d-m-Y", $timestamp);

                //echo $new_date; // Outputs: 31-03-2019
                // echo $row['physical_filed'];

                $filed = isset($row['filed']) ? $row['filed'] : '';
                $physical_filed = isset($row['physical_filed']) ? $row['physical_filed'] : '';
                $efiled = isset($row['efiled']) ? $row['efiled'] : '';
                $refiled = isset($row['refiled']) ? $row['refiled'] : '';
                $registered = isset($row['registered']) ? $row['registered'] : '';
                $verified = isset($row['verified']) ? $row['verified'] : '';

                $output .= '<tr>'
                . '<td style="background-color:white; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$new_date.'</td>'
                . '<td style="background-color:#e6ffe6; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$filed.'</td>'
                . '<td style="background-color:#e6ffe6; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$physical_filed.'</td>'
                . '<td style="background-color:#e6ffe6; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$efiled.'</td>'
                . '<td style="background-color: #fff7ee; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$refiled.'</td>'
                . '<td style="background-color: #fff7ee; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$registered.'</td>'
                . '<td style="background-color: #fff7ee; text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$verified.'</td>'
                . '</tr>';

                $filed_add = isset($row['filed']) ? $row['filed'] : 0;
                $efiled_add = isset($row['efiled']) ? $row['efiled'] : 0;
                $physical_filed_add = isset($row['physical_filed']) ? $row['physical_filed'] : 0;
                $refiled_add = isset($row['refiled']) ? $row['refiled'] : 0;
                $registered_add = isset($row['registered']) ? $row['registered'] : 0;
                $verified_add = isset($row['verified']) ? $row['verified'] : 0;

                $tot_filing = $tot_filing + $filed_add;
                $tot_efiled = $tot_efiled + $efiled_add;
                $tot_counter = $tot_counter + $physical_filed_add;
                $tot_refiled = $tot_refiled + $refiled_add;
                $tot_registered = $tot_registered + $registered_add;
                $tot_verified = $tot_verified + $verified_add;
                // pr($output);
            }
            $output.='<tr>'
            . '<td style="background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>TOTAL</B></td>'
            . '<td style="background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_filing.'</B></td>'
            . '<td style=" background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_counter.'</B></td>'
            . '<td style=" background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_efiled.'</B></td>'
            . '<td style="background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_refiled.'</B></td>'
            . '<td style="background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_registered.'</B></td>'
            . '<td style=" background-color: #CCFFFF; text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_verified.'</B></td>'
            . '</tr>';

            $output .='</table> <BR>';

            $output .= "</body>";
            $output .= '<div class="col-md-12" style="text-align: left; padding-bottom:10px;"><input id="pritResultBtn" type="button" onClick="printDiv()" value="print Result" ></div>';
        }
        // echo $output."<BR>";
        else
        {
            $output .= '<h3 class="sorry">SORRY, No Record Found!!!</h3>';
        }
        return $output;
    }
}
