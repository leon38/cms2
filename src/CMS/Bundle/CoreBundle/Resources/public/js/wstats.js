/**
 * Created by DCA on 24/11/2016.
 */
var blue = "#11A0F8";
var purple = "#7460EE";
var red = "#F9334B";
var yellow = "#FFB508";
var green = "#7ACE4C";
var grey = "#A0B2B9";

var data = {
    labels: labels_poids,
    datasets: [
        {
            label: " ",
            fill: false,
            borderColor: blue,
            backgroundColor: blue,
            borderWidth: 2,
            data: data_poids,
            pointBackgroundColor: "rgba(0,0,0,0)",
            pointBorderWidth: 0
        }]
};
new Chart($('#line_poids'), {
    type: 'line',
    data: data,
    animation:{
        animateScale:true
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: false
                }
            }]
        },
        elements: {
            point : {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 10
            }
        }
    }
});

var data = {
    labels: labels_activity,
    datasets: [
        {
            label: " ",
            fill: false,
            borderColor: [
                blue,
                purple,
                red,
                yellow,
                green,
                grey
            ],
            backgroundColor: [
                blue,
                purple,
                red,
                yellow,
                green,
                grey
            ],
            borderWidth: 2,
            data: data_steps,
            pointBackgroundColor: "rgba(0,0,0,0)",
            pointBorderWidth: 0
        }
        // },
        // {
        //     label: "Calories",
        //     fill: false,
        //     borderColor: purple,
        //     backgroundColor: purple,
        //     borderWidth: 2,
        //     data: data_calories,
        //     pointBackgroundColor: "rgba(0,0,0,0)",
        //     pointBorderWidth: 0
        // }
    ]
};
new Chart($('#line_activity'), {
    type: 'bar',
    data: data,
    animation:{
        animateScale:true
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: false
                }
            }],
            xAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        elements: {
            point : {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 10
            }
        }
    }
});