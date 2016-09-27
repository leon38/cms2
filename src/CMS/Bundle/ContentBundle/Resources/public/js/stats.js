/**
 * Created by DCA on 10/08/2016.
 */
var ctx;
$(document).ready(function() {
    $.ajax({
        url: Routing.generate('admin_content_stats_type'),
        dataType: 'json',
        success: function (result) {
            var data = {
                labels: result.labels,
                datasets: [
                    {
                        data: result.data,
                        backgroundColor: [
                            "#0b272d",
                            "#5a9696",
                            "#e0e8e5",
                            "#d6e4ba",
                            "#bbdb93"
                        ],
                        hoverBackgroundColor: [
                            "#0b272d",
                            "#5a9696",
                            "#e0e8e5",
                            "#d6e4ba",
                            "#bbdb93"
                        ]
                    }]
            };
            new Chart($("#doughnut"), {
                type: 'doughnut',
                data: data,
                animation:{
                    animateScale:true
                }
            });
        }
    });


    $.ajax({
        url: Routing.generate('admin_content_stats_content'),
        dataType: 'json',
        success: function (result) {
            var data = {
                labels: result.labels,
                datasets: [
                    {
                        label: "Nombre d'article par mois",
                        fill: false,
                        borderColor: "#5a9696",
                        backgroundColor: "#5a9696",
                        borderWidth: 1,
                        data: result.data
                    }],
            };
            new Chart($('#line'), {
                type: 'line',
                data: data,
                animation:{
                    animateScale:true
                },
                options: {
                    scales: {
                        yAxes: [{
                           ticks: {
                               beginAtZero: true
                           }
                        }]
                    }
                }
            });
        }
    });
});