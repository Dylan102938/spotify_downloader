<?php
    require 'vendor/autoload.php';

    if (isset($_POST['submit'])) {
        $session = new SpotifyWebAPI\Session(
            'c8f143ccac9f4f4fb851de80728f3fd5',
            '7e82a884919e4bcea230f8b62f23266c',
            'http://localhost/spotify_download/playlists.php'
        );

        $api = new SpotifyWebAPI\SpotifyWebAPI();

        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());

            print_r($api->me());
        } else {
            $options = [
                'scope' => [
                    'playlist-read-private',
                    'user-read-private'
                ],
            ];

            header('Location: ' . $session->getAuthorizeUrl($options));
            die();
        }
    }

?>

<html>

    <head>
        <title>Spotify Song Downloader</title>
        <link rel = "stylesheet" href = "index.css">
        <link href="https://fonts.googleapis.com/css?family=Catamaran:200,300,400|Roboto+Mono&display=swap" rel="stylesheet">
    </head>

    <body>
        <form method = "post">
            <div id = "wrapper">
                <h1>Spotify Song Downloader</h1>
                <button type = "submit" name = "submit" id = "login">Login to Spotify</button>
            </div>
        </form>
    </body>


</html>
