<?php
    require 'vendor/autoload.php';

    $session = new SpotifyWebAPI\Session(
        'c8f143ccac9f4f4fb851de80728f3fd5',
        '7e82a884919e4bcea230f8b62f23266c',
        'http://localhost/spotify_download/playlists.php'
    );

    $session->requestAccessToken($_GET['code']);

    $accessToken = $session->getAccessToken();
    $refreshToken = $session->getRefreshToken();

    $api = new SpotifyWebAPI\SpotifyWebAPI();
    $api->setAccessToken($accessToken);

    $me = $api->me();
    $user_id = $me->id;

    $playlists = $api->getUserPlaylists($user_id, ['limit'=>50]);
    $playlist_names = [];
    $playlist_id = [];
    $playlist_tracks = [];
    $playlist_names_button = "";
    $playlist_names_string = "";
    $playlist_tracks_names_string = "";

    foreach ($playlists->items as $playlist) {
        array_push($playlist_names, $playlist->name);
        array_push($playlist_id, $playlist->id);
        $playlist_names_button .= "<button class = 'playlist_button'>".$playlist->name."</button>";
        $playlist_names_string .= rawurlencode($playlist->name.";");
    }

    $playlist_names_string = substr($playlist_names_string, 0, strlen($playlist_names_string) - 3);

    for ($i = 0; $i < sizeof($playlist_id); $i++) {
        $tracks = $api->getPlaylistTracks($playlist_id[$i]);
        $temp_array = [];
        $playlist_tracks_names_string .= "<input type = 'hidden' class = 'tracks' id = '".$playlist_names[$i]."_hidden' value = '";
        foreach ($tracks->items as $track) {
            $full_name = $track->track->album->artists[0]->name . " - " . $track->track->name ;
            array_push($temp_array, $full_name);
            $playlist_tracks_names_string .= rawurlencode($full_name.";");
        }
        array_push($playlist_tracks, $temp_array);
        $playlist_tracks_names_string = substr($playlist_tracks_names_string, 0,
            strlen($playlist_tracks_names_string) - 3);
        $playlist_tracks_names_string .= "'>";
    }

?>

<html>
    <head>
        <title>My Playlists</title>
        <link rel = "stylesheet" href = "playlists.css">
        <link href="https://fonts.googleapis.com/css?family=Catamaran:200,300,400|Roboto+Mono&display=swap" rel="stylesheet">
    </head>

    <body>
        <input type = "hidden" id = "playlist_names" value = "<?php echo $playlist_names_string ?>">
        <?php echo $playlist_tracks_names_string ?>

        <div id = "download_confirm">
            <h1>Songs to Download</h1>
            <div id = "tent_songs"></div>
            <button id = "cancel">Cancel</button>
            <button id = "confirm_download">Confirm Download</button>
        </div>

        <div id = "playlists">
            <h1>My Playlists</h1>
            <?php echo $playlist_names_button; ?>
            <button id = "download_songs">Download Songs</button>
        </div>

        <div id = "songs">
        </div>
        <script
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
        <script src = "playlists.js"></script>
    </body>

</html>
