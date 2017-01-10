/**
 * Created by DCA on 10/08/2016.
 */
var blue = "#11A0F8";
var purple = "#7460EE";
var red = "#F9334B";
var yellow = "#FFB508";
var green = "#7ACE4C";
var grey = "#A0B2B9";
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
                            blue,
                            purple,
                            red,
                            yellow,
                            green
                        ],
                        hoverBackgroundColor: [
                            blue,
                            purple,
                            red,
                            yellow,
                            green
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
                        borderColor: blue,
                        backgroundColor: blue,
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