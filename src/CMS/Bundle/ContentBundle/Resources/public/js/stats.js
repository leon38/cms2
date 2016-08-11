/**
 * Created by DCA on 10/08/2016.
 */
var ctx;
$(document).ready(function() {
    ctx = $("#doughnut");
    $.ajax({
        url: Routing.generate('admin_content_stats'),
        dataType: 'json',
        success: function (result) {
            var data = {
                labels: result.labels,
                datasets: [
                    {
                        data: result.data,
                        backgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                            "#FFCE56"
                        ],
                        hoverBackgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                            "#FFCE56"
                        ]
                    }]
            };
            new Chart(ctx, {
                type: 'doughnut',
                data: data,
                animation:{
                    animateScale:true
                }
            });
        }
    });
});