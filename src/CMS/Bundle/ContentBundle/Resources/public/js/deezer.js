/**
 * Created by DCA on 11/08/2016.
 */
/**
 * Created by DCA on 08/08/2016.
 */
var activeSong;
var activeSongJ;
$(document).ready(function() {
    var artist = $('#tc_bundle_contentbundle_content_fieldValuesTemp_musique_artist').val();
    console.log(artist);
    if (artist != '') {
        search(artist);
    }
});



function playPause(id){
    //Sets the active song since one of the functions could be play.
    activeSong = document.getElementById(id);
    activeSongJ = $('#'+id);

    //Checks to see if the song is paused, if it is, play it from where it left off otherwise pause it.
    if (activeSong.paused){
        $(activeSongJ.data('target')).addClass('active');
        $(activeSongJ.data('target')).find('.play-pause').find('.fa').removeClass('fa-pause').addClass('fa-play');
        activeSong.play();
    }else{
        $(activeSongJ.data('target')).removeClass('active');
        $(activeSongJ.data('target')).find('.play-pause').find('.fa').removeClass('fa-play').addClass('fa-pause');
        activeSong.pause();
    }
}

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
    if (document.getElementById("tc_bundle_contentbundle_content_fieldValuesTemp_musique_artist") !== null &&query != '')
        query = document.getElementById("tc_bundle_contentbundle_content_fieldValuesTemp_musique_artist").value;

    if (query != '') {
        $.ajax({
            url: Routing.generate('deezer_search_artist', {query: query}),
            success: function (response) {
                $('.music').html(response);
            }
        });
    }
}