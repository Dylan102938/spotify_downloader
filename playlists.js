var playlists = document.getElementById("playlist_names").value;
playlists = playlists.split("%3B");

var tracks = document.getElementsByClassName("tracks");
tracknames = [];

for (var i = 0; i < tracks.length; i++) {
    tracknames.push(tracks[i].value.split("%3B"));
}

for (var i = 0; i < playlists.length; i++) {
    playlists[i] = decodeURIComponent(playlists[i]);
    for (var j = 0; j < tracknames[i].length; j++) {
        tracknames[i][j] = decodeURIComponent(tracknames[i][j]);
    }
}

var inner = "";

for (var j = 0; j < playlists.length; j++) {
    inner += "<div class = 'track-box' style = 'display: none;' id = '" + playlists[j] + "'>";

    for (var i = 0; i < tracknames[j].length; i++) {
        inner += "<button class = 'track' onclick = 'addDelTrack(this)'>" + tracknames[j][i] + "</button><br>";
    }

    inner += "</div>";
}

document.getElementById("songs").innerHTML += "<div id = 'track-wrapper'>"+inner+"</div>";

$('.playlist_button').click(function() {
    var buttons = $('.playlist_button');
    for (var i = 0; i < buttons.length; i++) {
        $(buttons[i]).removeClass('playlist_active');
    }

    $(this).addClass('playlist_active');

    var playlist_name = $(this).html();
    for (var i = 0; i < playlists.length; i++) {
        document.getElementById(playlists[i]).style.display = "none";
    }

    console.log(document.getElementById(playlist_name).style.display);
    document.getElementById(playlist_name).style.display = "block";
    console.log(document.getElementById(playlist_name).style.display);

    document.getElementById("songs").innerHTML = "<h1>Select Songs to Download</h1>" +
        "<label class='container'><h1>Select All Songs</h1>" +
        "<input type='checkbox'>" +
        "<span class='checkmark'></span>" +
        "</label>" +
        "<div id = 'track-wrapper'>"+ document.getElementById('track-wrapper').innerHTML +"</div>";
});

var songsToDownload = [];

function addDelTrack(elmt) {
    if (!elmt.classList.contains("track_active")) {
        elmt.classList.add("track_active");
        songsToDownload.push(elmt.innerHTML);

    }

    else {
        elmt.classList.remove("track_active");
        for (var i = 0; i < songsToDownload.length; i++) {
            if (songsToDownload[i] === elmt.innerHTML) {
                songsToDownload.splice(i, 1);
            }
        }
    }

    document.getElementById("download_songs").innerHTML = "Download Songs (" + songsToDownload.length + ")";

    var inner = "";
    for (var i = 0; i < songsToDownload.length; i++) {
        inner += "<button class = 'track'>" + songsToDownload[i] + "</button><br>";
    }

    document.getElementById("tent_songs").innerHTML = inner;
}

$("#download_songs").click(function() {
    $("#playlists").animate({
        left: '-50%'
    }, 500, function() {
        $("#playlists").css('left', '150%');
    });

    $("#songs").animate({
        right: '-50%'
    }, 500, function() {
        $("#songs").css('right', '150%');
    });

    $("#songs").promise().done(function() {
        $("#download_confirm").fadeIn(400);
    });
});


$("#confirm_download").click(function() {
    $.ajax({
        type: 'POST',
        url: 'download.php',
        data: {songs: songsToDownload},
        success: function(response) {
            // console.log(response);
            // const a = document.createElement("a");
            // a.style.display = "none";
            // document.body.appendChild(a);
            // a.href = window.URL.createObjectURL(
            //     new Blob([response], {type: 'octet/stream'})
            // );
            // console.log(a);
            // a.setAttribute("download", "yeet.zip");
            // a.click();
            // window.URL.revokeObjectURL(a.href);
            // document.body.removeChild(a);
            window.location = response;
        }
    });
});