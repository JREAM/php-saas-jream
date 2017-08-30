<?php

$path = 'app/controllers/api';

$dir = new DirectoryIterator($path);
    $files = array();

    $totalFiles = 0;
    $cont = 0;
    foreach ($dir as $file){
        if ($file->isFile()){
            $info = pathinfo($file->getBaseName());
            $file = str_ireplace('controller', '', $info['filename']);
            $file = strtolower($file);
            echo $file . PHP_EOL;
            //echo $info['filename'];
            //echo $file->getBaseName();
        }
    }
