/**
 * Created by DCA on 30/11/2016.
 */

var blue = "#11A0F8";
var purple = "#7460EE";
var red = "#F9334B";
var yellow = "#FFB508";
var green = "#7ACE4C";
var grey = "#A0B2B9";

var data_randos = {
    labels: labels_randos,
    datasets: [
        {
            label: " ",
            fill: false,
            backgroundColor: green,
            borderWidth: 1,
            borderColor: green,
            data: data_deniveles
        }
    ]
};
$(document).ready(function() {
    new Chart($('#randos'), {
        type: 'line',
        data: data_randos,
        animation:{
            animateScale:true
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
})