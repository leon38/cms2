/**
 * Created by DCA on 04/08/2016.
 */
function getWeather() {
    var ville = document.getElementById("content_fieldValuesTemp_meteo_ville").value;
    console.log(ville);
    $.ajax({
        url: Routing.generate("weather_ajax", {'city': ville}),
        dataType: 'json',
        success: function(data) {
            var result = data.query.results.channel.item.condition;
            var celsius = Math.ceil(fahrenheitToCelsius(result.temp));
            $('.weather').html('<img src="/bundles/content/img/simple_weather_icon_'+result.code+'.png"><br />'+celsius+'°C '+result.text);
        }
    })
}

function fahrenheitToCelsius(temp) {
    return (temp - 32) * (5/9);
}