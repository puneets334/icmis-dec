<?php
include("../../includes/db_inc.php");
include("../common/function.php");
session_start();

/*$sql = "
select a.*, r.* from (select group_concat(distinct j.abbreviation order by j.judge_seniority) old_coram,
c.next_dt, c.entry_date, c.roster_id, r.m_f, r.courtno, count(distinct c.diary_no) cases, count(c.diary_no) aor_entry
from consent_through_email c
inner join roster r on r.id = c.roster_id
left join roster_judge rj on rj.roster_id = r.id
left join judge j on j.jcode = rj.judge_id
where c.next_dt = '2021-11-23' and r.display != 'Y'
group by c.roster_id) a
left join roster r on r.courtno = a.courtno and a.next_dt = r.from_date and date(a.entry_date) <= r.entry_dt
and a.m_f = r.m_f
where r.display = 'Y'";*/

$output = '';
if(isset($_POST['listing_dts']) && !empty($_POST['listing_dts']) ){
    $listing_dts = date('Y-m-d',strtotime(trim($_POST['listing_dts'])));
    $currentDate = date('Y-m-d');


    $sql = "select c.roster_id old_roster_id, r1.courtno oldcourt,
group_concat(distinct j1.jname order by j1.judge_seniority separator ', ') old_coram,
 h.roster_id new_roster_id, 
group_concat(distinct j2.jname order by j2.judge_seniority separator ', ') new_coram,
r2.courtno newcourt, h.mainhead, h.board_type,
count(distinct c.diary_no) total_cases, count(distinct c.party_id, c.advocate_id) total_concent
from consent_through_email c
inner join heardt h on c.diary_no = h.diary_no and c.next_dt = h.next_dt 
inner join roster r1 on r1.id = c.roster_id
inner join roster_judge rj1 on rj1.roster_id = r1.id
inner join judge j1 on j1.jcode = rj1.judge_id
inner join roster r2 on r2.id = h.roster_id
inner join roster_judge rj2 on rj2.roster_id = r2.id
inner join judge j2 on j2.jcode = rj2.judge_id
where c.next_dt = '$listing_dts' and h.roster_id != c.roster_id
and c.is_deleted is null group by c.roster_id";
    $res = mysql_query($sql) or die(mysql_error());
    ?>
    <div class="row col-sm-12">
        <div class="col-sm-8">
            <h4>TRANSER ENTRY OF CONSENT CASES FROM ONE COURT TO ANOTHER COURT FOR LISTING DATE <?=date('d-m-Y',strtotime(trim($_POST['listing_dts'])));?></h4>
        </div>
    </div>

    <?php
    if(mysql_num_rows($res) > 0) {
        $sno = 1;
        ?>
        <table class="table table-responsive">
            <thead>
            <tr>
                <th style="width: 5%">
                    SNo.
                </th>
                <th style="width: 40%">
                    From Previous Court
                </th>
                <th style="width: 5%">
                    Total Cases
                </th>
                <th style="width: 5%">
                    Total Consents
                </th>
                <th style="width: 40%">To New Court Details</th>
                <th style="width: 5%">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sno = 1;
            while ($row = mysql_fetch_array($res)) {
                $list_type = "";
                        if($row['mainhead'] == 'F'){
                            $list_type = "<br><span class='text-primary'>Regular List</span>";
                        }
                        else if($row['mainhead'] == 'M' && $row['board_type'] == 'J'){
                            $list_type = "<br><span class='text-primary'>Misc. List</span>";
                        }
                        else if($row['mainhead'] == 'M' && $row['board_type'] == 'C'){
                            $list_type = "<br><span class='text-primary'>Chamber List</span>";
                        }
                        else if($row['mainhead'] == 'M' && $row['board_type'] == 'R'){
                            $list_type = "<br><span class='text-primary'>Registrar List</span>";
                        }
                        ?>
                <tr>
                    <td>
                        <?=$sno++?>
                    </td>
                    <td>
                        <?=$row['old_coram'].$list_type?>
                    </td>
                    <td>
                        <?=$row['total_cases']?>
                    </td>
                    <td>
                        <?=$row['total_concent']?>
                    </td>
                    <td>
                        <?=$row['new_coram'].$list_type?>

                    </td>
                    <td id="transfer_result_<?=$row['old_roster_id']?>">
                        <button data-next_dt="<?= $listing_dts ?>"
                                data-old_roster_id="<?= $row['old_roster_id'] ?>"
                                data-new_roster_id="<?= $row['new_roster_id'] ?>"
                                data-action="save"
                                class="btn btn-secondary btn-block btn_transfer" type="button" name="btn_transfer" id="btn_transfer_<?=$row['old_roster_id'] ?>">Transfer</button>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    else{
        echo "No Records Found";
    }
    //  }
}
else{
    echo "Please select mandatory fields";
}
?>
<style>
    .aorDataDiv{
        border: 2px solid #ead5d5;
    }
    .ppDataDiv{
        border: 2px solid #078e7b;
    }
</style>