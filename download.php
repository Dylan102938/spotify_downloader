<?php
    if (isset($_POST['songs'])) {
        $files = glob('songs/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        for ($i = 0; $i < sizeof($_POST['songs']); $i++) {
            $song = $_POST['songs'][$i] . " lyric video";
            $correctString = str_replace(" ", "+", $song);
            $youtubeUrl = "https://www.youtube.com/results?search_query=" . $correctString . "&sp=EgIQAQ%253D%253D";
            $getHTML = file_get_contents($youtubeUrl);
            $pattern = '/<a href="\/watch\?v=(.*?)"/i';

            if (preg_match($pattern, $getHTML, $match)) {
                $videoID = $match[1];
                $video = "https://youtube.com/watch?v=" . $videoID;
                $dir = getcwd();
                $cmd = './youtube-dl --extract-audio --audio-format mp3 -o "' . $dir . '/songs/%(title)s.%(ext)s" ' . $video . ' --ffmpeg-location ./ffmpeg';
                $output = shell_exec($cmd . " 2>&1");
            } else {
                echo "Something went wrong!";
                exit;
            }
        }

        $zip = new ZipArchive();
        $filename = "./songs.zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }

        $dir = 'songs/';

        // Create zip
        if (is_dir($dir)){

            if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){

                    // If file
                    if (is_file($dir.$file)) {
                        if($file != '' && $file != '.' && $file != '..'){
                            $zip->addFile($dir.$file);
                        }
                    }

                }
                closedir($dh);
            }
        }

        $zip->close();

        echo $filename;
    }
?>