<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\EliminationModel;

class Elimination extends BaseController
{

    public $model;
    public $diary_no;
    public  $EliminationModel;

    function __construct()
    {
         $this->EliminationModel = new EliminationModel();
    }

    /**
     * To display print advance elemination Page
     *
     * @return void
     */
    public function cl_print_advance_elimination()
    {
        $data['listing_dts'] = $this->EliminationModel->advance_eliminated_dates();
        return  view('Listing/print/cl_print_advance_elimination', $data);
    }

    public function get_cause_list_advance_elimination()
    {
        $list_dt = $this->request->getPost('listing_dts');
        $data['list_dt'] = !empty($list_dt) ? $list_dt : null;
        $data['board_type'] = $this->request->getPost('board_type');
        session()->set('json_elimination_board_type', $data['board_type']);
        session()->set('json_list_dt_board_type', $data['list_dt']);

        $data['advance_eliminations'] = [];
        $data['advance_list_no'] = $data['nmd_note'] = $data['advance_eliminations_print'] = '';


        if(!empty($data['list_dt']) && ($data['list_dt'] != -1) && !empty($data['board_type'])) {
            $isExist = $this->EliminationModel->sc_working_days($data['list_dt']);
            $nmd_note = "";
            if($isExist){
                $nmd_note = "NMD ";
            }
            $data['nmd_note'] = $nmd_note;
            $get_advance_list_no = $this->EliminationModel->advance_list_no($data['list_dt'], $data['board_type']);
            $data['advance_list_no'] = $get_advance_list_no['advance_list_no'] + 1;
            $data['advance_eliminations'] = $this->EliminationModel->get_advance_eliminations($data['list_dt'], $data['board_type']);
            $data['advance_eliminations_print'] = $this->EliminationModel->get_advance_elimination_cl_printed($data['list_dt'], $data['board_type']);
            //pr($data);
        }
        return  view('Listing/print/get_cause_list_advance_elimination', $data);
    }

