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
		$result = $this->CoramGivenByModel->removeCoram();
		return $this->response->setJSON([
      'status' => true,
      'data' => $result,
    ]);
	}
}
?>
