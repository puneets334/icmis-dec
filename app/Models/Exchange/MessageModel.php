<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function get_rec()
    {
        $q = $_REQUEST['q'];
        $q = str_replace(['%', '_'], ['\%', '\_'], $q); // Escape % and _
        $q = $this->db->escapeString($q); // Escape for SQL injection

        $sql_string = "SELECT DISTINCT
        u.empid AS id, u.empid || ' # ' || u.name || ' # ' || ut.type_name || ' # ' || us.section_name AS name
        FROM master.users u
        LEFT OUTER JOIN master.usertype ut ON u.usertype = ut.id
        LEFT OUTER JOIN master.usersection us ON u.section = us.id
        WHERE u.empid NOT IN (3032, 3719, 1) 
        AND u.display = 'Y'
        AND
        (
            u.name ILIKE '%$q%' 
            OR (u.empid::text = '$q' AND '$q' ~ '^[0-9]+$')  -- Compare after confirming $q is numeric
        )
        ORDER BY id ASC";

        $query = $this->db->query($sql_string);
        $results = $query->getResultArray();
        $arr = [];
        foreach ($results as $obj) {
            if (!empty($obj['id']) && !empty($obj['name'])) {
                $arr[] = $obj;
            }
        }

        $json_response = json_encode($arr);

        if (isset($_REQUEST['callback'])) {
            $json_response = $_REQUEST['callback'] . "($json_response)";
        }

        return $json_response;
    }

    public function send_msg($mess_to_arr, $msg_frm, $msg, $hd_ipadd)
    {
        for ($index = 0; $index < count($mess_to_arr); $index++) {
            $msg = urldecode($msg);

            $data = [
                'to_user' => trim($mess_to_arr[$index]),
                'from_user' => $msg_frm,
                'msg' => $msg,
                'ipadd' => $hd_ipadd,
                'time' => date('Y-m-d H:i:s')
            ];


            $this->db->table('msg')->insert($data);
        }


        $message = "Message sent successfully...";
        echo "<div style='text-align: center; color: green;'>$message</div>";
    }

    public function inbox_pro($q, $dtp)
    {
        $usercode = session()->get('login')['usercode'];

        $db = \Config\Database::connect();
        $builder = $db->table('msg m')
            ->select('m.*, ut.type_name, u.*, us.section_name')
            ->join('master.users u', 'm.from_user = CAST(u.empid AS TEXT)', 'left')
            ->join('master.usertype ut', 'u.usertype = ut.id', 'left')
            ->join('master.usersection us', 'u.section = us.id', 'left')
            ->where('m.to_user', $usercode)
            ->where('m.display', 'Y')
            ->where('m.trash', 'N');

        if ($q == 'P') {
            $builder->where('DATE(m.time)', $dtp);
        }

        $builder->orderBy('m.seen', 'DESC')
            ->orderBy('m.time', 'DESC');

        $query = $builder->get();

        $html = '';
        if ($query->getNumRows() == 0) {
            $html = '<div align="center"><strong>No Record Found</strong></div>';
        } else {
            $result = $query->getResultArray();
            // Further processing of $result if needed


            if ($q == 'all') {
                $updateData =
                    [
                        'r_unr' => 0
                    ];

                $this->db->table('msg')->where('to_user', $usercode)->where('display', 'Y')->where('trash', 'N')->update($updateData);
            } else if ($q == 'P') {
                $startOfDay = $dtp . ' 00:00:00+00'; // UTC start of the day
                $endOfDay = $dtp . ' 23:59:59+00'; // UTC end of the day
                $updateData =
                    [
                        'r_unr' => 0
                    ];

                $this->db->table('msg')->where('to_user', $usercode)->where('display', 'Y')->where('trash', 'N')->where('time >=', $startOfDay)->where('time <=', $endOfDay)->update($updateData);
            }


            // Start of the table
            $html .= '<table align="center" class="table_tr_th_w_clr tbl_border table-striped table-hover" style="width:100%; table-layout:fixed; border:solid thin;">';
            $html .= '<col width="5%" /><col width="20%" /><col width="50%" /><col width="25%" />';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>S.No</th>';
            $html .= '<th>From</th>';
            $html .= '<th>Message</th>';
            $html .= '<th>Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $SN = 1;
            foreach ($result as $key => $val) {
                $z2 = date('j M Y, G:i:s', strtotime($val['time']));
                $html .= '<tr>';
                $html .= '<td>' . $SN . '</td>';
                $html .= '<td><span id="spno_' . $val['id'] . '">' . $val['name'] . '<br /><font color="#006600">' . $val['type_name'] . ' (<b>' . $val['section_name'] . '</b>)</font><br />' . $z2 . '</span></td>';
                $html .= '<td>' . $val['msg'] . '</td>';
                $html .= '<td>';

                // Actions based on message seen status
                if ($val['seen'] == 'N') {
                    $html .= '<input type="button" value="Mark as Seen" id="seen_' . $val['id'] . '" onclick="seen(' . $val['id'] . ')" />';
                } else {
                    $html .= '<span style="color: #01AFF8">&#x2714;&#x2714;</span>'; // Seen indicator
                }

                // Delete and Reply buttons
                $html .= '<input type="button" value="Delete" onclick="savethis(' . $val['id'] . ');" />';
                $html .= '<input type="button" value="Reply" onclick="replythis(' . $val['from_user'] . ');" />';

                // Popup for reply
               xkground-color: whitesmoke;">';
                $html .= '<table class="table_tr_th_w_clr tbl_border" style="width:400px; height: 400px;">';
                $html .= '<tr><td colspan="2"><span id="reply_str" style="display:none;"></span></td></tr>';
                $html .= '<tr><td colspan="2"><textarea id="replytext" style="width: 400px; height: 200px;"></textarea></td></tr>';
                $html .= '<tr><td><input type="button" id="reply_btn" value="Send" onclick="send_reply()" /></td>';
                $html .= '<td><input type="button" value="Close" onclick="popup(\'popUpDiv\')" /></td></tr>';
                $html .= '</table>';
                $html .= '</div>'; // End of popup

                $html .= '</td>';
                $html .= '</tr>';
                $SN++;
            }

            $html .= '</tbody>';
            $html .= '</table><div id="hint"></div>'; // End of the table
        }

        return $html;
    }

    public function seen($id)
    {
        $updateData =
            [
                'seen' => 'Y',
                'seen_time' => date('Y-m-d H:i:s')
            ];

        $this->db->table('msg')->where('id', $id)->update($updateData);
    }

    public function savethis($id)
    {
        $updateData =
            [
                'display' => 'N',
                'trash' => 'Y'
            ];

        $this->db->table('msg')->where('id', $id)->update($updateData);
    }

    public function reply()
    {
        $reply_str = $_REQUEST['reply_str'];
        $replytext = $_REQUEST['replytext'];
        $msg_frm = session()->get('login')['usercode'];
        $hd_ipadd = !empty($_REQUEST['hd_ipadd']) ? $_REQUEST['hd_ipadd'] : '';

        $data = [
            'to_user' => trim($reply_str),
            'from_user' => $msg_frm,
            'msg' => $replytext,
            'ipadd' => $hd_ipadd
        ];

        $this->db->table('msg')->insert($data);
        $message = "Message sent successfully...";
        return $message;
    }

    public function trash_pro($q, $dtp)
    {
        $usercode = session()->get('login')['usercode'];
        $db = \Config\Database::connect();

        if ($q == 'all') {
            $subQuery1 = $db->table('msg m')
                ->select('m.id, m.to_user AS tu, m.from_user AS fu, m.msg, m.time, u.name AS us_1')
                ->join('master.users u', 'CAST(u.empid AS TEXT) = m.to_user', 'inner')
                ->where('m.from_user', $usercode)
                ->where('m.display2', 'N')
                ->where('m.trash2', 'Y');

            $subQuery2 = $db->table('msg m')
                ->select('m.id, m.to_user AS tu, m.from_user AS fu, m.msg, m.time, u.name AS us_1')
                ->join('master.users u', 'CAST(u.empid AS TEXT) = m.to_user', 'inner')
                ->where('m.to_user', $usercode)
                ->where('m.display', 'N')
                ->where('m.trash', 'Y');

            $query = $db->table("({$subQuery1->getCompiledSelect()} UNION {$subQuery2->getCompiledSelect()}) as rr")
                ->select('rr.*, ur.name AS un')
                ->join('master.users ur', 'CAST(ur.empid AS TEXT) = rr.fu', 'inner')
                ->orderBy('rr.time', 'DESC')
                ->get();
        } else if ($q == 'P') {
            $subQuery1 = $db->table('msg m')
                ->select('m.id, m.to_user AS tu, m.from_user AS fu, m.msg, m.time')
                ->join('master.users u', 'CAST(u.empid AS TEXT) = m.to_user', 'inner')
                ->where('m.from_user', $usercode)
                ->where('m.display2', 'N')
                ->where('m.trash2', 'Y');

            $subQuery2 = $db->table('msg m')
                ->select('m.id, m.to_user AS tu, m.from_user AS fu, m.msg, m.time')
                ->join('master.users u', 'CAST(u.empid AS TEXT) = m.to_user', 'inner')
                ->where('m.to_user', $usercode)
                ->where('m.display', 'N')
                ->where('m.trash', 'Y');

            $query = $db->table("({$subQuery1->getCompiledSelect()} UNION {$subQuery2->getCompiledSelect()}) as combined")
                ->select('combined.*, ur.name AS un, u.name AS us_1')
                ->join('master.users ur', 'CAST(ur.empid AS TEXT) = combined.fu', 'inner')
                ->join('master.users u', 'CAST(u.empid AS TEXT) = combined.tu', 'inner')
                ->where('DATE(combined.time)', $dtp)
                ->orderBy('combined.time', 'DESC')
                ->get();
        }

        $html = '';
        if ($query->getNumRows() == 0) {
            $html = '<div align="center"><strong>No Record Found</strong></div>';
        } else {
            $result = $query->getResultArray();
            $html .= '<table class="table_tr_th_w_clr tbl_border table-striped table-hover myTable" align="center" style="width:100%; table-layout:fixed; border:solid thin;">';
            $html .= '<col width="5%" /><col width="20%" /><col width="20%" /><col width="55%" />';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>S.No</th>';
            $html .= '<th>From</th>';
            $html .= '<th>To</th>';
            $html .= '<th>Message</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $SN = 1;
            foreach ($result as $key => $val) {
                $z2 = date('j M Y, G:i:s', strtotime($val['time']));
                $html .= '<tr>';
                $html .= '<td>' . $SN . '</td>';
                $html .= '<td><span id="spno_' . $val['id'] . '">' . $val['un'] . '<br />' . $z2 . '</span></td>';
                $html .= '<td><span id="spno_' . $val['id'] . '">' . $val['us_1'] . '<br />' . $z2 . '</span></td>';
                $html .= '<td style="word-wrap:break-word;">' . $val['msg'] . '</td>';
                $html .= '</tr>';
                $SN++;
            }

            $html .= '</tbody>';
            $html .= '</table><div id="hint"></div>';
        }

        return $html;
    }

    public function sentbox_pro_old($q, $dtp)
    {
        $usercode = session()->get('login')['usercode'];
        $sp = 1;

        if ($q == 'all') {
            $queryString = "SELECT * 
           FROM msg m
           JOIN master.users u ON u.empid::text = m.to_user
           WHERE m.from_user = '$usercode' 
          -- AND to_user='8888' 
           AND m.display2 = 'Y'
            AND m.trash2 = 'N'
           ORDER BY m.time DESC";
        } else if ($q == 'P') {
            $queryString = "SELECT *
           FROM msg m
           JOIN master.users u ON u.empid::text = m.to_user
           WHERE m.from_user = '$usercode'
         -- AND to_user='8888'
           AND m.display2 = 'Y'
           AND m.trash2 = 'N'
           AND DATE(m.time) = '$dtp'
           ORDER BY m.time DESC";
        }

        $query = $this->db->query($queryString);
        $html = '';
        if ($query->getNumRows() < 0 || $query->getNumRows() == 0) {
            $html = '<div align="center"><strong>No Record Found</strong></div>';
        } else {
            $result = $query->getResultArray();

            $html .= '<table align="center" class="table_tr_th_w_clr tbl_border table-striped table-hover" style="width:100%;table-layout:fixed;border:solid;border-width:thin">';
            $html .= '<col width="5" /><col width="20" /><col width="45" /><col width="30" />';
            $html .= '<tr><td>S.No</td><td>To</td><td>Message</td><td></td></tr>';

            $SN = 1;
            foreach ($result as $key => $val) {
                $z2 = date('j M Y, G:i:s', strtotime($val['time']));
                $html .= '<tr>';
                $html .= '<td width="auto">' . $SN . '</td>';
                $html .= '<td width="auto"><span id="spno_' . $val['id'] . '">' . $val['name'] . '</br>' . $z2 . '</span></td>';
                $html .= '<td style="width:auto;word-wrap:break-word;">' . $val['msg'] . '</td>';
                $html .= '<td width="auto">';

                if ($val['seen'] == 'Y') {
                    $html .= '<span style="color: #01AFF8">&#x2714;&#x2714;</span>';
                }
                if ($val['r_unr'] == '1') {
                    $html .= '<span style="color: #01AFF8">&#x2714;</span>';
                }

                $html .= "<input type='button' value='Delete' name='savesingle_$sp' id='savesingle_$sp' onclick='savethis({$val['id']});' />";
                $html .= '</td>';
                $html .= '</tr>';

                $sp++;
                $SN++;
            }
            $html .= '</table><div id="hint"></div>';
        }
        return $html;
    }

    public function sentbox_pro($q, $dtp)
    {
        $usercode = session()->get('login')['usercode'];


        $builder = $this->db->table('msg m')
            ->join('master.users u', 'u.empid = CAST(m.to_user AS INTEGER)', 'inner')
            ->where('m.from_user', $usercode)
            // ->where('m.to_user', '8888')
            ->where('m.display2', 'Y')
            ->where('m.trash2', 'N')
            ->orderBy('m.time', 'DESC');

        if ($q == 'P') {
            $builder->where('DATE(m.time)', $dtp);
        }

        $query = $builder->get();


        if ($query->getNumRows() == 0) {
            return '<div align="center"><strong>No Record Found</strong></div>';
        }

        $result = $query->getResultArray();
      

        $html = '<table align="center" class="table_tr_th_w_clr tbl_border table-striped table-hover" style="width:100%;table-layout:fixed;border:solid;border-width:thin">';
        $html .= '<col width="5" /><col width="20" /><col width="45" /><col width="30" />';
        $html .= '<tr><td>S.No</td><td>To</td><td>Message</td><td></td></tr>';

        $SN = 1;
        foreach ($result as $val) {
            //$z2 = date('j M Y, G:i:s', strtotime($val['time']));
            $z2 = $val['time'];
            $html .= '<tr>';
            $html .= '<td width="auto">' . $SN . '</td>';
            $html .= '<td width="auto"><span id="spno_' . htmlspecialchars($val['id']) . '">'
                . htmlspecialchars($val['name']) . '<br />' . htmlspecialchars($z2) . '</span></td>';
            $html .= '<td style="width:auto;word-wrap:break-word;">' . htmlspecialchars($val['msg']) . '</td>';
            $html .= '<td width="auto">';

            if ($val['seen'] == 'Y') {
                $html .= '<span style="color: #01AFF8">&#x2714;&#x2714;</span>';
            }
            if ($val['r_unr'] == '1') {
                $html .= '<span style="color: #01AFF8">&#x2714;</span>';
            }

            $html .= "<input type='button' value='Delete' onclick='savethis(" . htmlspecialchars($val['id']) . ");' />";
            $html .= '</td></tr>';

            $SN++;
        }

        $html .= '</table><div id="hint"></div>';
        return $html;
    }






    public function sent_save($id)
    {
        $updateData =
            [
                'display2' => 'N',
                'trash2' => 'Y'
            ];

        $this->db->table('msg')->where('id', $id)->update($updateData);
    }
}
