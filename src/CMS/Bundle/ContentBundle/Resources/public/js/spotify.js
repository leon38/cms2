/**
 * Created by DCA on 08/08/2016.
 */
// find template and compile it
var templateSource = document.getElementsByClassName('spotify')[0].innerHTML,
    resultsPlaceholder = document.getElementById('results'),
    playingCssClass = 'playing',
    audioObject = null;

var fetchTracks = function (albumId, callback) {
    $.ajax({
        url: 'https://api.spotify.com/v1/artists//top-tracks?country=SE' + albumId,
        success: function (response) {
            callback(response);
        }
    });
};

var fetchTopTracks = function (artistId) {
    $.ajax({
        url: 'https://api.spotify.com/v1/artists/'+artistId+'/top-tracks?country=FR',
        success: function (response) {
            var tracks = response.tracks;
            var resultPlaceHolder = document.getElementsByClassName('spotify')[0];
            var result = '<ul class="list-unstyled">';
            for (var i = 0; i < 5; i++) {
                var track = tracks[i];
                console.log(track);
                result += '<li><img src="'+track.album.images[0].url+'" width="64" height="64"> '+track.name+'</li>';
            }
            result += '</ul>';
            $('.spotify').append(result);
        }
    })
}

var searchTopTracks = function (query) {
    $.ajax({
        url: 'https://api.spotify.com/v1/search',
        data: {
            q: query,
            type: 'artist'
        },
        success: function (response) {
           var id_artist = response.artists.items[0].id;
           fetchTopTracks(id_artist);
        }
    });
};



function searchArtist() {
    searchTopTracks(document.getElementById("tc_bundle_contentbundle_content_fieldValuesTemp_spotify_artist").value);
}