    public function cl_print_save_advance_elimination()
    {
        $list_dt = $this->request->getPost('list_dt');
        $board_type = $this->request->getPost('board_type');
        $ucode =  session()->get('login')['usercode'];
        $prtContent = $this->request->getPost('prtContent');
        $mainhead ="M";
        //$pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $prtContent);
        
	    $logo_url = base_url('images/scilogo.png');
        $pdf_cont = str_replace("http://localhost/icmis/public/images/scilogo.png", $logo_url, $prtContent);
        
        $advance_eliminations_print = $this->EliminationModel->get_advance_elimination_cl_printed($list_dt, $board_type);
        //$advance_eliminations_print = 0;
        if ($advance_eliminations_print > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Already Printed.']);
        } else {
            $is_print_details_Saved = $this->EliminationModel->save_advance_elimination_print($list_dt, $board_type, $ucode);
            //$is_print_details_Saved = 1;
            if($is_print_details_Saved) {
                $file_path = $mainhead . "_" . $board_type;
                $path_dir = WRITEPATH . "judgment/cl/advance_elimination/$list_dt/";
                if (!is_dir($path_dir)) {
                    mkdir($path_dir, 0777, true);
                }

                $data_file = $path_dir . $file_path . ".html";
                $data_file1 = $path_dir . $file_path . ".pdf";
                if (file_exists($data_file)) {
                    unlink($data_file);
                }
                file_put_contents($data_file, $pdf_cont);
            
            
                //$mpdf = new \Mpdf\Mpdf();
                //$mpdf->WriteHTML(file_get_contents($data_file));
                //$mpdf->Output($data_file1, 'F');

                $mpdf = new \Mpdf\Mpdf();
                $mpdf->SetDisplayMode('fullpage');
                //$mpdf->showImageErrors = true;
                //$mpdf->shrink_tables_to_fit = 0;
                //$mpdf->keep_table_propotions = true;
                $mpdf->WriteHTML($pdf_cont);
                $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);
        
                    //$this->EliminationModel->get_cl_elimination_json($list_dt, $board_type, $ucode);
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Advance Elimination List Ported/Published Successfully.']);    
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error:Advance Elimination Not Ported/Published.']);
            }
        }
    }

    public function cl_print_final_elimination()
    {
        $data['listing_dts'] = $this->EliminationModel->advance_eliminated_dates();
        return  view('Listing/print/cl_print_final_elimination', $data);
    }

    public function get_cause_list_final_elimination()
    {
        $data['list_dt'] = $this->request->getPost('listing_dts');
        $data['board_type'] = $this->request->getPost('board_type');
        session()->set('final_eleimnation_lst_dt', $data['list_dt']);
        session()->set('final_eleimnation_board_type', $data['board_type']);
        $data['final_eliminations'] = [];
        $data['advance_list_no'] = $data['nmd_note'] = $data['final_eliminations_print'] = '';
        if(!empty($data['list_dt']) && ($data['list_dt'] != -1) && !empty($data['board_type'])) {
            $isExist = $this->EliminationModel->sc_working_days($data['list_dt']);
            $nmd_note = "";
            if($isExist){
                $nmd_note = "NMD ";
            }
            $data['nmd_note'] = $nmd_note;
            $get_advance_list_no = $this->EliminationModel->list_no_from_final($data['list_dt'], $data['board_type']);
            $data['advance_list_no'] = $get_advance_list_no['advance_list_no'] + 1;
            $data['final_eliminations'] = $this->EliminationModel->getListings($data['list_dt'], $data['board_type']);           
            //$data['final_eliminations'] = $this->EliminationModel->get_final_eliminations($data['list_dt'], $data['board_type']);
            $data['final_eliminations_print'] = $this->EliminationModel->get_final_elimination_cl_printed($data['list_dt'], $data['board_type']);
            $data['elimination_model'] = $this->EliminationModel;
        }
        return  view('Listing/print/get_cause_list_final_elimination', $data);
    }

    public function cl_print_save_final_elimination() {
        $list_dt = $this->request->getPost('list_dt');
        $board_type = $this->request->getPost('board_type');
        $ucode =  session()->get('login')['usercode'];
        $prtContent = $this->request->getPost('prtContent');
        $mainhead ="M";
        //$pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $prtContent);
        $logo_url = base_url('images/scilogo.png');
        $pdf_cont = str_replace("http://localhost/icmis/public/images/scilogo.png", $logo_url, $prtContent);

        $final_eliminations_print = $this->EliminationModel->get_final_elimination_cl_printed($list_dt, $board_type);
        //$final_eliminations_print = 0;
        if ($final_eliminations_print > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Already Printed.']);
        } else {
            $is_print_details_Saved = $this->EliminationModel->save_final_elimination_print($list_dt, $board_type, $ucode);
            //$is_print_details_Saved = 1;
            if($is_print_details_Saved) {
                $file_path = $mainhead . "_" . $board_type;
                $path_dir = WRITEPATH . "judgment/cl/final_elimination/$list_dt/";
                if (!is_dir($path_dir)) {
                    mkdir($path_dir, 0777, true);
                }

                $data_file = $path_dir . $file_path . ".html";
                $data_file1 = $path_dir . $file_path . ".pdf";
                if (file_exists($data_file)) {
                    unlink($data_file);
                }
                file_put_contents($data_file, $pdf_cont);
                //$composerAutoload = WRITEPATH . 'vendor\autoload.php';

                /*if (file_exists($composerAutoload)) {
                    require_once $composerAutoload;
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML(file_get_contents($data_file));
                    $mpdf->Output($data_file1, 'F');

                    $this->EliminationModel->cl_final_elimination_json();
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Final Elimination List Ported/Published Successfully.']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Composer autoload file not found.']);
                }*/

                $mpdf = new \Mpdf\Mpdf();
                $mpdf->SetDisplayMode('fullpage');
                //$mpdf->showImageErrors = true;
                //$mpdf->shrink_tables_to_fit = 0;
                //$mpdf->keep_table_propotions = true;
                $mpdf->WriteHTML($pdf_cont);
                $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);
                //$this->EliminationModel->cl_final_elimination_json();
                return $this->response->setJSON(['status' => 'success', 'message' => 'Final Elimination List Ported/Published Successfully.']);

            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error:Final Elimination Not Ported/Published.']);
            }
        }
    }

}
