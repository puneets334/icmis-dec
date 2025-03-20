<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Entities\IncompleteFDRModel;


class IncompleteFDR extends BaseController
{
    public $IncompleteFDRModel;

    function __construct()
    {
        $this->IncompleteFDRModel = new IncompleteFDRModel();
    }

    public function index()
    {
        $data['model'] = $this->IncompleteFDRModel;
        return view('Filing/incompleteFDRView', $data);
    }

    public function incompleteFDR_alt()
    {
        $data['model'] = $this->IncompleteFDRModel;
        return view('Filing/incomplete_fdr_alt', $data);
    }

    public function receiveFDR()
    {  
        // pr($_REQUEST);
        $id = $this->request->getPost('id');
        $value = $this->request->getPost('value');
        $emid =  $_SESSION['login']['empid'];
        $cat = 0;
        $ref = 0;
        $de = 0;
        $scr = 0;
        $tag = 0;
        $fdr = 0;

        $fil_trap_type_row['usertype'] = 108;
        $fil_trap_type_row['type_name'] = 'Filing Dispatch Receive';
        $fdr = 1;

        $data['model'] = $this->IncompleteFDRModel->receiveFDR($fdr,$fil_trap_type_row['usertype'],$de,$emid,$id,$value);
    }


    public function fetchIncompleteMatters()
    {
        $ucode = $_SESSION['login']['usercode'];
        $data['stype'] = $this->request->getPost('stype');
        $data['usertype'] = $_SESSION['login']['usertype'];
        $data['model'] = $this->IncompleteFDRModel;
        $data['data'] =  $data['model']->get_incompleteFDR_data($data['usertype']);
        return view('ManagementReport/DA/verification_get_data_view', $data);
    }


    public function receive()
    {
        $model = new IncompleteFDRModel();
        $id = $this->request->getPost('id');
        $value = $this->request->getPost('value');

        try {
            $model->receiveFile($id, $value);
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function getIncompleteMatters()
    {
        $model = new IncompleteFDRModel();
        $stype = $this->request->getPost('stype');
        $dno = $this->request->getPost('dno');
        $dyr = $this->request->getPost('dyr');
        $ref = 2; // Assuming ref is needed here as well

        if ($stype == 'all_dno') {

            $result = $model->getAllIncompleteMatters();
            // pr('$result');
        } elseif ($stype == 'select_dno') {
            $result = $model->getIncompleteMattersByDiaryNo($dno, $dyr);
        } else {
            $result = [];
        }

        // Render the partial view with the results
        return view('partials/incomplete_matters_table', [
            'results' => $result,
            'fil_trap_type_row' => [
                'usertype' => 108,
                'type_name' => 'Filing Dispatch Receive'
            ],
            'ref' => $ref
        ]);
    }
}
