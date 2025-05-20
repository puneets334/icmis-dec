<?php
function get_diary_case_type($ct,$cn,$cy)
{
    if($ct != ''){
    $get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy FROM main WHERE (SUBSTRING_INDEX(fil_no, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2),'-',-1)) AND (SUBSTRING_INDEX(fil_no, '-', -1)) AND  if((reg_year_mh=0 OR DATE(fil_dt)>DATE('2017-05-10')), YEAR(fil_dt)=$cy, reg_year_mh=$cy) ) or (SUBSTRING_INDEX(fil_no_fh, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2),'-',-1)) AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) AND if(reg_year_fh=0, YEAR(fil_dt_fh)=$cy, reg_year_fh=$cy))";

        $get_dno = mysql_query($get_dno) or die(__LINE__.'->'.mysql_error());
        if(mysql_affected_rows()>0){
            $get_dno = mysql_fetch_array($get_dno);
            return $get_dno['dn'].$get_dno['dy'];
        }
        else 
        {
            $get_dno ="SELECT SUBSTR( h.diary_no, 1, LENGTH( h.diary_no ) -4 ) AS dn, SUBSTR( h.diary_no , -4 ) AS dy, if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', 1),'') as ct1, if(h.new_registration_number!='',SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1 ),'') as crf1,  if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', -1),'') as crl1 FROM main_casetype_history h WHERE ((SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1)) AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1)) AND h.new_registration_year=$cy) OR (SUBSTRING_INDEX(h.old_registration_number, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN (  SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', - 1 ) ) AND (SUBSTRING_INDEX(h.old_registration_number, '-', - 1)) AND h.old_registration_year = $cy)) AND h.is_deleted='f'";
    
            $get_dno = mysql_query($get_dno) or die(__LINE__.'->'.mysql_error());
            if(mysql_affected_rows()>0){
                $get_dno = mysql_fetch_array($get_dno);
                return $get_dno['dn'].$get_dno['dy'];
            }
        }
    }
    return;
}
?>

