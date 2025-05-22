<?php
namespace CodeIgniter\Validation;
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Entities\Model_CaveatAdvocate;
use App\Models\Entities\Model_CaveatLowerct;
use App\Models\Entities\Model_CaveatParty;
use App\Models\Entities\Model_renewed_caveat;
use App\Models\Filing\Model_caveat;
use CodeIgniter\Model;

class Renew extends BaseController
{
    public $Model_caveat;
    public $Model_renewed_caveat;
    public $Model_caveat_party;
    public $Model_caveat_advocate;
    public $Model_caveat_lowerct;
    function __construct()
    {   //unset($_SESSION['caveat_details']);
        $this->Model_caveat= new Model_caveat();
        $this->Model_renewed_caveat= new Model_renewed_caveat();
        $this->Model_caveat_party= new Model_CaveatParty();
        $this->Model_caveat_advocate= new Model_CaveatAdvocate();
        $this->Model_caveat_lowerct= new Model_CaveatLowerct();
    }
    public function index()
    {
        return view('Caveat/search_renew');
    }
    public function get_caveat_info()
    {
        if ($this->request->getMethod() === 'post') {
            $this->validation->setRule('caveat_number', 'caveat_number', 'required');
            $this->validation->setRule('caveat_year', 'caveat_year', 'required');

            $caveat_number = $this->request->getPost('caveat_number');
            $caveat_year = $this->request->getPost('caveat_year');
            $caveat_no=$caveat_number.$caveat_year;
            $data = [
                'caveat_number'=>$caveat_number,
                'caveat_year'=>$caveat_year,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('caveat_number').$this->validation->getError('caveat_year');
                echo $this->validation->listErrors();exit();
            }

            $cause_title=$this->Model_caveat->select("*,pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no")->where(['caveat_no'=>$caveat_no])->get()->getResultArray();
            if ($cause_title){

            }else{
                echo '3@@@Data not found';exit();
            }

            $data['cause_title']=$cause_title;
            $data['is_renewed']=$this->Model_caveat->select('*')->where(['caveat_no'=>$caveat_no,'is_renew'=>'Y'])->get()->getResultArray();

            $data['get_new_caveat']=$this->Model_renewed_caveat->select("CONCAT(left((cast(old_caveat_no as text)),-4),'/', right((cast(old_caveat_no as text)),4)) as old_caveat_no,
CONCAT(left((cast(new_caveat_no as text)),-4),'/', right((cast(new_caveat_no as text)),4)) as new_caveat_no,TO_CHAR(renew_date, 'DD-MM-YYYY') as renew_date")->where(['old_caveat_no'=>$caveat_no])->get()->getResultArray();
            $data['caveat_no']=$caveat_no;
            $resul_view= view('Caveat/get_caveat_info_renew',$data);
            $this->session->set(array('caveat_details' => $cause_title[0]));
            echo '1@@@'.$resul_view;exit();

        }

    }

    public function copy_caveat(){

        if ($this->request->getMethod() === 'post') {
            $this->validation->setRule('caveat_number', 'caveat_number', 'required');
            $this->validation->setRule('caveat_year', 'caveat_year', 'required');
            $caveat_number =$_REQUEST['caveat_number'];
            $caveat_year =$_REQUEST['caveat_year'];
            $data = [
                'caveat_number'=>$caveat_number,
                'caveat_year'=>$caveat_year,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('caveat_number').$this->validation->getError('caveat_year');
                echo $this->validation->listErrors();exit();
            }
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $cav_no=$caveat_number.$caveat_year;
            $ucode =$_SESSION['login']['usercode'];
            $year = date('Y');
            $fil_q=is_data_from_table('master.cnt_caveat',['caveat_year'=>$year],'max_caveat_no','R');
            $fil=$fil_q['max_caveat_no'];
            $fil++;
            $diary_no = $fil.$year;
            $caveatno = $fil.$year;
            $caveat_no = $fil .'/'. $year;
            $rs1=$rs3=$rs4=$rs5=false;
            $insert_renewed_caveat=$ContactServerRoom='';
            $from_caveat_no=$this->Model_caveat->select('*')->where(['caveat_no'=>$cav_no])->get()->getResultArray();
            if (!empty($from_caveat_no)){
                foreach ($from_caveat_no as $caveat_row) {
                    $data_addon = [
                        'caveat_no' => $caveatno,
                        'diary_user_id' => $ucode,
                        'diary_no_rec_date' => date("Y-m-d H:i:s"),
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    unset($caveat_row['caveat_no']);unset($caveat_row['usercode']);unset($caveat_row['ent_dt']);
                    unset($caveat_row['diary_user_id']);
                    unset($caveat_row['diary_no_rec_date']);
                    unset($caveat_row['create_modify']);
                    unset($caveat_row['updated_by']);
                    unset($caveat_row['updated_by_ip']);
                    $final_array = array_merge($data_addon, $caveat_row);
                    $rs1 = insert('caveat', $final_array);
                }
            }
                if ($rs1){
                    $from_caveat_party=$this->Model_caveat_party->select('*')->where(['caveat_no'=>$cav_no])->get()->getResultArray();
                    if (!empty($from_caveat_party)) {
                        foreach ($from_caveat_party as $caveat_party_row) {
                            $data_addon_party = [
                                'caveat_no' => $caveatno,
                                'usercode' => $ucode,
                                'ent_dt' => date("Y-m-d H:i:s"),
                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            unset($caveat_party_row['caveat_no']);unset($caveat_party_row['usercode']); unset($caveat_party_row['ent_dt']);
                            unset($caveat_party_row['create_modify']);unset($caveat_party_row['updated_by']);unset($caveat_party_row['updated_by_ip']);
                            $final_array_caveat_party = array_merge($data_addon_party, $caveat_party_row);
                            $rs3 = insert('caveat_party', $final_array_caveat_party);
                        }
                    }
                        if ($rs3) {
                            $from_caveat_advocate = $this->Model_caveat_advocate->select('*')->where(['caveat_no' => $cav_no])->get()->getResultArray();
                            if (!empty($from_caveat_advocate)) {
                                foreach ($from_caveat_advocate as $advocate_row) {
                                    $data_addon_caveat_advocate = [
                                        'caveat_no' => $caveatno,
                                        'usercode' => $ucode,
                                        'ent_dt' => date("Y-m-d H:i:s"),
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    unset($advocate_row['caveat_no']); unset($advocate_row['usercode']); unset($advocate_row['ent_dt']);
                                    unset($advocate_row['create_modify']);unset($advocate_row['updated_by']);unset($advocate_row['updated_by_ip']);
                                    $final_array_caveat_advocate = array_merge($data_addon_caveat_advocate, $advocate_row);
                                    $rs4 = insert('caveat_advocate', $final_array_caveat_advocate);
                                }
                            }


                            if ($rs4) {
                                $from_caveat_lowerctNumRows = $this->Model_caveat_lowerct->select('*')->where(['caveat_no' => $cav_no])->get();
                                $from_caveat_lowerct = $from_caveat_lowerctNumRows->getResultArray();
                                if (!empty($from_caveat_lowerct)) {
                                    foreach ($from_caveat_lowerct as $caveat_lowerct_row) {
                                        $data_addon_caveat_lowerct = [
                                            'caveat_no' => $caveatno,
                                            'usercode' => $ucode,
                                            'ent_dt' => date("Y-m-d H:i:s"),
                                            'create_modify' => date("Y-m-d H:i:s"),
                                            'updated_by' => session()->get('login')['usercode'],
                                            'updated_by_ip' => getClientIP(),
                                        ];
                                        unset($caveat_lowerct_row['lower_court_id']);
                                        unset($caveat_lowerct_row['caveat_no']); unset($caveat_lowerct_row['usercode']);unset($caveat_lowerct_row['ent_dt']);
                                        unset($caveat_lowerct_row['create_modify']);unset($caveat_lowerct_row['updated_by']);unset($caveat_lowerct_row['updated_by_ip']);
                                        $final_array_caveat_lowerct = array_merge($data_addon_caveat_lowerct, $caveat_lowerct_row);
                                        $rs5 = insert('caveat_lowerct', $final_array_caveat_lowerct);
                                    }
                                }
                                if ($rs5 || $from_caveat_lowerctNumRows->getNumRows() <= 0) {
                                    // update counter for caveat no.;
                                    $data_update_cnt_caveat = [
                                        'max_caveat_no' => $fil,
                                        'caveat_year' => $year,
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_update_cnt_caveat = update('master.cnt_caveat', $data_update_cnt_caveat, ['caveat_year' => $year]);

                                    $data_update_caveat = [
                                        'is_renew' => 'Y',
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_update_caveat = update('caveat', $data_update_caveat, ['caveat_no' => $cav_no]);

                                    $data_insert_renewed_caveat = [
                                        'old_caveat_no' => $cav_no,
                                        'new_caveat_no' => $caveatno,
                                        'renew_user' => $ucode,
                                        'renew_date' => date("Y-m-d H:i:s"),
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_insert_renewed_caveat = insert('renewed_caveat', $data_insert_renewed_caveat);

                                    if ($is_insert_renewed_caveat) {
                                       // echo "Caveat No. " . $caveat_no;
                                        $insert_renewed_caveat="Caveat Number. ".$caveat_no;
                                    } else {
                                       // echo "Error!!. Contact Server Room.";
                                        $ContactServerRoom='Contact Server Room.';
                                    }

                                } else {
                                    $ContactServerRoom='Contact Server Room.';
                                    $data_update_caveat_lowerct = [
                                        'lw_display' => 'Y',
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_update_caveat_lowerct = update('caveat_lowerct', $data_update_caveat_lowerct, ['caveat_no' => $caveatno]);
                                    $data_update_caveat_advocate = [
                                        'display' => 'N',
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_update_caveat_advocate = update('caveat_advocate', $data_update_caveat_advocate, ['caveat_no' => $caveatno]);
                                    $data_update_caveat_party = [
                                        'pflag' => 'T',
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_update_caveat_party = update('caveat_party', $data_update_caveat_party, ['caveat_no' => $caveatno]);
                                    $is_deleted_caveat = delete('caveat', ['caveat_no' => $caveatno]);
                                }
                            } else {

                                $ContactServerRoom='Contact Server Room.';
                                $data_update_caveat_advocate = [
                                    'display' => 'N',
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $is_update_caveat_advocate = update('caveat_advocate', $data_update_caveat_advocate, ['caveat_no'=>$caveatno]);
                                $data_update_caveat_party = [
                                    'pflag' =>'T',
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $is_update_caveat_party=update('caveat_party',$data_update_caveat_party,['caveat_no'=>$caveatno]);
                                $is_deleted_caveat=delete('caveat',['caveat_no'=>$caveatno]);

                            }
                        } else {

                            $ContactServerRoom='Contact Server Room.';
                            $data_update_caveat_party = [
                                'pflag' =>'T',
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $is_update_caveat_party=update('caveat_party',$data_update_caveat_party,['caveat_no'=>$caveatno]);
                            $is_deleted_caveat=delete('caveat',['caveat_no'=>$caveatno]);

                        }

                } else {

                    $ContactServerRoom='Contact Server Room.';
                    $data_update_caveat_lowerct = [
                        'lw_display' => 'Y',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_caveat_lowerct=update('caveat_lowerct',$data_update_caveat_lowerct,['caveat_no'=>$caveatno]);
                    $data_update_caveat_advocate = [
                        'display' => 'N',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_caveat_advocate=update('caveat_advocate',$data_update_caveat_advocate,['caveat_no'=>$caveatno]);
                    $data_update_caveat_party = [
                        'pflag' =>'T',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_caveat_party=update('caveat_party',$data_update_caveat_party,['caveat_no'=>$caveatno]);
                    $is_deleted_caveat=delete('caveat',['caveat_no'=>$caveatno]);
                }

            $this->db->transComplete();

            if (!empty($insert_renewed_caveat)){
                echo '1@@@<spna class="text-success">'.$insert_renewed_caveat.' Renew Successfully</spna>';exit();
            }else if(!empty($ContactServerRoom)) {
                echo '3@@@<spna class="text-danger">'.$ContactServerRoom.'</span>';exit();
            }else{
                echo '1@@@<spna class="text-success">Caveat No.'.$caveat_no.' Renew Successfully</spna>';exit();
            }
            /*
            if($this->db->transStatus() === FALSE)
                return FALSE;
            else
                return TRUE ;*/
            //all part of done after end point
            exit();

        }

    }
}
