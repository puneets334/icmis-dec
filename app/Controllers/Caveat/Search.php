<?php
namespace CodeIgniter\Validation;
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Entities\Model_CaveatA;
use App\Models\Filing\Model_caveat;

class Search extends BaseController
{
    public $Model_caveat;
    public $Model_caveat_a;
    function __construct()
    {   unset($_SESSION['caveat_details']);
        $this->Model_caveat= new Model_caveat();
        $this->Model_caveat_a= new Model_CaveatA();
    }
    public function index()
    {
        if ($this->request->getMethod() === 'post' && $this->validate([
                'caveat_number' => ['label' => 'Caveat Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'caveat_year' => ['label' => 'Caveat Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $caveat_number = $this->request->getPost('caveat_number');
                $caveat_year = $this->request->getPost('caveat_year');
                 $caveat_no=$caveat_number.$caveat_year;
                $get_main_table=$this->Model_caveat->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
                if (empty($get_main_table)){
                    $get_main_table=$this->Model_caveat_a->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
                }
            if ($get_main_table){
                $this->session->set(array('caveat_details'=> $get_main_table));
                return redirect()->to('Caveat/Modify');exit();
            }else{
                session()->setFlashdata("message_error", 'Data not Fount');
            }
        }
        $data['casetype']=get_from_table_json('casetype');
        //echo '<pre>';print_r($data);exit();
        return view('Caveat/search',$data);
    }

}
