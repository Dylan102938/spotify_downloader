<?php
    require 'vendor/autoload.php';

    use YoutubeDl\YoutubeDl;
    use YoutubeDl\Exception\CopyrightException;
    use YoutubeDl\Exception\NotFoundException;
    use YoutubeDl\Exception\PrivateVideoException;

    if (isset($_POST['songs'])) {

        for ($i = 0; $i < sizeof($_POST['songs']); $i++) {
            $song = $_POST['songs'][$i] . " lyric video";
            $correctString  = str_replace(" ","+", $song);
            $youtubeUrl = "https://www.youtube.com/results?search_query=". $correctString . "&sp=EgIQAQ%253D%253D";
            $getHTML = file_get_contents($youtubeUrl);
            $pattern = '/<a href="\/watch\?v=(.*?)"/i';

            if(preg_match($pattern, $getHTML, $match)) {
                $videoID = $match[1];

            }

            else {
                echo "Something went wrong!";
                exit;
            }
        }
    }
    // $songs = $_POST['songs'] . " lyric video";
    $songs = ["MAX - Lights Down Low", "Rixton - Speakerphone", "Cheat Codes - Feels Great (feat. Fetty Wap & CVBZ)",
        "KYLE - Hey Julie! (feat. Lil Yachty)", "NF - Time", "Kid Quill - A Song to Sing", "B.O.B. - Is it War"];

    $dl = new YoutubeDl([
        'continue' => true, // force resume of partially downloaded files. By default, youtube-dl will resume downloads if possible.
        'format' => 'bestvideo',
    ]);

    for ($i = 0; $i < sizeof($songs); $i++) {
        $correctString  = str_replace(" ","+", $songs[$i] . " lyric video");
        $youtubeUrl = "https://www.youtube.com/results?search_query=" . $correctString . "&sp=EgIQAQ%253D%253D";
        $getHTML = file_get_contents($youtubeUrl);
        $pattern = '/<a href="\/watch\?v=(.*?)"/i';

        if(preg_match($pattern, $getHTML, $match)) {
            $videoID = $match[1];

        }
}
?>