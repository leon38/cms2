/**
 * Created by DCA on 11/08/2016.
 */
/**
 * Created by DCA on 08/08/2016.
 */
var activeSong;
var activeSongJ;
$(document).ready(function() {
    var artist = $('#content_fieldValuesTemp_musique_artist').val();
    if (artist != '') {
        search(artist);
    }
});

var playRunner = null;
var percentage = 0;
var id_player = 1;
function go($time, songLength) {
    playRunner = setInterval(function() {
        var $visualizers = $('.player#player-'+id_player+' .visualizer>div');
        //visualizers
        $visualizers.each(function() {
            $(this).css('height', Math.random() * 90 + 10 + '%');
        });
        //progress bar
        percentage += 0.15;
        if (percentage > 100) percentage = 0;
        var $progressBar = $('.player#player-'+id_player+' .progress-bar');
        var $progressBarRunner = $progressBar.find('.runner');
        $progressBarRunner.css('width', percentage + '%');

        $time.text(calculateTime(songLength, percentage));
    }, 250);
};

function playPause(id, songLength) {
    id_player = id;
    var $player = $('.player#player-'+id_player);
    var $time = $('.player#player-'+id_player+' .time');
    $player.toggleClass('paused').toggleClass('playing');
    if (playRunner) {
        clearInterval(playRunner);
        playRunner = null;
        $time.text(calculateTime(songLength, 100));
        document.getElementById('audio-player-'+id_player).pause();
    } else {
        percentage = 0;
        document.getElementById('audio-player-'+id_player).play();
        go($time, songLength, percentage);
    }
}

function actionProgressBar(id) {
    var $progressBar = $('#player-'+id+' .progress-bar');
    var posY = $progressBar.offset().left;
    var clickY = e.pageX - posY;
    var width = $progressBar.width();

    percentage = clickY / width * 100;
}

function calculateTime(songLength, percentage) {
    //time
    var currentLength = songLength / 100 * percentage;
    var minutes = Math.floor(currentLength / 60);
    var seconds = Math.floor(currentLength - (minutes * 60));
    if (seconds <= 9) {
        return (minutes + ':0' + seconds);
    } else {
        return (minutes + ':' + seconds);
    }
}

clearInterval(playRunner);



// function playPause(id){
//     //Sets the active song since one of the functions could be play.
//     activeSong = document.getElementById(id);
//     activeSongJ = $('#'+id);
//
//     //Checks to see if the song is paused, if it is, play it from where it left off otherwise pause it.
//     if (activeSong.paused){
//         $(activeSongJ.data('target')).addClass('active');
//         $(activeSongJ.data('target')).find('.play-pause').find('.fa').removeClass('fa-pause').addClass('fa-play');
//         activeSong.play();
//     }else{
//         $(activeSongJ.data('target')).removeClass('active');
//         $(activeSongJ.data('target')).find('.play-pause').find('.fa').removeClass('fa-play').addClass('fa-pause');
//         activeSong.pause();
//     }
// }

function updateTime(){
    var currentSeconds = (Math.floor(activeSong.currentTime % 60) < 10 ? '0' : '') + Math.floor(activeSong.currentTime % 60);
    var currentMinutes = Math.floor(activeSong.currentTime / 60);

    //Sets the current song location compared to the song duration.
    $(activeSongJ.data('target')).find('.song-time').html(currentMinutes + ":" + currentSeconds + ' / ' + Math.floor(activeSong.duration / 60) + ":" + (Math.floor(activeSong.duration % 60) < 10 ? '0' : '') + Math.floor(activeSong.duration % 60));

    //Fills out the slider with the appropriate position.
    var percentageOfSong = (activeSong.currentTime/activeSong.duration);
    var percentageOfSlider = $(activeSongJ.data('target')).find('.song-slider').offsetWidth * percentageOfSong;

    //Updates the track progress div.
    $(activeSongJ.data('target')).find('.track-progress').css('width', Math.round(percentageOfSlider) + "px");
}


function search(query) {
    if (document.getElementById("content_fieldValuesTemp_musique_artist") !== null && query != '')
        query = document.getElementById("content_fieldValuesTemp_musique_artist").value;

    if (query != '') {
        $.ajax({
            url: Routing.generate('deezer_search_artist', {query: query}),
            success: function (response) {
                $('.music').html(response);
            }
        });
    }
}