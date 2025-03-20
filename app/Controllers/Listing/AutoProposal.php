<?php
namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Reports\Listing\ProposalModel;
use App\Models\Casetype;
use CodeIgniter\Email\Email;

class AutoProposal extends BaseController
{
  protected $ProposalModel;

  public function __construct()
  {
    $this->ProposalModel = new ProposalModel();
  }


  //Auto Proposal

  public function auto_proposal_info()
    {
      
        return  view('Listing/Report/auto_proposal_listed_report');
    }

  public function auto_proposal_action()
    {
      $email = \Config\Services::email();
      $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
      $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
      $next_court_work_day_ymd = date("Y-m-d", strtotime(chksDate($cur_ddt)));
      $cur_dttime = date('d-m-Y H:i:s');
      $startTime = explode(' ', microtime());
      $misc_days = $this->ProposalModel->misc_days();

      $msg = "<br/><table border='1' style='color: #00220d; border-collapse:collapse; border:1px solid #7a0707; font-family:verdana; font-size:13px; font-weight:bold'><tr><td style='background:#FFCCFF; padding:4px;'>Query Head</td><td style='background:#FFCCFF; padding:4px;'>Affected Records</td></tr>";
      $msg .= $this->ProposalModel->case_remark_heard_insert();
      $msg .= $this->ProposalModel->case_remark_heard_update();
      $msg .= $this->ProposalModel->part_heard_insert();
      $msg .= $this->ProposalModel->final_hearing_cases();
      $msg .= $this->ProposalModel->chamber_defect_not_remove_90_I();
      $msg .= $this->ProposalModel->chamber_defectNot_remove_90_I_2($misc_days);
      $msg .= $this->ProposalModel->chamber_defectNot_remove_90_U($next_court_work_day_ymd);
      $msg .= $this->ProposalModel->nmd_updation();
      $msg .= $this->ProposalModel->nmd_removal();
      $msg .= $this->ProposalModel->no_of_part_heard();
      $msg .= $this->ProposalModel->remove_part_heard_coram_judge_retired();
      $msg .= $this->ProposalModel->inserted_coram_notice();
      $msg .= $this->ProposalModel->updated_coram_notice();
      $msg .= $this->ProposalModel->inserted_coram_given_by_chief();
      $msg .= $this->ProposalModel->updated_coram_given_by_chief();
      $msg .= $this->ProposalModel->inserted_coram_ma_count();
      $msg .= $this->ProposalModel->Updated_coram_ma_count();
      $msg .= $this->ProposalModel->updated_unreg_fil_dt();
      $msg .= $this->ProposalModel->tp_head_changed();
      $msg .= $this->ProposalModel->bail_category_head_changed();
      $msg .= $this->ProposalModel->tp_bail_ia();
      $msg .= $this->ProposalModel->freshly_adjourned_head_changed();
      $msg .= $this->ProposalModel->last_heardt_bench_flag();
      $msg .= $this->ProposalModel->old_after_notice();
      $msg .= $this->ProposalModel->not_before_judge_entry();
      $msg .= '</table>';
      $headers = [
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html;charset=UTF-8',
        'From' => '<sci@nic.in>',
        'Reply-To' => 'sci@nic.in',
        'Return-Path' => 'sci@nic.in',
        'Disposition-Notification-To' => 'sci@nic.in',
        'X-Confirm-Reading-To' => 'sci@nic.in'
    ];
    $email->setFrom('ca.balkasaiyak@sci.nic.in', 'Your Name'); // Keep this as well
    $email->setTo('tomail@xyz.com');
    
    foreach ($headers as $headerName => $headerValue) {
        $email->setHeader($headerName, $headerValue);
    }
    
    $subject = "SCI proposal " . date('d-m-Y');
    $email->setSubject($subject);
    $email->setMessage($msg); // Use the generated HTML table

      if ($email->send()) {
        $data = $email->printDebugger();
        //print_r($data);
          echo 'Email sent successfully!';
      } else {
          $data = $email->printDebugger();
          echo 'Email sending failed: ' . $data;
      }    
    }
}
