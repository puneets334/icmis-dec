$(document).ready(function () {
    $(document).on('click', '#btn_sensetive', function () {
        get_report();
    });
    $(document).on("click", "#prnnt1", function () {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
});
function get_report() {
    $.ajax({
        url: 'get_sensitive_cases.php',
        cache: false,
        async: true,
        // data: {d_no: t_h_cno, d_yr: t_h_cyt},
        beforeSend: function () {
            $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function (data, status) {
            $('#div_result').html(data);
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}