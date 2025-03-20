$(function () {
    new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
    new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('pie'));
});

function getChartJs(type) {
    var config = null;

   if (type === 'bar') {
        config = {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    data: [65, 59, 80, 81, 56, 55, 40],
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
                }, {
                        label: "My Second dataset",
                        data: [28, 48, 40, 19, 86, 27, 90],
                        backgroundColor: 'rgba(233, 30, 99, 0.8)'
                    }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
   if (type === 'pie') {
       var data_rogy = $("#pie_chart").data("rogy");
        config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: data_rogy,
                    backgroundColor: [
                        "rgb(255, 0, 0)",
                        "rgb(255, 165, 0)",
                        "rgb(255, 255, 0)",
                        "rgb(0, 128, 0)"
                    ],
                }],
                labels: [
                    "Red",
                    "Orange",
                    "Yellow",
                    "Green"
                ]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
    return config;
}