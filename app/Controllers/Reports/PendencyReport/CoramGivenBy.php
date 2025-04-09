<?php
namespace App\Controllers\Reports\PendencyReport;

use App\Controllers\BaseController;
use App\Models\Reports\PendencyReport\CoramGivenByModel;
use App\Libraries\MCTABLE;

class CoramGivenBy extends BaseController
{
  public $CoramGivenByModel;

	function __construct()
	{
		$this->CoramGivenByModel = new CoramGivenByModel();
	}

	public function index()
	{
		$data['judges'] = $this->CoramGivenByModel->getJudgesList();
		return view('Reports/pendencyReport/coram_del', $data);
	}

	public function removeCoram()
	{
		$request = \Config\Services::request();
        $judge = $request->getPost('judge');
        $crm_dtl = $request->getPost('crm_dtl');
        $mainhead = $request->getPost('mainhead');
		$data['result'] = $this->CoramGivenByModel->removeCoram($judge, $crm_dtl, $mainhead);
		return view('Reports/pendencyReport/remove_coram', $data);
	}
}
?>
