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
            <tr style="border: 1px solid #000000; border-collapse: collapse;">
                <th bgcolor= #CCFFFF rowspan=2 style="border: 1px solid #000000; border-collapse: collapse;">Date</th>
                <th  bgcolor= #CCFFFF colspan="3" style="border: 1px solid #000000; border-collapse: collapse;">Filed</th>
                <th  bgcolor= #CCFFFF rowspan= 2 style="border: 1px solid #000000; border-collapse: collapse;">Re-Filed</th>
                <th bgcolor= #CCFFFF  rowspan= 2 style="border: 1px solid #000000; border-collapse: collapse;">Registered</th>
                <th bgcolor= #CCFFFF rowspan= 2 style="border: 1px solid #000000; border-collapse: collapse;">Verified</th>
            </tr>
            <tr style="border: 1px solid #000000; border-collapse: collapse;">
                <th bgcolor= #CCFFFF style="border: 1px solid #000000; border-collapse: collapse;">Total</th>
                <th bgcolor= #CCFFFF style="border: 1px solid #000000; border-collapse: collapse;">Physical</th>
                <th  bgcolor= #CCFFFF style="border: 1px solid #000000; border-collapse: collapse;">E-Filing</th>
            </tr>';

            foreach ($result as $key => $row)
            {
                $timestamp = strtotime($row['filing_date']);

                // Creating new date format from that timestamp
                $new_date = date("d-m-Y", $timestamp);

                //echo $new_date; // Outputs: 31-03-2019
                $output .= '<tr>'
                . '<td bgcolor=white style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.$new_date.'</td>'
                . '<td bgcolor=#e6ffe6 style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['filed']).'</td>'
                . '<td  bgcolor=#e6ffe6 style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['physical_filed']).'</td>'
                . '<td   bgcolor=#e6ffe6 style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['efiled']).'</td>'
                . '<td bgcolor= #fff7ee style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['refiled']).'</td>'
                . '<td  bgcolor= #fff7ee style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['registered']).'</td>'
                . '<td bgcolor= #fff7ee style="text-align:center;border: 1px solid #000000; border-collapse: collapse;">'.isset($row['verified']).'</td>'
                . '</tr>';

                $tot_filing = $tot_filing + isset($row['filed']);
                $tot_efiled = $tot_efiled + isset($row['efiled']);
                $tot_counter = $tot_counter + isset($row['physical_filed']);
                $tot_refiled = $tot_refiled + isset($row['refiled']) ;
                $tot_registered = $tot_registered + isset($row['registered']);
                $tot_verified = $tot_verified + isset($row['verified']);
            }
            $output.='<tr>'
            . '<td bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>TOTAL</B></td>'
            . '<td bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_filing.'</B></td>'
            . '<td  bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_counter.'</B></td>'
            . '<td  bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_efiled.'</B></td>'
            . '<td bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_refiled.'</B></td>'
            . '<td bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_registered.'</B></td>'
            . '<td  bgcolor= #CCFFFF style="text-align:center;border: 1px solid #000000; border-collapse: collapse;"><B>'.$tot_verified.'</B></td>'
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
