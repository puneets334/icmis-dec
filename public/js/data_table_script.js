    $(function () {
    $(".query_builder_report").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,"sType" : "num,percent",
        "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
            { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
    }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

});

