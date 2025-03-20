<?php
if(!empty($flag))
{
    if($flag === 'c_lt')
    {
 ?>
<div id="mess_display_page" align='center' style='color: red;'>
    <h4>Can't generate office report because cause list not yet printed. </h4>
</div>
        <?php
    }elseif($flag === 'c_up')
    {
        ?>
        <div id="mess_display_page" align='center' style='color: red;'>
            <h4>Can't generate fresh office report because case is not yet updated or listed in court. </h4>
        </div>
        <?php
    }elseif($flag === 'da')
    {
    ?>
<div id="mess_display_page" align='center' style='color: red;'>
    <h4>Only Concerned Dealing Assistant can upload Office Report !!!! </h4>
</div>

    <?php
    }elseif($flag === 'da_not')
    {
    ?>
<div id="mess_display_page" align='center' style='color: red;'>
    <h4>DA not found in matter.. Office Report can not be generated. Please Update DA in matter</h4>
</div>
<?php
    }
}
?>
