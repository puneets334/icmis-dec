<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pool Cases</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="pool_cases">
        <!-- Table for displaying cases -->
        <table border="1" width="100%" id="cases_table" class="display" cellspacing="0">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Diary / Reg. / Tag</th>
                    <th>Proposed Date / Head</th>
                    <th>Sub Head</th>
                    <th>Sub. Category</th>
                    <th>Purpose of Listing</th>
                    <th>Before/ Not Before Judge</th>
                    <th>DA/Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be appended here by AJAX -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function(){
            $.ajax({
                url: "<?= base_url('Listing/Transfer/get_records'); ?>",
                method: "POST",
                data: {
                    // Add the form data here, e.g., is_nmd, list_dt, civil_criminal, etc.
                    is_nmd: '0',
                    list_dt: '2024-09-25', // Example
                    civil_criminal: 'C'
                },
                dataType: 'json',
                success: function(response) {
                    let tableBody = $('#cases_table tbody');
                    let sno = 1;
                    tableBody.empty();
                    $.each(response, function(index, record) {
                        let row = `<tr>
                            <td>${sno++}</td>
                            <td>${record.diary_no}</td>
                            <td>${record.fil_no}</td>
                            <td>${record.submaster_id}</td>
                            <td>${record.short_description}</td>
                            <td>${record.purpose}</td>
                            <td>${record.board_type}</td>
                            <td>${record.last_updated}</td>
                        </tr>`;
                        tableBody.append(row);
                    });
                }
            });
        });
    </script>
</body>
</html>
